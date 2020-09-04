<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'nama', 'ktp', 'telp', 'hp', 'daerah_id', 'alamat', 'email', 'keterangan'
    ];

    public function daerah()
    {
        return $this->belongsTo('App\Models\Daerah', 'daerah_id', 'id');
    }
}
