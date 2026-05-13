<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
});

Route::get('/login-planta', function () {
    return view('home.login_planta');
})->name('login.planta');

Route::get('/login-general', function () {
    return view('home.login_general');
})->name('login.general');
