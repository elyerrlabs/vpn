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

class Peer extends Master
{
    /**
     * Table name
     * @var string
     */
    public $table = "vpn_peers";


    protected $fillable = [
        'name',
        'public_key',
        'preshared_key',
        'allowed_ips',
        'persistent_keepalive', 
        'mounted',
        'stand_by',
        'user_id',
        'wireguard_id',
    ];

    /**
     * Wireguard
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Wireguard, Peer>
     */
    public function wireguard()
    {
        return $this->belongsTo(Wireguard::class);
    }

    /**
     * Belongs to the user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Peer>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
