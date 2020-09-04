<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BisnisKategori extends Model
{

    protected $table = 'bisnis_kategori';

    protected $fillable = ['nama'];

    public function bisnis()
    {
        return $this->hasOne('App\Models\Bisnis', 'kategori_id');
    }
}
