<?php

namespace Vpn\App\Services;

use Vpn\App\Wrapper\Core;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Vpn\App\Models\Wireguard;
use Vpn\App\Contracts\Service;
use Illuminate\Support\Facades\DB;
use Elyerr\ApiResponse\Assets\Asset;
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

final class WireguardService extends MasterService
{
    use Asset;

    /**
     * Wireguard repository
     * @var WireguardRepository
     */
    protected $repository;

    public function __construct()
    {
        $this->repository = app(WireguardRepository::class);
    }

    /**
     * Search for admins
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Wireguard>
     */
    public function listWireguardServersForUser(Request $request)
    {
        $query = $this->repository->query();

        $query->whereHas(
            'server',
            function ($query) use ($request) {

                $query->when(
                    $request->filled('internal'),
                    fn ($q) => $q->where('internal', $request->internal)
                );

                $query->where('hidden', false);
                $query->orWhere('user_id', $request->user()->id);
            }
        );

        $query->where('mounted', true);

        $query->when(
            $request->filled('slug'),
            fn ($q) =>  $q->whereRaw('lower(slug) like ?', ['%' . strtolower('slug') . '%'])
        );

        $query->when(
            $request->filled('server_id'),
            fn ($q) => $q->where('server_id', $request->server_id)
        );

        $query->when(
            $request->filled('public'),
            fn ($q) => $q->orWhere('public', "=", $request->public)
        );

        return $query;
    }

    /**
     * Search for admins
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Wireguard>
     */
    public function search(Request $request)
    {
        $query = $this->repository->query()
         ->whereHas(
             'server',
             function ($query) use ($request) {

                 $query->when(
                     $request->filled('internal'),
                     fn ($q) =>
                         $q->where('internal', $request->internal)
                 );

                 $query->when(
                     $request->filled('hidden'),
                     fn ($q) =>
                     $q->where('hidden', $request->hidden)
                 );

             }
         )
         ->when(
             $request->filled('slug'),
             fn ($q) =>
        $q->whereRaw('LOWER(slug) LIKE ?', ['%' . strtolower($request->slug) . '%'])
         )
         ->when(
             $request->filled('server_id'),
             fn ($q) =>
             $q->where('server_id', $request->server_id)
         )
          ->when(
              $request->filled('mounted'),
              fn ($q) =>
             $q->where('mounted', $request->mounted)
          )
          ->when(
              $request->filled('public'),
              fn ($q) =>
             $q->where('public', $request->public)
          )
          ->when(
              $request->filled('user_id'),
              fn ($q) =>
             $q->whereHas(
                 'server.user',
                 fn ($sub) => $sub->where('id', $request->user_id)
             )
          );
        return $query;
    }

    /**
     * Search resource for user
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchForUser(Request $request)
    {
        $query = $this->repository->query()

         ->whereHas(
             'server.user',
             function ($query) use ($request) {
                 $query->where('user_id', '=', $request->user()->id);
             }
         )
            ->when(
                $request->filled('slug'),
                fn ($q) =>
                $q->whereRaw('LOWER(slug) LIKE ?', ['%' . strtolower($request->slug) . '%'])
            )

            ->when(
                $request->filled('server_id'),
                fn ($q) =>
                $q->where('server_id', $request->server_id)
            )

            ->when(
                $request->filled('mounted'),
                fn ($q) =>
                $q->where('mounted', $request->mounted)
            )

            ->when(
                $request->filled('public'),
                fn ($q) =>
                $q->where('public', $request->public)
            );


        return $query;
    }

    /**
     * Show details resources
     * @param string $id
     * @return Wireguard
     */
    public function details(string $id, bool $forUser = false)
    {
        return $this->repository->query()->where('id', $id)
         ->when($forUser, fn ($q) =>
         $q->whereHas(
             'server',
             fn ($sub) =>
                 $sub->where('user_id', request()->user()->id)
         ))->first();
    }

    /**
     * Create new resource
     * @param array $data
     * @param bool $forUser
     * @return Wireguard
     */
    public function create(array $data, bool $forUser = false)
    {
        $nets = $this->listSubnetForServer($data['server_id'], $forUser);

        throw_if($nets->count(), new ReportError(__('Server can not be found'), 404));

        $last_subnet = $nets->latest()->first();
        $network = $this->generateNextSubnet($last_subnet ? $last_subnet->subnet : null);
        $subnet = "{$network['subnet']}/{$network['prefix']}";
        $gateway = "{$network['gateway']}/{$network['prefix']}";

        //Limit to 10 subnets to create by server
        throw_if(
            $nets->count() >= 10,
            new ReportError(__('The limit has been exceeded'), 403)
        );

        // Filter by slug and server
        $exists = $this->repository->findBySlug(
            Str::slug($data['slug']),
            $data['server_id']
        );

        // Deny creation if it the name already exists
        throw_if(
            $exists,
            new ReportError(__('The provided slug is already assigned to the current server'), 403)
        );

        $model = DB::transaction(function () use ($data, $subnet, $gateway) {

            $model = $this->repository->create([
                    'slug' => $data['slug'],
                    'subnet' => $subnet,
                    'gateway' => $gateway,
                    'private_key' => $this->generatePrivKey(),
                    'listen_port' => $data['listen_port'],
                    'mtu' => $data['mtu'] ?? 1420,
                    'dns' => $data['dns'] ?? null,
                    'dns_enabled' => $data['dns_enabled'] ?? false,
                    'network_interface' => $data['network_interface'],
                    'mounted' => $data['mounted'] ?? false,
                    'public' => $data['public'] ?? false,
                    'server_id' => $data['server_id']
                ]);

            $this->grpc(function () use ($model) {

                $this->core($model)->mountInterface(
                    $model->slug,
                    $model->subnet,
                    $model->gateway,
                    $model->private_key,
                    $model->network_interface,
                    $model->listen_port,
                    $model->mtu
                );
            });

            return $model;
        });

        return $model;
    }

