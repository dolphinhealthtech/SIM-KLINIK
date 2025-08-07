@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h5 class="text-muted text-center">Selamat datang di modul Pendaftaran Pasien</h5>
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
                                <h3>{{ $pasienallold }}</h3>

                                <p>Total Pasien lama</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-clock "></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $pasienallnewnow }}</h3>

                                <p>Total Pasien Baru Bulan ini</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $pasienall }}</h3>

                                <p>Total Pasien</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $pasiennoverif }}</h3>

                                <p>Pasien Belun Verifikasi</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-xmark"></i>
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
                                            <th class="text-center" width="10%">Status</th>
                                            <th class="text-center">No.RM</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Tanggal Lahir</th>
                                            <th class="text-center">No.Kartu BPJS</th>
                                            <th class="text-center">No.Telepon</th>
                                            <th class="text-center" width="25%">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pasiens as $pasiensdata)
                                            <tr>
                                                <td class="text-center" >
                                                    @if ($pasiensdata->verifikasi == 1)
                                                        <i class="fas fa-user-xmark text-danger fa-fade"  title="Belum Verifikasi" style="cursor: pointer;"></i> <br>
                                                        <span class="badge badge-danger">Belum Verifikasi</span>
                                                    @elseif ($pasiensdata->verifikasi == 2)
                                                        <i class="fas fa-user-check text-success fa-fade"  title="Sudah Verifikasi" style="cursor: pointer;"></i> <br>
                                                        <span class="badge badge-success">Sudah Verifikasi</span>
                                                    @else
                                                        <i class="fas fa-user-slash text-warning fa-fade"  title="Tidak Aktif" style="cursor: pointer;"></i> <br>
                                                        <span class="badge badge-warning">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="text-center" >{{ $pasiensdata->no_rm }}</td>
                                                <td class="text-center" >{{ $pasiensdata->nama }}</td>
                                                <td class="text-center">{{ $pasiensdata->tanggal_lahir }}</td>
                                                <td class="text-center">{{ $pasiensdata->no_bpjs }}</td>
                                                <td class="text-center">{{ $pasiensdata->telepon }}</td>
                                                <td class="text-center">
                                                    @if ($pasiensdata->verifikasi == 1)
                                                        <a class="btn btn-info rounded-pill lengkapi-btn" data-toggle="modal"
                                                            data-target="#lengkapiModal"
                                                            data-id="{{ $pasiensdata->id }}">
                                                            <i class="fa fa-exclamation-circle"></i> Lengkapi
                                                        </a>

                                                        <!-- Tombol Panggil untuk data yang belum dilengkapi -->
                                                        <a class="btn btn-primary rounded-pill panggil-btn" data-toggle="modal"
                                                            data-target="#panggilModal"
                                                            data-id="{{ $pasiensdata->id }}"
                                                            data-nama="{{ $pasiensdata->nama }}">
                                                            <i class="fa fa-bullhorn"></i> Panggil
                                                        </a>
                                                    @else
                                                        <a class="btn btn-outline-warning btn-sm rounded-pill edit-btn" data-toggle="modal"
                                                            data-target="#EditModal"
                                                            data-id="{{ $pasiensdata->id }}">
                                                            <i class="fa-solid fa-user-pen"></i> Edit
                                                        </a>
                                                    @endif
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

    <!-- Modal Panggil Pasien -->
<div class="modal fade" id="panggilModal" tabindex="-1" role="dialog" aria-labelledby="panggilModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="panggilModalLabel">Konfirmasi Panggil Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="panggilText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="konfirmasiPanggil">Panggil Sekarang</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Script untuk modal panggil
    $(document).on('click', '.panggil-btn', function() {
        let id = $(this).data('id');
        let nama = $(this).data('nama');

        $('#panggilText').html(`<span>Apakah Anda yakin ingin memanggil pasien <b>${nama}</b>?</span>`);

        // Ketika tombol konfirmasi diklik
        $('#konfirmasiPanggil').off('click').on('click', function() {
            $.ajax({
                url: "{{ route('pasien.panggil', ['id' => '__ID__']) }}".replace('__ID__', id),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        $('#panggilModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
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
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memanggil pasien.'
                    });
                }
            });
        });
    });
