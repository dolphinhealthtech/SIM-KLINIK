@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard v1</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>150</h3>

                                <p>New Orders</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>53<sup style="font-size: 20px">%</sup></h3>

                                <p>Bounce Rate</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>44</h3>

                                <p>User Registrations</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>65</h3>

                                <p>Unique Visitors</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="userstabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No RM</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Tanggal Lahir</th>
                                            <th class="text-center">Nomor Kartu BPJS</th>
                                            <th class="text-center">Nomor Telepon</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pasiens as $pasiensdata)
                                            <tr
                                                class="{{ $pasiensdata->verifikasi == 1 ? 'table-danger' : ($pasiensdata->verifikasi == 2 ? 'table-success' : '') }}">
                                                <td>{{ $pasiensdata->no_rm }}</td>
                                                <td>{{ $pasiensdata->nama }}</td>
                                                <td>{{ $pasiensdata->tanggal_lahir }}</td>
                                                <td>{{ $pasiensdata->no_bpjs }}</td>
                                                <td>{{ $pasiensdata->telepon }}</td>
                                                <td>
                                                    <a class="btn btn-info rounded-pill" data-toggle="modal"
                                                        data-target="#lengkapiModal{{ $pasiensdata->id }}">
                                                        <i class="fa fa-exclamation-circle"></i> Lengkapi
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>


    <!-- Modal XL -->
    <div class="modal fade" id="lengkapiModal{{ $pasiensdata->id }}" tabindex="-1"
        aria-labelledby="modalTitle{{ $pasiensdata->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle{{ $pasiensdata->id }}">Lengkapi Data Pasien Atas Nama
                        {{ $pasiensdata->nama }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-md-3 d-flex justify-content-center">
                                <div class="position-relative text-center">
                                    <!-- Input Gambar -->
                                    <input type="file" id="profileImageInput" name="profile_image" accept="image/*" class="d-none" onchange="previewImage(event)">

                                    <!-- Bingkai Gambar dengan Rasio 3:4 -->
                                    <label for="profileImageInput" class="d-block" style="cursor: pointer;">
                                        <div style="width: 100%;border: 2px solid #ccc; max-width: 180px; aspect-ratio: 3 / 4; overflow: hidden; border-radius: 10px;  background: #f0f0f0; display: flex; align-items: center; margin-top: 75px;  justify-content: center;">
                                            <img id="profileImage" class="img-fluid rounded" src="{{ asset('setting/' . ($setting->profile_image ?? 'default.png')) }}"
                                                alt="User profile picture" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h3 style="text-align: left;">BIODATA</h3>
                                <div class="col-2" style="padding-left: 0; text-align: left;">
                                    <hr style="width: 85%; margin-left: 0;">
                                </div>
                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nama </label>
                                            <input type="text" class="form-control" placeholder="nama"
                                                id="nama" name="nama" value="{{ $pasiensdata->nama }}" readonly>
                                        </div>
                                        @error('nama')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Nomor NIK</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-center" id="nik" name="nik"
                                                    value="{{ $pasiensdata->nik }}" readonly onclick="handleClick()"
                                                    style="cursor: pointer; background-color: #f8f9fa; border: 1px solid #ced4da;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>Tempat & Tanggal Lahir</label>
                                            <div class="input-group">
                                                <!-- Input Tempat Lahir -->
                                                <input type="text" class="form-control" id="tempat_lahir"
                                                    name="tempat_lahir" value="{{ old('tempat_lahir', $pasiensdata->tempat_lahir ?? '') }}"
                                                    placeholder="Tempat" style="width: 50%;">

                                                <!-- Input Tanggal Lahir -->
                                                <input type="date" class="form-control" id="tgllahir"
                                                    name="tgllahir" value="{{ old('tgllahir', $pasiensdata->tanggal_lahir) }}"
                                                    style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Provinsi</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="provinsi"
                                                name="provinsi">
                                                <option value="" disabled selected>Provinsi</option>
                                                @foreach ($provinsi as $provinsidata)
                                                    <option value="{{ $provinsidata->kode }}">{{ $provinsidata->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Kota/Kabupaten</label>
                                            <select class="form-control select2bs4" style="width: 100%;"
                                                id="kabupaten" name="kabupaten">
                                                <option value="" disabled selected>Kota/Kabupaten</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Kecamatan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="kecamatan"
                                                name="kecamatan" value="{{ old('kecamatan') }}">
                                                <option value="" disabled selected>Kecamatan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Desa/Kelurahan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="desa"
                                                name="desa" value="{{ old('desa') }}">
                                                <option value="" disabled selected>Desa/Kelurahan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label>RT </label>
                                            <input type="text" class="form-control" placeholder="001" id="rt"
                                                name="rt" value="{{ old('rt') }}">
                                        </div>
                                        @error('rt')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label>RW </label>
                                            <input type="text" class="form-control" placeholder="002" id="rw"
                                                name="rw" value="{{ old('rw') }}">
                                        </div>
                                        @error('rw')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Kode Pos </label>
                                            <input type="text" class="form-control" id="kode_pos" name="kode_pos"
                                                value="{{ old('kode_pos') }}">
                                        </div>
                                        @error('kode_pos')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea class="form-control" placeholder="Masukkan alamat" id="alamat" name="alamat" rows="1">{{ $pasiensdata->alamat }}</textarea>
                                        </div>
                                        @error('alamat')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group text-center">
                                            <label>Nomor BPJS & Satusehat</label>
                                            <div class="input-group">
                                                <!-- Input Tempat Lahir -->
                                                <input type="text" class="form-control text-center" id="noka"
                                                    name="noka" value="{{ old('noka', $pasiensdata->no_bpjs ?? '') }}"
                                                    placeholder="Noka BPJS" style="width: 50%;">

                                                <!-- Input Tanggal Lahir -->
                                                <input type="text" class="form-control text-center" id="noihs"
                                                name="noihs" value="{{ old('noihs', $pasiensdata->kode_ihs ?? '') }}"
                                                placeholder="Noka SatuSehat" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group text-center">
                                            <label>Jenis & Kelas BPJS</label>
                                            <div class="input-group">

                                                <input type="text" class="form-control text-center" id="jenis_kartu"
                                                    name="jenis_kartu" value="{{ old('jenis_kartu', $pasiensdata->jenis_Kartu_bpjs ?? '') }}"
                                                    placeholder="Jenis Peserta BPJS" style="width: 50%;">

                                                <input type="text" class="form-control text-center" id="kelas"
                                                name="kelas" value="{{ old('kelas', $pasiensdata->kelas_bpjs ?? '') }}"
                                                placeholder="Kelas BPJS" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group text-center">
                                            <label>Provide & Masa Berlaku Kartu BPJS</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-center" id="provide"
                                                name="provide" value="{{ old('provide', $pasiensdata->provide ?? '') }}"
                                                placeholder="Provide BPJS" style="width: 50%;">

                                                <input type="date" class="form-control text-center" id="tgl_exp_bpjs"
                                                    name="tgl_exp_bpjs" value="{{ old('tgl_exp_bpjs', $pasiensdata->tgl_exp_bpjs ?? '') }}"
                                                    placeholder="Masa Berlaku Kartu BPJS" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Jenis Kelamin</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="seks"
                                                name="seks">
                                                <option value="" disabled selected>Jenis Kelamin</option>
                                                @foreach ($kelamin as $kelamindata)
                                                    <option value="{{ $kelamindata->kode }}">{{ $kelamindata->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Golongan Darah</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="goldar"
                                                name="goldar">
                                                <option value="" disabled selected>--- pilih ---</option>
                                                @foreach ($goldar as $goldardata)
                                                    <option value="{{ $goldardata->id }}">{{ $goldardata->nama . $goldardata->resus}}</option>
                                                @endforeach
                                            </select>
                                            @error('goldar')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Status Pernikahan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="pernikahan"
                                                name="pernikahan">
                                                <option value="" disabled selected>--- pilih ---</option>
                                                @foreach ($pernikahan as $pernikahandata)
                                                    <option value="{{ $pernikahandata->id }}">{{ $pernikahandata->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('pernikahan')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Kewarganegaraan</label>
                                            <select class="form-control select2bs4" style="width: 100%;"
                                                id="kewarganegaraan" name="kewarganegaraan">
                                                <option value=""
                                                    {{ old('kewarganegaraan') == '' ? 'selected' : '' }}>--- pilih ---
                                                </option>
                                                <option value="wni"
                                                    {{ old('kewarganegaraan') == 'wni' ? 'selected' : '' }}>Warga Negara
                                                    Indonesia</option>
                                                <option value="wna"
                                                    {{ old('kewarganegaraan') == 'wna' ? 'selected' : '' }}>Warga Negara
                                                    Asing</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Agama</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="agama"
                                                name="agama">
                                                <option value="" disabled selected>--- pilih ---</option>
                                                @foreach ($agama as $agamadata)
                                                    <option value="{{ $agamadata->id }}">{{ $agamadata->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('agama')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Pendidikan</label>
                                            <select class="form-control select2bs4" style="width: 100%;"
                                                id="pendidikan" name="pendidikan">
                                                <option value="" disabled selected>--- pilih ---</option>

                                            </select>
                                            @error('pendidikan')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Status Pekerja</label>
                                            <select class="form-control select2bs4" style="width: 100%;"
                                                id="status_kerja" name="status_kerja">
                                                <option value="" disabled selected>--- Pilih Status ---
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Telepon</label>
                                            <input type="text" class="form-control" id="telepon" name="telepon">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Suku</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="suku"
                                                name="suku">
                                                <option value="" disabled selected>Pilih Suku</option>
                                                @foreach ($suku as $sukudata)
                                                    <option value="{{ $sukudata->id }}">{{ $sukudata->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('suku')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bangsa</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="bangsa"
                                                name="bangsa">
                                                <option value="" disabled selected>Pilih Bangsa</option>
                                                @foreach ($bangsa as $bangsadata)
                                                    <option value="{{ $bangsadata->id }}">{{ $bangsadata->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('bangsa')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bahasa</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="bahasa"
                                                name="bahasa">
                                                <option value="" disabled selected> Pilih Bahasa </option>
                                                @foreach ($bahasa as $bahasadata)
                                                    <option value="{{ $bahasadata->id }}">{{ $bahasadata->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('bahasa')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="Email" class="form-control" id="email" name="email">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                autocomplete>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="userinput" name="userinput" value="{{ auth()->user()->name }}">
                        <input type="hidden" id="userinputid" name="userinputid" value="{{ auth()->user()->id }}">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="sumbit" class="btn btn-primary">Simpan</button>
                </div>
                    </form>
            </div>
        </div>
    </div>


    <script>
        function formatDate(dateString) {
            let parts = dateString.split("-"); // Pisahkan berdasarkan "-"
            return `${parts[2]}-${parts[1]}-${parts[0]}`; // Susun ulang menjadi "yyyy-MM-dd"
        }

        function updateInputValue(inputElement, newValue) {
            if (inputElement.value.trim() !== newValue) {
                inputElement.value = newValue;
            }
        }

        function handleClick() {
            let nik = document.getElementById("nik").value; // Ambil nilai NIK dari input
            let apiUrl = `{{ route('pcare.nik', ':nik') }}`.replace(':nik', nik); // Perbaiki parameter

            // Fetch data dari API
            fetch(apiUrl, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                }
            })
            .then(response => response.json()) // Ubah respons ke JSON
            .then(responseData => {
                if (responseData.status === "success" && responseData.data) {
                    let data = responseData.data;

                    // Ambil elemen input
                    let nokaInput = document.getElementById("noka");
                    let jenisKartuInput = document.getElementById("jenis_kartu");
                    let kelasInput = document.getElementById("kelas");
                    let provideInput = document.getElementById("provide");
                    let expbpjsInput = document.getElementById("tgl_exp_bpjs");
                    let tgllahirInput = document.getElementById("tgllahir");
                    let namaInput = document.getElementById("nama");

                    // Update nilai input hanya jika berbeda
                    updateInputValue(nokaInput, data.noKartu);
                    updateInputValue(jenisKartuInput, data.jnsPeserta.nama);
                    updateInputValue(kelasInput, data.jnsKelas.nama);
                    updateInputValue(provideInput, data.kdProviderPst.nmProvider);
                    if (data.tglAkhirBerlaku) {
                        updateInputValue(expbpjsInput, formatDate(data.tglAkhirBerlaku));
                    }
                    if (data.tglLahir) {
                        updateInputValue(tgllahirInput, formatDate(data.tglLahir));
                    }
                    updateInputValue(namaInput, data.nama);

                        // **Panggil route tambahan setelah namaInput diperbarui**
                        let noihsApiUrl = `{{ route('satusehat.nik', ':nik') }}`.replace(':nik', nik); // Sesuaikan URL
                        fetch(noihsApiUrl, {
                            method: "GET",
                            headers: {
                                "Content-Type": "application/json",
                            }
                        })
                        .then(response => response.json())
                        .then(noihsData => {
                            let noihsInput = document.getElementById("noihs"); // Ambil elemen input noihs
                            if (noihsData.status === "success" && noihsData.data) {
                                updateInputValue(noihsInput, noihsData.data); // Update No IHS
                            }
                        })
                        .catch(error => {
                            console.error("Gagal mengambil No IHS:", error);
                        });

                } else {
                    console.error("Data tidak ditemukan atau format tidak sesuai:", responseData);
                    alert("Data tidak ditemukan.");
                }
            })
            .catch(error => {
                console.error("Terjadi kesalahan:", error);
                alert("Gagal mengambil data dari API.");
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            $('#provinsi').change(function () {
                let provinsiID = $(this).val();
                $('#kabupaten').html('<option value="">Memuat...</option>');
                $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
                $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');

                if (provinsiID) {
                    $.get("{{ route('get.kabupaten') }}", { provinsi_id: provinsiID }, function (data) {
                        let options = '<option value="">Pilih Kabupaten</option>';
                        $.each(data, function (index, kab) {
                            options += `<option value="${kab.kode}">${kab.name}</option>`;
                        });
                        $('#kabupaten').html(options);
                    });
                }
            });

            $('#kabupaten').change(function () {
                let kabupatenID = $(this).val();
                $('#kecamatan').html('<option value="">Memuat...</option>');
                $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');

                if (kabupatenID) {
                    $.get("{{ route('get.kecamatan') }}", { kabupaten_id: kabupatenID }, function (data) {
                        let options = '<option value="">Pilih Kecamatan</option>';
                        $.each(data, function (index, kec) {
                            options += `<option value="${kec.kode}">${kec.name}</option>`;
                        });
                        $('#kecamatan').html(options);
                    });
                }
            });

            $('#kecamatan').change(function () {
                let kecamatanID = $(this).val();
                $('#kelurahan').html('<option value="">Memuat...</option>');

                if (kecamatanID) {
                    $.get("{{ route('get.kelurahan') }}", { kecamatan_id: kecamatanID }, function (data) {
                        let options = '<option value="">Pilih Kelurahan</option>';
                        $.each(data, function (index, kel) {
                            options += `<option value="${kel.kode}">${kel.name}</option>`;
                        });
                        $('#desa').html(options);
                    });
                }
            });
        });
    </script>

    <script>
        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function() {
                document.getElementById('profileImage').src = reader.result;
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $("#userstabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#userstabel_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
