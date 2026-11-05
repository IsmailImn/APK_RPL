<?php

namespace App\Http\Controllers;

use App\Models\ItemKeranjang;
use App\Models\ItemPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;

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

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'alamat_pengiriman'=>'required|string',
            'produk_id'=>'nullable|exists:table_produk,id',
            'jumlah'=>'nullable|integer|min:1|required_with:produk_id'
        ]);

        $userId = Auth::id();

        DB::beginTransaction();

        try{

            $itemsToOrder = [];

            if ($request->filled('produk_id')){
                $produk = Produk::find($request->produk_id);

                if (!$produk || $produk->status_aktif != 'ada' || $produk->stok < $request->jumlah){
                    return response()->json([
                        'success'=>false,
                        'message'=>'Produk Tidak ada atau habis atau jumlah stok tidak mencukupi'
                    ], 400);
                }

                $itemsToOrder[] = [
                    'produk'=>$produk,
                    'jumlah'=>$request->jumlah
                ];
            } else {
                $itemKeranjang = ItemKeranjang::with('produk')
                ->where('pengguna_id', $userId)
                ->get();

                if ($itemKeranjang->isEmpty()){
                    return response()->json([
                        'succes'=>false,
                        'message'=>'Anda belum memiliki item dalam keranjang'
                    ], 400);
                }

                foreach ($itemKeranjang as $item) {
                    if (!$item || $item->produk->status_aktif != 'ada' || $item->produk->stok < $item->jumlah) {
                        return response()->json([
                            'succes'=>false,
                            'message'=>'Produk '. $item->produk->nama_produk .'sudah habis atau jumlah stok tidak menucukupi'
                        ], 400);
                    }

                    $itemsToOrder[] = [
                        'produk'=>$item->produk,
                        'jumlah'=>$item->jumlah
                    ];
                }
            }

            $pesanan = Pesanan::create([
                'pengguna_id'=>$userId,
                'alamat_pengiriman'=>$request->alamat_pengiriman,
                'status'=>'belum_dikirim',
                'tanggal_pesanan'=>now()
            ]);

            foreach ($itemsToOrder as $item) {
                $produk = $item['produk'];
                $jumlah = $item['jumlah'];

                ItemPesanan::create([
                    'pesanan_id'=>$pesanan->id,
                    'produk_id'=>$produk->id,
                    'jumlah'=>$jumlah,
                    'harga_satuan'=>$produk->harga
                ]);

                $produk->stok = $produk->stok - $jumlah;
                $produk->save();

                if ($produk->stok <= 0){
                    $produk->update(['status_aktif'=>'habis']);
                }
            }

            if (!$request->filled('produk_id')){
                ItemKeranjang::where('pengguna_id', $userId)->delete();
            }

            DB::commit();

            return response()->json([
                'success'=>true,
                'message'=>'Pesanan berhasil dibuat',
                'data'=>$pesanan->load('itemPesanan.produk')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function batal($id): JsonResponse
    {
        $userId = Auth::id();

        DB::beginTransaction();

        try{
            $pesanan = Pesanan::where('id', $id)
            ->where('pengguna_id', $userId)
            ->first();

            if (!$pesanan){
                return response()->json([
                    'success'=>false,
                    'message'=>'Pesanan tidak ditemukan atau anda belum melakukan pemesanan'
                ], 404);
            }

            if ($pesanan->status !== 'belum_dikirim'){
                return response()->json([
                    'success'=>false,
                    'message'=>'Pesanan yang anda tuju tidak bisa dibatalkan karena sedang dalam proses pengiriman'
                ], 400);
            }

            $items = ItemPesanan::where('pesanan_id', $pesanan->id)->get();

            foreach ($items as $item){
                $produk = Produk::find($item->produk_id);

                if ($produk){
                    $produk->stok = $produk->stok + $item->jumlah;

                    if ($produk->status_aktif === 'habis' && $produk->stok > 0){
                        $produk->update(['status_aktif'=>'ada']);
                    }
                }
            }

            ItemPesanan::where('pesanan_id', $pesanan->id)->delete();
            $pesanan->delete();
            
            DB::commit();

            return response()->json([
                'success'=>true,
                'message'=>'Pesanan berhasil dibatalkan'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success'=>false,
                'message'=>'Terjadi kesalahan: '. $e->getMessage()
            ], 500);
        }
    }
}
