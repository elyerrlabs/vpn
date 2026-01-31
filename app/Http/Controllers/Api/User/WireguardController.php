<?php

namespace Vpn\App\Http\Controllers\Api\User;
 
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; 
use Vpn\App\Services\WireguardService;
use App\Http\Controllers\ApiController; 
use Vpn\App\Transformers\User\WireguardTransformer;
use Vpn\App\Transformers\User\UserWireguardTransformer;

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

class WireguardController extends ApiController
{
    /**
     * Repository
     * @var WireguardService
     */
    public $service;

    /**
     * construct
     * @param WireguardService $wireguardService
     */
    public function __construct(WireguardService $wireguardService)
    {
        parent::__construct();
        $this->service = $wireguardService;
        $this->middleware('scope:administrator:vpn:full,commerce:vpn:advanced,commerce:vpn:intermediate,commerce:vpn:basic');
    }

    /**
     * Index
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listWireguardServersForUser(Request $request)
    {
        $query = $this->service->listWireguardServersForUser($request);

        return $this->showAllByBuilder($query, WireguardTransformer::class);
    }

    /**
     * Show resources for users
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->service->searchForUser($request);

        return $this->showAllByBuilder($data, UserWireguardTransformer::class);
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
            'listen_port' => [
                'required',
                'integer',
                'between:1024,65535',
                Rule::unique('vpn_wireguards')->where(
                    fn($q) =>
                    $q->where('server_id', $request->server_id)
                ),
            ],
            'dns' => ['nullable', 'ipv4'],
            'dns_enabled' => ['nullable', 'boolean'],
            'network_interface' => ['required'],
            'mounted' => ['nullable', 'boolean'],
            'public' => ['nullable', 'boolean'],
            'server_id' => ['required', 'exists:vpn_servers,id']
        ]);

        $model = $this->service->create($request->toArray(), true);

        return $this->showOne($model, UserWireguardTransformer::class, 201);
    }

    /**
     * Show specific resource
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function show(string $id)
    {
        $model = $this->service->details($id, true);

        return $this->showOne($model, UserWireguardTransformer::class);
    }

    /**
     * Update specific resource
     * @param \App\Http\Requests\Wireguard\UpdateRequest $request
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function update(Request $request, string $id)
    {
        $model = $this->service->update($id, $request->toArray(), true);

        return $this->showOne($model, UserWireguardTransformer::class);
    }

    /**
     * Destroy specific resource
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(string $id)
    {
        $model = $this->service->delete($id, true);

        return $this->showOne($model, UserWireguardTransformer::class);
    }

    /**
     * Shutdown the wireguard interface
     * @param string $id
     * @return void
     */
    public function shutdown(string $id)
    {
        $this->service->shutdown($id, true);

        return $this->message(__('The WireGuard server is already stopped'), 200);
    }

    /**
     * Start the wireguard interface
     * @param string $id
     * @return void
     */
    public function start(string $id)
    {
        $this->service->start($id, true);

        return $this->message(__('The WireGuard server has been started'), 200);
    }
}
