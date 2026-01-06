<?php

namespace Vpn\App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Vpn\App\Models\Wireguard;
use Vpn\App\Contracts\Service;
use Vpn\App\Traits\IpGenerator;
use Vpn\App\Traits\WireguardGenerator;
use Vpn\App\Repositories\WireguardRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;

/*
 * VPN - Server-side software for centralized administration and node management of a VPN service.
 * Copyright (C) 2025 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

final class WireguardService implements Service
{

    use IpGenerator, WireguardGenerator;

    /**
     * Wireguard repository
     * @var WireguardRepository
     */
    protected $repository;

    public function __construct()
    {
        $this->repository = app(WireguardRepository::class);
    }


    public function search(Request $request)
    {
        $query = $this->repository->query();

        if ($request->filled('slug')) {
            $query->whereRaw('lower(slug) like ?', ['%' . strtolower('slug') . '%']);
        }

        if ($request->filled('server_id')) {
            $query->where('server_id', $request->server_id);
        }

        if ($request->filled('mounted')) {
            $query->where('mounted', $request->mounted);
        }

        if ($request->filled('public')) {
            $query->orWhere('public', "=", $request->public);
        }

        if ($request->filled('user_id')) {
            $query->whereHas(
                'server.user',
                function ($query) use ($request) {
                    $query->where('', '=', $request->user_id);
                }
            );
        }

        return $query;
    }

    /**
     * Search resource for user
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchForUser(Request $request)
    {
        $request->merge([
            'user_id' => auth()->user()->id
        ]);

        return $this->search($request);
    }

    /**
     * Show details resources
     * @param string $id
     * @return Wireguard
     */
    public function details(string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Create new resource
     * @param array $data
     * @return Wireguard
     */
    public function create(array $data)
    {

        $nets = $this->listSubnetForServer($data['server_id']);
        $last_subnet = $nets->latest()->first();
        $network = $this->generateNextSubnet($last_subnet ? $last_subnet->subnet : null);
        $subnet = "{$network['subnet']}/{$network['prefix']}";
        $gateway = "{$network['gateway']}/{$network['prefix']}";

        //Limit to 10 subnets to create by server
        throw_if(
            $nets->count() >= 10,
            new ReportError(__('The limit has been exceeded'), 403)
        );

        // Filter by slug and server
        $exists = $this->repository->findBySlug(
            Str::slug($data['slug']),
            $data['server_id']
        );

        // Deny creation if it the name already exists
        throw_if(
            $exists,
            new ReportError(__('The provided slug is already assigned to the current server'), 403)
        );

        return $this->repository->create([
            'slug' => $data['slug'],
            'subnet' => $subnet,
            'gateway' => $gateway,
            'private_key' => $this->generatePrivKey(),
            'listen_port' => $data['listen_port'],
            'dns' => $data['dns'] ?? null,
            'dns_enabled' => $data['dns_enabled'] ?? false,
            'network_interface' => $data['network_interface'],
            'mounted' => $data['mounted'] ?? false,
            'public' => $data['public'] ?? false,
            'server_id' => $data['server_id']
        ]);
    }

    /**
     * List subnets by server id
     * @param string $server_id
     * @return \Illuminate\Database\Eloquent\Builder<Wireguard>
     */
    public function listSubnetForServer(string $server_id)
    {
        $nets = $this->repository->query();

        // Search the all subnets for this server
        $nets->whereHas(
            'server',
            function ($query) use ($server_id) {
                $query->where('id', $server_id);
            }
        );

        return $nets;
    }

    /**
     * Update resource
     * @param string $id
     * @param array $data
     * @return Wireguard
     */
    public function update(string $id, array $data)
    {
        $model = $this->repository->find(($id));

        if ($model->isDirty('listen_port')) {
            $model->listen_port = $data['listen_port'];
        }

        if ($model->isDirty('dns')) {
            $model->dns = $data['dns'];
        }

        if ($model->isDirty('dns_enabled')) {
            $model->dns_enabled = $data['dns_enabled'];
        }

        if ($model->isDirty('network_interface')) {
            $model->network_interface = $data['network_interface'];
        }

        if ($model->isDirty('mounted')) {
            $model->mounted = $data['mounted'];
        }

        if ($model->isDirty('public')) {
            $model->public = $data['public'];
        }

        $model->push();

        return $model;
    }

    /**
     * Delete resource
     * @param string $id
     * @return Wireguard
     */
    public function delete(string $id)
    {
        $model = $this->repository->find($id);

        if (empty($model)) {
            throw new ReportError(__('Server can not be found'), 404);
        }

        if ($model->peers()->count()) {
            throw new ReportError(__('The Wireguard interface can not be deleted because Peer are associated with it.'), 403);
        }

        $model->delete();

        return $model;
    }

    /**
     * Delete resource
     * @param string $id
     * @return Wireguard
     */
    public function deleteForUser(string $id)
    {
        $model = $this->repository->query()
            ->where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->first();

        if (empty($model)) {
            throw new ReportError(__('Server can not be found'), 404);
        }

        if ($model->peers()->count()) {
            throw new ReportError(__('The Wireguard interface can not be deleted because Peer are associated with it.'), 403);
        }

        $model->delete();

        return $model;
    }
}
