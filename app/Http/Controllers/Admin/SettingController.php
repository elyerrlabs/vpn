<?php

namespace Vpn\App\Http\Controllers\Admin;


final class SettingController extends \App\Http\Controllers\Web\Admin\Setting\SettingController
{

    public function general()
    {
        return view('Vpn::settings.general');
    }
}
