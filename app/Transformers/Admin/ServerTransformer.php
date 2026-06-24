<?php

namespace Vpn\App\Transformers\Admin;

use Vpn\App\Models\Server;
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

class ServerTransformer extends TransformerAbstract
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
    public function transform(Server $server)
    {

        $links = request()->wantsJson() ?
            [
                'index' => route('module.vpn.api.admin.servers.index'),
            ] :
            [
                'index' => route('module.vpn.admin.servers.index'),
                'store' => route('module.vpn.admin.servers.store'),
                'update' => route('module.vpn.admin.servers.update', ['server' => $server->id]),
                'destroy' => route('module.vpn.admin.servers.destroy', ['server' => $server->id]),
            ];

        return [
            'id' => $server->id,
            'name' => $server->name,
            'ip' => $server->ip,
            'port' => $server->port,
            'url' => $server->url,
            'internal' => $server->internal,
            'user' => [
                'id' => $server->user?->id,
                'name' => $server->user?->name,
                'last_name' => $server->user?->last_name,
            ],
            'socks_port' => $server->socks_port ?? 1090,
            'proxy_port' => $server->proxy_port ?? 1080,
            'hidden' => $server->hidden ? true : false,
            'created' => format_date($server->created_at),
            'updated' => format_date($server->updated_at),
            'links' => $links,
        ];
    }
}
