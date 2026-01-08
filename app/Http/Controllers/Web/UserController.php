<?php
namespace Vpn\App\Http\Controllers\Web;

use Inertia\Inertia;
use App\Http\Controllers\WebController;

final class UserController extends WebController
{

    public function peers()
    {
        return Inertia::render("User/Peer/Index", [
            'menus' => resolveInertiaRoutes(config('menus.vpn_user_routes')),
            'routes' => [
                'peers' => route('module.vpn.api.users.peers.index'),
                'servers' => route('module.vpn.api.users.lists.servers'),
                'wireguard' => route('module.vpn.api.users.wireguard.index')
            ]
        ]);
    }
}