</script>

    <!-- Modal XL -->
    <div class="modal fade" id="lengkapiModal" tabindex="-1"
        aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Lengkapi Data Pasien</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pasien.verifikasi') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <style>
                                    .alert {
                                        padding: 15px;
                                        margin-bottom: 20px;
                                        border: 1px solid transparent;
                                        border-radius: 4px;
                                    }
                                    .alert-danger {
                                        color: #721c24;
                                        background-color: #f8d7da;
                                        border-color: #f5c6cb;
                                    }
                                </style>
                                <div class="text-center col-sm-6">
                                    <div class="form-group">
                                        <div id="bpjs_error1" class="alert alert-warning" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="text-center col-sm-6">
                                    <div class="form-group">
                                        <div id="bpjs_error" class="alert alert-danger" style="display: none;"></div>
                                    </div>
                                </div>
                            <div class="col-md-3 d-flex justify-content-center">
                                <div class="position-relative text-center">
                                    <!-- Input Gambar -->
                                    <input type="file" id="profileImageInput" name="profile_image" accept="image/*" class="d-none" onchange="previewImage(event)">

                                    <!-- Bingkai Gambar dengan Rasio 3:4 -->
                                    <label for="profileImageInput" class="d-block" style="cursor: pointer;">
                                        <div style="width: 100%;border: 2px solid #ccc; max-width: 180px; aspect-ratio: 3 / 4; overflow: hidden; border-radius: 10px;  background: #f0f0f0; display: flex; align-items: center; margin-top: 75px;  justify-content: center;">
                                            <img id="profileImage" class="img-fluid rounded"
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

                                    <input type="hidden" class="form-control" placeholder="nomor_rm" id="nomor_rm" name="nomor_rm">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" placeholder="nama"
                                                id="nama" name="nama" readonly required>
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
                                                     required
                                                    style="background-color: #f8f9fa; cursor: pointer; border: 1px solid #ced4da;">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="handleClick()" id="syncButton" title="Ambil NIK">
                                                        <i id="syncIcon" class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>Tempat & Tanggal Lahir</label>
                                            <div class="input-group">
                                                <!-- Input Tempat Lahir -->
                                                <input type="text" class="form-control" id="tempat_lahir"
                                                    name="tempat_lahir" required
                                                    placeholder="Tempat" style="width: 50%;">

                                                <!-- Input Tanggal Lahir -->
                                                <input type="date" class="form-control" id="tgllahir"
                                                    name="tgllahir" required
                                                    style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Provinsi</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="provinsi"
                                                name="provinsi" required>
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
                                                id="kabupaten" name="kabupaten" required>
                                                <option value="" disabled selected>Kota/Kabupaten</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Kecamatan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="kecamatan"
                                                name="kecamatan" value="{{ old('kecamatan') }}" required>
                                                <option value="" disabled selected>Kecamatan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Desa/Kelurahan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="desa"
                                                name="desa" value="{{ old('desa') }}" required>
                                                <option value="" disabled selected>Desa/Kelurahan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label>RT</label>
                                            <input type="text" class="form-control" placeholder="001" id="rt"
                                                name="rt" value="{{ old('rt') }}" required>
                                        </div>
                                        @error('rt')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label>RW</label>
                                            <input type="text" class="form-control" placeholder="002" id="rw"
                                                name="rw" value="{{ old('rw') }}" required>
                                        </div>
                                        @error('rw')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Kode Pos</label>
                                            <input type="text" class="form-control" id="kode_pos" name="kode_pos"
                                                value="{{ old('kode_pos') }}" required>
                                        </div>
                                        @error('kode_pos')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea class="form-control" placeholder="Masukkan alamat" id="alamat" name="alamat" rows="1" required></textarea>
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
                                                    name="noka"
                                                    placeholder="Noka BPJS" style="width: 50%;">

                                                <!-- Input Tanggal Lahir -->
                                                <input type="text" class="form-control text-center" id="noihs"
                                                name="noihs"
                                                placeholder="Noka SatuSehat" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group text-center">
                                            <label>Jenis & Kelas BPJS</label>
                                            <div class="input-group">

                                                <input type="text" class="form-control text-center" id="jenis_kartu"
                                                    name="jenis_kartu"
                                                    placeholder="Jenis Peserta BPJS" style="width: 50%;">

                                                <input type="text" class="form-control text-center" id="kelas"
                                                name="kelas"
                                                placeholder="Kelas BPJS" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group text-center">
                                            <label>Faskes & Masa Berlaku Kartu BPJS</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-center" id="provide"
                                                name="provide"
                                                placeholder="Provide BPJS" style="width: 50%;">

                                                <input type="date" class="form-control text-center" id="tgl_exp_bpjs"
                                                    name="tgl_exp_bpjs"
                                                    placeholder="Masa Berlaku Kartu BPJS" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Jenis Kelamin</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="seks"
                                                name="seks" required>
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
                                                name="goldar" required>
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
                                                name="pernikahan" required>
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
                                                id="kewarganegaraan" name="kewarganegaraan" required>
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
                                                name="agama" required>
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
                                                id="pendidikan" name="pendidikan" required>
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
                                            <label>Pekerja</label>
                                            <select class="form-control select2bs4" style="width: 100%;"
                                                id="status_kerja" name="status_kerja" required>
                                                <option value="" disabled selected>Pilih Pekerjaan</option>
                                                @foreach ($pekerjaan as $pekerjaandata)
                                                    <option value="{{ $pekerjaandata->id }}">{{ $pekerjaandata->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Telepon</label>
                                            <input type="text" class="form-control" id="telepon" name="telepon" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Suku</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="suku"
                                                name="suku" required>
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
                                            <label>Email</label>
                                            <input type="Email" class="form-control" id="email" name="email" required>
                                        </div>
                                    </div>
                                          {{-- Checkbox Penjamin 2 --}}
                                    <div class="col-sm-2 d-flex align-items-center">
                                        <div class="form-check">
                                            <label class="form-check-label" for="aktif_penjamin_2">&nbsp;</label>
                                            <input type="checkbox" class="form-check-input" id="aktif_penjamin_2">
                                            <label class="form-check-label" for="aktif_penjamin_2">Penjamin 2</label>
                                        </div>
                                    </div>

                                    {{-- Select Penjamin 2 --}}
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="penjamin_2">Penjamin 2</label>
                                            <select class="form-control" name="penjamin_2" id="penjamin_2" disabled>
                                                <option value="">-- Pilih Penjamin 2 --</option>
                                                {{-- @foreach($pejamin as $penjamin)
                                                    <option value="{{ $penjamin->nama }}">{{ $penjamin->nama }}</option>
                                                @endforeach --}}
                                                @foreach($asuransi as $asuransi2)
                                                    <option value="{{ $asuransi2->nama }}">{{ $asuransi2->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Input Penjamin 2 --}}
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="penjamin_2_info">No. Penjamin 2</label>
                                            <input type="text" class="form-control" name="penjamin_2_info" id="penjamin_2_info" placeholder="No. Penjamin 2" disabled>
                                        </div>
                                    </div>

                                    {{-- Checkbox Penjamin 3 --}}
                                    <div class="col-sm-2 d-flex align-items-center">
                                        <div class="form-check">
                                            <label class="form-check-label" for="aktif_penjamin_3">&nbsp;</label>
                                            <input type="checkbox" class="form-check-input" id="aktif_penjamin_3">
                                            <label class="form-check-label" for="aktif_penjamin_3">Penjamin 3</label>
                                        </div>
                                    </div>

                                    {{-- Select Penjamin 3 --}}
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="penjamin_3">Penjamin 3</label>
                                            <select class="form-control" name="penjamin_3" id="penjamin_3" disabled>
                                                <option value="">-- Pilih Penjamin 3 --</option>
                                                @foreach($asuransi as $asuransi3)
                                                    <option value="{{ $asuransi3->nama }}">{{ $asuransi3->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Input Penjamin 3 --}}
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="penjamin_3_info">No. Penjamin 3</label>
                                            <input type="text" class="form-control" name="penjamin_3_info" id="penjamin_3_info" placeholder="No. Penjamin 3" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="kodeprovide" name="kodeprovide">
                        <input type="hidden" id="hubungan_keluarga" name="hubungan_keluarga">
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


    <!-- Modal XL -->
    <div class="modal fade" id="EditModal" tabindex="-1"
        aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Edit Data Pasien</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pasien.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <style>
                                    .alert {
                                        padding: 15px;
                                        margin-bottom: 20px;
                                        border: 1px solid transparent;
                                        border-radius: 4px;
                                    }
                                    .alert-danger {
                                        color: #721c24;
                                        background-color: #f8d7da;
                                        border-color: #f5c6cb;
                                    }
                                </style>
                                <div class="text-center col-sm-6">
                                    <div class="form-group">
                                        <div id="bpjs_error_edit_1" class="alert alert-warning" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="text-center col-sm-6">
                                    <div class="form-group">
                                        <div id="bpjs_error_edit" class="alert alert-danger" style="display: none;"></div>
                                    </div>
                                </div>
                            <div class="col-md-3 d-flex justify-content-center">
                                <div class="position-relative text-center">
                                    <!-- Input Gambar -->
                                    <input type="file" id="profileImageInput_edit" name="profile_image_edit" accept="image/*" class="d-none" onchange="previewImage_edit(event)">

                                    <!-- Bingkai Gambar dengan Rasio 3:4 -->
                                    <label for="profileImageInput_edit" class="d-block" style="cursor: pointer;">
                                        <div style="width: 100%;border: 2px solid #ccc; max-width: 180px; aspect-ratio: 3 / 4; overflow: hidden; border-radius: 10px;  background: #f0f0f0; display: flex; align-items: center; margin-top: 75px;  justify-content: center;">
                                            <img id="profileImage_edit" class="img-fluid rounded"
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

                                    <input type="hidden" class="form-control" placeholder="nomor_rm" id="nomor_rm_edit" name="nomor_rm_edit" value="{{ old('nomor_rm_edit') }}">

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" placeholder="nama"
                                                id="nama_edit" name="nama_edit" readonly value="{{ old('nama_edit') }}" required>
                                        </div>
                                        @error('nama_edit')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Nomor NIK</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-center" id="nik_edit" name="nik_edit"
                                                     required
                                                    style="background-color: #f8f9fa; cursor: pointer; border: 1px solid #ced4da;">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="handleClick_edit()" id="syncButton" title="Ambil NIK">
                                                        <i id="syncIcon_edit" class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>Tempat & Tanggal Lahir</label>
                                            <div class="input-group">
                                                <!-- Input Tempat Lahir -->
                                                <input type="text" class="form-control" id="tempat_lahir_edit"
                                                    name="tempat_lahir_edit" value="{{ old('tempat_lahir_edit') }}" required
                                                    placeholder="Tempat" style="width: 50%;">

                                                <!-- Input Tanggal Lahir -->
                                                <input type="date" class="form-control" id="tgllahir_edit"
                                                    name="tgllahir_edit" value="{{ old('tgllahir_edit') }}" required
                                                    style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Provinsi</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="provinsi_edit"
                                                name="provinsi_edit" value="{{ old('provinsi_edit') }}" required>
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
                                                id="kabupaten_edit" name="kabupaten_edit" value="{{ old('kabupaten_edit') }}" required>
                                                <option value="" disabled selected>Kota/Kabupaten</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Kecamatan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="kecamatan_edit"
                                                name="kecamatan_edit" value="{{ old('kecamatan_edit') }}" required>
                                                <option value="" disabled selected>Kecamatan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Desa/Kelurahan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="desa_edit"
                                                name="desa_edit" value="{{ old('desa_edit') }}" required>
                                                <option value="" disabled selected>Desa/Kelurahan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label>RT</label>
                                            <input type="text" class="form-control" placeholder="001" id="rt_edit"
                                                name="rt_edit" value="{{ old('rt_edit') }}" required>
                                        </div>
                                        @error('rt_edit')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label>RW</label>
                                            <input type="text" class="form-control" placeholder="002" id="rw_edit"
                                                name="rw_edit" value="{{ old('rw_edit') }}" required>
                                        </div>
                                        @error('rw')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Kode Pos</label>
                                            <input type="text" class="form-control" id="kode_pos_edit" name="kode_pos_edit"
                                                value="{{ old('kode_pos_edit') }}" required>
                                        </div>
                                        @error('kode_pos_edit')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea class="form-control" placeholder="Masukkan alamat" id="alamat_edit" name="alamat_edit" rows="1" value="{{ old('alamat_edit') }}" required></textarea>
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
                                                <input type="text" class="form-control text-center" id="noka_edit"
                                                    name="noka_edit" value="{{ old('noka_edit') }}"
                                                    placeholder="Noka BPJS" style="width: 50%;">

                                                <!-- Input Tanggal Lahir -->
                                                <input type="text" class="form-control text-center" id="noihs_edit"
                                                name="noihs_edit" value="{{ old('noihs_edit') }}"
                                                placeholder="Noka SatuSehat" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group text-center">
                                            <label>Jenis & Kelas BPJS</label>
                                            <div class="input-group">

                                                <input type="text" class="form-control text-center" id="jenis_kartu_edit"
                                                    name="jenis_kartu_edit" value="{{ old('jenis_kartu_edit') }}"
                                                    placeholder="Jenis Peserta BPJS" style="width: 50%;">

                                                <input type="text" class="form-control text-center" id="kelas_edit"
                                                name="kelas_edit" value="{{ old('kelas_edit') }}"
                                                placeholder="Kelas BPJS" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group text-center">
                                            <label>Faskes & Masa Berlaku Kartu BPJS</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-center" id="provide_edit"
                                                name="provide_edit" value="{{ old('provide_edit') }}"
                                                placeholder="Provide BPJS" style="width: 50%;">

                                                <input type="date" class="form-control text-center" id="tgl_exp_bpjs_edit"
                                                    name="tgl_exp_bpjs_edit" value="{{ old('tgl_exp_bpjs_edit') }}"
                                                    placeholder="Masa Berlaku Kartu BPJS" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Jenis Kelamin</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="seks_edit"
                                                name="seks_edit" value="{{ old('seks_edit') }}" required>
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
                                                name="goldar_edit" value="{{ old('goldar_edit') }}" required>
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
                                                name="pernikahan_edit" value="{{ old('pernikahan_edit') }}" required>
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
                                                id="kewarganegaraan_edit" name="kewarganegaraan_edit" required>
                                                <option value=""
                                                    {{ old('kewarganegaraan_edit') == '' ? 'selected' : '' }}>--- pilih ---
                                                </option>
                                                <option value="wni"
                                                    {{ old('kewarganegaraan_edit') == 'wni' ? 'selected' : '' }}>Warga Negara
                                                    Indonesia</option>
                                                <option value="wna"
                                                    {{ old('kewarganegaraan_edit') == 'wna' ? 'selected' : '' }}>Warga Negara
                                                    Asing</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Agama</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="agama_edit"
                                                name="agama_edit" value="{{ old('agama_edit') }}" required>
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
                                                id="pendidikan_edit" name="pendidikan_edit" value="{{ old('pendidikan_edit') }}" required>
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
                                            <label>Pekerja</label>
                                            <select class="form-control select2bs4" style="width: 100%;"
                                                id="status_kerja_edit" name="status_kerja_edit" value="{{ old('status_kerja_edit') }}" required>
                                                <option value="" disabled selected>Pilih Pekerjaan</option>
                                                @foreach ($pekerjaan as $pekerjaandata)
                                                    <option value="{{ $pekerjaandata->id }}">{{ $pekerjaandata->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Telepon</label>
                                            <input type="text" class="form-control" id="telepon_edit" name="telepon_edit" value="{{ old('telepon_edit') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Suku</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="suku_edit"
                                                name="suku_edit" value="{{ old('suku_edit') }}" required>
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
                                                name="bangsa_edit" value="{{ old('bangsa_edit') }}">
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
                                                name="bahasa_edit" value="{{ old('bahasa_edit') }}">
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
                                            <label>Email</label>
                                            <input type="Email" class="form-control" id="email_edit" name="email_edit" value="{{ old('email_edit') }}" required>
                                        </div>
                                    </div>
                                        {{-- Checkbox Penjamin 2 --}}
                                    <div class="col-sm-2 d-flex align-items-center">
                                        <div class="form-check">
                                            <label class="form-check-label" for="aktif_penjamin_2_edit">&nbsp;</label>
                                            <input type="checkbox" class="form-check-input" id="aktif_penjamin_2_edit">
                                            <label class="form-check-label" for="aktif_penjamin_2_edit">Penjamin 2</label>
                                        </div>
                                    </div>

                                    {{-- Select Penjamin 2 --}}
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="penjamin_2_edit">Penjamin 2</label>
                                            <select class="form-control" name="penjamin_2_edit" id="penjamin_2_edit" disabled>
                                                <option value="">-- Pilih Penjamin 2 --</option>
                                                @foreach($asuransi as $asuransi2_edit)
                                                    <option value="{{ $asuransi2_edit->nama }}">{{ $asuransi2_edit->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Input Penjamin 2 --}}
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="penjamin_2_info_edit">No. Penjamin 2</label>
                                            <input type="text" class="form-control" name="penjamin_2_info_edit" id="penjamin_2_info_edit" placeholder="No. Penjamin 2" disabled>
                                        </div>
                                    </div>

                                    {{-- Checkbox Penjamin 3 --}}
                                    <div class="col-sm-2 d-flex align-items-center">
                                        <div class="form-check">
                                            <label class="form-check-label" for="aktif_penjamin_3_edit">&nbsp;</label>
                                            <input type="checkbox" class="form-check-input" id="aktif_penjamin_3_edit">
                                            <label class="form-check-label" for="aktif_penjamin_3_edit">Penjamin 3</label>
                                        </div>
                                    </div>

                                    {{-- Select Penjamin 3 --}}
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="penjamin_3_edit">Penjamin 3</label>
                                            <select class="form-control" name="penjamin_3_edit" id="penjamin_3_edit" disabled>
                                                <option value="">-- Pilih Penjamin 3 --</option>
                                                @foreach($asuransi as $asuransi_3_edit)
                                                    <option value="{{ $asuransi_3_edit->nama }}">{{ $asuransi_3_edit->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Input Penjamin 3 --}}
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="penjamin_3_info_edit">No. Penjamin 3</label>
                                            <input type="text" class="form-control" name="penjamin_3_info_edit" id="penjamin_3_info_edit" placeholder="No. Penjamin 3" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="user_edit" name="user_edit" value="{{ old('user_edit') }}">
                        <input type="hidden" id="kodeprovide_edit" name="kodeprovide_edit" value="{{ old('kodeprovide_edit') }}">
                        <input type="hidden" id="hubungan_keluarga_edit" name="hubungan_keluarga_edit" value="{{ old('hubungan_keluarga_edit') }}">
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
        $('#aktif_penjamin_2').on('change', function () {
            const aktif = $(this).is(':checked');
            $('#penjamin_2, #penjamin_2_info').prop('disabled', !aktif);
        });

        $('#aktif_penjamin_3').on('change', function () {
            const aktif = $(this).is(':checked');
            $('#penjamin_3, #penjamin_3_info').prop('disabled', !aktif);
        });
            $('#aktif_penjamin_2_edit').on('change', function () {
            const aktif = $(this).is(':checked');
            $('#penjamin_2_edit, #penjamin_2_info_edit').prop('disabled', !aktif);
        });

        $('#aktif_penjamin_3_edit').on('change', function () {
            const aktif = $(this).is(':checked');
            $('#penjamin_3_edit, #penjamin_3_info_edit').prop('disabled', !aktif);
        });

    </script>
    <script>
        function formatDate_edit(dateString) {
                let parts = dateString.split("-"); // Pisahkan berdasarkan "-"
                return `${parts[2]}-${parts[1]}-${parts[0]}`; // Susun ulang menjadi "yyyy-MM-dd"
            }

            function updateInputValue_edit(inputElement, newValue) {
                if (inputElement.value.trim() !== newValue) {
                    inputElement.value = newValue;
                }
            }

            function handleClick_edit() {
                const icon = document.getElementById("syncIcon_edit"); // Ambil ikon di dalam tombol
                icon.classList.add('fa-spin'); // Mulai animasi putar

                let nik = document.getElementById("nik_edit").value; // Ambil nilai NIK dari input
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
                        let nokaInput = document.getElementById("noka_edit");
                        let jenisKartuInput = document.getElementById("jenis_kartu_edit");
                        let kelasInput = document.getElementById("kelas_edit");
                        let provideInput = document.getElementById("provide_edit");
                        let expbpjsInput = document.getElementById("tgl_exp_bpjs_edit");
                        let tgllahirInput = document.getElementById("tgllahir_edit");
                        let namaInput = document.getElementById("nama_edit");
                        let kodeprovide = document.getElementById("kodeprovide_edit");
                        let hubungankeluarga = document.getElementById("hubungan_keluarga_edit");

                        // Update nilai input hanya jika berbeda
                        updateInputValue_edit(nokaInput, data.noKartu);
                        updateInputValue_edit(jenisKartuInput, data.jnsPeserta.nama);
                        updateInputValue_edit(kelasInput, data.jnsKelas.nama);
                        updateInputValue_edit(provideInput, data.kdProviderPst.nmProvider);
                        updateInputValue_edit(kodeprovide, data.kdProviderPst.kdProvider);
                        updateInputValue_edit(hubungankeluarga, data.hubunganKeluarga);
                        if (data.tglAkhirBerlaku) {
                            updateInputValue_edit(expbpjsInput, formatDate_edit(data.tglAkhirBerlaku));
                        }
                        if (data.tglLahir) {
                            updateInputValue_edit(tgllahirInput, formatDate_edit(data.tglLahir));
                        }
                        updateInputValue_edit(namaInput, data.nama);

                        var ket = data.aktif || false;

                        if (ket === true) {
                            $('#bpjs_error_edit').hide();
                        } else {
                            $('#bpjs_error_edit').text(data.ketAktif || 'Status tidak aktif').show();
                        }

                        const kode = {
                            KPFK: "{{ $kodefasyankes->KPFK }}"
                        };
                        console.log(kode);
                        if (kode.KPFK === data.kdProviderPst.kdProvider) {
                            $('#bpjs_error_edit_1').hide();
                        } else {
                            $('#bpjs_error_edit_1').text('Faskes BPJS tidak Sesuai').show();
                        }

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
                                let noihsInput = document.getElementById("noihs_edit"); // Ambil elemen input noihs
                                if (noihsData.status === "success" && noihsData.data) {
                                    updateInputValue_edit(noihsInput, noihsData.data); // Update No IHS
                                }
                            })
                            .catch(error => {
                                console.error("Gagal mengambil No IHS:", error);
                            }).finally(() => {
                                icon.classList.remove('fa-spin'); // Stop animasi setelah proses selesai
                            });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: "Data tidak ditemukan."
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: "Gagal mengambil data dari API."
                        });
                                icon.classList.remove('fa-spin');

                });
            }
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
            $(document).on('click', '.edit-btn', function () {
                let pasienId = $(this).data('id');
                console.log("Edit Pasien ID:", pasienId);

                $.get(`/api/get-pasien/${pasienId}`)
                    .done(function (data) {
                        $('#nomor_rm_edit').val(data.no_rm);
                        $('#nama_edit').val(data.nama);
                        $('#nik_edit').val(data.nik);
                        $('#tempat_lahir_edit').val(data.tempat_lahir);
                        $('#tgllahir_edit').val(data.tanggal_lahir);
                        $('#rt_edit').val(data.rt);
                        $('#rw_edit').val(data.rw);
                        $('#kode_pos_edit').val(data.kode_pos);
                        $('#alamat_edit').val(data.alamat);
                        $('#noka_edit').val(data.no_bpjs);
                        $('#noihs_edit').val(data.kode_ihs);
                        $('#jenis_kartu_edit').val(data.jenis_Kartu_bpjs);
                        $('#kelas_edit').val(data.kelas_bpjs);
                        $('#provide_edit').val(data.provide);
                        $('#tgl_exp_bpjs_edit').val(data.tgl_exp_bpjs);
                        $('#goldar_edit').val(data.goldar).trigger('change');
                        $('#seks_edit').val(data.seks).trigger('change');
                        $('#pernikahan_edit').val(data.pernikahan).trigger('change');
                        $('#kewarganegaraan_edit').val(data.kewarganegaraan).trigger('change');
                        $('#agama_edit').val(data.agama).trigger('change');
                        $('#pendidikan_edit').val(data.pendidikan).trigger('change');
                        $('#status_kerja_edit').val(data.pekerjaan).trigger('change');
                        $('#telepon_edit').val(data.telepon);
                        $('#user_edit').val(data.users);
                        $('#kodeprovide_edit').val(data.kodeprovide);
                        $('#hubungan_keluarga_edit').val(data.hubungan_keluarga);
                        $('#suku_edit').val(data.suku).trigger('change');
                        $('#bangsa_edit').val(data.bangsa).trigger('change');
                        $('#bahasa_edit').val(data.bahasa).trigger('change');
                        $('#email_edit').val(data.getnama?.email ?? '');

                        if (data.getnama?.profile) {
                            $('#profileImage_edit').attr('src', `/profile/${data.getnama.profile}`);
                        } else {
                            // Gunakan default jika kosong
                            $('#profileImage_edit').attr('src', `/profile/default.png`);
                        }

                        // Load Provinsi, Kabupaten, Kecamatan, Desa secara berurutan
                        if (data.provinsi_kode) {
                            $('#provinsi_edit').val(data.provinsi_kode).trigger('change');
                            loadKabupaten(data.provinsi_kode, data.kabupaten_kode, function (kabupatenID) {
                                loadKecamatan(kabupatenID, data.kecamatan_kode, function (kecamatanID) {
                                    loadDesa(kecamatanID, data.desa_kode);
                                });
                            });
                        }
                    })
                    .fail(function (error) {
                        console.error("Gagal mengambil data pasien:", error);
                    });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Event saat tombol "Lengkapi" diklik
            $('.lengkapi-btn').on('click', function () {
                // Ambil data-id dari tombol yang diklik
                let pasienId = $(this).data('id');

                // Contoh: Ambil data pasien dari server (opsional)
                $.get(`/api/get-pasien/${pasienId}`, function (data) {
                    $('#nomor_rm').val(data.no_rm);
                    $('#nama').val(data.nama);
                    $('#nik').val(data.nik);
                    $('#tgllahir').val(data.tanggal_lahir);
                    $('#noka').val(data.no_bpjs);
                    $('#noihs').val(data.kode_ihs);
                    $('#alamat').val(data.alamat);
                    $('#telepon').val(data.telepon);
                    $('#pernikahan').val(data.pernikahan);
                    $('#goldar').val(data.goldar).trigger('change');
                    $('#seks').val(data.seks).trigger('change');
                    $('#email').val(data.getnama?.email ?? '');
                    $('#bangsa').val(data.bangsa).trigger('change');
                    $('#bahasa').val(data.bahasa).trigger('change');

                    if (data.getnama?.profile) {
                        $('#profileImage').attr('src', `/profile/${data.getnama.profile}`);
                    } else {
                        // Gunakan default jika kosong
                        $('#profileImage').attr('src', `/profile/default.png`);
                    }

                    // generateCredentials();
                }).fail(function (error) {
                    console.error("Gagal mengambil data pasien:", error);
                });
            });
        });
    </script>

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
            const icon = document.getElementById("syncIcon"); // Ambil ikon di dalam tombol
            icon.classList.add('fa-spin'); // Mulai animasi putar

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
                    let kodeprovide = document.getElementById("kodeprovide");
                    let hubungankeluarga = document.getElementById("hubungan_keluarga");

                    // Update nilai input hanya jika berbeda
                    updateInputValue(nokaInput, data.noKartu);
                    updateInputValue(jenisKartuInput, data.jnsPeserta.nama);
                    updateInputValue(kelasInput, data.jnsKelas.nama);
                    updateInputValue(provideInput, data.kdProviderPst.nmProvider);
                    updateInputValue(kodeprovide, data.kdProviderPst.kdProvider);
                    updateInputValue(hubungankeluarga, data.hubunganKeluarga);
                    if (data.tglAkhirBerlaku) {
                        updateInputValue(expbpjsInput, formatDate(data.tglAkhirBerlaku));
                    }
                    if (data.tglLahir) {
                        updateInputValue(tgllahirInput, formatDate(data.tglLahir));
                    }
                    updateInputValue(namaInput, data.nama);

                    var ket = data.aktif || false;
                        if (ket === true) {
                            $('#bpjs_error').hide();
                        } else {
                            $('#bpjs_error').text(data.ketAktif || 'Status tidak aktif').show();
                        }
                        const kode = {
                            KPFK: "{{ $kodefasyankes->KPFK }}"
                        };
                        console.log(kode);
                        if (kode.KPFK === data.kdProviderPst.kdProvider) {
                            $('#bpjs_error1').hide();
                        } else {
                            $('#bpjs_error1').text('Faskes BPJS tidak Sesuai').show();
                        }

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
                        }).finally(() => {
                            icon.classList.remove('fa-spin'); // Stop animasi setelah proses selesai
                        });


                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: "Data tidak ditemukan."
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "Gagal mengambil data dari API."
                });
                icon.classList.remove('fa-spin');

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
        function previewImage_edit(event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function() {
                document.getElementById('profileImage_edit').src = reader.result;
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
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
                "buttons": false,
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ total entri)",
                }
            }).buttons().container().appendTo('#userstabel_wrapper .col-md-6:eq(0)');
        });
    </script>

@endsection

