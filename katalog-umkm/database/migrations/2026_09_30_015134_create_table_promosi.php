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
        Schema::create('table_promosi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk')->constrained('table_produk')->onDelete('cascade');
            $table->string('judul');
            $table->string('path_banner')->nullable();
            $table->float('persen_diskon');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_promosi');
    }
};
