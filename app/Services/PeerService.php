<?php

namespace Vpn\App\Services;

use Vpn\App\Wrapper\Core;
use Illuminate\Http\Request;
use Vpn\App\Models\Wireguard;
use Vpn\App\Contracts\Service;
use Illuminate\Support\Facades\DB;
use Vpn\App\Repositories\PeerRepository;
use Vpn\App\Repositories\WireguardRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;

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

final class PeerService extends MasterService implements Service
{
    /**
     * Peer repository
     * @var PeerRepository
     */
    protected $repository;

    public function __construct()
    {
        $this->repository = app(PeerRepository::class);
    }

    /**
     * Search resource for admin
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search(Request $request)
    {
        return $this->repository->query()
            ->when(
                $request->filled('name'),
                fn($q) =>
                $q->where('name', 'like', '%' . $request->name . '%')
            )
            ->when(
                $request->filled('wireguard_id'),
                fn($q) =>
                $q->where('wireguard_id', $request->wireguard_id)
            )
            ->when(
                $request->filled('mounted'),
                fn($q) =>
                $q->where('mounted', $request->mounted)
            )
            ->when(
                $request->filled('stand_by'),
                fn($q) =>
                $q->where('stand_by', $request->stand_by)
            )
            ->when(
                $request->filled('user_id'),
                fn($q) =>
                $q->where('user_id', $request->user_id)
            )
            ->when(
                $request->filled('server_id'),
                fn($q) =>
                $q->whereHas(
                    'wireguard.server',
                    fn($s) =>
                    $s->where('id', $request->server_id)
                )
            );
    }


    /**
     * Search resource for user
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchForUser(Request $request)
    {
        $request->merge([
            'user_id' => request()->user()->id
        ]);

        return $this->search($request);
    }

    /**
     * Show details resources
     * @param string $id
     * @return \Vpn\App\Models\Peer
     */
    public function details(string $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Create new resource
     * @param array $data
     * @return \Vpn\App\Models\Peer
     */
    public function create(array $data)
    {

        $model = DB::transaction(function () use ($data) {

            /**
             * Retrieve the user
             */
            $user = request()->user();

            //---------check plans --------------------------//
            if (!app()->environment(['local', 'dev'])) {
                //user access

                // Check the plan is not onwner for this server
                if (!$this->checkServerOwner($data['wireguard_id'])) {
                    $this->verifyVpnPlan($user);
                }
            }

            //Retrieve Wireguard server
            $wireguard_server = app(WireguardRepository::class)
                ->query()
                ->where('id', $data['wireguard_id'])
                ->first();

            //Generate pair keys to the client
            $keys = $this->generatePairKeys();

            //Preshared key to the client
            $preshared_key = $this->generatePresharedkey();
            $dns = $wireguard_server->dns_enabled ? $wireguard_server->dns : null;

            //Generate new random ip
            $ip_allowed = $this->generateRandomIp($wireguard_server->subnet);

            //Create new peer
            $model = $this->repository->create([
                'name' => $data['name'],
                'public_key' => $keys['public_key'],
                'preshared_key' => $preshared_key,
                'allowed_ips' => $ip_allowed,
                'persistent_keepalive' => 25,
                'user_id' => $user->id,
                'wireguard_id' => $wireguard_server->id,
                'mounted' => true
            ]);

            // Mount peer
            $this->core($model->wireguard)->addPeer(
                $user->id,
                $model->name,
                $model->wireguard->slug,
                $model->public_key,
                $model->allowed_ips,
                $model->wireguard->getServer(),
                $model->preshared_key,
                $model->persistent_keepalive
            );

            /**
             * Create peer configuration
             */
            $config[] = "[Interface]";
            $config[] = "PrivateKey = {$keys['private_key']}";
            $config[] = "MTU = {$model->wireguard->mtu}";
            /**
             * Note: The 'ListenPort' directive has been commented out because it is not supported on some platforms.
             * Certain systems do not allow explicitly setting this parameter in the WireGuard configuration.
             * Therefore, it is omitted to ensure broader compatibility.
             */
            // $config[] = "ListenPort = {$wireguard_server->listen_port}";

            $config[] = "Address =  {$ip_allowed}/32";
            if ($wireguard_server->dns_enabled) {
                $config[] = "DNS =  {$dns}";
            }
            $config[] = "";
            $config[] = "[Peer]";
            $config[] = "PublicKey = {$this->generatePubKey($wireguard_server->private_key)}";
            $config[] = "Endpoint = {$wireguard_server->server->ip}:{$wireguard_server->listen_port}";
            $config[] = "AllowedIPs = 0.0.0.0/0, ::/0";
            $config[] = "PresharedKey = {$preshared_key}";
            $config[] = "PersistentKeepalive = {$model->persistent_keepalive}";

            // Add configuration to the model
            $model->config = implode("\n", $config);

            return $model;
        });

        return $model;
    }

    /**
     * Update resource
     * @param string $id
     * @param array $data
     * @return \Vpn\App\Models\Peer
     */
    public function update(string $id, array $data)
    {
        $model = $this->repository->query()
            ->where('user_id', request()->user()->id)
            ->where('id', $id)
            ->first();

        if (empty($model)) {
            throw new ReportError(__("The peer can not be found"), 404);
        }

        $model->fill($data);
        $model->push();

        return $model;
    }


    /**
     * Delete resource
     * @param string $id
     * @return \Vpn\App\Models\Peer
     */
    public function delete(string $id)
    {
        $model = $this->repository->find($id);

        DB::transaction(function () use ($model) {

            if (empty($model)) {
                throw new ReportError(__("The peer can not be found"), 404);
            }

            $this->core($model->wireguard)->deletePeer(
                $model->wireguard->slug,
                $model->public_key
            );

            $model->delete();
        });

        return $model;
    }

    /**
     * Check server owner
     * @param string $wireguard_id
     * @return bool
     */
    public function checkServerOwner(string $wireguard_id)
    {
        $wireguardServer = app(WireguardService::class)->details($wireguard_id, true);

        if (empty($wireguardServer)) {
            return false;
        }

        return true;
    }


    public function start(string $id)
    {
        $model = $this->repository->find($id);

        $this->core($model->wireguard);
        
    }


    public function stop(string $id)
    {

    }
}
