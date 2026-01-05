<?php

use Illuminate\Support\Facades\Route;
use Vpn\App\Http\Controllers\Admin\ServerController;

/**
 * Register routes API
 */

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {

    Route::resource('servers', ServerController::class);
});