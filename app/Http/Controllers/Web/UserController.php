<?php

namespace Vpn\App\Http\Controllers\Web;

use Inertia\Inertia;
use App\Http\Controllers\WebController;

final class UserController extends WebController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:administrator:vpn:full,commerce:vpn-servers:professional,commerce:vpn-servers:advanced,commerce:vpn-servers:intermediate,commerce:vpn-servers:basic')->except('peers');
    }

    /**
     * Show server user interface
     * @return \Inertia\Response
     */
    public function servers()
    {
        return Inertia::render('User/Server/Index', [
            'menus' => resolveInertiaRoutes(config('menus.vpn_user_routes')),
            'servers' => [
                'index' => route('module.vpn.api.users.servers.index'),
                'store' => route('module.vpn.api.users.servers.store')
            ]
        ]);
    }

    /**
     * Show Wireguard user interface
     * @return \Inertia\Response
     */
    public function wireguard()
    {
        return Inertia::render('User/Wireguard/Index', [
            'menus' => resolveInertiaRoutes(config('menus.vpn_user_routes')),
            'wireguard' => [
                'index' => route('module.vpn.api.users.wireguard.index'),
                'store' => route('module.vpn.api.users.wireguard.store'),
                'servers' => route('module.vpn.api.users.servers.index'),
            ]
        ]);
    }

    /**
     * Show Peer user interface
     * @return \Inertia\Response
     */
    public function peers()
    {
        return Inertia::render("User/Peer/Index", [
            'menus' => resolveInertiaRoutes(config('menus.vpn_user_routes')),
            'routes' => [
                'peers' => route('module.vpn.api.users.peers.index'),
                'servers' => route('module.vpn.api.users.lists.servers'),
                'wireguard' => route('module.vpn.api.users.lists.wireguard')
            ]
        ]);
    }
}