    /**
     * List subnets by server id
     * @param string $server_id
     * @param bool $forUser
     * @return \Illuminate\Database\Eloquent\Builder<Wireguard>
     */
    public function listSubnetForServer(string $server_id, bool $forUser = false)
    {
        $nets = $this->repository->query();

        // Search the all subnets for this server
        $nets->whereHas(
            'server',
            function ($query) use ($server_id, $forUser) {
                $query->where('id', $server_id);
                // Only for users
                $query->when($forUser, fn ($q) => $q->where('user_id', request()->user()->id));
            }
        );

        return $nets;
    }

    /**
     * Update server
     * @param string $id
     * @param array $data
     * @param bool $forUser
     * @return TValue|Wireguard|null
     */
    public function update(string $id, array $data, bool $forUser = false)
    {
        $model = $this->repository->query()->where('id', $id)
        ->when(
            $forUser,
            fn ($q) =>
            $q->whereHas(
                'server',
                fn ($subq) =>
                $subq->where('user_id', request()->user()->id)
            )
        ) ->first();

        throw_if(
            !$forUser &&  !$model->server->internal,
            new ReportError(__('This server is provided by a third party and cannot be updated.'), 403)
        );

        $model->fill($data);
        $model->push();

        if (isset($data['restart']) && $data['restart']) {
            $this->grpc(function () use ($model) {
                $this->core($model)->reloadNetwork($model->slug);
            });
        }

        return $model;
    }

    /**
     * Delete server
     * @param string $id
     * @param bool $forUser
     * @throws ReportError
     * @return TValue|Wireguard|null
     */
    public function delete(string $id, bool $forUser = false)
    {
        $model = $this->repository->query()->where('id', $id)
        ->when(
            $forUser,
            fn ($q) =>  $q->whereHas(
                'server',
                fn ($sub) => $sub->where('user_id', request()->user()->id)
            )
        )->first();

        if (empty($model)) {
            throw new ReportError(__('Server can not be found'), 404);
        }

        if ($model->peers()->count()) {
            throw new ReportError(__('The Wireguard interface can not be deleted because Peer are associated with it.'), 403);
        }

        throw_if(
            $model->mounted,
            new ReportError(__('Unable to delete this resource because is active. Please shutdown and try again.'), 403)
        );

        DB::transaction(function () use ($model) {

            $this->grpc(function () use ($model) {
                $this->core($model)->removeInterface($model->slug);
            });

            $status = $model->delete();

            if ($status) {
                app(PeerRepository::class)->query()->where('wireguard_id', $model->id)->delete();
            }
        });

        return $model;
    }

    /**
     * Shutdown server
     * @param string $id
     * @param bool $forUser
     * @return void
     */
    public function shutdown(string $id, bool $forUser = false)
    {
        $model = $this->repository->query()->where('id', $id)
        ->when($forUser, fn ($q) => $q->whereHas(
            'server',
            fn ($sub) =>  $sub->where('user_id', request()->user()->id)
        ))->first();

        throw_if(empty($model), new ReportError(__('The WireGuard server can not be found'), 404));

        throw_if(!$model->mounted, new ReportError(__('The WireGuard server is already stopped'), 403));

        if ($model->mounted) {
            DB::transaction(function () use ($model) {

                $this->grpc(function () use ($model) {
                    $this->core($model)->shutdownInterface($model->slug);
                });


                // updated peer status
                app(PeerRepository::class)->query()
                ->where('wireguard_id', $model->id)
                ->update(['mounted' => false, 'stand_by' => true]);

                $this->update($model->id, ['mounted' => false]);
            });

        }
    }

    /**
     * Start server
     * @param string $id
     * @param bool $forUser
     * @return void
     */
    public function start(string $id, bool $forUser = false)
    {
        $model = $this->repository->query()->where('id', $id)
        ->when(
            $forUser,
            fn ($q) =>
            $q->whereHas(
                'server',
                fn ($sub) =>
                $sub->where('user_id', request()->user()->id)
            )
        )->first();

        throw_if(empty($model), new ReportError(__('The WireGuard server can not be found'), 404));

        throw_if($model->mounted, new ReportError(__('The WireGuard server is already started'), 403));

        if (!$model->mounted) {
            DB::transaction(function () use ($model) {


                $this->grpc(function () use ($model) {
                    $this->core($model)->startInterface($model->slug);
                });

                app(PeerRepository::class)->query()
                   ->where('wireguard_id', $model->id)
                   ->update(['mounted' => true, 'stand_by' => false]);

                $this->update($model->id, ['mounted' => true]);
            });
        }
    }
}
