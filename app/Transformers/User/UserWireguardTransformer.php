<?php

namespace Vpn\App\Transformers\User;

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;
use Vpn\App\Models\Wireguard;

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

class UserWireguardTransformer extends TransformerAbstract
{
    use Asset;
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Wireguard $wireguard)
    {
        return [
            'id' => $wireguard->id,
            'name' => $wireguard->name,
            'slug' => $wireguard->slug,
            'subnet' => $wireguard->subnet,
            'gateway' => $wireguard->gateway,
            'private_key' => $wireguard->private_key,
            'listen_port' => $wireguard->listen_port,
            'dns' => $wireguard->dns,
            'dns_enabled' => $wireguard->dns_enabled ? true : false,
            'network_interface' => $wireguard->network_interface,
            'mounted' => $wireguard->mounted ? true : false,
            'public' => $wireguard->public ? true : false,
            'server' => [
                'id' => $wireguard->server->id,
                'name' => $wireguard->server->name,
                'url' => $wireguard->server->url,
                'ip' => $wireguard->server->ip,
            ],
            'links' => [
                'index' => route('module.vpn.api.users.wireguard.index'),
                'store' => route('module.vpn.api.users.wireguard.store'),
                'show' => route('module.vpn.api.users.wireguard.show', ['wireguard' => $wireguard->id]),
                'update' => route('module.vpn.api.users.wireguard.update', ['wireguard' => $wireguard->id]),
                'destroy' => route('module.vpn.api.users.wireguard.destroy', ['wireguard' => $wireguard->id]),
                'start' => route('module.vpn.api.users.wireguard.start', ['wireguard' => $wireguard->id]),
                'shutdown' => route('module.vpn.api.users.wireguard.shutdown', ['wireguard' => $wireguard->id]),
            ],
        ];
    }
}
