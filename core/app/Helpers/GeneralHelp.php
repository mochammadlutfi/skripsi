<?php

use App\Models\Daerah;
use App\Models\VariasiDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

if (!function_exists('get_daerah')) {

    /**
     * Konversi tanggal kedalam format MYSQL
     *
     * @param string $date
     * @param bool $time (default = false)
     * @return strin
     */
    function get_daerah($daerah_id)
    {
        $daerah = Daerah::find($daerah_id);

        $alamat  = ucwords(strtolower($daerah->urban)).', ';
        $alamat .= ucwords(strtolower($daerah->sub_district)).',<br>';
        $alamat .= ucwords(strtolower($daerah->city)).', ';
        $alamat .= ucwords(strtolower($daerah->provinsi->province_name));
        return $alamat;
    }
}

if (!function_exists('get_status')) {

    /**
     * Menampilkan Status Transaksi
     *
     * @param string $status
     * @return strin
     */
    function get_status($status)
    {
        if($status == 'diterima')
        {
            $sts = '<span class="badge badge-success">'. ucwords($status).'</span>';
        }else if($status == 'dipesan' || $status == 'konfirmasi')
        {
            $sts = '<span class="badge badge-warning">'. ucwords($status).'</span>';
        }else if($status == 'final')
        {
            $sts = '<span class="badge badge-success">'. ucwords($status).'</span>';

        }else if($status == 'draft' || $status == 'pending')
        {
            $sts = '<span class="badge badge-danger">'. ucwords($status).'</span>';
        }

        return $sts;
    }
}

if (!function_exists('get_bayar_status')) {

    /**
     * Menampilkan Status Pembayaran
     *
     * @param string $status
     * @return strin
     */
    function get_bayar_status($status)
    {
        if($status == 'lunas')
        {
            $sts = '<span class="badge badge-success">'. ucwords($status).'</span>';
        }else if($status == 'sebagian')
        {
            $sts = '<span class="badge badge-warning">'. ucwords($status).'</span>';
        }else if($status == 'belum dibayar')
        {
            $sts = '<span class="badge badge-error">'. ucwords($status).'</span>';
        }

        return $sts;
    }
}

if (!function_exists('get_pengiriman_status')) {

    /**
     * Menampilkan Status Transaksi
     *
     * @param string $status
     * @return strin
     */
    function get_pengiriman_status($status)
    {
        if($status == '1')
        {
            $sts = '<span class="badge badge-primary">Sedang Dikirim</span>';
        }else if($status == '0')
        {
            $sts = '<span class="badge badge-warning">Belum Dikirim</span>';
        }else if($status == '2')
        {
            $sts = '<span class="badge badge-danger">Bermasalah</span>';
        }else if($status == '3')
        {
            $sts = '<span class="badge badge-success">Diterima</span>';
        }

        return $sts;
    }
}

if (!function_exists('get_stok_max')) {

    /**
     * Konversi tanggal kedalam format MYSQL
     *
     * @param string $date
     * @param bool $time (default = false)
     * @return strin
     */
    function get_stok_max($variasi_id)
    {
        $stok = VariasiDetail::where('variasi_id', $variasi_id)->first();
        if($stok)
        {
           return $stok->qty_tersedia;
        }
    }
}

if (!function_exists('get_namaProduk')) {

    /**
     * Menampilkan Nama Produk
     *
     * @param string $date
     * @param bool $time (default = false)
     * @return strin
     */
    function get_namaProduk($produk, $variasi)
    {
        if($variasi == '')
        {
           return $produk;
        }else{
            return $produk.', '.$variasi;
        }
    }
}

if (!function_exists('get_tgl')) {

    /**
     * Menampilkan Nama Produk
     *
     * @param string $date
     * @param bool $time (default = false)
     * @return strin
     */
    function get_tgl($tgl)
    {
        Carbon::setLocale('id');
        return Carbon::parse($tgl)->translatedFormat('d F Y');
    }
}

if (!function_exists('bln_nomor')) {

    /**
     * Menampilkan Nama Produk
     *
     * @param string $date
     * @param bool $time (default = false)
     * @return strin
     */
    function bln_nomor($bln)
    {
        switch ($bln)
        {
            case "Januari":
                return 1;
                break;
            case "Februari":
                return 2;
                break;
            case "Maret":
                return 3;
                break;
            case "April":
                return 4;
                break;
            case "Mei":
                return 5;
                break;
            case "Juni":
                return 6;
                break;
            case "Juli":
                return 7;
                break;
            case "Agustus":
                return 8;
                break;
            case "September":
                return 9;
                break;
            case "Oktober":
                return 10;
                break;
            case "November":
                return 11;
                break;
            case "Desember":
                return 12;
                break;
        }
    }
}




if (!function_exists('get_no_transaksi')) {

    /**
     * Menampilkan Nama Produk
     *
     * @param string $date
     * @param bool $time (default = false)
     * @return strin
     */
    function get_no_transaksi($tipe)
    {
        if($tipe == 'jual')
        {
            $kd_trans = 'P';
            $q = DB::table('transaksi')->select(DB::raw('MAX(RIGHT(invoice_no,5)) AS kd_max'))->where('tipe', 'jual');
        }elseif($tipe == 'beli')
        {
            $kd_trans = 'B';
            $q = DB::table('transaksi')->select(DB::raw('MAX(RIGHT(invoice_no,5)) AS kd_max'))->where('tipe', 'beli');
        }elseif($tipe == 'retur_beli')
        {
            $kd_trans = 'RB';
            $q = DB::table('transaksi')->select(DB::raw('MAX(RIGHT(invoice_no,5)) AS kd_max'))->where('tipe', 'beli');
        }
        $no = 1;
        date_default_timezone_set('Asia/Jakarta');
        if($q->count() > 0){
            foreach($q->get() as $k){
                return $kd_trans.date('ymd').sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return $kd_trans.date('ymd').sprintf("%05s", $no);
        }
    }
}


