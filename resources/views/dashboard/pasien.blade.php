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
                                                    value="{{ $pasiensdata->nik }}" readonly onfocus="this.blur()">
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
                                                    placeholder="Tempat" style="width: 50%;">

                                                <!-- Input Tanggal Lahir -->
                                                <input type="text" class="form-control text-center" id="noihs"
                                                name="noihs" value="{{ old('noihs', $pasiensdata->kode_ihs ?? '') }}"
                                                placeholder="Tempat" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
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
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Status Pernikahan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="pernikahan"
                                                name="pernikahan">
                                                <option value="" disabled selected>--- pilih ---</option>
                                                <option value="menikah"
                                                    {{ old('pernikahan') == 'menikah' ? 'selected' : '' }}>Menikah</option>
                                                <option value="belum_nikah"
                                                    {{ old('pernikahan') == 'belum_nikah' ? 'selected' : '' }}>Belum
                                                    Menikah</option>
                                                <option value="cerai_hidup"
                                                    {{ old('pernikahan') == 'cerai_hidup' ? 'selected' : '' }}>Cerai Hidup
                                                </option>
                                                <option value="cerai_mati"
                                                    {{ old('pernikahan') == 'cerai_mati' ? 'selected' : '' }}>Cerai Mati
                                                </option>
                                            </select>
                                            @error('pernikahan')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Suku</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="suku"
                                                name="suku">
                                                <option value="" disabled selected>--- Pilih Suku ---</option>

                                            </select>
                                            @error('suku')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Bangsa</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="bangsa"
                                                name="bangsa">
                                                <option value="" disabled selected>--- Pilih Bangsa ---</option>

                                            </select>
                                            @error('bangsa')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Bahasa</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="bahasa"
                                                name="bahasa">
                                                <option value="" disabled selected>--- Pilih Bahasa ---</option>

                                            </select>
                                            @error('bahasa')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="Email" class="form-control" id="email" name="email">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                autocomplete>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Telepon</label>
                                            <input type="text" class="form-control" id="telepon" name="telepon">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h3 style="text-align: left;">STATUS</h3>
                                <div class="col-2" style="padding-left: 0; text-align: left;">
                                    <hr style="width: 55%; margin-left: 0;">
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Tanggal Masuk</label>
                                            <input type="date" class="form-control" id="tglawal" name="tglawal">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="aktivasi"
                                                name="aktivasi">
                                                <option value="">--- pilih ---</option>
                                                <option value="aktif">Aktif</option>
                                                <option value="tidak aktif">Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Unit</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="poli"
                                                name="poli">
                                                <option value="" disabled selected>--- Pilih Unit ---</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Jabatan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="jabatan"
                                                name="jabatan">
                                                <option value="" disabled selected>--- Pilih Jabatan ---</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nomor NPWP</label>
                                            <input type="text" class="form-control" id="npwp" name="npwp">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nomor STR</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="str" name="str"
                                                    style="width: 60%;">
                                                <input type="date" class="form-control" id="expstr" name="expstr"
                                                    style="width: 40%;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nomor SIP </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="sip" name="sip"
                                                    placeholder="Nomor SIP" style="width: 60%;">
                                                <input type="date" class="form-control" id="expspri" name="expspri"
                                                    style="width: 40%;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Pelatihan Khusus </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="pk" name="pk"
                                                    placeholder="Nomor PK" style="width: 60%;">
                                                <input type="date" class="form-control" id="exppk" name="exppk"
                                                    style="width: 40%;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>ID Satu Sehat</label>
                                            <input type="text" class="form-control" id="kode" name="kode">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <h3 style="text-align: left;">PENDIDIKAN</h3>
                                        <div class="col-2" style="padding-left: 0; text-align: left;">
                                            <hr style="width: 90%; margin-left: 0;">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Pendidikan</label>
                                                    <select class="form-control select2bs4" style="width: 100%;"
                                                        id="pendidikan" name="pendidikan">
                                                        <option value="" disabled selected>--- pilih ---</option>
                                                        <option value="SD"
                                                            {{ old('pendidikan') == 'SD' ? 'selected' : '' }}>SD</option>
                                                        <option value="SMP"
                                                            {{ old('pendidikan') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                        <option value="SMA"
                                                            {{ old('pendidikan') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                                        <option value="S1"
                                                            {{ old('pendidikan') == 'S1' ? 'selected' : '' }}>Sarjana
                                                        </option>
                                                        <option value="S2"
                                                            {{ old('pendidikan') == 'S2' ? 'selected' : '' }}>Magister
                                                        </option>
                                                        <option value="S3"
                                                            {{ old('pendidikan') == 'S3' ? 'selected' : '' }}>Doctoral
                                                            Degree</option>
                                                    </select>
                                                    @error('pendidikan')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Status Pekerja</label>
                                                    <select class="form-control select2bs4" style="width: 100%;"
                                                        id="status_kerja" name="status_kerja">
                                                        <option value="" disabled selected>--- Pilih Status ---
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

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
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
                    </form>
            </div>
        </div>
    </div>

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
