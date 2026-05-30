<?php

use Illuminate\Support\Facades\Route;
use Vpn\App\Http\Controllers\Web\SettingController;

/**
 * Register admin routes
 */

Route::middleware("throttle:third-party:vpn:admin")->group(function () {

    /**
     * Server routes
     */
    Route::resource(
        'servers',
        \Vpn\App\Http\Controllers\Admin\ServerController::class
    )->only('index', 'store', 'update', 'destroy');

    /**
     * Wireguard routes
     */
    Route::put(
        'wireguards/{wireguard}/shutdown',
        [\Vpn\App\Http\Controllers\Admin\WireguardController::class, 'shutdown']
    )->name('wireguards.shutdown');

    Route::put(
        'wireguards/{wireguard}/start',
        [\Vpn\App\Http\Controllers\Admin\WireguardController::class, 'start']
    )->name('wireguards.start');

    Route::resource(
        'wireguards',
        \Vpn\App\Http\Controllers\Admin\WireguardController::class
    )->only('index', 'store', 'update', 'destroy');

    /**
     * Settings routes
     */
    Route::group([
        'prefix' => 'settings',
        'as' => 'settings.'
    ], function () {
        Route::get(
            '/',
            [\Vpn\App\Http\Controllers\Admin\SettingController::class, 'general']
        )->name('general');
    });
});
