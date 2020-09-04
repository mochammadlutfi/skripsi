<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelian';

    protected $fillable = [
        'transaksi_id', 'produk_id', 'variasi_id', 'quantity', 'hrg_beli', 'sub_total', 'qty_jual', 'qty_sesuaikan', 'qty_retur'
    ];

    public function variasi()
    {
        return $this->belongsTo('App\Models\ProdukVariasi', 'variasi_id');
    }
}
