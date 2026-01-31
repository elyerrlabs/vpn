<?php

use Illuminate\Support\Facades\Route;
use Vpn\App\Http\Controllers\Web\SettingController;

/**
 * Register admin routes
 */

Route::middleware("throttle:third-party:vpn:admin")->group(function () {

    Route::get('servers', [\Vpn\App\Http\Controllers\Web\AdminController::class, 'servers'])->name('servers');
    Route::get('wireguard', [\Vpn\App\Http\Controllers\Web\AdminController::class, 'wireguard'])->name('wireguard');

    Route::group([
        'prefix' => 'settings',
        'as' => 'settings.'
    ], function () {

        Route::get('/', [SettingController::class, 'general'])->name('general');
    });
});
