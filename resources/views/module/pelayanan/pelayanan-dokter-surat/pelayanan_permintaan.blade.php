@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Permintaan Pasien</h1>
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
                                    <img src="{{ asset('profile/default.png') }}" alt="Klinik Logo"
                                        class="img-circle elevation-2" style="width: 100px; height: 100px; opacity: .8">
                                </div>

                                <div class="form-group">
                                    <label for="nomor_rm">No. RM</label>
                                    <input type="text" class="form-control bg-light" id="nomor_rm"
                                        value="{{ $pelayanan->nomor_rm }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="nama">Nama Pasien</label>
                                    <input type="text" class="form-control bg-light" id="nama" name="nama"
                                        value="{{ $pelayanan->pasien->nama }}" readonly>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jenis_kelamin">Jenis Kelamin</label>
                                            <input type="text" class="form-control bg-light" id="jenis_kelamin"
                                                name="jenis_kelamin" value="{{ $pelayanan->pasien->kelamin->nama }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="penjamin">Penjamin</label>
                                            <input type="text" class="form-control bg-light" id="penjamin"
                                                name="penjamin" value="{{ $pelayanan->pendaftaran->penjamin->nama }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_lahir">Tanggal Lahir</label>
                                            <input type="text" class="form-control bg-light" id="tanggal_lahir"
                                                name="tanggal_lahir" value="{{ $pelayanan->pasien->tanggal_lahir }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="umur">Umur</label>
                                            <input type="text" class="form-control bg-light" id="umur"
                                                value="{{ $umur }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="dokter_pengirim">Dokter Pengirim</label>
                                    <input type="text" class="form-control bg-light" id="dokter_pengirim"
                                        name="dokter_pengirim" value="{{ $pelayanan->pendaftaran->dokter->namauser->name }}"
                                        readonly>
                                </div>

                                <div class="form-group">
                                    <label for="poli">Poli</label>
                                    <input type="text" class="form-control bg-light" id="poli" name="poli"
                                        value="{{ $pelayanan->poli->nama }}" readonly>
                                </div>

                                <input type="hidden" id="alamat" name="alamat"
                                    value="{{ $pelayanan->pasien->alamat }}">
                                <input type="hidden" id="no_bpjs" name="no_bpjs"
                                    value="{{ $pelayanan->pasien->no_bpjs ?? '-' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-four-sakit-tab" data-toggle="pill"
                                            href="#custom-tabs-four-sakit" role="tab"
                                            aria-controls="custom-tabs-four-sakit" aria-selected="false">Surat Sakit</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-four-sehat-tab" data-toggle="pill"
                                            href="#custom-tabs-four-sehat" role="tab"
                                            aria-controls="custom-tabs-four-sehat" aria-selected="false">Surat Sehat</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-four-kematian-tab" data-toggle="pill"
                                            href="#custom-tabs-four-kematian" role="tab"
                                            aria-controls="custom-tabs-four-kematian" aria-selected="false">Surat
                                            Kematian</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-four-skd-tab" data-toggle="pill"
                                            href="#custom-tabs-four-skd" role="tab"
                                            aria-controls="custom-tabs-four-skd" aria-selected="false">Surat Keterangan
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " id="custom-tabs-four-radiologi-tab" data-toggle="pill"
                                            href="#custom-tabs-four-radiologi" role="tab"
                                            aria-controls="custom-tabs-four-radiologi" aria-selected="true">Radiologi</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-four-laboratorium-tab" data-toggle="pill"
                                            href="#custom-tabs-four-laboratorium" role="tab"
                                            aria-controls="custom-tabs-four-laboratorium"
                                            aria-selected="false">Laboratorium</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">

                                    <div class="tab-pane fade" id="custom-tabs-four-radiologi"
                                        role="tabpanel" aria-labelledby="custom-tabs-four-radiologi-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" id="rad_table_hidden" name="rad_table_hidden">
                                                <div
                                                    style="border: 2px solid black; padding: 10px; width: 100%; max-width: auto; min-height: 255px;">
                                                    <div class="table-responsive"
                                                        style="max-height: 255px; overflow-y: auto;">
                                                        <table class="table" id="rad_table" style="border: none;">
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
                                                        <label for="pemeriksaan_radiologi"
                                                            class="col-form-label">Pemeriksaan</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <select class="form-control select2bs4" style="width: 100%;"
                                                            id="pemeriksaan_radiologi" name="pemeriksaan_radiologi">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            @foreach ($radiologi_pemeriksaan as $radiologi_pemeriksaan_item)
                                                                <option value="{{ $radiologi_pemeriksaan_item->nama }}">
                                                                    {{ $radiologi_pemeriksaan_item->nama }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-primary w-100"
                                                            id="btn-tambah-rad">Tambah</button>
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center mt-3">
                                                    <div class="col-md-2">
                                                        <label class="col-form-label">Posisi</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <select class="form-control select2bs4" style="width: 100%;"
                                                            id="jenis_posisi_radiologi" name="jenis_posisi_radiologi">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            @foreach ($radiologi_jenis as $radiologi_jenis_item)
                                                                <option value="{{ $radiologi_jenis_item->nama }}">
                                                                    {{ $radiologi_jenis_item->nama }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select class="form-control select2bs4" style="width: 100%;"
                                                            id="posisi_radiologi" name="posisi_radiologi">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            <option value="R">R</option>
                                                            <option value="L">L</option>
                                                            <option value="Both">Both</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger w-100"
                                                            id="btn-hapus-rad">Hapus</button>
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center mt-3">
                                                    <div class="col-md-2">
                                                        <label class="col-form-label">Metode</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <select class="form-control select2bs4" style="width: 100%;"
                                                            id="metode_radiologi" name="metode_radiologi">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            <option value="Rutin">Rutin</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-3">
                                                    <div class="col-md-2">
                                                        <label class="col-form-label">Diagnosa Referensi</label>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <select class="form-control select2bs4" style="width: 100%;"
                                                            id="diagnosa_radiologi" name="diagnosa_radiologi">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            @foreach ($data_icd9 as $radiologi)
                                                                <option
                                                                    value="({{ $radiologi->kode_icd9 }}) {{ $radiologi->nama_icd9 }}">
                                                                    ({{ $radiologi->kode_icd9 }})
                                                                    {{ $radiologi->nama_icd9 }}</option>
                                                            @endforeach
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
                                                        <label for="tanggal_periksa_radiologi">Tanggal Periksa</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="datetime-local" class="form-control"
                                                            id="tanggal_periksa_radiologi"
                                                            name="tanggal_periksa_radiologi">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-3">
                                                    <div class="col-md-2">
                                                        <label class="col-form-label">Catatan Dokter</label>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <textarea class="form-control" id="catatan_dokter_radiologi" name="catatan_dokter_radiologi" rows="2"
                                                            placeholder="Masukkan catatan dokter..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-3">
                                                    <div class="col-md-12 text-right">
                                                        <button type="button" class="btn btn-success"
                                                            id="btn-print-rad">
                                                            <i class="fas fa-print"></i> Print
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="custom-tabs-four-laboratorium" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-laboratorium-tab">
                                        <div class="row">
                                            <input type="hidden" id="lab_table_hidden" name="lab_table_hidden">
                                            <div class="col-md-12">
                                                <div
                                                    style="border: 2px solid black; padding: 10px; width: 100%; max-width: auto; min-height: 285px;">
                                                    <div class="table-responsive"
                                                        style="max-height: 285px; overflow-y: auto;">
                                                        <table class="table" id="lab_table" style="border: none;">
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
                                                        <label for="bidang_laboratorium"
                                                            class="col-form-label">Bidang</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <select class="form-control select2bs4" style="width: 100%;"
                                                            id="bidang_laboratorium" name="bidang_laboratorium">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            <option value="Seluruh Data" data-id="all">Seluruh Data
                                                            </option>
                                                            @foreach ($data_lab as $data_lab_item)
                                                                <option value="{{ $data_lab_item->nama }}"
                                                                    data-id="{{ $data_lab_item->id }}">
                                                                    {{ $data_lab_item->nama }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-primary w-100"
                                                            id="btn-tambah-lab">Tambah</button>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger w-100"
                                                            id="btn-hapus-lab">Hapus</button>
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center mt-3">
                                                    <div class="col-md-2">
                                                        <label class="col-form-label">Pemeriksaan</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <select class="form-control select2bs4" style="width: 100%;"
                                                            id="pemeriksaan_laboratorium" name="pemeriksaan_laboratorium">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-3">
                                                    <div class="col-md-2">
                                                        <label class="col-form-label">Diagnosa Referensi</label>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <select class="form-control select2bs4" style="width: 100%;"
                                                            id="diagnosa_laboratorium" name="diagnosa_laboratorium">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            @foreach ($data_icd9 as $lab)
                                                                <option
                                                                    value="({{ $lab->kode_icd9 }}) {{ $lab->nama_icd9 }}">
                                                                    ({{ $lab->kode_icd9 }})
                                                                    {{ $lab->nama_icd9 }}</option>
                                                            @endforeach
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
                                                        <label for="tanggal_periksa_laboratorium">Tanggal Periksa</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="datetime-local" class="form-control"
                                                            id="tanggal_periksa_laboratorium"
                                                            name="tanggal_periksa_laboratorium">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-3">
                                                    <div class="col-md-2">
                                                        <label class="col-form-label">Catatan Dokter</label>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <textarea class="form-control" id="catatan_dokter_laboratorium" name="catatan_dokter_laboratorium" rows="3"
                                                            placeholder="Masukkan catatan dokter..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-3">
                                                    <div class="col-md-12 text-right">
                                                        <button type="button" class="btn btn-success"
                                                            id="btn-print-lab">
                                                            <i class="fas fa-print"></i> Print
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="custom-tabs-four-skd" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-skd-tab">
                                        <form>
                                            <div class="form-group row mt-3 align-items-center">
                                                <div class="col-md-4">
                                                    <label for="tanggal_pemeriksaan_skd">Tgl Pemeriksaan</label>
                                                    <input type="datetime-local" class="form-control"
                                                        id="tanggal_pemeriksaan_skd" name="tanggal_pemeriksaan_skd">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="kode_surat_skd">Kode Surat</label>
                                                    <input type="text" class="form-control" id="kode_surat_skd"
                                                        name="kode_surat_skd" readonly value="{{ $kodeSurat }}">
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Jenis</label>
                                                <div class="col-md-9">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="jenis_skdp"
                                                            id="jenis_bpjs" value="BPJS" checked>
                                                        <label class="form-check-label" for="jenis_bpjs">BPJS</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="jenis_skdp"
                                                            id="jenis_nonbpjs" value="Non BPJS">
                                                        <label class="form-check-label" for="jenis_nonbpjs">Non
                                                            BPJS</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Nomor Kunjungan</label>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control"
                                                        name="nomor_kunjungan_skdp"
                                                        value="{{ $pelayanan->pendaftaran->no_kunjungan }}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Untuk</label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="untuk_skdp">
                                                        <option value="KONTROL PASIEN">KONTROL PASIEN</option>
                                                        <option value="RUJUKAN">RUJUKAN</option>
                                                        <option value="LAINNYA">LAINNYA</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Pada</label>
                                                <div class="col-md-9">
                                                    <input type="date" class="form-control" name="pada_skdp">
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Poli / Unit</label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="poli_unit_skdp">
                                                        <option value="POLI ANAK">POLI ANAK</option>
                                                        <option value="POLI UMUM">POLI UMUM</option>
                                                        <option value="POLI GIGI">POLI GIGI</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Belum dapat dikembalikan ke FKTP
                                                    dengan alasan</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control mb-2" name="alasan1_skdp"
                                                        placeholder="(1) Kontrol">
                                                    <input type="text" class="form-control" name="alasan2_skdp"
                                                        placeholder="(2)">
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Rencana tindak lanjut pada kunjungan
                                                    selanjutnya</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control mb-2" name="rencana1_skdp"
                                                        placeholder="(1) Pemeriksaan Lanjutan/Terapi">
                                                    <input type="text" class="form-control" name="rencana2_skdp"
                                                        placeholder="(2)">
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <div class="col-md-12 text-right">
                                                    <button type="button" class="btn btn-success" id="btn-print-skdp">
                                                        <i class="fas fa-print"></i> Print
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade show active" id="custom-tabs-four-sakit" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-sakit-tab">
                                        <form>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Diagnosis Utama</label>
                                                <div class="col-md-9">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <input type="text" class="form-control"
                                                                value="{{ $pelayanan->icd->kode_icd10 ?? '' }}" readonly>
                                                        </div>
                                                        <div class="col-md-8 mb-2">
                                                            <input type="text" class="form-control"
                                                                value="{{ $pelayanan->icd->nama_icd10 ?? '' }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Diagnosis Penyerta</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control mb-2"
                                                        name="diagnosis_penyerta_1">
                                                    <input type="text" class="form-control mb-2"
                                                        name="diagnosis_penyerta_2">
                                                    <input type="text" class="form-control"
                                                        name="diagnosis_penyerta_3">
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Komplikasi</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control mb-2" name="komplikasi_1">
                                                    <input type="text" class="form-control mb-2" name="komplikasi_2">
                                                    <input type="text" class="form-control" name="komplikasi_3">
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Lama Istirahat</label>
                                                <div class="col-md-3">
                                                    <input type="number" class="form-control" name="lama_istirahat">
                                                </div>
                                                <label class="col-form-label">Hari</label>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Terhitung Mulai</label>
                                                <div class="col-md-9">
                                                    <input type="date" class="form-control" name="terhitung_mulai">
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <div class="col-md-12 text-right">
                                                    <button type="button" class="btn btn-success" id="btn-print-sakit">
                                                        <i class="fas fa-print"></i> Print
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="custom-tabs-four-sehat" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-sehat-tab">
                                        <form>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Tgl Periksa</label>
                                                <div class="col-md-9">
                                                    <input type="date" class="form-control" name="tgl_periksa_sehat"
                                                        value="{{ date('Y-m-d') }}">
                                                </div>
                                            </div>

                                            @php
                                                $firstSoap = $pelayanan->pelayanan_so->first();
                                            @endphp
                                            <div class="form-group row mt-3">
                                                <div class="col-md-4">
                                                    <label>Tensi (mmHg)</label>
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <input type="number" class="form-control" name="sistole"
                                                                placeholder="Sistole"
                                                                value="{{ $firstSoap->sistol ?? '' }}">
                                                        </div>
                                                        <div
                                                            class="col-md-1 d-flex justify-content-center align-items-center">
                                                            <span>/</span> <!-- Menambahkan pemisah / -->
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="number" class="form-control" name="diastole"
                                                                placeholder="Diastole"
                                                                value="{{ $firstSoap->distol ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="suhu">Suhu (°C)</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control"
                                                            name="suhu" placeholder="Suhu"
                                                            value="{{ $firstSoap->suhu ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="berat">Berat (/Kg)</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control"
                                                            name="berat" placeholder="Berat"
                                                            value="{{ $firstSoap->berat ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row mt-3">
                                                <div class="col-md-4">
                                                    <label for="rr">RR (/mnt)</label>
                                                    <input type="number" class="form-control" name="respiratory_rate"
                                                        placeholder="Respiratory Rate"
                                                        value="{{ $firstSoap->rr ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="nadi">Nadi (/mnt)</label>
                                                    <input type="number" class="form-control" name="nadi"
                                                        placeholder="Nadi" value="{{ $firstSoap->nadi ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="tinggi">Tinggi (Cm)</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" name="tinggi"
                                                            placeholder="Tinggi" value="{{ $firstSoap->tinggi ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row mt-3 align-items-center">
                                                <label class="col-md-2 col-form-label"><strong>Lain-lain :</strong></label>

                                                <label class="col-md-2 col-form-label mb-0 d-flex align-items-center">
                                                    Buta Warna :
                                                    <input class="form-check-input ms-2" type="checkbox"
                                                        name="buta_warna_check" id="buta_warna_check">
                                                </label>

                                                <div class="col-md-4">
                                                    <select class="form-control" name="buta_warna_status">
                                                        <option value="Tidak">Tidak</option>
                                                        <option value="Ya">Ya</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group row mt-3">
                                                <div class="col-md-12 text-right">
                                                    <button type="button" class="btn btn-success" id="btn-print-sehat">
                                                        <i class="fas fa-print"></i> Print
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="custom-tabs-four-kematian" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-kematian-tab">
                                        <form>
                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Tgl Periksa</label>
                                                <div class="col-md-9">
                                                    <input type="date" class="form-control"
                                                        name="tgl_periksa_kematian">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Tanggal / Jam Meninggal</label>
                                                <div class="col-md-4">
                                                    <input type="date" class="form-control" name="tanggal_meninggal"
                                                        value="{{ date('Y-m-d') }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="time" class="form-control" name="jam_meninggal"
                                                        value="00:00">
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">*) Ref tgl/jam meninggal<br>di UGD/
                                                    Poli/ Ranap</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control"
                                                        placeholder="Contoh: UGD, Poli Umum, Ranap">
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Penyebab Kematian</label>
                                                <div class="col-md-9">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio"
                                                            name="penyebab_kematian" value="Sakit" checked>
                                                        <label class="form-check-label">Sakit</label>
                                                    </div>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio"
                                                            name="penyebab_kematian" value="Lainnya" id="radio_lainnya">
                                                        <label class="form-check-label"
                                                            for="radio_lainnya">Lainnya</label>
                                                    </div>
                                                    <input type="text" class="form-control mb-2"
                                                        name="penyebab_lainnya" id="penyebab_lainnya"
                                                        placeholder="Sebutkan penyebab lainnya" style="display:block;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="penyebab_kematian" value="DOA">
                                                        <label class="form-check-label">DOA (Death on Arrival)</label>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group row mt-3">
                                                <div class="col-md-12 text-right">
                                                    <button type="button" class="btn btn-success"
                                                        id="btn-print-kematian">
                                                        <i class="fas fa-print"></i> Print
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    @include('module.pelayanan.pelayanan-dokter-surat.components.javascript')
@endsection
