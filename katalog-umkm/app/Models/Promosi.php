<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promosi extends Model
{
    protected $table = 'table_promosi';
    protected $fillable = ['produk_id', 'judul', 'path_banner', 'persen_diskon', 'tanggal_mulai', 'tanggal_selesai'];
    
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
