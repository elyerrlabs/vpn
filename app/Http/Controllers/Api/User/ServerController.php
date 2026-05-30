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
     * Construct
     * @param ServerService $serverService
     */
    public function __construct(protected ServerService $serverService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:vpn:full,enterprise:vpn-servers:professional,enterprise:vpn-servers:advanced,enterprise:vpn-servers:intermediate,enterprise:vpn-servers:basic')->only('index');
        $this->middleware('scope:administrator:vpn:full,commerce:vpn:professional,commerce:vpn:advanced,commerce:vpn:intermediate,commerce:vpn:basic')->only('listServers');
    }

    /**
     * Show the all servers available for connection
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listServers(Request $request)
    {
        $data = $this->serverService->listServerForUsers($request);

        return $this->showAllByBuilder($data, ServerTransformer::class);
    }

    /**
     * List server belongs to the user
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->serverService->searchForUser($request);

        return $this->showAllByBuilder($data, UserServerTransformer::class);
    }
}
