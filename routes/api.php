<?php

use Illuminate\Support\Facades\Route;

/**
 * Register routes API
 */

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {

    Route::resource('servers', \Vpn\App\Http\Controllers\Api\Admin\ServerController::class)->except('create', 'edit');
    Route::resource('wireguard', \Vpn\App\Http\Controllers\Api\Admin\WireguardController::class)->except('create', 'edit');
});


Route::group([
    'prefix' => 'users',
    'as' => 'users.'
], function () {

    Route::get('/servers/all', [\Vpn\App\Http\Controllers\Api\User\ServerController::class, 'listServers'])->name('lists.servers');
    Route::resource('servers', \Vpn\App\Http\Controllers\Api\User\ServerController::class)->except('edit', 'create');

    Route::resource('wireguard', \Vpn\App\Http\Controllers\Api\User\WireguardController::class)->only('index');
    Route::resource('peers', \Vpn\App\Http\Controllers\Api\User\PeerController::class)->only('index', 'store', 'update', 'destroy');
});