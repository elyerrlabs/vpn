<?php

use Illuminate\Support\Facades\Route;

/**
 * Register routes API
 */

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {

    Route::resource('servers', \Vpn\App\Http\Controllers\Api\Admin\ServerController::class);
    Route::resource('wireguard', \Vpn\App\Http\Controllers\Api\Admin\WireguardController::class);
});


Route::group([
    'prefix' => 'users',
    'as' => 'users.'
], function () {

    Route::resource('servers', \Vpn\App\Http\Controllers\Api\User\ServerController::class)->only('index');
    Route::resource('wireguard', \Vpn\App\Http\Controllers\Api\User\WireguardController::class)->only('index');
    Route::resource('peers', \Vpn\App\Http\Controllers\Api\User\PeerController::class)->only('index', 'store', 'update', 'destroy');
});