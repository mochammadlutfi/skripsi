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
            url: laroute.route('peramalan.variasi'),
            data: {
                produk_id: $(this).data('produk_id'),
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
                $('#form-peramalan')[0].reset();
                $('#variasiModal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            },
        });
    });
    // var fullmonth_array = $.datepicker.regional['id'].monthNames;
    $("#field-tgl_target").datepicker({
        format: "mm-yyyy",
        dayViewHeaderFormat : 'MMMM YYYY',
        viewMode: "months", 
        minViewMode: "months",
        language: "id",
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
        $('#produkModal').modal();
    });

    $('#form-peramalan').submit(function(e) { 
        e.preventDefault();
        var formData = new FormData($('#form-peramalan')[0]);
        $.ajax({
            url: laroute.route('peramalan.hitung'),
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: ' ',
                    imageUrl: laroute.url('assets/img/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function (response) {
                Swal.close();
                if (response.fail == false) {
                    $("#variasiModal").modal("hide");
                    $('#judul').html(response.judul);
                    $('#target_peramalan').html(response.target);
                    $('#hasil_peramalan').html(response.peramalan);
                    $('#peramalan_hasil').val(response.hasil);
                    $('#peramalan_variasi_id').val(response.variasi_id);
                    $('#peramalanModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    })
                }else{
                    if(response.tipe == 'input')
                    {
                        for (control in response.errors) {
                            $('#field-' + control).addClass('is-invalid');
                            $('#error-' + control).html(response.errors[control]);
                        }
                    }else{
                        Swal.fire({
                            title: "Gagal!",
                            text: response.errors,
                            timer: 3000,
                            showConfirmButton: false,
                            icon: 'error'
                        });
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        });
    });

    $('#form-hasil').submit(function(e) { 
        e.preventDefault();
        var formData = new FormData($('#form-hasil')[0]);
        $.ajax({
            url: laroute.route('peramalan.pembelian'),
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: ' ',
                    imageUrl: laroute.url('assets/img/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function (response) {
                Swal.close();
                if (response.fail == false) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: 'Hasil Peramalan Ditambahkan Di Keranjang Pembelian!',
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                }else{
                    Swal.fire({
                        title: "Gagal!",
                        text: 'Terjadi Permasalahan Proses Penambahan!',
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'error'
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.close();
            }
        });
    });

});

// Get Data Produk
function getProduk(keyword = null, kategori_id = null, merk_id = null, page = 1) {
    $.ajax({
        method: 'GET',
        url: laroute.route('peramalan'),
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