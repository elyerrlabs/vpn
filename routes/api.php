<?php

use Illuminate\Support\Facades\Route;
use Vpn\App\Http\Controllers\Api\public\GatewayController;

/**
 * Register routes API
 */

Route::middleware(["throttle:third-party:vpn:admin"])->group(function () {

    Route::group([
        'prefix' => 'admin',
        'as' => 'admin.'
    ], function () {

        Route::resource('servers', \Vpn\App\Http\Controllers\Api\Admin\ServerController::class)->except('create', 'edit');
        Route::resource('wireguard', \Vpn\App\Http\Controllers\Api\Admin\WireguardController::class)->except('create', 'edit');
        Route::put('wireguard/{wireguard}/shutdown', [\Vpn\App\Http\Controllers\Api\Admin\WireguardController::class, 'shutdown'])->name('wireguard.shutdown');
        Route::put('wireguard/{wireguard}/start', [\Vpn\App\Http\Controllers\Api\Admin\WireguardController::class, 'start'])->name('wireguard.start');
    });


    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function () {

        Route::get('/servers/all', [\Vpn\App\Http\Controllers\Api\User\ServerController::class, 'listServers'])->name('lists.servers');
        Route::resource('servers', \Vpn\App\Http\Controllers\Api\User\ServerController::class)->except('edit', 'create');

        Route::get('wireguard/all', [\Vpn\App\Http\Controllers\Api\User\WireguardController::class, 'listWireguardServersForUser'])->name('lists.wireguard');
        Route::resource('wireguard', \Vpn\App\Http\Controllers\Api\User\WireguardController::class)->except('edit', 'create');
        Route::put('wireguard/{wireguard}/shutdown', [\Vpn\App\Http\Controllers\Api\User\WireguardController::class, 'shutdown'])->name('wireguard.shutdown');
        Route::put('wireguard/{wireguard}/start', [\Vpn\App\Http\Controllers\Api\User\WireguardController::class, 'start'])->name('wireguard.start');

        Route::resource('peers', \Vpn\App\Http\Controllers\Api\User\PeerController::class)->only('index', 'store', 'update', 'destroy');
    });
});


Route::get('/gateway',  GatewayController::class)
    ->middleware('throttle:third-party:vpn:admin')
    ->name('gateway');
