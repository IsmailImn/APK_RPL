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
        Schema::create('table_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')->constrained('table_pengguna')->onDelete('cascade');
            $table->text('alamat_pengiriman');
            $table->enum('status', ['belum_dikirim', 'dikirim', 'terkirim']);
            $table->timestamp('tanggal_pesanan')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_pesanan');
    }
};
