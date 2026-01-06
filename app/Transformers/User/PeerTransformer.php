<?php

namespace Vpn\App\Transformers\User;

use Vpn\App\Models\Peer;
use League\Fractal\TransformerAbstract;

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

class PeerTransformer extends TransformerAbstract
{
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
    public function transform(Peer $peer)
    {

        return [
            'id' => $peer->id,
            'name' => $peer->name,
            'public_key' => $peer->public_key,
            'preshared_key' => $peer->preshared_key,
            'allowed_ips' => $peer->allowed_ips,
            'persistent_keepalive' => $peer->persistent_keepalive,
            'mtu' => $peer->mtu,
            'mounted' => $peer->mounted,
            'user_id' => $peer->user_id,
            'config' => $peer->config ?: null,
            'wireguard' => [
                'name' => $peer->wireguard->slug,
                'listen_port' => $peer->wireguard->listen_port,
                'server_name' => $peer->wireguard->server->name,
            ],
            'links' => [
                'index' => route('module.vpn.api.users.peers.index'),
                'store' => route('module.vpn.api.users.peers.store'),
                'update' => route('module.vpn.api.users.peers.update', ['peer' => $peer->id]),
                'destroy' => route('module.vpn.api.users.peers.destroy', ['peer' => $peer->id]),
            ],
        ];
    }

    /**
     * Retrieve Original Attributes to filter data
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'name' => 'name',
            'public_key' => 'public_key',
            'preshared_key' => 'preshared_key',
            'allowed_ips' => 'allowed_ips',
            'persistent_keepalive' => 'persisten_keepalive',
            'endpoint' => 'endpoint',
            'active' => 'active',
            'created' => 'created_at',
            'updated' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;

    }
}
