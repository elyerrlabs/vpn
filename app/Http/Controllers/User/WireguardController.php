<?php

namespace Vpn\App\Http\Controllers\User;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Vpn\App\Services\WireguardService;
use Vpn\App\Transformers\User\UserWireguardTransformer;

final class WireguardController extends WebController
{
    /**
     * Construct
     * @param WireguardService $wireguardService
     */
    public function __construct(protected WireguardService $wireguardService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:vpn:full,enterprise:vpn-servers:professional,enterprise:vpn-servers:advanced,enterprise:vpn-servers:intermediate,enterprise:vpn-servers:basic');
    }

    /**
     * Show Wireguard user interface
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $data = $data = $this->wireguardService->searchForUser($request)->paginate($request->input('per_page', 15));

        return Inertia::render('User/Wireguard/Index', [
            'data' => $this->transformCollection($data, UserWireguardTransformer::class),
            'menus' => resolveInertiaRoutes(config('menus.vpn_user_routes')),
            'routes' => [
                'wireguard' => route('module.vpn.web.users.wireguards.index'),
            ],
            'api' => [
                'servers' => route('module.vpn.api.users.servers.index')
            ]
        ]);
    }

    /**
     * Wireguard
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

        $this->wireguardService->create($request->toArray(), true);

        return redirect()->route('module.vpn.web.users.wireguards.index')->with('status', __('Wireguard server created successfully'));
    }

    /**
     * Update
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $this->wireguardService->update($id, $request->toArray(), true);

        return redirect()->route('module.vpn.web.users.wireguards.index')->with('status', __('Wireguard server updated successfully'));
    }

    /**
     * Destroy
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->wireguardService->delete($id, true);

        return redirect()->route('module.vpn.web.users.wireguards.index')->with('status', __('Wireguard server deleted successfully'));
    }

    /**
     * Shutdown
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function shutdown(string $id)
    {
        $this->wireguardService->shutdown($id, true);

        return redirect()->route('module.vpn.web.users.wireguards.index')->with('status', __('Wireguard server shutdown successfully'));
    }

    /**
     * Start
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start(string $id)
    {
        $this->wireguardService->start($id, true);

        return redirect()->route('module.vpn.web.users.wireguards.index')->with('status', __('Wireguard server started successfully'));
    }
}
