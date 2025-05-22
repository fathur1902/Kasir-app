<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiItem extends Model
{
    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'keuntungan',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function stokProduk()
    {
        return $this->belongsTo(StokProduk::class, 'produk_id');
    }
}
