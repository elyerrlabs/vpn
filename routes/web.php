<?php

use Illuminate\Support\Facades\Route;
use Vpn\App\Http\Controllers\Web\UserController;

Route::middleware(["throttle:third-party:vpn:web"])->group(function () {

    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function () {

        Route::get('servers', [UserController::class, 'servers'])->name('servers');
        Route::get('wireguard', [UserController::class, 'wireguard'])->name('wireguard');

        Route::get('peers', [UserController::class, 'peers'])->name('peers');
    });
});