<?php
namespace Vpn\App\Http\Controllers\Web;

use Inertia\Inertia;
use App\Http\Controllers\WebController;

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

final class AdminController extends WebController
{

    public function servers()
    {
        return Inertia::render('Admin/Server/Index', [
            'menus' => resolveInertiaRoutes(config('menus.vpn_routes')),
            'servers' => [
                'index' => route('module.vpn.api.admin.servers.index'),
                'store' => route('module.vpn.api.admin.servers.store')
            ]
        ]);
    }

    public function wireguard()
    {
        return Inertia::render('Admin/Wireguard/Index', [
            'menus' => resolveInertiaRoutes(config('menus.vpn_routes')),
            'wireguard' => [
                'index' => route('module.vpn.api.admin.wireguard.index'),
                'store' => route('module.vpn.api.admin.wireguard.store'),
                'servers' => route('module.vpn.api.admin.servers.index'),
            ]
        ]);
    }
}
