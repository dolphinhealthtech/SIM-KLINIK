@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pembelian Obat / Alkes</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        {{-- <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard v1</li>
                        </ol> --}}
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
                            <div class="card-body">
                                <form id="addFormpembelian" action="{{ route('pembelian.add') }}" method="POST">
                                    @csrf
                                    <div class="bs-stepper">
                                        <div class="bs-stepper-header" role="tablist">
                                            <!-- your steps here -->
                                            <div class="step" data-target="#data-awal">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="data-awal" id="data-awal-trigger">
                                                    <span class="bs-stepper-circle">1</span>
                                                    <span class="bs-stepper-label">Data Awal Pembelian</span>
                                                </button>
                                            </div>

                                            <div class="line"></div>

                                            <div class="step" data-target="#data-pembelian">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="data-pembelian" id="data-pembelian-trigger">
                                                    <span class="bs-stepper-circle">2</span>
                                                    <span class="bs-stepper-label">Data Pembelian</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="bs-stepper-content">
                                            <!-- your steps content here -->
                                            <div id="data-awal" class="content" role="tabpanel" aria-labelledby="data-awal-trigger">
                                                <div class="row">
                                                    <input type="hidden" id="data_json_tabel" name="data_json_tabel">

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="nomor_faktur">No Faktur</label>
                                                            <input type="text" class="form-control" id="nomor_faktur" name="nomor_faktur" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="d-flex align-items-center">
                                                                <label for="supplierCheck" class="mr-2 mb-0">Supplier</label>
                                                                <input type="checkbox" id="supplierCheck" name="supplierCheck" onclick="toggle_supplier()" style="margin-left: 5px;">
                                                            </div>
                                                            <div class="d-flex align-items-center mt-2">
                                                                <div id="supplier_select_wrapper" style="width: 100%;">
                                                                    <select class="form-control select2bs4 mt-2" style="width: 100%;" id="supplier_select" name="supplier_select">
                                                                        <option value="" disabled selected>Pilih Supplier</option>
                                                                        @foreach ($supplier as $supplierData)
                                                                            <option value="{{ $supplierData->nama }}">{{ $supplierData->nama }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <input type="text" class="form-control" id="supplier_input" name="supplier_input" placeholder="Masukan nama perusahaan supplier" style="display: none;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="d-flex align-items-center">
                                                                <label for="no_po_spCheck" class="mr-2 mb-0">No. PO / SP</label>
                                                                <input type="checkbox" id="no_po_spCheck" name="no_po_spCheck" onclick="toggle_no_po_sp()" style="margin-left: 5px;">
                                                            </div>
                                                            <div class="d-flex align-items-center mt-2">
                                                                <input type="text" class="form-control" id="no_po_sp" name="no_po_sp" placeholder="Masukan nomor PO / SP">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="no_faktur_supplier">No. Faktur Supplier</label>
                                                            <input type="text" class="form-control" id="no_faktur_supplier" name="no_faktur_supplier">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="d-flex align-items-center">
                                                                <label for="tanggal_terima_barangCheck" class="mr-2 mb-0">Tanggal Terima Barang</label>
                                                                <input type="checkbox" id="tanggal_terima_barangCheck" name="tanggal_terima_barangCheck" onclick="toggle_tanggal_terima_barang()" style="margin-left: 5px;">
                                                            </div>
                                                            <div class="d-flex align-items-center mt-2">
                                                                <input type="date" class="form-control" id="tanggal_terima_barang" name="tanggal_terima_barang">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tanggal_faktur">Tanggal Faktur</label>
                                                            <input type="date" class="form-control" id="tanggal_faktur" name="tanggal_faktur">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tanggal_jatuh_tempo" class="mr-2 mb-0">Tanggal Jatuh Tempo Pembayaran</label>
                                                            <div class="d-flex align-items-center mt-2">
                                                                <div class="input-group">
                                                                    <!-- Tombol untuk Date Range Picker -->
                                                                    <div class="input-group-prepend">
                                                                        <button type="button" class="btn btn-default" id="range_tanggal_jatuh_tempo" style="font-size: 0.9rem;">
                                                                            <i class="far fa-calendar-alt"></i> Date
                                                                            <i class="fas fa-caret-down"></i>
                                                                        </button>
                                                                    </div>

                                                                    <!-- Input untuk menampilkan rentang tanggal yang disabled, menempel pada tombol -->
                                                                    <input type="text" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" readonly style="font-size: 0.9rem;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="pajak_ppn">Pajak / PPN</label>
                                                            <input type="text" class="form-control" id="pajak_ppn" name="pajak_ppn">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="metode_hna">Metode HNA</label>
                                                            <select class="form-control select2bs4" style="width: 100%;" id="metode_hna" name="metode_hna">
                                                                <option value="" disabled selected>Pilih Supplier</option>
                                                                <option value="1">Tanpa PPN Dan Diskon</option>
                                                                <option value="2">Dengan PPN</option>
                                                                <option value="3">Dengan Diskon</option>
                                                                <option value="4">Dengan PPN Dan Diskon</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary" onclick="validateDataAwal()">Selanjutnya</button>
                                                {{-- <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button> --}}
                                            </div>
                                            <div id="data-pembelian" class="content" role="tabpanel" aria-labelledby="data-pembelian-trigger">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="nama_obat_alkes">Nama Obat / Alkes</label>
                                                                <select class="form-control select2bs4 mt-2" style="width: 100%;" id="nama_obat_alkes" name="nama_obat_alkes">
                                                                    <option value="" disabled selected>Pilih Obat/Alkes</option>
                                                                    @foreach ($dabar as $dabarData)
                                                                        <option value="{{ $dabarData->nama_barang }}"
                                                                            data-kode-barang="{{ $dabarData->kode_barang }}"
                                                                            data-nilai-kecil="{{ $dabarData->nilai_satuan_kecil }}">
                                                                            {{ $dabarData->nama_barang }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group row align-items-center">
                                                                <!-- Checkbox -->
                                                                <div class="col-md-1">
                                                                    <input type="checkbox" id="kemasan_kecil_besarCheck" onclick="toggleKemasanLabel()">
                                                                </div>

                                                                <!-- Label Dinamis -->
                                                                <div class="col-md-11">
                                                                    <label for="kemasan_kecil_besarCheck" id="kemasanLabel" class="mb-0">Kemasan Kecil</label>
                                                                </div>
                                                            </div>

                                                            <!-- Satuan Kecil -->
                                                            <div id="formKecil" style="display: block;">
                                                                <!-- Nilai Satuan Kecil -->
                                                                <div class="form-group row align-items-center">
                                                                    <div class="col-md-3">
                                                                        <label for="nilai_satuan_kecil">Satuan Kecil</label>
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <input type="text" class="form-control" id="nilai_satuan_kecil" name="nilai_satuan_kecil">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <span class="form-text">Kemasan Kecil</span>
                                                                    </div>
                                                                </div>

                                                                <!-- Harga Satuan Kecil -->
                                                                <div class="form-group row align-items-center">
                                                                    <div class="col-md-3">
                                                                        <label for="harga_satuan_kecil">Harga Satuan</label>
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <input type="text" class="form-control" id="harga_satuan_kecil" name="harga_satuan_kecil">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <span class="form-text" id="harga_satuan_kecil_sebelumnya"></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Satuan Besar -->
                                                            <div id="formBesar" style="display: none;">
                                                                <!-- Nilai Satuan Besar -->
                                                                <div class="form-group row align-items-center">
                                                                    <div class="col-md-3">
                                                                        <label for="nilai_satuan_besar">Satuan Besar</label>
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <input type="text" class="form-control" id="nilai_satuan_besar" name="nilai_satuan_besar">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <span class="form-text">Kemasan Besar</span>
                                                                    </div>
                                                                </div>

                                                                <!-- Harga Satuan Besar -->
                                                                <div class="form-group row align-items-center">
                                                                    <div class="col-md-3">
                                                                        <label for="harga_satuan_besar">Harga Satuan</label>
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <input type="text" class="form-control" id="harga_satuan_besar" name="harga_satuan_besar">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <span class="form-text" id="harga_satuan_besar_sebelumnya"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="d-flex align-items-center">
                                                                    <label for="diskon_toggle" class="mr-2 mb-0">Diskon (Persen/Rupiah)</label>
                                                                    <input type="checkbox" id="diskon_toggle" name="diskon_toggle" onclick="toggle_diskon()" style="margin-left: 5px;">
                                                                </div>

                                                                <!-- Persen -->
                                                                <div class="d-flex align-items-center mt-2">
                                                                    <input type="text" class="form-control" id="diskon_persen" name="diskon_persen" placeholder="Masukan persentase diskonnya !">
                                                                    <input type="text" class="form-control" id="diskon_rupiah" name="diskon_rupiah" style="display: none;" placeholder="Masukan nilai rupiah diskonnya !">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="tgl_expired">Tanggal Expired</label>
                                                                <input type="date" class="form-control" id="tgl_expired" name="tgl_expired">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="no_batch">No Batch</label>
                                                                <input type="text" class="form-control" id="no_batch" name="no_batch" placeholder="Masukan no batch">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group row">
                                                                <div class="col-md-6">
                                                                    <button type="button" onclick="deleteSelectedRows()" class="btn btn-danger btn-block">Hapus</button>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <button type="button" onclick="addNewDataToTabel()" class="btn btn-primary btn-block">Tambah Data Sementara</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div style="border: 2px solid black; padding: 10px; width: 100%; max-width: auto; min-height: 300px;">
                                                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                                                <table class="table" id="dataTable" style="border: none;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No</th>
                                                                            <th>Nama Item</th>
                                                                            <th>Qty</th>
                                                                            <th>Harga Satuan</th>
                                                                            <th>Disc</th>
                                                                            <th>Exp</th>
                                                                            <th>Batch</th>
                                                                            <th>Sub Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        {{-- DATA TERISI OTOMATIS NANTI --}}
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <br>

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        {{-- SUB TOTAL --}}
                                                                        <div class="col-md-4">
                                                                            <h5 style="font-weight: bold;">Sub Total</h5>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <h5 style="font-weight: bold;">:</h5>
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <h5 style="font-weight: bold" id="sub_total_keseluruhan"></h5>
                                                                            <input type="hidden" id="sub_total_keseluruhan_input" name="sub_total_keseluruhan_input">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        {{-- TOTAL DISKON --}}
                                                                        <div class="col-md-4">
                                                                            <h5 style="font-weight: bold; color: red;">Total Diskon</h5>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <h5 style="font-weight: bold; color: red;">:</h5>
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <h5 style="font-weight: bold" id="diskon_total_keseluruhan"></h5>
                                                                            <input type="hidden" id="diskon_total_keseluruhan_input" name="diskon_total_keseluruhan_input">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        {{-- PPN TOTAL --}}
                                                                        <div class="col-md-4">
                                                                            <h5 style="font-weight: bold; color: blue;">PPN</h5>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <h5 style="font-weight: bold; color: blue;">:</h5>
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <h5 style="font-weight: bold" id="ppn_total_keseluruhan"></h5>
                                                                            <input type="hidden" id="ppn_total_keseluruhan_input" name="ppn_total_keseluruhan_input">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row align-items-center">
                                                                        <div class="col-md-3">
                                                                            <h5 class="mb-0" style="font-weight:bold; color: green;">Materai : </h5>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <select class="form-control select2bs4" style="width: 100%;" id="materai" name="materai">
                                                                                <option value="" disabled selected>Pilih :</option>
                                                                                <option value="0">0</option>
                                                                                <option value="3000">3.000</option>
                                                                                <option value="6000">6.000</option>
                                                                                <option value="10000">10.000</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <h5 class="mb-0" style="font-weight:bold; color: rgb(42, 197, 245);">Koreksi : </h5>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="text" class="form-control" id="koreksi" name="koreksi" value="0" maxlength="3" pattern="\d{1,3}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3);">
                                                                        </div>
                                                                    </div>

                                                                    {{-- GARIS YEEE --}}
                                                                    <div style="width: 100%; border-top: 2px solid black; position: relative; margin-top: 20px;">
                                                                        <span style="position: absolute; right: -10px; top: -15px; font-weight: bold;">+</span>
                                                                    </div>

                                                                    <br>

                                                                    <div class="form-group row">
                                                                        <div class="col-md-4">
                                                                            <h4 style="font-weight: bold">Total</h4>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <h4 style="font-weight: bold">:</h4>
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <h4 style="font-weight: bold" id="total_keseluruhan"></h4>
                                                                            <input type="hidden" id="total_keseluruhan_input" name="total_keseluruhan_input">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="penerima_barang">Penerima Barang/Obat/Alkes</label>
                                                                        <select class="form-control select2bs4 mt-2" style="width: 100%;" id="penerima_barang" name="penerima_barang">
                                                                            <option value="" disabled selected>Pilih Supplier</option>
                                                                            @foreach ($user as $userData)
                                                                                <option value="{{ $userData->name }}">{{ $userData->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    <br><br><br><br><br><br>
                                                                    <div class="form-group row">
                                                                        <div class="col-md-6">
                                                                            <button type="button" class="btn btn-primary btn-block" onclick="stepper.previous()">Sebelumnya</button>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <button type="submit" id="btnSimpanCetak" class="btn btn-info btn-block">Simpan & Cetak</button>
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
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>No</label>
                                <input type="text" class="form-control" id="editNo" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Kode Item</label>
                                <input type="text" class="form-control" id="editKodeBarang" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama Item</label>
                                <input type="text" class="form-control" id="editNama" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Qty</label>
                                <input type="text" class="form-control" id="editQty">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Harga Satuan</label>
                                <input type="text" class="form-control" id="editHargaSatuan">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Disc</label>
                                <input type="text" class="form-control" id="editDisc">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Exp</label>
                                <input type="date" class="form-control" id="editExp">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Batch</label>
                                <input type="text" class="form-control" id="editBatch">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sub Total</label>
                                <input type="text" class="form-control" id="editSubTotal" disabled>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveEdit()">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT ADD --}}
    <script>
        $(document).ready(function() {
            // Tambahkan logging untuk debugging
            console.log('Document ready, setting up form handler');

            // Tangani submit form dengan ID yang benar
            $('#addFormpembelian').on('submit', function(e) {
                e.preventDefault();
                console.log('Form submitted');

                // Nonaktifkan tombol untuk mencegah klik ganda
                $('#btnSimpanCetak').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                // Ambil data form
                var formData = $(this).serialize();

                // Kirim data ke server
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log('Success response:', response);

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil disimpan!',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // Buka PDF di tab baru tanpa mengganti URL saat ini
                            setTimeout(function() {
                                // Buka PDF di tab baru
                                window.open('{{ url("/pembelian/cetak") }}/' + response.data.nomor_faktur, '_blank');

                                // Reset form dan kembali ke awal
                                $('#addFormpembelian')[0].reset();

                                // Reset semua data
                                resetAllData();

                                // Ambil nomor faktur baru
                                $.ajax({
                                    url: '/api/generate-faktur-pembelian',
                                    method: 'GET',
                                    success: function(response) {
                                        if (response.success) {
                                            $('#nomor_faktur').val(response.kode_faktur);
                                        }
                                    }
                                });

                                // Reset stepper ke langkah pertama
                                stepper.to(1);

                                // Aktifkan kembali tombol simpan
                                $('#btnSimpanCetak').prop('disabled', false).text('Simpan & Cetak');
                            }, 1500);
                        } else {
                            $('#btnSimpanCetak').prop('disabled', false).text('Simpan & Cetak');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Terjadi kesalahan saat menyimpan data!'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', xhr.responseText);
                        $('#btnSimpanCetak').prop('disabled', false).text('Simpan & Cetak');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menyimpan data!'
                        });
                    }
                });
            });
        });
    </script>

