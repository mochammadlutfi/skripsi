<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $fillable = [
        'nama', 'telp', 'hp', 'daerah_id', 'alamat', 'email', 'perwakilan_nama', 'perwakilan_kontak', 'keterangan', 'lat', 'lng'
    ];

    public function daerah()
    {
        return $this->belongsTo('App\Models\Daerah', 'daerah_id', 'id');
    }
}
