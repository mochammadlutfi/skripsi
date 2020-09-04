<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Daerah extends Model
{
    protected $table = 'daerah';

    public function provinsi()
    {
        return $this->belongsTo('App\Models\Provinsi', 'province_code', 'province_code');
    }
}
