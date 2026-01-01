<?php

Route::get('/', function () {
    return view('Vpn::welcome');
})->name('welcome');