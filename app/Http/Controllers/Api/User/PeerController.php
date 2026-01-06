<?php
namespace Vpn\App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use Vpn\App\Services\PeerService;
use App\Http\Controllers\ApiController;
use Vpn\App\Transformers\User\PeerTransformer;

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

class PeerController extends ApiController
{

    /**
     * Repository
     * @var PeerService
     */
    public $service;

    /**
     * Construct
     * @param PeerService $peerService
     */
    public function __construct(PeerService $peerService)
    {
        parent::__construct();
        $this->service = $peerService;
    }

    /**
     * Show all resources
     * @param \App\Models\Server\Peer $peer
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->service->search($request);

        return $this->showAllByBuilder($data, PeerTransformer::class);
    }

    /**
     * Create a new resource
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:150', 'min:1'],
            'wireguard_id' => ['required', 'exists:vpn_wireguards,id']
        ]);

        $data = $this->service->create($request->toArray());

        return $this->showOne($data, PeerTransformer::class, 201);
    }

    /**
     * On and off the current peer
     * @param \App\Models\Server\Peer $peer
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $data = $this->service->update($id, $request->toArray());

        return $this->showOne($data, PeerTransformer::class);
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $data = $this->service->delete($id);

        return $this->showOne($data, PeerTransformer::class);
    }
}
