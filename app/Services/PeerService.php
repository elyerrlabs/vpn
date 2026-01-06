<?php

namespace Vpn\App\Services;

use Illuminate\Http\Request;
use Vpn\App\Contracts\Service;
use Vpn\App\Repositories\PeerRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Vpn\App\Repositories\WireguardRepository;

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

final class PeerService extends MasterService implements Service
{
    /**
     * Peer repository
     * @var PeerRepository
     */
    protected $repository;

    public function __construct()
    {
        $this->repository = app(PeerRepository::class);
    }

    /**
     * Search resource for admin
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search(Request $request)
    {
        $query = $this->repository->query();

        if ($request->filled('name')) {
            $query->whereRaw('lower(name) like ?', ['%' . strtolower($request->name) . '%']);
        }

        if ($request->filled('wireguard_id')) {
            $query->where('wireguard_id', '=', $request->wireguard_id);
        }

        if ($request->filled('mounted')) {
            $query->where('mounted', '=', $request->mounted);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', '=', $request->user_id);
        }

        if ($request->filled('server_id')) {
            $query->whereHas(
                'wireguard.server',
                function ($query) use ($request) {
                    $query->where('id', '=', $request->server_id);
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
            'user_id' => request()->user()->id
        ]);

        return $this->search($request);
    }

    /**
     * Show details resources
     * @param string $id
     * @return \Vpn\App\Models\Peer
     */
    public function details(string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Create new resource
     * @param array $data
     * @return \Vpn\App\Models\Peer
     */
    public function create(array $data)
    {
        /**
         * Retrieve the user
         */
        $user = request()->user();

        //---------check plans --------------------------//
        if (!app()->environment(['local', 'dev'])) {
            //user access
            $this->verifyPlan($user);
        }

        //Retrieve Wireguard server
        $wireguard_server = app(WireguardRepository::class)
            ->query()
            ->where('id', $data['wireguard_id'])
            ->first();

        //Generate pair keys to the client
        $keys = $this->generatePairKeys();

        //Preshared key to the client
        $preshared_key = $this->generatePresharedkey();
        $dns = $wireguard_server->dns_enabled ? $wireguard_server->dns : null;

        //Generate new random ip
        $ip_allowed = $this->generateRandomIp($wireguard_server->subnet);

        //Create new peer
        $model = $this->repository->create([
            'name' => $data['name'],
            'public_key' => $keys['public_key'],
            'preshared_key' => $preshared_key,
            'allowed_ips' => $ip_allowed,
            'persistent_keepalive' => 25,
            'mtu' => 1420,
            'user_id' => $user->id,
            'wireguard_id' => $wireguard_server->id,
            'mounted' => true
        ]);

        /**
         * Create peer configuration
         */
        $config[] = "[Interface]";
        $config[] = "PrivateKey = {$keys['private_key']}";
        /**
         * Note: The 'ListenPort' directive has been commented out because it is not supported on some platforms.
         * Certain systems do not allow explicitly setting this parameter in the WireGuard configuration.
         * Therefore, it is omitted to ensure broader compatibility.
         */
        // $config[] = "ListenPort = {$wireguard_server->listen_port}";

        $config[] = "Address =  {$ip_allowed}/32";
        if ($wireguard_server->dns_enabled) {
            $config[] = "DNS =  {$dns}";
        }
        $config[] = "";
        $config[] = "[Peer]";
        $config[] = "PublicKey = {$this->generatePubKey($wireguard_server->private_key)}";
        $config[] = "Endpoint = {$wireguard_server->server->ip}:{$model->listen_port}";
        $config[] = "AllowedIPs = 0.0.0.0/0, ::/0";
        $config[] = "PresharedKey = {$preshared_key}";
        $config[] = "MTU = {$model->mtu}";
        $config[] = "PersistentKeepalive = {$model->persistent_keepalive}";

        // Add configuration to the model
        $model->config = implode("\n", $config);

        return $model;
    }

    /**
     * Update resource
     * @param string $id
     * @param array $data
     * @return \Vpn\App\Models\Peer
     */
    public function update(string $id, array $data)
    {
        $model = $this->repository->query()
            ->where('user_id', request()->user()->id)
            ->where('id', $id)
            ->first();

        if (empty($model)) {
            throw new ReportError(__("The peer can not be found"), 404);

        }

        if ($model->isDirty('mounted')) {
            $model->mounted = $data['mounted'];
        }

        return $model;
    }


    /**
     * Delete resource
     * @param string $id
     * @return \Vpn\App\Models\Peer
     */
    public function delete(string $id)
    {
        $model = $this->repository->find($id);

        if (empty($model)) {
            throw new ReportError(__("The peer can not be found"), 404);

        }

        $model->delete();

        return $model;
    }

    /**
     * Delete resource
     * @param string $id
     * @return \Vpn\App\Models\Peer
     */
    public function deleteForUser(string $id)
    {
        $model = $this->repository->query()
            ->where('user_id', request()->user()->id)
            ->where('id', $id)
            ->first();

        if (empty($model)) {
            throw new ReportError(__("The peer can not be found"), 404);

        }

        $model->delete();

        return $model;
    }
}
