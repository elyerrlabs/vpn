<?php

use Illuminate\Support\Facades\Route;
use Vpn\App\Http\Controllers\Admin\ServerController;
use Vpn\App\Http\Controllers\Admin\WireguardController;

/**
 * Register routes API
 */

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {

    Route::resource('servers', ServerController::class);
    Route::resource('wireguard', WireguardController::class);
});