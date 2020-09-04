<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{

    protected $table = 'transaksi';

    protected $fillable = [
        'bisnis_id', 'outlet_id', 'tipe', 'status', 'bayar_status', 'invoice_no', 'sales_id', 'pelanggan_id',
        'supplier_id', 'ref_no', 'tgl_transaksi', 'total', 'diskon_tipe', 'diskon_nilai', 'detail_pengiriman',
        'biaya_pengiriman', 'stok_awal_produk_id', 'keterangan', 'ket_staf', 'final_total', 'dibuat_oleh', 'retur_induk', 'jumlah_pengembalian'

    ];

    public function bayaran()
    {
        return $this->hasMany('App\Models\TransaksiBayar', 'transaksi_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Pelanggan', 'pelanggan_id', 'id');
    }

    public function sales()
    {
        return $this->belongsTo('App\Models\Sales', 'sales_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'dibuat_oleh', 'id');
    }

    public function pembelian()
    {
        return $this->hasMany(\App\Models\Pembelian::class);
    }

    public function penjualan()
    {
        return $this->hasMany(\App\Models\Penjualan::class);
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Supplier::class);
    }

    public function pengiriman()
    {
        return $this->hasOne(\App\Models\Pengiriman::class);
    }

    public function induk_retur()
    {
        return $this->belongsTo('App\Models\Transaksi','retur_induk');
    }
}
