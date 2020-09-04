<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/coba', function () {
    auth()->user()->assignRole('Kepala Delivery');
    // return view('welcome');
    // Role::create(['name' => 'Admin']);
    // Role::create(['name' => 'General Manager']);
    // Role::create(['name' => 'Merchandiser']);
    // Role::create(['name' => 'Kepala Delivery']);
});
Route::get('/','Auth\LoginController@showLoginForm');
Route::namespace('Auth')->group(function(){
    //Login Routes
    Route::get('/login','LoginController@showLoginForm')->name('login');
    Route::post('/login','LoginController@login');
    Route::post('/logout','LoginController@logout')->name('logout');

    //Register Routes
    Route::get('/daftar','RegisterController@showRegistrationForm')->name('register');
    Route::post('/daftar','RegisterController@register');

    //Forgot Password Routes
    Route::get('/lupa-password','ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/getEmail','ForgotPasswordController@getEmail')->name('lupa.getEmail');

    //Reset Password Routes
    Route::get('/lupa/{token}','ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/reset','ResetPasswordController@reset')->name('lupa.reset');

    // Email Verification Route(s)
    Route::get('email/verify','VerificationController@show')->name('verification.notice');
    Route::get('email/verify/{id}','VerificationController@verify')->name('verification.verify');
    Route::get('email/resend','VerificationController@resend')->name('verification.resend');

});
// Auth::routes();

Route::get('/beranda', 'BerandaController@index')->name('beranda');

Route::post('json/wilayah','GeneralController@wilayah')->name('wilayah.json');
Route::post('json/getPos','GeneralController@getPos')->name('getPos.json');
// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix' => 'produk'], function () {
    Route::get('/','ProdukController@index')->name('produk');
    Route::get('/tambah','ProdukController@tambah')->name('produk.tambah');
    Route::post('/simpan','ProdukController@simpan')->name('produk.simpan');
    Route::get('/edit/{id}','ProdukController@edit')->name('produk.edit');
    Route::post('/update','ProdukController@update')->name('produk.update');
    Route::get('/hapus/{id}','ProdukController@hapus')->name('produk.hapus');
    Route::get('/json','ProdukController@json')->name('produk.json');


    Route::get('/stok-awal/{id}','StokAwalController@index')->name('stok_awal');
    Route::post('/stok-awal-simpan','StokAwalController@simpan')->name('stok_awal.simpan');


    Route::group(['prefix' => 'variasi'], function () {
        Route::post('/tambah','VariasiProdukController@tambah')->name('variasi.tambah');
        Route::post('/change-row','VariasiProdukController@changeRow')->name('variasi.changeRow');
        Route::get('/json-modal','VariasiProdukController@jsonModal')->name('variasi.jsonModal');
        Route::get('/json-find','VariasiProdukController@jsonFind')->name('variasi.jsonFind');
        Route::get('/getForm','VariasiProdukController@getForm')->name('variasi.getForm');
    });
});

Route::group(['prefix' => 'kategori'], function () {
    Route::get('/','KategoriController@index')->name('kategori');
    Route::post('/json','KategoriController@json')->name('kategori.json');
    Route::post('/simpan','KategoriController@simpan')->name('kategori.simpan');
    Route::get('/edit/{id}','KategoriController@edit')->name('kategori.edit');
    Route::post('/update','KategoriController@update')->name('kategori.update');
    Route::get('/hapus/{id}','KategoriController@hapus')->name('kategori.hapus');
});

Route::group(['prefix' => 'merk'], function () {
    Route::get('/','MerkController@index')->name('merk');
    Route::post('/json','MerkController@json')->name('merk.json');
    Route::post('/simpan','MerkController@simpan')->name('merk.simpan');
    Route::get('/edit/{id}','MerkController@edit')->name('merk.edit');
    Route::post('/update','MerkController@update')->name('merk.update');
    Route::get('/hapus/{id}','MerkController@hapus')->name('merk.hapus');
});

Route::group(['prefix' => 'satuan'], function () {
    Route::get('/','SatuanController@index')->name('satuan');
    Route::post('/json','SatuanController@json')->name('satuan.json');
    Route::post('/simpan','SatuanController@simpan')->name('satuan.simpan');
    Route::get('/edit/{id}','SatuanController@edit')->name('satuan.edit');
    Route::post('/update','SatuanController@update')->name('satuan.update');
    Route::get('/hapus/{id}','SatuanController@hapus')->name('satuan.hapus');
});

