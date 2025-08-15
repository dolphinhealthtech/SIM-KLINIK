@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">SO Perawat</h1>
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
                            <form id="formSoapPerawat" action="{{ route('sopelayana.update') }}" method="POST" enctype="multipart/form-data">
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
                                          <div class="bs-stepper">
                                            <div class="bs-stepper-header" role="tablist">
                                              <!-- your steps here -->
                                              <div class="step" data-target="#Subyektif-part">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="Subyektif-part" id="Subyektif-part-trigger">
                                                  <span class="bs-stepper-circle">1</span>
                                                  <span class="bs-stepper-label">Subyektif</span>
                                                </button>
                                              </div>
                                              <div class="line"></div>
                                              <div class="step" data-target="#Obyektif-part">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="Obyektif-part" id="Obyektif-part-trigger">
                                                  <span class="bs-stepper-circle">2</span>
                                                  <span class="bs-stepper-label">Obyektif</span>
                                                </button>
                                              </div>
                                              <div class="line"></div>
                                              <div class="step" data-target="#htt-part">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="htt-part" id="htt-part-trigger">
                                                  <span class="bs-stepper-circle">3</span>
                                                  <span class="bs-stepper-label">Head To Toe</span>
                                                </button>
                                              </div>
                                            </div>
                                            <div class="bs-stepper-content">

                                              <!-- your steps content here -->
                                              <div id="Subyektif-part" class="content" role="tabpanel" aria-labelledby="Subyektif-part-trigger">
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
                                                        <table class="table table-bordered" id="SubTabel" data-value='{{$pelayanan_soap->tableData}}'>
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 5%; text-align: center;">No</th>
                                                                    <th style="width: 70%">Subyektif</th>
                                                                    <th style="width: 25%; text-align: center;">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                              </div>

                                              {{-- setp ke 2 --}}
                                              <div id="Obyektif-part" class="content" role="tabpanel" aria-labelledby="Obyektif-part-trigger">

                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label>Tensi (mmHg)</label>
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                <input type="text" class="form-control" id="sistol" name="sistol" value="{{$pelayanan_soap->sistol}}">
                                                            </div>
                                                            <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                                <span>/</span> <!-- Menambahkan pemisah / -->
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" class="form-control" id="distol" name="distol" onchange="updateTensi()" value="{{$pelayanan_soap->distol}}">
                                                            </div>
                                                            <input type="hidden" id="tensi" name="tensi">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="suhu">Suhu (°C)</label>
                                                        <input type="text" class="form-control" id="suhu" name="suhu" onchange="validateSuhu(this)" value="{{$pelayanan_soap->suhu}}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="nadi">Nadi (/mnt)</label>
                                                        <input type="text" class="form-control" id="nadi" name="nadi" onchange="validateNadi()" value="{{$pelayanan_soap->nadi}}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="rr">RR (/mnt)</label>
                                                        <input type="text" class="form-control" id="rr" name="rr" onchange="validateRR(this)" value="{{$pelayanan_soap->rr}}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="tinggi">Tinggi (Cm)</label>
                                                        <input type="text" class="form-control" id="tinggi" name="tinggi" onchange="validateTB()" value="{{$pelayanan_soap->tinggi}}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="berat">Berat (/Kg)</label>
                                                        <input type="text" class="form-control" id="berat" name="berat" onchange="validateTB()" value="{{$pelayanan_soap->berat}}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-3">
                                                        <label for="spo2">SpO2</label>
                                                        <input type="text" class="form-control" id="spo2" name="spo2" onchange="validateSpO2(this)" value="{{$pelayanan_soap->spo2}}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Alergi dan jenis</label>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <select class="form-control select2bs4" id="jenis_alergi" name="jenis_alergi">
                                                                    <option value="" disabled {{ $pelayanan_soap->jenis_alergi == '' ? 'selected' : '' }}>-- Pilih --</option>
                                                                    <option value="00" {{ $pelayanan_soap->jenis_alergi == '00' ? 'selected' : '' }}>tidak ada</option>
                                                                    <option value="01" {{ $pelayanan_soap->jenis_alergi == '01' ? 'selected' : '' }}>makanan</option>
                                                                    <option value="02" {{ $pelayanan_soap->jenis_alergi == '02' ? 'selected' : '' }}>obat</option>
                                                                    <option value="03" {{ $pelayanan_soap->jenis_alergi == '03' ? 'selected' : '' }}>udara</option>
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
                                                        <input type="text" class="form-control" id="lingkar_perut" name="lingkar_perut" value="{{$pelayanan_soap->lingkar_perut}}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Data BMI</label>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control" id="nilai_bmi" name="nilai_bmi" readonly value="{{$pelayanan_soap->nilai_bmi}}">
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
                                                            <option value="" disabled {{ is_null($pelayanan_soap->eye) ? 'selected' : '' }}>-- Pilih --</option>
                                                            @foreach ($gsc_eye as $gsc_eyedata)
                                                                <option value="{{ $gsc_eyedata->skor }}" {{ $pelayanan_soap->eye == $gsc_eyedata->skor ? 'selected' : '' }}>
                                                                    {{ $gsc_eyedata->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="verbal">VERBAL</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="verbal" name="verbal">
                                                            <option value="" disabled {{ is_null($pelayanan_soap->verbal) ? 'selected' : '' }}>-- Pilih --</option>
                                                            @foreach ($gcs_verbal as $gcs_verbaldata)
                                                                <option value="{{ $gcs_verbaldata->skor }}"
                                                                    {{ $pelayanan_soap->verbal == $gcs_verbaldata->skor ? 'selected' : '' }}>
                                                                    {{ $gcs_verbaldata->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="motorik">MOTORIK</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="motorik" name="motorik">
                                                            <option value="" disabled {{ is_null($pelayanan_soap->motorik) ? 'selected' : '' }}>-- Pilih --</option>
                                                            @foreach ($gcs_motorik as $gcs_motorikdata)
                                                                <option value="{{ $gcs_motorikdata->skor }}"
                                                                    {{ $pelayanan_soap->motorik == $gcs_motorikdata->skor ? 'selected' : '' }}>
                                                                    {{ $gcs_motorikdata->nama }}
                                                                </option>
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

                                                <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                              </div>

                                              {{-- step ke 3 --}}
                                              <div id="htt-part" class="content" role="tabpanel" aria-labelledby="htt-part-trigger">
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
                                                        <textarea class="form-control" id="summernote" name="summernote" rows="5">
                                                            {{ old('summernote', $pelayanan_soap->summernote ?? '') }}
                                                        </textarea>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                              </div>
                                            </div>
                                          </div>
                                      <!-- /.card -->
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

@include('module.pelayanan.pelayanan-perawat.components.javascript-edit')

@endsection




