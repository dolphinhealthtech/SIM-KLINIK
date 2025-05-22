@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pendataan Faktur Masuk Kasir</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Kasir</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Kolom Kiri - Tabel Utama -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <label for="tampil">Tampil</label>
                                    <select id="tampil" class="form-control-sm thin-border">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="cari">Cari:</label>
                                    <input type="search" id="cari" class="form-control-sm thin-border">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="kasirTable" class="table table-bordered table-striped thin-table-border">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No RM</th>
                                            <th>Nama</th>
                                            <th>Alamat</th>
                                            <th>Poli</th>
                                            <th>Total (Rp.)</th>
                                            <th>Tanggal</th>
                                            <th>Pilihan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Contoh data -->
                                        <tr>
                                            <td>1</td>
                                            <td>RM001</td>
                                            <td>John Doe</td>
                                            <td>Jl. Contoh No. 123</td>
                                            <td>Umum</td>
                                            <td>150,000</td>
                                            <td>2023-06-15</td>
                                            <td>
                                                <button class="btn btn-sm btn-success thin-border" onclick="bayarTagihan(1)">
                                                    <i class="fas fa-money-bill-wave"></i> Bayar
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>RM002</td>
                                            <td>Jane Smith</td>
                                            <td>Jl. Sample No. 456</td>
                                            <td>Gigi</td>
                                            <td>200,000</td>
                                            <td>2023-06-16</td>
                                            <td>
                                                <button class="btn btn-sm btn-success thin-border" onclick="bayarTagihan(2)">
                                                    <i class="fas fa-money-bill-wave"></i> Bayar
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 d-flex justify-content-between">
                                <div>
                                    <span>menampilkan 1 to 2 of 2 entries</span>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-default thin-border">Sebelumnya</button>
                                    <button class="btn btn-sm btn-default thin-border">Berikutnya</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kolom Kanan - Preview -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="mb-3">Preview UBL :</h5>
                            <div class="preview-container thin-border" style="min-height: 500px;">
                                <table class="table table-bordered mb-0 thin-table-border">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>Qty</th>
                                            <th>SubTot</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Preview data akan ditampilkan di sini -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable sederhana
        $('#kasirTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "zeroRecords": "No matching records found"
            }
        });
    });
    
    // Fungsi untuk menangani klik tombol bayar
    function bayarTagihan(id) {
        // Contoh data preview
        const previewData = [
            { nama: 'Konsultasi Dokter', harga: '100,000', qty: '1', subtotal: '100,000' },
            { nama: 'Obat Paracetamol', harga: '25,000', qty: '2', subtotal: '50,000' }
        ];
        
        // Tampilkan data di preview
        const previewTable = $('.preview-container table tbody');
        previewTable.empty();
        
        previewData.forEach(item => {
            previewTable.append(`
                <tr>
                    <td>${item.nama}</td>
                    <td>${item.harga}</td>
                    <td>${item.qty}</td>
                    <td>${item.subtotal}</td>
                </tr>
            `);
        });
        
        // Tambahkan baris total
        previewTable.append(`
            <tr class="font-weight-bold">
                <td colspan="3" class="text-right">Total:</td>
                <td>150,000</td>
            </tr>
        `);
        
        // Tampilkan alert untuk demo
        Swal.fire({
            title: 'Proses Pembayaran',
            text: `Memproses pembayaran untuk pasien dengan ID ${id}`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal'
        });
    }
</script>
@endsection

<style>
    /* Memastikan kedua kolom sejajar di bagian atas */
    .row {
        display: flex;
        flex-wrap: wrap;
    }
    
    .col-md-8, .col-md-4 {
        margin-top: 0;
    }
    
    /* Styling untuk preview container */
    .preview-container {
        border: 1px solid #dee2e6 !important;
        background-color: #f8f9fa;
    }
    
    /* Styling untuk tabel preview */
    .preview-container table {
        background-color: white;
    }
    
    /* Memastikan card memiliki tinggi yang sama */
    .card {
        height: 100%;
    }
    
    /* Mengurangi jarak antara konten dan footer */
    .content-wrapper {
        padding-bottom: 0;
        margin-bottom: 0;
    }
    
    /* Styling untuk footer */
    .main-footer {
        margin-top: 0;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    
    /* Mengurangi padding pada section content */
    .content {
        padding-bottom: 0.5rem;
    }
    
    /* Mengurangi margin pada container-fluid */
    .container-fluid {
        margin-bottom: 0;
    }

    /* Border tipis untuk input, select, dan tabel */
    .thin-border {
        border: 1px solid #ced4da !important;
    }

    .thin-table-border th,
    .thin-table-border td {
        border: 1px solid #dee2e6 !important;
    }

    /* Styling untuk form control */
    .form-control-sm {
        border-radius: 3px;
    }

    /* Mengurangi ketebalan border pada tabel */
    .table-bordered {
        border: 1px solid #dee2e6 !important;
    }
</style>