Route::group(['prefix' => 'peramalan'], function () {
    Route::get('/','PeramalanController@index')->name('peramalan');
    Route::get('/json-modal','PeramalanController@show_variasi')->name('peramalan.variasi');
    Route::post('/hitung','PeramalanController@hitung')->name('peramalan.hitung');
    Route::post('/pembelian','PeramalanController@pembelian')->name('peramalan.pembelian');
});

Route::group(['prefix' => 'pembelian'], function () {
    Route::get('/','PembelianController@index')->name('pembelian');
    Route::post('/simpan','PembelianController@simpan')->name('pembelian.simpan');
    Route::get('/riwayat','PembelianController@riwayat')->name('pembelian.riwayat');
    Route::get('/draft','PembelianController@draft')->name('pembelian.draft');
    Route::get('/edit/{id_transaksi}','PembelianController@edit')->name('pembelian.edit');
    Route::post('/update','PembelianController@update')->name('pembelian.update');
    Route::get('/detail/{transaksi_id}','PembelianController@detail')->name('pembelian.detail');
    Route::get('/konfirmasi/{id}','PembelianController@konfirmasi')->name('pembelian.konfirmasi');

    // Keranjang Pembelian
    Route::get('/getProduk','PembelianController@getProduk')->name('pembelian.getProduk');
    Route::post('/addCart','PembelianController@addCart')->name('pembelian.addCart');
    Route::get('/getCart','PembelianController@getCart')->name('pembelian.getCart');
    Route::get('/editCart','PembelianController@editCart')->name('pembelian.editCart');
    Route::POST('/deleteCart','PembelianController@deleteCart')->name('pembelian.deleteCart');
    Route::POST('/updateCart','PembelianController@updateCart')->name('pembelian.updateCart');

    Route::group(['prefix' => 'supplier'], function () {
        Route::get('/','SupplierController@index')->name('supplier');
        Route::post('/json','SupplierController@json')->name('supplier.json');
        Route::get('/tambah','SupplierController@tambah')->name('supplier.tambah');
        Route::post('/simpan','SupplierController@simpan')->name('supplier.simpan');
        Route::get('/edit/{id}','SupplierController@edit')->name('supplier.edit');
        Route::post('/update','SupplierController@update')->name('supplier.update');
        Route::get('/hapus/{id}','SupplierController@hapus')->name('supplier.hapus');
    });
});

Route::group(['prefix' => 'penjualan'], function () {
    Route::get('/','PenjualanController@index')->name('penjualan');
    Route::post('/simpan','PenjualanController@simpan')->name('penjualan.simpan');
    Route::get('/riwayat','PenjualanController@riwayat')->name('penjualan.riwayat');
    Route::get('/edit/{id_transaksi}','PenjualanController@edit')->name('penjualan.edit');
    Route::get('/detail/{transaksi_id}','PenjualanController@detail')->name('penjualan.detail');

    // Keranjang Penjualan
    Route::get('/getProduk','PenjualanController@getProduk')->name('penjualan.getProduk');
    Route::post('/addCart','PenjualanController@addCart')->name('penjualan.addCart');
    Route::get('/getCart','PenjualanController@getCart')->name('penjualan.getCart');
    Route::get('/editCart','PenjualanController@editCart')->name('penjualan.editCart');
    Route::POST('/deleteCart','PenjualanController@deleteCart')->name('penjualan.deleteCart');
    Route::POST('/updateCart','PenjualanController@updateCart')->name('penjualan.updateCart');
});

Route::group(['prefix' => 'pelanggan'], function () {
    Route::get('/','PelangganController@index')->name('pelanggan');
    Route::get('/tambah','PelangganController@tambah')->name('pelanggan.tambah');
    Route::post('/simpan','PelangganController@simpan')->name('pelanggan.simpan');
    Route::get('/edit/{id}','PelangganController@edit')->name('pelanggan.edit');
    Route::post('/update','PelangganController@update')->name('pelanggan.update');
    Route::get('/hapus/{id}','PelangganController@hapus')->name('pelanggan.hapus');
    Route::post('/json','PelangganController@json')->name('pelanggan.json');
});

