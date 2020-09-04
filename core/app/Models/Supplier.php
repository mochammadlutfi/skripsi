<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';

    protected $fillable = [
        'nama', 'telp', 'daerah_id', 'alamat', 'perwakilan_nama', 'perwakilan_hp', 'bisnis_id'
    ];

    public function daerah()
    {
        return $this->belongsTo('App\Models\Daerah', 'daerah_id', 'id');
    }
}
