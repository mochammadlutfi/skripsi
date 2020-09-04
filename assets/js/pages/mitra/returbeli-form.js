jQuery(document).ready(function () {
 
    // get_cart();
    $("#field-supplier").select2({
        placeholder: 'Cari Supplier',
        language: 'id',
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

    $('#form-returbeli').submit(function(e) { 
        e.preventDefault();
        var formData = new FormData($('#form-returbeli')[0]);
        $.ajax({
            url: laroute.route('returbeli.simpan'),
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
                        title: "Berhasil",
                        text: 'Retur Produk berhasil Disimpan!',
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    window.setTimeout(function () {
                        location.href = laroute.route('returbeli')
                    }, 1000);
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

    $(document).on('change', '.jumlah_beli', function() {
        var row = $(this).closest('tr');
        var variasi_id = row.find('.row_pembelian_id').val();
        var quantity =  __read_number(row.find('input.jumlah_beli'), true);
        var hrg_pokok = __read_number(row.find('input.hrg_pokok'), true);
        
        var table_total = 0;

        var sub_tot = quantity*hrg_pokok;
        // alert(hrg_pokok)
        row.find('.row_subtotal').text(__currency_trans_from_en(sub_tot, true, true));
        __write_number(row.find('input.row_subtotal_hidden'), sub_tot, false);

        $('#retur_table tbody tr').each(function() {
            var this_total = parseFloat(__read_number($(this).find('input.row_subtotal_hidden')));
            if (this_total) {
                table_total += this_total;
            }
        });
        $('#retur_table tfoot').find('.total_subtotal').text(__currency_trans_from_en(table_total, true, true));
        __write_number($('input#total_subtotal_input'), table_total, false);
    });

    $(document).on('click', 'button#btn-cari_produk', function() {
        
        $('div#list-produk-body').html('');
        getProduk(null, null, null);
        $('#produkModal').modal();
    });

    $('table#retur_table tbody').on('click', 'button.hapus_cart', function() {
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
                url: laroute.route('returbeli.deleteCart'),
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
                    $('#retur_table tbody').html(response.html);
                    $('#retur_table tfoot').find('.total_subtotal').text(__currency_trans_from_en(response.sub_total, true, true));
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
