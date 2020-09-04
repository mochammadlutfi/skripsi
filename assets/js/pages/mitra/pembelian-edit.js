jQuery(document).ready(function () {
 
    get_cart();
    supplierSelect = $("#field-supplier").select2({
        placeholder: 'Cari Supplier',
        ajax: {
            url: laroute.route('supplier.json'),
            type: 'POST',
            dataType: 'JSON',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

    
    supplierOption = new Option($('#field-supplier').attr("data-text"), $('#field-supplier').attr("data-id"), true, true);
    supplierSelect.append(supplierOption).trigger('change');


    $(document).on('click', 'div.product_box', function() {
        $.ajax({
            method: 'GET',
            url: laroute.route('variasi.jsonModal'),
            data: {
                produk_id: $(this).data('produk_id'),
                tipe : 'pembelian',
            },
            dataType: 'JSON',
            success: function(result) {
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
                        $('#pembelian_table tbody').find('tr').each(function() {
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

    $('#field-tgl_transaksi').datepicker({
        startDate: '1-1-2018',
        format: 'dd-mm-yyyy',
        language: 'id',
    });

    $('#form-produkvariasi').submit(function(e) { 
        e.preventDefault();
        var formData = new FormData($('#form-produkvariasi')[0]);
        formData.append('row_count', $('#row_count').val());
        $.ajax({
            url: laroute.route('pembelian.addCart'),
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
                if (response.fail == false) {
                    Swal.fire({
                        title: "Berhasil",
                        text: 'Produk Berhasil Dihapus Dari Keranjang Belanja!',
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    // Tutup Modal Produk
                    $('#variasiModal').modal('hide');
                    $('#produkModal').modal('hide');
                    // Tampilkan Keranjang Pembelian

                    $('#pembelian_table tbody').html(response.html);
                    $('#pembelian_table tfoot').find('.total_subtotal').text(__currency_trans_from_en(response.sub_total, true, true));
                    __write_number($('input#total_subtotal_input'), response.sub_total, true);
        
                    $('#grand_total').text(__currency_trans_from_en(response.total, true, true));
                    __write_number($('input#grand_total_hidden'), response.total, true);

                    __write_number($('input#row_count'), response.total_item, true);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error adding / update data');

            }
        });
    });

    $('#form-pembelian').submit(function(e) { 
        e.preventDefault();
        var formData = new FormData($('#form-pembelian')[0]);
        $.ajax({
            url: laroute.route('pembelian.update'),
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                Swal.fire({
                    title: 'Tunggu Sebentar...',
                    text: 'Data Sedang Diproses!',
                    imageUrl: laroute.url('assets/img/loading.gif', ['']),
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });
            },
            success: function (response) {
                if (response.fail == false) {
                    Swal.fire({
                        title: `Berhasil!`,
                        showConfirmButton: false,
                        icon: 'success',
                        html: `Draft Pembelian Berhasil Disimpan!?
                            <br><br>
                            <button type="button" class="btn btn-keluar btn-alt-danger"><i class="si si-close mr-1"></i>Keluar</button>
                            <button type="button" class="btn btn-detail btn-alt-primary"><i class="si si-doc mr-1"></i>Lihat Data</button>`,
                        showCancelButton: false,
                        showConfirmButton: false,
                        onBeforeOpen: () => {
                            const keluar = document.querySelector('.btn-keluar')
                            const detail = document.querySelector('.btn-detail')
                
                            keluar.addEventListener('click', () => {
                                // window.setTimeout(function(){
                                    location.href = laroute.route('pembelian.draft');
                                // } ,1000);
                            })
                
                            detail.addEventListener('click', () => {
                                // window.setTimeout(function(){
                                    location.href = response.detail_url;
                                // } ,1000);
                            })
                        }
                    });
                }else{
                    Swal.close();
                    if(response.pesan)
                    {
                        Swal.fire({
                            title: "Gagal",
                            text: response.pesan,
                            timer: 3000,
                            showConfirmButton: false,
                            icon: 'error'
                        });
                    }else{
                        for (control in response.errors) {
                            $('#field-' + control).addClass('is-invalid');
                            $('#error-' + control).html(response.errors[control]);
                        }
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: "Gagal",
                    text: 'Data Gagal Diproses!',
                    timer: 3000,
                    showConfirmButton: false,
                    icon: 'error'
                });
            }
        });
    });

    $("#field-kategori").change(function(){
        getProduk(null, $(this).val() , null, null);
    });

    $(document).on('change', '.jumlah_beli, .hrg_pokok', function() {
        var row = $(this).closest('tr');
        var variasi_id = row.find('.row_variasi_id').val();
        var quantity =  __read_number(row.find('input.jumlah_beli'), true);
        var hrg_pokok = __read_number(row.find('input.hrg_pokok'), true);

        updateCart(variasi_id, quantity, hrg_pokok)
    });

    $(document).on('click', 'button#btn-cari_produk', function() {
        
        $('div#list-produk-body').html('');
        getProduk(null, null, null);
        $('#produkModal').modal();
    });

    $('table#pembelian_table tbody').on('click', 'button.hapus_cart', function() {
        var variasi_id = $(this).parents('tr').find('.row_variasi_id').val();
        Swal.fire({
            title: "Anda Yakin?",
            text: "Produk Akan Dihapus Dari Keranjang Belanja!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak, Batalkan!',
            reverseButtons: true,
            allowOutsideClick: false,
            confirmButtonColor: '#af1310',
            cancelButtonColor: '#fffff',
        })
        .then((result) => {
            if (result.value) {
            $.ajax({
                url: laroute.route('pembelian.deleteCart'),
                type: "POST",
                dataType: "JSON",
                data: {
                    variasi_id: variasi_id,
                },
                success: function(response) {
                    Swal.fire({
                        title: "Berhasil",
                        text: 'Produk Berhasil Dihapus Dari Keranjang Belanja!',
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    $('#pembelian_table tbody').html(response.html);
                    $('#pembelian_table tfoot').find('.total_subtotal').text(__currency_trans_from_en(response.sub_total, true, true));
                    __write_number($('input#total_subtotal_input'), response.sub_total, true);
        
                    $('#grand_total').text(__currency_trans_from_en(response.total, true, true));
                    __write_number($('input#grand_total_hidden'), response.total, true);
        
                    __write_number($('input#row_count'), response.total_item, true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
            } else {
                window.setTimeout(function(){
                    location.reload();
                } ,1500);
            }
        });
    });
});

function get_cart()
{
    $.ajax({
        method: 'GET',
        url: laroute.route('pembelian.getCart'),
        dataType: 'JSON',
        success: function(response) {
            $('#pembelian_table tbody').html(response.html);
            $('#pembelian_table tfoot').find('.total_subtotal').text(__currency_trans_from_en(response.sub_total, true, true));
            __write_number($('input#total_subtotal_input'), response.sub_total, false);

            $('#grand_total').text(__currency_trans_from_en(response.total, true, true));
            __write_number($('input#grand_total_hidden'), response.total, false);

            __write_number($('input#row_count'), response.total_item, false);

            __write_number($('input#field-jml_bayar'), response.total, false);
        },
    });
}

function updateCart(cart_id, qty, hrg)
{
    $.ajax({
        url: laroute.route('pembelian.updateCart'),
        type: "POST",
        dataType: "JSON",
        data: {
            variasi_id : cart_id,
            qty:qty,
            hrg:hrg,
        },
        success: function (response) {
            if (response.fail == false) {
                $('#pembelian_table tbody').html(response.html);
                $('#pembelian_table tfoot').find('.total_subtotal').text(__currency_trans_from_en(response.sub_total, true, true));
                __write_number($('input#total_subtotal_input'), response.sub_total, true);
    
                $('#grand_total').text(__currency_trans_from_en(response.total, true, true));
                __write_number($('input#grand_total_hidden'), response.total, true);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSimpan').text('Simpan');
            $('#btnSimpan').attr('disabled', false);

        }
    });
}