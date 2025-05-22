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
            <div class="row">
                <!-- Kolom Kiri - Data Pasien -->
                <div class="col-md-3">
                    <div class="card card-outline border-secondary shadow">
                        <div class="card-header bg-secondary text-white">
                            <h3 class="card-title"><i class="fas fa-user-injured mr-2"></i>Data Pasien</h3>
                        </div>
                        <div class="card-body p-3">
                            <div class="form-group row mb-2">
                                <label class="col-sm-4 col-form-label col-form-label-sm font-weight-bold">No. RM</label>
                                <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm bg-light" id="no_rm" value="000005" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-4 col-form-label col-form-label-sm font-weight-bold">Nama</label>
                                <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm bg-light" id="nama" value="HAVERYNA MEYSA HIDAYAH" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-4 col-form-label col-form-label-sm font-weight-bold">Sex</label>
                                <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm bg-light" id="sex" value="Perempuan" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-4 col-form-label col-form-label-sm font-weight-bold">Usia</label>
                                <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm bg-light" id="usia" value="19" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-4 col-form-label col-form-label-sm font-weight-bold">Alamat</label>
                                <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm bg-light" id="alamat" value="dumpit, Gandasari, Jatiuwung" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-4 col-form-label col-form-label-sm font-weight-bold">Poli</label>
                                <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm bg-light" id="poli" value="POLI UMUM" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-4 col-form-label col-form-label-sm font-weight-bold">Dokter</label>
                                <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm bg-light" id="dokter" value="JULI OSMIDA PURBA" readonly>
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
                                <label class="col-sm-4 col-form-label col-form-label-sm font-weight-bold">Jenis</label>
                                <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm bg-light" id="jenis_perawatan" value="RAWAT JALAN" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-4 col-form-label col-form-label-sm font-weight-bold">Pasien</label>
                                <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                <div class="col-sm-7">
                                    <select class="form-control form-control-sm" id="jenis_pasien">
                                        <option value="BPJS" selected>BPJS</option>
                                        <option value="Umum">Umum</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-4 col-form-label col-form-label-sm font-weight-bold">Penjamin</label>
                                <label class="col-sm-1 col-form-label col-form-label-sm">:</label>
                                <div class="col-sm-7">
                                    <select class="form-control form-control-sm" id="penjamin">
                                        <option value="BPJS" selected>BPJS</option>
                                        <option value="Mandiri">Mandiri</option>
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
                            <button class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#modalTambahTindakan">
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
                            <div class="table-responsive" style="height: 212px; overflow-y: auto; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                                <table class="table table-striped table-hover table-sm">
                                    <thead>
                                        <tr class="bg-secondary text-white">
                                            <th class="text-center align-middle" width="5%">No</th>
                                            <th class="align-middle" width="25%">Nama</th>
                                            <th class="text-center align-middle" width="10%">Harga</th>
                                            <th class="text-center align-middle" width="5%">Qty</th>
                                            <th class="text-center align-middle" width="10%">Total</th>
                                            <th class="text-center align-middle" width="20%">Dokter</th>
                                            <th class="text-center align-middle" width="15%">Date</th>
                                            <th class="text-center align-middle" width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center align-middle">1</td>
                                            <td class="align-middle">Paracetamol 500 mg Tablet</td>
                                            <td class="text-left align-middle">Rp 660</td>
                                            <td class="text-center align-middle">6</td>
                                            <td class="text-left align-middle">Rp 3,960</td>
                                            <td class="align-middle">JULI OSMIDA PURBA</td>
                                            <td class="text-center align-middle">2025-04-25</td>
                                            <td class="text-center align-middle">
                                                <button class="btn btn-xs btn-outline-danger btn-hapus-item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
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
                                    <h3 class="card-title" style="font-size: 0.9rem;"><i class="fas fa-file-invoice-dollar mr-1"></i>Tagihan dan Metode Pembayaran</h3>
                                </div>
                                <div class="card-body p-2">
                                    <!-- Bagian Perhitungan Tagihan -->
                                    <h6 class="font-weight-bold text-secondary mb-1" style="font-size: 0.8rem;"><i class="fas fa-calculator mr-1"></i>Perhitungan Tagihan</h6>
                                    
                                    <!-- Baris 1: Sub Total dan Administrasi -->
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label class="col-sm-4 col-form-label-sm font-weight-bold" style="font-size: 0.7rem; padding-right: 0;">Sub Total</label>
                                                <label class="col-sm-1 col-form-label-sm" style="font-size: 0.7rem; padding: 0;">:</label>
                                                <div class="col-sm-7" style="padding-left: 0;">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light py-0 px-1" style="font-size: 0.7rem;">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm text-right py-0" style="font-size: 0.7rem; height: 24px;" id="sub_total" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label class="col-sm-4 col-form-label-sm font-weight-bold" style="font-size: 0.7rem; padding-right: 0;">Administrasi</label>
                                                <label class="col-sm-1 col-form-label-sm" style="font-size: 0.7rem; padding: 0;">:</label>
                                                <div class="col-sm-7" style="padding-left: 0;">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light py-0 px-1" style="font-size: 0.7rem;">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" id="administrasi" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Baris 2: Potongan dan Materai -->
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label class="col-sm-4 col-form-label-sm font-weight-bold" style="font-size: 0.7rem; padding-right: 0;">Potongan</label>
                                                <label class="col-sm-1 col-form-label-sm" style="font-size: 0.7rem; padding: 0;">:</label>
                                                <div class="col-sm-7" style="padding-left: 0;">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light py-0 px-1" style="font-size: 0.7rem;">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" id="potongan" value="0" readonly>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-sm btn-outline-secondary py-0 px-1" style="font-size: 0.7rem; height: 24px;" data-toggle="modal" data-target="#modalDiskon">
                                                                <i class="fas fa-tag"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label class="col-sm-4 col-form-label-sm font-weight-bold" style="font-size: 0.7rem; padding-right: 0;">Materai</label>
                                                <label class="col-sm-1 col-form-label-sm" style="font-size: 0.7rem; padding: 0;">:</label>
                                                <div class="col-sm-7" style="padding-left: 0;">
                                                    <select class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" id="materai">
                                                        <option value="0" selected>0</option>
                                                        <option value="6000">6.000</option>
                                                        <option value="10000">10.000</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
