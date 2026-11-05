<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GambarProduk;
use App\Models\Produk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProdukController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'kategori_id'=>'required|exists:table_kategori,id',
            'nama_produk'=>'required|string|max:255',
            'deskripsi'=>'required|string',
            'harga'=>'required|integer',
            'stok'=>'required|integer',
            'status_aktif'=>'required|in:ada,habis',
            'gambar'=>'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::beginTransaction();

        try{
            $produk = Produk::create($request->only([
                'kategori_id', 'nama_produk', 'deskripsi', 'harga', 'stok', 'status_aktif'
            ]));

            if ($request->hasFile('gambar')){
                $path = $request->file('gambar')->store('produk', 'public');

                GambarProduk::create([
                    'produk_id'=>$produk->id,
                    'path_gambar'=>$path,
                    'urutan'=>1
                ]);
            }

            DB::commit();

            return response()->json([
                'success'=>true,
                'message'=>'Produk Berhasil ditambahkan',
                'data'=>$produk->load('gambarProduk')
            ],201);
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                'success'=>false,
                'message'=>'Terjadi Kesalahan pada: '. $e->getMessage()
            ], 500);
        }
    }
}
