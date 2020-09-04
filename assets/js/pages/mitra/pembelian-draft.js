$(document).ready(function () {
    moment.locale('id');
    var start =  moment().startOf('month');
    var end = moment();

    $("#field-supplier").select2({
        placeholder: 'Pilih Supplier',
        allowClear: true,
        ajax: {
            url: laroute.route('supplier.json'),
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

    $('#tgl_range').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Hari Ini': [moment(), moment()],
            'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 Hari Yang Lalu': [moment().subtract(6, 'days'), moment()],
            '30 Hari Yang Lalu': [moment().subtract(29, 'days'), moment()],
            'Bulan Sekarang': [moment().startOf('month'), moment().endOf('month')],
            'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale : {
            "customRangeLabel": "Custom Tanggal",
        }
    }, laksanakan);

    laksanakan(start, end);

    $(document).on('change', '#tgl_range', function () {
        var tgl_mulai = $('#tgl_mulai').val();
        var tgl_akhir = $('#tgl_akhir').val();
        fetch_data(1,tgl_mulai, tgl_akhir);
    });


    $(document).on('click', 'table#list-riwayat tbody.data_transaksi tr', function () {
        var transaksi_id = $(this).closest("tr").data('transaksi_id');
        // alert(transaksi_id);
        document.location = laroute.route('pembelian.detail', {transaksi_id:transaksi_id});
    });

    $("#field-supplier").change(function(){
        fetch_data(1, null, null, $(this).val());
    });

    // Navigasi Next Page Modal Produk
    $(document).on('click', 'button#nextProduk', function() {     
        current_page = parseInt($('#current_page').val());
        current_page += 1;
        fetch_data(current_page,null, null, null);
    });

    // Navigasi Prev Page Modal Produk
    $(document).on('click', 'button#prevProduk', function() {
        current_page = parseInt($('#current_page').val());
        current_page -= 1;
        fetch_data(current_page,null, null, null);
    });

    // Pencarian Produk
    $(document).on('input', 'input#field-keyword', function() {
        keyword = $(this).val();
        fetch_data(1, null, null, null);
    });

});


function laksanakan(start, end) {
    if(start.format('D MMMM, YYYY') == end.format('D MMMM, YYYY'))
    {
        tampil = start.format('D MMMM, YYYY');
    }else{
        tampil = start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY');
    }
    $('input#tgl_mulai').val(start.format('YYYY-MM-DD'));
    $('input#tgl_akhir').val(end.format('YYYY-MM-DD'));
    $('#tgl_range').val(tampil);
}


function fetch_data(page = 1, tgl_mulai = null, tgl_akhir = null, supplier = null) {
    if(!tgl_mulai && !tgl_mulai)
    {
        tgl_mulai = $('#tgl_mulai').val();
        tgl_akhir = $('#tgl_akhir').val();
    }
    if(!supplier)
    {
        supplier = $('#field-supplier').val();
    }
    $.ajax({
        url: laroute.route('pembelian.draft'),
        data: {
            page: page,
            tgl_mulai : tgl_mulai,
            tgl_akhir : tgl_akhir,
            supplier : supplier,
        },
        success: function (response) {
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
            $('#current_page').val(response.current_page);

            if(response.tipe == 'page')
            {
                if(response.html == '')
                {
                    return;
                }else{
                    $('table#list-riwayat tbody').remove();
                    $('table#list-riwayat thead').after(response.html);
                }
            }else{
                $('table#list-riwayat tbody').remove();
                $('table#list-riwayat thead').after(response.html);
            }
            
            $('.tot_transaksi').html(response.total_transaksi);
            $('.jml_pembelian').html(response.penjualan_kotor);
        }
    })
}
