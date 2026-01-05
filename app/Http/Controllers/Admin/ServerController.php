<?php
namespace Vpn\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Vpn\App\Services\ServerService;
use Vpn\App\Transformers\Admin\ServerTransformer;

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

class ServerController extends \App\Http\Controllers\ApiController
{
    /**
     * Service
     * @var 
     */
    public $service;

    /**
     * Construct
     * @param ServerService $serverService
     */
    public function __construct(ServerService $serverService)
    {
        parent::__construct();
        $this->service = $serverService;
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:view')->only('index', 'interfaces');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:view')->only('index', 'interfaces');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:create')->only('store');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:show')->only('show');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:update')->only('update', 'toggle');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:destroy')->only('destroy');
    }

    /**
     * index
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->service->search($request);

        return $this->showAllByBuilder($query, ServerTransformer::class);
    }


    /**
     * Create new resource
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:150', 'min:3'],
            'ip' => ['required', 'ipv4', 'unique:vpn_servers,ip'],
            'url' => ['nullable', 'max:100'],
            'port' => ['required', 'max:6'],
            'socks_port' => ['nullable', 'max:6'],
            'proxy_port' => ['nullable', 'max:6'],
        ]);

        $data = $this->service->create($request->toArray());

        return $this->showOne($data, ServerTransformer::class, 201);
    }

    /**
     * Show resource details
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $data = $this->service->details($id);

        return $this->showOne($data, ServerTransformer::class);
    }

    /**
     * Update resource
     * @param Request $request
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'ip' => ['required', 'ipv4', 'unique:vpn_servers,ip,' . $id]
        ]);

        $data = $this->service->update($id, $request->toArray());

        return $this->showOne($data, ServerTransformer::class);
    }

    /**
     * Destroy resource
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $data = $this->service->delete($id);

        return $this->showOne($data);
    }
}
