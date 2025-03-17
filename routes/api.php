<?php

use App\Http\Controllers\TransaksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Webhook Midtrans untuk handle status transaksi
Route::post('/midtrans-webhook', [TransaksiController::class, 'handleWebhook']);