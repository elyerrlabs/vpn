<?php

namespace Vpn\App\Models;

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

class Server extends Master
{

    /**
     * Table name
     * @var string
     */
    public $table = "vpn_servers";

    /**
     * Transformer class to output information to the client
     * @var
     */
    public $transformer = ServerTransformer::class;

    public $fillable = [
        'name',
        'ip',
        'url',
        'port',
        'socks_port',
        'proxy_port',
        'user_id',
    ];

    /**
     * Belongs to the user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Server>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Has many wireguard interfaces
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wireguards()
    {
        return $this->hasMany(Wireguard::class);
    }
}