<!-- Baris 3: Total (Full Width) -->
<div class="row mb-1">
    <div class="col-md-6">
        <div class="form-group row mb-1">
            <label class="col-sm-4 col-form-label-sm font-weight-bold text-danger" style="font-size: 0.7rem; padding-right: 0;">Total</label>
            <label class="col-sm-1 col-form-label-sm" style="font-size: 0.7rem; padding: 0;">:</label>
            <div class="col-sm-7" style="padding-left: 0;">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-danger text-white py-0 px-1" style="font-size: 0.7rem;">Rp</span>
                    </div>
                    <input type="text" class="form-control form-control-sm bg-danger text-white font-weight-bold py-0" style="font-size: 0.7rem; height: 24px;" id="total" value="3,960" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
                                    
                                    <!-- Baris 4: Tagihan dan Harus Dibayar -->
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label class="col-sm-4 col-form-label-sm font-weight-bold text-primary" style="font-size: 0.7rem; padding-right: 0;">Tagihan</label>
                                                <label class="col-sm-1 col-form-label-sm" style="font-size: 0.7rem; padding: 0;">:</label>
                                                <div class="col-sm-7" style="padding-left: 0;">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-dark text-white py-0 px-1" style="font-size: 0.7rem;">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm bg-dark text-white font-weight-bold py-0" style="font-size: 0.7rem; height: 24px;" id="tagihan" value="3,960" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-0">
                                                <label class="col-sm-4 col-form-label-sm font-weight-bold text-danger" style="font-size: 0.7rem; padding-right: 0;">Harus Dibayar</label>
                                                <label class="col-sm-1 col-form-label-sm" style="font-size: 0.7rem; padding: 0;">:</label>
                                                <div class="col-sm-7" style="padding-left: 0;">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-danger text-white py-0 px-1" style="font-size: 0.7rem;">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm bg-danger text-white font-weight-bold py-0" style="font-size: 0.7rem; height: 24px;" id="kurang_bayar" value="3,960" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr class="my-1">
                                    
                                    <!-- Metode Pembayaran -->
                                    <div class="mt-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="multi_payment">
                                                <label class="custom-control-label" for="multi_payment" style="font-size: 0.7rem;">Multi Payment</label>
                                            </div>
                                        </div>
                                        
                                        <!-- Pembayaran 1 -->
                                        <div class="row mb-1 payment-method">
                                            <div class="col-md-2 pr-0">
                                                <label class="col-form-label-sm font-weight-bold" style="font-size: 0.7rem;">Bayar (1) :</label>
                                            </div>
                                            <div class="col-md-2 px-1">
                                                <select class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" id="payment_method_1">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="debit" selected>Debit Card</option>
                                                    <option value="credit">Credit Card</option>
                                                    <option value="transfer">Transfer</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 px-1">
                                                <input type="text" class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" placeholder="Nominal Rupiah">
                                            </div>
                                            <div class="col-md-3 px-1">
                                                <select class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" id="payment_type_1">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="bca" selected>Bank OCBC NISP</option>
                                                    <option value="bni">BNI</option>
                                                    <option value="bri">BRI</option>
                                                    <option value="mandiri">Mandiri</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 pl-1">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text py-0 px-1" style="font-size: 0.7rem; height: 24px;">Ref:</span>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Pembayaran 2 -->
                                        <div class="row mb-1 payment-method">
                                            <div class="col-md-2 pr-0">
                                                <label class="col-form-label-sm font-weight-bold" style="font-size: 0.7rem;">Bayar (2) :</label>
                                            </div>
                                            <div class="col-md-2 px-1">
                                                <select class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" id="payment_method_2">
                                                    <option value="" selected>-- Pilih --</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="debit">Debit Card</option>
                                                    <option value="credit">Credit Card</option>
                                                    <option value="transfer">Transfer</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 px-1">
                                                <input type="text" class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" placeholder="Nominal Rupiah">
                                            </div>
                                            <div class="col-md-3 px-1">
                                                <select class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" id="payment_type_2">
                                                    <option value="" selected>-- Pilih --</option>
                                                    <option value="bca">Bank OCBC NISP</option>
                                                    <option value="bni">BNI</option>
                                                    <option value="bri">BRI</option>
                                                    <option value="mandiri">Mandiri</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 pl-1">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text py-0 px-1" style="font-size: 0.7rem; height: 24px;">Ref:</span>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Pembayaran 3 -->
                                        <div class="row payment-method">
                                            <div class="col-md-2 pr-0">
                                                <label class="col-form-label-sm font-weight-bold" style="font-size: 0.7rem;">Bayar (3) :</label>
                                            </div>
                                            <div class="col-md-2 px-1">
                                                <select class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" id="payment_method_3">
                                                    <option value="" selected>-- Pilih --</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="debit">Debit Card</option>
                                                    <option value="credit">Credit Card</option>
                                                    <option value="transfer">Transfer</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 px-1">
                                                <input type="text" class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" placeholder="Nominal Rupiah">
                                            </div>
                                            <div class="col-md-3 px-1">
                                                <select class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" id="payment_type_3">
                                                    <option value="" selected>-- Pilih --</option>
                                                    <option value="bca">Bank OCBC NISP</option>
                                                    <option value="bni">BNI</option>
                                                    <option value="bri">BRI</option>
                                                    <option value="mandiri">Mandiri</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 pl-1">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text py-0 px-1" style="font-size: 0.7rem; height: 24px;">Ref:</span>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm py-0" style="font-size: 0.7rem; height: 24px;" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tombol aksi di bagian bawah card -->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between">
                                            <button type="button" class="btn btn-sm btn-secondary py-0 px-2" style="font-size: 0.7rem; height: 24px;">
                                                <i class="fas fa-arrow-left mr-1"></i>Kembali
                                            </button>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-danger py-0 px-2 mr-1" style="font-size: 0.7rem; height: 24px;">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success py-0 px-2" style="font-size: 0.7rem; height: 24px;">
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
                <form id="formTambahTindakan">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_tindakan" class="font-weight-bold">Pilih Tindakan <span class="text-danger">*</span></label>
                                <select class="form-control" id="modal_tindakan" required>
                                    <option value="">-- Pilih Tindakan --</option>
                                    <option value="konsultasi">Konsultasi Dokter</option>
                                    <option value="injeksi">Injeksi</option>
                                    <option value="infus">Pemasangan Infus</option>
                                    <option value="ekg">EKG</option>
                                    <option value="rontgen">Rontgen</option>
                                    <option value="lab_darah">Lab Darah Lengkap</option>
                                    <option value="lab_urine">Lab Urine</option>
                                    <option value="jahit_luka">Jahit Luka</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_petugas" class="font-weight-bold">Petugas <span class="text-danger">*</span></label>
                                <select class="form-control" id="modal_petugas" required>
                                    <option value="">-- Pilih Petugas --</option>
                                    <option value="dr_juli">Dr. JULI OSMIDA PURBA</option>
                                    <option value="dr_sari">Dr. SARI INDAH PRATIWI</option>
                                    <option value="dr_budi">Dr. BUDI SANTOSO</option>
                                    <option value="ns_rina">Ns. RINA MARLINA</option>
                                    <option value="ns_dina">Ns. DINA KARTIKA</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_tarif" class="font-weight-bold">Tarif <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="modal_tarif" placeholder="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal_qty" class="font-weight-bold">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="modal_qty" value="1" min="1" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Batal
                </button>
                <button type="button" class="btn btn-secondary" id="btnSimpanTindakan">
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
                                <select class="form-control" id="jenis_potongan">
                                    <option value="discount">Discount</option>
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
                                    <input type="text" class="form-control" id="besar_potongan" placeholder="10">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="uraian_potongan" class="font-weight-bold">Uraian Potongan</label>
                        <input type="text" class="form-control" id="uraian_potongan" placeholder="Masukkan Uraian Potongan" value="Masukkan Uraian Potongan">
                    </div>
                    
                    <div class="form-group">
                        <label for="poli_potongan" class="font-weight-bold">Poli</label>
                        <select class="form-control" id="poli_potongan">
                            <option value="">-- Pilih Poli --</option>
                            <option value="poli_umum" selected>POLI GIGI & MULUT</option>
                            <option value="poli_anak">POLI ANAK</option>
                            <option value="poli_kandungan">POLI KANDUNGAN</option>
                            <option value="poli_mata">POLI MATA</option>
                            <option value="poli_tht">POLI THT</option>
                            <option value="poli_jantung">POLI JANTUNG</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Batal
                </button>
                <button type="button" class="btn btn-secondary" id="btnTerapkanDiskon">
                    <i class="fas fa-check mr-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Format angka dengan pemisah ribuan
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }
        
        // Parse rupiah string ke number
        function parseRupiah(rupiah) {
            return parseInt(rupiah.toString().replace(/\D/g, '')) || 0;
        }
        
        // Event handler untuk perubahan nilai
        $('#administrasi, #materai').on('change keyup', function() {
            hitungTotal();
        });
        
        // Multi payment toggle
        $('#multi_payment').change(function() {
            if($(this).is(':checked')) {
                $('.payment-method').show();
            } else {
                $('.payment-method:not(:first)').hide();
            }
        });
        
        // ===== MODAL TAMBAH TINDAKAN =====
        
        // Data tarif tindakan
        const tarifTindakan = {
            'konsultasi': ,
            'injeksi': ,
            'infus': ,
            'ekg': ,
            'rontgen': ,
            'lab_darah': ,
            'lab_urine': ,
            'jahit_luka': 
        };
        
        // Ketika tindakan dipilih, otomatis isi tarif
        $('#modal_tindakan').change(function() {
            const tindakan = $(this).val();
            if (tindakan && tarifTindakan[tindakan]) {
                $('#modal_tarif').val(formatRupiah(tarifTindakan[tindakan]));
                hitungTotalTindakan();
            } else {
                $('#modal_tarif').val('');
                $('#modal_total').val('');
            }
        });
        
        // Hitung total ketika tarif atau qty berubah
        $('#modal_tarif, #modal_qty').on('keyup change', function() {
            hitungTotalTindakan();
        });
        
        function hitungTotalTindakan() {
            const tarif = parseRupiah($('#modal_tarif').val());
            const qty = parseInt($('#modal_qty').val()) || 0;
            const total = tarif * qty;
            
            $('#modal_total').val(formatRupiah(total));
        }
        
        // Simpan tindakan
        
        // Update nomor urut setelah hapus item
        function updateNomorUrut() {
            $('#detail-table tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }
        
        // ===== MODAL DISKON =====
        
        // Terapkan diskon
        $('#btnTerapkanDiskon').click(function() {
            const besarPotongan = parseRupiah($('#besar_potongan').val());
            const jenisPotongan = $('#jenis_potongan option:selected').text();
            const uraianPotongan = $('#uraian_potongan').val();
            
            if (besarPotongan <= 0) {
                alert('Mohon masukkan besar potongan');
                return;
            }
            
            const subTotal = parseRupiah($('#sub_total').val());
            
            // Pastikan potongan tidak melebihi sub total
            if (besarPotongan > subTotal) {
                alert('Potongan tidak boleh melebihi sub total');
                return;
            }
            
            $('#potongan').val(formatRupiah(besarPotongan));
            hitungTotal();
            $('#modalDiskon').modal('hide');
            
            alert('Potongan berhasil diterapkan');
        });
        
        // Hapus diskon
        $('#btnHapusDiskon').click(function() {
            $('#potongan').val('0');
            $('#formDiskon')[0].reset();
            $('#uraian_potongan').val('Masukkan Uraian Potongan'); // Reset to default
            hitungTotal();
            $('#modalDiskon').modal('hide');
            
            alert('Potongan berhasil dihapus');
        });
        
        // Reset modal diskon ketika dibuka
        $('#modalDiskon').on('show.bs.modal', function() {
            const currentDiskon = parseRupiah($('#potongan').val());
            if (currentDiskon > 0) {
                $('#besar_potongan').val(formatRupiah(currentDiskon));
            }
        });
        
        // Format input rupiah saat mengetik
        $(document).on('keyup', 'input[type="text"]', function() {
            if ($(this).attr('id') === 'modal_tarif' || $(this).attr('id') === 'besar_potongan' || $(this).attr('id') === 'administrasi') {
                let value = $(this).val().replace(/\D/g, '');
                if (value) {
                    $(this).val(value);
                }
            }
        });
        
        // Format nominal pembayaran
        $(document).on('keyup', 'input[placeholder*="Nominal"]', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value) {
                $(this).val(value);
            }
        });
        
        // Init
        $('.payment-method:not(:first)').hide();
        hitungTotal();
    });
