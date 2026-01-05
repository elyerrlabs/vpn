<?php

namespace Vpn\App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

interface Service
{
    /**
     * Search resource for admin
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search(Request $request);

    /**
     * Search resource for user
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchForUser(Request $request);

    /**
     * Show details resources
     * @param string $id
     * @return Model
     */
    public function details(string $id);

    /**
     * Create new resource
     * @param array $data
     * @return Model
     */
    public function create(array $data);

    /**
     * Update resource
     * @param string $id
     * @param array $data
     * @return Model
     */
    public function update(string $id, array $data);

    /**
     * Delete resource
     * @param string $id
     * @return Model
     */
    public function delete(string $id);
}