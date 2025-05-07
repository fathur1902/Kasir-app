<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'metode_pembayaran', 'total', 'bayar', 'kembalian'
    ];

    public function transaksiItems()
    {
        return $this->hasMany(TransaksiItem::class);
    }

}
