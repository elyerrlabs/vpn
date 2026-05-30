<?php
namespace Vpn\App\Http\Controllers\Admin;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Vpn\App\Services\WireguardService;
use Vpn\App\Transformers\Admin\WireguardTransformer;

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

final class WireguardController extends WebController
{

    /**
     * Construct
     */
    public function __construct(protected WireguardService $wireguardService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:vpn:full,administrator:vpn:view')->only('index');
        $this->middleware('userCanAny:administrator:vpn:full,administrator:vpn:create')->only('store');
        $this->middleware('userCanAny:administrator:vpn:full,administrator:vpn:show')->only('show');
        $this->middleware('userCanAny:administrator:vpn:full,administrator:vpn:update')->only('update', 'shutdown', 'start');
        $this->middleware('userCanAny:administrator:vpn:full,administrator:vpn:destroy')->only('destroy');
    }

    /**
     * Wireguard
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $data = $this->wireguardService->search($request)->paginate($request->input('per_page', 15));

        return Inertia::render('Admin/Wireguard/Index', [
            'data' => $this->transformCollection($data, WireguardTransformer::class),
            'menus' => resolveInertiaRoutes(config('menus.vpn_admin_routes')),
            'routes' => [
                'wireguard' => route('module.vpn.admin.wireguards.index')
            ],
            'api' => [
                'servers' => route('module.vpn.api.admin.servers.index')
            ]
        ]);
    }

    /**
     * Create new store
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:150', 'min:3'],
            'listen_port' => [
                'required',
                'integer',
                'between:1024,65535',
                Rule::unique('vpn_wireguards')->where(
                    fn($q) =>
                    $q->where('server_id', $request->server_id)
                ),
            ],
            'dns' => ['nullable', 'ipv4'],
            'dns_enabled' => ['nullable', 'boolean'],
            'network_interface' => ['required'],
            'mounted' => ['nullable', 'boolean'],
            'public' => ['nullable', 'boolean'],
            'server_id' => ['required', 'exists:vpn_servers,id']
        ]);

        $this->wireguardService->create($request->toArray());

        return redirect()->route('module.vpn.admin.wireguards.index')->with('success', __('Wireguard server created successfully'));
    }


    /**
     * Update 
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $this->wireguardService->update($id, $request->toArray());

        return redirect()->route('module.vpn.admin.wireguards.index')->with('success', __('Wireguard server updatd successfully'));
    }

    /**
     * Destroy 
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->wireguardService->delete($id);

        return redirect()->route('module.vpn.admin.wireguards.index')->with('success', __('Wireguard server updatd successfully'));
    }

    /**
     * Shutdown wireguard server
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function shutdown(string $id)
    {
        $this->wireguardService->shutdown($id);

        return redirect()->route('module.vpn.admin.wireguards.index')->with('success', __('Wireguard server stopped successfully'));
    }

    /**
     * Start wireguard server
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start(string $id)
    {
        $this->wireguardService->start($id);

        return redirect()->route('module.vpn.admin.wireguards.index')->with('success', __('Wireguard server started successfully'));
    }
}
