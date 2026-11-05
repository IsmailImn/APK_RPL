<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GambarProduk;
use App\Models\Produk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public function update(Request $request, int $id): JsonResponse
    {
        $produk = Produk::find($id);

        if (!$produk){
            return response()->json([
                'success'=>false,
                'message'=>'Produk tidak ditemukan'
            ], 404);
        }
        $request->validate([
            'kategori_id'=>'sometimes|required|exists:table_kategori,id',
            'nama_produk'=>'sometimes|required|string|max:255',
            'deskripsi'=>'sometimes|required|string',
            'harga'=>'sometimes|required|integer|min:0',
            'stok'=>'sometimes|required|integer|min:0',
            'status_aktif'=>'sometimes|required|in:ada,habis',
        ]);

        $produk->update($request->all());

        return response()->json([
            'success'=>true,
            'message'=>'Berhasil melakukan update pada detail data produk '. $produk->nama_produk,
            'data'=>$produk
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $produk = Produk::with('gambarProduk')->find($id);

        if (!$produk){
            return response()->json([
                'success'=>false,
                'message'=>'Produk tidak ditemukan'
            ], 404);
        }

        try{
            foreach($produk->gambarProduk as $gambar) {
                if ($gambar->path_gambar){
                    Storage::disk('public')->delete($gambar->path_gambar);
                }
            }

            $produk->gambarProduk()->delete();
            $produk->delete();

            DB::commit();
            return response()->json([
                'success'=>true,
                'message'=>'Produk berhasil dihapus beserta gambarnya'
            ], 200);
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                'success'=>false,
                'message'=>'Terjadi kesalahan pada: '. $e->getMessage()
            ], 500);
        }
    }
}
