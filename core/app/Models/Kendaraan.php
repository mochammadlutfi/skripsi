<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

    protected $fillable = [
        'jenis', 'no_polisi', 'tipe', 'max_kapasitas',
    ];
}
