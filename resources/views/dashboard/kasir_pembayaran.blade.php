@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Detail Pembayaran Kasir</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text-secondary">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('kasir') }}" class="text-secondary">Kasir</a></li>
                        <li class="breadcrumb-item active text-dark">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Row 1: Data Pasien dan Tabel Detail -->
            <form id="addFormKasir" action="{{ route('kasir.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Kolom Kiri - Data Pasien -->
                    <div class="col-md-3">
                        <div class="card card-outline border-secondary shadow">
                            <div class="card-header bg-secondary text-white">
                                <h3 class="card-title"><i class="fas fa-user-injured mr-2"></i>Data Pasien</h3>
                            </div>
                            <div class="card-body p-3">
                                <input type="hidden" id="data_hidden" name="data_hidden">
                                <input type="hidden" id="kode_faktur_hidden" name="kode_faktur_hidden">
                                <input type="hidden" id="no_rawat_hidden" name="no_rawat_hidden">
                                <div class="form-group row mb-2">
                                    <label class="col-sm-3 col-form-label col-form-label-sm font-weight-bold">No. RM</label>
                                    <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm bg-light" id="no_rm" name="no_rm"
                                        value="{{ optional($apotek)->no_rm ?? $tindakan->nomor_rm ?? '' }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-sm-3 col-form-label col-form-label-sm font-weight-bold">Nama</label>
                                    <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm bg-light" id="nama" name="nama"
                                        value="{{ optional($apotek)->nama ?? $tindakan->nama ?? '' }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-sm-3 col-form-label col-form-label-sm font-weight-bold">Sex</label>
                                    <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm bg-light" id="sex" name="sex"
                                        value="{{ optional(optional($apotek)->data_soap)->sex ?? $tindakan->sex ?? '' }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-sm-3 col-form-label col-form-label-sm font-weight-bold">Usia</label>
                                    <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm bg-light" id="usia" name="usia"
                                        value="{{ optional(optional($apotek)->data_soap)->umur ?? optional(optional($tindakan)->data_soap)->umur ?? '' }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-sm-3 col-form-label col-form-label-sm font-weight-bold">Alamat</label>
                                    <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control form-control-sm bg-light" id="alamat" name="alamat" rows="4" readonly>
                                            {{ optional($apotek)->alamat ?? optional(optional(optional($tindakan)->data_soap)->pasien)->alamat ?? '' }}
                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-sm-3 col-form-label col-form-label-sm font-weight-bold">Poli</label>
                                    <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm bg-light" id="poli" name="poli"
                                        value="{{ optional($apotek)->poli ?? optional(optional(optional(optional($tindakan)->data_soap)->pendaftaran)->poli)->nama ?? '' }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-sm-3 col-form-label col-form-label-sm font-weight-bold">Dokter</label>
                                    <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm bg-light" id="dokter" name="dokter"
                                        value="{{ optional($apotek)->dokter ?? optional(optional(optional(optional(optional($tindakan)->data_soap)->pendaftaran)->dokter)->namauser)->name ?? '' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Jenis Perawatan -->
                        <div class="card card-outline border-secondary shadow mt-3">
                            <div class="card-header bg-light">
                                <h3 class="card-title"><i class="fas fa-procedures mr-2"></i>Jenis Perawatan</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="form-group row mb-2">
                                    <label class="col-sm-3 col-form-label col-form-label-sm font-weight-bold">Jenis</label>
                                    <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm bg-light" id="jenis_perawatan" name="jenis_perawatan" value="RAWAT JALAN" readonly>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label class="col-sm-3 col-form-label col-form-label-sm font-weight-bold">Penjamin</label>
                                    <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control form-control-sm" id="penjamin" name="penjamin">
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($penjamin as $penjaminData)
                                                <option value="{{ $penjaminData->nama }}"
                                                    {{ optional($apotek)->penjamin ?? optional(optional($tindakan)->data_soap)->penjamin == $penjaminData->nama ? 'selected' : '' }}>
                                                    {{ $penjaminData->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Button Tambah Tindakan -->
                        <div class="card card-outline border-secondary shadow mt-3">
                            <div class="card-header bg-light">
                                <h3 class="card-title"><i class="fas fa-plus-circle mr-2"></i>Tambahan Tindakan</h3>
                            </div>
                            <div class="card-body p-3 text-center">
                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#modalTambahTindakan">
                                    <i class="fas fa-plus-circle mr-1"></i>Tambah Tindakan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan - Detail Tagihan -->
                    <div class="col-md-9">
                        <div class="card card-outline border-secondary shadow">
                            <div class="card-header bg-light">
                                <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-2"></i>Detail Tagihan</h3>
                            </div>
                            <div class="card-body p-0">
                                <!-- Tabel Transaksi dengan Fixed Height yang lebih kecil -->
                                <div class="table-responsive" style="height: 220px; overflow-y: auto; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                                    <table class="table table-striped table-hover table-sm" id="tabelData">
                                        <thead>
                                            <tr class="bg-secondary text-white">
                                                <th class="text-center align-middle" width="5%">No</th>
                                                <th class="align-middle" width="25%">Nama</th>
                                                <th class="text-center align-middle" width="10%">Harga</th>
                                                <th class="text-center align-middle" width="20%">Qty / Pelaksana</th>
                                                <th class="text-center align-middle" width="10%">Total</th>
                                                <th class="text-center align-middle" width="10%">Date</th>
                                                <th class="text-center align-middle" width="5%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Perhitungan dan Pembayaran dalam satu card -->
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="card card-outline border-secondary shadow-sm">
                                    <div class="card-header py-1 px-2 bg-light">
                                        <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-1"></i>Tagihan dan Metode Pembayaran</h3>
                                    </div>
                                    <div class="card-body p-2">
                                        <h6 class="font-weight-bold text-secondary mb-1"><i class="fas fa-calculator mr-1"></i>Perhitungan Tagihan</h6>

                                        <!-- Sub Total dan Administrasi -->
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <div class="form-group row mb-1">
                                                    <label class="col-sm-4 col-form-label-sm font-weight-bold">Sub Total</label>
                                                    <label class="col-sm-1 col-form-label-sm">:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-light py-0 px-1">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm py-0" id="sub_total" name="sub_total" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row mb-1">
                                                    <label class="col-sm-4 col-form-label-sm font-weight-bold text-danger">Total</label>
                                                    <label class="col-sm-1 col-form-label-sm">:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-danger text-white py-0 px-1">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm bg-danger text-white font-weight-bold py-0" id="total" name="total" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Potongan dan Materai -->
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <div class="form-group row mb-1">
                                                    <label class="col-sm-4 col-form-label-sm font-weight-bold">Potongan</label>
                                                    <label class="col-sm-1 col-form-label-sm">:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-light py-0 px-1">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm py-0" id="potongan_harga" name="potongan_harga" placeholder="0" readonly>
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1" data-toggle="modal" data-target="#modalDiskon">
                                                                    <i class="fas fa-tag"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row mb-1">
                                                    <label class="col-sm-4 col-form-label-sm font-weight-bold text-primary">Tagihan</label>
                                                    <label class="col-sm-1 col-form-label-sm">:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-dark text-white py-0 px-1">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm bg-dark text-white font-weight-bold py-0" id="tagihan" name="tagihan" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Total -->
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <div class="form-group row mb-1">
                                                    <label class="col-sm-4 col-form-label-sm font-weight-bold">Administrasi</label>
                                                    <label class="col-sm-1 col-form-label-sm">:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text bg-light py-0 px-1">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-sm py-0" id="administrasi" name="administrasi" placeholder="0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tagihan dan Harus Dibayar -->
                                        <div class="row mb-1">
                                            <div class="col-md-6">
                                                <div class="form-group row mb-1">
                                                    <label class="col-sm-4 col-form-label-sm font-weight-bold">Materai</label>
                                                    <label class="col-sm-1 col-form-label-sm">:</label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control form-control-sm py-0" id="materai" name="materai">
                                                            <option value="0" selected>0</option>
                                                            <option value="3000">3.000</option>
                                                            <option value="6000">6.000</option>
                                                            <option value="12000">6.000 (x2)</option>
                                                            <option value="18000">6.000 (x3)</option>
                                                            <option value="24000">6.000 (x4)</option>
                                                            <option value="10000">10.000</option>
                                                            <option value="20000">10.000 (x2)</option>
                                                            <option value="30000">10.000 (x3)</option>
                                                            <option value="40000">10.000 (x4)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row mb-0">
                                                    <label class="col-form-label-sm font-weight-bold text-white w-100 text-center" id="kurang_dibayar_label"></label>

                                                    <!-- Input tersembunyi untuk mengirimkan nilai -->
                                                    <input type="hidden" id="kurang_dibayar" name="kurang_dibayar">
                                                </div>
                                            </div>

                                        </div>

                                        <hr class="my-1">

                                        <!-- Multi Payment -->
                                        <div class="mt-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="multi_payment" onchange="toggleMultiPayment()">
                                                    <label class="custom-control-label" for="multi_payment">Multi Payment</label>
                                                </div>
                                            </div>

                                            <!-- Pembayaran 1 -->
                                            <div class="row mb-1 payment-method">
                                                <div class="col-md-2 pr-0">
                                                    <label class="col-form-label-sm font-weight-bold">Bayar (1) :</label>
                                                </div>
                                                <div class="col-md-2 px-1">
                                                    <select class="form-control form-control-sm py-0" id="payment_method_1" name="payment_method_1" onchange="updateBankOptions('payment_method_1', 'payment_type_1'); paymentMethod1();">
                                                        <option value="" selected disabled>-- Pilih --</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="debit">Debit Card</option>
                                                        <option value="credit">Credit Card</option>
                                                        <option value="transfer">Transfer</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 px-1">
                                                    <input type="text" class="form-control form-control-sm py-0" id="payment_nominal_1" name="payment_nominal_1" placeholder="Nominal Rupiah" oninput="validateUangBayar('payment_method_1', 'payment_nominal_1'); sisaDibayar();">
                                                </div>
                                                <div class="col-md-3 px-1">
                                                    <select class="form-control form-control-sm py-0" id="payment_type_1" name="payment_type_1" disabled>
                                                        <option value="" selected disabled>-- Pilih --</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 pl-1">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text py-0 px-1">Ref:</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm py-0" placeholder="0" id="payment_ref_1" name="payment_ref_1" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Pembayaran 2 -->
                                            <div class="row mb-1 payment-method">
                                                <div class="col-md-2 pr-0">
                                                    <label class="col-form-label-sm font-weight-bold" style="color: grey" id="label_payment_2">Bayar (2) :</label>
                                                </div>
                                                <div class="col-md-2 px-1">
                                                    <select class="form-control form-control-sm py-0" id="payment_method_2" name="payment_method_2" disabled onchange="updateBankOptions('payment_method_2', 'payment_type_2'); paymentMethod2();">
                                                        <option value="" selected disabled>-- Pilih --</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="debit">Debit Card</option>
                                                        <option value="credit">Credit Card</option>
                                                        <option value="transfer">Transfer</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 px-1">
                                                    <input type="text" class="form-control form-control-sm py-0" id="payment_nominal_2" name="payment_nominal_2" placeholder="Nominal Rupiah" disabled oninput="validateUangBayar('payment_method_2', 'payment_nominal_2'); sisaDibayar();">
                                                </div>
                                                <div class="col-md-3 px-1">
                                                    <select class="form-control form-control-sm py-0" id="payment_type_2" name="payment_type_2" disabled>
                                                        <option value="" selected disabled>-- Pilih --</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 pl-1">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text py-0 px-1">Ref:</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm py-0" id="payment_ref_2" name="payment_ref_2" placeholder="0" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Pembayaran 3 -->
                                            <div class="row payment-method">
                                                <div class="col-md-2 pr-0">
                                                    <label class="col-form-label-sm font-weight-bold" style="color: grey" id="label_payment_3">Bayar (3) :</label>
                                                </div>
                                                <div class="col-md-2 px-1">
                                                    <select class="form-control form-control-sm py-0" id="payment_method_3" name="payment_method_3" disabled onchange="updateBankOptions('payment_method_3', 'payment_type_3'); paymentMethod3();">
                                                        <option value="" selected disabled>-- Pilih --</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="debit">Debit Card</option>
                                                        <option value="credit">Credit Card</option>
                                                        <option value="transfer">Transfer</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 px-1">
                                                    <input type="text" class="form-control form-control-sm py-0" id="payment_nominal_3" name="payment_nominal_3" placeholder="Nominal Rupiah" disabled oninput="validateUangBayar('payment_method_3', 'payment_nominal_3'); sisaDibayar();">
                                                </div>
                                                <div class="col-md-3 px-1">
                                                    <select class="form-control form-control-sm py-0" id="payment_type_3" name="payment_type_3" disabled>
                                                        <option value="" selected disabled>-- Pilih --</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 pl-1">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text py-0 px-1">Ref:</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm py-0" placeholder="0" id="payment_ref_3" name="payment_ref_3" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tombol Aksi -->
                                            <div class="row mt-2">
                                                <div class="col-12 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-sm btn-secondary ml-1">
                                                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                                                    </button>
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-danger mr-1">
                                                            <i class="fas fa-trash mr-1"></i>Delete
                                                        </button>
                                                        <button type="submit" class="btn btn-sm btn-success mr-1">
                                                            <i class="fas fa-save mr-1"></i>Simpan
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
            </form>
        </div>
    </section>
</div>

<!-- Modal Tambah Tindakan -->
<div class="modal fade" id="modalTambahTindakan" tabindex="-1" role="dialog" aria-labelledby="modalTambahTindakanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalTambahTindakanLabel">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Tindakan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="kategori_tindakan_tambahan" class="font-weight-bold">Pilih Kategori Tindakan</label>
                            <select class="form-control" id="kategori_tindakan_tambahan" name="kategori_tindakan_tambahan">
                                <option value="" disabled selected>-- Pilih --</option>
                                @foreach ($tindakanTambahan as $tindakanTambahan)
                                    <option value="{{ $tindakanTambahan->nama }}" data-id="{{ $tindakanTambahan->id }}">{{ $tindakanTambahan->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tindakan_tambahan" class="font-weight-bold">Pilih Tindakan</label>
                            <select class="form-control" id="tindakan_tambahan" name="tindakan_tambahan">
                                <option value="" disabled selected>-- Pilih --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="petugas_tambahan" class="font-weight-bold">Petugas</label>
                            <select class="form-control" id="petugas_tambahan" name="petugas_tambahan">
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="Perawat">Perawat</option>
                                <option value="Dokter">Dokter</option>
                                <option value="Perawat & Dokter">Perawat & Dokter</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tarif_perawat_tambahan" class="font-weight-bold">Tarif Perawat</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="tarif_perawat_tambahan" name="tarif_perawat_tambahan" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tarif_dokter_tambahan" class="font-weight-bold">Tarif Dokter</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="tarif_dokter_tambahan" name="tarif_dokter_tambahan" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="total_tindakan_tambahan" class="font-weight-bold">Total Tarif</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="total_tindakan_tambahan" name="total_tindakan_tambahan" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Batal
                </button>
                <button type="button" class="btn btn-secondary" onclick="tambahTindakanBaru()">
                    <i class="fas fa-save mr-1"></i>Simpan Tindakan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Diskon -->
<div class="modal fade" id="modalDiskon" tabindex="-1" role="dialog" aria-labelledby="modalDiskonLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                        <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalDiskonLabel">
                    <i class="fas fa-tag mr-2"></i>Tambah Potongan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formDiskon">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jenis_potongan" class="font-weight-bold">Jenis Potongan</label>
                                <select class="form-control" id="jenis_potongan" name="jenis_potongan">
                                    <option value="discount" selected>Discount</option>
                                    <option value="voucher">Voucher</option>
                                    <option value="cashback">Cashback</option>
                                    <option value="promo">Promo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="besar_potongan" class="font-weight-bold">Besar Potongan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="besar_potongan" name="besar_potongan" placeholder="0,00">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="uraian_potongan" class="font-weight-bold">Uraian Potongan</label>
                        <input type="text" class="form-control" id="uraian_potongan" name="uraian_potongan" placeholder="Masukkan Uraian Potongan">
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Batal
                </button>
                <button type="button" class="btn btn-secondary" onclick="tambahPotonganKeTabel()">
                    <i class="fas fa-check mr-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    const tindakanTabel = @json($tindakanTabel);
    const apotekTabel = @json($apotekTabel);
    document.getElementById('kode_faktur_hidden').value = @json($kode_faktur);
    document.getElementById('no_rawat_hidden').value = @json($no_rawat);

    console.log(@json($no_rawat));

    //SCRIPT MASUKIN DATA KE INPUTAN HIDDEN
    function collectTableDataToHiddenInput() {
        const tindakan = [];
        const apotek = [];
        const diskon = [];

        const rows = document.querySelectorAll('#tabelData tbody tr');

        rows.forEach(row => {
            const tds = row.querySelectorAll('td');
            const jenisData = row.getAttribute('data-jenis');

            const nama = tds[1].textContent.trim();
            const harga = parseInt(tds[2].textContent.replace(/\./g, '')) || 0;
            const qtyOrPelaksana = tds[3].textContent.trim();
            const total = parseInt(tds[4].textContent.replace(/\./g, '')) || 0;
            const date = tds[5].textContent.trim();

            if (jenisData === 'diskon') {
                diskon.push({
                    nama: nama,
                    harga: harga,
                    jenis: qtyOrPelaksana,
                    nilai: total,
                    tanggal: date,
                });
            } else if (!isNaN(parseInt(qtyOrPelaksana))) {
                apotek.push({
                    nama_obat_alkes: nama,
                    harga: harga,
                    qty: parseInt(qtyOrPelaksana),
                    total: total,
                    tanggal: date,
                });
            } else {
                tindakan.push({
                    jenis_tindakan: nama,
                    harga: harga,
                    jenis_pelaksana: qtyOrPelaksana,
                    total: total,
                    tanggal: date,
                });
            }
        });

        const data = {
            tindakan: tindakan,
            apotek: apotek,
            diskon: diskon
        };

        document.getElementById('data_hidden').value = JSON.stringify(data);
        console.log(JSON.stringify(data));
    }


    //SCRIPT UNTUK NAMPILIN DATA KE TABEL
    function renderTable() {
        let no = 1;
        let html = '';

        tindakanTabel.forEach(item => {
            html += `
            <tr>
                <td class="text-center align-middle">${no++}</td>
                <td class="align-middle">${item.Jenis_tindakan ?? '-'}</td>
                <td class="text-center align-middle">${numberFormat(item.harga)}</td>
                <td class="text-center align-middle">${item.jenis_pelaksana ?? '-'}</td>
                <td class="text-center align-middle">${numberFormat(item.harga)}</td>
                <td class="text-center align-middle">${formatDate(item.created_at)}</td>
                <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>
            `;
        });

        apotekTabel.forEach(item => {
            html += `
            <tr>
                <td class="text-center align-middle">${no++}</td>
                <td class="align-middle">${item.nama_obat_alkes ?? '-'}</td>
                <td class="text-center align-middle">${numberFormat(item.harga)}</td>
                <td class="text-center align-middle">${item.qty ?? 1}</td>
                <td class="text-center align-middle">${numberFormat(item.total)}</td>
                <td class="text-center align-middle">${formatDate(item.tanggal)}</td>
                <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>
            `;
        });

        document.querySelector('#tabelData tbody').innerHTML = html;

        // Set ke hidden input
        collectTableDataToHiddenInput();
        hitungSubtotal();
        hitungTotal();
        sisaDibayar();
    }

    //FUNGSI FORMAT HARGA
    function numberFormat(number) {
        if (!number) return '0';
        return Number(number).toLocaleString('id-ID');
    }

    //FUNGSI FORMAT TANGGAL
    function formatDate(dateStr) {
        if (!dateStr) return '-';
        return dateStr.substring(0, 10);
    }

    //NGEJALANIN FUNGSI RENDER TABEL
    document.addEventListener('DOMContentLoaded', () => {
        renderTable();

        Inputmask({
            alias: "numeric",
            groupSeparator: ".",
            radixPoint: ",",
            autoGroup: true,
            digits: 0,
            digitsOptional: false,
            placeholder: "",
            rightAlign: false,
            removeMaskOnSubmit: true
        }).mask("#besar_potongan, #administrasi");

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
            }).mask("#payment_nominal_1, #payment_nominal_2, #payment_nominal_3");

    });

    let diskonCounter = 0; // counter untuk membedakan diskon

    function tambahPotonganKeTabel() {
        const jenis = document.getElementById('jenis_potongan').value;
        const besar = document.getElementById('besar_potongan').value.replace(/\./g, '').replace(',', '.'); // hilangkan titik
        const uraian = document.getElementById('uraian_potongan').value;

        // Konversi ke angka
        const total = parseFloat(besar) || 0;
        const now = new Date().toISOString().slice(0, 10); // tanggal sekarang

        const tabel = document.querySelector('#tabelData tbody');
        const barisBaru = document.createElement('tr');

        barisBaru.setAttribute('data-jenis', 'diskon'); // penanda jenis
        barisBaru.setAttribute('data-id', `diskon_${diskonCounter++}`);

        barisBaru.innerHTML = `
            <td class="text-center align-middle">-</td>
            <td class="align-middle">${uraian} (${jenis})</td>
            <td class="text-center align-middle">-${numberFormat(total)}</td>
            <td class="text-center align-middle">1</td>
            <td class="text-center align-middle">-${numberFormat(total)}</td>
            <td class="text-center align-middle">${now}</td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-danger btn-hapus"><i class="fas fa-trash-alt"></i></button>
            </td>
        `;

        tabel.appendChild(barisBaru);
        updateNomorTabel();
        collectTableDataToHiddenInput();
        hitungPotongan();
        hitungTotal();
        sisaDibayar();

        $('#modalDiskon').modal('hide');
    }

    function updateNomorTabel() {
        document.querySelectorAll('#tabelData tbody tr').forEach((row, index) => {
            row.querySelector('td:first-child').textContent = index + 1;
        });
    }

    const semuaKategori = @json($tindakanTambahan);

    document.getElementById('kategori_tindakan_tambahan').addEventListener('change', function () {
        const selectedKategoriId = parseInt(this.options[this.selectedIndex].dataset.id);
        const tindakanSelect = $('#tindakan_tambahan');
        tindakanSelect.empty().append('<option value="">-- Pilih --</option>');

        const kategori = semuaKategori;  // objek langsung

        if (kategori && Array.isArray(kategori.perawatan_tindakan)) {
            kategori.perawatan_tindakan.forEach(t => {
                if(t.perawatan_kategori_id === selectedKategoriId) {
                    tindakanSelect.append(`<option value="${t.nama}" data-tarif-perawat="${t.tarif_perawat}" data-tarif-dokter="${t.tarif_dokter}">${t.nama}</option>`);
                }
            });
        }
    });

    // Event listener untuk perubahan pilihan tindakan dan petugas
    document.getElementById('tindakan_tambahan').addEventListener('change', hitungTarif);
    document.getElementById('petugas_tambahan').addEventListener('change', hitungTarif);

    function hitungTarif() {
        const tindakanSelect = document.getElementById('tindakan_tambahan');
        const petugasSelect = document.getElementById('petugas_tambahan');

        const selectedOption = tindakanSelect.options[tindakanSelect.selectedIndex];

        // Gunakan getAttribute untuk ambil data attribute (jika ada)
        // Pastikan atribut data sudah ada di option
        const tarifPerawat = parseFloat(selectedOption?.getAttribute('data-tarif-perawat')) || 0;
        const tarifDokter = parseFloat(selectedOption?.getAttribute('data-tarif-dokter')) || 0;

        const jenisPetugas = petugasSelect.value;

        let total = 0;

        if (jenisPetugas === 'Perawat') {
            document.getElementById('tarif_perawat_tambahan').value = numberFormat(tarifPerawat);
            document.getElementById('tarif_dokter_tambahan').value = numberFormat(0);
            total = tarifPerawat;
        } else if (jenisPetugas === 'Dokter') {
            document.getElementById('tarif_perawat_tambahan').value = numberFormat(0);
            document.getElementById('tarif_dokter_tambahan').value = numberFormat(tarifDokter);
            total = tarifDokter;
        } else if (jenisPetugas === 'Perawat & Dokter') {
            document.getElementById('tarif_perawat_tambahan').value = numberFormat(tarifPerawat);
            document.getElementById('tarif_dokter_tambahan').value = numberFormat(tarifDokter);
            total = tarifPerawat + tarifDokter;
        } else {
            // Jika belum memilih petugas
            document.getElementById('tarif_perawat_tambahan').value = '';
            document.getElementById('tarif_dokter_tambahan').value = '';
            total = 0;
        }

        document.getElementById('total_tindakan_tambahan').value = numberFormat(total);
    }

    let tambahanTindakan = 0; // counter untuk membedakan diskon

    function tambahTindakanBaru() {
        const nama = document.getElementById('tindakan_tambahan').value;
        const petugas = document.getElementById('petugas_tambahan').value;
        const t_perawat = document.getElementById('tarif_perawat_tambahan').value.replace(/\./g, '').replace(',', '.');
        const t_dokter = document.getElementById('tarif_dokter_tambahan').value.replace(/\./g, '').replace(',', '.');
        const t_total = document.getElementById('total_tindakan_tambahan').value.replace(/\./g, '').replace(',', '.');

        // Konversi ke angka
        const tarif_perawat = parseFloat(t_perawat) || 0;
        const tarif_dokter = parseFloat(t_dokter) || 0;
        const tarif_total = parseFloat(t_total) || 0;
        const now = new Date().toISOString().slice(0, 10); // tanggal sekarang

        let tampilkanTarif = '';

        if (petugas === 'Perawat') {
            tampilkanTarif = numberFormat(tarif_perawat);
        } else if (petugas === 'Dokter') {
            tampilkanTarif = numberFormat(tarif_dokter);
        } else if (petugas === 'Perawat & Dokter') {
            tampilkanTarif = numberFormat(tarif_total);
        } else {
            tampilkanTarif = '-';
        }

        const tabel = document.querySelector('#tabelData tbody');
        const barisBaru = document.createElement('tr');

        barisBaru.setAttribute('data-jenis', 'tambahan_tindakan'); // penanda jenis
        barisBaru.setAttribute('data-id', `tambahan_tindakan_${tambahanTindakan++}`);

        barisBaru.innerHTML = `
            <td class="text-center align-middle">-</td>
            <td class="align-middle">${nama}</td>
            <td class="text-center align-middle">${tampilkanTarif}</td>
            <td class="text-center align-middle">${petugas}</td>
            <td class="text-center align-middle">${numberFormat(tarif_total)}</td>
            <td class="text-center align-middle">${now}</td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-sm btn-danger btn-hapus"><i class="fas fa-trash-alt"></i></button>
            </td>
        `;

        tabel.appendChild(barisBaru);
        updateNomorTabel();
        collectTableDataToHiddenInput();
        hitungSubtotal();
        hitungTotal();
        sisaDibayar();

        $('#modalTambahTindakan').modal('hide');
    }

    function hitungSubtotal() {
        let subtotal = 0;

        // Ambil semua <tr> di tbody tabel
        const rows = document.querySelectorAll('#tabelData tbody tr');

        rows.forEach(row => {
            // Ambil isi kolom Total (5th kolom, index ke-4, karena index mulai 0)
            const totalCell = row.cells[4];
            if (totalCell) {
            // Dapatkan text dan bersihkan titik ribuan, koma desimal dll sesuai format angka
            let nilai = totalCell.textContent.trim().replace(/\./g, '');
            let angka = parseFloat(nilai);
            if (!isNaN(angka)) {
                subtotal += angka;
            }
            }
        });

        // Format subtotal ke format rupiah (optional)
        const formattedSubtotal = numberFormat(subtotal);

        // Masukkan ke input sub_total
        document.getElementById('sub_total').value = formattedSubtotal;
    }

    function hitungPotongan() {
        let potongan = 0;

        const rows = document.querySelectorAll('#tabelData tbody tr');

        rows.forEach(row => {
            const jenisData = row.getAttribute('data-jenis');

            if (jenisData === 'diskon') {
                const totalCell = row.cells[4];
                let nilai = parseInt(
                    totalCell.textContent.trim().replace(/\./g, '').replace(',', '').replace(/\s+/g, '').replace('-', '')
                ) || 0;

                potongan += nilai;
            }
        });

        const formatted = numberFormat(potongan); // Format angka ke rupiah
        document.getElementById('potongan_harga').value = formatted;
    }

    function hitungTotal() {
        const subTotal = parseFloat(document.getElementById('sub_total').value.replace(/\./g, '').replace(',', '.')) || 0;
        const potonganHarga = parseFloat(document.getElementById('potongan_harga').value.replace(/\./g, '').replace(',', '.')) || 0;
        const administrasi = parseFloat(document.getElementById('administrasi').value.replace(/\./g, '').replace(',', '.')) || 0;
        const materai = parseFloat(document.getElementById('materai').value) || 0;

        // Total tagihan
        const totalTagihan = subTotal - potonganHarga + administrasi + materai;

        // Update tagihan
        document.getElementById('tagihan').value = numberFormat(totalTagihan);
        document.getElementById('total').value = numberFormat(totalTagihan);

        sisaDibayar();
    }

    // Pasang event listener agar fungsi dipanggil saat nilai berubah
    document.getElementById('administrasi').addEventListener('keyup', hitungTotal);
    document.getElementById('materai').addEventListener('change', hitungTotal);

    // Ambil elemen checkbox dan elemen pembayaran
    let multiPaymentCheckbox = document.getElementById("multi_payment");
    let bayar1 = document.getElementById('payment_method_1');
    let bayar2 = document.getElementById("payment_method_2");
    let bayar3 = document.getElementById("payment_method_3");
    let uangBayar1 = document.getElementById('payment_nominal_1');
    let uangBayar2 = document.getElementById("payment_nominal_2");
    let uangBayar3 = document.getElementById('payment_nominal_3');
    let bankBayar1 = document.getElementById('payment_type_1');
    let bankBayar2 = document.getElementById('payment_type_2');
    let bankBayar3 = document.getElementById('payment_type_3');
    let refInput1 = document.getElementById('payment_ref_1');
    let refInput2 = document.getElementById('payment_ref_2');
    let refInput3 = document.getElementById('payment_ref_3');
    let labelBayar2 = document.getElementById("label_payment_2");
    let labelBayar3 = document.getElementById("label_payment_3");

    function toggleMultiPayment() {
        if (multiPaymentCheckbox.checked) {
            // Jika checkbox di-check, aktifkan Bayar 2 dan Bayar 3, ubah warna label
            bayar2.disabled = false;
            bayar3.disabled = false;
            uangBayar2.disabled = false;
            uangBayar3.disabled = false;
            labelBayar2.style.color = "black";
            labelBayar3.style.color = "black";
        } else {
            // Jika checkbox tidak di-check, nonaktifkan Bayar 2 dan Bayar 3, ubah warna label
            bayar2.disabled = true;
            bayar3.disabled = true;
            $(bayar2).val("").trigger("change");
            $(bayar3).val("").trigger("change");
            uangBayar2.disabled = true;
            uangBayar3.disabled = true;
            bankBayar2.disabled = true;
            bankBayar3.disabled = true;
            refInput2.disabled = true;
            refInput3.disabled = true;
            labelBayar2.style.color = "gray";
            labelBayar3.style.color = "gray";
            $(uangBayar2).val(""); // Set nilai uangBayar2 ke
            $(uangBayar3).val(""); // Set nilai uangBayar3 ke
        }

        sisaDibayar();
    };

    function paymentMethod1() {
        const selectedValue = bayar1.value;

        if (selectedValue === 'cash') {
            $(bankBayar1).val("").trigger("change");
            bankBayar1.disabled = true;
            refInput1.disabled = true;
        } else {
            bankBayar1.disabled = false;
            refInput1.disabled = false;
        }
    }

    function paymentMethod2() {
        const selectedValue = bayar2.value;

        if (selectedValue === 'cash') {
            $(bankBayar2).val("").trigger("change");
            bankBayar2.disabled = true;
            refInput2.disabled = true;
        } else {
            bankBayar2.disabled = false;
            refInput2.disabled = false;
        }
    }

    function paymentMethod3() {
        const selectedValue = bayar3.value;

        if (selectedValue === 'cash') {
            $(bankBayar3).val("").trigger("change");
            bankBayar3.disabled = true;
            refInput3.disabled = true;
        } else {
            bankBayar3.disabled = false;
            refInput3.disabled = false;
        }
    }

    const bankOptions = [
        @foreach ($bank as $bank)
        { value: "{{ $bank->nama }}", label: "{{ $bank->nama }}", type: "bank" },
        @endforeach
    ];

    // Fungsi dinamis untuk mengupdate dropdown
    function updateBankOptions(payment_method_Id, payment_type_Id) {
        const bayar = document.getElementById(payment_method_Id).value;
        const bankBayar = document.getElementById(payment_type_Id);

        let options = [];
        if (bayar !== "cash") {
            options = bankOptions;
            bankBayar.innerHTML = "";
            bankBayar.disabled = false;
        }

        // Tambahkan opsi ke dropdown
        options.forEach(option => {
            const opt = document.createElement("option");
            opt.value = option.value;
            opt.textContent = option.label;
            bankBayar.appendChild(opt);
        });

        // Tambahkan placeholder
        const placeholder = document.createElement("option");
        placeholder.value = "";
        placeholder.textContent = "-- Pilih --";
        placeholder.disabled = true;
        placeholder.selected = true;
        bankBayar.insertBefore(placeholder, bankBayar.firstChild);

        // Jika tidak ada opsi, matikan dropdown
        if (options.length === 0) {
            bankBayar.disabled = true;
        }
    }

    function validateUangBayar(payment_method_Id, payment_nominal_Id) {
        const bayarElement = document.getElementById(payment_method_Id);
        const uangBayarElement = document.getElementById(payment_nominal_Id);

        if (bayarElement.value === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Metode Pembayaran',
                text: 'Silakan pilih metode pembayaran terlebih dahulu.',
                confirmButtonText: 'OK'
            });
            uangBayarElement.value = ""; // Kosongkan input uangBayar terkait
        }
    }

    function sisaDibayar() {
        let sisaDibayar = 0;

        // Ambil nilai tagihan dan pembayaran
        let tagihan = parseInt(document.getElementById("tagihan")?.value.replace(/\./g, '')) || 0;
        let pembayaran1 = parseInt(document.getElementById("payment_nominal_1")?.value.replace('Rp ', '').replace(/\./g, '')) || 0;
        let pembayaran2 = parseInt(document.getElementById("payment_nominal_2")?.value.replace('Rp ', '').replace(/\./g, '')) || 0;
        let pembayaran3 = parseInt(document.getElementById("payment_nominal_3")?.value.replace('Rp ', '').replace(/\./g, '')) || 0;

        // Hitung sisa pembayaran
        sisaDibayar = tagihan - (pembayaran1 + pembayaran2 + pembayaran3);

        // Ambil elemen untuk label dan container
        const kurangDibayarLabel = document.getElementById("kurang_dibayar_label");

        if (kurangDibayarLabel) {
            if (sisaDibayar === 0) {
                // Lunas
                kurangDibayarLabel.textContent = "Lunas";
                kurangDibayarLabel.style.backgroundColor = "green"; // Ubah warna latar ke hijau
            } else if (sisaDibayar < 0) {
                // Kembalian
                kurangDibayarLabel.textContent = `Kembalian Rp ${(Math.abs(sisaDibayar)).toLocaleString()}`;
                kurangDibayarLabel.style.backgroundColor = "blue"; // Ubah warna latar ke biru
            } else {
                // Kurang dibayar
                kurangDibayarLabel.textContent = `Kurang Dibayar Rp ${sisaDibayar.toLocaleString()}`;
                kurangDibayarLabel.style.backgroundColor = "red"; // Ubah warna latar ke merah
            }
        }

        // Set nilai tersembunyi untuk pengiriman data
        const kurangDibayarInput = document.getElementById("kurang_dibayar");
        if (kurangDibayarInput) {
            kurangDibayarInput.value = sisaDibayar;
        }
    }

    $('#addFormKasir').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href = "{{ route('kasir') }}";
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
                    $('#addFormKasir').find('.is-invalid').removeClass('is-invalid');

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

</script>

@endsection
