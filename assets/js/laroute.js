(function () {

    var laroute = (function () {

        var routes = {

            absolute: true,
            rootUrl: 'http://localhost/skripsi',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/open","name":"debugbar.openhandler","action":"Barryvdh\Debugbar\Controllers\OpenHandlerController@handle"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/clockwork\/{id}","name":"debugbar.clockwork","action":"Barryvdh\Debugbar\Controllers\OpenHandlerController@clockwork"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/telescope\/{id}","name":"debugbar.telescope","action":"Barryvdh\Debugbar\Controllers\TelescopeController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/assets\/stylesheets","name":"debugbar.assets.css","action":"Barryvdh\Debugbar\Controllers\AssetController@css"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/assets\/javascript","name":"debugbar.assets.js","action":"Barryvdh\Debugbar\Controllers\AssetController@js"},{"host":null,"methods":["DELETE"],"uri":"_debugbar\/cache\/{key}\/{tags?}","name":"debugbar.cache.delete","action":"Barryvdh\Debugbar\Controllers\CacheController@delete"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/user","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"coba","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":null,"action":"App\Http\Controllers\Auth\LoginController@showLoginForm"},{"host":null,"methods":["GET","HEAD"],"uri":"login","name":"login","action":"App\Http\Controllers\Auth\LoginController@showLoginForm"},{"host":null,"methods":["POST"],"uri":"login","name":null,"action":"App\Http\Controllers\Auth\LoginController@login"},{"host":null,"methods":["POST"],"uri":"logout","name":"logout","action":"App\Http\Controllers\Auth\LoginController@logout"},{"host":null,"methods":["GET","HEAD"],"uri":"daftar","name":"register","action":"App\Http\Controllers\Auth\RegisterController@showRegistrationForm"},{"host":null,"methods":["POST"],"uri":"daftar","name":null,"action":"App\Http\Controllers\Auth\RegisterController@register"},{"host":null,"methods":["GET","HEAD"],"uri":"lupa-password","name":"password.request","action":"App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm"},{"host":null,"methods":["POST"],"uri":"getEmail","name":"lupa.getEmail","action":"App\Http\Controllers\Auth\ForgotPasswordController@getEmail"},{"host":null,"methods":["GET","HEAD"],"uri":"lupa\/{token}","name":"password.reset","action":"App\Http\Controllers\Auth\ResetPasswordController@showResetForm"},{"host":null,"methods":["POST"],"uri":"reset","name":"lupa.reset","action":"App\Http\Controllers\Auth\ResetPasswordController@reset"},{"host":null,"methods":["GET","HEAD"],"uri":"email\/verify","name":"verification.notice","action":"App\Http\Controllers\Auth\VerificationController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"email\/verify\/{id}","name":"verification.verify","action":"App\Http\Controllers\Auth\VerificationController@verify"},{"host":null,"methods":["GET","HEAD"],"uri":"email\/resend","name":"verification.resend","action":"App\Http\Controllers\Auth\VerificationController@resend"},{"host":null,"methods":["GET","HEAD"],"uri":"beranda","name":"beranda","action":"App\Http\Controllers\BerandaController@index"},{"host":null,"methods":["POST"],"uri":"json\/wilayah","name":"wilayah.json","action":"App\Http\Controllers\GeneralController@wilayah"},{"host":null,"methods":["POST"],"uri":"json\/getPos","name":"getPos.json","action":"App\Http\Controllers\GeneralController@getPos"},{"host":null,"methods":["GET","HEAD"],"uri":"produk","name":"produk","action":"App\Http\Controllers\ProdukController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"produk\/tambah","name":"produk.tambah","action":"App\Http\Controllers\ProdukController@tambah"},{"host":null,"methods":["POST"],"uri":"produk\/simpan","name":"produk.simpan","action":"App\Http\Controllers\ProdukController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"produk\/edit\/{id}","name":"produk.edit","action":"App\Http\Controllers\ProdukController@edit"},{"host":null,"methods":["POST"],"uri":"produk\/update","name":"produk.update","action":"App\Http\Controllers\ProdukController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"produk\/hapus\/{id}","name":"produk.hapus","action":"App\Http\Controllers\ProdukController@hapus"},{"host":null,"methods":["GET","HEAD"],"uri":"produk\/json","name":"produk.json","action":"App\Http\Controllers\ProdukController@json"},{"host":null,"methods":["GET","HEAD"],"uri":"produk\/stok-awal\/{id}","name":"stok_awal","action":"App\Http\Controllers\StokAwalController@index"},{"host":null,"methods":["POST"],"uri":"produk\/stok-awal-simpan","name":"stok_awal.simpan","action":"App\Http\Controllers\StokAwalController@simpan"},{"host":null,"methods":["POST"],"uri":"produk\/variasi\/tambah","name":"variasi.tambah","action":"App\Http\Controllers\VariasiProdukController@tambah"},{"host":null,"methods":["POST"],"uri":"produk\/variasi\/change-row","name":"variasi.changeRow","action":"App\Http\Controllers\VariasiProdukController@changeRow"},{"host":null,"methods":["GET","HEAD"],"uri":"produk\/variasi\/json-modal","name":"variasi.jsonModal","action":"App\Http\Controllers\VariasiProdukController@jsonModal"},{"host":null,"methods":["GET","HEAD"],"uri":"produk\/variasi\/json-find","name":"variasi.jsonFind","action":"App\Http\Controllers\VariasiProdukController@jsonFind"},{"host":null,"methods":["GET","HEAD"],"uri":"produk\/variasi\/getForm","name":"variasi.getForm","action":"App\Http\Controllers\VariasiProdukController@getForm"},{"host":null,"methods":["GET","HEAD"],"uri":"kategori","name":"kategori","action":"App\Http\Controllers\KategoriController@index"},{"host":null,"methods":["POST"],"uri":"kategori\/json","name":"kategori.json","action":"App\Http\Controllers\KategoriController@json"},{"host":null,"methods":["POST"],"uri":"kategori\/simpan","name":"kategori.simpan","action":"App\Http\Controllers\KategoriController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"kategori\/edit\/{id}","name":"kategori.edit","action":"App\Http\Controllers\KategoriController@edit"},{"host":null,"methods":["POST"],"uri":"kategori\/update","name":"kategori.update","action":"App\Http\Controllers\KategoriController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"kategori\/hapus\/{id}","name":"kategori.hapus","action":"App\Http\Controllers\KategoriController@hapus"},{"host":null,"methods":["GET","HEAD"],"uri":"merk","name":"merk","action":"App\Http\Controllers\MerkController@index"},{"host":null,"methods":["POST"],"uri":"merk\/json","name":"merk.json","action":"App\Http\Controllers\MerkController@json"},{"host":null,"methods":["POST"],"uri":"merk\/simpan","name":"merk.simpan","action":"App\Http\Controllers\MerkController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"merk\/edit\/{id}","name":"merk.edit","action":"App\Http\Controllers\MerkController@edit"},{"host":null,"methods":["POST"],"uri":"merk\/update","name":"merk.update","action":"App\Http\Controllers\MerkController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"merk\/hapus\/{id}","name":"merk.hapus","action":"App\Http\Controllers\MerkController@hapus"},{"host":null,"methods":["GET","HEAD"],"uri":"satuan","name":"satuan","action":"App\Http\Controllers\SatuanController@index"},{"host":null,"methods":["POST"],"uri":"satuan\/json","name":"satuan.json","action":"App\Http\Controllers\SatuanController@json"},{"host":null,"methods":["POST"],"uri":"satuan\/simpan","name":"satuan.simpan","action":"App\Http\Controllers\SatuanController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"satuan\/edit\/{id}","name":"satuan.edit","action":"App\Http\Controllers\SatuanController@edit"},{"host":null,"methods":["POST"],"uri":"satuan\/update","name":"satuan.update","action":"App\Http\Controllers\SatuanController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"satuan\/hapus\/{id}","name":"satuan.hapus","action":"App\Http\Controllers\SatuanController@hapus"},{"host":null,"methods":["GET","HEAD"],"uri":"peramalan","name":"peramalan","action":"App\Http\Controllers\PeramalanController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"peramalan\/json-modal","name":"peramalan.variasi","action":"App\Http\Controllers\PeramalanController@show_variasi"},{"host":null,"methods":["POST"],"uri":"peramalan\/hitung","name":"peramalan.hitung","action":"App\Http\Controllers\PeramalanController@hitung"},{"host":null,"methods":["POST"],"uri":"peramalan\/pembelian","name":"peramalan.pembelian","action":"App\Http\Controllers\PeramalanController@pembelian"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian","name":"pembelian","action":"App\Http\Controllers\PembelianController@index"},{"host":null,"methods":["POST"],"uri":"pembelian\/simpan","name":"pembelian.simpan","action":"App\Http\Controllers\PembelianController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian\/riwayat","name":"pembelian.riwayat","action":"App\Http\Controllers\PembelianController@riwayat"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian\/draft","name":"pembelian.draft","action":"App\Http\Controllers\PembelianController@draft"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian\/edit\/{id_transaksi}","name":"pembelian.edit","action":"App\Http\Controllers\PembelianController@edit"},{"host":null,"methods":["POST"],"uri":"pembelian\/update","name":"pembelian.update","action":"App\Http\Controllers\PembelianController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian\/detail\/{transaksi_id}","name":"pembelian.detail","action":"App\Http\Controllers\PembelianController@detail"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian\/konfirmasi\/{id}","name":"pembelian.konfirmasi","action":"App\Http\Controllers\PembelianController@konfirmasi"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian\/getProduk","name":"pembelian.getProduk","action":"App\Http\Controllers\PembelianController@getProduk"},{"host":null,"methods":["POST"],"uri":"pembelian\/addCart","name":"pembelian.addCart","action":"App\Http\Controllers\PembelianController@addCart"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian\/getCart","name":"pembelian.getCart","action":"App\Http\Controllers\PembelianController@getCart"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian\/editCart","name":"pembelian.editCart","action":"App\Http\Controllers\PembelianController@editCart"},{"host":null,"methods":["POST"],"uri":"pembelian\/deleteCart","name":"pembelian.deleteCart","action":"App\Http\Controllers\PembelianController@deleteCart"},{"host":null,"methods":["POST"],"uri":"pembelian\/updateCart","name":"pembelian.updateCart","action":"App\Http\Controllers\PembelianController@updateCart"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian\/supplier","name":"supplier","action":"App\Http\Controllers\SupplierController@index"},{"host":null,"methods":["POST"],"uri":"pembelian\/supplier\/json","name":"supplier.json","action":"App\Http\Controllers\SupplierController@json"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian\/supplier\/tambah","name":"supplier.tambah","action":"App\Http\Controllers\SupplierController@tambah"},{"host":null,"methods":["POST"],"uri":"pembelian\/supplier\/simpan","name":"supplier.simpan","action":"App\Http\Controllers\SupplierController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian\/supplier\/edit\/{id}","name":"supplier.edit","action":"App\Http\Controllers\SupplierController@edit"},{"host":null,"methods":["POST"],"uri":"pembelian\/supplier\/update","name":"supplier.update","action":"App\Http\Controllers\SupplierController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"pembelian\/supplier\/hapus\/{id}","name":"supplier.hapus","action":"App\Http\Controllers\SupplierController@hapus"},{"host":null,"methods":["GET","HEAD"],"uri":"penjualan","name":"penjualan","action":"App\Http\Controllers\PenjualanController@index"},{"host":null,"methods":["POST"],"uri":"penjualan\/simpan","name":"penjualan.simpan","action":"App\Http\Controllers\PenjualanController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"penjualan\/riwayat","name":"penjualan.riwayat","action":"App\Http\Controllers\PenjualanController@riwayat"},{"host":null,"methods":["GET","HEAD"],"uri":"penjualan\/edit\/{id_transaksi}","name":"penjualan.edit","action":"App\Http\Controllers\PenjualanController@edit"},{"host":null,"methods":["GET","HEAD"],"uri":"penjualan\/detail\/{transaksi_id}","name":"penjualan.detail","action":"App\Http\Controllers\PenjualanController@detail"},{"host":null,"methods":["GET","HEAD"],"uri":"penjualan\/getProduk","name":"penjualan.getProduk","action":"App\Http\Controllers\PenjualanController@getProduk"},{"host":null,"methods":["POST"],"uri":"penjualan\/addCart","name":"penjualan.addCart","action":"App\Http\Controllers\PenjualanController@addCart"},{"host":null,"methods":["GET","HEAD"],"uri":"penjualan\/getCart","name":"penjualan.getCart","action":"App\Http\Controllers\PenjualanController@getCart"},{"host":null,"methods":["GET","HEAD"],"uri":"penjualan\/editCart","name":"penjualan.editCart","action":"App\Http\Controllers\PenjualanController@editCart"},{"host":null,"methods":["POST"],"uri":"penjualan\/deleteCart","name":"penjualan.deleteCart","action":"App\Http\Controllers\PenjualanController@deleteCart"},{"host":null,"methods":["POST"],"uri":"penjualan\/updateCart","name":"penjualan.updateCart","action":"App\Http\Controllers\PenjualanController@updateCart"},{"host":null,"methods":["GET","HEAD"],"uri":"pelanggan","name":"pelanggan","action":"App\Http\Controllers\PelangganController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"pelanggan\/tambah","name":"pelanggan.tambah","action":"App\Http\Controllers\PelangganController@tambah"},{"host":null,"methods":["POST"],"uri":"pelanggan\/simpan","name":"pelanggan.simpan","action":"App\Http\Controllers\PelangganController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"pelanggan\/edit\/{id}","name":"pelanggan.edit","action":"App\Http\Controllers\PelangganController@edit"},{"host":null,"methods":["POST"],"uri":"pelanggan\/update","name":"pelanggan.update","action":"App\Http\Controllers\PelangganController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"pelanggan\/hapus\/{id}","name":"pelanggan.hapus","action":"App\Http\Controllers\PelangganController@hapus"},{"host":null,"methods":["POST"],"uri":"pelanggan\/json","name":"pelanggan.json","action":"App\Http\Controllers\PelangganController@json"},{"host":null,"methods":["GET","HEAD"],"uri":"sales","name":"sales","action":"App\Http\Controllers\SalesController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/tambah","name":"sales.tambah","action":"App\Http\Controllers\SalesController@tambah"},{"host":null,"methods":["POST"],"uri":"sales\/simpan","name":"sales.simpan","action":"App\Http\Controllers\SalesController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/edit\/{id}","name":"sales.edit","action":"App\Http\Controllers\SalesController@edit"},{"host":null,"methods":["POST"],"uri":"sales\/update","name":"sales.update","action":"App\Http\Controllers\SalesController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"sales\/hapus\/{id}","name":"sales.hapus","action":"App\Http\Controllers\SalesController@hapus"},{"host":null,"methods":["POST"],"uri":"sales\/json","name":"sales.json","action":"App\Http\Controllers\SalesController@json"},{"host":null,"methods":["GET","HEAD"],"uri":"kendaraan","name":"kendaraan","action":"App\Http\Controllers\KendaraanController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"kendaraan\/tambah","name":"kendaraan.tambah","action":"App\Http\Controllers\KendaraanController@tambah"},{"host":null,"methods":["POST"],"uri":"kendaraan\/simpan","name":"kendaraan.simpan","action":"App\Http\Controllers\KendaraanController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"kendaraan\/edit\/{id}","name":"kendaraan.edit","action":"App\Http\Controllers\KendaraanController@edit"},{"host":null,"methods":["POST"],"uri":"kendaraan\/update","name":"kendaraan.update","action":"App\Http\Controllers\KendaraanController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"kendaraan\/hapus\/{id}","name":"kendaraan.hapus","action":"App\Http\Controllers\KendaraanController@hapus"},{"host":null,"methods":["POST"],"uri":"kendaraan\/json","name":"kendaraan.json","action":"App\Http\Controllers\KendaraanController@json"},{"host":null,"methods":["GET","HEAD"],"uri":"supplier","name":"supplier","action":"App\Http\Controllers\SupplierController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"supplier\/tambah","name":"supplier.tambah","action":"App\Http\Controllers\SupplierController@tambah"},{"host":null,"methods":["POST"],"uri":"supplier\/simpan","name":"supplier.simpan","action":"App\Http\Controllers\SupplierController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"supplier\/edit\/{id}","name":"supplier.edit","action":"App\Http\Controllers\SupplierController@edit"},{"host":null,"methods":["POST"],"uri":"supplier\/update","name":"supplier.update","action":"App\Http\Controllers\SupplierController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"supplier\/hapus\/{id}","name":"supplier.hapus","action":"App\Http\Controllers\SupplierController@hapus"},{"host":null,"methods":["POST"],"uri":"supplier\/json","name":"supplier.json","action":"App\Http\Controllers\SupplierController@json"},{"host":null,"methods":["GET","HEAD"],"uri":"pengguna","name":"pengguna","action":"App\Http\Controllers\UserController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"pengguna\/tambah","name":"pengguna.tambah","action":"App\Http\Controllers\UserController@tambah"},{"host":null,"methods":["POST"],"uri":"pengguna\/simpan","name":"pengguna.simpan","action":"App\Http\Controllers\UserController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"pengguna\/edit\/{id}","name":"pengguna.edit","action":"App\Http\Controllers\UserController@edit"},{"host":null,"methods":["POST"],"uri":"pengguna\/update","name":"pengguna.update","action":"App\Http\Controllers\UserController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"pengguna\/hapus\/{id}","name":"pengguna.hapus","action":"App\Http\Controllers\UserController@hapus"},{"host":null,"methods":["POST"],"uri":"pengguna\/json","name":"pengguna.json","action":"App\Http\Controllers\UserController@json"},{"host":null,"methods":["GET","HEAD"],"uri":"pengiriman","name":"pengiriman","action":"App\Http\Controllers\PengirimanController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"pengiriman\/jadwal\/{id}","name":"pengiriman.jadwal","action":"App\Http\Controllers\PengirimanController@jadwal"},{"host":null,"methods":["POST"],"uri":"pengiriman\/konfirmasi","name":"pengiriman.konfirmasi","action":"App\Http\Controllers\PengirimanController@konfirmasi"},{"host":null,"methods":["GET","HEAD"],"uri":"pengiriman\/riwayat","name":"pengiriman.riwayat","action":"App\Http\Controllers\PengirimanController@riwayat"},{"host":null,"methods":["GET","HEAD"],"uri":"pengiriman\/edit\/{id}","name":"pengiriman.edit","action":"App\Http\Controllers\PengirimanController@edit"},{"host":null,"methods":["POST"],"uri":"pengiriman\/update","name":"pengiriman.update","action":"App\Http\Controllers\PengirimanController@update"},{"host":null,"methods":["POST"],"uri":"pengiriman\/perbaikan","name":"pengiriman.perbaikan","action":"App\Http\Controllers\PengirimanController@perbaikan"},{"host":null,"methods":["GET","HEAD"],"uri":"retur-pembelian","name":"returbeli","action":"App\Http\Controllers\ReturBeliController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"retur-pembelian\/tambah\/{id}","name":"returbeli.tambah","action":"App\Http\Controllers\ReturBeliController@tambah"},{"host":null,"methods":["POST"],"uri":"retur-pembelian\/simpan","name":"returbeli.simpan","action":"App\Http\Controllers\ReturBeliController@simpan"},{"host":null,"methods":["GET","HEAD"],"uri":"retur-pembelian\/detail\/{id}","name":"returbeli.detail","action":"App\Http\Controllers\ReturBeliController@detail"},{"host":null,"methods":["GET","HEAD"],"uri":"retur-pembelian\/edit\/{id}","name":"returbeli.edit","action":"App\Http\Controllers\ReturBeliController@edit"},{"host":null,"methods":["POST"],"uri":"retur-pembelian\/update","name":"returbeli.update","action":"App\Http\Controllers\ReturBeliController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"retur-pembelian\/hapus\/{id}","name":"returbeli.hapus","action":"App\Http\Controllers\ReturBeliController@hapus"},{"host":null,"methods":["GET","HEAD"],"uri":"retur-pembelian\/getProduk","name":"returbeli.getProduk","action":"App\Http\Controllers\ReturBeliController@getProduk"},{"host":null,"methods":["POST"],"uri":"retur-pembelian\/addCart","name":"returbeli.addCart","action":"App\Http\Controllers\ReturBeliController@addCart"},{"host":null,"methods":["GET","HEAD"],"uri":"retur-pembelian\/getCart","name":"returbeli.getCart","action":"App\Http\Controllers\ReturBeliController@getCart"},{"host":null,"methods":["GET","HEAD"],"uri":"retur-pembelian\/editCart","name":"returbeli.editCart","action":"App\Http\Controllers\ReturBeliController@editCart"},{"host":null,"methods":["POST"],"uri":"retur-pembelian\/deleteCart","name":"returbeli.deleteCart","action":"App\Http\Controllers\ReturBeliController@deleteCart"},{"host":null,"methods":["POST"],"uri":"retur-pembelian\/updateCart","name":"returbeli.updateCart","action":"App\Http\Controllers\ReturBeliController@updateCart"},{"host":null,"methods":["GET","HEAD"],"uri":"profil","name":"profil","action":"App\Http\Controllers\UserController@profil"},{"host":null,"methods":["POST"],"uri":"update-profil","name":"update.profil","action":"App\Http\Controllers\UserController@update_profil"},{"host":null,"methods":["GET","POST","HEAD"],"uri":"ubah-password","name":"ubah_pw","action":"App\Http\Controllers\UserController@ubah_pw"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                if (this.absolute && this.isOtherHost(route)){
                    return "//" + route.host + "/" + uri + qs;
                }

                return this.getCorrectUrl(uri + qs);
            },

            isOtherHost: function (route){
                return route.host && route.host != window.location.hostname;
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if ( ! this.absolute) {
                    return url;
                }

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // laroute.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // laroute.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // laroute.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // laroute.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // laroute.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // laroute.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.laroute = laroute;
    }

}).call(this);

