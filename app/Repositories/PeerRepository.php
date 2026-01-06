<?php

namespace Vpn\App\Repositories;

use Vpn\App\Contracts\Repository;
use Vpn\App\Models\Peer;

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

final class PeerRepository implements Repository
{

    /**
     * Model
     * @var Peer
     */
    protected $model;

    public function __construct()
    {
        $this->model = app(Peer::class);
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder<Peer>
     */
    public function query()
    {
        $query = $this->model->newQuery();

        $query->with(['wireguard', 'wireguard.server', 'user']);

        return $query;
    }

    /**
     * Add new resource
     * @param array $data
     * @return Peer
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Find resource
     * @param string $id
     * @param array $data
     * @return Peer
     */
    public function find(string $id)
    {
        return $this->model->findOrFail($id);
    }
}
