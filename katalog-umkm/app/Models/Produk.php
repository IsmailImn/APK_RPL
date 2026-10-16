<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $table = 'table_produk';
    protected $fillable = ['kategori_id', 'nama_produk', 'deskripsi', 'harga', 'stok', 'status_aktif'];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function itemPesanan(): HasMany
    {
        return $this->hasMany(ItemPesanan::class, 'id_produk', 'id');
    }

    public function itemKeranjang(): HasMany
    {
        return $this->hasMany(ItemKeranjang::class);
    }

    public function promosi(): HasMany
    {
        return $this->hasMany(Promosi::class);
    }

    public function gambarProduk(): HasMany
    {
        return $this->hasMany(GambarProduk::class);
    }
}
