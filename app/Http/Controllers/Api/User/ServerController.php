<?php
namespace Vpn\App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use Vpn\App\Services\ServerService;
use App\Http\Controllers\ApiController;
use Vpn\App\Transformers\User\ServerTransformer;
use Vpn\App\Transformers\User\UserServerTransformer;

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

class ServerController extends ApiController
{

    /**
     * Repository
     * @var ServerService
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
        $this->middleware('scope:commerce:administrator:vpn:full,vpn:advanced,commerce:vpn:intermediate,commerce:vpn:basic');
    }

    /**
     * Show the all servers available for connection
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listServers(Request $request)
    {
        $data = $this->service->listServerForUsers($request);

        return $this->showAllByBuilder($data, ServerTransformer::class);
    }

    /**
     * List server belongs to the user
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->service->searchForUser($request);

        return $this->showAllByBuilder($data, UserServerTransformer::class);
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

        $data = $this->service->create([
            'name' => $request->name,
            'ip' => $request->ip,
            'url' => $request->url ?? null,
            'port' => $request->port,
            'proxy_port' => $request->proxy_port ?? 1080,
            'socks_port' => $request->socks_port ?? 1090,
            'user_id' => request()->user()->id,
            'internal' => false,
            'hidden' => $request->hidden ?? false,
        ]);

        return $this->showOne($data, UserServerTransformer::class, 201);
    }

    /**
     * Show resource details
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $data = $this->service->detailsForUser($id);

        return $this->showOne($data, UserServerTransformer::class);
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

        $data = $this->service->updateForUser($id, $request->toArray());

        return $this->showOne($data, UserServerTransformer::class);
    }

    /**
     * Destroy resource
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $data = $this->service->deleteForUser($id);

        return $this->showOne($data, UserServerTransformer::class);
    }
}
