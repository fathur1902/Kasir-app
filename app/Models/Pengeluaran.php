<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'stok_produk_id',
        'nama_item',
        'jumlah_tambah',
        'harga_satuan',
        'total',
    ];

    public function stokProduk()
    {
        return $this->belongsTo(StokProduk::class, 'stok_produk_id');
    }
}
