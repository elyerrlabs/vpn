<?php
namespace Vpn\App\Http\Controllers\Admin;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Vpn\App\Services\ServerService;
use Vpn\App\Transformers\Admin\ServerTransformer;

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

final class ServerController extends WebController
{
    /**
     * Construct
     */
    public function __construct(protected ServerService $serverService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:vpn:full,administrator:vpn:view')->only('index');
        $this->middleware('userCanAny:administrator:vpn:full,administrator:vpn:create')->only('store');
        $this->middleware('userCanAny:administrator:vpn:full,administrator:vpn:show')->only('show');
        $this->middleware('userCanAny:administrator:vpn:full,administrator:vpn:update')->only('update');
        $this->middleware('userCanAny:administrator:vpn:full,administrator:vpn:destroy')->only('destroy');

    }

    /**
     * Servers
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $data = $this->serverService->search($request)->paginate($request->input('per_page', 15));

        return Inertia::render('Admin/Server/Index', [
            'data' => transformCollection($data, ServerTransformer::class),
            'menus' => resolveInertiaRoutes(config('menus.vpn_admin_routes')),
            'routes' => [
                'servers' => route('module.vpn.admin.servers.index'),
            ]
        ]);
    }

    /**
     * Create server
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:150', 'min:3'],
            'ip' => ['required', 'ipv4', 'unique:vpn_servers,ip'],
            'url' => ['nullable', 'max:100'],
            'port' => ['required', 'max:6'],
            'socks_port' => ['nullable', 'max:6'],
            'proxy_port' => ['nullable', 'max:6'],
        ]);

        $data = $this->serverService->create([
            'name' => $request->name,
            'ip' => $request->ip,
            'url' => $request->url ?? null,
            'port' => $request->port,
            'proxy_port' => $request->proxy_port ?? 1080,
            'socks_port' => $request->socks_port ?? 1090,
            'hidden' => $request->hidden ?? false,
        ]);

        return redirect()->route('module.vpn.admin.servers.index')->with('status', __('Server created successfully'));
    }

    /**
     * Update server
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'ip' => ['required', 'ipv4', 'unique:vpn_servers,ip,' . $id]
        ]);

        $this->serverService->update($id, $request->toArray());

        return redirect()->route('module.vpn.admin.servers.index')->with('status', __('Server updated successfully'));
    }

    /**
     * Destroy
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->serverService->delete($id);

        return redirect()->route('module.vpn.admin.servers.index')->with('status', __('Server deleted successfully'));
    }
}
