<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Autenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Autenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'table_pengguna';
    protected $fillable = ['nama', 'email', 'no_wa', 'password_hash', 'peran'];
    protected $hidden = ['password_hash'];

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class);
    }

    public function itemKeranjang(): HasMany
    {
        return $this->hasMany(ItemKeranjang::class);
    }
}
