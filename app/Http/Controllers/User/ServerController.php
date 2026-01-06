<?php
namespace Vpn\App\Http\Controllers\User;

use Illuminate\Http\Request;
use Vpn\App\Services\ServerService;
use App\Http\Controllers\ApiController;
use Vpn\App\Transformers\User\ServerTransformer;

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
        $this->service = $serverService;
    }

    /**
     * Search server for current user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->service->search($request);

        return $this->showAllByBuilder($data, ServerTransformer::class);
    }
}
