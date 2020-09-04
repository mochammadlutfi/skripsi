<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Bisnis extends Model
{

    protected $table = 'bisnis';

    protected $fillable =['nama', 'kategori_id', 'metode_akuntasi', 'logo'];

    public function kategori()
    {
        return $this->belongsTo('App\Models\BisnisKategori', 'kategori_id');
    }

    public function outlet()
    {
        return $this->hasMany('App\Models\Outlet');
    }

    public function outlet_pusat()
    {
        return $this->hasOne('App\Models\Outlet', 'bisnis_id')->oldest();
    }
}
