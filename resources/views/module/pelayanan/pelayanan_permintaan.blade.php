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

                            <input type="hidden" id="alamat" name="alamat" value="{{ $pelayanan->pasien->alamat }}">
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
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-skd-tab" data-toggle="pill" href="#custom-tabs-four-skd" role="tab" aria-controls="custom-tabs-four-skd" aria-selected="false">Surat Keterangan Dokter</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-four-radiologi" role="tabpanel" aria-labelledby="custom-tabs-four-radiologi-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="rad_table_hidden" name="rad_table_hidden">
                                        <div style="border: 2px solid black; padding: 10px; width: 100%; max-width: auto; min-height: 255px;">
                                            <div class="table-responsive" style="max-height: 255px; overflow-y: auto;">
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
                                                <label for="pemeriksaan_radiologi" class="col-form-label">Pemeriksaan</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select class="form-control select2bs4" style="width: 100%;" id="pemeriksaan_radiologi" name="pemeriksaan_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    @foreach ($radiologi_pemeriksaan as $radiologi_pemeriksaan_item)
                                                        <option value="{{ $radiologi_pemeriksaan_item->nama }}">{{ $radiologi_pemeriksaan_item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-primary w-100" id="btn-tambah-rad">Tambah</button>
                                            </div>
                                        </div>
                                        <div class="form-group row align-items-center mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Posisi</label>
                                            </div>
                                            <div class="col-md-5">
                                                <select class="form-control select2bs4" style="width: 100%;" id="jenis_posisi_radiologi" name="jenis_posisi_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    @foreach ($radiologi_jenis as $radiologi_jenis_item)
                                                        <option value="{{ $radiologi_jenis_item->nama }}">{{ $radiologi_jenis_item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control select2bs4" style="width: 100%;" id="posisi_radiologi" name="posisi_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    <option value="R">R</option>
                                                    <option value="L">L</option>
                                                    <option value="Both">Both</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger w-100" id="btn-hapus-rad">Hapus</button>
                                            </div>
                                        </div>
                                        <div class="form-group row align-items-center mt-3">
                                            <div class="col-md-2">
                                                <label class="col-form-label">Metode</label>
                                            </div>
                                            <div class="col-md-5">
                                                <select class="form-control select2bs4" style="width: 100%;" id="metode_radiologi" name="metode_radiologi">
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
                                                <select class="form-control select2bs4" style="width: 100%;" id="diagnosa_radiologi" name="diagnosa_radiologi">
                                                    <option value="" disabled selected>-- Pilih --</option>
                                                    @foreach ($data_icd9 as $radiologi)
                                                        <option value="({{$radiologi->kode_icd9}}) {{$radiologi->nama_icd9}}">({{$radiologi->kode_icd9}}) {{$radiologi->nama_icd9}}</option>
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
                                                <label class="col-md-3 col-form-label">No Kartu</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control"
                                                        value="{{ $pelayanan->pasien->no_bpjs }}" readonly>
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
                                                <div class="col-md-3"></div>
                                                <div class="col-md-9">
                                                    <button type="button" class="btn btn-secondary mb-2" disabled>Cek
                                                        Jadwal HFIS</button>
                                                </div>
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

                                    <div class="tab-pane fade" id="custom-tabs-four-sakit" role="tabpanel"
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

                                            <div class="form-group row mt-3">
                                                <div class="col-md-4">
                                                    <label>Tensi (mmHg)</label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="number" class="form-control" name="sistole"
                                                                placeholder="Sistole"
                                                                value="{{ $pelayanan->vital_signs->sistol ?? '' }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="number" class="form-control" name="diastole"
                                                                placeholder="Diastole"
                                                                value="{{ $pelayanan->vital_signs->distol ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="suhu">Suhu (°C)</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control"
                                                            name="suhu" placeholder="Suhu"
                                                            value="{{ $pelayanan->vital_signs->suhu ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="berat">Berat (/Kg)</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.1" class="form-control"
                                                            name="berat" placeholder="Berat"
                                                            value="{{ $pelayanan->vital_signs->berat ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row mt-3">
                                                <div class="col-md-4">
                                                    <label for="rr">RR (/mnt)</label>
                                                    <input type="number" class="form-control" name="respiratory_rate"
                                                        placeholder="Respiratory Rate"
                                                        value="{{ $pelayanan->vital_signs->rr ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="nadi">Nadi (/mnt)</label>
                                                    <input type="number" class="form-control" name="nadi"
                                                        placeholder="Nadi"
                                                        value="{{ $pelayanan->vital_signs->nadi ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="tinggi">Tinggi (Cm)</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" name="tinggi"
                                                            placeholder="Tinggi"
                                                            value="{{ $pelayanan->vital_signs->tinggi ?? '' }}">
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

                                            <div class="form-group row mt-3">
                                                <label class="col-md-3 col-form-label">Dokter</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="dokter_kematian"
                                                        value="{{ auth()->user()->name ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="form-group row align-items-center">
                                                <label class="col-md-3 col-form-label">Penandatangan</label>
                                                <div class="col-md-9 d-flex align-items-center gap-2">
                                                    <input type="checkbox" class="form-check-input me-2"
                                                        id="penandatangan_check">
                                                    <select class="form-control flex-grow-1" id="penandatangan">
                                                        <option value="">-- Pilih Penandatangan --</option>
                                                        <option value="Dokter">Dokter</option>
                                                        <option value="Perawat">Perawat</option>
                                                        <option value="Keluarga">Keluarga</option>
                                                    </select>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tanggal_pemeriksaan_skd = document.getElementById('tanggal_pemeriksaan_skd');
            const tanggal_mulai_istirahat_skd = document.getElementById('tanggal_mulai_istirahat_skd');
            const tanggal_akhir_istirahat_skd = document.getElementById('tanggal_akhir_istirahat_skd');
            const tanggal_periksa_laboratorium = document.getElementById('tanggal_periksa_laboratorium');
            const tanggal_periksa_radiologi = document.getElementById('tanggal_periksa_radiologi');

            tanggal_pemeriksaan_skd.addEventListener('click', function() {
                tanggal_pemeriksaan_skd.showPicker?.() || tanggal_pemeriksaan_skd
                    .focus(); // Buka date picker jika didukung, atau fokus
            });

            tanggal_mulai_istirahat_skd.addEventListener('click', function() {
                tanggal_mulai_istirahat_skd.showPicker?.() || tanggal_mulai_istirahat_skd
                    .focus(); // Buka date picker jika didukung, atau fokus
            });

            tanggal_akhir_istirahat_skd.addEventListener('click', function() {
                tanggal_akhir_istirahat_skd.showPicker?.() || tanggal_akhir_istirahat_skd
                    .focus(); // Buka date picker jika didukung, atau fokus
            });
            tanggal_periksa_laboratorium.addEventListener('click', function() {
                tanggal_periksa_laboratorium.showPicker?.() || tanggal_periksa_laboratorium
                    .focus(); // Buka date picker jika didukung, atau fokus
            });

            tanggal_periksa_radiologi.addEventListener('click', function() {
                tanggal_periksa_radiologi.showPicker?.() || tanggal_periksa_radiologi
                    .focus(); // Buka date picker jika didukung, atau fokus
            });

            // Handle checkbox buta warna
            const butaWarnaCheck = document.getElementById('buta_warna_check');
            const butaWarnaStatus = document.querySelector('select[name="buta_warna_status"]');

            if (butaWarnaCheck && butaWarnaStatus) {
                butaWarnaCheck.addEventListener('change', function() {
                    if (this.checked) {
                        butaWarnaStatus.value = 'Ya';
                    } else {
                        butaWarnaStatus.value = 'Tidak';
                    }
                });
            }

            // Handle radio button penyebab kematian lainnya
            const radioLainnya = document.getElementById('radio_lainnya');
            const penyebabLainnya = document.getElementById('penyebab_lainnya');

            if (radioLainnya && penyebabLainnya) {
                radioLainnya.addEventListener('change', function() {
                    if (this.checked) {
                        penyebabLainnya.style.display = 'block';
                    } else {
                        penyebabLainnya.style.display = 'none';
                    }
                });
            }

            // Handle checkbox penandatangan
            const penandatanganCheck = document.getElementById('penandatangan_check');
            const penandatanganSelect = document.getElementById('penandatangan');

            if (penandatanganCheck && penandatanganSelect) {
                penandatanganCheck.addEventListener('change', function() {
                    if (this.checked) {
                        penandatanganSelect.disabled = false;
                    } else {
                        penandatanganSelect.disabled = true;
                        penandatanganSelect.value = '';
                    }
                });
            }
        });
    </script>

    <script>
        function getCurrentDateTimeLocal() {
            const now = new Date();

            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // bulan dimulai dari 0
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }


        $(document).ready(function() {
            const datetimeInputRadiologi = document.getElementById('tanggal_periksa_radiologi');
            const datetimeInputLaboratorium = document.getElementById('tanggal_periksa_laboratorium');
            const datetimeInputSkd = document.getElementById('tanggal_pemeriksaan_skd');

            // Isi otomatis dengan waktu sekarang
            const now = getCurrentDateTimeLocal();
            if (datetimeInputRadiologi) datetimeInputRadiologi.value = now;
            if (datetimeInputLaboratorium) datetimeInputLaboratorium.value = now;
            if (datetimeInputSkd) datetimeInputSkd.value = now;

            datetimeInputRadiologi.addEventListener('click', function() {
                this.showPicker && this.showPicker(); // untuk browser yang support
            });
            datetimeInputLaboratorium.addEventListener('click', function() {
                this.showPicker && this.showPicker(); // untuk browser yang support
            });
            datetimeInputSkd.addEventListener('click', function() {
                this.showPicker && this.showPicker(); // untuk browser yang support
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#bidang_laboratorium').on('change', function() {
                // Ambil id dari atribut data-id, bukan dari value
                let id = $(this).find(':selected').data('id');

                $('#pemeriksaan_laboratorium').empty().append(
                    '<option disabled selected>Loading...</option>');

                $.ajax({
                    url: `/api/get-pemeriksaan-laboratorium/${id}`,
                    type: 'GET',
                    success: function(data) {
                        $('#pemeriksaan_laboratorium').empty().append(
                            '<option value="" disabled selected>-- Pilih --</option>');
                        $.each(data, function(key, value) {
                            $('#pemeriksaan_laboratorium').append(
                                `<option value="${value.nama_sublaboratorium_bidang}">${value.nama_sublaboratorium_bidang}</option>`
                            );
                        });
                    }
                });
            });
        });
    </script>

    <script>
        let selectedRow = null;
        let labData = [];

        function refreshTable() {
            let tbody = $('#lab_table tbody');
            tbody.empty();

            labData.forEach((item, index) => {
                tbody.append(`
                <tr data-index="${index}" class="lab-row">
                    <td>${index + 1}</td>
                    <td>${item}</td>
                </tr>
            `);
            });

            // Update hidden input as JSON string
            $('#lab_table_hidden').val(JSON.stringify(labData));

            console.log('Data : ', JSON.stringify(labData));
        }

        // Tambah data (dengan cek duplikat)
        $('#btn-tambah-lab').on('click', function() {
            let selected = $('#pemeriksaan_laboratorium').val();

            if (!selected) {
                Swal.fire('Pilih Pemeriksaan terlebih dahulu.', '', 'warning');
                return;
            }

            // Cek duplikat
            if (labData.includes(selected)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Duplikat',
                    text: `"${selected}" sudah ada dalam tabel.`,
                    showConfirmButton: true
                });
                return;
            }

            labData.push(selected);
            refreshTable();
            $('#pemeriksaan_laboratorium').val(null).trigger('change');
        });

        // Pilih baris
        $(document).on('click', '.lab-row', function() {
            $('.lab-row').removeClass('table-primary');
            $(this).addClass('table-primary');
            selectedRow = $(this).data('index');
        });

        // Hapus data
        $('#btn-hapus-lab').on('click', function() {
            if (selectedRow === null) {
                Swal.fire('Pilih baris yang ingin dihapus.', '', 'info');
                return;
            }

            labData.splice(selectedRow, 1);
            selectedRow = null;
            refreshTable();
        });
    </script>

    <script>
        $('#btn-print-lab').on('click', function() {
            const labData = $('#lab_table_hidden').val();
            const diagnosa = $('#diagnosa_laboratorium').val();
            const tanggal = $('#tanggal_periksa_laboratorium').val();
            const catatan = $('#catatan_dokter_laboratorium').val();
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const poli = $('#poli').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const penjamin = $('#penjamin').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!labData || !diagnosa || !tanggal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan data pemeriksaan dan tanggal periksa sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak Permintaan Laboratorium?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form dinamis
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('laboratorium.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'lab_table_hidden',
                        value: labData
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosa_laboratorium',
                        value: diagnosa
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_periksa_laboratorium',
                        value: tanggal
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'catatan_dokter_laboratorium',
                        value: catatan
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'poli',
                        value: poli
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'penjamin',
                        value: penjamin
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    // Setelah submit, redirect ke route dokter
                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000); // delay 1 detik agar PDF sempat terbuka
                }
            });
        });
    </script>

    <script>
        let selectedRadRow = null;
        let radData = [];

        function refreshRadTable() {
            let tbody = $('#rad_table tbody');
            tbody.empty();

            radData.forEach((item, index) => {
                tbody.append(`
                <tr data-index="${index}" class="rad-row">
                    <td>${index + 1}</td>
                    <td>${item.pemeriksaan}</td>
                    <td>${item.jenis_posisi} - ${item.posisi}</td>
                    <td>${item.metode}</td>
                </tr>
            `);
            });

            $('#rad_table_hidden').val(JSON.stringify(radData));
            console.log('Data : ', JSON.stringify(radData));
        }

        $('#btn-tambah-rad').on('click', function() {
            const pemeriksaan = $('#pemeriksaan_radiologi').val();
            const jenisPosisi = $('#jenis_posisi_radiologi').val();
            const posisi = $('#posisi_radiologi').val();
            const metode = $('#metode_radiologi').val();

            if (!pemeriksaan || !jenisPosisi || !posisi || !metode) {
                Swal.fire('Lengkapi semua field hingga metode sebelum menambah data.', '', 'warning');
                return;
            }

            const newItem = {
                pemeriksaan,
                jenis_posisi: jenisPosisi,
                posisi,
                metode
            };

            const isDuplicate = radData.some(item =>
                item.pemeriksaan === newItem.pemeriksaan
            );

            if (isDuplicate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Duplikat',
                    text: `Data "${pemeriksaan}" sudah ditambahkan ke dalam tabel.`,
                });
                return;
            }

            radData.push(newItem);
            refreshRadTable();

            // Reset input
            $('#pemeriksaan_radiologi').val(null).trigger('change');
            $('#jenis_posisi_radiologi').val(null).trigger('change');
            $('#posisi_radiologi').val(null).trigger('change');
            $('#metode_radiologi').val(null).trigger('change');
        });

        $(document).on('click', '.rad-row', function() {
            $('.rad-row').removeClass('table-primary');
            $(this).addClass('table-primary');
            selectedRadRow = $(this).data('index');
        });

        $('#btn-hapus-rad').on('click', function() {
            if (selectedRadRow === null) {
                Swal.fire('Pilih baris yang ingin dihapus.', '', 'info');
                return;
            }

            radData.splice(selectedRadRow, 1);
            selectedRadRow = null;
            refreshRadTable();
        });
    </script>

    <script>
        $('#btn-print-rad').on('click', function() {
            const radData = $('#rad_table_hidden').val();
            const diagnosa = $('#diagnosa_radiologi').val();
            const tanggal = $('#tanggal_periksa_radiologi').val();
            const catatan = $('#catatan_dokter_radiologi').val();
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const poli = $('#poli').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const penjamin = $('#penjamin').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!radData || !diagnosa || !tanggal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan data pemeriksaan dan tanggal periksa sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak Permintaan Laboratorium?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form dinamis
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('radiologi.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'rad_table_hidden',
                        value: radData
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosa_radiologi',
                        value: diagnosa
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_periksa_radiologi',
                        value: tanggal
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'catatan_dokter_radiologi',
                        value: catatan
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'poli',
                        value: poli
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'penjamin',
                        value: penjamin
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    // Setelah submit, redirect ke route dokter
                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000); // delay 1 detik agar PDF sempat terbuka
                }
            });
        });
    </script>

