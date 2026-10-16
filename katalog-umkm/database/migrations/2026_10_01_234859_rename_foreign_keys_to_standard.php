<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('table_pesanan', function (Blueprint $table) {
            $table->renameColumn('id_pengguna', 'pengguna_id');
        });

        Schema::table('table_produk', function (Blueprint $table) {
            $table->renameColumn('id_kategori', 'kategori_id');
        });

        Schema::table('table_item_pesanan', function (Blueprint $table) {
            $table->renameColumn('id_pesanan', 'pesanan_id');
            $table->renameColumn('id_produk', 'produk_id');
        });

        Schema::table('table_item_keranjang', function (Blueprint $table) {
            $table->renameColumn('id_pengguna', 'pengguna_id');
            $table->renameColumn('id_produk', 'produk_id');
        });

        Schema::table('table_promosi', function (Blueprint $table) {
            $table->renameColumn('id_produk', 'produk_id');
        });

        Schema::table('table_gambar_produk', function (Blueprint $table) {
            $table->renameColumn('id_produk', 'produk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_pesanan', function (Blueprint $table) {
            $table->renameColumn('pengguna_id', 'id_pengguna');
        });

        Schema::table('table_produk', function (Blueprint $table) {
            $table->renameColumn('kategori_id', 'id_kategori');
        });

        Schema::table('table_item_pesanan', function (Blueprint $table) {
            $table->renameColumn('pesanan_id', 'id_pesanan');
            $table->renameColumn('produk_id', 'id_produk');
        });

        Schema::table('table_item_keranjang', function (Blueprint $table) {
            $table->renameColumn('pengguna_id', 'id_pengguna');
            $table->renameColumn('produk_id', 'id_produk');
        });

        Schema::table('table_promosi', function (Blueprint $table) {
            $table->renameColumn('produk_id', 'id_produk');
        });

        Schema::table('table_gambar_produk', function (Blueprint $table) {
            $table->renameColumn('produk_id', 'id_produk');
        });
    }
};
