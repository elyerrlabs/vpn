<?php

namespace Vpn\App\Http\Controllers\User;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Vpn\App\Services\ServerService;
use Vpn\App\Transformers\User\UserServerTransformer;

final class ServerController extends WebController
{
    /**
     * construct
     * @param ServerService $serverService
     */
    public function __construct(protected ServerService $serverService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:vpn:full,enterprise:vpn-servers:professional,enterprise:vpn-servers:advanced,enterprise:vpn-servers:intermediate,enterprise:vpn-servers:basic');
    }

    /**
     * List server belongs to the user
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->serverService->searchForUser($request)->paginate($request->input('per_page', 15));

        return Inertia::render('User/Server/Index', [
            'data' => $this->transformCollection($data, UserServerTransformer::class),
            'menus' => resolveInertiaRoutes(config('menus.vpn_user_routes')),
            'routes' => [
                'servers' => route('module.vpn.web.users.servers.index'),
            ]
        ]);
    }

    /**
     * Create new resource
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

        $this->serverService->createForUser($request->toArray());

        return redirect()->route('module.vpn.web.users.servers.index')->with('status', __('Server created successfully'));
    }

    /**
     * Updated
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'ip' => ['required', 'ipv4', 'unique:vpn_servers,ip,' . $id]
        ]);

        $this->serverService->updateForUser($id, $request->toArray());

        return redirect()->route('module.vpn.web.users.servers.index')->with('status', __('Server updated successfully'));

    }

    /**
     * Destroy resource
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $this->serverService->deleteForUser($id);

        return redirect()->route('module.vpn.web.users.servers.index')->with('status', __('Server deleted successfully'));
    }
}