{{-- SCRIPT GLOBAL --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            window.stepper = new Stepper(document.querySelector(".bs-stepper"));

            // Mask untuk persen (maksimal 3 digit)
            Inputmask({
                alias: "numeric",
                suffix: '%',
                min: 0,
                max: 100,
                rightAlign: false,
                placeholder: "",
                allowMinus: false
            }).mask("#diskon_persen, #editDisc");

            Inputmask({
                alias: "numeric",
                groupSeparator: ".",
                radixPoint: ",",
                autoGroup: true,
                digitsOptional: true,
                digits: 0,
                placeholder: "",
                prefix: "Rp ",
                rightAlign: false,
                removeMaskOnSubmit: true
            }).mask("#diskon_rupiah, #harga_satuan_kecil, #harga_satuan_besar, #editHargaSatuan");

            @if (is_null($settingHarga))
                Swal.fire({
                    icon: 'warning',
                    title: 'Pengaturan Harga Kosong',
                    text: 'Silakan atur terlebih dahulu setting harga sebelum melanjutkan pembelian.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('setharga.get') }}";
                    }
                });
            @endif
        });

        function validateDataAwal() {
            const container = document.querySelector('#data-awal');
            const inputs = container.querySelectorAll('input, select, textarea');
            let unfilled = [];

            inputs.forEach(input => {
                const type = input.type;
                const isVisible = input.offsetParent !== null;

                // --- CEK KHUSUS: Supplier Select/Input
                if (input.id === 'supplier_select' || input.id === 'supplier_input') {
                    const supplierSelect = document.querySelector('#supplier_select');
                    const supplierInput = document.querySelector('#supplier_input');

                    const selectVal = supplierSelect && supplierSelect.value.trim();
                    const inputVal = supplierInput && supplierInput.value.trim();

                    // Jika salah satu sudah terisi, skip keduanya
                    if (selectVal !== '' || inputVal !== '') {
                        return; // skip validasi untuk supplier
                    }

                    // Kalau belum terisi dua-duanya dan input ini visible, maka masuk unfilled
                    if (isVisible) {
                        const name = 'Supplier';
                        if (!unfilled.includes(name)) unfilled.push(name);
                    }

                    return; // skip ke input berikutnya
                }

                // --- CEK UMUM
                if (
                    !input.disabled &&
                    type !== 'hidden' &&
                    type !== 'button' &&
                    !input.readOnly &&
                    isVisible &&
                    input.value.trim() === ''
                ) {
                    const label = container.querySelector(`label[for="${input.id}"]`);
                    const name = label ? label.innerText.trim() : input.name || input.id;
                    unfilled.push(name);
                }
            });

            if (unfilled.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Input Belum Lengkap!',
                    html: `
                        <p>Mohon lengkapi input berikut sebelum melanjutkan:</p>
                        <ul style="text-align: left;">
                            ${unfilled.map(item => `<li><strong>${item}</strong></li>`).join('')}
                        </ul>
                    `,
                    confirmButtonText: 'OK'
                });
            } else {
                stepper.next();
            }
        }

        $(document).ready(function() {
            // Reset data awal
            resetAllData();

            // Ketika halaman atau modal terbuka, ambil nomor faktur terbaru
            $.ajax({
                url: '/api/generate-faktur-pembelian',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        // Isi input nomor faktur dengan nomor yang dihasilkan
                        $('#nomor_faktur').val(response.kode_faktur);
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan dalam mengambil nomor faktur.'
                    });
                }
            });
        });
    </script>

