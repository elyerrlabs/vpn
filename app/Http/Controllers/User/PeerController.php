<?php

namespace Vpn\App\Http\Controllers\User;

use Inertia\Inertia;
use App\Http\Controllers\WebController;

final class PeerController extends WebController
{
    public function __construct()
    {
        parent::__construct();

        $scopes = [
            'administrator:vpn:full',
            'commerce:vpn:professional',
            'commerce:vpn:advanced',
            'commerce:vpn:intermediate',
            'commerce:vpn:basic'
        ];

        $this->middleware('userCanAny:' . implode(',', $scopes));
    }

    /**
     * Show Peer user interface
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render("User/Peer/Index", [
            'menus' => resolveInertiaRoutes(config('menus.vpn_user_routes')),
            'api' => [
                'peers' => route('module.vpn.api.users.peers.index'),
                'servers' => route('module.vpn.api.users.servers.list'),
                'wireguard' => route('module.vpn.api.users.wireguard.list')
            ]
        ]);
    }
}
