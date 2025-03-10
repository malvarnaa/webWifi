@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Detail Pesanan</h5>
                        </div>
                    </div>                        
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <td>Nama Pemesan</td>
                                <td>: {{ $register->nama_cust }}</td>
                            </tr> 
                            <tr>
                                <td>Nomor HP / Telepon</td>
                                <td>: {{ $register->nomor_hp }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>: {{ $register->email }}</td>
                            </tr>
                            <tr>
                                <td>Alamat Lengkap</td>
                                <td>: {{ $register->alamat_lengkap }}, 
                                    {{ $register->kec->nama_kec ?? '-' }}, 
                                    {{ $register->kab->nama_kab ?? '-' }}, 
                                    {{ $register->prov->nama_prov ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Paket</td>
                                <td>: {{ $register->paket->nama_paket ?? '-' }} {{ $register->paket->kecepatan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Kebutuhan</td>
                                <td>: {{ ucfirst($register->kebutuhan) }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Rencana Pemasangan</td>
                                <td>: {{ $register->tanggal_pemasangan ? date('d M Y', strtotime($register->tanggal_pemasangan)) : '-' }}</td>
                            </tr>
                            <tr>
                                <td>Total Harga</td>
                                <td>: Rp {{ number_format($register->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Pembayaran</td>
                                <td>:</td>
                            </tr>                            
                        </table>
                    </div>
                    <input type="hidden" id="latitude" value="{{ $register->latitude }}">
                    <input type="hidden" id="longitude" value="{{ $register->longitude }}">

                    <div id="map" style="height: 400px; width: 100%;"></div>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('review.pesanan') }}" class="btn btn-secondary">Kembali</a>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function initMap() {
        var latitude = parseFloat(document.getElementById("latitude").value) || -6.2088;
        var longitude = parseFloat(document.getElementById("longitude").value) || 106.8456;

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: { lat: latitude, lng: longitude }
        });

        new google.maps.Marker({
            position: { lat: latitude, lng: longitude },
            map: map
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBc6EeNk7cMFcZoBmaHGzCh4cJk-Blukxk&callback=initMap" async defer></script>
@endsection