{{-- SCRIPT DATA AWAL PEMBELIAN --}}

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Event listener untuk input tanggal_terima_barang
            document.getElementById('tanggal_terima_barang').addEventListener('click', function() {
                this.showPicker();
            });

            // Event listener untuk input tanggal_faktur
            document.getElementById('tanggal_faktur').addEventListener('click', function() {
                this.showPicker();
            });

            // Event listener untuk input tgl_expired
            document.getElementById('tgl_expired').addEventListener('click', function() {
                this.showPicker();
            });

            // Event listener untuk input editExp
            document.getElementById('editExp').addEventListener('click', function() {
                this.showPicker();
            });

            $('#range_tanggal_jatuh_tempo').daterangepicker(
                {
                    ranges   : {
                        'Today'       : [moment(), moment()],
                        'Tomorrow'    : [moment().add(1, 'days'), moment().add(1, 'days')],
                        'Next 7 Days' : [moment(), moment().add(6, 'days')],
                        'Next 30 Days': [moment(), moment().add(29, 'days')],
                        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                        'Next Month'  : [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')]
                    },
                    startDate: moment(),  // Set awal ke "Today"
                    endDate  : moment(),  // Set akhir ke "Today"
                    locale: {
                        format: 'D MMMM, YYYY'  // Format tanggal yang diinginkan
                    },
                    autoUpdateInput: false // Agar input tidak otomatis diperbarui
                },
                function (start, end, label) {
                    // Set nilai input dengan rentang tanggal yang dipilih
                    $('#tanggal_jatuh_tempo').val(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
                }
            );

            // Set nilai awal untuk input tanggal saat halaman dimuat (opsional)
            $('#tanggal_jatuh_tempo').val(
                moment().format('D MMMM, YYYY') + ' - ' + moment().format('D MMMM, YYYY')
            );

            const input = document.getElementById('pajak_ppn');
            const im = new Inputmask("percentage", {
                suffix: '%',
                rightAlign: false,
                min: 0,
                max: 100
            });
            im.mask(input);

        });

        document.addEventListener("DOMContentLoaded", function () {
            toggle_tanggal_terima_barang();
            toggleKemasanLabel();
            toggle_diskon();
        });

        function toggle_supplier() {
            const isChecked = document.getElementById("supplierCheck").checked;
            const supplierSelectWrapper = document.getElementById("supplier_select_wrapper");
            const supplierInput = document.getElementById("supplier_input");

            if (isChecked) {
                $('#supplier_select').select2('destroy');
                supplierSelectWrapper.style.visibility = "hidden";
                supplierSelectWrapper.style.position = "absolute";
                supplierInput.style.display = "block";
            } else {
                supplierInput.value = "";
                supplierInput.style.display = "none";
                supplierSelectWrapper.style.visibility = "visible";
                supplierSelectWrapper.style.position = "relative";

                $('#supplier_select').select2({
                    theme: "bootstrap4",
                    dropdownParent: $('#supplier_select').parent()
                });
            }
        }

        function toggle_no_po_sp() {
            const checkbox = document.getElementById("no_po_spCheck");
            const input = document.getElementById("no_po_sp");

            if (checkbox.checked) {
                input.value = "KONSINYASI";
                input.setAttribute("readonly", true);
            } else {
                input.value = "";
                input.removeAttribute("readonly");
            }
        }

        function toggle_tanggal_terima_barang() {
            const checkbox = document.getElementById("tanggal_terima_barangCheck");
            const input = document.getElementById("tanggal_terima_barang");

            // Selalu set tanggal hari ini sebagai default
            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const yyyy = today.getFullYear();
            const formattedDate = yyyy + '-' + mm + '-' + dd;  // Format: yyyy-mm-dd

            // Set nilai tanggal hari ini
            input.value = formattedDate;

            // Jika checkbox tidak dicentang, set input menjadi readonly
            if (!checkbox.checked) {
                // Tambahkan atribut readonly
                input.setAttribute("readonly", true);

                // Tambahkan class untuk styling (opsional)
                input.classList.add("bg-light");

                // Nonaktifkan event click untuk mencegah date picker muncul
                $(input).off('click').on('click', function(e) {
                    e.preventDefault();
                    return false;
                });
            } else {
                // Hapus atribut readonly
                input.removeAttribute("readonly");

                // Hapus class styling (opsional)
                input.classList.remove("bg-light");

                // Aktifkan kembali event click untuk memunculkan date picker
                $(input).off('click').on('click', function() {
                    this.showPicker();
                });

                // Fokus ke input dan tampilkan date picker
                setTimeout(function() {
                    input.focus();
                    input.showPicker();
                }, 100);
            }
        }

    // SCRIPT DATA PEMBELIAN
        function toggleKemasanLabel() {
            const isChecked = document.getElementById("kemasan_kecil_besarCheck").checked;

            document.getElementById("kemasanLabel").textContent = isChecked ? "Kemasan Besar" : "Kemasan Kecil";

            // Toggle tampilan form
            document.getElementById("formKecil").style.display = isChecked ? "none" : "block";
            document.getElementById("formBesar").style.display = isChecked ? "block" : "none";
        }

        function toggle_diskon() {
            const isChecked = document.getElementById("diskon_toggle").checked;
            const persenInput = document.getElementById("diskon_persen");
            const rupiahInput = document.getElementById("diskon_rupiah");

            if (isChecked) {
                persenInput.style.display = "none";
                rupiahInput.style.display = "block";
                persenInput.value = ""; // Reset persen jika beralih ke rupiah
            } else {
                rupiahInput.style.display = "none";
                persenInput.style.display = "block";
                rupiahInput.value = ""; // Reset rupiah jika beralih ke persen
            }
        }

            //Menghitung satuan besar dan kecil
        $(document).ready(function () {
            let nilaiKonversi = 1;

            $('#nama_obat_alkes').on('change', function () {
                const selectedOption = $(this).find('option:selected');
                nilaiKonversi = parseInt(selectedOption.data('nilai-kecil')) || 1;
            });

            $('#nilai_satuan_besar').on('input', function () {
                const nilaiBesar = parseInt($(this).val()) || 0;
                const hasilKecil = nilaiBesar * nilaiKonversi;
                $('#nilai_satuan_kecil').val(hasilKecil);
            });

            $('#harga_satuan_besar').on('input', function () {
                // Ambil nilai dari input harga satuan besar dan bersihkan formatnya (hapus "Rp", titik, dan koma)
                const rawHargaBesar = $(this).val().replace(/[Rp\s.]/g, '').replace(',', '.');
                const hargaBesar = parseFloat(rawHargaBesar) || 0;

                // Ambil nilai satuan kecil
                const nilaiKecil = parseInt($('#nilai_satuan_kecil').val()) || 1;

                // Menghitung harga satuan kecil berdasarkan pembagian
                const hargaKecil = hargaBesar / nilaiKecil;

                // Menampilkan hasil harga satuan kecil dengan format yang benar
                $('#harga_satuan_kecil').val(hargaKecil.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));
            });
        });

    </script>

