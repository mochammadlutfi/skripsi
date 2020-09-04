@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
<style>
    #searchInput{
        width: 350px;
        top:15px !important;
    }
</style>
@endsection


@section('content')
<div class="content">
    <form id="form-pelanggan" onsubmit="return false;">
        <div class="content-heading pt-0 mb-3">
            Tambah Pelanggan
            <button type="submit" class="btn btn-secondary float-right mr-5">
                <i class="si si-paper-plane mr-1"></i>
                Simpan Pelanggan
            </button>
        </div>
        <div class="block">
            <div class="block-content pb-15">
                <div class="row px-0">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="field-nama">Nama Perusahaan</label>
                            <input type="text" class="form-control" name="nama" id="field-nama" placeholder="Masukan Nama Pelanggan">
                            <div id="error-nama" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-telp">No. Telepon</label>
                            <input type="text" class="form-control" id="field-telp" name="telp" placeholder="Masukan No Telepon">
                            <div id="error-telp" class="invalid-feedback"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="field-wilayah">Wilayah</label>
                                    <select name="wilayah" id="field-wilayah" style="width:100%"></select>
                                    <div id="error-wilayah" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="field-kd_pos">Kode Pos</label>
                                    <input type="text" class="form-control" id="field-kd_pos" name="kd_pos" placeholder="Kode Pos">
                                    <div id="error-kd_pos" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-alamat">Alamat</label>
                            <textarea class="form-control" id="field-alamat" name="alamat" placeholder="Masukan Alamat Lengkap" rows="2"></textarea>
                            <div id="error-alamat" class="invalid-feedback"></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="field-lat">Latitude</label>
                                    <input type="text" class="form-control" name="lat" id="field-lat">
                                    <div id="error-lat" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="field-lng">Longitude</label>
                                    <input type="text" class="form-control" name="lng" id="field-lng">
                                    <div id="error-lng" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="field-perwakilan_nama">Nama Perwakilan</label>
                            <input type="text" class="form-control" name="perwakilan_nama" id="field-perwakilan_nama" placeholder="Masukan Nama Perwakilan">
                            <div id="error-perwakilan_nama" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-perwakilan_kontak">No. Kontak Perwakilan</label>
                            <input type="text" class="form-control" name="perwakilan_kontak" id="field-perwakilan_kontak" placeholder="Masukan No. Kontak Perwakilan">
                            <div id="error-perwakilan_kontak" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-email">Alamat Email</label>
                            <input type="text" class="form-control" name="email" id="field-email" placeholder="Masukan Alamat Email">
                            <div id="error-email" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="field-keterangan">Keterangan</label>
                            <textarea class="form-control" id="field-keterangan" name="keterangan" placeholder="Masukan Keterangan Tambahan" rows="3"></textarea>
                            <div id="error-keterangan" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="block">
        <div class="block-content py-15">
            <div class="row">
                <div class="col-12">
                    <input id="searchInput" class="form-control" type="text" placeholder="Cari Lokasi">
                    <div class="map" id="map" style="width: 100%; height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('scripts')
<script src="{{ asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/select2/js/i18n/id.js') }}"></script>
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=&libraries=places" async defer></script> --}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCeQBvgWWU9QI4ca0E8vB3XEPr11rOGv7k&sensor=false&libraries=places"></script>
<script src="{{ asset('assets/js/pages/pelanggan-form.js') }}"></script>
<script>

 google.maps.event.addDomListener(window, 'load', initialize);

function initialize() {
     var latlng = new google.maps.LatLng(-6.867470, 107.560349);
     var map = new google.maps.Map(document.getElementById('map'), {
         center: latlng,
         zoom: 13
     });
     var marker = new google.maps.Marker({
         map: map,
         position: latlng,
         draggable: true,
         anchorPoint: new google.maps.Point(0, -29)
     });
     var input = document.getElementById('searchInput');
     map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
     var geocoder = new google.maps.Geocoder();
     var autocomplete = new google.maps.places.Autocomplete(input);
     autocomplete.bindTo('bounds', map);
     var infowindow = new google.maps.InfoWindow();
     autocomplete.addListener('place_changed', function () {
         infowindow.close();
         marker.setVisible(false);
         var place = autocomplete.getPlace();
         if (!place.geometry) {
             //  window.alert("Autocomplete's returned place contains no geometry");
             return;
         }

         // If the place has a geometry, then present it on a map.
         if (place.geometry.viewport) {
             map.fitBounds(place.geometry.viewport);
         } else {
             map.setCenter(place.geometry.location);
             map.setZoom(17);
         }

         marker.setPosition(place.geometry.location);
         marker.setVisible(true);

         bindDataToForm(place.formatted_address, place.geometry.location.lat(), place.geometry.location.lng());
         infowindow.setContent(place.formatted_address);
         infowindow.open(map, marker);

     });
     // this function will work on marker move event into map
     google.maps.event.addListener(marker, 'dragend', function () {
         geocoder.geocode({
             'latLng': marker.getPosition()
         }, function (results, status) {
             if (status == google.maps.GeocoderStatus.OK) {
                 if (results[0]) {
                     bindDataToForm(results[0].formatted_address, marker.getPosition().lat(), marker.getPosition().lng());
                     infowindow.setContent(results[0].formatted_address);
                     infowindow.open(map, marker);
                 }
             }
         });
     });
}

function bindDataToForm(address, lat, lng) {
     // document.getElementById('location').value = address;
     document.getElementById('field-lat').value = lat;
     document.getElementById('field-lng').value = lng;
}
</script>
@endpush
