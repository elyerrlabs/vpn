<?php

use Vpn\App\Http\Controllers\Web\UserController;

Route::get('/', function () {
    return view('Vpn::welcome');
})->name('welcome');

Route::group([
    'prefix' => 'users',
    'as' => 'users.'
], function () {

    Route::get('servers', [UserController::class, 'servers'])->name('servers');
    Route::get('wireguard', [UserController::class, 'wireguard'])->name('wireguard');

    Route::get('/peers', [UserController::class, 'peers'])->name('peers');
});