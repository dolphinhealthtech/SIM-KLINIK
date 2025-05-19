@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1>Transaksi Apotek / Farmasi</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cariPasienModal">
                        <i class="fas fa-search"></i> Cari Pasien
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Step 1: Form Pasien -->
            <div id="step1" class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title">Data Pasien</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>No. Reg</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="no_reg" name="no_reg">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>No. RM</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="no_rm" name="no_rm">
                        </div>
                        <div class="col-md-5">
                            <button class="btn btn-info mr-2">Panggil</button>
                            <button class="btn btn-info mr-2">Auto</button>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>Nama</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="nama" name="nama">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>Alamat</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="alamat" name="alamat">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>Resep</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-4">
                            <select class="form-control" id="resep" name="resep">
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label>Dokter</label>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="checkbox" id="is_dokter">
                                    </div>
                                </div>
                                <select class="form-control" id="dokter" name="dokter">
                                    <option value="">-- Pilih Dokter --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>Rawat</label>
                        </div>
                        <div class="col-md-1 text-center">:</div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary btn-block">RAWAT JALAN</button>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="poli" name="poli">
                                <option value="">-- Pilih Poli --</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label>Jenis PX</label>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="jenis_px" name="jenis_px">
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-1">
                            <label>Penjamin</label>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="penjamin" name="penjamin">
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-primary" onclick="showStep2()">
                        <i class="fas fa-arrow-right"></i> Lanjut
                    </button>
                </div>
            </div>
            
            <!-- Step 2: Transaksi Obat -->
            <div id="step2" style="display: none;">
                <div class="row">
                    <!-- Panel Kiri - Form Resep -->
                    <div class="col-md-8 pr-2">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h3 class="card-title">Form Resep</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive border border-dark">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Item</th>
                                                <th>Kode Item</th>
                                                <th>Harga</th>
                                                <th>Diskon</th>
                                                <th>Kuantitas</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Baris kosong untuk space -->
                                            <tr style="height: 200px;">
                                                <td colspan="7"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="p-3 bg-light border mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <div class="col-md-2">
                                                    <button class="btn btn-info btn-sm rounded-circle">R/</button>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Barang :</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <select class="form-control" id="barang">
                                                        <option value="">-- Pilih Obat --</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-3">
                                                    <label>Qty :</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="number" class="form-control" id="qty">
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-3">
                                                    <label>Diskon :</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="checkbox" id="is_diskon">
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="diskon">
                                                    </div>
                                                    <small class="text-muted">(button mengubah disc rupiah)</small>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-3">
                                                    <label>Harga :</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <select class="form-control" id="harga_select">
                                                                <option value="">-- Pilih --</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control" id="harga_text">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-5"></div>
                                                <div class="col-md-7">
                                                    <button class="btn btn-info mr-2">Tambah</button>
                                                    <button class="btn btn-info">Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label>Barang :</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="d-flex">
                                                        <input type="text" class="form-control" id="barang_text2">
                                                        <span class="ml-2">(Poin)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <span class="text-danger">[+Embiis Rp.0]</span>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label>Sub Total :</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="border-bottom border-dark"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label>Total :</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="border-bottom border-dark"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-12 text-right">
                                                    <button class="btn btn-info mr-2">Simpan</button>
                                                    <button class="btn btn-info">Reload</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Panel Kanan - Informasi Resep -->
                    <div class="col-md-4 pl-2">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h3 class="card-title">Informasi Resep</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="border border-dark" style="height: 242px; background-color: #fff;"></div>
                                
                                <div class="p-3 bg-light border mt-3" style="min-height: 300px;">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label>No R:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="no_r">
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label>Jumlah:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="jumlah">
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label>Note:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="note">
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <button class="btn btn-info btn-block btn-sm">
                                                <i class="fas fa-file-download"></i> Load Per R/
                                            </button>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-info btn-block btn-sm">
                                                <i class="fas fa-file-download"></i> Load R/ Full
                                            </button>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-danger btn-block btn-sm">
                                                <i class="fas fa-download"></i> Download
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-secondary mr-2" onclick="showStep1()">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-success">
                            <i class="fas fa-check"></i> Selesai
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Cari Pasien -->
<div class="modal fade" id="cariPasienModal" tabindex="-1" role="dialog" aria-labelledby="cariPasienModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cariPasienModalLabel">Cari Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Isi modal akan ditambahkan nanti -->
                <p class="text-center">Fitur pencarian pasien akan ditambahkan nanti.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Pilih Pasien</button>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        margin-bottom: 1rem;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: 0.25rem;
    }
    .card-header {
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .h-100 {
        height: 100%!important;
    }
    .table {
        margin-bottom: 0;
    }
    .table thead th {
        vertical-align: middle;
        text-align: center;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        padding: 0.5rem;
        height: 42px;
    }
    .table td, .table th {
        border: 1px solid #dee2e6;
        padding: 0.5rem;
    }
    .btn {
        border-radius: 0.25rem;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }
    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .form-control {
        border-radius: 0.25rem;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    .border {
        border: 1px solid #dee2e6!important;
    }
    .border-dark {
        border-color: #343a40!important;
    }
    .bg-light {
        background-color: #f8f9fa!important;
    }
    .align-items-center {
        align-items: center!important;
    }
</style>

<script>
    function showStep1() {
        document.getElementById('step1').style.display = 'block';
        document.getElementById('step2').style.display = 'none';
    }
    
    function showStep2() {
        document.getElementById('step1').style.display = 'none';
        document.getElementById('step2').style.display = 'block';
    }
    
    $(document).ready(function() {
        // Inisialisasi tampilan awal
        showStep1();
    });
</script>
@endsection
