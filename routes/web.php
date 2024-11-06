<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.app');
});


Route::get('/dashboard', function () {
    return view('index');
})->name('dashboard');

Route::get('/test-login', function () {
    return view('login-page');
});
