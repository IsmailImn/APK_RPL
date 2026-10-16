<?php

use App\Http\Controllers\PesananController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/pesanan/semua', [PesananController::class, 'semua']);

Route::get('/pesanan/{id}', [PesananController::class, 'spesifik']);