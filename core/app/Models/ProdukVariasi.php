<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukVariasi extends Model
{
    protected $table = 'produk_variasi';

    protected $fillable = [
        'nama', 'sku', 'hrg_modal', 'hrg_jual', 'kelola_stok', 'min_stok', 'produk_id', 'satuan_id',
    ];

    public function produk()
    {
        return $this->hasOne('App\Models\Produk', 'id', 'produk_id');
    }

    public function satuan()
    {
        return $this->belongsTo('App\Models\Satuan', 'satuan_id');
    }

    public function detail()
    {
        return $this->hasOne('App\Models\VariasiDetail', 'variasi_id');
    }
}