<script>
    $('#btn-print-skd').on('click', function () {
        const tgl_pemeriksaan = $('#tanggal_pemeriksaan_skd').val();
        const kode_surat = $('#kode_surat_skd').val();
        const tgl_awal = $('#tanggal_mulai_istirahat_skd').val();
        const tgl_akhir = $('#tanggal_akhir_istirahat_skd').val();
        const diagnosa = $('#diagnosa_skd').val();
        const nama_pasien = $('#nama').val();
        const dokter_pengirim = $('#dokter_pengirim').val();
        const jenis_kelamin = $('#jenis_kelamin').val();
        const tanggal_lahir = $('#tanggal_lahir').val();
        const alamat = $('#alamat').val();
        const umur = $('#umur').val();
        const csrfToken = '{{ csrf_token() }}';

            if (!tgl_pemeriksaan || !tgl_awal || !tgl_akhir || !diagnosa || !kode_surat) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan data diagnosa dan tanggal periksa sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak Permintaan SKD?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form dinamis
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('skd.print') }}',
                        target: '_blank'
                    });

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: csrfToken
                }));
                form.append($('<input>', { type: 'hidden', name: 'tgl_pemeriksaan_skd', value: tgl_pemeriksaan }));
                form.append($('<input>', { type: 'hidden', name: 'kode_surat_skd', value: kode_surat }));
                form.append($('<input>', { type: 'hidden', name: 'tgl_awal_skd', value: tgl_awal }));
                form.append($('<input>', { type: 'hidden', name: 'tgl_akhir_skd', value: tgl_akhir }));
                form.append($('<input>', { type: 'hidden', name: 'diagnosa_skd', value: diagnosa }));
                form.append($('<input>', { type: 'hidden', name: 'nama_pasien', value: nama_pasien }));
                form.append($('<input>', { type: 'hidden', name: 'dokter_pengirim', value: dokter_pengirim }));
                form.append($('<input>', { type: 'hidden', name: 'jenis_kelamin', value: jenis_kelamin }));
                form.append($('<input>', { type: 'hidden', name: 'tanggal_lahir', value: tanggal_lahir }));
                form.append($('<input>', { type: 'hidden', name: 'alamat', value: alamat }));
                form.append($('<input>', { type: 'hidden', name: 'umur', value: umur }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    // Setelah submit, redirect ke route dokter
                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000); // delay 1 detik agar PDF sempat terbuka
                }
            });
        });

        // Script untuk Surat Sakit
        $('#btn-print-sakit').on('click', function() {
            const diagnosis_utama =
                '{{ $pelayanan->icd->kode_icd10 ?? '' }} - {{ $pelayanan->icd->nama_icd10 ?? '' }}';
            const diagnosis_penyerta_1 = $('input[name="diagnosis_penyerta_1"]').val();
            const diagnosis_penyerta_2 = $('input[name="diagnosis_penyerta_2"]').val();
            const diagnosis_penyerta_3 = $('input[name="diagnosis_penyerta_3"]').val();
            const komplikasi_1 = $('input[name="komplikasi_1"]').val();
            const komplikasi_2 = $('input[name="komplikasi_2"]').val();
            const komplikasi_3 = $('input[name="komplikasi_3"]').val();
            const lama_istirahat = $('input[name="lama_istirahat"]').val();
            const terhitung_mulai = $('input[name="terhitung_mulai"]').val();
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const umur = $('#umur').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!lama_istirahat || !terhitung_mulai) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan lama istirahat dan terhitung mulai sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak Surat Sakit?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('surat.sakit.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosis_utama',
                        value: diagnosis_utama
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosis_penyerta_1',
                        value: diagnosis_penyerta_1
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosis_penyerta_2',
                        value: diagnosis_penyerta_2
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosis_penyerta_3',
                        value: diagnosis_penyerta_3
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'komplikasi_1',
                        value: komplikasi_1
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'komplikasi_2',
                        value: komplikasi_2
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'komplikasi_3',
                        value: komplikasi_3
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'lama_istirahat',
                        value: lama_istirahat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'terhitung_mulai',
                        value: terhitung_mulai
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'umur',
                        value: umur
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000);
                }
            });
        });

        // Script untuk Surat Sehat
        $('#btn-print-sehat').on('click', function() {
            const tgl_periksa = $('input[name="tgl_periksa_sehat"]').val();
            const sistole = $('input[name="sistole"]').val();
            const diastole = $('input[name="diastole"]').val();
            const suhu = $('input[name="suhu"]').val();
            const berat = $('input[name="berat"]').val();
            const respiratory_rate = $('input[name="respiratory_rate"]').val();
            const nadi = $('input[name="nadi"]').val();
            const tinggi = $('input[name="tinggi"]').val();
            const buta_warna_status = $('select[name="buta_warna_status"]').val() || 'Tidak';
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const umur = $('#umur').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!tgl_periksa) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan tanggal periksa sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak Surat Sehat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('surat.sehat.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tgl_periksa_sehat',
                        value: tgl_periksa
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'sistole',
                        value: sistole
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diastole',
                        value: diastole
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'suhu',
                        value: suhu
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'berat',
                        value: berat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'respiratory_rate',
                        value: respiratory_rate
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nadi',
                        value: nadi
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tinggi',
                        value: tinggi
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'buta_warna_status',
                        value: buta_warna_status
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'umur',
                        value: umur
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000);
                }
            });
        });

        // Script untuk Surat Kematian
        $('#btn-print-kematian').on('click', function() {
            const tgl_periksa = $('input[name="tgl_periksa_kematian"]').val();
            const dokter_kematian = $('input[name="dokter_kematian"]').val();
            const penandatangan = $('#penandatangan').val() || '';
            const tanggal_meninggal = $('input[name="tanggal_meninggal"]').val();
            const jam_meninggal = $('input[name="jam_meninggal"]').val();
            const ref_tgl_jam = $('input[placeholder="Contoh: UGD, Poli Umum, Ranap"]').val() || '';
            const penyebab_kematian = $('input[name="penyebab_kematian"]:checked').val() || 'Sakit';
            const penyebab_lainnya = $('input[name="penyebab_lainnya"]').val();
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const umur = $('#umur').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!tgl_periksa || !tanggal_meninggal || !jam_meninggal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan tanggal periksa, tanggal meninggal, dan jam meninggal sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak Surat Kematian?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('surat.kematian.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tgl_periksa_kematian',
                        value: tgl_periksa
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_kematian',
                        value: dokter_kematian
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'penandatangan',
                        value: penandatangan
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_meninggal',
                        value: tanggal_meninggal
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jam_meninggal',
                        value: jam_meninggal
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'ref_tgl_jam',
                        value: ref_tgl_jam
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'penyebab_kematian',
                        value: penyebab_kematian
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'penyebab_lainnya',
                        value: penyebab_lainnya
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'umur',
                        value: umur
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000);
                }
            });
        });

        // Script untuk SKDP
        $('#btn-print-skdp').on('click', function() {
            const tanggal_pemeriksaan = $('#tanggal_pemeriksaan_skd').val();
            const kode_surat = $('#kode_surat_skd').val();
            const jenis_skdp = $('input[name="jenis_skdp"]:checked').val() || 'BPJS';
            const sep_bpjs = $('input[name="sep_bpjs"]').val();
            const no_kartu = '{{ $pelayanan->pasien->no_bpjs ?? '' }}';
            const untuk_skdp = $('select[name="untuk_skdp"]').val();
            const pada_skdp = $('input[name="pada_skdp"]').val();
            const poli_unit_skdp = $('select[name="poli_unit_skdp"]').val();
            const alasan1_skdp = $('input[name="alasan1_skdp"]').val();
            const alasan2_skdp = $('input[name="alasan2_skdp"]').val();
            const rencana1_skdp = $('input[name="rencana1_skdp"]').val();
            const rencana2_skdp = $('input[name="rencana2_skdp"]').val();
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const umur = $('#umur').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!tanggal_pemeriksaan || !kode_surat || !untuk_skdp || !pada_skdp) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan tanggal pemeriksaan, kode surat, untuk, dan pada sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak SKDP?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('skdp.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_pemeriksaan_skd',
                        value: tanggal_pemeriksaan
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'kode_surat_skd',
                        value: kode_surat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_skdp',
                        value: jenis_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'sep_bpjs',
                        value: sep_bpjs
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'no_kartu',
                        value: no_kartu
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'untuk_skdp',
                        value: untuk_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'pada_skdp',
                        value: pada_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'poli_unit_skdp',
                        value: poli_unit_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alasan1_skdp',
                        value: alasan1_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alasan2_skdp',
                        value: alasan2_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'rencana1_skdp',
                        value: rencana1_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'rencana2_skdp',
                        value: rencana2_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'umur',
                        value: umur
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000);
                }
            });
        });
    </script>
@endsection
