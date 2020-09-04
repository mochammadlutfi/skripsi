$(document).ready(function () {
    moment.locale('id');
    var start =  moment().startOf('month');
    var end = moment();

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

    $("#sales").select2({
        placeholder: 'Cari Sales',
        ajax: {
            url: laroute.route('sales.json'),
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

    // Navigasi Next Page Modal Produk
    $(document).on('change', '#status', function() {     
        status = parseInt($('#status').val());
        fetch_data(1, null, null, null, status);
    });

    $(document).on('click', 'table#list-riwayat tbody.data_transaksi tr', function () {
        var transaksi_id = $('table#list-riwayat tbody tr').data('transaksi_id');
        // alert(transaksi_id)
        document.location = laroute.route('penjualan.detail', {transaksi_id:transaksi_id});
    });

    // Navigasi Next Page Modal Produk
    $(document).on('click', 'button#nextProduk', function() {     
        current_page = parseInt($('#current_page').val());
        current_page += 1;
        fetch_data(current_page, null, null, null);
        $('#current_page').val(current_page);
    });// Navigasi Next Page Modal Produk
    $(document).on('click', 'button#nextProduk', function() {     
        current_page = parseInt($('#current_page').val());
        current_page += 1;
        fetch_data(current_page, null, null, null);
        $('#current_page').val(current_page);
    });

    // Navigasi Prev Page Modal Produk
    $(document).on('click', 'button#prevProduk', function() {
        current_page = parseInt($('#current_page').val());
        current_page -= 1;
        fetch_data(current_page, null, null, null);
        $('#current_page').val(current_page);
    });

    $('#search_box').keyup(function () {
        oTable.search($(this).val()).draw();
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

function clear_icon() {
    $('#id_icon').html('');
    $('#post_title_icon').html('');
}

function fetch_data(page = 1, tgl_mulai = null, tgl_akhir = null) {
    if(!tgl_mulai && !tgl_mulai)
    {
        tgl_mulai = $('#tgl_mulai').val();
        tgl_akhir = $('#tgl_akhir').val();
    }
    $.ajax({
        url: laroute.route('mitra.pembelian.riwayat'),
        data: {
            page: page,
            tgl_mulai : tgl_mulai,
            tgl_akhir : tgl_akhir,
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
            $('#produk_current_page').val(response.current_page);

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
                $('div#list-produk-body').html('');
                $('table#list-riwayat tbody').remove();
                $('table#list-riwayat thead').after(response.html);
            }
            
            $('.tot_transaksi').html(response.total_transaksi);
            $('.jml_pembelian').html(response.penjualan_kotor);
        }
    })
}
