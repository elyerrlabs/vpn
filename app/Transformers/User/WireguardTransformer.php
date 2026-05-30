<?php

namespace Vpn\App\Transformers\User;

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

class WireguardTransformer extends TransformerAbstract
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
    public function transform(Wireguard $wireguard)
    {
        return [
            'id' => $wireguard->id,
            'slug' => $wireguard->slug,
            'country' => "{$wireguard->server->name} - {$wireguard->name}",
            'ip' => $wireguard->server->ip,
            'by' => [
                'name' => $wireguard->server?->user?->name ?? config('app.name'),
                'last_name' => $wireguard->server?->user?->last_name ?? config('app.org_name')
            ],
        ];
    }
}