{{-- SCRIPT TABEL --}}
    <script>
        $(document).ready(function () {
            $('#materai, #koreksi').on('input change', function () {
                hitungTotalKeseluruhan();
            });
        });

       // Tambahkan fungsi cetak_data() di sini
        function cetak_data() {
            // Ambil nomor faktur
            const nomorFaktur = $('#nomor_faktur').val();

            if (!nomorFaktur) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Nomor faktur tidak boleh kosong!'
                });
                return;
            }

            // Buka halaman cetak PDF dengan download langsung
            window.location.href = '{{ url("/pembelian/cetak") }}/' + nomorFaktur;
        }

        function toggleRowSelection(row) {
            row.classList.toggle('selected-row');
        }

        // Tambahkan CSS ke dalam head
        const style = document.createElement('style');
        style.innerHTML = `
            .selected-row {
                background-color: #007bff !important;
                color: white;
            }
        `;
        document.head.appendChild(style);

        let currentRow = null;

        function toggleRowSelection(row) {
            // Hapus kelas 'selected-row' dari baris yang sedang terpilih (jika ada)
            if (currentRow && currentRow !== row) {
                currentRow.classList.remove("selected-row");
            }

            // Toggle kelas 'selected-row' pada baris yang baru dipilih
            row.classList.toggle("selected-row");

            // Update currentRow agar baris yang terakhir dipilih menjadi yang terpilih
            if (row.classList.contains("selected-row")) {
                currentRow = row;
            } else {
                currentRow = null;
            }
        }

        function openEditModal(row) {
            currentRow = row;
            const cells = row.getElementsByTagName('td');
            document.getElementById('editNo').value = cells[0].innerText;
            document.getElementById('editNama').value = cells[1].innerText;
            document.getElementById('editQty').value = cells[2].innerText;
            document.getElementById('editHargaSatuan').value = cells[3].innerText;
            document.getElementById('editDisc').value = cells[4].innerText;
            document.getElementById('editExp').value = cells[5].innerText;
            document.getElementById('editBatch').value = cells[6].innerText;
            document.getElementById('editSubTotal').value = cells[7].innerText;

            document.getElementById('editKodeBarang').value = cells[8].innerText;

            $('#editModal').modal('show');
        }

        $('#editQty, #editHargaSatuan').on('input keyup change blur', function () {
            updateEditSubTotal();
        });

        function updateEditSubTotal() {
            let qty = parseFloat($('#editQty').val()) || 0;
            let harga = $('#editHargaSatuan').val().replace(/[Rp\s.]/g, '').replace(',', '.');
            let hargaParsed = parseFloat(harga) || 0;

            let subTotal = qty * hargaParsed;
            let formatted = 'Rp ' + subTotal.toLocaleString('id-ID', { minimumFractionDigits: 0 });
            $('#editSubTotal').val(formatted);
        }

        function saveEdit() {
            if (currentRow) {
                const cells = currentRow.getElementsByTagName('td');
                cells[0].innerText = document.getElementById('editNo').value;
                cells[1].innerText = document.getElementById('editNama').value;
                cells[2].innerText = document.getElementById('editQty').value;
                cells[3].innerText = document.getElementById('editHargaSatuan').value;
                cells[4].innerText = document.getElementById('editDisc').value;
                cells[5].innerText = document.getElementById('editExp').value;
                cells[6].innerText = document.getElementById('editBatch').value;
                cells[7].innerText = document.getElementById('editSubTotal').value;
                cells[8].innerText = document.getElementById('editKodeBarang').value;

                $('#editModal').modal('hide');

                hitungTotalSubTotalRupiah();
                hitungTotalDiskon();
                hitungTotalPPN();
                updateHiddenJsonInput();
            }
        }

        function deleteSelectedRows() {
            document.querySelectorAll('.selected-row').forEach(row => row.remove());

            hitungTotalSubTotalRupiah();
            hitungTotalDiskon();
            hitungTotalPPN();
            updateHiddenJsonInput();
        }

        function addNewDataToTabel() {
            let namaObat = $('#nama_obat_alkes').val();
            let nilaiKecil = parseFloat($('#nilai_satuan_kecil').val());
            let rawHargaKecil = $('#harga_satuan_kecil').val();
            let diskonPersen = $('#diskon_persen').val();
            let diskonRupiah = $('#diskon_rupiah').val();
            let tglExpired = $('#tgl_expired').val();
            let noBatch = $('#no_batch').val();
            let kodeBarang = $('#nama_obat_alkes option:selected').data('kode-barang');

            let hargaKecil = parseFloat(rawHargaKecil.replace(/[Rp\s.]/g, '').replace(',', '.'));
            let subTotal = nilaiKecil * hargaKecil;
            let diskonBaru = (diskonPersen || diskonRupiah || '0').toString().trim();
            let expBaru = new Date(tglExpired).toISOString().split('T')[0];

            let foundMatch = null;
            let differences = [];

            $('#dataTable tbody tr').each(function () {
                let kode = $(this).find('.kode-barang-hidden').text().trim();
                if (kode === kodeBarang) {
                    let existingNama = $(this).find('td:eq(1)').text().trim();
                    let existingHarga = parseFloat($(this).find('td:eq(3)').text().replace(/[Rp\s.]/g, '').replace(',', '.'));
                    let existingDiskon = $(this).find('td:eq(4)').text().trim();
                    let expLamaRaw = $(this).find('td:eq(5)').text().trim();
                    let expLama = new Date(expLamaRaw).toISOString().split('T')[0];

                    let isSameNama = existingNama === namaObat;
                    let isSameHarga = existingHarga === hargaKecil;
                    let isSameDiskon = existingDiskon === diskonBaru;
                    let isSameExp = expLama === expBaru;

                    if (isSameNama && isSameHarga && isSameDiskon && isSameExp) {
                        foundMatch = $(this);
                        return false; // break
                    } else {
                        if (!isSameNama) differences.push("Nama Obat");
                        if (!isSameHarga) differences.push("Harga Satuan");
                        if (!isSameDiskon) differences.push("Diskon");
                        if (!isSameExp) differences.push("Tanggal Expired");
                    }
                }
            });

            if (foundMatch) {
                Swal.fire({
                    title: "Data Sama Ditemukan",
                    text: "Barang sudah ada di tabel. Tambahkan jumlah Qty saja?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Ya, tambahkan Qty",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let existingQty = parseFloat(foundMatch.find('td:eq(2)').text());
                        let updatedQty = existingQty + nilaiKecil;
                        let updatedSubTotal = updatedQty * hargaKecil;
                        let subTotalRupiah = 'Rp ' + updatedSubTotal.toLocaleString('id-ID', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        })
                        foundMatch.find('td:eq(2)').text(updatedQty);
                        foundMatch.find('td:eq(7)').text(subTotalRupiah);
                        hitungTotalSubTotalRupiah();
                        hitungTotalDiskon();
                        hitungTotalPPN();
                    }
                });
            } else if (differences.length > 0) {
                Swal.fire({
                    title: "Data Serupa Sudah Ada",
                    html: "Data dengan kode barang ini sudah ada dalam tabel.<br><b>Perbedaan terdeteksi pada:</b><br><ul>" +
                        differences.map(item => `<li>${item}</li>`).join('') + "</ul>",
                    icon: "warning"
                });
            } else {
                let subTotalRupiah = 'Rp ' + subTotal.toLocaleString('id-ID');
                const newRow = `
                    <tr onclick="toggleRowSelection(this)" ondblclick="openEditModal(this)">
                        <td>${$('#dataTable tbody tr').length + 1}</td>
                        <td>${namaObat}</td>
                        <td>${nilaiKecil}</td>
                        <td>${rawHargaKecil}</td>
                        <td>${diskonBaru}</td>
                        <td>${tglExpired}</td>
                        <td>${noBatch}</td>
                        <td>${subTotalRupiah}</td>
                        <td style="display:none" class="kode-barang-hidden">${kodeBarang}</td>
                    </tr>
                `;
                $('#dataTable tbody').append(newRow);
                hitungTotalSubTotalRupiah();
                hitungTotalDiskon();
                hitungTotalPPN();
                updateHiddenJsonInput();
            }

            // Reset inputan
            $('#nama_obat_alkes').val('').trigger('change');
            $('#nilai_satuan_kecil').val('');
            $('#harga_satuan_kecil').val('');
            $('#nilai_satuan_besar').val('');
            $('#harga_satuan_besar').val('');
            $('#diskon_persen').val('');
            $('#diskon_rupiah').val('');
            $('#tgl_expired').val('');
            $('#no_batch').val('');
        }

        function updateHiddenJsonInput() {
            const data = [];
            $('#dataTable tbody tr').each(function () {
                const cells = $(this).find('td');
                data.push({
                    no: cells.eq(0).text(),
                    nama: cells.eq(1).text(),
                    qty: parseFloat(cells.eq(2).text()),
                    hargaSatuan: cells.eq(3).text(),
                    disc: cells.eq(4).text(),
                    exp: cells.eq(5).text(),
                    batch: cells.eq(6).text(),
                    subTotal: cells.eq(7).text(),
                    kodeBarang: cells.eq(8).text()
                });
            });
            $('#data_json_tabel').val(JSON.stringify(data));

            let jsonData = JSON.stringify(data);
            // console.log('Final Data nieh : ', jsonData);
        }


        function hitungTotalSubTotalRupiah() {
            let total = 0;

            $('#dataTable tbody tr').each(function () {
                const teks = $(this).find('td').eq(7).text().trim();
                const angka = parseInt(teks.replace(/[^\d]/g, ''));
                if (!isNaN(angka)) {
                    total += angka;
                }
            });

            const totalFormatted = 'Rp ' + total.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            $('#sub_total_keseluruhan').text(totalFormatted);
            $('#sub_total_keseluruhan_input').val(total);

            // console.log(total);

            hitungTotalKeseluruhan();
        }

        function hitungTotalDiskon() {
            let totalDiskon = 0;

            $('#dataTable tbody tr').each(function () {
                const $tds = $(this).find('td');

                const qty = parseInt($tds.eq(2).text().trim());
                const hargaSatuanText = $tds.eq(3).text().trim();
                const diskonText = $tds.eq(4).text().trim();

                const hargaSatuan = parseInt(hargaSatuanText.replace(/[^\d]/g, '')); // Hapus "Rp" dan titik
                let diskonRupiah = 0;

                if (diskonText.includes('%')) {
                    // Diskon persen
                    const persen = parseFloat(diskonText.replace('%', ''));
                    if (!isNaN(persen)) {
                        diskonRupiah = Math.round((hargaSatuan * qty) * (persen / 100));
                    }
                } else if (diskonText.includes('Rp')) {
                    // Diskon rupiah langsung
                    diskonRupiah = parseInt(diskonText.replace(/[^\d]/g, ''));
                }

                if (!isNaN(diskonRupiah)) {
                    totalDiskon += diskonRupiah;
                }
            });

            // Format total diskon ke Rupiah tanpa ,00
            const totalDiskonFormatted = 'Rp ' + totalDiskon.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            // Tampilkan ke elemen target
            $('#diskon_total_keseluruhan').text(totalDiskonFormatted);
            $('#diskon_total_keseluruhan_input').val(totalDiskon);

            // console.log(totalDiskon);

            hitungTotalKeseluruhan();
        }

        function hitungTotalPPN() {
            // Ambil teks dari elemen subtotal dan diskon
            let subTotalText = $('#sub_total_keseluruhan').text().trim();
            let diskonText = $('#diskon_total_keseluruhan').text().trim();
            let ppnText = $('#pajak_ppn').val().trim(); // misal "10%"

            // Bersihkan angka dari teks
            let subTotal = parseInt(subTotalText.replace(/[^\d]/g, '')) || 0;
            let diskon = parseInt(diskonText.replace(/[^\d]/g, '')) || 0;
            let ppnPersen = parseFloat(ppnText.replace('%', '').replace(',', '.')) || 0;

            // Hitung PPN
            let dasarPajak = subTotal - diskon;
            let totalPPN = Math.round((dasarPajak * ppnPersen) / 100);

            // Format ke rupiah
            let totalPPNRupiah = 'Rp ' + totalPPN.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            // Tampilkan ke elemen target
            $('#ppn_total_keseluruhan').text(totalPPNRupiah);
            $('#ppn_total_keseluruhan_input').val(totalPPN);

            // console.log(totalPPN);

            hitungTotalKeseluruhan();
        }

        function hitungTotalKeseluruhan() {
            // Ambil nilai Subtotal, Diskon, PPN
            const subTotalText = $('#sub_total_keseluruhan').text().trim();
            const diskonText = $('#diskon_total_keseluruhan').text().trim();
            const ppnText = $('#ppn_total_keseluruhan').text().trim();

            // Convert ke angka
            const subTotal = parseInt(subTotalText.replace(/[^\d]/g, '')) || 0;
            const diskon = parseInt(diskonText.replace(/[^\d]/g, '')) || 0;
            const ppn = parseInt(ppnText.replace(/[^\d]/g, '')) || 0;

            // Ambil nilai materai dari <select>
            const materai = parseInt($('#materai').val()) || 0;

            // Ambil koreksi
            const koreksi = parseInt($('#koreksi').val().replace(/[^\d]/g, '')) || 0;

            // Total = subtotal - diskon + ppn + materai + koreksi
            const total = subTotal - diskon + ppn + materai + koreksi;

            // Format ke Rupiah
            const totalFormatted = 'Rp ' + total.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            // Tampilkan
            $('#total_keseluruhan').text(totalFormatted);
            $('#total_keseluruhan_input').val(total);
        }

        // Fungsi untuk reset semua data
        function resetAllData() {
            // Kosongkan tabel
            $('#dataTable tbody').empty();

            // Reset nilai perhitungan dan tampilan
            $('#sub_total_keseluruhan').text('Rp 0');
            $('#sub_total_keseluruhan_input').val(0);
            $('#diskon_total_keseluruhan').text('Rp 0');
            $('#diskon_total_keseluruhan_input').val(0);
            $('#ppn_total_keseluruhan').text('Rp 0');
            $('#ppn_total_keseluruhan_input').val(0);
            $('#total_keseluruhan').text('Rp 0');
            $('#total_keseluruhan_input').val(0);

            // Reset input form di langkah pertama
            $('#tanggal_faktur').val('');
            $('#tanggal_jatuh_tempo').val('');
            $('#supplier').val('').trigger('change');
            $('#pajak_ppn').val('0%');

            // Reset input form di langkah kedua
            $('#materai').val('0').trigger('change');
            $('#koreksi').val('0');
            $('#penerima_barang').val('').trigger('change');

            // Reset semua input lainnya
            $('#nama_obat_alkes').val('').trigger('change');
            $('#nilai_satuan_kecil').val('');
            $('#harga_satuan_kecil').val('');
            $('#diskon_persen').val('');
            $('#diskon_rupiah').val('');
            $('#tgl_expired').val('');
            $('#no_batch').val('');

            // Reset data JSON
            $('#data_json_tabel').val('[]');

            // Pastikan checkbox tidak tercentang
            $('input[type="checkbox"]').prop('checked', false);

            // Reset semua pesan error jika ada
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').hide();

            // Reset semua elemen select2 jika digunakan
            if ($.fn.select2) {
                $('select.select2').val(null).trigger('change');
            }

            // Jika ada elemen lain yang perlu direset di langkah kedua
            // tambahkan di sini
        }
    </script>



@endsection











