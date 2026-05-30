<?php

namespace Vpn\App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
     * Construct
     * @param WireguardService $wireguardService
     */
    public function __construct(protected WireguardService $wireguardService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:vpn:full,administrator:vpn:view')->only('index');
    }

    /**
     * Index
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->wireguardService->search($request);

        return $this->showAllByBuilder($query, WireguardTransformer::class);
    }
}
