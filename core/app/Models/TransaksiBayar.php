<?php

namespace App\Models;

// use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Model;

class TransaksiBayar extends Model
{
    // use Multitenantable;
    protected $table = 'transaksi_bayar';

    protected $fillable = [
        'transaksi_id', 'jumlah', 'method', 'note'
    ];

    public function merk()
    {
        return $this->belongsTo('App\Models\Merk', 'merk_id');
    }
}
