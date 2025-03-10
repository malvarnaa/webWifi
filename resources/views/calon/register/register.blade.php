@extends('layout.nosidebar')
@section('content')

<style>
    .alert {
        display: flex;
        text-align: left;
        align-items: center;
        left: 0px; 
    }
    .alert .bi-exclamation-triangle-fill{
        font-size: 20px;
        padding: 0 10px;
    }

    .paket-label {
        cursor: pointer;
        position: relative;
    }

    .paket-label input[type="radio"] {
        display: none; /* Sembunyikan radio button */
    }

    .paket-label .paket-card {
        transition: 0.3s;
        border: 2px solid transparent;
        cursor: pointer;
    }

    .paket-label input[type="radio"]:checked + .paket-card {
        border: 2px solid #007bff; /* Highlight border biru saat dipilih */
        background-color: #f8f9fa; /* Warna latar lebih terang */
    }



</style>
<div class="card mb-3">
    <div class="card-body">
        <div class="judul">
            <h1>Hai Cust,</h1>
            <h4>Lengkapi seluruh informasi dibawah untuk mendapatkan layanan dari kami!</h4>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="header-data-diri">
            <h4><b>Data Diri</b></h4>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle-fill"></i> 
                Semua data ini wajib diisi untuk kebutuhan proses pemesanan kamu.
            </div>    
        </div>
        <div class="form-data-diri">
            <form action="{{ route('register.store')}}" method="POST">
            @csrf
                <div class="mb-3">
                    <label for="">Nama Lengkap*</label>
                    <input type="text" class="form-control" name="nama_cust" placeholder="Masukkan Nama Lengkap kamu">
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="">Nomor Handphone*</label>
                        <input type="number" class="form-control" name="nomor_hp" placeholder="Masukkan nomor aktif WhatsApp kamu">
                        <small class="text-muted">Pastikan nomor yang dimasukkan aktif WhatsApp</small>
                    </div>
                
                    <div class="col-md-6">
                        <label for="">Email*</label>
                        <input type="email" class="form-control" name="email" placeholder="Masukkan Email kamu">
                    </div>
                </div>
                <div class="label-paket">
                    <label for="">Pilih Paket Wifi*</label>
                    <div class="d-flex flex-wrap gap-4">
                        @foreach ($paket as $item)
                            <label class="paket-label">
                                <input type="radio" name="paket_id" value="{{ $item->id }}" class="paket-radio" data-harga="{{ $item->harga }}">
                                <div class="card paket-card">
                                    <div class="card-body">
                                        <div class="icon mb-3"><i class="bi bi-wifi fs-1 text-primary"></i></div>
                                        <h5 class="card-title">{{ $item->nama_paket }}</h5>
                                        <p class="card-text">
                                            <strong>Kecepatan:</strong> {{ $item->kecepatan }}<br>
                                            {{ $item->deskripsi }}
                                        </p>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>       
                </div>   
                <div class="form-group">
                    <label for="prov">Provinsi</label>
                    <select id="prov" name="prov_id" class="form-control">
                        <option value="">Pilih Provinsi</option>
                        @foreach($prov as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_prov }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="kab">Kabupaten</label>
                    <select id="kab" name="kab_id" class="form-control">
                        <option value="">Pilih Kabupaten</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="kec">Kecamatan</label>
                    <select id="kec" name="kec_id" class="form-control">
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat_lengkap" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>Lokasi (GPS)</label>
                    <div class="input-group">
                        <input type="text" id="latitude" name="latitude" class="form-control" placeholder="Latitude" readonly>
                        <input type="text" id="longitude" name="longitude" class="form-control" placeholder="Longitude" readonly>
                        <button type="button" class="btn btn-primary" onclick="getLocation()">Ambil Lokasi</button>
                    </div>
                </div>                
                <div class="form-group">
                    <label for="kebutuhan">Kebutuhan</label>
                    <select id="kebutuhan" name="kebutuhan" class="form-control">
                        <option value="perumahan">Perumahan</option>
                        <option value="apartemen">Apartemen</option>
                        <option value="bisnis">Bisnis</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="">Rencana Tanggal Pemasangan*</label>
                    <input type="date" class="form-control" name="tanggal_pemasangan" placeholder="Masukkan Tanggal rencana pemasangan">
                </div>
                <div class="form-group">
                    <label for="total_harga">Total Harga (Bulan Pertama)</label>
                    <input type="number" id="total_harga" name="total_harga" class="form-control" readonly>
                    <p id="total_harga_display" style="margin-top: 5px; font-weight: bold;"></p>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>                                 
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.paket-label input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.paket-card').forEach(card => {
                card.classList.remove('selected');
            });
            this.nextElementSibling.classList.add('selected'); 
        });
    });

    document.getElementById('prov').addEventListener('change', function () {
        let provId = this.value;
        let kabSelect = document.getElementById('kab');
        let kecSelect = document.getElementById('kec');

        kabSelect.innerHTML = '<option value="">Pilih Kabupaten</option>';
        kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

        if (provId) {
            fetch(`/get-kabupaten/${provId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(kab => {
                        let option = document.createElement('option');
                        option.value = kab.id;
                        option.textContent = kab.nama_kab;
                        kabSelect.appendChild(option);
                    });
                });
        }
    });

    document.getElementById('kab').addEventListener('change', function () {
        let kabId = this.value;
        let kecSelect = document.getElementById('kec');

        kecSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';

        if (kabId) {
            fetch(`/get-kecamatan/${kabId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(kec => {
                        let option = document.createElement('option');
                        option.value = kec.id;
                        option.textContent = kec.nama_kec;
                        kecSelect.appendChild(option);
                    });
                });
        }
    });

    document.querySelectorAll('.paket-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            let harga = this.getAttribute('data-harga'); 

            if (harga) {
                document.getElementById('total_harga').value = harga; // Simpan angka asli
                document.getElementById('total_harga_display').textContent = formatRupiah(harga); // Tampilkan format rupiah
            } else {
                document.getElementById('total_harga').value = '';
                document.getElementById('total_harga_display').textContent = '';
            }
        });
    });

    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("Geolocation tidak didukung di browser ini.");
        }
    }

    function showPosition(position) {
        document.getElementById("latitude").value = position.coords.latitude;
        document.getElementById("longitude").value = position.coords.longitude;
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                alert("Izinkan akses lokasi untuk mengambil GPS!");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Informasi lokasi tidak tersedia.");
                break;
            case error.TIMEOUT:
                alert("Permintaan lokasi melebihi batas waktu.");
                break;
            case error.UNKNOWN_ERROR:
                alert("Terjadi kesalahan yang tidak diketahui.");
                break;
        }
    }
</script>

@endsection