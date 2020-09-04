<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'nama', 'kategori_id', 'merk_id', 'foto', 'variasi', 'bisnis_id'
    ];

    public function kategori()
    {
        return $this->belongsTo('App\Models\Kategori', 'kategori_id');
    }

    public function merk()
    {
        return $this->belongsTo('App\Models\Merk', 'merk_id');
    }

    public function produk_variasi()
    {
        return $this->hasMany('App\Models\ProdukVariasi', 'produk_id');
    }

    public function pvd()
    {
        return $this->hasMany('App\Models\VariasiDetail', 'produk_id');
    }
}
