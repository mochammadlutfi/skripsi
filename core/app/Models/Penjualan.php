<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = [
        'transaksi_id', 'produk_id', 'variasi_id', 'quantity', 'hrg_jual', 'sub_total',
    ];

    public function variasi()
    {
        return $this->belongsTo('App\Models\ProdukVariasi', 'variasi_id');
    }
}
