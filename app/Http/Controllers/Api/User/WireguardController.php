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
     * construct
     * @param WireguardService $wireguardService
     */
    public function __construct(protected WireguardService $wireguardService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:vpn:full,enterprise:vpn-servers:professional,enterprise:vpn-servers:advanced,enterprise:vpn-servers:intermediate,enterprise:vpn-servers:basic')->only('index');
        $this->middleware('scope:administrator:vpn:full,commerce:vpn:professional,commerce:vpn:advanced,commerce:vpn:intermediate,commerce:vpn:basic')->only('listWireguardServersForUser');
    }

    /**
     * List wireguard server available for users
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listWireguardServersForUser(Request $request)
    {
        $query = $this->wireguardService->listWireguardServersForUser($request);

        return $this->showAllByBuilder($query, WireguardTransformer::class);
    }

    /**
     * Show resources belongs to the users
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->wireguardService->searchForUser($request);

        return $this->showAllByBuilder($data, UserWireguardTransformer::class);
    }
}
