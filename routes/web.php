<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataWargaController;
use App\Http\Controllers\LogAktivitasController;

// Halaman utama
Route::view('/', 'index');

// Halaman login
Route::get('/login', [AuthController::class, 'index'])->name('login');

// Proses login & logout
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AuthController::class, 'admin'])->name('admin.index');
    // akses halaman Kelola WR
    Route::get('/admin/logaktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');
});

// Kepala Dinas
Route::middleware(['auth', 'role:kepala_dinas'])->group(function () {
    Route::get('/kepala-dinas/dashboard', [AuthController::class, 'kepaladinas'])->name('kepala_dinas.index');
});

// Keuangan
Route::middleware(['auth', 'role:keuangan'])->group(function () {
    Route::get('/keuangan/dashboard', [AuthController::class, 'keuangan'])->name('keuangan.index');
});

// Pendataan
Route::middleware(['auth', 'role:pendataan'])->group(function () {
    // akses halaman dashboard
    Route::get('/pendataan/dashboard', [AuthController::class, 'pendataan'])->name('pendataan.index');
    // Akses halaman Kelola Warga
    Route::get('/pendataan/datawarga', [DataWargaController::class, 'index'])->name('datawarga.index');
    Route::get('/pendataan/datawarga/create', [DataWargaController::class, 'create'])->name('datawarga.create');
    Route::post('/pendataan/datawarga', [DataWargaController::class, 'store'])->name('datawarga.store');
    Route::get('/pendataan/datawarga/{warga}/edit', [DataWargaController::class, 'edit'])->name('datawarga.edit');
    Route::put('/pendataan/datawarga/{warga}', [DataWargaController::class, 'update'])->name('datawarga.update');
    Route::delete('/pendataan/datawarga/{warga}', [DataWargaController::class, 'destroy'])->name('datawarga.destroy');
});

// Warga
Route::middleware(['auth', 'role:warga'])->group(function () {
    Route::get('/warga/dashboard', [AuthController::class, 'warga'])->name('warga.index');
});