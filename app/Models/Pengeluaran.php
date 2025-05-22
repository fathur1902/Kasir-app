<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'stok_produk_id',
        'jumlah_tambah',
        'total',
    ];

    public function stokProduk()
    {
        return $this->belongsTo(StokProduk::class);
    }
}

