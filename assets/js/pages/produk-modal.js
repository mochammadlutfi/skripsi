jQuery(document).ready(function () {

    $("#field-merk").select2({
        placeholder: 'Pilih Merk',
        allowClear: true,
        ajax: {
            url: laroute.route('merk.json'),
            type: 'POST',
            dataType: 'JSON',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    }).on('select2:unselecting', function(e) {
        $(this).val(null).trigger('change');
        e.preventDefault();
    });

    $("#field-merk").change(function(){
        getProduk(null, null, $(this).val() , 1, 'filter');
    });

    $("#field-kategori").select2({
        placeholder: 'Pilih Kategori',
        allowClear: true,
        ajax: {
            url: laroute.route('kategori.json'),
            type: 'POST',
            dataType: 'JSON',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    }).on('select2:unselecting', function(e) {
        $(this).val(null).trigger('change');
        e.preventDefault();
    });

    $("#field-kategori").change(function(){
        getProduk(null, $(this).val() , null, 1,'filter');
    });

    $(document).on('click', 'div.product_box', function() {
        $.ajax({
            method: 'GET',
            url: laroute.route('variasi.jsonModal'),
            data: {
                produk_id: $(this).data('produk_id'),
                tipe : $('#tipe_transaksi').val(),
            },
            dataType: 'JSON',
            success: function(result) {
                $('#modal_produkFoto').attr('src', result.produk_foto);
                $('#modal_produkNama').html(result.produk_nama);
                $('#modal_produkKategori').html(result.produk_kategori);
                $('table#variasi_table tbody').html('');
                result.variasi.forEach(function(variasi, index){
                    $('table#variasi_table tbody').append(variasi);
                });
                if($('input#row_count').val() >= 1)
                {
                    $('#variasi_table tbody').find('tr').each(function() {
                        var variasi_id = $(this).find('.variasi_id_modal').val();
                        var qty_modal = $(this).find('.kuantias_modal');
                        $('#penjualan_table tbody').find('tr').each(function() {
                            // var r_v = $(this).find('.row_variasi_id').val().split('-');
                            var row_v_id = $(this).find('.row_variasi_id').val();
                            if (row_v_id == variasi_id) {
                                var qty_cart = $(this).find('.jumlah_beli').val();
                                qty_modal.val(qty_cart);
                            }
                        });
                    });
                }
                $('#variasiModal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            },
        });
    });

    // Navigasi Next Page Modal Produk
    $(document).on('click', 'button#nextProduk', function() {     
        current_page = parseInt($('#produk_current_page').val());
        current_page += 1;
        getProduk(null, null, null, current_page);
    });

    // Navigasi Prev Page Modal Produk
    $(document).on('click', 'button#prevProduk', function() {
        current_page = parseInt($('#produk_current_page').val());
        current_page -= 1;
        getProduk(null, null, null);
    });

    // Pencarian Produk
    $(document).on('input', 'input#field-keyword', function() {
        keyword = $(this).val();
        getProduk(keyword, null, null);
    });
    
    $(document).on('click', 'button#btn-cari_produk', function() {      
        $('div#list-produk-body').html('');
        getProduk(null, null, null, null, 'page');
        // $('#produkModal').modal();
        $('#produkModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

});

// Get Data Produk
function getProduk(keyword = null, kategori_id = null, merk_id = null, page = 1) {
    $.ajax({
        method: 'GET',
        url: laroute.route('produk.json'),
        data: {
            keyword: keyword,
            kategori_id: kategori_id,
            merk_id: merk_id,
            page: page,
        },
        dataType: 'JSON',
        success: function(response) {
            if(!response.next_page)
            {
                $('#nextProduk').prop('disabled', true);
            }else{
                $('#nextProduk').prop('disabled', false);
            }
            if(!response.prev_page)
            {
                $('#prevProduk').prop('disabled', true);
            }else{
                $('#prevProduk').prop('disabled', false);
            }
            $('#produk_nav span').html(response.navigasi);
            $('#produk_current_page').val(response.current_page);

            if(response.tipe == 'page')
            {
                if(response.html == '')
                {
                    return;
                }else{
                    $('div#list-produk-body').append(response.html);
                }
            }else{
                $('div#list-produk-body').html('');
                $('div#list-produk-body').append(response.html);
            }
        },
    });
}