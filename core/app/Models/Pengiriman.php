<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';

    protected $fillable = [
        'transaksi_id', 'pelanggan_id', 'kendaraan_id', 'tgl_kirim', 'status', 'beban', 'rute', 'urutan'
    ];

    public function transaksi()
    {
        return $this->belongsTo('App\Models\Transaksi', 'transaksi_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Pelanggan', 'pelanggan_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo('App\Models\Kendaraan', 'kendaraan_id');
    }
}
