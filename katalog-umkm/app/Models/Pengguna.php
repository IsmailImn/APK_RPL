<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengguna extends Model
{
    protected $table = 'table_pengguna';
    protected $fillable = ['nama', 'email', 'no_wa', 'password_hash', 'peran'];

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class);
    }

    public function itemKeranjang(): HasMany
    {
        return $this->hasMany(ItemKeranjang::class);
    }
}
