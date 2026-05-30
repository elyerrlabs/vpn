<?php

use Illuminate\Support\Facades\Route;
use Vpn\App\Http\Controllers\User\PeerController;

Route::middleware(["throttle:third-party:vpn:web"])->group(function () {

    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function () {

        /**
         * Server
         */
        Route::resource(
            'servers',
            \Vpn\App\Http\Controllers\User\ServerController::class
        )->only('index', 'store', 'update', 'destroy');

        /**
         * Wireguard routes
         */

        Route::put(
            'wireguards/{wireguard}/shutdown',
            [\Vpn\App\Http\Controllers\User\WireguardController::class, 'shutdown']
        )->name('wireguards.shutdown');

        Route::put(
            'wireguards/{wireguard}/start',
            [\Vpn\App\Http\Controllers\User\WireguardController::class, 'start']
        )->name('wireguards.start');

        Route::resource(
            'wireguards',
            \Vpn\App\Http\Controllers\User\WireguardController::class
        )->only('index', 'store', 'update', 'destroy');

        /**
         * Wireguard peer routes
         */
        Route::resource('peers', PeerController::class)->only('index');
    });
});