</script>
@endsection

@section('styles')
<style>
    /* Ukuran font dan padding yang lebih kecil */
    body {
        font-size: 0.8rem;
    }
    
    /* Tabel dengan tinggi optimal */
    .table-responsive {
        height: 200px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-bottom: 0.5rem;
    }
    
    /* Header tabel yang tetap (sticky) dengan ukuran lebih kecil */
    .table th {
        position: sticky;
        top: 0;
        background-color: #6c757d;
        color: white;
        z-index: 10;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        padding: 0.3rem 0.3rem;
        font-size: 0.75rem;
    }
    
    .table td {
        padding: 0.25rem 0.3rem;
        font-size: 0.75rem;
    }
    
    /* Card dengan ukuran lebih kecil */
    .card {
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        border: none;
        margin-bottom: 0.5rem;
    }
    
    .card-header {
        padding: 0.4rem 0.5rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,0.125);
        font-weight: 600;
    }
    
    .card-title {
        font-size: 0.85rem;
        margin-bottom: 0;
    }
    
    .card-body {
        padding: 0.4rem 0.5rem;
    }
    
    /* Form controls lebih kecil */
    .form-control-sm, .form-control {
        height: calc(1.5em + 0.4rem + 2px);
        padding: 0.1rem 0.4rem;
        font-size: 0.75rem;
    }
    
    .input-group-text {
        padding: 0.1rem 0.4rem;
        font-size: 0.75rem;
    }
    
    /* Label lebih kecil dengan margin minimal */
    .col-form-label, .col-form-label-sm {
        padding-top: 0.1rem;
        padding-bottom: 0.1rem;
        font-size: 0.75rem;
        margin-bottom: 0;
    }
    
    /* Form group dengan margin minimal */
    .form-group {
        margin-bottom: 0.3rem;
    }
    
    /* Button lebih kecil */
    .btn {
        padding: 0.15rem 0.4rem;
        font-size: 0.75rem;
    }
    
    /* Spacing lebih kecil */
    .mt-3 {
        margin-top: 0.5rem !important;
    }
    
    .mb-2 {
        margin-bottom: 0.3rem !important;
    }
    
    /* Mengurangi tinggi payment method sections */
    .payment-method {
        margin-bottom: 0.3rem;
        padding-bottom: 0.3rem;
    }
    
    .payment-method h6 {
        font-size: 0.8rem;
        margin-bottom: 0.2rem;
    }
    
    /* Separator antara perhitungan dan pembayaran */
    .border-right {
        border-right: 1px solid #dee2e6;
    }
    
    /* Horizontal rule yang lebih tipis */
    hr {
        margin-top: 0.3rem;
        margin-bottom: 0.3rem;
        border-top: 1px solid rgba(0,0,0,0.1);
    }
</style>
@endsection
