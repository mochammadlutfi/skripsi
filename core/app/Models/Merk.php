<?php

namespace App\Models;

// use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;

class Merk extends Model
{
    // use Multitenantable;

    protected $table = 'merk';


    public function produk()
    {
        return $this->hasOne('App\Models\Produk', 'merk_id');
    }
}
