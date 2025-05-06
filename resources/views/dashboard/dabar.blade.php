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
                            <div class="card-header">
                                <h3 class="card-title">Data Barang</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#adddabarModal" >
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('dabar.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importdabarModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                    <!-- Tombol Sinkron (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#singkrondabarModal">
                                        <i class="fas fa-file-upload"></i> Singkron
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="dabartabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Nama Barang</th>
                                            <th class="text-center">Nama Industri</th>
                                            <th class="text-center">Jenis Formularium</th>
                                            <th class="text-center">Jenis Generik</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dabar as $dabardata)
                                            <tr>
                                                <td class="text-center">{{ $dabardata->kode_barang }}</td>
                                                <td class="text-center">{{ $dabardata->nama_barang }}</td>
                                                <td class="text-center">{{ $dabardata->nama_industri_barang }}</td>
                                                <td class="text-center">{{ $dabardata->jenis_formularium }}</td>
                                                <td class="text-center">{{ $dabardata->jenis_obat }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-dabar"
                                                        data-toggle="modal" data-id="{{ $dabardata->id }}"
                                                        data-nama_barang="{{ $dabardata->nama_barang }}"
                                                        data-jenis_formularium="{{ $dabardata->jenis_formularium }}"
                                                        data-kfa_kode="{{ $dabardata->kfa_kode }}"
                                                        data-nama_industri_barang="{{ $dabardata->nama_industri_barang }}"
                                                        data-satuan_kecil="{{ $dabardata->satuan_kecil }}"
                                                        data-nilai_satuan_kecil="{{ $dabardata->nilai_satuan_kecil }}"
                                                        data-satuan_sedang="{{ $dabardata->satuan_sedang }}"
                                                        data-nilai_satuan_sedang="{{ $dabardata->nilai_satuan_sedang }}"
                                                        data-satuan_besar="{{ $dabardata->satuan_besar }}"
                                                        data-tempat_penyimpanan="{{ $dabardata->tempat_penyimpanan }}"
                                                        data-barcode="{{ $dabardata->barcode }}"
                                                        data-gudang_kategori="{{ $dabardata->gudang_kategori }}"
                                                        data-jenis_obat="{{ $dabardata->jenis_obat }}"
                                                        data-jenis_generik="{{ $dabardata->jenis_generik }}"
                                                        data-bentuk_sediaan="{{ $dabardata->bentuk_sediaan }}"
                                                        data-target="#editdabarModal">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>

                                                    <a href="#" class="btn btn-danger btn-sm delete-data-goldar"
                                                        data-toggle="modal"data-id="{{ $dabardata->id }}"
                                                        data-nama-dabar="{{ $dabardata->nama_barang }}"
                                                        data-target="#deletedabarModal">
                                                        <i class="fas fa-trash"></i> Delete
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

<!-- Modal Add Gudang Barang Input -->
<div class="modal fade" id="adddabarModal" tabindex="-1" role="dialog" aria-labelledby="adddabarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adddabarModalLabel">Input Gudang Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormdabar" action="{{ route('dabar.store') }}" method="POST">
                    @csrf
                    <div class="bs-stepper">
                        <div class="bs-stepper-header" role="tablist">
                            <!-- Step 1: Informasi Umum Barang -->
                            <div class="step" data-target="#informasi-umum">
                                <button type="button" class="step-trigger" role="tab" aria-controls="informasi-umum" id="informasi-umum-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Informasi Umum</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <!-- Step 2: Satuan dan Nilai Satuan -->
                            <div class="step" data-target="#satuan-dan-nilai">
                                <button type="button" class="step-trigger" role="tab" aria-controls="satuan-dan-nilai" id="satuan-dan-nilai-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Satuan dan Nilai</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <!-- Step 3: Penyimpanan dan Informasi Lainnya -->
                            <div class="step" data-target="#penyimpanan-dan-informasi">
                                <button type="button" class="step-trigger" role="tab" aria-controls="penyimpanan-dan-informasi" id="penyimpanan-dan-informasi-trigger">
                                    <span class="bs-stepper-circle">3</span>
                                    <span class="bs-stepper-label">Penyimpanan dan Lainnya</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <!-- Step 1: Informasi Umum Barang -->
                            <div id="informasi-umum" class="content" role="tabpanel" aria-labelledby="informasi-umum-trigger">
                                <div class="row">
                                    <input type="hidden" class="form-control" id="kode_barang" name="kode_barang">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_barang">Nama Barang</label>
                                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode_kfa">Kode KFA</label>
                                            <input type="text" class="form-control" id="kode_kfa" name="kode_kfa" readonly value="001">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jenis_formularium">Jenis Formularium</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="jenis_formularium" name="jenis_formularium">
                                                <option value="" disabled selected>Pilih Jenis Formularium</option>
                                                <option value="Formularium">Formularium</option>
                                                <option value="Non Formularium">Non Formularium</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="industri_barang">Industri Barang</label>
                                            <input type="text" class="form-control" id="industri_barang" name="industri_barang" readonly value="PT 123">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="jenis_obat">Jenis Obat</label>
                                        <div class="form-group d-flex" id="jenis_obat_container" style="width: 100%;">
                                            <select class="form-control select2bs4" id="jenis_obat" name="jenis_obat" style="width: 100%;">
                                                <option value="" disabled selected>Pilih Jenis Obat</option>
                                                <option value="Generik">Generik</option>
                                                <option value="Non Generik">Non Generik</option>
                                            </select>

                                            <input type="text" class="form-control ml-2" id="jenis_generik" name="jenis_generik" placeholder="Masukkan jenis generik" style="display: none; width: 100%;">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                            </div>

                            <!-- Step 2: Satuan dan Nilai Satuan -->
                            <div id="satuan-dan-nilai" class="content" role="tabpanel" aria-labelledby="satuan-dan-nilai-trigger">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="satuan_kecil">Satuan Kecil</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="satuan_kecil" name="satuan_kecil">
                                                <option value="" disabled selected>Pilih Satuan Kecil</option>
                                                @foreach ($satuan as $satuanKecil)
                                                    <option value="{{ $satuanKecil->nama }}">{{ $satuanKecil->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nilai_satuan_kecil">Nilai Satuan Kecil</label>
                                            <div class="d-flex align-items-center">
                                                <input type="number" class="form-control flex-grow-1 mr-2" id="nilai_satuan_kecil" name="nilai_satuan_kecil" placeholder="Masukkan nilai satuan kecil">
                                                <label id="label_satuan_kecil" class="text-nowrap">Dalam 1</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="satuan_sedang">Satuan Sedang</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="satuan_sedang" name="satuan_sedang">
                                                <option value="" disabled selected>Pilih Satuan Sedang</option>
                                                @foreach ($satuan as $satuanSedang)
                                                    <option value="{{ $satuanSedang->nama }}">{{ $satuanSedang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nilai_satuan_sedang">Nilai Satuan Sedang</label>
                                            <div class="d-flex align-items-center">
                                                <input type="number" class="form-control flex-grow-1 mr-2" id="nilai_satuan_sedang" name="nilai_satuan_sedang" placeholder="Masukkan nilai satuan sedang">
                                                <label id="label_satuan_sedang" class="text-nowrap">Dalam 1</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="satuan_besar">Satuan Besar</label>
                                            <select class="form-control select2bs4" id="satuan_besar" name="satuan_besar">
                                                <option value="" disabled selected>Pilih Satuan Besar</option>
                                                @foreach ($satuan as $satuanBesar)
                                                    <option value="{{ $satuanBesar->nama }}">{{ $satuanBesar->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                            </div>

                            <!-- Step 3: Penyimpanan dan Informasi Lainnya -->
                            <div id="penyimpanan-dan-informasi" class="content" role="tabpanel" aria-labelledby="penyimpanan-dan-informasi-trigger">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tempat_penyimpanan">Tempat Penyimpanan</label>
                                            <input type="text" class="form-control" id="tempat_penyimpanan" name="tempat_penyimpanan" placeholder="Masukkan tempat penyimpanan">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Masukkan barcode">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="barang_kategori">Kategori Barang</label>
                                            <select class="form-control select2bs4" id="barang_kategori" name="barang_kategori">
                                                <option value="" disabled selected>Pilih Kategori Barang</option>
                                                @foreach ($kategori as $kategoriData)
                                                    <option value="{{ $kategoriData->nama }}">{{ $kategoriData->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bentuk_sediaan">Bentuk Sediaan</label>
                                            <select class="form-control select2bs4" id="bentuk_sediaan" name="bentuk_sediaan">
                                                <option value="" disabled selected>Pilih Bentuk Sediaan</option>
                                                <option value="Padat">Padat</option>
                                                <option value="Cair">Cair</option>
                                                <option value="Gas">Gas</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Gudang Barang Input -->
<div class="modal fade" id="editdabarModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Gudang Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormdabar" action="{{ route('dabar.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="dabarid_edit" name="dabarid_edit">
                    <div class="bs-stepper">
                        <div class="bs-stepper-header" role="tablist">
                            <!-- Step 1: Informasi Umum Barang -->
                            <div class="step" data-target="#informasi-umum-edit">
                                <button type="button" class="step-trigger" role="tab" aria-controls="informasi-umum-edit" id="informasi-umum-edit-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Informasi Umum</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <!-- Step 2: Satuan dan Nilai Satuan -->
                            <div class="step" data-target="#satuan-dan-nilai-edit">
                                <button type="button" class="step-trigger" role="tab" aria-controls="satuan-dan-nilai-edit" id="satuan-dan-nilai-edit-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Satuan dan Nilai</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <!-- Step 3: Penyimpanan dan Informasi Lainnya -->
                            <div class="step" data-target="#penyimpanan-dan-informasi-edit">
                                <button type="button" class="step-trigger" role="tab" aria-controls="penyimpanan-dan-informasi-edit" id="penyimpanan-dan-informasi-edit-trigger">
                                    <span class="bs-stepper-circle">3</span>
                                    <span class="bs-stepper-label">Penyimpanan dan Lainnya</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <!-- Step 1: Informasi Umum Barang -->
                            <div id="informasi-umum-edit" class="content" role="tabpanel" aria-labelledby="informasi-umum-edit-trigger">
                                <div class="row">
                                    <input type="hidden" class="form-control" id="kode_barang" name="kode_barang">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_barang_edit">Nama Barang</label>
                                            <input type="text" class="form-control" id="nama_barang_edit" name="nama_barang_edit" placeholder="Masukkan nama barang">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode_kfa_edit">Kode KFA</label>
                                            <input type="text" class="form-control" id="kode_kfa_edit" name="kode_kfa_edit" readonly value="001">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jenis_formularium_edit">Jenis Formularium</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="jenis_formularium_edit" name="jenis_formularium_edit">
                                                <option value="" disabled selected>Pilih Jenis Formularium</option>
                                                <option value="Formularium">Formularium</option>
                                                <option value="Non Formularium">Non Formularium</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="industri_barang_edit">Industri Barang</label>
                                            <input type="text" class="form-control" id="industri_barang_edit" name="industri_barang_edit" readonly value="PT 123">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="jenis_obat_edit">Jenis Obat</label>
                                        <div class="form-group d-flex" id="jenis_obat_edit_container" style="width: 100%;">
                                            <select class="form-control select2bs4" id="jenis_obat_edit" name="jenis_obat_edit" style="width: 100%;">
                                                <option value="" disabled selected>Pilih Jenis Obat</option>
                                                <option value="Generik">Generik</option>
                                                <option value="Non Generik">Non Generik</option>
                                            </select>

                                            <input type="text" class="form-control ml-2" id="jenis_generik_edit" name="jenis_generik_edit" placeholder="Masukkan jenis generik" style="display: none; width: 100%;">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                            </div>

                            <!-- Step 2: Satuan dan Nilai Satuan -->
                            <div id="satuan-dan-nilai-edit" class="content" role="tabpanel" aria-labelledby="satuan-dan-nilai-edit-trigger">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="satuan_kecil_edit">Satuan Kecil</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="satuan_kecil_edit" name="satuan_kecil_edit">
                                                <option value="" disabled selected>Pilih Satuan Kecil</option>
                                                @foreach ($satuan as $satuanKecil)
                                                    <option value="{{ $satuanKecil->nama }}">{{ $satuanKecil->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nilai_satuan_kecil_edit">Nilai Satuan Kecil</label>
                                            <div class="d-flex align-items-center">
                                                <input type="number" class="form-control flex-grow-1 mr-2" id="nilai_satuan_kecil_edit" name="nilai_satuan_kecil_edit" placeholder="Masukkan nilai satuan kecil">
                                                <label id="label_satuan_kecil_edit" class="text-nowrap">Dalam 1</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="satuan_sedang_edit">Satuan Sedang</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="satuan_sedang_edit" name="satuan_sedang_edit">
                                                <option value="" disabled selected>Pilih Satuan Sedang</option>
                                                @foreach ($satuan as $satuanSedang)
                                                    <option value="{{ $satuanSedang->nama }}">{{ $satuanSedang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nilai_satuan_sedang_edit">Nilai Satuan Sedang</label>
                                            <div class="d-flex align-items-center">
                                                <input type="number" class="form-control flex-grow-1 mr-2" id="nilai_satuan_sedang_edit" name="nilai_satuan_sedang_edit" placeholder="Masukkan nilai satuan sedang">
                                                <label id="label_satuan_sedang_edit" class="text-nowrap">Dalam 1</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="satuan_besar_edit">Satuan Besar</label>
                                            <select class="form-control select2bs4" id="satuan_besar_edit" name="satuan_besar_edit">
                                                <option value="" disabled selected>Pilih Satuan Besar</option>
                                                @foreach ($satuan as $satuanBesar)
                                                    <option value="{{ $satuanBesar->nama }}">{{ $satuanBesar->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                            </div>

                            <!-- Step 3: Penyimpanan dan Informasi Lainnya -->
                            <div id="penyimpanan-dan-informasi-edit" class="content" role="tabpanel" aria-labelledby="penyimpanan-dan-informasi-edit-trigger">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tempat_penyimpanan_edit">Tempat Penyimpanan</label>
                                            <input type="text" class="form-control" id="tempat_penyimpanan_edit" name="tempat_penyimpanan_edit" placeholder="Masukkan tempat penyimpanan">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="barcode_edit">Barcode</label>
                                            <input type="text" class="form-control" id="barcode_edit" name="barcode_edit" placeholder="Masukkan barcode">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="barang_kategori_edit">Kategori Barang</label>
                                            <select class="form-control select2bs4" id="barang_kategori_edit" name="barang_kategori_edit">
                                                <option value="" disabled selected>Pilih Kategori Barang</option>
                                                @foreach ($kategori as $kategoriData)
                                                    <option value="{{ $kategoriData->nama }}">{{ $kategoriData->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bentuk_sediaan_edit">Bentuk Sediaan</label>
                                            <select class="form-control select2bs4" id="bentuk_sediaan_edit" name="bentuk_sediaan_edit">
                                                <option value="" disabled selected>Pilih Bentuk Sediaan</option>
                                                <option value="Padat">Padat</option>
                                                <option value="Cair">Cair</option>
                                                <option value="Gas">Gas</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                <button type="submit" class="btn btn-primary">Perbarui</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- modal Delete Role --}}
<div class="modal fade" id="deletedabarModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormdabar" action="{{ route('dabar.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Master Data dabar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="dabarid_delete" name="dabarid_delete">
                    <div id="deleteTextdabar"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importdabarModal" tabindex="-1" role="dialog" aria-labelledby="importdabarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importdabarModalLabel">Import Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('dabar.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Pilih File Excel</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-import"></i> Import
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Singkron -->
<div class="modal fade" id="singkrondabarModal" tabindex="-1" role="dialog" aria-labelledby="singkrondabarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="singkrondabarModalLabel">Singkron Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="singkronDabar">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12" id="containerExternal">
                            <label for="external_database">Pilih Gudang</label>
                            <select class="form-control select2bs4" style="width: 100%;" id="external_database" name="external_database">
                                <option value="" disabled selected>Pilih Satuan Kecil</option>
                                @foreach ($singkron as $datasingkron)
                                    <option value="{{ $datasingkron->id }}">{{ $datasingkron->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <div id="loadingContainer" class="progress" style="display: none; height: 25px;">
                                <div id="loadingBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 0%;">
                                    <span id="loadingText" style="font-weight: bold; color: white; display: block; text-align: center;">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Tambah</button> <!-- Submit button -->
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        function resetModal() {
            $("#loadingContainer").show();         // Sembunyikan loading bar
            $("#loadingBar").css("width", "0%");   // Reset progress ke 0%
            $("#loadingText").text("0%");          // Reset teks ke 0%
            $(".btn-info").prop("disabled", false).text("Tambah"); // Aktifkan kembali tombol

            $("#singkronDabar")[0].reset(); // Reset form input
        }

        // Reset progress bar saat modal dibuka
        $("#singkrondabarModal").on("show.bs.modal", function () {
            $("#loadingContainer").hide(); // Tampilkan loading saat modal dibuka
            $("#loadingBar").css("width", "0%"); // Reset ke 0%
            $("#loadingText").text("0%"); // Reset teks ke 0%
        });

        $("#singkronDabar").submit(function (e) {
            e.preventDefault(); // Mencegah submit form default

            const id_db = $("#external_database").val();

            // Tampilkan loading bar dan reset ke 0%
            $("#loadingContainer").show();
            $("#containerExternal").hide();
            $("#loadingBar").css("width", "0%");
            $("#loadingText").text("0%");
            $(".btn-info").prop("disabled", true).text("Menambahkan...");

            // Simulasi animasi progress dari 0% hingga 100% sebelum AJAX dijalankan
            let progress = 0;
            let interval = setInterval(function () {
                progress += 10; // Tambah 10% setiap 300ms
                $("#loadingBar").css("width", progress + "%");
                $("#loadingText").text(progress + "%"); // Update teks

                if (progress >= 100) {
                    clearInterval(interval); // Hentikan animasi
                    $("#loadingText").text("Complete"); // Ubah teks jadi "Complete"

                    // ** Setelah 100%, jalankan AJAX request **
                    $.ajax({
                        url: "{{ route('dabar.singkron', ['id' => '__ID__']) }}".replace('__ID__', id_db),
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                $('#singkrondabarModal').modal('hide');
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
                            }).then(() => {
                                // **🔹 Reset modal ke kondisi awal setelah OK ditekan**
                                resetModal();
                            });

                        }
                    });
                }
            }, 300); // Setiap 300ms, naik 10%
        });
    });
</script>

<script>
    // Global Scope
    let stepper;

        $(document).ready(function() {
            $("#dabartabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#dabartabel_wrapper .col-md-6:eq(0)');
        });

        $(document).ready(function() {
            $('#adddabarModal').on('shown.bs.modal', function () {
                stepper = new Stepper(document.querySelector('#adddabarModal .bs-stepper'));

                $.ajax({
                    url: '/api/generate-kode-data-barang', // Pastikan sesuai dengan route API-mu
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#kode_barang').val(response.kode_barang); // Isi hidden input
                        }
                    },
                    error: function() {
                        console.error("Gagal generate kode barang");
                    }
                });
            });
        });


        $('#addFormdabar').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#adddabarModal').modal('hide');
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
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        let errorList = '';

                        // Hapus class 'is-invalid' dari semua input dulu (optional, biar bersih)
                        $('#addFormdabar').find('.is-invalid').removeClass('is-invalid');

                        Object.entries(xhr.responseJSON.errors).forEach(([key, value]) => {
                            errorList += `- ${value[0]}<br>`;
                            $(`#${key}`).addClass('is-invalid'); // Tambahkan class error ke input
                        });

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal!',
                            html: `Terdapat beberapa input yang belum valid:<br><br>${errorList}`,
                        });
                    } else {
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
                }
            });
        });

        $(document).ready(function() {
            $('#editdabarModal').on('shown.bs.modal', function () {
                stepper = new Stepper(document.querySelector('#editdabarModal .bs-stepper'));
            });
        })

        $(document).on('click', '.edit-data-dabar', function() {
            const data = $(this).data();

            $('#dabarid_edit').val(data.id);
            $('#nama_barang_edit').val(data.nama_barang);
            $('#kode_kfa_edit').val(data.kfa_kode);
            $('#jenis_formularium_edit').val(data.jenis_formularium).trigger('change');
            $('#industri_barang_edit').val(data.nama_industri_barang);
            $('#jenis_obat_edit').val(data.jenis_obat).trigger('change');
            $('#jenis_generik_edit').val(data.jenis_generik);

            $('#satuan_kecil_edit').val(data.satuan_kecil).trigger('change');
            $('#nilai_satuan_kecil_edit').val(data.nilai_satuan_kecil);
            $('#satuan_sedang_edit').val(data.satuan_sedang).trigger('change');
            $('#nilai_satuan_sedang_edit').val(data.nilai_satuan_sedang);
            $('#satuan_besar_edit').val(data.satuan_besar).trigger('change');

            $('#tempat_penyimpanan_edit').val(data.tempat_penyimpanan);
            $('#barcode_edit').val(data.barcode);
            $('#barang_kategori_edit').val(data.gudang_kategori).trigger('change');
            $('#bentuk_sediaan_edit').val(data.bentuk_sediaan).trigger('change');

            // Tampilkan input jenis_generik jika jenis_obat = 'Generik'
            if (data.jenis_obat === 'Generik') {
                $('#jenis_generik_edit').show();
            } else {
                $('#jenis_generik_edit').hide();
            }
        });

        $('#editFormdabar').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editdabarModal').modal('hide');
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
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        let errorList = '';

                        // Bersihkan error sebelumnya
                        $('#editFormdabar').find('.is-invalid').removeClass('is-invalid');

                        // Loop tiap error dan tampilkan
                        $.each(xhr.responseJSON.errors, function(key, messages) {
                            // Ambil elemen input berdasarkan ID
                            $('#' + key).addClass('is-invalid');

                            // Gabungkan semua pesan error (jika lebih dari 1)
                            messages.forEach(msg => {
                                errorList += `- ${msg}<br>`;
                            });
                        });

                        // Tampilkan dengan SweetAlert
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal!',
                            html: `Terdapat beberapa kesalahan pengisian:<br><br>${errorList}`,
                            confirmButtonText: 'Periksa Kembali',
                        });

                    } else {
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
                }
            });
        });

        $(document).on('click', '.delete-data-goldar', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-dabar');

            $('#dabarid_delete').val(id);
            $('#deleteTextdabar').html(
            `<span>Apa Anda yakin ingin menghapus data dabar <b>${name}</b> ?</span>`);
        });

        $('#deleteFormdabar').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deletedabarModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Goldar!',
                    });
                }
            });
        });
