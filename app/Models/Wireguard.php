<?php

namespace Vpn\App\Models;

use Illuminate\Support\Str;

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

class Wireguard extends Master
{
    //

    /**
     * Table name
     * @var string
     */
    public $table = "vpn_wireguards";

    protected $fillable = [
        'slug',
        'subnet',
        'gateway',
        'private_key',
        'listen_port',
        'dns',
        'dns_enabled',
        'network_interface',
        'mounted',
        'mtu',
        'public',
        'server_id'
    ];

    /**
     * Set slug attribute
     * @param mixed $value
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value, '-');
    }

    /**
     * Server
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Server, Wireguard>
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * Peers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Peer, Wireguard>
     */
    public function peers()
    {
        return $this->hasMany(Peer::class);
    }

    /**
     * Get server ip
     * @return string
     */
    public function getServer()
    {
        return "{$this->server->ip}:{$this->listen_port}";
    }
}
