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

        /**
         * List servers
         */
        Route::resource(
            'servers',
            \Vpn\App\Http\Controllers\Api\Admin\ServerController::class
        )->only('index');

        /**
         * List wireguard servers
         */
        Route::resource(
            'wireguard',
            \Vpn\App\Http\Controllers\Api\Admin\WireguardController::class
        )->only('index');
    });


    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function () {

        /**
         * List servers for users
         */
        Route::get('/servers/list', [
            \Vpn\App\Http\Controllers\Api\User\ServerController::class,
            'listServers'
        ])->name('servers.list');

        Route::resource(
            'servers',
            \Vpn\App\Http\Controllers\Api\User\ServerController::class,
        )->only('index');

        /**
         * List wireguard servers
         */
        Route::get('wireguards/list', [
            \Vpn\App\Http\Controllers\Api\User\WireguardController::class,
            'listWireguardServersForUser'
        ])->name('wireguard.list');

        Route::resource(
            'wireguards',
            \Vpn\App\Http\Controllers\Api\User\WireguardController::class
        )->only('index');

        /**
         * Peer routes
         */
        Route::resource(
            'peers',
            \Vpn\App\Http\Controllers\Api\User\PeerController::class
        )->only('index', 'store', 'destroy');
    });
});

/**
 * Gataway server to server validation
 */
Route::get('/gateway', GatewayController::class)->middleware('throttle:third-party:vpn:admin')->name('gateway');