Route::group(['prefix' => 'sales'], function () {
    Route::get('/','SalesController@index')->name('sales');
    Route::get('/tambah','SalesController@tambah')->name('sales.tambah');
    Route::post('/simpan','SalesController@simpan')->name('sales.simpan');
    Route::get('/edit/{id}','SalesController@edit')->name('sales.edit');
    Route::post('/update','SalesController@update')->name('sales.update');
    Route::get('/hapus/{id}','SalesController@hapus')->name('sales.hapus');
    Route::post('/json','SalesController@json')->name('sales.json');
});

Route::group(['prefix' => 'kendaraan'], function () {
    Route::get('/','KendaraanController@index')->name('kendaraan');
    Route::get('/tambah','KendaraanController@tambah')->name('kendaraan.tambah');
    Route::post('/simpan','KendaraanController@simpan')->name('kendaraan.simpan');
    Route::get('/edit/{id}','KendaraanController@edit')->name('kendaraan.edit');
    Route::post('/update','KendaraanController@update')->name('kendaraan.update');
    Route::get('/hapus/{id}','KendaraanController@hapus')->name('kendaraan.hapus');
    Route::post('/json','KendaraanController@json')->name('kendaraan.json');
});

Route::group(['prefix' => 'supplier'], function () {
    Route::get('/','SupplierController@index')->name('supplier');
    Route::get('/tambah','SupplierController@tambah')->name('supplier.tambah');
    Route::post('/simpan','SupplierController@simpan')->name('supplier.simpan');
    Route::get('/edit/{id}','SupplierController@edit')->name('supplier.edit');
    Route::post('/update','SupplierController@update')->name('supplier.update');
    Route::get('/hapus/{id}','SupplierController@hapus')->name('supplier.hapus');
    Route::post('/json','SupplierController@json')->name('supplier.json');
});

Route::group(['prefix' => 'pengguna'], function () {
    Route::get('/','UserController@index')->name('pengguna');
    Route::get('/tambah','UserController@tambah')->name('pengguna.tambah');
    Route::post('/simpan','UserController@simpan')->name('pengguna.simpan');
    Route::get('/edit/{id}','UserController@edit')->name('pengguna.edit');
    Route::post('/update','UserController@update')->name('pengguna.update');
    Route::get('/hapus/{id}','UserController@hapus')->name('pengguna.hapus');
    Route::post('/json','UserController@json')->name('pengguna.json');
});

Route::group(['prefix' => 'pengiriman'], function () {
    Route::get('/','PengirimanController@index')->name('pengiriman');
    Route::get('/jadwal/{id}','PengirimanController@jadwal')->name('pengiriman.jadwal');
    Route::post('/konfirmasi','PengirimanController@konfirmasi')->name('pengiriman.konfirmasi');
    Route::get('/riwayat','PengirimanController@riwayat')->name('pengiriman.riwayat');
    Route::get('/edit/{id}','PengirimanController@edit')->name('pengiriman.edit');
    Route::post('/update','PengirimanController@update')->name('pengiriman.update');
    Route::post('/perbaikan','PengirimanController@perbaikan')->name('pengiriman.perbaikan');
});

Route::group(['prefix' => 'retur-pembelian'], function () {
    Route::get('/','ReturBeliController@index')->name('returbeli');
    Route::get('/tambah/{id}','ReturBeliController@tambah')->name('returbeli.tambah');
    Route::post('/simpan','ReturBeliController@simpan')->name('returbeli.simpan');
    Route::get('/detail/{id}','ReturBeliController@detail')->name('returbeli.detail');
    Route::get('/edit/{id}','ReturBeliController@edit')->name('returbeli.edit');
    Route::post('/update','ReturBeliController@update')->name('returbeli.update');
    Route::get('/hapus/{id}','ReturBeliController@hapus')->name('returbeli.hapus');

    // Keranjang Pembelian
    Route::get('/getProduk','ReturBeliController@getProduk')->name('returbeli.getProduk');
    Route::post('/addCart','ReturBeliController@addCart')->name('returbeli.addCart');
    Route::get('/getCart','ReturBeliController@getCart')->name('returbeli.getCart');
    Route::get('/editCart','ReturBeliController@editCart')->name('returbeli.editCart');
    Route::POST('/deleteCart','ReturBeliController@deleteCart')->name('returbeli.deleteCart');
    Route::POST('/updateCart','ReturBeliController@updateCart')->name('returbeli.updateCart');
});


Route::get('/profil','UserController@profil')->name('profil');
Route::post('/update-profil','UserController@update_profil')->name('update.profil');
Route::match(['get', 'post'], '/ubah-password', 'UserController@ubah_pw')->name('ubah_pw');
