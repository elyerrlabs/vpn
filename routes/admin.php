<?php

use Illuminate\Support\Facades\Route;
use Vpn\App\Http\Controllers\TestController;

/**
 * Register admin routes
 */

Route::get('/', [TestController::class, 'admin'])->name('admin');