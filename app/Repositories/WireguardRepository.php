<?php

namespace Vpn\App\Repositories;

use Vpn\App\Contracts\Repository;
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

final class WireguardRepository implements Repository
{

    /**
     * Model
     * @var Wireguard
     */
    protected $model;

    public function __construct()
    {
        $this->model = app(Wireguard::class);
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder<Wireguard>
     */
    public function query()
    {
        $query = $this->model->newQuery();

        $query->with(['server', 'server.user', 'peers']);

        return $query;
    }

    /**
     * Add new resource
     * @param array $data
     * @return Wireguard
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Summary of update
     * @param string $id
     * @param array $data
     * @return Wireguard
     */
    public function update(string $id, array $data)
    {
        $model = $this->find($id);

        $model->update($data);

        return $model;
    }

     /**
     * Delete resource
     * @param string $id
     * @return Wireguard
     */

    /**
     * Find resource
     * @param string $id
     * @param array $data
     * @return Wireguard
     */
    public function find(string $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find slug by server
     * @param string $slug
     * @param string $server_id
     * @return Wireguard|null
     */
    public function findBySlug(string $slug, string $server_id)
    {
        $query = $this->query()->where('slug', '=', $slug);

        $query->whereHas('server', function ($query) use ($server_id) {
            $query->where('id', '=', $server_id);
        });

        return $query->first();
    }
}
