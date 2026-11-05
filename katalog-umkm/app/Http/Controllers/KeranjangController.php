<?php

namespace App\Http\Controllers;

use App\Models\ItemKeranjang;
use App\Models\Produk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Js;

class KeranjangController extends Controller
{
    public function index(): JsonResponse
    {
        $userId = Auth::id();
        $namaUser = Auth::user()->nama;

        $keranjang = ItemKeranjang::with('produk')
        ->where('pengguna_id', $userId)
        ->get();

        return response()->json([
            'success'=>true,
            'message'=>'Menampilkan item Keranjang '.$namaUser,
            'data'=>$keranjang
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'produk_id'=>'required|exists:table_produk,id',
            'jumlah'=>'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $produkId = $request->produk_id;
        $jumlahInput = $request->jumlah;
        $namaUser = Auth::user()->nama;

        $produk = Produk::find($produkId);
        if (!$produk || $produk->status_aktif != 'ada'){
            return response()->json([
                'success'=>false,
                'message'=>'Produk sedang tidak aktif atau habis'
            ], 400);
        }

        $itemKeranjang = ItemKeranjang::where('pengguna_id', $userId)
        ->where('produk_id', $produkId)
        ->first();

        if ($itemKeranjang){
            $totalJumlahBaru = $itemKeranjang->jumlah + $jumlahInput;

            if ($produk->stok < $totalJumlahBaru){
                return response()->json([
                    'success'=>false,
                    'message'=>'Gagal menambahkan. Jumlah Stok produk tidak mencukupi'
                ], 400);
            }

            $itemKeranjang->update([
                'jumlah'=>$totalJumlahBaru
            ]);
        } else {
            if ($produk->stok < $jumlahInput){
                return response()->json([
                    'success'=>false,
                    'message'=>'Gagal menambahkan. Jumlah Stok produk tidak mencukupi'
                ], 400);
            }

            $itemKeranjang = ItemKeranjang::create([
                'pengguna_id'=>$userId,
                'produk_id'=>$produkId,
                'jumlah'=>$jumlahInput
            ]);
        }

        return response()->json([
            'success'=>true,
            'message'=>'Menampilkan item keranjang '. $namaUser,
            'data'=>$itemKeranjang
        ], 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'jumlah'=>'required|integer|min:1'
        ]);

        $userId = Auth::id();

        $itemKeranjang = ItemKeranjang::where('id', $id)
        ->where('pengguna_id', $userId)
        ->first();

        if (!$itemKeranjang){
            return response()->json([
                'success'=>false,
                'message'=>'Item Keranjang tidak ditemukan atau anda belum menambahkan item ke keranjang',
            ], 404);
        }

        $produk = Produk::find($itemKeranjang->produk_id);

        if ($produk->stok < $request->jumlah){
            return response()->json([
                'success'=>false,
                'message'=>'Gagal menambahkan. Stok barang tidak mencukupi untuk melakukan penambahan'
            ], 400);
        }

        $itemKeranjang->update([
            'jumlah'=>$request->jumlah
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Berhasil menambahkan jumlah item keranjang',
            'data'=>$itemKeranjang
        ], 200);
    }

    public function hapus($id): JsonResponse
    {
        $userId = Auth::id();

        $itemKeranjang = ItemKeranjang::where('id', $id)
        ->where('pengguna_id', $userId)
        ->first();

        if (!$itemKeranjang){
            return response()->json([
                'success'=>false,
                'message'=>'Item Keranjang tidaka ada'
            ], 404);
        }

        $itemKeranjang->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Berhasil Hapus Item Keranjang'
        ], 200);
    }
}
