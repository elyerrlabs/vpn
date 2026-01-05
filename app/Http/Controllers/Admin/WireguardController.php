<?php
namespace Vpn\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Vpn\App\Services\WireguardService;
use Vpn\App\Transformers\Admin\WireguardTransformer;

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

class WireguardController extends \App\Http\Controllers\ApiController
{

    /**
     * Repository
     * @var 
     */
    public $service;

    public function __construct(WireguardService $wireguardService)
    {
        parent::__construct();
        $this->service = $wireguardService;
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:view')->only('index', 'interfaces');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:create')->only('store');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:show')->only('show');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:update')->only('update', 'toggle', 'reload');
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:destroy')->only('destroy');
    }

    /**
     * Index
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->service->search($request);

        return $this->showAllByBuilder($query, WireguardTransformer::class);
    }

    /**
     * Store
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'slug' => ['required', 'max:150', 'min:3'],
            'listen_port' => ['required', 'max:5'],
            'dns' => ['nullable', 'ipv4'],
            'dns_enabled' => ['nullable', 'boolean'],
            'network_interface' => ['required'],
            'mounted' => ['nullable', 'boolean'],
            'public' => ['nullable', 'boolean'],
            'server_id' => ['required', 'exists:vpn_servers,id']
        ]);


        $model = $this->service->create($request->toArray());

        return $this->showOne($model, WireguardTransformer::class, 201);
    }

    /**
     * Show specific resource
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function show(string $id)
    {
        $model = $this->service->details($id);

        return $this->showOne($model, WireguardTransformer::class);
    }

    /**
     * Update specific resource
     * @param \App\Http\Requests\Wireguard\UpdateRequest $request
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function update(Request $request, string $id)
    {
        $model = $this->service->update($id, $request->toArray());

        return $this->showOne($model);
    }

    /**
     * Destroy specific resource
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(string $id)
    {
        $model = $this->service->delete($id);

        return $this->showOne($model, WireguardTransformer::class);
    }
}
