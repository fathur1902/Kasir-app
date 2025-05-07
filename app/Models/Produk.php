<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $fillable = ['nama', 'singkatan'];

    public function stok()
    {
        return $this->hasMany(stokProduk::class, 'produk_id');
    }
}
