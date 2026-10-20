<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PesananController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/pesanan/semua', [PesananController::class, 'semua']);

Route::get('/pesanan/{id}', [PesananController::class, 'spesifik']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

// Route Terproteksi (Wajib membawa Bearer Token)
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});