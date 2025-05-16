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
                    <div class="col-12">
                        <div class="card">
                            <form action="{{ route('sopelayana.add') }}" method="POST">
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
                                                        <a class="nav-link" id="custom-tabs-four-objectiv-tab" data-toggle="pill" href="#custom-tabs-four-objectiv" role="tab" aria-controls="custom-tabs-four-objectiv" aria-selected="false">Objectiv</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-headtotoe-tab" data-toggle="pill" href="#custom-tabs-four-headtotoe" role="tab" aria-controls="custom-tabs-four-headtotoe" aria-selected="false">Head To Toe</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-assesmen-tab" data-toggle="pill" href="#custom-tabs-four-assesmen" role="tab" aria-controls="custom-tabs-four-assesmen" aria-selected="false">Assesmen</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-diag-tab" data-toggle="pill" href="#custom-tabs-four-diag" role="tab" aria-controls="custom-tabs-four-diag" aria-selected="false">Diagnosisi ICD</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-four-plan-tab" data-toggle="pill" href="#custom-tabs-four-plan" role="tab" aria-controls="custom-tabs-four-plan" aria-selected="false">Plan</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content" id="custom-tabs-four-tabContent">

                                                    <div class="tab-pane fade show active" id="custom-tabs-four-subyektif" role="tabpanel" aria-labelledby="custom-tabs-four-subyektif-tab">
                                                        <div class="form-group">
                                                            <label>Keluhan :</label>
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <input type="text" class="form-control" id="penyakit" placeholde="Masukan Keluhan">
                                                                </div>
                                                                <div class="col-md-5 d-flex align-items-center">
                                                                    <label class="mr-3 mb-0">Sejak</label>
                                                                    <input type="number" class="form-control mr-2" id="durasi" placeholder="Masukkan durasi">
                                                                    <select class="form-control select2bs4" id="waktu" name="waktu">
                                                                        <option value="" disabled selected>-- Pilih Hari --</option>
                                                                        <option value="Hari">Hari</option>
                                                                        <option value="Minggu">Minggu</option>
                                                                        <option value="Bulan">Bulan</option>
                                                                        <option value="Tahun">Tahun</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2 d-flex justify-content-end">
                                                                    <button type="button" class="btn btn-primary" onclick="addData()">Tambahkan</button>
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
                                                                <label for="summernote1">Kesadaran</label>
                                                                <textarea class="form-control" id="summernote1" name="summernote1" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-12">
                                                                <label for="summernote2">Kesadaran</label>
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
                                                                        <button type="button" class="ml-2 btn btn-default" id="kodeICD10">KODE ICD 10</button>
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
                                                                        <button type="button" class="ml-2 btn btn-default" id="kodeICD9">KODE ICD 9</button>
                                                                        <!-- Dropdown Prioritas -->
                                                                        <button type="button" class="ml-2 btn btn-default dropdown-toggle" id="dropdownMenuButtonICD9" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            <span id="prioritas_icd_9" class="caret">Pilih</span>
                                                                        </button>
                                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButtonICD9">
                                                                            <li><a class="dropdown-item" data-value="Primary">Primary</a></li>
                                                                            <li><a class="dropdown-item" data-value="Sekunder">Sekunder</a></li>
                                                                        </ul>
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
                                                                <label for="summernote5">Kesadaran</label>
                                                                <textarea class="form-control" id="summernote5" name="summernote5" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-6">
                                                                <label for="summernote3">Kesadaran</label>
                                                                <textarea class="form-control" id="summernote3" name="summernote3" rows="3"></textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="summernote4">Kesadaran</label>
                                                                <textarea class="form-control" id="summernote4" name="summernote4" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- /.card -->
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
    $(document).ready(function () {
        // Inisialisasi variabel
        let selectedICD10 = null;
        let selectedPriorityICD10 = null;

        // Toggle tampilan card ICD
        $('#toggleICD').on('click', function () {
            $('#icdCard').toggle();
        });

        // Saat ICD-10 dipilih
        $('#icd10').on('change', function () {
            const selectedOption = $(this).find('option:selected');
            selectedICD10 = {
                code: selectedOption.val(),
                name: selectedOption.data('nama')
            };
            $('#kodeICD10').text(selectedICD10.code);
        });

        // Saat prioritas dipilih
        $('#dropdownMenuButtonICD10').next('.dropdown-menu').find('.dropdown-item').on('click', function () {
            selectedPriorityICD10 = $(this).data('value');
            $('#prioritas_icd_10').text(selectedPriorityICD10);
        });

        // Klik tombol "Terima"
        $('#acceptICD10').on('click', function () {
            if (!selectedICD10 || !selectedPriorityICD10) {
                alert('Pilih Diagnosa dan Prioritas!');
                return;
            }

            // Cek apakah sudah ada
            let exists = false;
            $('.icd_10 tbody tr').each(function () {
                if ($(this).find('td:first').text().trim() === selectedICD10.code) {
                    exists = true;
                    return false;
                }
            });

            if (exists) {
                alert('Data sudah ada di tabel.');
                return;
            }

            // Simpan lewat AJAX
            $.ajax({
                url: '{{ route("pendaftaran.add") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',

                    kode: selectedICD10.code,
                    prioritas: selectedPriorityICD10,
                    status_penyakit: $('#status_penyakit').val() || 'lama'
                },
                success: function (response) {
                    if (response.success) {
                        const newRow = `
                            <tr align="center" data-id="${response.id}">
                                <td valign="top">${selectedICD10.code}</td>
                                <td valign="top">${selectedICD10.name}</td>
                                <td valign="top">${selectedPriorityICD10}</td>
                                <td valign="top">
                                    <button type="button" class="btn btn-danger btn-sm deleteICD10">Hapus</button>
                                </td>
                            </tr>
                        `;
                        $('.icd_10 tbody').append(newRow);
                        $(document).trigger('rowAdded');
                        resetFieldsICD10();
                    } else {
                        alert('Gagal: ' + response.message);
                    }
                },
                error: function (xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseText);
                }
            });
        });

        // Hapus ICD-10
        $(document).on('click', '.deleteICD10', function () {
            const row = $(this).closest('tr');
            const id = row.data('id');

            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: '{{ route("pendaftaran.add") }}',
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function (response) {
                        if (response.success) {
                            row.remove();
                            $(document).trigger('rowRemoved');
                        } else {
                            alert('Gagal: ' + response.message);
                        }
                    },
                    error: function (xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            }
        });

        // Reset semua input ICD10
        function resetFieldsICD10() {
            $('#icd10').val('').trigger('change');
            $('#kodeICD10').text('KODE ICD 10');
            $('#prioritas_icd_10').text('Pilih');
            selectedICD10 = null;
            selectedPriorityICD10 = null;
        }

        // Toggle tampilan tabel berdasarkan data
        function toggleTableVisibility() {
            const icd10Rows = $(".icd_10 tbody tr").not(".kosong").length;
            const icd9Rows = $(".icd_9 tbody tr").not(".kosong").length;
            const kosongRow = $(".kosong");

            icd10Rows === 0 ? $(".isi_10").hide() : $(".isi_10").show();
            icd9Rows === 0 ? $(".isi_9").hide() : $(".isi_9").show();

            if (icd10Rows === 0 && icd9Rows === 0) {
                kosongRow.show();
            } else {
                kosongRow.hide();
            }
        }

        // Jalankan saat pertama kali
        toggleTableVisibility();

        // Trigger ulang saat data berubah
        $(document).on('rowAdded rowRemoved', function () {
            toggleTableVisibility();
        });

        // Trigger hapus dari tombol hapus ICD-10/9
        $(document).on('click', '.deleteICD10, .deleteICD9', function () {
            $(this).closest('tr').remove();
            $(document).trigger('rowRemoved');
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#jenis_alergi').on('change', function () {
            const kode = $(this).val();

            if (kode) {
                $.ajax({
                    url: '/api/alergi/by-jenis/' + kode,
                    method: 'GET',
                    success: function(response) {
                        const select2 = $('#alergi');
                        select2.empty().append('<option value="" disabled selected>-- Pilih Data Alergi --</option>');

                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function(item) {
                                select2.append(`<option value="${item.kode_alergi}">${item.nama_jenis_alergi}</option>`);
                            });
                        } else {
                            select2.append('<option value="00">Tidak ada data</option>');
                        }
                    },
                    error: function() {
                        alert('Gagal memuat data alergi dari server.');
                    }
                });
            }
        });
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
                        alert('Gagal mengambil data sub pemeriksaan.');
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
            alert('Harap lengkapi semua data terlebih dahulu.');
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
            alert("Semua kolom harus diisi!");
            return;
        }
        if (!penyakit || !durasi || !waktu) {
            alert("Semua kolom harus diisi!");
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

{{-- BS-Stepper --}}
<script>
    $(function () {
        $('#summernote').summernote({
            height: 300, // Tentukan tinggi editor (dalam px)
            tabsize: 2,
            disableResizeEditor: true // Menonaktifkan resize editor
        });
        $('#summernote1').summernote({
            height: 100, // Tentukan tinggi editor (dalam px)
            tabsize: 2
        });
        $('#summernote2').summernote({
            height: 100, // Tentukan tinggi editor (dalam px)
            tabsize: 2
        });
        $('#summernote3').summernote({
            height: 100, // Tentukan tinggi editor (dalam px)
            tabsize: 2
        });
        $('#summernote4').summernote({
            height: 100, // Tentukan tinggi editor (dalam px)
            tabsize: 2
        });
        $('#summernote5').summernote({
            height: 100, // Tentukan tinggi editor (dalam px)
            tabsize: 2
        });
    })
</script>


@endsection
