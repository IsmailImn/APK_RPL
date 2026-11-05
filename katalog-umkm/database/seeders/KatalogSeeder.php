<?php

namespace Database\Seeders;

use Carbon\Carbon as CarbonAlias;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class KatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('table_promosi')->truncate();
        DB::table('table_gambar_produk')->truncate();
        DB::table('table_produk')->truncate();
        DB::table('table_kategori')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $FacialId = DB::table('table_kategori')->insertGetId([
            'nama_kategori'=>'Facial Skincare'
        ]);

        $produk1Id = DB::table('table_produk')->insertGetId([
            'kategori_id'=>$FacialId,
            'nama_produk'=>'LILLAH BEAUTY CARE Cleanser Wash',
            'deskripsi'=>'LILLAH BEAUTY CARE Cleanser Wash Pembersih wajah, yang dapat mengangkat dan membersihkan sel-sel kulit mati & sisa make up di kulit wajah. Mengandung bahan Niacinamide, Licorice Extract, Alpha Arbutin, Aloe Barbadensis Leaf Juice, dan Hydrolized Collagen yang dapat membantu melembabkan dan melembutkan kulit wajah dan menjadikan kulit tampak lebih cerah.',
            'harga'=>60000,
            'stok'=>50,
            'status_aktif'=>'ada'
        ]);

            $produk2Id = DB::table('table_produk')->insertGetId([
            'kategori_id'=>$FacialId,
            'nama_produk'=>'LILLAH BEAUTY CARE Toner Brightening',
            'deskripsi'=>'LILLAH BEAUTY CARE Toner Brightening merupakan penyegar wajah dengan pH Balance, yang dapat mengangkat sisa kotoran dan make up di wajah. Dengan kandungan bahan Niacinamide, Alpha Arbutin, Centella Asiatica Extract, Tranexamic Acid, & Allantoin yang dapat membantu melembabkan, menghidrasi dan menjadikan kulit tampak lebih cerah.',
            'harga'=>65000,
            'stok'=>50,
            'status_aktif'=>'ada'
        ]);

            $produk3Id = DB::table('table_produk')->insertGetId([
            'kategori_id'=>$FacialId,
            'nama_produk'=>'LILLAH BEAUTY CARE  Sun-Day Tinted',
            'deskripsi'=>'LILLAH BEAUTY CARE  Sun-Day Tinted Pelembab wajah yang digunakan pada pagi/siang hari dan mengandung bahan tabir surya untuk melindungi kulit dari akibat paparan sinar matahari. Diperkaya dengan kandungan Lumicease™ Blue, Niacinamide, Centella Asiatica, White 10 SL yang dapat membantu melembabkan dan meratakan warna kulit sehingga menjadikan kulit tampak lebih cerah.',
            'harga'=>75000,
            'stok'=>50,
            'status_aktif'=>'ada'
        ]);

            $produk4Id = DB::table('table_produk')->insertGetId([
            'kategori_id'=>$FacialId,
            'nama_produk'=>'LILLAH BEAUTY CARE Multiactive Brightening Night Cream',
            'deskripsi'=>'LILLAH BEAUTY CARE Multiactive Brightening Night Cream Pelembab dan pencerah wajah yang digunakan pada malam hari, diformulasi dengan kandungan bahan Chromabright, Retinol, Sodium DNA, Hexylresorcinol, Tripeptide yang dapat membantu mencerahkan kulit, melembabkan serta menyamarkan noda hitam di wajah, sehingga menjadikan kulit tampak lebih cerah dan glow up.',
            'harga'=>86000,
            'stok'=>50,
            'status_aktif'=>'ada'
        ]);

        $produk5Id = DB::table('table_produk')->insertGetId([
            'kategori_id'=>$FacialId,
            'nama_produk'=>'LILLAH BEAUTY CARE Paker Brightening',
            'deskripsi'=>'LILLAH BEAUTY CARE Paket Cleanser Wash, Toner Brightening, Sun-Day Tinted, dan Multiactive Night Cream',
            'harga'=>250000,
            'stok'=>50,
            'status_aktif'=>'ada'
        ]);

        DB::table('table_gambar_produk')->insert([
            ['produk_id'=>$produk1Id, 'path_gambar'=>'images/produk/Cleanser Wash.jpeg', 'urutan'=>1],
            ['produk_id'=>$produk2Id, 'path_gambar'=>'images/produk/Toner.jpeg', 'urutan'=>1],
            ['produk_id'=>$produk3Id, 'path_gambar'=>'images/produk/Sunday tinted.jpeg', 'urutan'=>1],
            ['produk_id'=>$produk4Id, 'path_gambar'=>'images/produk/Night Cream.jpeg', 'urutan'=>1],
            ['produk_id'=>$produk5Id, 'path_gambar'=>'images/produk/Paket.jpeg', 'urutan'=>1],
        ]);

        DB::table('table_promosi')->insert([
            'produk_id'=>$produk5Id,
            'judul'=>'Promo Brightening',
            'path_banner'=>'images/produk/Paket.jpeg',
            'persen_diskon'=>20.0,
            'tanggal_mulai'=>Carbon::now()->subDays(1),
            'tanggal_selesai'=>Carbon::now()->addDays(5)
        ]);
    }
}
