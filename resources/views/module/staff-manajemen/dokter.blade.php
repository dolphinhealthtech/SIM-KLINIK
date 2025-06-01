@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dokter</h1>
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
                    <!-- ./col -->
                    <div class="col-lg-6 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $dokterall }}</h3>

                                <p>Total Dokter</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-6 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $dokternoverif }}</h3>

                                <p>Dokter Belun Verifikasi</p>
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
                            <div class="card-header">
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#adddokterModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="doktertabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Poli</th>
                                            <th class="text-center">tanggal Masuk</th>
                                            <th class="text-center">status Pegawai</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dokter as $dokterdata)
                                            <tr class="{{ $dokterdata->verifikasi == 1 ? 'table-danger' : ($dokterdata->verifikasi == 2 ? 'table-success' : '') }}">
                                                <td class="text-center" >{{ $dokterdata->namauser->name }}</td>
                                                <td class="text-center" >{{ $dokterdata->namapoli->nama }}</td>
                                                <td class="text-center" >{{ $dokterdata->tgl_masuk }}</td>
                                                <td class="text-center" >{{ $dokterdata->namastatuspegawai->nama }}</td>
                                                <td class="text-center" >
                                                    @if ($dokterdata->verifikasi == 1)
                                                    <a class="btn btn-info rounded-pill lengkapi-btn" data-toggle="modal"
                                                        data-target="#lengkapiModal"
                                                        data-id="{{ $dokterdata->id }}">
                                                        <i class="fa fa-exclamation-circle"></i> Lengkapi
                                                    </a>
                                                    @else
                                                    <a class="btn btn-warning rounded-pill edit-btn" data-toggle="modal"
                                                        data-target="#editdokterModal"
                                                        data-id="{{ $dokterdata->id }}">
                                                        <i class="fa-solid fa-user-pen"></i> Edit
                                                    </a>
                                                    @endif
                                                    <a href="#" class="btn btn-danger rounded-pill delete-data-dokter"
                                                        data-toggle="modal"data-id="{{ $dokterdata->id }}"
                                                        data-nama-dokter="{{ $dokterdata->namauser->name }}"
                                                        data-target="#deletedokterModal">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>

                                                    <a href="#" class="btn btn-info rounded-pill jadwal-data-dokter"
                                                        data-toggle="modal"
                                                        data-id="{{ $dokterdata->id }}"
                                                        data-nama-dokter-jadwal="{{ $dokterdata->namauser->name }}"
                                                        data-target="#jadwaldokterModal">
                                                        <i class="far fa-clock"></i> Jadwal
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


    <!-- modal add Dokter -->
    <div class="modal fade" id="adddokterModal" tabindex="-1"
        aria-labelledby="modalTitle">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Data Dokter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addFormdokter" action="{{ route('dokter.store') }}" method="POST">
                        @csrf
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
                                            <select class="form-control select2bs4" style="width: 100%;" id="nama"
                                                name="nama">
                                                <option value="" disabled selected>Nama</option>
                                                @foreach ($user as $userdata)
                                                    <option value="{{ $userdata->id }}">{{ $userdata->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('nama')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Kode Dokter</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-center" id="kode" name="kode">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Poli</label>
                                            <select class="form-control select2bs4"  style="width: 100%;"  id="poli" name="poli">
                                                <option value="" disabled selected>--- Pilih Poli ---</option>
                                                @foreach ($poli as $polid)
                                                <option value="{{$polid->id}}">{{$polid->nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nomor NIK</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-center" id="nik" name="nik">
                                            </div>
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
                                            <label>ID Satu Sehat</label>
                                            <input type="text" class="form-control" id="kode_satu" name="kode_satu" >
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nomor STR  & Expired</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="str" name="str" style="width: 60%;" placeholder="Nomor STR">
                                                <input type="date" class="form-control" id="expstr" name="expstr" style="width: 40%;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nomor SIP & Expired</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="sip" name="sip" placeholder="Nomor SIP" style="width: 60%;">
                                                <input type="date" class="form-control" id="expspri" name="expspri" style="width: 40%;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Mulai Kerja Sejak</label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <br>
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
                                            <textarea class="form-control" placeholder="Masukkan alamat" id="alamat" name="alamat" rows="1"></textarea>
                                        </div>
                                        @error('alamat')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
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
                                                <option value="{{ $goldardata->id }}">
                                                    @if ($goldardata->resus == "null")
                                                        {{ $goldardata->nama }}
                                                    @else
                                                        {{ $goldardata->nama . $goldardata->resus }}
                                                    @endif
                                                </option>
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
                                                @foreach ($pendidikan as $pendidikandata)
                                                <option value="{{ $pendidikandata->kode }}">{{ $pendidikandata->nama }}</option>
                                            @endforeach
                                            </select>
                                            @error('pendidikan')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Telepon</label>
                                            <input type="text" class="form-control" id="telepon" name="telepon">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
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

                                    <div class="col-md-3">
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

                                    <div class="col-md-3">
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

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Tempat & Tanggal Lahir</label>
                                            <div class="input-group">
                                                <!-- Input Tempat Lahir -->
                                                <input type="text" class="form-control" id="tempat_lahir"
                                                    name="tempat_lahir"
                                                    placeholder="Tempat" style="width: 50%;">

                                                <!-- Input Tanggal Lahir -->
                                                <input type="date" class="form-control" id="tgl_lahir"
                                                    name="tgl_lahir"
                                                    style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Status Kerja</label>
                                            <select class="form-control select2bs4"  style="width: 100%;"  id="posker" name="posker">
                                                <option value="" disabled selected>--- Pilih Posisi ---</option>
                                                @foreach ($posker as $poskerd)
                                                <option value="{{$poskerd->id}}">{{$poskerd->nama}}</option>
                                                @endforeach
                                            </select>
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

    {{-- modal Delete Dokter --}}
    <div class="modal fade" id="deletedokterModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
        <div class="modal-dialog">
            <form id="deleteFormdokter" action="{{ route('dokter.destroy') }}" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Hapus Master Data dokter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="dokterid_delete" name="dokterid_delete">
                        <div id="deleteTextdokter"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- modal Verifikasi Dokter --}}
    <div class="modal fade" id="lengkapiModal" tabindex="-1" role="dialog" aria-labelledby="lengkapiModalLabel">
        <div class="modal-dialog modal-xl">
            <form id="lengkapiFormdokter" action="{{ route('dokter.verifikasi') }}" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="lengkapiModalLabel">Verifikasi Data dokter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="dokterid_verifikasi" name="dokterid_verifikasi">
                        <div class="form-group">
                            <label><strong>Pendidikan</strong></label>
                            <hr>
                            <div class="pendidikan-list col-12"></div>
                        </div>

                        <br>

                        <div class="form-group">
                            <label><strong>Pendidikan Spesialis</strong></label>
                            <hr>

                            <div id="spesialis-container" class="col-12"></div>

                            <div class="text-center">
                                <button type="button" class="btn btn-sm btn-primary mt-2" id="tambah-spesialis">+ Tambah Spesialis</button>
                            </div>
                        </div>

                        <br>

                        <div class="form-group">
                            <label><strong>Sertifikat Pelatihan Khusus</strong></label>
                            <hr>
                            <div id="pelatihan-container" class="col-12"></div>
                            <div class="text-center">
                                <button type="button" class="btn btn-sm btn-success mt-2" id="tambah-pelatihan">+ Tambah Pelatihan</button>
                            </div>
                        </div>

                        <br>

                        <div>
                            <label><strong>Informasi Bank</strong></label>
                            <hr>
                            <div class="col-12">
                                <div class="row align-items-end mb-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nama Bank</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="nama_bank"
                                                name="nama_bank">
                                                <option value="" disabled selected>Nama</option>
                                                @foreach ($bank as $bankdata)
                                                    <option value="{{ $bankdata->nama }}">{{ $bankdata->nama }} - {{ $bankdata->kode }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>No Rekening</label>
                                            <input type="text" class="form-control" id="norek" name="norek">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Cabang</label>
                                            <input type="text" class="form-control" id="cabang_bank" name="cabang_bank" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- modal edit Dokter --}}
    <div class="modal fade" id="editdokterModal" tabindex="-1" role="dialog" aria-labelledby="editdokterModalLabel">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editdokterModalLabel">Edit Data Dokter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="bs-stepper">
                        <div class="bs-stepper-header" role="tablist">
                            <!-- your steps here -->
                            <div class="step" data-target="#biodata">
                                <button type="button" class="step-trigger" role="tab" aria-controls="biodata"
                                    id="biodata-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Biodata</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#biodata-lanjutan">
                                <button type="button" class="step-trigger" role="tab"
                                    aria-controls="biodata-lanjutan" id="biodata-lanjutan-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">biodata lanjutan</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <form id="updatedokterForm" action="{{ route('dokter.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="dokterid_update" name="dokterid_update">

                                <!-- your steps content here -->
                                <div id="biodata" class="content" role="tabpanel" aria-labelledby="biodata-trigger">
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
                                                        <select class="form-control select2bs4" style="width: 100%;" id="nama_edit"
                                                            name="nama_edit">
                                                            <option value="" disabled selected>Nama</option>
                                                            @foreach ($user as $userdata)
                                                                <option value="{{ $userdata->id }}">{{ $userdata->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('nama')
                                                        <div style="color: red;">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Kode Dokter</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center" id="kode_edit" name="kode_edit">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Poli</label>
                                                        <select class="form-control select2bs4"  style="width: 100%;"  id="poli_edit" name="poli_edit">
                                                            <option value="" disabled selected>--- Pilih Poli ---</option>
                                                            @foreach ($poli as $polid)
                                                            <option value="{{$polid->id}}">{{$polid->nama}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Nomor NIK</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center" id="nik_edit" name="nik_edit">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Nomor NPWP</label>
                                                        <input type="text" class="form-control" id="npwp_edit" name="npwp_edit">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>ID Satu Sehat</label>
                                                        <input type="text" class="form-control" id="kode_satu_edit" name="kode_satu_edit" >
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Nomor STR  & Expired</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="str_edit" name="str_edit" style="width: 60%;" placeholder="Nomor STR">
                                                            <input type="date" class="form-control" id="expstr_edit" name="expstr_edit" style="width: 40%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Nomor SIP & Expired</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="sip_edit" name="sip_edit" placeholder="Nomor SIP" style="width: 60%;">
                                                            <input type="date" class="form-control" id="expspri_edit" name="expspri_edit" style="width: 40%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Mulai Kerja Sejak</label>
                                                        <div class="input-group">
                                                            <input type="date" class="form-control" id="tgl_masuk_edit" name="tgl_masuk_edit" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <br>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Provinsi</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="provinsi_edit"
                                                            name="provinsi_edit">
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
                                                            id="kabupaten_edit" name="kabupaten_edit">
                                                            <option value="" disabled selected>Kota/Kabupaten</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Kecamatan</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="kecamatan_edit"
                                                            name="kecamatan_edit" value="{{ old('kecamatan') }}">
                                                            <option value="" disabled selected>Kecamatan</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Desa/Kelurahan</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="desa_edit"
                                                            name="desa_edit" value="{{ old('desa') }}">
                                                            <option value="" disabled selected>Desa/Kelurahan</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-1">
                                                    <div class="form-group">
                                                        <label>RT </label>
                                                        <input type="text" class="form-control" placeholder="001" id="rt_edit"
                                                            name="rt_edit" value="{{ old('rt') }}">
                                                    </div>
                                                    @error('rt')
                                                        <div style="color: red;">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-1">
                                                    <div class="form-group">
                                                        <label>RW </label>
                                                        <input type="text" class="form-control" placeholder="002" id="rw_edit"
                                                            name="rw_edit" value="{{ old('rw') }}">
                                                    </div>
                                                    @error('rw')
                                                        <div style="color: red;">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label>Kode Pos </label>
                                                        <input type="text" class="form-control" id="kode_pos_edit" name="kode_pos_edit"
                                                            value="{{ old('kode_pos') }}">
                                                    </div>
                                                    @error('kode_pos')
                                                        <div style="color: red;">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <label>Alamat</label>
                                                        <textarea class="form-control" placeholder="Masukkan alamat" id="alamat_edit" name="alamat_edit" rows="1"></textarea>
                                                    </div>
                                                    @error('alamat')
                                                        <div style="color: red;">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Jenis Kelamin</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="seks_edit"
                                                            name="seks_edit">
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
                                                        <select class="form-control select2bs4" style="width: 100%;" id="goldar_edit"
                                                            name="goldar_edit">
                                                            <option value="" disabled selected>--- pilih ---</option>
                                                            @foreach ($goldar as $goldardata)
                                                            <option value="{{ $goldardata->id }}">
                                                                @if ($goldardata->resus == "null")
                                                                    {{ $goldardata->nama }}
                                                                @else
                                                                    {{ $goldardata->nama . $goldardata->resus }}
                                                                @endif
                                                            </option>
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
                                                        <select class="form-control select2bs4" style="width: 100%;" id="pernikahan_edit"
                                                            name="pernikahan_edit">
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
                                                            id="kewarganegaraan_edit" name="kewarganegaraan_edit">
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
                                                        <select class="form-control select2bs4" style="width: 100%;" id="agama_edit"
                                                            name="agama_edit">
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
                                                            id="pendidikan_edit" name="pendidikan_edit">
                                                            <option value="" disabled selected>--- pilih ---</option>
                                                            @foreach ($pendidikan as $pendidikandata)
                                                            <option value="{{ $pendidikandata->kode }}">{{ $pendidikandata->nama }}</option>
                                                        @endforeach
                                                        </select>
                                                        @error('pendidikan')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Telepon</label>
                                                        <input type="text" class="form-control" id="telepon_edit" name="telepon_edit">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Suku</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="suku_edit"
                                                            name="suku_edit">
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

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Bangsa</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="bangsa_edit"
                                                            name="bangsa_edit">
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

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Bahasa</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="bahasa_edit"
                                                            name="bahasa_edit">
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

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Tempat & Tanggal Lahir</label>
                                                        <div class="input-group">
                                                            <!-- Input Tempat Lahir -->
                                                            <input type="text" class="form-control" id="tempat_lahir_edit"
                                                                name="tempat_lahir_edit"
                                                                placeholder="Tempat" style="width: 50%;">

                                                            <!-- Input Tanggal Lahir -->
                                                            <input type="date" class="form-control" id="tgl_lahir_edit"
                                                                name="tgl_lahir_edit"
                                                                style="width: 50%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Status Kerja</label>
                                                        <select class="form-control select2bs4"  style="width: 100%;"  id="posker_edit" name="posker_edit">
                                                            <option value="" disabled selected>--- Pilih Posisi ---</option>
                                                            @foreach ($posker as $poskerd)
                                                            <option value="{{$poskerd->id}}">{{$poskerd->nama}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                </div>



                                <div id="biodata-lanjutan" class="content" role="tabpanel" aria-labelledby="biodata-lanjutan-trigger">
                                        <div class="form-group">
                                            <label><strong>Pendidikan</strong></label>
                                            <hr>
                                            <div class="pendidikan-list_edit col-12"></div>
                                        </div>

                                        <br>

                                        <div class="form-group">
                                            <label><strong>Pendidikan Spesialis</strong></label>
                                            <hr>
                                            <div id="spesialis-container_edit" class="col-12"></div>
                                            <div class="text-center">
                                                <button type="button" class="btn btn-sm btn-primary mt-2" id="tambah-spesialis_edit">+ Tambah Spesialis</button>
                                            </div>
                                        </div>

                                        <br>

                                        <div class="form-group">
                                            <label><strong>Sertifikat Pelatihan Khusus</strong></label>
                                            <hr>
                                            <div id="pelatihan-container_edit" class="col-12"></div>
                                            <div class="text-center">
                                                <button type="button" class="btn btn-sm btn-success mt-2" id="tambah-pelatihan_edit">+ Tambah Pelatihan</button>
                                            </div>
                                        </div>

                                        <br>

                                        <div>
                                            <label><strong>Informasi Bank</strong></label>
                                            <hr>
                                            <div class="col-12">
                                                <div class="row align-items-end mb-3">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Nama Bank</label>
                                                            <select class="form-control select2bs4" style="width: 100%;" id="nama_bank_edit"
                                                                name="nama_bank_edit">
                                                                <option value="" disabled selected>Nama</option>
                                                                @foreach ($bank as $bankdata)
                                                                    <option value="{{ $bankdata->nama }}">{{ $bankdata->nama }} - {{ $bankdata->kode }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>No Rekening</label>
                                                            <input type="text" class="form-control" id="norek_edit" name="norek_edit">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Cabang</label>
                                                            <input type="text" class="form-control" id="cabang_bank_edit" name="cabang_bank_edit">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Jadwal Dokter -->
    <div class="modal fade" id="jadwaldokterModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <form id="deleteFormdokter" action="#" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="nama-dokter-jadwal" class="modal-title"></h5>

                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div id="calendarDokter" style="height:600px; min-width: 100%;"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        var calendar;
        let name = $(this).data('nama-dokter-jadwal');
        let id = $(this).data('id');

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendarDokter');
            var Calendar = FullCalendar.Calendar;


            calendar = new Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                height: 800,
                slotMinTime: "00:00:00",
                slotMaxTime: "24:00:00",
                selectable: true,
                selectMirror: true,
                nowIndicator: true,

                select: function (info) {
                    const dokter_id = window.selectedDokterId;
                    const title = "Jadwal Masuk";

                    if (title) {
                        // Kirim ke server
                        fetch("{{ route('dokter.jadwal') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                dokter_id: dokter_id,
                                title: title,
                                start: info.startStr,
                                end: info.endStr
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            // Tambahkan langsung ke kalender
                            calendar.addEvent({
                                id: data.id,
                                title: title,
                                start: info.startStr,
                                end: info.endStr
                            });
                        })
                        .catch(err => console.error("Error:", err));
                    }

                    calendar.unselect();
                },

                events: function(fetchInfo, successCallback, failureCallback) {
                    if (!window.selectedDokterId) return;

                    fetch(`/api/jadwal/json/${window.selectedDokterId}`)
                        .then(res => res.json())
                        .then(data => successCallback(data))
                        .catch(err => failureCallback(err));
                },

                eventClick: function(info) {
                    Swal.fire({
                        title: 'Hapus Jadwal?',
                        text: "Apakah Anda yakin ingin menghapus jadwal ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const eventId = info.event.id;

                            fetch(`/dokter/jadwal/hapus/${eventId}`, {
                                method: "DELETE",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    info.event.remove();
                                    Swal.fire(
                                        'Terhapus!',
                                        'Jadwal berhasil dihapus.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Gagal!',
                                        'Tidak dapat menghapus jadwal.',
                                        'error'
                                    );
                                }
                            })
                            .catch(err => {
                                console.error("Error:", err);
                                Swal.fire(
                                    'Error!',
                                    'Terjadi kesalahan saat menghapus.',
                                    'error'
                                );
                            });
                        }
                    });
                }


            });

            $(document).on('click', '.jadwal-data-dokter', function () {
                // Ambil data dari tombol yang diklik
                let name = $(this).data('nama-dokter-jadwal');
                let id = $(this).data('id');

                $('#nama-dokter-jadwal').html(name);

                window.selectedDokterId = id;


                // Render calendar
                setTimeout(function () {
                    // Update source event sesuai ID dokter yang diklik
                    calendar.removeAllEvents();
                    calendar.refetchEvents(); // kalau pakai dynamic source
                    calendar.render();
                }, 200);
            });

        });
    </script>

    <script>

        $(document).ready(function () {

            function loadKabupaten(provinsiID, selectedKabupaten = "", callback = null) {
                if (!provinsiID) return;
                console.log("Loading Kabupaten untuk Provinsi ID:", provinsiID);

                $('#kabupaten_edit').html('<option value="">Memuat...</option>');
                $('#kecamatan_edit').html('<option value="" disabled selected>Pilih Kecamatan</option>');
                $('#desa_edit').html('<option value="" disabled selected>Pilih Kelurahan</option>');

                $.get("{{ route('get.kabupaten') }}", { provinsi_id: provinsiID })
                    .done(function (data) {
                        console.log("Data Kabupaten:", data);
                        let options = '<option value="" disabled selected>Pilih Kabupaten</option>';
                        $.each(data, function (index, kab) {
                            options += `<option value="${kab.kode}">${kab.name}</option>`;
                        });
                        $('#kabupaten_edit').html(options);

                        if (selectedKabupaten) {
                            $('#kabupaten_edit').val(selectedKabupaten);
                            console.log("Kabupaten Selected:", selectedKabupaten);
                            $('#kabupaten_edit').trigger('change');
                            if (callback) callback(selectedKabupaten);
                        }
                    })
                    .fail(function () {
                        console.error("Gagal mengambil data kabupaten.");
                    });
            }

            function loadKecamatan(kabupatenID, selectedKecamatan = "", callback = null) {
                if (!kabupatenID) return;
                console.log("Loading Kecamatan untuk Kabupaten ID:", kabupatenID);

                $('#kecamatan_edit').html('<option value="">Memuat...</option>');
                $('#desa_edit').html('<option value="" disabled selected>Pilih Kelurahan</option>');

                $.get("{{ route('get.kecamatan') }}", { kabupaten_id: kabupatenID })
                    .done(function (data) {
                        console.log("Data Kecamatan:", data);
                        let options = '<option value="" disabled selected>Pilih Kecamatan</option>';
                        $.each(data, function (index, kec) {
                            options += `<option value="${kec.kode}">${kec.name}</option>`;
                        });
                        $('#kecamatan_edit').html(options);

                        if (selectedKecamatan) {
                            $('#kecamatan_edit').val(selectedKecamatan);
                            console.log("Kecamatan Selected:", selectedKecamatan);
                            $('#kecamatan_edit').trigger('change');
                            if (callback) callback(selectedKecamatan);
                        }
                    })
                    .fail(function () {
                        console.error("Gagal mengambil data kecamatan.");
                    });
            }

            function loadDesa(kecamatanID, selectedDesa = "") {
                if (!kecamatanID) return;
                console.log("Loading Desa untuk Kecamatan ID:", kecamatanID);

                $('#desa_edit').html('<option value="">Memuat...</option>');

                $.get("{{ route('get.kelurahan') }}", { kecamatan_id: kecamatanID })
                    .done(function (data) {
                        console.log("Data Desa:", data);
                        let options = '<option value="" disabled selected>Pilih Kelurahan</option>';
                        $.each(data, function (index, kel) {
                            options += `<option value="${kel.kode}">${kel.name}</option>`;
                        });
                        $('#desa_edit').html(options);

                        if (selectedDesa) {
                            $('#desa_edit').val(selectedDesa);
                            console.log("Desa Selected:", selectedDesa);
                            $('#desa_edit').trigger('change');
                        }
                    })
                    .fail(function () {
                        console.error("Gagal mengambil data kelurahan.");
                    });
            }

            // Event saat Provinsi dipilih ulang
            $('#provinsi_edit').on('change', function () {
                let provinsiID = $(this).val();
                console.log("Provinsi dipilih:", provinsiID);
                loadKabupaten(provinsiID);
            });

            // Event saat Kabupaten dipilih ulang
            $('#kabupaten_edit').on('change', function () {
                let kabupatenID = $(this).val();
                console.log("Kabupaten dipilih:", kabupatenID);
                loadKecamatan(kabupatenID);
            });

            // Event saat Kecamatan dipilih ulang
            $('#kecamatan_edit').on('change', function () {
                let kecamatanID = $(this).val();
                console.log("Kecamatan dipilih:", kecamatanID);
                loadDesa(kecamatanID);
            });

            // Event saat tombol "Lengkapi" diklik
            $('.edit-btn').on('click', function () {
                // Ambil data-id dari tombol yang diklik
                let dokterId = $(this).data('id');

                // Contoh: Ambil data pasien dari server (opsional)
                $.get(`/api/get-dokter-all/${dokterId}`, function (data) {
                    $('#dokterid_update').val(data.dokter.id);
                    $('#nama_edit').val(data.dokter.users).trigger('change');
                    $('#kode_edit').val(data.dokter.kode);
                    $('#poli_edit').val(data.dokter.poli).trigger('change');
                    $('#nik_edit').val(data.dokter.nik);
                    $('#npwp_edit').val(data.dokter.npwp);
                    $('#kode_satu_edit').val(data.dokter.kode_satu);
                    $('#str_edit').val(data.dokter.str);
                    $('#expstr_edit').val(data.dokter.exp_str);
                    $('#sip_edit').val(data.dokter.sip);
                    $('#expspri_edit').val(data.dokter.exp_spri);
                    $('#tgl_masuk_edit').val(data.dokter.tgl_masuk);
                    $('#rt_edit').val(data.dokter.rt);
                    $('#rw_edit').val(data.dokter.rw);
                    $('#kode_pos_edit').val(data.dokter.kode_pos);
                    $('#alamat_edit').val(data.dokter.alamat);
                    $('#seks_edit').val(data.dokter.seks).trigger('change');
                    $('#goldar_edit').val(data.dokter.goldar).trigger('change');
                    $('#pernikahan_edit').val(data.dokter.pernikahan).trigger('change');
                    $('#kewarganegaraan_edit').val(data.dokter.kewarganegaraan).trigger('change');
                    $('#agama_edit').val(data.dokter.agama).trigger('change');
                    $('#pendidikan_edit').val(data.dokter.pendidikan).trigger('change');
                    $('#telepon_edit').val(data.dokter.telepon);
                    $('#suku_edit').val(data.dokter.suku).trigger('change');
                    $('#bangsa_edit').val(data.dokter.bangsa).trigger('change');
                    $('#bahasa_edit').val(data.dokter.bahasa).trigger('change');
                    $('#tempat_lahir_edit').val(data.dokter.tempat_lahir);
                    $('#tgl_lahir_edit').val(data.dokter.tanggal_lahir).trigger('change');
                    $('#posker_edit').val(data.dokter.status_pegawaian).trigger('change');

                    if (data.dokter.provinsi_kode) {
                        $('#provinsi_edit').val(data.dokter.provinsi_kode).trigger('change');
                            loadKabupaten(data.dokter.provinsi_kode, data.dokter.kabupaten_kode, function (kabupatenID) {
                            loadKecamatan(kabupatenID, data.dokter.kecamatan_kode, function (kecamatanID) {
                                loadDesa(kecamatanID, data.dokter.desa_kode);
                            });
                        });
                    }

                    let list = '';
                    data.dokter.verifikasi.pendidikan.forEach((item, index) => {
                        list += `
                            <div class="row align-items-end mb-3">
                                <input type="hidden" name="pendidikan[${index}][kode]" value="${item.kode}">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Sekolah ${item.kode}</label>
                                    <input type="text" name="pendidikan[${index}][nama_sekolah]" class="form-control" value="${item.nama_sekolah ?? ''}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Tahun Lulus ${item.kode}</label>
                                    <input type="month" name="pendidikan[${index}][tahun_lulus]" class="form-control" value="${item.tahun_lulus ?? ''}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Ijazah ${item.kode}</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="ijasah-${index}" name="pendidikan[${index}][ijasah]">
                                        <label class="custom-file-label" for="ijasah-${index}">${item.ijasah ?? 'Pilih file'}</label>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    $('#editdokterModal .pendidikan-list_edit').html(list);

                    // Spesialis
                    let spesialisList = '';
                    data.dokter.verifikasi.spesialis.forEach((item, index) => {
                        spesialisList += `
                            <div class="row align-items-end mb-3 spesialis-item_edit">
                                <div class="col-md-3">
                                    <label class="form-label">Nama Spesialis</label>
                                    <input type="text" name="spesialis[${index}][nama]" class="form-control" value="${item.nama ?? ''}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Institusi</label>
                                    <input type="text" name="spesialis[${index}][institusi]" class="form-control" value="${item.institusi ?? ''}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Tahun Lulus</label>
                                    <input type="month" name="spesialis[${index}][tahun_lulus]" class="form-control" value="${item.tahun_lulus ?? ''}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Ijazah</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="spesialis-ijasah-${index}" name="spesialis[${index}][ijasah]">
                                        <label class="custom-file-label" for="spesialis-ijasah-${index}">${item.ijasah ?? 'Pilih file'}</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-remove-spesialis_edit">×</button>
                                </div>
                            </div>
                        `;
                    });
                    $('#spesialis-container_edit').html(spesialisList);

                    // Pelatihan
                    let pelatihanList = '';
                    data.dokter.verifikasi.pelatihan.forEach((item, index) => {
                        pelatihanList += `
                            <div class="row align-items-end mb-3 pelatihan-item_edit">
                                <div class="col-md-3">
                                    <label class="form-label">Nama Pelatihan</label>
                                    <input type="text" name="pelatihan[${index}][nama]" class="form-control" value="${item.nama ?? ''}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Penyelenggara</label>
                                    <input type="text" name="pelatihan[${index}][penyelenggara]" class="form-control" value="${item.penyelenggara ?? ''}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Tahun</label>
                                    <input type="month" name="pelatihan[${index}][tahun]" class="form-control" value="${item.tahun ?? ''}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Upload Sertifikat</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="pelatihan-sertifikat-${index}" name="pelatihan[${index}][sertifikat]">
                                        <label class="custom-file-label" for="pelatihan-sertifikat-${index}">${item.sertifikat ?? 'Pilih file'}</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-remove-pelatihan_edit">×</button>
                                </div>
                            </div>
                        `;
                    });
                    $('#pelatihan-container_edit').html(pelatihanList);

                    // Informasi Bank
                    $('#nama_bank_edit').val(data.dokter.verifikasi.nama_bank).trigger('change');
                    $('#norek_edit').val(data.dokter.verifikasi.norek);
                    $('#cabang_bank_edit').val(data.dokter.verifikasi.cabang_bank);

                }).fail(function (error) {
                    console.error("Gagal mengambil data pasien:", error);
                });
            });
        });

        $(document).ready(function () {
            // Fitur Tambah Spesialis
            $('#tambah-spesialis_edit').on('click', function () {
                let index = $('#spesialis-container_edit .spesialis-item_edit').length;  // Ambil jumlah item spesialis saat ini
                let newItem = `
                    <div class="row align-items-end mb-3 spesialis-item_edit">
                        <div class="col-md-3">
                            <label class="form-label">Nama Spesialis</label>
                            <input type="text" name="spesialis[${index}][nama]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Institusi</label>
                            <input type="text" name="spesialis[${index}][institusi]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tahun Lulus</label>
                            <input type="month" name="spesialis[${index}][tahun_lulus]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ijazah</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="spesialis-ijasah-${index}" name="spesialis[${index}][ijasah]">
                                <label class="custom-file-label" for="spesialis-ijasah-${index}">Pilih file</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-remove-spesialis_edit">×</button>
                        </div>
                    </div>
                `;
                $('#spesialis-container_edit').append(newItem);
            });

            // Fitur Hapus Spesialis
            $(document).on('click', '.btn-remove-spesialis_edit', function () {
                $(this).closest('.spesialis-item_edit').remove();
            });

            // Fitur Tambah Pelatihan
            $('#tambah-pelatihan_edit').on('click', function () {
                let index = $('#pelatihan-container_edit .pelatihan-item_edit').length;  // Ambil jumlah item pelatihan saat ini
                let newItem = `
                    <div class="row align-items-end mb-3 pelatihan-item_edit">
                        <div class="col-md-3">
                            <label class="form-label">Nama Pelatihan</label>
                            <input type="text" name="pelatihan[${index}][nama]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Penyelenggara</label>
                            <input type="text" name="pelatihan[${index}][penyelenggara]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tahun</label>
                            <input type="month" name="pelatihan[${index}][tahun]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Upload Sertifikat</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="pelatihan-sertifikat-${index}" name="pelatihan[${index}][sertifikat]">
                                <label class="custom-file-label" for="pelatihan-sertifikat-${index}">Pilih file</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-remove-pelatihan_edit">×</button>
                        </div>
                    </div>
                `;
                $('#pelatihan-container_edit').append(newItem);
            });

            // Fitur Hapus Pelatihan
            $(document).on('click', '.btn-remove-pelatihan_edit', function () {
                $(this).closest('.pelatihan-item_edit').remove();
            });
        });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
    </script>

    <script>
        $(document).on('change', '.custom-file-input', function (e) {
            const fileName = e.target.files[0]?.name;
            $(this).next('.custom-file-label').html(fileName);
        });

        $('.lengkapi-btn').on('click', function () {
            let dokterId = $(this).data('id');

            $('#dokterid_verifikasi').val(dokterId);

            $.get(`/api/get-dokter/${dokterId}`, function (data) {
                let list = '';
                data.pendidikans.forEach((item, index) => {
                    list += `
                        <div class="row align-items-end mb-3">
                            <input type="hidden" name="pendidikan[${index}][kode]" value="${item.kode}">

                            <div class="col-md-6">
                                <label class="form-label">Nama Sekolah ${item.kode}</label>
                                <input type="text" name="pendidikan[${index}][nama_sekolah]" class="form-control" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Tahun Lulus ${item.kode}</label>
                                <input type="month" name="pendidikan[${index}][tahun_lulus]" class="form-control" required>
                            </div>

                           <div class="col-md-4">
                                <label class="form-label">Ijazah ${item.kode}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="ijasah-${index}" name="pendidikan[${index}][ijasah]">
                                    <label class="custom-file-label" for="ijasah-${index}">Pilih file</label>
                                </div>
                            </div>

                        </div>
                    `;
                });

                $('#lengkapiModal .pendidikan-list').html(list);
                $('#modalVerifikasi').modal('show');


            });

            let spesialisIndex = 0;

            $('#tambah-spesialis').on('click', function () {
                const html = `
                    <div class="row align-items-end mb-3 spesialis-item">
                        <div class="col-md-3">
                            <label class="form-label">Nama Spesialis</label>
                            <input type="text" name="spesialis[${spesialisIndex}][nama]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Institusi</label>
                            <input type="text" name="spesialis[${spesialisIndex}][institusi]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tahun Lulus</label>
                            <input type="month" name="spesialis[${spesialisIndex}][tahun_lulus]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ijazah</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="spesialis-ijasah-${spesialisIndex}" name="spesialis[${spesialisIndex}][ijasah]">
                                <label class="custom-file-label" for="spesialis-ijasah-${spesialisIndex}">Pilih file</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-remove-spesialis">×</button>
                        </div>
                    </div>
                `;
                $('#spesialis-container').append(html);
                spesialisIndex++;
            });

            $(document).on('click', '.btn-remove-spesialis', function () {
                $(this).closest('.spesialis-item').remove();
            });

            let pelatihanIndex = 0;

            $('#tambah-pelatihan').on('click', function () {
                const html = `
                    <div class="row align-items-end mb-3 pelatihan-item">
                        <div class="col-md-3">
                            <label class="form-label">Nama Pelatihan</label>
                            <input type="text" name="pelatihan[${pelatihanIndex}][nama]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Penyelenggara</label>
                            <input type="text" name="pelatihan[${pelatihanIndex}][penyelenggara]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tahun</label>
                            <input type="month" name="pelatihan[${pelatihanIndex}][tahun]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Upload Sertifikat</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="pelatihan-sertifikat-${pelatihanIndex}" name="pelatihan[${pelatihanIndex}][sertifikat]">
                                <label class="custom-file-label" for="pelatihan-sertifikat-${pelatihanIndex}">Pilih file</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-remove-pelatihan">×</button>
                        </div>
                    </div>
                `;
                $('#pelatihan-container').append(html);
                    pelatihanIndex++;
                });

                // Tampilkan nama file
                $(document).on('change', '.custom-file-input', function (e) {
                    const fileName = e.target.files[0]?.name;
                    $(this).next('.custom-file-label').html(fileName);
                });

                // Hapus baris pelatihan
                $(document).on('click', '.btn-remove-pelatihan', function () {
                    $(this).closest('.pelatihan-item').remove();
                });


        });
    </script>

    <script>
        $('#addFormdokter').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#adddokterModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: true
                            }).then(() => {
                                $('.modal-backdrop').remove(); // Hapus backdrop jika masih ada
                                location.reload(); // Reload halaman untuk update data
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = "Terjadi kesalahan dalam menyimpan data!";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            });


        $(document).on('click', '.delete-data-dokter', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-dokter');

            $('#dokterid_delete').val(id);
            $('#deleteTextdokter').html(
            `<span>Apa Anda yakin ingin menghapus data dokter <b>${name}</b> ?</span>`);
        });

        $('#deleteFormdokter').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#jadwaldokterModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            $('.modal-backdrop').remove(); // Hapus backdrop jika masih ada
                            location.reload(); // Reload halaman untuk update data
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus Dokter!',
                    });
                }
            });
        });

        $('#lengkapiFormdokter').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#lengkapiModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            $('.modal-backdrop').remove(); // Hapus backdrop jika masih ada
                            location.reload(); // Reload halaman untuk update data
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus Dokter!',
                    });
                }
            });
        });

        $('#updatedokterForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editdokterModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            $('.modal-backdrop').remove(); // Hapus backdrop jika masih ada
                            location.reload(); // Reload halaman untuk update data
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus Dokter!',
                    });
                }
            });
        });
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
            $("#doktertabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#doktertabel_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
