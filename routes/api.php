<?php

use Illuminate\Support\Facades\Route;

/**
 * Register routes API
 */

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {

    Route::resource('servers', \Vpn\App\Http\Controllers\Admin\ServerController::class);
    Route::resource('wireguard', \Vpn\App\Http\Controllers\Admin\WireguardController::class);
});


Route::group([
    'prefix' => 'users',
    'as' => 'users.'
], function () {

    Route::resource('servers', \Vpn\App\Http\Controllers\User\ServerController::class)->only('index');
    Route::resource('wireguard', \Vpn\App\Http\Controllers\User\WireguardController::class)->only('index');
    Route::resource('peers', \Vpn\App\Http\Controllers\User\PeerController::class)->only('index', 'store', 'update', 'destroy');
});