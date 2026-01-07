<?php

use Illuminate\Support\Facades\Route;

/**
 * Register admin routes
 */

Route::get('servers', [\Vpn\App\Http\Controllers\Web\AdminController::class, 'servers'])->name('servers');
Route::get('wireguard', [\Vpn\App\Http\Controllers\Web\AdminController::class, 'wireguard'])->name('wireguard');