<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'tanggal', 'metode_pembayaran', 'total', 'bayar', 'kembalian'
    ];

    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }
}
