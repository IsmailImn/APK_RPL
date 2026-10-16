<?php

namespace App\Models;

use Illuminate\Cache\RetrievesMultipleKeys;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GambarProduk extends Model
{
    protected $table = 'table_gambar_produk';
    protected $fillable = ['produk_id', 'path_gambar', 'urutan'];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
