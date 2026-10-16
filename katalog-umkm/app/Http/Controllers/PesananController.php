<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function semua(){
        $semuaPesanan = Pesanan::with(['pengguna', 'itemPesanan.produk'])->get();

        return response()->json([
            'status'=>'success',
            'data'=>$semuaPesanan
        ]);
    }

    public function spesifik(int $id){
        $pesanan = Pesanan::findOrFail($id);

        $pesanan->load(['pengguna', 'itemPesanan.produk']);

        return response()->json([
            'status' => 'success',
            'message' => 'Detail pesanan berhasil ditemukan',
            'data' => $pesanan
        ]);
    }
}
