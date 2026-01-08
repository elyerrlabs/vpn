<?php

namespace Vpn\App\Services;

use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Vpn\App\Contracts\Service;
use Vpn\App\Repositories\ServerRepository;

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

final class ServerService implements Service
{

    /**
     * Repository
     * @var ServerRepository
     */
    protected $repository;


    public function __construct()
    {
        $this->repository = app(ServerRepository::class);
    }

    /**
     * Search for admin
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\Vpn\App\Models\Server>
     */
    public function search(Request $request)
    {
        $query = $this->repository->query();

        if ($request->filled('hidden')) {
            $query->where('hidden', $request->hidden);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user()->id);
        }

        if ($request->filled('internal')) {
            $query->where('internal', '=', $request->internal);
        }

        if ($request->filled('name')) {
            $query->whereRaw('lower(name) like ?', ['%' . strtolower($request->name) . '%']);
        }

        if ($request->filled('url')) {
            $query->whereRaw('lower(url) like ?', ['%' . strtolower($request->url) . '%']);
        }

        return $query;
    }

    /**
     * Search for user 
     * @param Request $request
     */
    public function searchForUser(Request $request)
    {
        $query = $this->repository->query();

        $query->where('user_id', $request->user()->id);
        $query->where('internal', '=', false);

        if ($request->filled('hidden')) {
            $query->where('hidden', $request->hidden);
        }

        if ($request->filled('name')) {
            $query->whereRaw('lower(name) like ?', ['%' . strtolower($request->name) . '%']);
        }

        if ($request->filled('url')) {
            $query->whereRaw('lower(url) like ?', ['%' . strtolower($request->url) . '%']);
        }

        return $query;
    }

    /**
     * Create new server
     * @param array $data
     * @return \Vpn\App\Models\Server
     */
    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    /**
     * Update server
     * @param string $id
     * @param array $data
     * @throws ReportError
     * @return \Vpn\App\Models\Server
     */
    public function update(string $id, array $data)
    {
        $model = $this->repository->find($id);

        if (empty($model)) {
            throw new ReportError(__('Server can not be found'), 404);
        }

        if ($model->isDirty('name')) {
            $model->name = $data['name'];
        }

        if ($model->isDirty('ip') && $model->wireguards()->count() == 0) {
            $model->name = $data['ip'];
        }

        if ($model->isDirty('port')) {
            $model->name = $data['port'];
        }

        if ($model->isDirty('socks_port')) {
            $model->name = $data['socks_port'];
        }

        if ($model->isDirty('proxy_port')) {
            $model->name = $data['proxy_port'];
        }

        if ($model->isDirty('internal')) {
            $model->name = $data['internal'];
        }

        $model->push();

        return $model;
    }

    /**
     * Server details
     * @param string $id
     * @return \Vpn\App\Models\Server
     */
    public function details(string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Delete resource
     * @param string $id
     * @throws ReportError
     * @return \Vpn\App\Models\Server
     */
    public function delete(string $id)
    {
        $model = $this->repository->find($id);

        if ($model->wireguards()->count()) {
            throw new ReportError(__("his server cannot be deleted because WireGuard interfaces are associated with it."), 403);
        }

        $model->delete();

        return $model;
    }

}
