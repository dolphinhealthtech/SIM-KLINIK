
@extends('layouts.dashbord')

@section('content')
    <!-- CSS Kustom untuk tampilan yang lebih baik -->
    <style>
        .small-box {
            height: 80px !important;
            background-color: #6C757D !important;
        }
        .small-box .icon {
            font-size: 18px !important;
            top: 10px !important;
            right: 10px !important;
            opacity: 0.8 !important;
        }
        .small-box .icon i {
            font-size: 18px !important;
        }
        .small-box h6 {
            font-size: 14px !important;
            margin-bottom: 5px !important;
        }
        .small-box p {
            font-size: 12px !important;
        }
        .card-header {
            background-color: white !important;
        }
        .table thead th {
            background-color: #f8f9fa !important;
        }
        .clickable-row {
            cursor: pointer;
        }
        .clickable-row:hover {
            background-color: #f5f5f5;
        }
        .pagination-container {
            display: flex;
            align-items: center;
        }
        .pagination-container .page-number {
            width: 40px;
            text-align: center;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
            border-radius: 0.5rem;
        }
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            padding: 1rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .bg-highlight {
            background-color: rgba(255, 204, 204, 0.2) !important; /* Merah dengan opacity 0.2 */
            color: #333 !important;
        }

        .main-header .nav-links {
            display: flex;
            gap: 20px;
        }
        .main-header .nav-links a {
            color: #6c757d;
            text-decoration: none;
        }
        .main-header .nav-links a:hover {
            color: #007bff;
        }
        .main-header .right-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .main-footer {
            background-color: #fff;
            padding: 15px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            text-align: center;
            margin-top: 20px;
        }
        /* Definisi warna merah transparan untuk baris yang dipilih */
        .bg-merah-transparan {
            background-color: rgba(255, 0, 0, 0.15) !important; /* Merah dengan opacity rendah */
            color: #333 !important;
        }
        
        /* Definisi warna pink untuk baris default yang dipilih pertama kali */
        .bg-pink {
            background-color: rgba(255, 192, 203, 0.3) !important; /* Pink dengan opacity rendah */
            color: #333 !important;
        }
    </style>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Gudang Utama</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Gudang Utama</li>
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
                    <!-- Kolom Kiri - Daftar Permintaan -->
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Permintaan Obat</h3>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="filterKlinik">Filter Klinik:</label>
                                        <select class="form-control" id="filterKlinik">
                                            <option value="">Semua Klinik</option>
                                            <option value="Klinik Balaraja">Klinik Balaraja</option>
                                            <option value="Klinik Jaya">Klinik Jaya</option>
                                            <option value="Klinik Sentosa">Klinik Sentosa</option>
                                            <option value="Klinik Makmur">Klinik Makmur</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Halaman:</label>
                                        <div class="pagination-container">
                                            <button class="btn btn-default" id="prevPage"><i class="fas fa-chevron-left"></i></button>
                                            <input type="text" class="form-control page-number" id="currentPage" value="1" readonly>
                                            <button class="btn btn-default" id="nextPage"><i class="fas fa-chevron-right"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-bordered table-striped" id="permintaanTable">
                                    <thead>
                                        <tr>
                                            <th>Klinik</th>
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data akan diisi secara dinamis -->
                                    </tbody>
                                </table>

                                <div class="text-center mt-3">
                                    <button type="button" class="btn btn-success" id="konfirmasiPermintaanBtn">
                                        <i class="fas fa-check-circle"></i> Konfirmasi Permintaan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan - Form Permintaan -->
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Form Permintaan Obat</h3>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="obatSelect ">Pilih Obat:</label>
                                        <select class="form-control select2bs4" id="obatSelect">
                                            <option value="">Pilih Obat</option>
                                            <!-- Opsi obat akan diisi secara dinamis -->
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="jumlahObat">Jumlah:</label>
                                        <div class="d-flex">
                                            <input type="number" class="form-control" id="jumlahObat" placeholder="Jumlah" min="1" value="1">
                                            <button class="btn btn-info ml-2" id="addObatBtn">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-bordered table-striped" id="requestItemsTable">
                                    <thead>
                                        <tr>
                                            <th>Kode Obat</th>
                                            <th>Nama Obat</th>
                                            <th>Harga Dasar</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Item akan ditambahkan secara dinamis -->
                                    </tbody>
                                </table>

                                <div class="text-right mt-3">
                                    <button type="button" class="btn btn-success" id="konfirmasiRequestBtn">
                                        <i class="fas fa-save"></i> Simpan Permintaan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Detail Obat -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailModalLabel">Detail Permintaan Obat</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Klinik:</strong> <span id="detailKlinik"></span>
                        </div>
                        <div class="col-md-4">
                            <strong>Kode Permintaan:</strong> <span id="detailKodeRequest"></span>
                        </div>
                        <div class="col-md-4">
                            <strong>Tanggal:</strong> <span id="detailTanggal"></span>
                        </div>
                    </div>
                    
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th class="text-center" width="15%">Kode Obat</th>
                                <th width="50%">Nama Obat</th>
                                <th class="text-center" width="15%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="detailTableBody">
                            <!-- Detail item akan ditambahkan secara dinamis -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-success" id="approveRequestBtn">
                        <i class="fas fa-check"></i> Konfirmasi Permintaan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Data contoh untuk permintaan
            const allRequests = [
                {
                    id: 'BLR-20250512-0001',
                    klinik: 'Klinik Balaraja',
                    tanggal: '12-05-2025',
                    items: [
                        { kode: 'B00001', nama: 'Paracetamol 500 mg Tablet (PHAPROS Tbk)', jumlah: 5 }
                    ]
                },
                {
                    id: 'JYA-20250510-0002',
                    klinik: 'Klinik Jaya',
                    tanggal: '10-05-2025',
                    items: [
                        { kode: 'B00002', nama: 'Amoxicillin 500mg', jumlah: 10 },
                        { kode: 'B00003', nama: 'Ibuprofen 400mg', jumlah: 8 }
                    ]
                },
                {
                    id: 'SNT-20250509-0003',
                    klinik: 'Klinik Sentosa',
                    tanggal: '09-05-2025',
                    items: [
                        { kode: 'B00004', nama: 'Cetirizine 10mg', jumlah: 15 }
                    ]
                },
                {
                    id: 'MKM-20250508-0004',
                    klinik: 'Klinik Makmur',
                    tanggal: '08-05-2025',
                    items: [
                        { kode: 'B00005', nama: 'Omeprazole 20mg', jumlah: 12 }
                    ]
                }
            ];

            // Inisialisasi DataTable dengan opsi kustom
            const permintaanTable = $('#permintaanTable').DataTable({
                paging: false,
                searching: false,
                info: false,
                ordering: true,
                language: {
                    emptyTable: "Tidak ada data permintaan"
                },
                columnDefs: [
                    { orderable: true, targets: [0, 1, 2] }
                ]
            });

            // Fungsi untuk memfilter dan menampilkan permintaan
            function filterAndDisplayRequests() {
                const klinikFilter = $('#filterKlinik').val();
                
                // Kosongkan tabel
                permintaanTable.clear();
                
                // Filter dan tambahkan baris
                allRequests.forEach(req => {
                    if (!klinikFilter || req.klinik === klinikFilter) {
                        permintaanTable.row.add([
                            req.klinik,
                            req.id,
                            req.tanggal
                        ]).draw(false);
                    }
                });
                
                // Tambahkan class clickable-row ke semua baris
                $('#permintaanTable tbody tr').addClass('clickable-row');
                
                // Tambahkan atribut data ke baris
                $('#permintaanTable tbody tr').each(function(index) {
                    const rowData = permintaanTable.row(index).data();
                    const requestId = rowData[1];
                    const request = allRequests.find(r => r.id === requestId);
                    
                    if (request) {
                        $(this).attr('data-id', request.id);
                        $(this).attr('data-klinik', request.klinik);
                    }
                });
                
                // Sorot baris pertama jika ada dengan warna pink
                if (permintaanTable.rows().count() > 0) {
                    $('#permintaanTable tbody tr:first').addClass('bg-pink');
                }
            }

            // Terapkan filter saat pemilihan klinik berubah
            $('#filterKlinik').change(function() {
                filterAndDisplayRequests();
            });

            // Inisialisasi dengan semua permintaan
            filterAndDisplayRequests();

            // Variabel untuk melacak klik terakhir
            let lastClickTime = 0;
            let lastClickedRow = null;

            // Tangani klik pada baris permintaan
            $(document).on('click', '#permintaanTable tbody tr', function(e) {
                const now = new Date().getTime();
                const row = $(this);
                
                // Cek apakah ini double click (klik kedua dalam 300ms)
                if (lastClickedRow && lastClickedRow.is(row) && now - lastClickTime < 300) {
                    // Double click - tampilkan modal
                    const requestId = row.data('id');
                    showDetailModal(requestId);
                    
                    // Reset tracking
                    lastClickTime = 0;
                    lastClickedRow = null;
                } else {
                    // Single click - toggle warna
                    
                    // Hapus warna pink dari semua baris
                    $('#permintaanTable tbody tr').removeClass('bg-pink');
                    
                    // Toggle warna merah transparan
                    if (row.hasClass('bg-merah-transparan')) {
                        // Jika sudah merah, hapus warna
                        row.removeClass('bg-merah-transparan');
                    } else {
                        // Hapus warna merah dari semua baris lain
                        $('#permintaanTable tbody tr').removeClass('bg-merah-transparan');
                        // Tambahkan warna merah ke baris ini
                        row.addClass('bg-merah-transparan');
                    }
                    
                    // Update tracking untuk deteksi double click
                    lastClickTime = now;
                    lastClickedRow = row;
                    
                    // Simpan ID permintaan yang dipilih jika baris dipilih
                    if (row.hasClass('bg-merah-transparan')) {
                        const requestId = row.data('id');
                        $('#konfirmasiPermintaanBtn').data('selected-id', requestId);
                    } else {
                        // Hapus ID yang dipilih jika tidak ada baris yang dipilih
                        $('#konfirmasiPermintaanBtn').data('selected-id', null);
                    }
                }
            });

            // Fungsi untuk menampilkan modal detail
            function showDetailModal(requestId) {
                const request = allRequests.find(r => r.id === requestId);
                
                if (request) {
                    // Isi detail modal
                    $('#detailKlinik').text(request.klinik);
                    $('#detailKodeRequest').text(request.id);
                    $('#detailTanggal').text(request.tanggal);
                    
                    // Isi tabel detail
                    $('#detailTableBody').empty();
                    request.items.forEach((item, index) => {
                        $('#detailTableBody').append(`
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td class="text-center">${item.kode}</td>
                                <td>${item.nama}</td>
                                <td class="text-center">${item.jumlah}</td>
                            </tr>
                        `);
                    });
                    
                    // Simpan ID permintaan untuk tombol konfirmasi
                    $('#approveRequestBtn').data('request-id', requestId);
                    
                    // Tampilkan modal
                    $('#detailModal').modal('show');
                }
            }
            
            // Tangani klik pada tombol konfirmasi permintaan
            $('#konfirmasiPermintaanBtn').on('click', function() {
                const selectedId = $(this).data('selected-id');
                
                if (selectedId) {
                    showDetailModal(selectedId);
                } else {
                    // Jika tidak ada baris yang dipilih
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Silakan pilih permintaan terlebih dahulu!'
                    });
                }
            });
            
            // Tangani klik pada tombol konfirmasi di modal
            $('#approveRequestBtn').on('click', function() {
                const requestId = $(this).data('request-id');
                const request = allRequests.find(req => req.id === requestId);
                
                if (!request) return;
                
                Swal.fire({
                    title: 'Konfirmasi Permintaan',
                    text: "Apakah Anda yakin ingin mengkonfirmasi permintaan ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Konfirmasi!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tambahkan semua item ke tabel kanan
                        request.items.forEach(item => {
                            // Hasilkan harga acak untuk tujuan demo
                            const harga = Math.floor(5000 + Math.random() * 95000);
                            
                            // Tambahkan ke tabel
                            const newRow = `
                                <tr>
                                    <td>${item.kode}</td>
                                    <td>${item.nama}</td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID').format(harga)}</td>
                                    <td>${item.jumlah}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger delete-item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                            
                            $('#requestItemsTable tbody').append(newRow);
                        });
                        
                        // Tutup modal
                        $('#detailModal').modal('hide');
                        
                        // Tampilkan pesan sukses
                        Swal.fire(
                            'Berhasil!',
                            'Permintaan telah dikonfirmasi dan ditambahkan ke form.',
                            'success'
                        );
                    }
                });
            });

            // Hapus item dari tabel
            $(document).on('click', '.delete-item', function() {
                $(this).closest('tr').remove();
            });

            // Klik tombol tambah obat
            $('#addObatBtn').click(function() {
                const obat = $('#obatSelect').val();
                const jumlah = $('#jumlahObat').val();
                
                if (!obat || !jumlah || jumlah <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Harap isi semua field dengan benar!'
                    });
                    return;
                }
                
                // Cari item obat dari daftar
                const allObats = [];
                allRequests.forEach(req => {
                    req.items.forEach(item => {
                        if (!allObats.some(o => o.kode === item.kode)) {
                            allObats.push(item);
                        }
                    });
                });
                
                const selectedObat = allObats.find(item => item.nama === obat);
                
                if (!selectedObat) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Obat tidak ditemukan!'
                    });
                    return;
                }
                
                // Hasilkan harga acak untuk tujuan demo
                const harga = Math.floor(5000 + Math.random() * 95000);
                
                // Tambahkan ke tabel
                const newRow = `
                    <tr>
                        <td>${selectedObat.kode}</td>
                        <td>${selectedObat.nama}</td>
                        <td>Rp ${new Intl.NumberFormat('id-ID').format(harga)}</td>
                        <td>${jumlah}</td>
                        <td>
                            <button class="btn btn-sm btn-danger delete-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                
                $('#requestItemsTable tbody').append(newRow);
                
                // Reset form
                $('#obatSelect').val('').trigger('change');
                $('#jumlahObat').val(1);
                
                // Tampilkan pesan sukses
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Item berhasil ditambahkan ke form permintaan',
                    timer: 1500,
                    showConfirmButton: false
                });
            });

            // Konfirmasi permintaan button click
            $('#konfirmasiPermintaanBtn').click(function() {
                Swal.fire({
                    title: 'Konfirmasi Permintaan',
                    text: "Apakah Anda yakin ingin mengkonfirmasi permintaan yang dipilih?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Konfirmasi!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'Berhasil!',
                            'Permintaan telah dikonfirmasi.',
                            'success'
                        );
                    }
                });
            });

            // Konfirmasi stok button click
            $('#konfirmasiStokBtn').click(function() {
                Swal.fire({
                    title: 'Konfirmasi Permintaan Stok',
                    text: "Apakah Anda yakin ingin mengkonfirmasi permintaan stok yang dipilih?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Konfirmasi!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'Berhasil!',
                            'Permintaan stok telah dikonfirmasi.',
                            'success'
                        );
                    }
                });
            });

            // Isi dropdown obat saat halaman dimuat
            $(document).ready(function() {
                // Isi dropdown dengan semua obat dari semua permintaan
                const allObats = [];
                allRequests.forEach(req => {
                    req.items.forEach(item => {
                        if (!allObats.some(o => o.kode === item.kode)) {
                            allObats.push(item);
                        }
                    });
                });
                
                // Urutkan berdasarkan nama
                allObats.sort((a, b) => a.nama.localeCompare(b.nama));
                
                // Tambahkan ke dropdown
                $('#obatSelect').empty().append('<option value="">Pilih Obat</option>');
                allObats.forEach(item => {
                    $('#obatSelect').append(`<option value="${item.nama}">${item.kode} - ${item.nama}</option>`);
                });
            });
        });
    </script>


@endsection
