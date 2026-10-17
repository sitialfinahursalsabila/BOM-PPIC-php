<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StatusProduksiController;
use App\Http\Middleware\PeranMiddleware;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.proses');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/bom', [BomController::class, 'index'])->name('bom.index');
    Route::get('/bom/{bom}', [BomController::class, 'show'])->name('bom.show');

    Route::get('/status-produksi', [StatusProduksiController::class, 'index'])->name('status.index');
    Route::get('/status-produksi/{rencana}', [StatusProduksiController::class, 'show'])->name('status.show');
    Route::post('/status-produksi/{rencana}/ubah', [StatusProduksiController::class, 'ubah'])
        ->middleware(PeranMiddleware::class . ':produksi')->name('status.ubah');

    Route::resource('items', ItemController::class)->except('show')
        ->parameters(['items' => 'item'])
        ->middleware(PeranMiddleware::class . ':ppic');
});
