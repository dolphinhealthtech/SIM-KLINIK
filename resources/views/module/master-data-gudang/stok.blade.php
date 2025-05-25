@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Stok obat</h1>
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
                            <div class="card-header">
                                <h3 class="card-title">Stok Obat / Alkes</h3>
                            </div>
                            <div class="card-body">
                                <table id="stoktabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th class="text-center" width="20%">Kode</th>
                                            <th class="text-center" width="45%">Nama Obat / Alkes</th>
                                            <th class="text-center" width="10%">Total Stok</th>
                                            <th class="text-center" width="20%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Mengelompokkan stok berdasarkan kode obat
                                            $groupedStok = [];
                                            foreach ($stok as $item) {
                                                $kodeObat = $item->kode_obat_alkes;
                                                if (!isset($groupedStok[$kodeObat])) {
                                                    $groupedStok[$kodeObat] = [
                                                        'kode' => $kodeObat,
                                                        'nama' => $item->nama_obat_alkes,
                                                        'total_qty' => 0,
                                                        'items' => []
                                                    ];
                                                }
                                                $groupedStok[$kodeObat]['total_qty'] += $item->qty;
                                                $groupedStok[$kodeObat]['items'][] = $item;
                                            }
                                        @endphp

                                        @foreach ($groupedStok as $kodeObat => $group)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $group['kode'] }}</td>
                                                <td>{{ $group['nama'] }}</td>
                                                <td class="text-center">{{ $group['total_qty'] }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-primary detail-btn" 
                                                        data-kode="{{ $group['kode'] }}"
                                                        data-nama="{{ $group['nama'] }}"
                                                        data-toggle="modal" 
                                                        data-target="#detailModal">
                                                        Detail
                                                    </button>
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

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailModalLabel">Detail Obat: <span id="modalTitle"></span></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="detailTable" class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center" width="15%">ID</th>
                                    <th class="text-center" width="25%">Tanggal Masuk</th>
                                    <th class="text-center" width="25%">Expired</th>
                                    <th class="text-center" width="15%">Stok</th>
                                </tr>
                            </thead>
                            <tbody id="detailTableBody">
                                <!-- Data akan diisi melalui JavaScript -->
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th colspan="4" class="text-right">Total Stok:</th>
                                    <th class="text-center" id="totalStok">0</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function() {
        // DataTable untuk tabel utama
        $("#stoktabel").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [
                "csv",
                "excel",
                "pdf",
                "print",
            ],
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
            }
        }).buttons().container().appendTo('#stoktabel_wrapper .col-md-6:eq(0)');
        
        // Variabel untuk menyimpan data stok
        let stokData = @json($stok);
        
        // Event handler untuk tombol detail
        $('.detail-btn').on('click', function() {
            const kode = $(this).data('kode');
            const nama = $(this).data('nama');
            
            // Set judul modal
            $('#modalTitle').text(kode + ' - ' + nama);
            
            // Filter data berdasarkan kode
            const filteredItems = stokData.filter(item => item.kode_obat_alkes === kode);
            
            // Hitung total stok
            let totalStok = 0;
            filteredItems.forEach(item => {
                totalStok += parseInt(item.qty || 0);
            });
            
            // Isi tabel detail
            let tableHtml = '';
            filteredItems.forEach((item, index) => {
                const tanggalMasuk = item.tanggal_terima_obat ? new Date(item.tanggal_terima_obat).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }) : '-';
                
                const tanggalExpired = item.expired ? new Date(item.expired).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }) : '-';
                
                tableHtml += `
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td class="text-center">${item.id || '-'}</td>
                        <td class="text-center">${tanggalMasuk}</td>
                        <td class="text-center">${tanggalExpired}</td>
                        <td class="text-center">${item.qty || 0}</td>
                    </tr>
                `;
            });
            
            $('#detailTableBody').html(tableHtml);
            $('#totalStok').text(totalStok);
            
            // Inisialisasi DataTable untuk tabel detail
            if ($.fn.DataTable.isDataTable('#detailTable')) {
                $('#detailTable').DataTable().destroy();
            }
            
            $('#detailTable').DataTable({
                "responsive": true,
                "lengthChange": false,
                "searching": false,
                "paging": true,
                "info": true,
                "ordering": true,
                "autoWidth": false,
                "pageLength": 10,
                "language": {
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        });
    });
</script>
@endsection












