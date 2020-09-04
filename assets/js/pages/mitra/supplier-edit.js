$(document).ready(function () {

    var wilayahSelect = $('#field-wilayah').select2({
        placeholder: 'Cari Kelurahan',
        language: 'id',
        ajax: {
            url: laroute.route('wilayah.json'),
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
        },
        
        minimumInputLength: 3,
        // templateResult: formatResult,
        templateResult: function(response) {
            if(response.loading)
            {
                return "Mencari...";
            }else{
                var selectionText = response.text.split(",");
                var $returnString = $('<span>'+selectionText[0] + '</br>' + selectionText[1] + ', ' + selectionText[2]+ ', ' + selectionText[3] +'</span>');
                return $returnString;
            }
        },
        templateSelection: function(response) {
            return response.text;
        },
    });

    wilayahOption = new Option($('#field-wilayah').attr("data-text"), $('#field-wilayah').attr("data-id"), true, true);
    wilayahSelect.append(wilayahOption).trigger('change');

    $(document).on('change', '#field-wilayah', function() {
        $.ajax({
            method: 'POST',
            url: laroute.route('getPos.json'),
            data: {
                daerah_id : $('#field-wilayah').val(),
            },
            dataType: 'JSON',
            success: function(response) {
                $('#field-kd_pos').val(response)
            },
        });
    });

    $("#form-supplier").submit(function (e) {
        e.preventDefault();
        var formData = new FormData($('#form-supplier')[0]);
        $.ajax({
            url: laroute.route('supplier.update'),
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
                $('.is-invalid').removeClass('is-invalid');
                if (response.fail == false) {
                    $('#modal_embed').modal('hide');
                    Swal.fire({
                        title: "Berhasil",
                        text: "Data Supplier Berhasil Diperbaharui!",
                        timer: 3000,
                        showConfirmButton: false,
                        icon: 'success'
                    });
                    window.setTimeout(function () {
                        // location.reload();
                        location.href = laroute.route('supplier');
                    }, 1500);
                } else {
                    Swal.close();
                    for (control in response.errors) {
                        $('#field-' + control).addClass('is-invalid');
                        $('#error-' + control).html(response.errors[control]);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.close();
                alert('Error adding / update data');
            }
        });
    });
});