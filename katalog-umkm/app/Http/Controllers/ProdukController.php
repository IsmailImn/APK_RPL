<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Promosi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function kategori(): JsonResponse 
    {
        $kategori = Kategori::all();

        return response()->json([
            'status'=>'success',
            'message'=>'Daftar Kategori Berhasil diambil',
            'data'=>$kategori
        ], 200);
    }

    public function index(Request $request): JsonResponse
    {
        $query = Produk::with(['gambarProduk', 'promosi'])->where('status_aktif', 'ada');

        if ($request->has('search') && $request->search != ''){
            $query->where('nama_produk', 'like','%'. $request->search .'%');
        }

        if ($request->has('kategori_id') && $request->kategori_id != ''){
            $query->where('kategori_id', $request->kategori_id);
        }

        $produk = $query->latest()->paginate(5);

        if ($produk->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk yang Anda cari tidak ditemukan',
                'data'    => null // atau bisa diisi [] sesuai kebutuhan frontend
            ], 404); // Mengembalikan kode status 404 (Not Found)
        }

        return response()->json([
            'success'=>true,
            'message'=>'Daftar Produk Berhasil diambil',
            'data'=>$produk
        ], 200);
    }

    public function show($id): JsonResponse
    {
        $produk = Produk::with(['gambarProduk', 'promosi', 'kategori'])
        ->where('status_aktif', 'ada')->find($id);

        if (!$produk){
            return response()->json([
                'success'=>false,
                'message'=>'Produk tidak ditemukan atau stok sudah habis',
            ], 404);
        }

        return response()->json([
            'success'=>true,
            'message'=>'Detail Produk berhasil diambil',
            'data'=>$produk
        ]);
    }

    public function promosi(): JsonResponse
    {
        $waktuSekarang = now();
        $promosi = Promosi::with(['produk.gambarProduk'])
        ->where('tanggal_mulai', '<=', $waktuSekarang)
        ->where('tanggal_selesai', '>=', $waktuSekarang)
        ->get();

        if (!$promosi){
            return response()->json([
                'success'=>false,
                'message'=>'Tidak ada Promo berlaku',
            ], 404);
        }

        return response()->json([
            'success'=>true,
            'message'=>'Daftar Promo berhasil diambil',
            'data'=>$promosi
        ], 200);
    }
}