</script>

{{-- Tambahan --}}
<script>
    $(document).ready(function () {
        // Jika pakai Select2, gunakan event dari jQuery
        $('#satuan_kecil').on('change', function () {
            const value = $(this).val();
            $('#label_satuan_kecil').text(value + ' Dalam 1');
        });

        $('#satuan_sedang').on('change', function () {
            const value = $(this).val();
            $('#label_satuan_sedang').text(value + ' Dalam 1');
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Jika pakai Select2, gunakan event dari jQuery
        $('#satuan_kecil_edit').on('change', function () {
            const value = $(this).val();
            $('#label_satuan_kecil_edit').text(value + ' Dalam 1');
        });

        $('#satuan_sedang_edit').on('change', function () {
            const value = $(this).val();
            $('#label_satuan_sedang_edit').text(value + ' Dalam 1');
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#jenis_obat').change(function() {
            var selectedValue = $(this).val();  // Mendapatkan nilai yang dipilih
            var jenisGenerikInput = $('#jenis_generik');
            var jenisObatSelect = $('#jenis_obat');

            if (selectedValue === 'Generik') {
                // Ketika Generik dipilih
                $('#jenis_obat_container').css('display', 'flex');  // Memastikan kontainer tetap flex
                jenisObatSelect.css('width', '0%');  // Set lebar select menjadi 50%
                jenisGenerikInput.show().css('width', '100%');  // Menampilkan input teks untuk jenis generik dan set lebar menjadi 50%
            } else {
                // Ketika Non Generik dipilih
                jenisObatSelect.css('width', '100%');  // Set lebar select menjadi 100%
                jenisGenerikInput.hide();  // Menyembunyikan input teks
                jenisGenerikInput.val('Non Generic');  // Memberikan nilai "Non Generic" pada input ketika Non Generik dipilih
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#jenis_obat_edit').change(function () {
            var selectedValue = $(this).val();
            var jenisGenerikInput = $('#jenis_generik_edit');
            var jenisObatSelect = $('#jenis_obat_edit');

            if (selectedValue === 'Generik') {
                $('#jenis_obat_edit_container').css('display', 'flex');
                jenisObatSelect.css('width', '0%');
                jenisGenerikInput.show().css('width', '100%');
                jenisGenerikInput.val('');
            } else {
                jenisObatSelect.css('width', '100%');
                jenisGenerikInput.hide();
                jenisGenerikInput.val('Non Generic');
            }
        });
    });
</script>

@endsection
