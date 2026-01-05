<?php

namespace Vpn\App\Repositories;

use Vpn\App\Contracts\Repository;
use Vpn\App\Models\Server;

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

final class ServerRepository implements Repository
{

    /**
     * Model
     * @var Server
     */
    protected $model;

    public function __construct()
    {
        $this->model = app(Server::class);
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder<Server>
     */
    public function query()
    {
        $query = $this->model->newQuery();

        $query->with(['wireguards', 'user']);

        return $query;
    }

    /**
     * Add new resource
     * @param array $data
     * @return Server
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Find resource
     * @param string $id
     * @param array $data
     * @return Server
     */
    public function find(string $id)
    {
        return $this->model->findOrFail($id);
    }
}

