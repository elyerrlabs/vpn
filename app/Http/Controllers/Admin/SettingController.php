<?php

namespace Vpn\App\Http\Controllers\Admin;
use App\Http\Controllers\Web\Admin\Setting\SettingController as Controller;


final class SettingController extends Controller
{

    public function __construct()
    {
        $this->middleware('userCanAny:settings:vpn:full,settings:vpn:view')->only('general');
        $this->middleware('userCanAny:settings:vpn:full,settings:vpn:update')->only('update');
    }

    public function general()
    {
        return view('Vpn::settings.parts.general');
    }
}
