<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class stokProduk extends Model
{
    use HasFactory;

    protected $table = 'stok_produks';

    protected $fillable = ['produk_id', 'harga', 'jumlah', 'total', 'profit'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function transaksiItems()
    {
        return $this->hasMany(TransaksiItem::class);
    }

    // Opsional: menghitung total otomatis saat saving
    protected static function booted()
    {
        static::saving(function ($stok) {
            $stok->total = $stok->harga * $stok->jumlah;
        });
    }
}
