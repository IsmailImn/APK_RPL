<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    protected $table = 'table_pesanan';
    protected $fillable = ['pengguna_id', 'alamat_pengiriman', 'status', 'tanggal_pengiriman'];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function itemPesanan(): HasMany
    {
        return $this->hasMany(ItemPesanan::class);
    }

    public function itemKeranjang(): HasMany
    {
        return $this->hasMany(ItemKeranjang::class);
    }
}
