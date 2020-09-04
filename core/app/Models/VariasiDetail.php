<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariasiDetail extends Model
{
    protected $table = 'produk_variasi_detail';

    protected $fillable = [
        'produk_id', 'variasi_id', 'outlet_id', 'qty_tersedia'
    ];

}
