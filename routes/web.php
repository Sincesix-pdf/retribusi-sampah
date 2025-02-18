<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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
    Route::get('/pendataan/dashboard', [AuthController::class, 'pendataan'])->name('pendataan.index');
});

