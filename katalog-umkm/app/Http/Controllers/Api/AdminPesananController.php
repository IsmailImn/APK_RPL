<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminPesananController extends Controller
{
    public function index(): JsonResponse
    {
        $pesanan = Pesanan::with(['pengguna', 'itemPesanan.produk'])
        ->orderByRaw("FIELD(status,'belum_dikirim', 'dikirim', 'terkirim')")
        ->latest()
        ->get();

        return response()->json([
            'success'=>true,
            'message'=>'Seluruh pesanan masuk berhasil diambil',
            'data'=>$pesanan
        ], 200);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status'=>'required|in:belum_dikirim,dikirim,terkirim'
        ]);

        $pesanan = Pesanan::find($id);
        if(!$pesanan){
            return response()->json([
                'success'=>false,
                'message'=>'Pesanan yang anda tuju tidak ditemukan'
            ], 404);
        }

        $pesanan->update([
            'status'=>$request->status
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Status pesanan dengan #'. $id ."berhasil diubah menjadi: ". $request->status,
            'data'=>$pesanan
        ], 200);
    }
}
