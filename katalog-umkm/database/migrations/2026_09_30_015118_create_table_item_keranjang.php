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
        Schema::create('table_item_keranjang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')->constrained('table_pengguna')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('table_produk')->onDelete('cascade');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__item_keranjang');
    }
};
