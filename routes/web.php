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
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/edit/{id}', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::put('/penjualan/update/{id}', [PenjualanController::class, 'update'])->name('penjualan.update');
    Route::delete('/penjualan/delete/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.delete');
});


Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
