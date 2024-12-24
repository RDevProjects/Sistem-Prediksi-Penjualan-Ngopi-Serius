<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\AnalisaController;
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
    Route::prefix('/penjualan')->group(function () {
        Route::get('/', [PenjualanController::class, 'index'])->name('penjualan');
        Route::get('/create', [PenjualanController::class, 'create'])->name('penjualan.create');
        Route::post('/store', [PenjualanController::class, 'store'])->name('penjualan.store');
        Route::get('/edit/{id}', [PenjualanController::class, 'edit'])->name('penjualan.edit');
        Route::put('/update/{id}', [PenjualanController::class, 'update'])->name('penjualan.update');
        Route::delete('/delete/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.delete');
    });

    Route::get('/analisis', [AnalisaController::class, 'index'])->name('analisis');
    Route::post('/analisis', [AnalisaController::class, 'analisis'])->name('analisis.post');
});


Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
