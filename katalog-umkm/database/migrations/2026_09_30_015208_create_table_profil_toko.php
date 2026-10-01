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
        Schema::create('table_profil_toko', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');
            $table->string('logo');
            $table->string('warna_tema');
            $table->text('deskripsi_toko');
            $table->text('alamat');
            $table->string('no_wa_toko');
            $table->string('tautan_toko');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__profil_toko');
    }
};
