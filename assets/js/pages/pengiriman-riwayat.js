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


$(function () {
    oTable = $('#list-riwayat').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: laroute.route('pengiriman.riwayat'),
            data: function (d) {
                d.tgl_mulai = $('#tgl_mulai').val()
                d.tgl_akhir = $('#tgl_akhir').val()
            }
        },
        columns: [
            {
                data: 'transaksi',
                name: 'transaksi'
            },
            {
                data: 'pelanggan',
                name: 'pelanggan'
            },
            {
                data: 'produk',
                name: 'produk'
            },
            {
                data: 'kendaraan',
                name: 'kendaraan'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'tgl',
                name: 'tgl'
            },
        ]
    });

    $('#search_box').keyup(function () {
        oTable.search($(this).val()).draw();
    });

    $("#tgl_range").change(function(){
        oTable.draw();
    });
});
