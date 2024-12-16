<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjualanController;
use App\Http\Middleware\isLogin;
use Illuminate\Support\Facades\Route;

Route::get('/kosong', function () {
    return view('layouts.app');
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware([isLogin::class])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan');
});


Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
