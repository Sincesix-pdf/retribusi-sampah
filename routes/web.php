<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Halaman utama
Route::view('/', 'index');
Route::view('/warga', 'datawarga.index');

// Halaman login
Route::get('/login', [AuthController::class, 'index'])->name('login');

// Proses login & logout
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route dengan Middleware berdasarkan Role
Route::middleware(['auth', 'role:pendataan'])->group(function () {
    Route::get('/pendataan', [PendataanController::class, 'index'])->name('pendataan.index');
});

Route::middleware(['auth', 'role:keuangan'])->group(function () {
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
});

Route::middleware(['auth', 'role:kepala_dinas'])->group(function () {
    Route::get('/grafik', [KepalaDinasController::class, 'index'])->name('grafik.index');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/log', [AdminController::class, 'index'])->name('log.index');
});

Route::middleware(['auth', 'role:warga'])->group(function () {
    Route::get('/histori', [WargaController::class, 'index'])->name('histori.index');
});
