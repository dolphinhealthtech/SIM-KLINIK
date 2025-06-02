@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Permintaan Radiologi / Laboratorium</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Permintaan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Data Pasien -->
                <div class="col-md-4">
                    <div class="card card-primary card-outline" style="height: 780px;">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-injured mr-2"></i>Data Pasien</h3>
                        </div>
                        <div class="card-body">
                            <!-- Brand Logo dari sidebar dengan path yang diubah ke public/profile/default.png -->
                            <div class="text-center mb-4">
                                <img src="{{ asset('profile/default.png') }}"
                                    alt="Klinik Logo" class="img-circle elevation-2"
                                    style="width: 100px; height: 100px; opacity: .8">
                            </div>

                            <div class="form-group">
                                <label for="nomor_rm">No. RM</label>
                                <input type="text" class="form-control bg-light" id="nomor_rm" value="{{ $pelayanan->nomor_rm }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="nama">Nama Pasien</label>
                                <input type="text" class="form-control bg-light" id="nama" value="{{ $pelayanan->pasien->nama }}" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <input type="text" class="form-control bg-light" id="jenis_kelamin" value="{{ $pelayanan->pasien->kelamin->nama }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="penjamin">Penjamin</label>
                                        <input type="text" class="form-control bg-light" id="penjamin" value="{{ $pelayanan->pendaftaran->penjamin->nama }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="text" class="form-control bg-light" id="tanggal_lahir" value="{{ $pelayanan->pasien->tanggal_lahir }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="umur">Umur</label>
                                        <input type="text" class="form-control bg-light" id="umur" value="{{ $umur }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="dokter_pengirim">Dokter Pengirim</label>
                                <input type="text" class="form-control bg-light" id="dokter_pengirim" name="dokter_pengirim" value="{{ $pelayanan->pendaftaran->dokter->namauser->name }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="poli">Poli</label>
                                <input type="text" class="form-control bg-light" id="poli" name="poli" value="{{ $pelayanan->poli->nama }}" readonly>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-four-radiologi-tab" data-toggle="pill" href="#custom-tabs-four-radiologi" role="tab" aria-controls="custom-tabs-four-radiologi" aria-selected="true">Radiologi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-laboratorium-tab" data-toggle="pill" href="#custom-tabs-four-laboratorium" role="tab" aria-controls="custom-tabs-four-laboratorium" aria-selected="false">Laboratorium</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-four-radiologi" role="tabpanel" aria-labelledby="custom-tabs-four-radiologi-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div style="border: 2px solid black; padding: 10px; width: 100%; max-width: 1000px; min-height: 285px;">
                                            <div class="table-responsive" style="max-height: 285px; overflow-y: auto;">
                                                <table class="table" id="dataTable" style="border: none;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10%">No</th>
                                                            <th style="width: 35%">Nama Pemeriksaan</th>
                                                            <th style="width: 35%">Posisi</th>
                                                            <th style="width: 20%">Metode</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- DATA TERISI OTOMATIS NANTI --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row align-items-center mt-3">
                                            <div class="col-md-2">
                                                <label for="pemeriksaan_radiologi" class="col-form-label">Pemeriksaan</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="form-control select2bs4" style="width: 100%;" id="pemeriksaan_radiologi" name="pemeriksaan_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    <option value="testing">testing</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-primary w-100">Tambah</button>
                                            </div>
                                        </div>
                                        <div class="form-group row align-items-center mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Posisi</label>
                                            </div>
                                            <div class="col-md-5">
                                                <select class="form-control select2bs4" style="width: 100%;" id="jenis_posisi_radiologi" name="jenis_posisi_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    <option value="testing">testing</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control select2bs4" style="width: 100%;" id="posisi_radiologi" name="posisi_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    <option value="testing">testing</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger w-100">Hapus</button>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Diagnosa Referensi</label>
                                            </div>
                                            <div class="col-md-10">
                                                <select class="form-control select2bs4" style="width: 100%;" id="diagnosa_radiologi" name="diagnosa_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    <option value="testing">testing</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">

                                            </div>
                                            <div class="col-md-10">
                                                <small class="form-text text-muted">
                                                    *) Hanya sebagai referensi, bukan diagnosa akhir dari pasien.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Tanggal Periksa</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="datetime-local" class="form-control" id="tanggal_periksa_radiologi" name="tanggal_periksa_radiologi">
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Catatan Dokter</label>
                                            </div>
                                            <div class="col-md-10">
                                                <textarea class="form-control" id="catatan_dokter_radiologi" name="catatan_dokter_radiologi" rows="3" placeholder="Masukkan catatan dokter..."></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-12 text-right">
                                                <button type="button" class="btn btn-success">
                                                    <i class="fas fa-print"></i> Print
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="custom-tabs-four-laboratorium" role="tabpanel" aria-labelledby="custom-tabs-four-laboratorium-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div style="border: 2px solid black; padding: 10px; width: 100%; max-width: 1000px; min-height: 285px;">
                                            <div class="table-responsive" style="max-height: 285px; overflow-y: auto;">
                                                <table class="table" id="dataTable" style="border: none;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10%">No</th>
                                                            <th style="width: 90%">Nama Pemeriksaan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- DATA TERISI OTOMATIS NANTI --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row align-items-center mt-3">
                                            <div class="col-md-2">
                                                <label for="bidang_laboratorium" class="col-form-label">Bidang</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2bs4" style="width: 100%;" id="bidang_laboratorium" name="bidang_laboratorium">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    <option value="testing">testing</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-primary w-100">Tambah</button>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger w-100">Hapus</button>
                                            </div>
                                        </div>
                                        <div class="form-group row align-items-center mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Pemeriksaan</label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2bs4" style="width: 100%;" id="pemeriksaan_laboratorium" name="pemeriksaan_laboratorium">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    <option value="testing">testing</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Diagnosa Referensi</label>
                                            </div>
                                            <div class="col-md-10">
                                                <select class="form-control select2bs4" style="width: 100%;" id="diagnosa_laboratorium" name="diagnosa_laboratorium">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    <option value="testing">testing</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">

                                            </div>
                                            <div class="col-md-10">
                                                <small class="form-text text-muted">
                                                    *) Hanya sebagai referensi, bukan diagnosa akhir dari pasien.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Tanggal Periksa</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="datetime-local" class="form-control" id="tanggal_periksa_laboratorium" name="tanggal_periksa_laboratorium">
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Catatan Dokter</label>
                                            </div>
                                            <div class="col-md-10">
                                                <textarea class="form-control" id="catatan_dokter_laboratorium" name="catatan_dokter_laboratorium" rows="3" placeholder="Masukkan catatan dokter..."></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-md-12 text-right">
                                                <button type="button" class="btn btn-success">
                                                    <i class="fas fa-print"></i> Print
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    const datetimeInputRadiologi = document.getElementById('tanggal_periksa_radiologi');
    const datetimeInputLaboratorium = document.getElementById('tanggal_periksa_laboratorium');

    datetimeInputRadiologi.addEventListener('click', function () {
        this.showPicker && this.showPicker(); // untuk browser yang support
    });
    datetimeInputLaboratorium.addEventListener('click', function () {
        this.showPicker && this.showPicker(); // untuk browser yang support
    });
</script>

@endsection
