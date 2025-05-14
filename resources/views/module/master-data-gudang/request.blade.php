@extends('layouts.dashbord')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Request Obat Klinik</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Manajemen Obat</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                
                <!-- Info Boxes -->
                <div class="row mb-3">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary py-1">
                            <div class="inner">
                                <h6>{{ count($requestData ?? []) }}</h6>
                                <p class="mb-0">Total Request</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary py-1">
                            <div class="inner">
                                <h6>{{ count($approveData ?? []) }}</h6>
                                <p class="mb-0">Menunggu Approval</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary py-1">
                            <div class="inner">
                                <h6>{{ count($stokData ?? []) }}</h6>
                                <p class="mb-0">Jenis Obat Tersedia</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-pills"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary py-1">
                            <div class="inner">
                                <h6>0</h6>
                                <p class="mb-0">Stok Obat Menipis</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>

                                <!-- Action Button -->
                <div class="row mb-2">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addRequestModal">
                            <i class="fas fa-plus-circle"></i> Buat Request Obat Baru
                        </button>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="row">
                    <!-- Left Column: Approve Data Obat -->
                    <div class="col-md-6">
                        <div class="card card-outline">
                            <div class="card-header bg-white">
                                <h3 class="card-title">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Approve Data Obat
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="approveTable" class="table table-bordered table-striped table-hover">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Nama Obat</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-center" width="25%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($approveData ?? [] as $item)
                                            <tr class="{{ $loop->even ? 'bg-light' : '' }}">
                                                <td class="text-center">{{ $item->id }}</td>
                                                <td>{{ $item->nama_obat }}</td>
                                                <td class="text-center">{{ $item->jumlah }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-success btn-sm approve-btn" data-id="{{ $item->id }}">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                    <button class="btn btn-danger btn-sm reject-btn" data-id="{{ $item->id }}">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Request Obat -->
                    <div class="col-md-6">
                        <div class="card card-outline">
                            <div class="card-header bg-white">
                                <h3 class="card-title">
                                    <i class="fas fa-clipboard-list mr-1"></i>
                                    Request Obat
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="requestTable" class="table table-bordered table-striped table-hover">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="text-center" width="20%">Kode</th>
                                            <th class="text-center">Nama Obat</th>
                                            <th class="text-center" width="15%">Jumlah</th>
                                            <th class="text-center" width="20%">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requestData ?? [] as $item)
                                            <tr class="{{ $loop->even ? 'bg-light' : '' }}">
                                                <td class="text-center">{{ $item->kode }}</td>
                                                <td>{{ $item->nama_obat }}</td>
                                                <td class="text-center">{{ $item->jumlah }}</td>
                                                <td class="text-center">{{ $item->tanggal }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row: Stok Obat -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-outline">
                            <div class="card-header bg-white">
                                <h3 class="card-title">
                                    <i class="fas fa-pills mr-1"></i>
                                    Stok Obat Klinik 
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="stokTable" class="table table-bordered table-striped table-hover">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th class="text-center" width="15%">Kode</th>
                                            <th class="text-center">Nama Obat</th>
                                            <th class="text-center" width="15%">Jumlah</th>
                                            <th class="text-center" width="15%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stokData ?? [] as $index => $item)
                                            <tr class="{{ $loop->even ? 'bg-light' : '' }}">
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">{{ $item->kode }}</td>
                                                <td>{{ $item->nama_obat }}</td>
                                                <td class="text-center">{{ $item->jumlah }}</td>
                                                <td class="text-center">
                                                    @if($item->jumlah > 100)
                                                        <span class="badge badge-success">Stok Aman</span>
                                                    @elseif($item->jumlah > 50)
                                                        <span class="badge badge-warning">Stok Menengah</span>
                                                    @else
                                                        <span class="badge badge-danger">Stok Menipis</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <!-- Modal Tambah Request -->
    <div class="modal fade" id="addRequestModal" tabindex="-1" role="dialog" aria-labelledby="addRequestModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-white">
                    <h5 class="modal-title" id="addRequestModalLabel">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Request Obat
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addRequestForm" action="{{ route('request.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kode_request">Kode Request</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-primary"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="kode_request" name="kode_request" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Item Request Container -->
                        <div class="request-items">
                            <div class="card mb-3 border-secondary request-item">
                                <div class="card-header bg-white">
                                    <h6 class="mb-0"><i class="fas fa-pills mr-1"></i> Item Obat #1</h6>
                                    <button type="button" class="close text-dark remove-item" style="display: none;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Obat</label>
                                                <select class="form-control select2" name="items[0][obat_id]" style="width: 100%;">
                                                    <option value="">Pilih Obat</option>
                                                    @foreach ($obatList ?? [] as $obat)
                                                        <option value="{{ $obat->id }}" 
                                                                data-kode="{{ $obat->kode_barang }}" 
                                                                data-nama="{{ $obat->nama_barang }}">
                                                            {{ $obat->kode_barang }} - {{ $obat->nama_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="items[0][kode_obat]" class="kode-obat-input">
                                                <input type="hidden" name="items[0][nama_obat]" class="nama-obat-input">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Jumlah</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-info"><i class="fas fa-hashtag"></i></span>
                                                    </div>
                                                    <input type="number" class="form-control" name="items[0][jumlah]" min="1" value="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Tambahkan ke Tabel</label>
                                                <button type="button" class="btn btn-success btn-block add-to-table-btn">
                                                    <i class="fas fa-plus"></i> Tambahkan ke Tabel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        
                        <div class="card border-secondary">
                            <div class="card-header bg-white">
                                <h6 class="mb-0"><i class="fas fa-list mr-1"></i> Daftar Item Request</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="itemTable">
                                        <thead class="bg-white">
                                            <tr>
                                                <th class="text-center" width="5%">No</th>
                                                <th class="text-center" width="15%">Kode Obat</th>
                                                <th class="text-center">Nama Obat</th>
                                                <th class="text-center" width="15%">Jumlah</th>
                                                <th class="text-center" width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemTableBody">
                                            <tr id="emptyRow" class="bg-light">
                                                <td colspan="5" class="text-center">Belum ada item yang ditambahkan</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden input to store table data -->
                        <input type="hidden" name="tableItems" id="tableItems" value="[]">
                        
                        <div class="mt-4 text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            $("#approveTable, #requestTable, #stokTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ data keseluruhan)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "stripeClasses": ['', 'bg-light']
            });
            
            // Generate kode request saat modal dibuka
            $('#addRequestModal').on('shown.bs.modal', function() {
                // Generate kode request (contoh: REQ-YYYYMMDD-XXXX)
                const date = new Date();
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const random = Math.floor(1000 + Math.random() * 9000);
                const kodeRequest = `REQ-${year}${month}${day}-${random}`;
                $('#kode_request').val(kodeRequest);
                
                // Inisialisasi Select2
                $('.select2').select2({
                    dropdownParent: $('#addRequestModal'),
                    placeholder: "Pilih Obat",
                    allowClear: true,
                    width: '100%'
                });
            });
            
            // Fungsi untuk update nomor item
            function updateItemNumbers() {
                $('.request-item').each(function(index) {
                    $(this).find('.card-header h6').text('Item Obat #' + (index + 1));
                });
            }
            
            // Tambah item obat
            $('.add-item-btn').click(function() {
                const newIndex = $('.request-item').length;
                const newItem = $('.request-item').first().clone();
                
                // Reset nilai dan update nama field
                newItem.find('select').attr('name', `items[${newIndex}][obat_id]`).val('').trigger('change');
                newItem.find('.kode-obat-input').attr('name', `items[${newIndex}][kode_obat]`).val('');
                newItem.find('.nama-obat-input').attr('name', `items[${newIndex}][nama_obat]`).val('');
                newItem.find('input[type="number"]').attr('name', `items[${newIndex}][jumlah]`).val('1');
                newItem.find('.remove-item').show();
                
                $('.request-items').append(newItem);
                
                // Inisialisasi Select2 untuk elemen baru
                newItem.find('.select2').select2({
                    dropdownParent: $('#addRequestModal'),
                    placeholder: "Pilih Obat",
                    allowClear: true,
                    width: '100%'
                });
                
                // Update nomor item
                updateItemNumbers();
            });
            
            // Hapus item obat
            $(document).on('click', '.remove-item', function() {
                if ($('.request-item').length > 1) {
                    $(this).closest('.request-item').remove();
                    updateItemNumbers();
                } else {
                    alert('Minimal harus ada satu item obat!');
                }
            });
            
            // Saat obat dipilih, simpan kode dan nama obat ke hidden input
            $(document).on('change', '.select2', function() {
                const selectedOption = $(this).find('option:selected');
                const kodeObat = selectedOption.data('kode');
                const namaObat = selectedOption.data('nama');
                
                const itemContainer = $(this).closest('.request-item');
                itemContainer.find('.kode-obat-input').val(kodeObat);
                itemContainer.find('.nama-obat-input').val(namaObat);
            });
            
            // Tambahkan item ke tabel
            $(document).on('click', '.add-to-table-btn', function() {
                const itemContainer = $(this).closest('.request-item');
                const obatSelect = itemContainer.find('select');
                const jumlahInput = itemContainer.find('input[type="number"]');
                
                const obatId = obatSelect.val();
                const kodeObat = obatSelect.find('option:selected').data('kode');
                const namaObat = obatSelect.find('option:selected').data('nama');
                const jumlah = jumlahInput.val();
                
                if (!obatId) {
                    alert('Silakan pilih obat terlebih dahulu!');
                    return;
                }
                
                if (jumlah <= 0) {
                    alert('Jumlah harus lebih dari 0!');
                    return;
                }
                
                // Hapus pesan "Belum ada item"
                $('#emptyRow').remove();
                
                // Cek apakah obat sudah ada di tabel
                let existingRow = null;
                $('#itemTableBody tr').each(function() {
                    if ($(this).data('obat-id') == obatId) {
                        existingRow = $(this);
                        return false;
                    }
                });
                
                if (existingRow) {
                    // Tampilkan SweetAlert jika item sudah ada di tabel
                    Swal.fire({
                        icon: 'warning',
                        title: 'Item Sudah Ada',
                        text: `${namaObat} sudah ditambahkan ke dalam tabel. Silakan pilih obat lain.`,
                        confirmButtonText: 'OK'
                    });
                    return;
                } else {
                    // Tambahkan baris baru jika obat belum ada
                    const rowCount = $('#itemTableBody tr').length + 1;
                    const newRow = `
                        <tr data-obat-id="${obatId}" data-kode="${kodeObat}" data-nama="${namaObat}" data-jumlah="${jumlah}">
                            <td class="text-center">${rowCount}</td>
                            <td class="text-center">${kodeObat}</td>
                            <td>${namaObat}</td>
                            <td class="text-center">${jumlah}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-table-item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    $('#itemTableBody').append(newRow);
                }
                
                // Update hidden input dengan data tabel
                updateTableItems();
                
                // Reset form item
                obatSelect.val('').trigger('change');
                jumlahInput.val(1);
                
                // Tampilkan notifikasi sukses
                toastr.success('Item berhasil ditambahkan ke tabel');
            });
            
            // Hapus item dari tabel
            $(document).on('click', '.remove-table-item', function() {
                $(this).closest('tr').remove();
                
                // Jika tabel kosong, tambahkan pesan "Belum ada item"
                if ($('#itemTableBody tr').length === 0) {
                    $('#itemTableBody').html('<tr id="emptyRow"><td colspan="5" class="text-center">Belum ada item yang ditambahkan</td></tr>');
                } else {
                    // Update nomor urut
                    $('#itemTableBody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });
                }
                
                // Update hidden input dengan data tabel
                updateTableItems();
            });
            
            // Fungsi untuk mengupdate hidden input dengan data tabel
            function updateTableItems() {
                const tableItems = [];
                
                $('#itemTableBody tr').each(function() {
                    if (!$(this).attr('id') || $(this).attr('id') !== 'emptyRow') {
                        tableItems.push({
                            obat_id: $(this).data('obat-id'),
                            kode_obat: $(this).data('kode'),
                            nama_obat: $(this).data('nama'),
                            jumlah: $(this).data('jumlah')
                        });
                    }
                });
                
                $('#tableItems').val(JSON.stringify(tableItems));
            }
            
            // Submit form
            $('#addRequestForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validasi: pastikan ada item di tabel
                if ($('#itemTableBody tr').length === 0 || $('#emptyRow').length > 0) {
                    alert('Silakan tambahkan minimal satu item obat ke tabel!');
                    return;
                }
                
                // Kirim form
                this.submit();
            });
            
            // Refresh data
            function refreshData() {
                location.reload();
            }
            
            // Detail request
            $(document).on('click', '.detail-btn', function() {
                const id = $(this).data('id');
                
                // Simulasi data detail untuk tampilan
                // Nanti akan diganti dengan AJAX request ke server
                const detailData = {
                    kode: 'REQ-ABCD1234',
                    tanggal: '15 Juni 2023',
                    items: [
                        { no: 1, kode: 'OBT-0001', nama: 'Paracetamol 500mg', jumlah: 100 },
                        { no: 2, kode: 'OBT-0002', nama: 'Amoxicillin 500mg', jumlah: 50 }
                    ]
                };
                
                // Tampilkan data di modal
                $('#detail-kode').text(detailData.kode);
                $('#detail-tanggal').text(detailData.tanggal);
                
                let itemsHtml = '';
                detailData.items.forEach(function(item) {
                    itemsHtml += `
                        <tr>
                            <td class="text-center">${item.no}</td>
                            <td class="text-center">${item.kode}</td>
                            <td>${item.nama}</td>
                            <td class="text-center">${item.jumlah}</td>
                        </tr>
                    `;
                });
                
                $('#detail-items').html(itemsHtml);
                $('#detailRequestModal').modal('show');
            });
            
            // Approve request
            $(document).on('click', '.approve-btn', function() {
                const id = $(this).data('id');
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin menyetujui request ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Setujui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('request.approve') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        showConfirmButton: true
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat memproses permintaan'
                                });
                            }
                        });
                    }
                });
            });
            
            // Reject request
            $(document).on('click', '.reject-btn', function() {
                const id = $(this).data('id');
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin menolak request ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('request.reject') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        showConfirmButton: true
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat memproses permintaan'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
    <!-- Custom CSS for smaller icons -->
    <style>
        .small-box {
            height: 70px !important;
            background-color: #6C757D !important;
        }
        .small-box .icon {
            font-size: 40px !important;
            top: 30px !important;
            right: 30px !important;
            opacity: 0.8 !important;
        }
        .small-box .icon i {
            font-size: 40px !important;
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
            background-color: white !important;
        }
    </style>
@endsection
