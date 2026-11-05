<?php

use App\Http\Controllers\Api\AdminProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProdukController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/pesanan/semua', [PesananController::class, 'semua']);

Route::get('/itempesanan/{id}', [PesananController::class, 'spesifik']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/kategori', [ProdukController::class, 'kategori']);

Route::get('/produk', [ProdukController::class, 'index']);

Route::get('/produk/{id}', [ProdukController::class, 'show']);

Route::get('/promosi', [ProdukController::class, 'promosi']);

Route::post('/admin/register-publik', [AuthController::class, 'registerAdminPublik']);

// Route Terproteksi (Wajib membawa Bearer Token)
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/keranjang', [KeranjangController::class, 'index']);
    Route::post('/keranjang', [KeranjangController::class, 'store']);
    Route::put('/keranjang/{id}', [KeranjangController::class, 'update']);
    Route::delete('/keranjang/{id}', [KeranjangController::class, 'hapus']);
    Route::post('/pesanan', [PesananController::class, 'store']);
    Route::post('/pesanan/{id}/batal', [PesananController::class, 'batal']);
});

Route::middleware(['auth:sanctum','admin'])->prefix('admin')->group(function() {
    Route::post('/register-staff', [AuthController::class, 'registerAdmin']);
    Route::post('/produk', [AdminProdukController::class, 'store']);
    Route::put('/produk/{id}/', [AdminProdukController::class, 'update']);
    Route::delete('/produk/{id}/', [AdminProdukController::class, 'destroy']);
});