@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">SOAP Dokter</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">

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
                    <div class="col-12">
                        <div class="card">
                            <form id="addFormsoap" action="{{ route('pelayana_dokter.add') }}" method="POST">
                                @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="nomor_rm">Nomor RM</label>
                                                <input type="text" class="form-control" id="nomor_rm" name="nomor_rm" value="{{$pelayanan->nomor_rm}}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="nama">Nama</label>
                                                <input type="text" class="form-control" id="nama" name="nama" value="{{$pelayanan->pasien->nama}}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="no_rawat">Nomor Rawat</label>
                                                <input type="text" class="form-control" id="no_rawat" name="no_rawat" value="{{$pelayanan->nomor_register}}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="sex">Jenis Kelamin</label>
                                                <input type="text" class="form-control" id="sex" name="sex" value="{{$pelayanan->pasien->kelamin->nama}}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="penjamin">Penjamin</label>
                                                <input type="text" class="form-control" id="penjamin" name="penjamin" value="{{$pelayanan->pendaftaran->penjamin->nama}}" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                                <input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{$pelayanan->pasien->tanggal_lahir}}" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="umur">Umur</label>
                                                <input type="text" class="form-control" id="umur" name="umur" value="{{$umur}}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="card card-primary card-outline card-outline-tabs">
                                            <div class="card-header p-0 border-bottom-0">
                                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="custom-tabs-four-subyektif-tab" data-toggle="pill" href="#custom-tabs-four-subyektif" role="tab" aria-controls="custom-tabs-four-subyektif" aria-selected="true">Subyektif</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-objectiv-tab" data-toggle="pill" href="#custom-tabs-four-objectiv" role="tab" aria-controls="custom-tabs-four-objectiv" aria-selected="false">Objektif</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-headtotoe-tab" data-toggle="pill" href="#custom-tabs-four-headtotoe" role="tab" aria-controls="custom-tabs-four-headtotoe" aria-selected="false">Head To Toe</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-assesmen-tab" data-toggle="pill" href="#custom-tabs-four-assesmen" role="tab" aria-controls="custom-tabs-four-assesmen" aria-selected="false">Assesmen</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-diag-tab" data-toggle="pill" href="#custom-tabs-four-diag" role="tab" aria-controls="custom-tabs-four-diag" aria-selected="false">Diagnosis</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-plan-tab" data-toggle="pill" href="#custom-tabs-four-plan" role="tab" aria-controls="custom-tabs-four-plan" aria-selected="false">Plan</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-diet-tab" data-toggle="pill" href="#custom-tabs-four-diet" role="tab" aria-controls="custom-tabs-four-diet" aria-selected="false">Diet</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-tindakan-tab" data-toggle="pill" href="#custom-tabs-four-tindakan" role="tab" aria-controls="custom-tabs-four-tindakan" aria-selected="false">Tindakan</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-obat-tab" data-toggle="pill" href="#custom-tabs-four-obat" role="tab" aria-controls="custom-tabs-four-obat" aria-selected="false">Obat</a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-odo-tab" data-toggle="pill" href="#custom-tabs-four-odo" role="tab" aria-controls="custom-tabs-four-odo" aria-selected="false">Odontogram </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                                    <div class="tab-pane fade show active" id="custom-tabs-four-subyektif" role="tabpanel" aria-labelledby="custom-tabs-four-subyektif-tab">
                                                        <div class="form-group">
                                                            <label>Keluhan :</label>
                                                            <div class="row align-items-stretch">
                                                                <div class="col-md-6">
                                                                    <textarea class="form-control" id="penyakit" placeholder="Masukan Keluhan"></textarea>
                                                                </div>
                                                                <div class="col-md-5 d-flex align-items-center">
                                                                    <label class="mr-4 mb-0">Sejak</label>
                                                                    <input type="number" class="form-control mr-2" id="durasi" placeholder="Masukkan durasi">
                                                                    <select class="form-control select2bs4" id="waktu" name="waktu">
                                                                        <option value="" disabled selected>-- Pilih waktu --</option>
                                                                        <option value="Menit">Menit</option>
                                                                        <option value="Jam">Jam</option>
                                                                        <option value="Hari">Hari</option>
                                                                        <option value="Minggu">Minggu</option>
                                                                        <option value="Bulan">Bulan</option>
                                                                        <option value="Tahun">Tahun</option>
                                                                    </select>
                                                                    <div class="col-md-2">
                                                                        <button type="button" class="btn btn-primary" onclick="addData()">Tambahkan</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <input type="hidden" id="tableData" name="tableData" value="[]">

                                                        <!-- Tabel -->
                                                        <div class="form-group row">
                                                            <div class="col-md-12">
                                                                <table class="table table-bordered" id="SubTabel">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 5%; text-align: center;">No</th>
                                                                            <th style="width: 70%">Subyektif</th>
                                                                            <th style="width: 25%; text-align: center;">Aksi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="custom-tabs-four-objectiv" role="tabpanel" aria-labelledby="custom-tabs-four-objectiv-tab">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <label>Tensi (mmHg)</label>
                                                                <div class="row">
                                                                    <div class="col-md-5">
                                                                        <input type="text" class="form-control" id="sistol" name="sistol">
                                                                    </div>
                                                                    <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                                        <span>/</span> <!-- Menambahkan pemisah / -->
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <input type="text" class="form-control" id="distol" name="distol" onchange="updateTensi()">
                                                                    </div>
                                                                    <input type="hidden" id="tensi" name="tensi">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="suhu">Suhu (°C)</label>
                                                                <input type="text" class="form-control" id="suhu" name="suhu" onchange="validateSuhu(this)">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="nadi">Nadi (/mnt)</label>
                                                                <input type="text" class="form-control" id="nadi" name="nadi" onchange="validateNadi()">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="rr">RR (/mnt)</label>
                                                                <input type="text" class="form-control" id="rr" name="rr" onchange="validateRR(this)">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="tinggi">Tinggi (Cm)</label>
                                                                <input type="text" class="form-control" id="tinggi" name="tinggi" onchange="validateTB()">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="berat">Berat (/Kg)</label>
                                                                <input type="text" class="form-control" id="berat" name="berat" onchange="validateTB()">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-3">
                                                                <label for="spo2">SpO2</label>
                                                                <input type="text" class="form-control" id="spo2" name="spo2" onchange="validateSpO2(this)">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Alergi dan jenis</label>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <select class="form-control select2bs4" id="jenis_alergi" name="jenis_alergi">
                                                                            <option value="" disabled selected>-- Pilih --</option>
                                                                            <option value="00">tidak ada</option>
                                                                            <option value="01">makanan</option>
                                                                            <option value="02">obat</option>
                                                                            <option value="03">udara</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <select class="form-control select2bs4" id="alergi" name="alergi">
                                                                            <option value="" disabled selected>-- Pilih --</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="lingkar_perut">Lingkar Perut</label>
                                                                <input type="text" class="form-control" id="lingkar_perut" name="lingkar_perut">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Data BMI</label>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <input type="text" class="form-control" id="nilai_bmi" name="nilai_bmi" readonly>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <input type="text" class="form-control" id="status_bmi" name="status_bmi" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-3">
                                                                <label for="eye">EYE</label>
                                                                <select class="form-control select2bs4" style="width: 100%;" id="eye" name="eye">
                                                                    <option value="" disabled selected>-- Pilih --</option>
                                                                    @foreach ($gsc_eye as $gsc_eyedata)
                                                                        <option value="{{$gsc_eyedata->skor}}">{{$gsc_eyedata->nama}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="verbal">VERBAL</label>
                                                                <select class="form-control select2bs4" style="width: 100%;" id="verbal" name="verbal">
                                                                    <option value="" disabled selected>-- Pilih --</option>
                                                                    @foreach ($gcs_verbal as $gcs_verbaldata)
                                                                        <option value="{{$gcs_verbaldata->skor}}">{{$gcs_verbaldata->nama}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="motorik">MOTORIK</label>
                                                                <select class="form-control select2bs4" style="width: 100%;" id="motorik" name="motorik">
                                                                    <option value="" disabled selected>-- Pilih --</option>
                                                                    @foreach ($gcs_motorik as $gcs_motorikdata)
                                                                        <option value="{{$gcs_motorikdata->skor}}">{{$gcs_motorikdata->nama}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="sadar">Kesadaran</label>
                                                                <select class="form-control" style="width: 100%;" id="sadar" name="sadar" disabled>
                                                                    <option value="" disabled selected> </option>
                                                                    @foreach ($gcs_kesadaran as $gcs_kesadarandata)
                                                                        <option value="{{ $gcs_kesadarandata->skor }}">{{ $gcs_kesadarandata->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="custom-tabs-four-headtotoe" role="tabpanel" aria-labelledby="custom-tabs-four-headtotoe-tab">
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <select class="form-control select2bs4" style="width: 100%;" id="htt_pemeriksaan" name="htt_pemeriksaan">
                                                                        <option value="-" disabled selected> -- Silahkan Pilih -- </option>
                                                                        @foreach ($htt_pemeriksaan as $htt_pemeriksaandata)
                                                                            <option value="{{ $htt_pemeriksaandata->id }}">
                                                                                {{ $htt_pemeriksaandata->nama_pemeriksaan }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-3 d-flex align-items-center">
                                                                    <label class="mb-0 text-center mr-3 ">Di</label>
                                                                    <select id="sub-pemeriksaan-select"  class="form-control select2bs4" style="width: 100%;">
                                                                        <option value="">-- Pilih Sub Pemeriksaan --</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3 d-flex align-items-center">
                                                                    <label class="mb-0 text-center mr-3 ">Pada</label>
                                                                    <input type="text" class="form-control" id="htt_pemeriksaan_detail" name="htt_pemeriksaan_detail"disabled>
                                                                </div>
                                                                <div class="col-md-2 d-flex justify-content-end">
                                                                    <button type="button" class="btn btn-primary" onclick="addDataHtt_Text()">Tambahkan</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-12">
                                                                <textarea class="form-control" id="summernote" name="summernote" rows="5"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="custom-tabs-four-assesmen" role="tabpanel" aria-labelledby="custom-tabs-four-assesmen-tab">
                                                        <div class="form-group row">
                                                            <div class="col-md-12">
                                                                <label for="summernote2">Assesmen</label>
                                                                <textarea class="form-control" id="summernote2" name="summernote2" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="custom-tabs-four-diag" role="tabpanel" aria-labelledby="custom-tabs-four-diag-tab">
                                                        <div class="form-group row">
                                                            <div class="col-md-6">
                                                                <div class="form-group row align-items-center">
                                                                    <label for="icd10" class="ml-2 col-form-label">Diagnosa (ICD 10)</label>
                                                                    <div class="ml-1">
                                                                        <button type="button" class="ml-2 btn btn-default" id="kodeICD10" style="cursor: not-allowed;" data-toggle="tooltip" data-placement="top" title="Silahkan pilih pada kolom dibawah">KODE ICD 10</button>
                                                                        <button type="button" class="ml-2 btn btn-default dropdown-toggle" id="dropdownMenuButtonICD10" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <span id="prioritas_icd_10" class="caret">Pilih</span>
                                                                        </button>
                                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButtonICD10">
                                                                            <li><a class="dropdown-item" data-value="Primary">Primary</a></li>
                                                                            <li><a class="dropdown-item" data-value="Sekunder">Sekunder</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="input-group" style="display: flex; align-items: center;">
                                                                    <select class="form-control select2bs4" style="width: 80%;" id="icd10" name="icd10">
                                                                        <option value="" disabled selected>-- Pilih --</option>
                                                                        @foreach ($icd10 as $icd10data)
                                                                                <option value="{{$icd10data->kode_icd10}}" data-nama="{{$icd10data->nama_icd10}}">{{$icd10data->kode_icd10}} - {{$icd10data->nama_icd10}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <!-- Tombol check yang sejajar dengan dropdown -->
                                                                    <div class="input-group-append">
                                                                        <button type="button" class="btn btn-secondary" id="acceptICD10">
                                                                            <i class="fa fa-check"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group row align-items-center">
                                                                    <label for="icd9" class="ml-2 col-form-label">Tindakan (ICD 9)</label>
                                                                    <div class="ml-1">
                                                                        <!-- Tombol yang akan menampilkan kode ICD 9 -->
                                                                        <button type="button" class="ml-2 btn btn-default" id="kodeICD9" style="cursor: not-allowed;" data-toggle="tooltip" data-placement="top" title="Silahkan pilih pada kolom dibawah">KODE ICD 9</button>
                                                                    </div>
                                                                </div>

                                                                <!-- Dropdown ICD 9 menggunakan -->
                                                                <div class="input-group" style="display: flex; align-items: center;">
                                                                    <select class="form-control select2bs4" style="width: 80%;" id="icd9" name="icd9">
                                                                        <option value="" disabled selected>-- Pilih --</option>
                                                                        @foreach ($icd9 as $icd9data)
                                                                            <option value="{{$icd9data->kode_icd9}}" data-nama="{{$icd9data->nama_icd9}}">{{$icd9data->kode_icd9}} - {{$icd9data->nama_icd9}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <!-- Tombol Accept -->
                                                                    <div class="input-group-append">
                                                                        <button type="button" class="btn btn-secondary" id="acceptICD9">
                                                                            <i class="fa fa-check"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <table class="table table-bordered no-padding icd" width="100%" style="border-spacing: 0; border-collapse: collapse;">
                                                            <tbody>
                                                                <tr class="kosong">
                                                                    <td colspan="4" style="text-align:center;">Data Tidak Ada</td>
                                                                </tr>

                                                                <tr class="isi_10">
                                                                    <td valign="top" width="200px" style="vertical-align: middle;"> Diagnosa/Penyakit/ICD 10 </td>
                                                                    <td valign="top" width="1px" style="vertical-align: middle;"> : </td>
                                                                    <td valign="top">
                                                                        <table width="100%" cellpadding="3px" cellspacing="0" class="icd_10">
                                                                            <thead>
                                                                                <tr align="center">
                                                                                    <td valign="top" width="100px" style="border: none;">Kode</td>
                                                                                    <td valign="top" style="border: none;">Nama Penyakit</td>
                                                                                    <td valign="top" width="100px" style="border: none;">Prioritas</td>
                                                                                    <td valign="top" width="100px" style="border: none;">Aksi</td>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr class="isi_9">
                                                                    <td valign="top" width="250px" style="vertical-align: middle;">Tindakan/Penyakit/ICD 9</td>
                                                                    <td valign="top" width="1px" style="vertical-align: middle;">:</td>
                                                                    <td valign="top">
                                                                        <table width="100%" cellpadding="3px" cellspacing="0" class="icd_9">
                                                                            <tbody>
                                                                                <tr align="center">
                                                                                    <td valign="top" width="100px" style="border: none;">Kode</td>
                                                                                    <td valign="top" style="border: none;">Nama Tindakan</td>
                                                                                    <td valign="top" width="100px" style="border: none;">Prioritas</td>
                                                                                    <td valign="top" width="100px" style="border: none;">Aksi</td>
                                                                                </tr>

                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    </div>

                                                    <div class="tab-pane fade" id="custom-tabs-four-plan" role="tabpanel" aria-labelledby="custom-tabs-four-plan-tab">
                                                        <div class="form-group row">
                                                            <div class="col-md-12">
                                                                <label for="summernote5">Expertise</label>
                                                                <textarea class="form-control" id="summernote5" name="summernote5" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-6">
                                                                <label for="summernote3">Evaluasi</label>
                                                                <textarea class="form-control" id="summernote3" name="summernote3" rows="3"></textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="summernote4">Rencana Tindakan</label>
                                                                <textarea class="form-control" id="summernote4" name="summernote4" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="custom-tabs-four-diet" role="tabpanel" aria-labelledby="custom-tabs-four-diet-tab">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label>Jenis Diet</label>
                                                                <select id="jenisDiet" class="form-control select2bs4">
                                                                    <option value="">-- Pilih Jenis Diet --</option>
                                                                    @foreach ($jenis_diete as $jenis_dietedata)
                                                                        <option value="{{ $jenis_dietedata->nama }}">{{ $jenis_dietedata->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label>Makanan Dianjurkan</label>
                                                                <select id="makananAnjuran" class="form-control select2bs4">
                                                                    <option value="">-- Pilih Makanan Dianjurkan --</option>
                                                                    @foreach ($jenis_makanan_diet as $jenis_makanan_dietdata)
                                                                        <option value="{{ $jenis_makanan_dietdata->nama }}">{{ $jenis_makanan_dietdata->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label>Makanan Tidak Boleh</label>
                                                                <select id="makananPantangan" class="form-control select2bs4">
                                                                    <option value="">-- Pilih Makanan Tidak Boleh --</option>
                                                                    @foreach ($jenis_makanan_diet as $jenis_makanan_dietdata)
                                                                        <option value="{{ $jenis_makanan_dietdata->nama }}">{{ $jenis_makanan_dietdata->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-1 d-flex align-items-end">
                                                                <button type="button"  class="btn btn-success" id="btnTambahDiet" onclick="tambahAtauUpdateDiet(event)">Tambah</button>
                                                            </div>
                                                        </div>

                                                        <!-- Tabel hasil -->
                                                        <div class="mt-3">
                                                            <table class="table table-bordered" id="tabelDiet">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Jenis Diet</th>
                                                                        <th>Makanan Dianjurkan</th>
                                                                        <th>Makanan Tidak Boleh</th>
                                                                        <th>Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                        <div id="hiddenDietInputs">
                                                        </div>


                                                    </div>

                                                    <div class="tab-pane fade" id="custom-tabs-four-tindakan" role="tabpanel" aria-labelledby="custom-tabs-four-tindakan-tab">
                                                        <div class="form-row align-items-center mb-3" id="form-tindakan">
                                                            <div class="col-md-3 mb-2">
                                                                <select class="form-control select2bs4" id="jenis-tindakan">
                                                                    <option value="">Jenis Tindakan</option>
                                                                    @foreach($kategori as $kat)
                                                                        <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 mb-2">
                                                                <select class="form-control select2bs4" id="tindakan">
                                                                    <option value="">Tindakan</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 mb-2">
                                                                <select class="form-control select2bs4" id="pelaksana">
                                                                    <option value="">Pelaksana</option>
                                                                        <option value="dokter">Dokter</option>
                                                                        <option value="perawat">Perawat</option>
                                                                        <option value="keduanya">Dokter + Perawat</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 mb-2">
                                                                <button type="button" class="btn btn-primary btn-block" id="tambah-tindakan">Tambah</button>
                                                            </div>
                                                        </div>

                                                        <!-- TABEL TINDAKAN -->
                                                        <div class="mt-3">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" id="tabel-tindakan">
                                                                    <thead class="thead-light">
                                                                        <tr>
                                                                            <th style="width: 30%">Tindakan</th>
                                                                            <th style="width: 25%">Pelaksana</th>
                                                                            <th style="width: 20%">Harga</th>
                                                                            <th style="width: 25%">Aksi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div id="hiddenTindakanInputs"></div>

                                                    </div>

                                                    <div class="tab-pane fade" id="custom-tabs-four-obat" role="tabpanel" aria-labelledby="custom-tabs-four-obat-tab">
                                                            <!-- Input R:/ -->
                                                            <div class="form-row align-items-center mb-3">
                                                                <div class="col-12">
                                                                    <label for="r-text">R:/</label>
                                                                    <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <button type="button" id="btn-r-action" class="btn btn-info">R:/</button>
                                                                    </div>
                                                                    <input type="text" id="r-text" class="form-control" placeholder="Kosong = R:/, isi = R:/ + teks">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Input Obat & Dosis -->
                                                            <div class="form-row align-items-end mb-3">
                                                            <div class="col-md-4">
                                                                <label for="nama-obat">Nama Obat</label>
                                                                <select class="form-control select2bs4" id="nama-obat">
                                                                <option value="">-- Pilih Obat --</option>
                                                                @foreach ($obat as $obatdata)
                                                                    <option value="{{ $obatdata->nama_barang }}" data-satuan="{{ $obatdata->satuan_kecil }}">{{ $obatdata->nama_barang }}</option>
                                                                @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Qty / Jumlah</label>
                                                                <div class="form-row">
                                                                    <div class="col">
                                                                    <input type="text" id="dosis1" class="form-control" placeholder="Contoh: 500">
                                                                    </div>
                                                                    <div class="col">
                                                                    <input type="text" id="dosis2" class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="instruksi">Instruksi</label>
                                                                <select class="form-control select2bs4" id="instruksi">
                                                                <option value="">-- Pilih Instruksi --</option>
                                                                <option value="CITO">CITO</option>
                                                                <option value="ITER">ITER</option>
                                                                <option value="Equal qs">Equal qs</option>
                                                                <option value="m.f pulv da in caps">m.f pulv da in caps</option>
                                                                <option value="s.u.e">s.u.e</option>
                                                                <option value="m.f pulv dtd no X">m.f pulv dtd no X</option>
                                                                <option value="m.f pulv dtd no XV">m.f pulv dtd no XV</option>
                                                                <option value="s.q.d.d.c">s.q.d.d.c</option>
                                                                <option value="haust">haust</option>
                                                                <option value="s.i.m.m">s.i.m.m</option>
                                                                <option value="cth">cth</option>
                                                                </select>
                                                            </div>
                                                            </div>

                                                            <!-- Input Signa -->
                                                            <div class="form-row align-items-end mb-3">
                                                                <div class="col-md-6">
                                                                    <label>Signa</label>
                                                                    <div class="form-row align-items-center">
                                                                        <div class="col">
                                                                            <input type="text" id="signa-jumlah1" class="form-control" placeholder="Contoh: 1">
                                                                        </div>
                                                                        <div class="col-auto">
                                                                            <strong>x</strong>
                                                                        </div>
                                                                        <div class="col">
                                                                            <input type="text" id="signa-jumlah2" class="form-control" placeholder="Contoh: 3">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div style="visibility: hidden;"><label for="signa-satuan1">Signa Satuan 1</label></div>
                                                                    <select class="form-control select2bs4" id="dosis3">
                                                                        @foreach ($satuan as $satuandata)
                                                                            <option value="{{ $satuandata->nama }}">{{ $satuandata->nama }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div style="visibility: hidden;"><label for="signa-satuan2">Signa Satuan 2</label></div>
                                                                    <select class="form-control select2bs4" id="signa-satuan2">
                                                                        <option value="">-- Pilih Satuan --</option>
                                                                        <option value="SEBELUM MAKAN">SEBELUM MAKAN</option>
                                                                        <option value="SESUDAH MAKAN">SESUDAH MAKAN</option>
                                                                        <option value="SEBELUM/SESUDAH MAKAN">SEBELUM/SESUDAH MAKAN</option>
                                                                        <option value="JIKA MUAL-MUAL">JIKA MUAL-MUAL</option>
                                                                        <option value="JIKA BUANG AIR BESAR">JIKA BUANG AIR BESAR</option>
                                                                        <option value="JIKA MERASA NYERI">JIKA MERASA NYERI</option>
                                                                        <option value="DIMINUM SETELAH SUAPAN PERTAMA">DIMINUM SETELAH SUAPAN PERTAMA</option>
                                                                    </select>
                                                                </div>

                                                            </div>

                                                            <!-- Tombol Tambah -->
                                                            <div class="form-group mb-3">
                                                            <button type="button" id="btn-add-obat" class="btn btn-primary">Tambah Obat ke Resep</button>
                                                            <button type="button" class="btn btn-secondary" id="btn-print-resep-ajax">🖨️ Print Resep (PDF)</button>

                                                            </div>

                                                            <!-- Tampilan Resep -->
                                                            <div class="form-group">
                                                            <label for="summernote-resep">Resep:</label>
                                                            <div id="summernote-resep" name="summernote-resep" style="border:1px solid #ccc; min-height:200px; padding:10px; background:#f9f9f9; overflow-y:auto;"></div>
                                                            <input type="hidden" name="resep_data" id="resep-data">

                                                            </div>

                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                    {{-- poli gigi only --}}
                                                    <div class="tab-pane fade" id="custom-tabs-four-odo" role="tabpanel" aria-labelledby="custom-tabs-four-odo-tab">
                                                        <style>
                                                            .svg-container {
                                                                display: flex;
                                                                justify-content: center;
                                                                align-items: center;
                                                                width: 100%;
                                                            }
                                                            .clickable-box {
                                                                cursor: pointer;
                                                            }
                                                        </style>
                                                        <div class="container svg-container">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 980 300" preserveAspectRatio="xMidYMin meet">
                                                                @php
                                                                    $leftNumbers = [18, 17, 16, 15, 14, 13, 12, 11, 55, 54, 53, 52, 51, 85, 84, 83, 82, 81, 48, 47, 46, 45, 44, 43, 42, 41];
                                                                    $rightNumbers = [21, 22, 23, 24, 25, 26, 27, 28, 61, 62, 63, 64, 65, 71, 72, 73, 74, 75, 31, 32, 33, 34, 35, 36, 37, 38];
                                                                @endphp
                                                                <!-- Kotak Kiri -->
                                                                @foreach ($leftNumbers as $index => $number)
                                                                    @php
                                                                        $row = $index < 8 ? 0 : ($index < 13 ? 1 : ($index < 18 ? 2 : 3));
                                                                        $col = $index < 8 ? $index : ($index < 13 ? $index - 8 + 1.5 : ($index < 18 ? $index - 13 + 1.5 : $index - 18));
                                                                        $x = $col * 60;
                                                                        $y = $row * 60;
                                                                        $isDiagonal = in_array($number, [14, 15, 16, 17, 18, 44, 45, 46, 47, 48, 54, 55, 84, 85]);
                                                                        $isMiddleLine = in_array($number, [11, 12, 13, 51, 52, 53, 81, 82, 83, 41, 42, 43]);
                                                                        $imagePath = $isDiagonal ? "/dist/img/odo/geraham.png" : ($isMiddleLine ? "/dist/img/odo/seri.png" : "/dist/img/odo/geraham.png");
                                                                    @endphp

                                                                    <g class="clickable-box" data-number="{{ $number }}">
                                                                        <image
                                                                            x="{{ $x + 10 }}"
                                                                            y="{{ $y + 10 }}"
                                                                            width="40"
                                                                            height="40"
                                                                            href="{{ $imagePath }}"
                                                                            pointer-events="all"
                                                                        />
                                                                        <text
                                                                            x="{{ $x + 30 }}"
                                                                            y="{{ $y + 65 }}"
                                                                            font-size="12"
                                                                            text-anchor="middle"
                                                                            pointer-events="none"
                                                                        >
                                                                            {{ $number }}
                                                                        </text>
                                                                    </g>
                                                                @endforeach

                                                                <!-- Divider -->
                                                                <rect x="490" y="0" width="5" height="255" fill="red" />

                                                                <!-- Kotak Kanan -->
                                                                @foreach ($rightNumbers as $index => $number)
                                                                @php
                                                                    $row = $index < 8 ? 0 : ($index < 13 ? 1 : ($index < 18 ? 2 : 3));
                                                                    $col = $index < 8 ? $index : ($index < 13 ? $index - 8 + 1.5 : ($index < 18 ? $index - 13 + 1.5 : $index - 18));
                                                                    $x = $col * 60 + 500;
                                                                    $y = $row * 60;
                                                                    $isDiagonal = in_array($number, [24, 25, 26, 27, 28, 34, 35, 36, 37, 38, 64, 65, 74, 75]);
                                                                    $isMiddleLine = in_array($number, [21, 22, 23, 61, 62, 63, 71, 72, 73, 31, 32, 33]);
                                                                    $imagePath = $isDiagonal ? "/dist/img/odo/geraham.png" : ($isMiddleLine ? "/dist/img/odo/seri.png" : "/dist/img/odo/geraham.png");
                                                                @endphp

                                                                <g class="clickable-box" data-number="{{ $number }}">
                                                                    <image
                                                                        x="{{ $x + 10 }}"
                                                                        y="{{ $y + 10 }}"
                                                                        width="40"
                                                                        height="40"
                                                                        href="{{ $imagePath }}"
                                                                        pointer-events="all"
                                                                    />
                                                                    <text
                                                                        x="{{ $x + 30 }}"
                                                                        y="{{ $y + 65 }}"
                                                                        font-size="12"
                                                                        text-anchor="middle"
                                                                        pointer-events="none"
                                                                    >
                                                                        {{ $number }}
                                                                    </text>
                                                                </g>
                                                                @endforeach
                                                            </svg>
                                                        </div>

                                                        <div class="card collapsed-card">
                                                            <div class="card-header bg-info">
                                                                <p class="card-title">Pemeriksaan Gigi</p>

                                                                <div class="card-tools">
                                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <!-- Bagian kiri: DMF -->
                                                                    <div class="col-md-4">
                                                                        <h5 class="mb-3 font-weight-bold text-primary">Status Gigi (DMF)</h5>

                                                                        <div class="form-group">
                                                                            <label for="Decayed">Decayed</label>
                                                                            <input type="text" class="form-control" id="Decayed" name="Decayed" value="{{ old('Decayed', $gigiDetails->Decayed ?? '') }}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="Missing">Missing</label>
                                                                            <input type="text" class="form-control" id="Missing" name="Missing" value="{{ old('Missing', $gigiDetails->Missing ?? '') }}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="Filled">Filled</label>
                                                                            <input type="text" class="form-control" id="Filled" name="Filled" value="{{ old('Filled', $gigiDetails->Filled ?? '') }}">
                                                                        </div>

                                                                        <button type="button" class="btn btn-info mt-3 w-100" id="saveDentalForm">Simpan</button>

                                                                    </div>

                                                                    <!-- Spacer -->
                                                                    <div class="col-md-1"></div>

                                                                    <!-- Bagian kanan: Pemeriksaan Tambahan -->
                                                                    <div class="col-md-7">
                                                                        <h5 class="mb-3 font-weight-bold text-primary">Pemeriksaan Tambahan</h5>

                                                                        @php
                                                                            $selects = [
                                                                                ['label' => 'Oclusi', 'id' => 'Oclusi', 'options' => ['Normal Bite', 'Cross Bite', 'deep Bite']],
                                                                                ['label' => 'Torus Palatinus', 'id' => 'Palatinus', 'options' => ['Tidak Ada', 'Kecil', 'Sedang', 'Besar', 'Multiple']],
                                                                                ['label' => 'Torus Mandibularis', 'id' => 'Mandibularis', 'options' => ['Sisi Kiri', 'Sisi Kanan', 'Kedua Sisi']],
                                                                                ['label' => 'Platum', 'id' => 'Platum', 'options' => ['Dalam', 'Sedang', 'Rendah']],
                                                                                ['label' => 'Diastema', 'id' => 'Diastema', 'options' => ['Ada', 'Tidak Ada']],
                                                                                ['label' => 'Gigi Anomali', 'id' => 'Anomali', 'options' => ['Ada', 'Tidak Ada']]
                                                                            ];
                                                                        @endphp

                                                                        @foreach ($selects as $select)
                                                                            <div class="form-group row align-items-center">
                                                                                <label for="{{ $select['id'] }}" class="col-sm-4 col-form-label">{{ $select['label'] }}</label>
                                                                                <div class="col-sm-8">
                                                                                    <select class="form-control" id="{{ $select['id'] }}" name="{{ $select['id'] }}">
                                                                                        <option value="">Pilih</option>
                                                                                        @foreach ($select['options'] as $option)
                                                                                            <option value="{{ $option }}" {{ (isset($gigiDetails) && $gigiDetails->{$select['id']} == $option) ? 'selected' : '' }}>
                                                                                                {{ $option }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                            <!-- /.card -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    <!-- /.content -->
</div>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<!-- Modal Bootstrap -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="infoModalLabel">Pilih Kondisi Gigi</h5>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="kondisiGigi">Pilih Kondisi:</label>
            <select class="form-control select2bs4" style="width: 100%;" id="kondisiGigi" name="kondisiGigi">
                <option value="AMF" data-color="AMF">AMF</option>
                <option value="COF" data-color="COF">COF</option>
                <option value="FIS" data-color="FIS">FIS</option>
                <option value="NVT" data-color="NVT">NVT</option>
                <option value="CRT" data-color="CRT">CRT</option>
                <option value="CAR" data-color="CAR">CAR</option>
                <option value="AMF-CRT" data-color="AMF-CRT">AMF-CRT</option>
                <option value="FMC" data-color="FMC">FMC</option>
                <option value="FMC-RCT" data-color="FMC-RCT">FMC-RCT</option>
                <option value="POC" data-color="POC">POC</option>
                <option value="POC-RCT" data-color="POC-RCT">POC-RCT</option>
                <option value="RRX" data-color="RRX">RRX</option>
                <option value="MIS" data-color="MIS">MIS</option>
                <option value="COF_" data-color="COF_">COF_</option>
                <option value="CRF" data-color="CRF">CRF</option>
                <option value="COF-RCT" data-color="COF-RCT">COF-RCT</option>

              </select>
          </div>
          <!-- Input for Note -->
            <div class="form-group">
                <label for="noteGigi">Catatan:</label>
                <textarea class="form-control" id="noteGigi" name="noteGigi" rows="3" placeholder="Masukkan catatan terkait kondisi gigi di sini..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="saveButton">Simpan</button>
        </div>
      </div>
    </div>
</div>

<script>
    let selectedBox = null;

    // Ambil info pasien dari form readonly
    function getPatientInfo() {
        return {
            nomor_rm: document.getElementById('nomor_rm').value,
            nama: document.getElementById('nama').value,
            no_rawat: document.getElementById('no_rawat').value,
            sex: document.getElementById('sex').value,
            penjamin: document.getElementById('penjamin').value,
            tanggal_lahir: document.getElementById('tanggal_lahir').value
        };
    }

    // Klik gigi SVG
    document.querySelectorAll('.clickable-box').forEach(box => {
        box.addEventListener('click', function (event) {
            selectedBox = event.target.closest('.clickable-box');
            if (!selectedBox) return;

            const number = selectedBox.getAttribute('data-number');
            if (!number) {
                console.error('Nomor gigi tidak ditemukan!');
                return;
            }

            document.getElementById('infoModalLabel').textContent = `Pilih Kondisi Gigi (${number})`;

            // Reset pilihan modal
            document.getElementById('kondisiGigi').selectedIndex = 0;
            document.getElementById('noteGigi').value = '';

            $('#infoModal').modal('show');
        });
    });

    // Klik tombol "Simpan" di modal
    document.getElementById('saveButton').addEventListener('click', function () {
        if (!selectedBox) return;

        const selectedOption = document.getElementById('kondisiGigi').selectedOptions[0];
        const condition = selectedOption?.getAttribute('data-color') || '';
        const note = document.getElementById('noteGigi').value.trim();
        const toothNumber = selectedBox.getAttribute('data-number');
        const patientInfo = getPatientInfo();

        const odontogramRoute = "{{ route('odontogram.add') }}";
        const newData = {
            ...patientInfo,
            tooth_number: toothNumber,
            condition: condition,
            note: note
        };

        // Update visual gigi di SVG
        updateToothBox(toothNumber, condition, note);

        // Kirim langsung ke server via AJAX
        $.ajax({
            url: odontogramRoute,
            method: 'POST',
            data: JSON.stringify([newData]),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function () {
                $('#infoModal').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: `Data gigi ${toothNumber} berhasil disimpan.`,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                console.error(`Gagal menyimpan gigi ${toothNumber}:`, xhr.responseText);

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menyimpan!',
                    text: 'Terjadi kesalahan saat menyimpan data.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Fungsi bantu update tampilan kotak gigi
    function updateToothBox(toothNumber, condition, note) {
        const box = document.querySelector(`.clickable-box[data-number="${toothNumber}"]`);
        if (!box) return;

        const image = box.querySelector('image');
        const imagePath = `/dist/img/odo/${condition}.png`;
        if (image) image.setAttribute('href', imagePath);

        box.setAttribute('title', note);
    }

    // Load data dari server saat halaman pertama kali diload
    function loadOdontogramData() {
        const nomor_rm = document.getElementById('nomor_rm').value;
        const no_rawat = document.getElementById('no_rawat').value;

        $.ajax({
            url: '/api/odontogram/load',
            method: 'GET',
            data: {
                nomor_rm: nomor_rm,
                no_rawat: no_rawat
            },
            success: function (data) {
                data.forEach(item => {
                    updateToothBox(item.tooth_number, item.condition, item.note);
                });
                console.log('Data odontogram berhasil dimuat.');
            },
            error: function (xhr) {
                console.error('Gagal memuat data odontogram:', xhr.responseText);
            }
        });
    }

    // Jalankan saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', function () {
        loadOdontogramData();
    });
</script>


<script>
    document.getElementById('saveDentalForm').addEventListener('click', function () {
        // Ambil nilai input DMF
        const Decayed = document.getElementById('Decayed').value;
        const Missing = document.getElementById('Missing').value;
        const Filled  = document.getElementById('Filled').value;

        // Ambil nilai dari semua select (gunakan ID)
        const Oclusi        = document.getElementById('Oclusi').value;
        const Palatinus     = document.getElementById('Palatinus').value;
        const Mandibularis  = document.getElementById('Mandibularis').value;
        const Platum        = document.getElementById('Platum').value;
        const Diastema      = document.getElementById('Diastema').value;
        const Anomali       = document.getElementById('Anomali').value;

        // Ambil info pasien dari form (readonly fields)
        const nomor_rm       = document.getElementById('nomor_rm')?.value || '';
        const nama           = document.getElementById('nama')?.value || '';
        const no_rawat       = document.getElementById('no_rawat')?.value || '';
        const sex            = document.getElementById('sex')?.value || '';
        const penjamin       = document.getElementById('penjamin')?.value || '';
        const tanggal_lahir  = document.getElementById('tanggal_lahir')?.value || '';

        const odontogramDetailsAddRoute = "{{ route('odontogram.details.add') }}";

        // Siapkan payload untuk dikirim
        const formData = {
            nomor_rm,
            nama,
            no_rawat,
            sex,
            penjamin,
            tanggal_lahir,
            Decayed,
            Missing,
            Filled,
            Oclusi,
            Palatinus,
            Mandibularis,
            Platum,
            Diastema,
            Anomali
        };

        // Kirim via AJAX ke server (Laravel endpoint)
        $.ajax({
            url: odontogramDetailsAddRoute, // Ganti sesuai rute Laravel Anda
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data pemeriksaan gigi berhasil disimpan.',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menyimpan!',
                    text: 'Terjadi kesalahan saat menyimpan data.'
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        const nomor_rm = $('#nomor_rm').val();
        const no_rawat = $('#no_rawat').val();

        $.ajax({
            url: '/api/odontogram/load-details',
            method: 'POST',
            data: {
                nomor_rm: nomor_rm,
                no_rawat: no_rawat,
            },
            success: function (data) {
                if (data) {
                    $('#Decayed').val(data.Decayed);
                    $('#Missing').val(data.Missing);
                    $('#Filled').val(data.Filled);
                    $('#Oclusi').val(data.Oclusi);
                    $('#Palatinus').val(data.Palatinus);
                    $('#Mandibularis').val(data.Mandibularis);
                    $('#Platum').val(data.Platum);
                    $('#Diastema').val(data.Diastema);
                    $('#Anomali').val(data.Anomali);
                }
            },
            error: function (xhr) {
                console.error('Gagal memuat data dental exam:', xhr.responseText);
            }
        });
    });
</script>

{{-- Script load data --}}
<script>
    const soapRoute = "{{ route('pelayana_dokter_data.get', ':id') }}";

    $(document).ready(function () {
        let id = $('#no_rawat').val();
        if (id) {
            loadSoapData(id); // Ini panggil AJAX saat halaman dibuka
        }
    });

    function loadSoapData(id) {
        const url = soapRoute.replace(':id', id); // Ganti placeholder dengan ID sebenarnya
        $.ajax({
            url: url,
            method: 'GET',
            success: function (data) {
                // PARSE data.tableData (default [] jika null)
                dataArray = JSON.parse(data.tableData || '[]');

                // Kosongkan DataTable
                dataTable.clear().draw();

                // Tambahkan ulang ke DataTable
                dataArray.forEach((item, index) => {
                    const aksiBtn = `
                        <button class="btn btn-warning btn-sm mr-1" onclick="editData(${index})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="removeData(${index})">Hapus</button>
                    `;
                    dataTable.row.add([
                        index + 1,
                        `${item.penyakit} sejak ${item.durasi} ${item.waktu}`,
                        aksiBtn
                    ]);
                });
                dataTable.draw();
                updateHiddenInput(); // Simpan ke hidden input #tableData

                $('#sistol').val(data.sistol);
                $('#distol').val(data.distol);
                $('#tensi').val(data.tensi);
                $('#suhu').val(data.suhu);
                $('#nadi').val(data.nadi);
                $('#rr').val(data.rr);
                $('#tinggi').val(data.tinggi);
                $('#berat').val(data.berat);
                $('#spo2').val(data.spo2);

                $('#jenis_alergi').val(data.jenis_alergi).trigger('change');
                loadAlergiByJenis(data.jenis_alergi, data.alergi);

                $('#lingkar_perut').val(data.lingkar_perut);
                $('#nilai_bmi').val(data.nilai_bmi);
                $('#status_bmi').val(data.status_bmi);

                $('#eye').val(data.eye).trigger('change');
                $('#verbal').val(data.verbal).trigger('change');
                $('#motorik').val(data.motorik).trigger('change');

                $('#summernote').summernote('code', data.summernote);

                function formatPatientData(data) {
                    let keluhan = '';
                    if(data.keluhan && data.durasi && data.waktu) {
                        keluhan = `- Keluhan: ${data.keluhan}, sejak ${data.durasi} ${data.waktu}`;
                    }

                    const lines = [
                        keluhan,
                        `- Tensi: ${data.sistol}/${data.distol} mmHg`,
                        `- Suhu: ${data.suhu} °C`,
                        `- Nadi: ${data.nadi} /mnt, RR: ${data.rr} /mnt`,
                        `- Tinggi: ${data.tinggi} cm, Berat: ${data.berat} kg`,
                        `- SpO2: ${data.spo2}`,
                        `- Alergi: ${data.jenis_alergi} - ${data.alergi}`,
                        `- Lingkar Perut: ${data.lingkar_perut} cm`,
                        `- BMI: ${data.nilai_bmi} (${data.status_bmi})`,
                        `- GCS: E${data.eye} V${data.verbal} M${data.motorik} → Kesadaran: ${data.sadar || 'N/A'}`,
                    ];

                    // Ambil isi summernote (bisa berupa HTML), convert ke teks polos tanpa tag HTML supaya rapi
                    const headToToeRaw = data.summernote || '';
                    const headToToeText = $('<div>').html(headToToeRaw).text().replace(/\n/g, '').trim() || 'Tidak ada pemeriksaan';
                    lines.push(`- Head to Toe: ${headToToeText}`);

                    // Gabungkan jadi string dengan newline
                    const fullText = lines.filter(line => line && line.trim() !== '').join('\n');

                    // Ubah newline jadi <br> untuk HTML Summernote
                    const htmlText = fullText.replace(/\n/g, '<br>');

                    return htmlText;
                    }

                    // Contoh pakai:
                    const formatted = formatPatientData(data);
                    $('#summernote5').summernote('code', formatted);
                }
        });
    }
</script>

{{-- Script resep --}}
<script>
    $(function () {
        let resepList = [];
        let selectedIndex = -1;

        function renderResep() {
            let html = "";
            resepList.forEach((line, i) => {
            html += `<div class="resep-line d-flex justify-content-between align-items-center"
                    data-index="${i}" style="padding:6px 10px; cursor:pointer; border-bottom:1px solid #ddd; ${i === selectedIndex ? 'background:#d1ecf1;' : ''}">
                <span class="resep-text">${$('<div>').html(line.replace(/\n/g, "<br>")).html()}</span>`;
            if (i === selectedIndex) {
                html += `<div class="btn-group btn-group-sm ml-2">
                        <button type="button" class="btn btn-warning btn-up">▲</button>
                        <button type="button" class="btn btn-warning btn-down">▼</button>
                        <button type="button" class="btn btn-success btn-edit">✎</button>
                        <button type="button" class="btn btn-danger btn-delete">✖</button>
                        </div>`;
            }
            html += `</div>`;
            });
            $("#summernote-resep").html(html);
            $("#resep-data").val(JSON.stringify(resepList));
        }

        $("#btn-r-action").click(function () {
            const text = $("#r-text").val().trim();
            resepList.push(text ? `R:/ ${text}` : "R:/");
            $("#r-text").val("");
            renderResep();
        });

    $("#btn-add-obat").click(function () {
            const nama = $("#nama-obat").val();
            const dosis1 = $("#dosis1").val().trim();
            const dosis2 = $("#dosis2").val().trim();
            const signaJumlah1 = $("#signa-jumlah1").val().trim();
            const signaJumlah2 = $("#signa-jumlah2").val().trim();
            const dosis3 = $("#dosis3").val().trim();
            const signaSatuan2 = $("#signa-satuan2").val();
            const instruksi = $("#instruksi").val().trim();

            if (!nama) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Pilih nama obat!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            let line = `${nama}`;
            if (dosis1) line += ` ${dosis1}`;
            if (dosis2) line += ` ${dosis2}`;

            line += "\n";

            if (instruksi) {
                line += `${instruksi}\n`;
            }

            // Susun signa walau tidak lengkap
            if (signaJumlah1 && signaJumlah2) {
                line += `${signaJumlah1} x ${signaJumlah2}`;
                if (dosis3) line += ` ${dosis3}`;
                if (signaSatuan2) line += ` ${signaSatuan2}`;
            }

            resepList.push(line);
            renderResep();

            // Reset input
            $("#nama-obat").val("");
            $("#dosis1").val("");
            $("#dosis2").val("");
            $("#instruksi").val("");
            $("#signa-jumlah1").val("");
            $("#signa-jumlah2").val("");
            $("#dosis3").val("");
            $("#signa-satuan2").val("");
        });


        // Auto isi dosis2
        $("#nama-obat").on("change", function () {
            const satuan = $(this).find(":selected").data("satuan");
            $("#dosis2").val(satuan ?? "");
        });

        $("#summernote-resep").on("click", ".resep-line", function () {
            const idx = $(this).data("index");
            selectedIndex = selectedIndex === idx ? -1 : idx;
            renderResep();
        });

        $("#summernote-resep").on("click", ".btn-delete", function (e) {
            e.stopPropagation();
            const idx = $(this).closest(".resep-line").data("index");
            resepList.splice(idx, 1);
            selectedIndex = -1;
            renderResep();
        });

        $("#summernote-resep").on("click", ".btn-up", function (e) {
            e.stopPropagation();
            const idx = $(this).closest(".resep-line").data("index");
            if (idx > 0) {
            [resepList[idx - 1], resepList[idx]] = [resepList[idx], resepList[idx - 1]];
            selectedIndex = idx - 1;
            renderResep();
            }
        });

        $("#summernote-resep").on("click", ".btn-down", function (e) {
            e.stopPropagation();
            const idx = $(this).closest(".resep-line").data("index");
            if (idx < resepList.length - 1) {
            [resepList[idx + 1], resepList[idx]] = [resepList[idx], resepList[idx + 1]];
            selectedIndex = idx + 1;
            renderResep();
            }
        });

        $("#summernote-resep").on("click", ".btn-edit", function (e) {
            e.stopPropagation();
            const idx = $(this).closest(".resep-line").data("index");
            const line = resepList[idx];

            // Reset form yang pasti diisi (nama & dosis)
            $("#nama-obat").val("");
            $("#dosis1").val("");
            $("#dosis2").val("");
            $("#instruksi").val("");
            $("#signa-jumlah1").val("");
            $("#signa-jumlah2").val("");
            $("#dosis3").val("");
            $("#signa-satuan2").val("");
            $("#r-text").val("");

            if (line.startsWith("R:/")) {
                // Jika ini resep bebas
                const content = line.replace(/^R:\/*\s*/, "");
                $("#r-text").val(content);
            } else {
                // Pisah berdasarkan newline, hilangkan baris kosong
                const parts = line.split("\n").map(p => p.trim()).filter(p => p.length > 0);

                // === PARSING BARIS 1: nama obat + dosis1 + dosis2 ===
                if (parts.length > 0) {
                    const tokens = parts[0].split(" ");
                    if (tokens.length >= 3) {
                        // Ambil 2 terakhir sebagai dosis
                        $("#dosis2").val(tokens.pop());
                        $("#dosis1").val(tokens.pop());
                        $("#nama-obat").val(tokens.join(" "));
                    } else if (tokens.length === 2) {
                        $("#dosis1").val(tokens.pop());
                        $("#nama-obat").val(tokens.join(" "));
                    } else if (tokens.length === 1) {
                        $("#nama-obat").val(tokens[0]);
                    }
                }

                // === PARSING BARIS 2: instruksi (opsional) ===
                if (parts.length > 1 && !/^\d+\s*x\s*\d+/.test(parts[1])) {
                    // Pastikan ini bukan signa
                    $("#instruksi").val(parts[1]);
                }

                // === PARSING BARIS 3 atau BARIS 2 (jika tidak ada instruksi): signa ===
                const signaLine = parts.find(p => /^\d+\s*x\s*\d+/.test(p));
                if (signaLine) {
                    const signaRegex = /^(\d+)\s*x\s*(\d+)(?:\s+(\S+))?(?:\s+(.*))?$/;
                    const match = signaLine.match(signaRegex);
                    if (match) {
                        if (match[1]) $("#signa-jumlah1").val(match[1]);
                        if (match[2]) $("#signa-jumlah2").val(match[2]);
                        if (match[3]) $("#dosis3").val(match[3]);
                        if (match[4]) $("#signa-satuan2").val(match[4]);
                    }
                }
            }

            // Hapus item yang sedang diedit dan render ulang
            resepList.splice(idx, 1);
            selectedIndex = -1;
            renderResep();
        });



        $("#btn-print-resep-ajax").click(function () {
            const resepData = JSON.stringify(resepList);

            $.ajax({
                url: '{{ route('resep.print') }}',
                type: 'POST',
                data: {
                    resep_data: resepData,
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'blob' // penting agar bisa buka PDF dari binary
                },
                success: function (response, status, xhr) {
                    const blob = new Blob([response], { type: 'application/pdf' });
                    const url = window.URL.createObjectURL(blob);
                    window.open(url, '_blank');
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal mencetak resep.'
                    });
                }
            });
        });
    });
</script>

{{-- Script tindakan --}}
<script>
    const perawatanTindakan = @json($tindakan);

    let tindakanList = [];
    let editingData = null;
    let editingIndex = null;

    function renderTable() {
        const tbody = $('#tabel-tindakan tbody');
        tbody.empty();

        tindakanList.forEach((group, index) => {
            const pelaksanaRows = group.pelaksana;
            pelaksanaRows.forEach((p, i) => {
                const row = $('<tr>');
                if (i === 0) {
                    row.append(`<td rowspan="${pelaksanaRows.length}" class="align-middle text-center">${group.nama}</td>`);
                }
                row.append(`<td class="align-middle text-center">${p.pelaksana}</td>`);
                row.append(`<td class="align-middle text-center">Rp ${p.harga.toLocaleString('id-ID')}</td>`);
                if (i === 0) {
                    row.append(`
                        <td rowspan="${pelaksanaRows.length}" class="align-middle text-center">
                            <button type="button" class="btn btn-sm btn-warning btn-edit" data-index="${index}">Edit</button>
                            <button type="button" class="btn btn-sm btn-danger btn-delete" data-index="${index}">Hapus</button>
                        </td>
                    `);
                }
                tbody.append(row);
            });
        });
    }

    function resetForm() {
        $('#jenis-tindakan').val('');
        $('#tindakan').empty().append('<option value="">Pilih Tindakan</option>');
        $('#pelaksana').val('');
        $('#tambah-tindakan').text('Tambah').removeData('edit-index');

        if (editingData !== null && editingIndex !== null) {
            tindakanList.splice(editingIndex, 0, editingData);
            editingData = null;
            editingIndex = null;
            renderTable();
        }
    }

    $(document).ready(function () {
        $('#jenis-tindakan').change(function () {
            const kategoriId = parseInt($(this).val());
            const tindakanSelect = $('#tindakan').empty().append('<option value="">Pilih Tindakan</option>');

            if (!isNaN(kategoriId)) {
                perawatanTindakan
                    .filter(t => t.perawatan_kategori_id === kategoriId)
                    .forEach(t => {
                        tindakanSelect.append(`<option value="${t.id}">${t.nama}</option>`);
                    });
            }
        });

        $('#tambah-tindakan').click(function () {
            const kategoriId = parseInt($('#jenis-tindakan').val());
            const tindakanId = parseInt($('#tindakan').val());
            const pelaksana = $('#pelaksana').val();
            const isEditing = $(this).data('edit-index') === 'editing';

            if (isNaN(kategoriId) || isNaN(tindakanId) || !pelaksana) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Semua kolom harus diisi!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            const tindakanData = perawatanTindakan.find(t => t.id === tindakanId);
            if (!tindakanData) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Tindakan tidak valid!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            const pelaksanaList = [];
            if (pelaksana === 'dokter') {
                pelaksanaList.push({ pelaksana: 'Dokter', harga: tindakanData.tarif_dokter });
            } else if (pelaksana === 'perawat') {
                pelaksanaList.push({ pelaksana: 'Perawat', harga: tindakanData.tarif_perawat });
            } else if (pelaksana === 'keduanya') {
                pelaksanaList.push({ pelaksana: 'Dokter', harga: tindakanData.tarif_dokter });
                pelaksanaList.push({ pelaksana: 'Perawat', harga: tindakanData.tarif_perawat });
            }

            const newEntry = { nama: tindakanData.nama, pelaksana: pelaksanaList, _id: 'tindakan_' + Date.now() };

            if (isEditing) {
                tindakanList.splice(editingIndex, 0, newEntry);
                editingData = null;
                editingIndex = null;
                $(this).removeData('edit-index').text('Tambah');
            } else {
                const existingIndex = tindakanList.findIndex(t => t.nama === tindakanData.nama);

                if (existingIndex !== -1) {
                    const existing = tindakanList[existingIndex];
                    pelaksanaList.forEach(p => {
                        if (!existing.pelaksana.some(e => e.pelaksana === p.pelaksana)) {
                            existing.pelaksana.push(p);
                        }
                    });

                    const existingId = existing._id;
                    $(`#hiddenTindakanInputs input[data-id="${existingId}"]`).remove();

                    // Tambahkan hanya 1 tindakan_nama
                    $('#hiddenTindakanInputs').append(`
                        <input type="hidden" name="tindakan_nama[]" value="${existing.nama}" data-id="${existingId}">
                    `);
                    existing.pelaksana.forEach(p => {
                        $('#hiddenTindakanInputs').append(`
                            <input type="hidden" name="tindakan_pelaksana[]" value="${p.pelaksana}" data-id="${existingId}">
                            <input type="hidden" name="tindakan_harga[]" value="${p.harga ?? 0}" data-id="${existingId}">
                        `);
                    });
                } else {
                    tindakanList.push(newEntry);
                    $('#hiddenTindakanInputs').append(`
                        <input type="hidden" name="tindakan_nama[]" value="${newEntry.nama}" data-id="${newEntry._id}">
                    `);
                    pelaksanaList.forEach(p => {
                        $('#hiddenTindakanInputs').append(`
                            <input type="hidden" name="tindakan_pelaksana[]" value="${p.pelaksana}" data-id="${newEntry._id}">
                            <input type="hidden" name="tindakan_harga[]" value="${p.harga ?? 0}" data-id="${newEntry._id}">
                        `);
                    });
                }
            }

            renderTable();
            resetForm();
        });

        $('#tabel-tindakan').on('click', '.btn-delete', function () {
            const index = $(this).data('index');
            if (editingIndex === index) {
                editingData = null;
                editingIndex = null;
                $('#tambah-tindakan').removeData('edit-index').text('Tambah');
                resetForm();
            }
            const uniqueId = tindakanList[index]._id;
            $(`#hiddenTindakanInputs input[data-id="${uniqueId}"]`).remove();
            tindakanList.splice(index, 1);
            renderTable();
        });

        $('#tabel-tindakan').on('click', '.btn-edit', function () {
            const index = $(this).data('index');
            const item = tindakanList[index];

            editingData = item;
            editingIndex = index;
            const uniqueId = item._id;
            $(`#hiddenTindakanInputs input[data-id="${uniqueId}"]`).remove();
            tindakanList.splice(index, 1);
            renderTable();

            const tindakanObj = perawatanTindakan.find(t => t.nama === item.nama);
            const kategoriId = tindakanObj ? tindakanObj.perawatan_kategori_id : null;

            const pelaksana = item.pelaksana.length === 2 ? 'keduanya' : item.pelaksana[0].pelaksana.toLowerCase();

            $('#jenis-tindakan').val(kategoriId).trigger('change');

            setTimeout(() => {
                $('#tindakan').val(tindakanObj ? tindakanObj.id : '').trigger('change');
                $('#pelaksana').val(pelaksana);
                $('#tambah-tindakan').text('Update').data('edit-index', 'editing');
            }, 100);
        });
    });
</script>

{{-- Script Diet --}}
<script>
    let editIndex = null;

    function tambahAtauUpdateDiet(event) {
        event.preventDefault();

        const jenis = document.getElementById("jenisDiet").value;
        const anjur = document.getElementById("makananAnjuran").value;
        const pantang = document.getElementById("makananPantangan").value;

        if (!jenis || !anjur || !pantang) {
            Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Mohon lengkapi semua pilihan!',
                    confirmButtonText: 'OK'
            });
            return;
        }

        const tbody = document.querySelector("#tabelDiet tbody");

        if (editIndex === null) {
            const uniqueId = 'diet_' + Date.now(); // ID unik

            const row = document.createElement("tr");
            row.setAttribute("data-id", uniqueId);
            row.innerHTML = `
                <td>${jenis}</td>
                <td>${anjur}</td>
                <td>${pantang}</td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" onclick="editBaris(this)">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusBaris(this)">Hapus</button>
                </td>
            `;
            tbody.appendChild(row);

            // Tambahkan hidden input
            const hiddenWrapper = document.getElementById("hiddenDietInputs");
            hiddenWrapper.insertAdjacentHTML("beforeend", `
                <input type="hidden" name="diet_jenis[]" value="${jenis}" data-id="${uniqueId}">
                <input type="hidden" name="diet_anjuran[]" value="${anjur}" data-id="${uniqueId}">
                <input type="hidden" name="diet_pantangan[]" value="${pantang}" data-id="${uniqueId}">
            `);
        }


        // Reset input
        document.getElementById("jenisDiet").value = "";
        document.getElementById("makananAnjuran").value = "";
        document.getElementById("makananPantangan").value = "";
    }

    function editBaris(button) {
        const row = button.closest("tr");
        const id = row.getAttribute("data-id");
        const cells = row.children;

        // Ambil nilai dari baris
        const jenis = cells[0].textContent;
        const anjur = cells[1].textContent;
        const pantang = cells[2].textContent;

        // Set nilai ke select/input
        document.querySelectorAll(`#hiddenDietInputs input[data-id="${id}"]`).forEach(el => el.remove());
        document.getElementById("jenisDiet").value = jenis;
        document.getElementById("makananAnjuran").value = anjur;
        document.getElementById("makananPantangan").value = pantang;

        // Hapus baris dari tabel
        row.remove();

        // Reset mode edit
        editIndex = null;
        document.getElementById("btnTambahDiet").textContent = "Update";
    }


    function hapusBaris(button) {
        const row = button.closest("tr");
        const id = row.getAttribute("data-id");
        document.querySelectorAll(`#hiddenDietInputs input[data-id="${id}"]`).forEach(el => el.remove());
        row.remove();

        if (editIndex !== null) {
            editIndex = null;
            document.getElementById("btnTambahDiet").textContent = "Tambah";
        }
    }

</script>

{{-- Script ICD --}}
<script>
    $(document).ready(function () {
        // Variabel penyimpanan
        let selectedICD10 = null, selectedPriorityICD10 = null;
        let selectedICD9 = null, selectedPriorityICD9 = null;

        function hasPrimary(tbodySelector) {
        let found = false;
        $(`${tbodySelector} tr`).each(function () {
            const prioritasText = $(this).find('td:eq(2)').text().trim();
            if (prioritasText === 'Primary') {
                found = true;
                return false; // Break the loop
            }
        });
        return found;
    }

    function isDuplicate(tbodySelector, code) {
        let found = false;
        $(`${tbodySelector} tr`).each(function () {
            const kodeText = $(this).find('td:eq(0)').text().trim();
            if (kodeText === code) {
                found = true;
                return false; // Break the loop
            }
        });
        return found;
    }

    // ICD10 - dropdown
    $('#icd10').on('change', function () {
        const opt = $(this).find('option:selected');
        selectedICD10 = { code: opt.val(), name: opt.data('nama') };
        $('#kodeICD10').text(selectedICD10.code);
    });

    $('#dropdownMenuButtonICD10').next('.dropdown-menu').find('.dropdown-item').on('click', function () {
        selectedPriorityICD10 = $(this).data('value');
        $('#prioritas_icd_10').text(selectedPriorityICD10);
    });

    $('#acceptICD10').on('click', function () {
        console.log('Accept clicked'); // Debug log
        console.log('selectedICD10:', selectedICD10); // Debug log
        console.log('selectedPriorityICD10:', selectedPriorityICD10); // Debug log

        // Validasi 1: Cek apakah diagnosa dan prioritas sudah dipilih
        if (!selectedICD10 || !selectedPriorityICD10) {
            console.log('Validation failed: Missing data'); // Debug log
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Pilih Diagnosa dan Prioritas!',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Validasi 2: Cek duplikasi data
        if (isDuplicate('.icd_10 tbody', selectedICD10.code)) {
            console.log('Validation failed: Duplicate data'); // Debug log
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Data sudah ada!',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Validasi 3: Cek primary hanya boleh satu
        if (selectedPriorityICD10 === 'Primary' && hasPrimary('.icd_10 tbody')) {
            console.log('Validation failed: Primary already exists'); // Debug log
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Primary hanya boleh satu.',
                confirmButtonText: 'OK'
            });
            return;
        }

        console.log('All validations passed, adding data'); // Debug log

        // Jika semua validasi berhasil, tambahkan data
        $('.icd_10 tbody').append(generateRow(selectedICD10, selectedPriorityICD10, 'ICD10'));
        resetFields('#icd10', '#kodeICD10', '#prioritas_icd_10');
        selectedICD10 = selectedPriorityICD10 = null;
        updateTableState();
    });

        // ICD9 - dropdown
        $('#icd9').on('change', function () {
            const opt = $(this).find('option:selected');
            selectedICD9 = { code: opt.val(), name: opt.data('nama') };
            $('#kodeICD9').text(selectedICD9.code);

            // Set prioritas langsung ke Secondary tanpa pilihan
            selectedPriorityICD9 = 'Sekunder';
            $('#prioritas_icd_9').text(selectedPriorityICD9); // Opsional, jika kamu ingin tampilkan prioritas
        });

        $('#acceptICD9').on('click', function () {
            if (!selectedICD9) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Pilih Tindakan!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Karena prioritas selalu Secondary, tidak perlu cek selectedPriorityICD9

            if (isDuplicate('.icd_9 tbody', selectedICD9.code)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Data sudah ada!',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Tidak perlu cek Primary untuk ICD9 karena prioritas selalu Secondary

            $('.icd_9 tbody').append(generateRow(selectedICD9, selectedPriorityICD9, 'ICD9'));
            resetFields('#icd9', '#kodeICD9', '#prioritas_icd_9');
            selectedICD9 = null;
            selectedPriorityICD9 = null;
            updateTableState();
        });

        // Hapus baris
        $(document).on('click', '.deleteRow', function () {
            const tr = $(this).closest('tr');
            const id = tr.data('id');
            $(`input[data-id="${id}"]`).remove();
            tr.remove();
            updateTableState();
        });

        // Generate baris
        function generateRow(data, priority, type) {
            const uniqueId = `${type}_${data.code}_${priority}_${Date.now()}`;
            return `
                <tr align="center" data-id="${uniqueId}">
                    <td valign="top" style="border: none;">${data.code}</td>
                    <td valign="top" style="border: none;">${data.name}</td>
                    <td valign="top" style="border: none;">${priority}</td>
                    <td valign="top" style="border: none;">
                        <button type="button" class="btn btn-danger btn-sm deleteRow">Hapus</button>
                    </td>
                    <input type="hidden" name="${type.toLowerCase()}_code[]" value="${data.code}" data-id="${uniqueId}">
                    <input type="hidden" name="${type.toLowerCase()}_name[]" value="${data.name}" data-id="${uniqueId}">
                    <input type="hidden" name="${type.toLowerCase()}_priority[]" value="${priority}" data-id="${uniqueId}">
                </tr>`;
        }





        function resetFields(selectID, kodeID, prioritasID) {
            $(selectID).val('').trigger('change');
            $(kodeID).text(kodeID.includes('10') ? 'KODE ICD 10' : 'KODE ICD 9');
            $(prioritasID).text('Pilih');
        }

        function updateTableState() {
            const icd10Count = $('.icd_10 tbody tr').length;
            const icd9Count = $('.icd_9 tbody tr').not(':first').length;

            $('.isi_10').toggle(icd10Count > 0);
            $('.isi_9').toggle(icd9Count > 0);
            $('.kosong').toggle(icd10Count === 0 && icd9Count === 0);
        }

        // ✅ Fungsi JSON Output dalam format: [{type, priority, code}, ...]
        window.getICDDataAsJSON = function () {
            const result = [];

            $('input[name="icd10_code[]"]').each(function () {
                const id = $(this).data('id');
                const code = $(this).val();
                const priority = $(`input[name="icd10_priority[]"][data-id="${id}"]`).val();

                if (code && priority) {
                    result.push({ type: 'ICD10', priority, code });
                }
            });

            $('input[name="icd9_code[]"]').each(function () {
                const id = $(this).data('id');
                const code = $(this).val();
                const priority = $(`input[name="icd9_priority[]"][data-id="${id}"]`).val();

                if (code && priority) {
                    result.push({ type: 'ICD9', priority, code });
                }
            });

            return result;
        }

        updateTableState();
    });
</script>

{{-- Script jenis alergi --}}
<script>
    function loadAlergiByJenis(kodeJenis, selectedAlergi = null) {
        if (!kodeJenis) {
            $('#alergi').empty().append('<option value="" disabled selected>-- Pilih Data Alergi --</option>');
            return;
        }

        $.ajax({
            url: '/api/alergi/by-jenis/' + kodeJenis,
            method: 'GET',
            success: function(response) {
                const select2 = $('#alergi');
                select2.empty().append('<option value="" disabled>-- Pilih Data Alergi --</option>');

                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(item) {
                        select2.append(`<option value="${item.kode_alergi}">${item.nama_jenis_alergi}</option>`);
                    });

                    if (selectedAlergi) {
                        select2.val(selectedAlergi).trigger('change');
                    }
                } else {
                    select2.append('<option value="00">Tidak ada data</option>');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal memuat data alergi dari server.',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    $(document).ready(function() {
        // Event handler untuk ketika jenis_alergi berubah
        $('#jenis_alergi').on('change', function () {
            const kode = $(this).val();
            loadAlergiByJenis(kode);
        });

        // Saat load data dari server (misal di fungsi loadSoapData)
        // Contoh:
        // $('#jenis_alergi').val(data.jenis_alergi).trigger('change');
        // loadAlergiByJenis(data.jenis_alergi, data.alergi);
    });

</script>

{{-- htt Script --}}
<script>
    $(document).ready(function () {
        const pemeriksaanSelect = $('#htt_pemeriksaan');
        const subSelect = $('#sub-pemeriksaan-select');
        const inputDetail = $('#htt_pemeriksaan_detail');

        function toggleInput() {
            const pemeriksaanValid = pemeriksaanSelect.val() && pemeriksaanSelect.val() !== "-";
            const subValid = subSelect.val() && subSelect.val() !== "";
            inputDetail.prop('disabled', !(pemeriksaanValid && subValid));
        }

        // Ketika pemeriksaan berubah
        pemeriksaanSelect.on('change', function () {
            let id = $(this).val();
            subSelect.empty().append('<option value="">-- Pilih Sub Pemeriksaan --</option>');
            inputDetail.prop('disabled', true); // Nonaktifkan input saat sub di-reset

            if (id && id !== "-") {
                $.ajax({
                    url: '/api/sub-pemeriksaan/' + id,
                    type: 'GET',
                    success: function (data) {
                        data.forEach(function (item) {
                            subSelect.append('<option value="' + item.id + '">' + item.nama_subpemeriksaan + '</option>');
                        });
                        subSelect.trigger('change');
                    },
                    error: function () {
                         Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal mengambil data sub pemeriksaan.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });

        // Aktifkan input hanya jika kedua dropdown sudah terisi
        subSelect.on('change', toggleInput);
    });

    function addDataHtt_Text() {
        const pemeriksaan = $('#htt_pemeriksaan option:selected').text().trim();
        const sub = $('#sub-pemeriksaan-select option:selected').text().trim();
        const detail = $('#htt_pemeriksaan_detail').val().trim();

        if (!pemeriksaan || !sub || !detail || pemeriksaan === '-- Silahkan Pilih --') {
            Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Harap lengkapi semua data terlebih dahulu.',
                            confirmButtonText: 'OK'
            });
            return;
        }

        const summernote = $('#summernote');
        let currentContent = summernote.summernote('code');

        const parser = new DOMParser();
        const doc = parser.parseFromString(currentContent, 'text/html');

        // Cari atau buat <ul> utama
        let ulMain = doc.body.querySelector('ul');
        if (!ulMain) {
            ulMain = doc.createElement('ul');
            doc.body.appendChild(ulMain);
        }

        // ===== PERIKSA LEVEL 1 (PEMERIKSAAN) =====
        let liPemeriksaan = Array.from(ulMain.children).find(li => li.innerText.trim().startsWith(pemeriksaan));
        if (!liPemeriksaan) {
            liPemeriksaan = doc.createElement('li');
            liPemeriksaan.innerHTML = `<strong>${pemeriksaan}</strong>`;
            ulMain.appendChild(liPemeriksaan);
        }

        // ===== PERIKSA LEVEL 2 (SUB) =====
        let ulSub = liPemeriksaan.querySelector('ul');
        if (!ulSub) {
            ulSub = doc.createElement('ul');
            liPemeriksaan.appendChild(ulSub);
        }

        let liSub = Array.from(ulSub.children).find(li => li.innerText.trim().startsWith(sub));
        if (!liSub) {
            liSub = doc.createElement('li');
            liSub.innerText = sub;
            ulSub.appendChild(liSub);
        }

        // ===== PERIKSA LEVEL 3 (DETAIL) =====
        let ulDetail = liSub.querySelector('ul');
        if (!ulDetail) {
            ulDetail = doc.createElement('ul');
            liSub.appendChild(ulDetail);
        }

        // Cek apakah detail sudah ada
        const exists = Array.from(ulDetail.children).some(li => li.innerText.trim() === detail);
        if (!exists) {
            const liDetail = doc.createElement('li');
            liDetail.innerText = detail;
            ulDetail.appendChild(liDetail);
        }

        // Update isi Summernote
        summernote.summernote('code', doc.body.innerHTML);

        // Reset input
        $('#htt_pemeriksaan').val(null).trigger('change');
        $('#sub-pemeriksaan-select').html('<option value="">-- Pilih Sub Pemeriksaan --</option>').trigger('change');
        $('#htt_pemeriksaan_detail').val('');
    }
</script>

{{-- Tensi Script --}}
<script>
    function calculateAge(tanggalLahir) {
        const today = new Date();
        const birthDate = new Date(tanggalLahir);

        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();

        if (today.getDate() < birthDate.getDate()) {
            months--;
        }

        if (months < 0) {
            years--;
            months += 12;
        }

        return { years, months };
    }

    function updateTensi() {
        const sistol = document.getElementById('sistol').value.trim();
        const distol = document.getElementById('distol').value.trim();
        const tanggalLahir = document.getElementById('tanggal_lahir').value.trim();

        if (!tanggalLahir) {
            Swal.fire({
                icon: 'warning',
                title: 'Tanggal Lahir Kosong',
                text: 'Mohon isi tanggal lahir terlebih dahulu.',
            });
            return;
        }

        const { years: tahun } = calculateAge(tanggalLahir);

        // Validasi awal
        if (!sistol || !distol || isNaN(sistol) || isNaN(distol)) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Tidak Valid',
                text: 'Sistol dan Diastol harus diisi dengan angka yang valid.',
            }).then(() => {
                document.getElementById('sistol').value = '';
                document.getElementById('distol').value = '';
                document.getElementById('tensi').value = '';
            });
            return;
        }

        const sistolValue = parseInt(sistol);
        const distolValue = parseInt(distol);
        const tensiValue = `${sistolValue}/${distolValue}`;
        document.getElementById('tensi').value = tensiValue;

        let message = '';
        if (tahun <= 5) {
            if (sistolValue <= 74 || distolValue <= 49)
                message = 'Data Tensi Terdeteksi HIPOTENSI. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 75 && sistolValue <= 100 && distolValue >= 50 && distolValue <= 65)
                message = 'Data Tensi Normal. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 101 || distolValue >= 66)
                message = 'Data Tensi Terdeteksi HIPERTENSI. Apakah Anda ingin melanjutkan?';
        } else if (tahun <= 12) {
            if (sistolValue <= 89 || distolValue <= 59)
                message = 'Data Tensi Terdeteksi HIPOTENSI. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 90 && sistolValue <= 110 && distolValue >= 60 && distolValue <= 75)
                message = 'Data Tensi Normal. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 111 || distolValue >= 76)
                message = 'Data Tensi Terdeteksi HIPERTENSI. Apakah Anda ingin melanjutkan?';
        } else if (tahun <= 17) {
            if (sistolValue <= 89 || distolValue <= 59)
                message = 'Data Tensi Terdeteksi HIPOTENSI. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 90 && sistolValue <= 120 && distolValue >= 60 && distolValue <= 80)
                message = 'Data Tensi Normal. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 121 || distolValue >= 81)
                message = 'Data Tensi Terdeteksi HIPERTENSI. Apakah Anda ingin melanjutkan?';
        } else if (tahun <= 64) {
            if (sistolValue <= 89 || distolValue <= 59)
                message = 'Data Tensi Terdeteksi HIPOTENSI. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 90 && sistolValue <= 120 && distolValue >= 60 && distolValue <= 80)
                message = 'Data Tensi Normal. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 121 || distolValue >= 81)
                message = 'Data Tensi Terdeteksi HIPERTENSI. Apakah Anda ingin melanjutkan?';
        } else if (tahun >= 65) {
            if (sistolValue <= 89 || distolValue <= 59)
                message = 'Data Tensi Terdeteksi HIPOTENSI. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 90 && sistolValue <= 140 && distolValue >= 60 && distolValue <= 90)
                message = 'Data Tensi Normal. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 141 || distolValue >= 91)
                message = 'Data Tensi Terdeteksi HIPERTENSI. Apakah Anda ingin melanjutkan?';
        }

        if (message) {
            Swal.fire({
                icon: 'info',
                title: 'Validasi Tensi',
                text: message,
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Ubah Data'
            }).then((result) => {
                if (!result.isConfirmed) {
                    document.getElementById('sistol').value = '';
                    document.getElementById('distol').value = '';
                    document.getElementById('tensi').value = '';
                }
            });
        }
    }
</script>

{{-- RR Script --}}
<script>
    function calculateAge(tanggalLahir) {
        const today = new Date();
        const birthDate = new Date(tanggalLahir);

        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();

        if (today.getDate() < birthDate.getDate()) {
            months--;
        }

        if (months < 0) {
            years--;
            months += 12;
        }

        return { years, months };
    }

    function validateRR(input) {
        const rrValue = parseInt(input.value.trim());
        const tanggalLahir = document.getElementById('tanggal_lahir').value.trim();

        // Cek input tanggal lahir
        if (!tanggalLahir) {
            Swal.fire({
                icon: 'warning',
                title: 'Tanggal Lahir Kosong',
                text: 'Mohon isi tanggal lahir terlebih dahulu.',
            });
            return;
        }

        const { years: tahun, months: bulan } = calculateAge(tanggalLahir);

        if (isNaN(rrValue)) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Tidak Valid',
                text: 'Mohon masukkan angka Respiratory Rate (RR) yang benar!',
            }).then(() => {
                input.value = '';
                input.focus();
            });
            return;
        }

        let status = '';
        let pesan = '';
        let icon = 'info';

        const checkRange = (min, max) => {
            if (rrValue < min) {
                status = 'RR Terlalu Rendah';
                pesan = `RR Anda (${rrValue}) di bawah batas normal (${min} - ${max})`;
                icon = 'warning';
            } else if (rrValue > max) {
                status = 'RR Terlalu Cepat';
                pesan = `RR Anda (${rrValue}) di atas batas normal (${min} - ${max})`;
                icon = 'warning';
            } else {
                status = 'RR Normal';
                pesan = `RR Anda (${rrValue}) berada dalam rentang normal (${min} - ${max})`;
                icon = 'success';
            }
        };

        if (tahun === 0 && bulan <= 12) checkRange(30, 60);
        else if (tahun >= 1 && tahun <= 2) checkRange(24, 40);
        else if (tahun >= 3 && tahun <= 5) checkRange(22, 34);
        else if (tahun >= 6 && tahun <= 12) checkRange(18, 30);
        else if (tahun >= 13 && tahun <= 17) checkRange(12, 20);
        else if (tahun >= 18 && tahun <= 64) checkRange(18, 24);
        else if (tahun >= 65) checkRange(12, 28);

        Swal.fire({
            icon: icon,
            title: status,
            text: pesan,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Ubah Data',
        }).then((result) => {
            if (!result.isConfirmed) {
                input.value = '';
                input.focus();
            }
        });
    }
</script>

{{-- Suhu Script --}}
<script>
    function validateSuhu(input) {
        let suhuValue = input.value.trim();

        // Cek jika nilai menggunakan koma
        if (suhuValue.includes(',')) {
            Swal.fire({
                icon: 'warning',
                title: 'Format tidak valid',
                text: 'Gunakan titik (.) sebagai pemisah desimal, bukan koma!',
            }).then(() => {
                input.value = '';
                input.focus();
            });
            return;
        }

        const suhuNumber = parseFloat(suhuValue);

        // Validasi angka
        if (isNaN(suhuNumber)) {
            Swal.fire({
                icon: 'warning',
                title: 'Data tidak valid',
                text: 'Mohon masukkan suhu dalam angka yang benar!',
            }).then(() => {
                input.value = '';
                input.focus();
            });
            return;
        }

        // Tentukan kondisi berdasarkan suhu
        let status = '';
        let pesan = '';
        let icon = 'info';

        if (suhuNumber < 34.4) {
            status = 'Hipotermia';
            pesan = 'Suhu tubuh terlalu rendah. Segera konsultasi medis jika perlu.';
            icon = 'error';
        } else if (suhuNumber >= 34.4 && suhuNumber <= 37.4) {
            status = 'Suhu Normal';
            pesan = 'Suhu tubuh Anda berada dalam rentang normal.';
            icon = 'success';
        } else if (suhuNumber >= 37.5 && suhuNumber <= 37.9) {
            status = 'Demam Ringan';
            pesan = 'Kemungkinan terdapat infeksi ringan atau peradangan.';
            icon = 'warning';
        } else if (suhuNumber >= 38 && suhuNumber <= 38.9) {
            status = 'Demam';
            pesan = 'Tubuh sedang melawan infeksi atau peradangan.';
            icon = 'warning';
        } else if (suhuNumber >= 39) {
            status = 'Demam Tinggi';
            pesan = 'Segera konsultasi medis bila gejala berlanjut.';
            icon = 'error';
        }

        // Tampilkan pesan konfirmasi
        Swal.fire({
            icon: icon,
            title: status,
            text: `${pesan} (Suhu: ${suhuNumber}°C)`,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Ubah Data',
        }).then((result) => {
            if (!result.isConfirmed) {
                input.value = '';
                input.focus();
            }
        });
    }
</script>

{{-- SPO2 Script --}}
<script>
    function validateSpO2(input) {
        const spo2Value = parseFloat(input.value.trim());

        // Jika bukan angka
        if (isNaN(spo2Value)) {
            Swal.fire({
                icon: 'warning',
                title: 'SpO2 tidak valid',
                text: 'Mohon masukkan angka yang benar!',
            }).then(() => {
                input.value = '';
                input.focus();
            });
            return;
        }

        // Jika nilai tidak dalam rentang normal
        if (spo2Value < 95 || spo2Value > 100) {
            let pesan = '';

            if (spo2Value < 95) {
                pesan = `SpO2 Anda (${spo2Value}%) terlalu rendah. Normal: 95% - 100%.`;
            } else {
                pesan = `SpO2 Anda (${spo2Value}%) terlalu tinggi. Normal: 95% - 100%.`;
            }

            Swal.fire({
                icon: 'warning',
                title: 'SpO2 Tidak Normal',
                text: pesan,
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Ubah Data',
            }).then((result) => {
                if (!result.isConfirmed) {
                    input.value = '';
                    input.focus();
                }
            });
        } else {
            // Nilai normal, tampilkan notifikasi sukses
            Swal.fire({
                icon: 'success',
                title: 'SpO2 Normal',
                text: `SpO2 Anda (${spo2Value}%) berada dalam rentang normal.`,
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Ubah Data',
            });
        }

    }
</script>

{{-- Nadi Script --}}
<script>
    function calculateAge(tanggalLahir) {
        const today = new Date();
        const birthDate = new Date(tanggalLahir);

        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();

        if (today.getDate() < birthDate.getDate()) {
            months--;
        }

        if (months < 0) {
            years--;
            months += 12;
        }

        return { years, months };
    }

    function validateNadi() {
        const nadiInput = document.getElementById('nadi');
        const tanggalLahir = document.getElementById('tanggal_lahir').value.trim();
        const nadi = parseInt(nadiInput.value.trim());

        if (!tanggalLahir) {
            Swal.fire({
                icon: 'error',
                title: 'Tanggal lahir kosong',
                text: 'Data tanggal lahir tidak tersedia.',
            });
            return;
        }

        if (isNaN(nadi)) {
            Swal.fire({
                icon: 'warning',
                title: 'Nadi tidak valid',
                text: 'Masukkan angka nadi yang benar!',
            }).then(() => {
                nadiInput.value = '';
                nadiInput.focus();
            });
            return;
        }

        const { years, months } = calculateAge(tanggalLahir);

        let rentang = { min: 0, max: 0 };
        if (years === 0 && months <= 12) {
            rentang = { min: 100, max: 160 };
        } else if (years <= 2) {
            rentang = { min: 90, max: 150 };
        } else if (years <= 5) {
            rentang = { min: 80, max: 140 };
        } else if (years <= 10) {
            rentang = { min: 70, max: 130 };
        } else {
            rentang = { min: 60, max: 100 };
        }

        const dalamRentang = nadi >= rentang.min && nadi <= rentang.max;
        const status = dalamRentang ? 'Data Nadi Sesuai' : 'Data Nadi Tidak Sesuai';
        const pesan = dalamRentang
            ? `Nadi Anda (${nadi} bpm) sesuai untuk umur ${years} Tahun ${months} Bulan.`
            : `Nadi Anda (${nadi} bpm) di luar rentang normal (${rentang.min}-${rentang.max} bpm) untuk umur ${years} Tahun ${months} Bulan.`;

        Swal.fire({
            icon: dalamRentang ? 'success' : 'warning',
            title: status,
            text: pesan,
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Ubah Data'
        }).then((result) => {
            if (!result.isConfirmed) {
                nadiInput.value = '';
                nadiInput.focus();
            }
        });
    }

</script>

{{-- BMI Script --}}
<script>
    function validateTB() {
        const tinggiEl = document.getElementById('tinggi');
        const beratEl = document.getElementById('berat');
        const tinggi = tinggiEl.value.trim();
        const berat = beratEl.value.trim();

        // Fungsi untuk reset input
        function resetInputs() {
            tinggiEl.value = '';
            beratEl.value = '';
            tinggiEl.focus();
        }

        if (!tinggi || !berat) return;

        // Cek apakah input tidak kosong dan valid
        const tinggiVal = parseFloat(tinggi);
        const beratVal = parseFloat(berat);
        const inputInvalid = isNaN(tinggiVal) || isNaN(beratVal)  || tinggiVal <= 0 || beratVal <= 0;

        let message = '';

        if (inputInvalid) {
            message = `Data Tinggi / Berat Badan Ada Yang Tidak Sesuai.\nMohon isi yang benar!`;
        } else {
            const tinggiMeter = tinggiVal / 100;
            const bmi = beratVal / (tinggiMeter * tinggiMeter);
            const bmiFixed = bmi.toFixed(2);

            let bmiCategory = '';
            if (bmi < 18.5) {
                bmiCategory = 'Berat badan kurang (Underweight)';
            } else if (bmi < 25) {
                bmiCategory = 'Berat badan normal';
            } else if (bmi < 30) {
                bmiCategory = 'Kelebihan berat badan (Overweight)';
            } else {
                bmiCategory = 'Obesitas';
            }

            document.getElementById("nilai_bmi").value = bmiFixed;
            document.getElementById("status_bmi").value = bmiCategory;

            message = `Data BMI-nya adalah: ${bmiFixed},\nDengan kategori: ${bmiCategory}\nApakah Anda ingin melanjutkan?`;
        }

        Swal.fire({
            title: 'Konfirmasi',
            text: message,
            icon: inputInvalid ? 'warning' : 'info',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Lanjutkan proses jika diperlukan
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                resetInputs();
            }
        });

    }
</script>

{{-- GCS Script --}}
<script>
    $(document).ready(function() {
        // Function to calculate and select "sadar" based on sum of eye, verbal, motorik
        function updateSadarSelection() {
            let eyeScore = parseInt($('#eye').val()) || 0;
            let verbalScore = parseInt($('#verbal').val()) || 0;
            let motorikScore = parseInt($('#motorik').val()) || 0;

            // Calculate total score
            let totalScore = eyeScore + verbalScore + motorikScore;

            // Find and select the option in "sadar" that matches the totalScore
            $('#sadar').val(totalScore).trigger('change');
        }

        // Attach event listeners to each dropdown to trigger the update when value changes
        $('#eye, #verbal, #motorik').on('change', updateSadarSelection);
    });

</script>

{{-- Subjectiv Script --}}
<script>
    let dataArray = [];
    let dataTable;

    $(document).ready(function () {
        dataTable = $('#SubTabel').DataTable({
            paging: false,        // Hilangkan pagination
            searching: false,     // Hilangkan kotak pencarian
            info: false,          // Hilangkan teks "Showing x of y entries"
            ordering: false,      // Opsional: hilangkan fitur urutkan

            columnDefs: [
                { targets: 0, className: 'text-center' },
                { targets: 2, className: 'text-center' }
            ]
        });
    });

    function addData() {
        const penyakit = $('#penyakit').val().trim();
        const durasi = $('#durasi').val().trim();
        const waktu = $('#waktu').val();

        if (!penyakit && !durasi && !waktu) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Semua kolom harus diisi!',
                confirmButtonText: 'OK'
            });
            return;
        }
        if (!penyakit || !durasi || !waktu) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Semua kolom harus diisi!',
                confirmButtonText: 'OK'
            });
            return;
        }

        const index = dataArray.length;
        const newData = { penyakit, durasi, waktu };
        dataArray.push(newData);

        const aksiBtn = `
            <button class="btn btn-warning btn-sm mr-1" onclick="editData(${index})">Edit</button>
            <button class="btn btn-danger btn-sm" onclick="removeData(${index})">Hapus</button>
        `;

        dataTable.row.add([
            index + 1,
            `${penyakit} sejak ${durasi} ${waktu}`,
            aksiBtn
        ]).draw();

        updateHiddenInput();
        resetInputs();
    }

    function removeData(index) {
        dataArray.splice(index, 1);
        dataTable.clear().draw(); // Kosongkan dan render ulang
        dataArray.forEach((item, i) => {
            dataTable.row.add([
                i + 1,
                `${item.penyakit} sejak ${item.durasi} ${item.waktu}`,
                `<button class="btn btn-warning btn-sm mr-1" onclick="editData(${i})">Edit</button>
                 <button class="btn btn-danger btn-sm" onclick="removeData(${i})">Hapus</button>`
            ]);
        });
        dataTable.draw();
        updateHiddenInput();
    }

    function editData(index) {
        const item = dataArray[index];
        $('#penyakit').val(item.penyakit);
        $('#durasi').val(item.durasi);
        $('#waktu').val(item.waktu).trigger('change');

        removeData(index); // Hapus dulu, nanti ditambah ulang setelah diedit
    }

    function updateHiddenInput() {
        $('#tableData').val(JSON.stringify(dataArray));
    }

    function resetInputs() {
        $('#penyakit').val('');
        $('#durasi').val('');
        $('#waktu').val('').trigger('change');
    }
</script>

{{-- sctipt untuk init summernote --}}
<script>
    $(function () {

        $('#summernote').summernote({
            height: 300, // Tentukan tinggi editor (dalam px)
            tabsize: 2,
            disableResizeEditor: true // Menonaktifkan resize editor
        });

        // script untuk dolphi
        $('#summernote2').summernote({
            height: 100,
            tabsize: 2,
            toolbar: [
                ['custom', ['speak']], // Tambahkan tombol AI
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
            buttons: {
                speak: function (context) {
                    var ui = $.summernote.ui;
                    var isRecognizing = false;
                    var recognition;
                    var $button;

                    function createButtonContent(isRecording) {
                        return isRecording
                            ? '<i class="fa fa-stop"></i> Stop Speech'
                            : '<i class="fa fa-microphone"></i> Speech';
                    }

                    var button = ui.button({
                        contents: createButtonContent(false),
                        tooltip: 'Speech to Text',
                        click: function () {
                            if (!('webkitSpeechRecognition' in window)) {
                                Swal.fire('Error', 'Speech Recognition tidak didukung di browser ini.', 'error');
                                return;
                            }

                            if (isRecognizing && recognition) {
                                recognition.stop();
                                isRecognizing = false;
                                $button.html(createButtonContent(false));
                                return;
                            }

                            recognition = new webkitSpeechRecognition();
                            recognition.lang = 'id-ID';
                            recognition.interimResults = false;
                            recognition.continuous = true; // Agar tidak stop otomatis

                            recognition.onstart = function () {
                                isRecognizing = true;
                                $button.html(createButtonContent(true));
                                console.log("🔊 Mulai mendengarkan...");
                            };

                            recognition.onresult = function (event) {
                                let finalTranscript = '';
                                for (let i = event.resultIndex; i < event.results.length; ++i) {
                                    if (event.results[i].isFinal) {
                                        finalTranscript += event.results[i][0].transcript + ' ';
                                    }
                                }
                                if (finalTranscript.trim()) {
                                    // Tambahkan spasi jika teks sebelumnya tidak diakhiri spasi
                                    const editorContent = context.invoke('code');
                                    const needsSpace = editorContent && !editorContent.endsWith(' ');
                                    const textToInsert = (needsSpace ? ' ' : '') + finalTranscript.trim() + ' ';
                                    context.invoke('editor.insertText', textToInsert);
                                    console.log("🎤 Hasil suara:", finalTranscript.trim());
                                }
                            };


                            recognition.onerror = function (event) {
                                console.warn("❗ Speech recognition error:", event.error);
                                if (event.error === 'not-allowed' || event.error === 'service-not-allowed') {
                                    Swal.fire('Error', 'Izin mikrofon ditolak.', 'error');
                                    isRecognizing = false;
                                    $button.html(createButtonContent(false));
                                }
                            };

                            recognition.onend = function () {
                                console.log("🛑 Sesi pengenalan suara selesai.");
                                isRecognizing = false;
                                $button.html(createButtonContent(false));
                            };

                            try {
                                recognition.start();
                            } catch (e) {
                                console.error('Recognition start error:', e);
                            }
                        }
                    });

                    $button = button.render();
                    return $button;
                }
            }
        });

        $('#summernote3').summernote({
            height: 100, // Tentukan tinggi editor (dalam px)
            tabsize: 2
        });

        $('#summernote4').summernote({
            height: 100, // Tentukan tinggi editor (dalam px)
            tabsize: 2
        });

        // sctipt speak to text
        $('#summernote5').summernote({
            height: 100,
            tabsize: 2,
            toolbar: [
                ['custom', ['dolphi']],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
            buttons: {
                dolphi: function (context) {
                    var ui = $.summernote.ui;

                        // Fungsi mengumpulkan seluruh data form medis
                        function collectAllMedicalData() {
                            return {
                                sistol: document.getElementById('sistol')?.value || '',
                                distol: document.getElementById('distol')?.value || '',
                                suhu: document.getElementById('suhu')?.value || '',
                                nadi: document.getElementById('nadi')?.value || '',
                                rr: document.getElementById('rr')?.value || '',
                                tinggi: document.getElementById('tinggi')?.value || '',
                                berat: document.getElementById('berat')?.value || '',
                                spo2: document.getElementById('spo2')?.value || '',
                                jenis_alergi: document.getElementById('jenis_alergi')?.value || '',
                                alergi: document.getElementById('alergi')?.value || '',
                                lingkar_perut: document.getElementById('lingkar_perut')?.value || '',
                                nilai_bmi: document.getElementById('nilai_bmi')?.value || '',
                                status_bmi: document.getElementById('status_bmi')?.value || '',
                                eye: document.getElementById('eye')?.value || '',
                                verbal: document.getElementById('verbal')?.value || '',
                                motorik: document.getElementById('motorik')?.value || '',
                                sadar: document.getElementById('sadar')?.value || '',

                            };
                        }

                        function getKeluhanFormatted() {
                            const rawValue = document.getElementById('tableData')?.value || '[]';
                            let keluhanText = '';

                            try {
                                const keluhanArray = JSON.parse(rawValue);
                                keluhanText = keluhanArray.map(item =>
                                    `Keluhan: ${item.penyakit}, sejak ${item.durasi} ${item.waktu}`
                                ).join('\n- ');
                            } catch (e) {
                                keluhanText = 'Keluhan: (data tidak valid)';
                            }

                            return keluhanText;
                        }

                        function getHTTFromSummernote() {
                            const summernoteElem = $('#summernote');
                            if (!summernoteElem.length) {
                                return 'Tidak ada pemeriksaan';
                            }

                            const html = summernoteElem.summernote('code') || '';
                            const container = $('<div>').html(html);

                            // Fungsi parsing nested list menjadi kalimat
                            function parseInspection(element) {
                                const results = [];

                                $(element).children('li').each(function() {
                                // Level 1 (misal: Kepala)
                                let level1 = '';
                                const firstChild = $(this).contents().get(0);
                                if (firstChild) {
                                    if (firstChild.nodeType === 3) { // text node
                                    level1 = $(this).contents().filter(function() { return this.nodeType === 3; }).first().text().trim();
                                    } else if (firstChild.nodeType === 1 && firstChild.tagName === 'STRONG') {
                                    level1 = $(firstChild).text().trim();
                                    }
                                }

                                // Level 2 (anak <ul><li>)
                                const level2ul = $(this).children('ul').first();
                                if (level2ul.length) {
                                    level2ul.children('li').each(function() {
                                    const level2 = $(this).contents().filter(function() {
                                        return this.nodeType === 3;
                                    }).first().text().trim();

                                    // Level 3 (anak <ul><li>)
                                    const level3ul = $(this).children('ul').first();
                                    if (level3ul.length) {
                                        level3ul.children('li').each(function() {
                                        const level3 = $(this).text().trim();
                                        if (level1 && level2 && level3) {
                                            results.push(`Inspeksi Pemeriksaan ${level1} pada ${level2} di ${level3}`);
                                        }
                                        });
                                    } else {
                                        if (level1 && level2) {
                                        results.push(`Inspeksi Pemeriksaan ${level1} pada ${level2}`);
                                        }
                                    }
                                    });
                                } else {
                                    if (level1) {
                                    results.push(`Inspeksi Pemeriksaan ${level1}`);
                                    }
                                }
                                });

                                return results.join('\n');
                            }

                            // Coba parsing dulu
                            const parsedText = parseInspection(container);

                            if (parsedText) {
                                return parsedText;
                            }

                            // Kalau parsing kosong, fallback ke teks polos
                            const plainText = container.text().trim();
                            return plainText || 'Tidak ada pemeriksaan';
                        }


                        function formatMedicalQuestion(data) {

                            const keluhan = getKeluhanFormatted();
                            const htt = getHTTFromSummernote();


                            return `Berikut adalah data medis pasien:
                            - ${keluhan}
                            - Tensi: ${data.sistol}/${data.distol} mmHg
                            - Suhu: ${data.suhu} °C
                            - Nadi: ${data.nadi} /mnt, RR: ${data.rr} /mnt
                            - Tinggi: ${data.tinggi} cm, Berat: ${data.berat} kg
                            - SpO2: ${data.spo2}
                            - Alergi: ${data.jenis_alergi} - ${data.alergi}
                            - Lingkar Perut: ${data.lingkar_perut} cm
                            - BMI: ${data.nilai_bmi} (${data.status_bmi})
                            - GCS: E${data.eye} V${data.verbal} M${data.motorik} → Kesadaran: ${data.sadar}
                            - Head to Toe: ${htt}

                            Apa kemungkinan diagnosis dan saran tindak lanjut berdasarkan data di atas?`;
                        }

                            // Buat tombol custom
                            var button = ui.button({
                                contents: '<i class="fa fa-stethoscope"></i> Dolphi Health AI',
                                tooltip: 'Tanya AI Kesehatan berdasarkan data medis',
                                click: function () {
                                    const medicalData = collectAllMedicalData();
                                    const question = formatMedicalQuestion(medicalData);

                                    // Kirim pertanyaan ke Flowise API
                                    fetch("https://cloud.flowiseai.com/api/v1/prediction/663a90e4-cfe1-4b54-b78e-5a44bc2c2794", {
                                        method: "POST",
                                        headers: {
                                            Authorization: "Bearer CPJaa0Sj6M-jG2JGDpm2jp2pIH44R1Z3AJHNQs_5EGY",
                                            "Content-Type": "application/json"
                                        },
                                        body: JSON.stringify({ question: question })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        const answer = data.text || JSON.stringify(data);

                                        // Buka jawaban di jendela popup baru
                                        const popupWindow = window.open(
                                            '',
                                            '_blank',
                                            'width=500,height=600,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,status=no'
                                        );

                                        popupWindow.document.write(`
                                            <html>
                                                <head>
                                                    <title>Dolphi Health AI</title>
                                                    <style>
                                                        body {
                                                            font-family: Arial, sans-serif;
                                                            padding: 20px;
                                                            background: #f9f9f9;
                                                        }
                                                        h2 {
                                                            color: #4CAF50;
                                                            margin-top: 0;
                                                        }
                                                        pre {
                                                            white-space: pre-wrap;
                                                            word-wrap: break-word;
                                                            font-size: 14px;
                                                            border: 1px solid #ccc;
                                                            padding: 10px;
                                                            background-color: #fff;
                                                        }
                                                        button.copy-btn {
                                                            margin-top: 10px;
                                                            padding: 8px 12px;
                                                            background-color: #4CAF50;
                                                            color: white;
                                                            border: none;
                                                            border-radius: 4px;
                                                            cursor: pointer;
                                                        }
                                                        button.copy-btn:hover {
                                                            background-color: #45a049;
                                                        }
                                                    </style>
                                                </head>
                                                <body>
                                                    <h2>💡 Jawaban dari Dolphi AI</h2>
                                                    <pre id="answer">${answer}</pre>
                                                    <button class="copy-btn" id="copyBtn">📋 Salin Jawaban</button>
                                                </body>
                                            </html>
                                        `);

                                        popupWindow.document.close();

                                        // Tambahkan script copy setelah dokumen selesai dimuat
                                        popupWindow.onload = function () {
                                            popupWindow.document.getElementById('copyBtn').onclick = function () {
                                                const text = popupWindow.document.getElementById('answer').innerText;

                                                // Fallback method using textarea and execCommand
                                                const textArea = popupWindow.document.createElement('textarea');
                                                textArea.value = text;
                                                popupWindow.document.body.appendChild(textArea);
                                                textArea.select();
                                                try {
                                                    const successful = popupWindow.document.execCommand('copy');
                                                } catch (err) {
                                                    popupWindow.alert('Gagal menyalin jawaban: ' + err);
                                                }
                                                popupWindow.document.body.removeChild(textArea);
                                            };
                                        };


                                    })
                                    .catch(error => {
                                        Swal.fire('Error', 'Gagal mengambil jawaban dari AI: ' + error.message, 'error');
                                    });
                                }
                            });

                    return button.render();
                }
            }
        });
    })
</script>

<script>
    $('#addFormsoap').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            window.location.href = "{{ route('pelayanad.get') }}";
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
</script>

@endsection





