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
                            <div class="col-md-3">
                                <div>
                                    <!-- Bingkai Gambar dengan Rasio 3:4 -->
                                    <div id="imageFrame"
                                        style="display: flex; justify-content: center; align-items: center; width: 80%; height: 0; padding-bottom: 110%; position: relative; overflow: hidden; background-color: #f0f0f0; cursor: pointer; margin-top: 75px; margin-left: 20px;"
                                        onclick="document.getElementById('foto').click();">
                                        <!-- Gambar Profil Pengguna -->
                                        <img class="profile-user-img img-fluid" alt="Foto profile" id="previewImage"
                                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <!-- Input file disembunyikan, akan di-trigger oleh klik pada imageFrame -->
                                    <input type="file" class="form-control d-none" id="foto" name="foto"
                                        accept="image/*" onchange="previewImage(event)">
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
                                            <label>
                                                Nama <input type="checkbox" id="manual_check"> Input Manual
                                            </label>
                                            <div class="input-group" id="nama_dokter_container">
                                                <input type="text" class="form-control" id="nama_dokter_input"
                                                    name="nama_dokter" placeholder="Masukkan Nama Dokter"
                                                    style="width: 59%; display: none;">
                                                <select class="form-control select2bs4" id="nama_dokter"
                                                    name="nama_dokter" style="width: 59%;">
                                                    <option value="" disabled selected>--- Pilih Dokter ---</option>
                                                </select>
                                                <input type="text" class="form-control" placeholder="Kode Dokter"
                                                    id="kode_dokter" name="kode_dokter" style="width: 41%;" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Alamat </label>
                                            <input type="text" class="form-control" placeholder="Alamat"
                                                id="Alamat" name="Alamat" value="{{ old('Alamat') }}">
                                        </div>
                                        @error('Alamat')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>NIK / Nomer KTP</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="nik" name="nik"
                                                    oninput="cekSatuSehat()">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Tempat Tanggal Lahir</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="tempat_lahir"
                                                    name="tempat_lahir" placeholder="Tempat" style="width: 60%;">
                                                <input type="date" class="form-control" id="tgllahir"
                                                    name="tgllahir" style="width: 40%;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Provinsi</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="provinsi"
                                                name="provinsi">
                                                <option value="" disabled selected>Provinsi</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Kota/Kabupaten</label>
                                            <select class="form-control select2bs4" style="width: 100%;"
                                                id="kota_kabupaten" name="kota_kabupaten">
                                                <option value="" disabled selected>Kota/Kabupaten</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Kecamatan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="kecamatan"
                                                name="kecamatan" value="{{ old('kecamatan') }}">
                                                <option value="" disabled selected>Kecamatan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
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
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Seks</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="seks"
                                                name="seks">
                                                <option value="" disabled selected>--- Pilih Jenis Kelamin ---
                                                </option>

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
                                                <option value="islam" {{ old('agama') == 'islam' ? 'selected' : '' }}>
                                                    Islam</option>
                                                <option value="katolik" {{ old('agama') == 'katolik' ? 'selected' : '' }}>
                                                    Kristen Katolik</option>
                                                <option value="protestan"
                                                    {{ old('agama') == 'protestan' ? 'selected' : '' }}>Kristen Protestan
                                                </option>
                                                <option value="hindu" {{ old('agama') == 'hindu' ? 'selected' : '' }}>
                                                    Hindu</option>
                                                <option value="buddha" {{ old('agama') == 'buddha' ? 'selected' : '' }}>
                                                    Buddha</option>
                                                <option value="khonghucu"
                                                    {{ old('agama') == 'khonghucu' ? 'selected' : '' }}>Khonghucu</option>
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
                            <div class="col-12">
                                <hr>
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
