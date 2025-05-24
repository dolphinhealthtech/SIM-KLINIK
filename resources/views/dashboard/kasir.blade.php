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
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="kasirTable" class="table table-bordered table-striped thin-table-border">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Kode Faktur</th>
                                            <th class="text-center">No RM</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Poli</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Pilihan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter = 1; @endphp

                                        @foreach ($apotek as $apotekdata)
                                            <tr onclick="showDetail(event, this)" data-active="false"
                                            data-detail_obat='@json($apotekdata->detail_obat)' data-detail_tindakan='@json($apotekdata->detail_tindakan)'>
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td class="text-center">{{ $apotekdata->kode_faktur }}</td>
                                                <td class="text-center">{{ $apotekdata->no_rm }}</td>
                                                <td class="text-center">{{ $apotekdata->nama }}</td>
                                                <td class="text-center">{{ $apotekdata->poli }}</td>
                                                <td class="text-center">{{ number_format($apotekdata->total, 0, ',', '.') }}</td>
                                                <td class="text-center">{{ $apotekdata->tanggal }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('kasir.pembayaran', ['kode_faktur' => $apotekdata->kode_faktur, 'no_rawat' => $apotekdata->no_rawat]) }}" class="btn btn-sm btn-info">Bayar</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @foreach ($tindakan as $t)
                                            <tr onclick="showDetailTindakan(event, this)" data-active="false" data-no_rawat="{{ $t->no_rawat }}">
                                                <td class="text-center">{{ $counter++ }}</td>
                                                <td class="text-center">{{ $t->kode_faktur }}</td>
                                                <td class="text-center">{{ $t->nomor_rm }}</td>
                                                <td class="text-center">{{ $t->nama }}</td>
                                                <td class="text-center">{{ $t->data_soap->pendaftaran->poli->nama }}</td>
                                                <td class="text-center">{{ number_format($t->harga, 0, ',', '.') }}</td>
                                                <td class="text-center">{{ $t->created_at->format('Y-m-d') }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('kasir.pembayaran', ['kode_faktur' => $t->kode_faktur, 'no_rawat' => $t->no_rawat]) }}" class="btn btn-sm btn-info">Bayar</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
                                <table class="table table-bordered mb-0 thin-table-border" id="previewTabel">
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

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable sederhana
        $('#kasirTable').DataTable({
            "paging": true,
            "lengthChange": true,
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

    function showDetail(event, row) {
        // Cegah jika yang diklik adalah tombol atau anak elemen dalam kolom terakhir
        if (event.target.closest('td')?.cellIndex === 7) {
            return;
        }

        const isActive = row.getAttribute('data-active') === 'true';

        // Reset semua baris
        const rows = document.querySelectorAll('#kasirTable tbody tr');
        rows.forEach(r => {
            r.classList.remove('table-primary');
            r.setAttribute('data-active', 'false');
        });

        const tbody = document.querySelector('#previewTabel tbody');
        tbody.innerHTML = '';

        if (!isActive) {
            // Tandai baris ini sebagai aktif
            row.classList.add('table-primary');
            row.setAttribute('data-active', 'true');

            // Formatter untuk angka rupiah tanpa Rp dan ,00
            const formatRupiah = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            });

            const detail_tindakan = JSON.parse(row.getAttribute('data-detail_tindakan'));
            detail_tindakan.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.Jenis_tindakan}</td>
                        <td>${formatRupiah.format(item.harga)}</td>
                        <td>${item.jenis_pelaksana}</td>
                        <td>${formatRupiah.format(item.harga)}</td>
                    </tr>
                `;
            });

            const detail_obat = JSON.parse(row.getAttribute('data-detail_obat'));
            detail_obat.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.nama_obat_alkes}</td>
                        <td>${formatRupiah.format(item.harga)}</td>
                        <td>${item.qty}</td>
                        <td>${formatRupiah.format(item.total)}</td>
                    </tr>
                `;
            });
        }
    }

    // function showDetailTindakan(event, row) {
    //     // Cegah jika yang diklik adalah tombol atau anak elemen dalam kolom terakhir (index 7 karena 0-based)
    //     if (event.target.closest('td')?.cellIndex === 7) {
    //         return;
    //     }

    //     const isActive = row.getAttribute('data-active') === 'true';

    //     // Reset semua baris pada tabel tindakan (ganti #tindakanTable sesuai id tabelmu)
    //     const rows = document.querySelectorAll('#kasirTable tbody tr');
    //     rows.forEach(r => {
    //         r.classList.remove('table-primary');
    //         r.setAttribute('data-active', 'false');
    //     });

    //     // Target tbody preview tindakan
    //     const tbody = document.querySelector('#previewTabel tbody');
    //     tbody.innerHTML = '';

    //     if (!isActive) {
    //         row.classList.add('table-primary');
    //         row.setAttribute('data-active', 'true');

    //         const formatRupiah = new Intl.NumberFormat('id-ID', {
    //             minimumFractionDigits: 0,
    //             maximumFractionDigits: 0,
    //         });

    //         // Ambil data langsung dari kolom tabel
    //         const jenisTindakan = row.querySelector('td:nth-child(5)').textContent.trim();
    //         const jenisPelaksana = row.querySelector('td:nth-child(6)').textContent.trim();
    //         let hargaText = row.querySelector('td:nth-child(7)').textContent.trim();
    //         // Ubah format harga ke number (hapus titik ribuan)
    //         const hargaNumber = parseInt(hargaText.replace(/\./g, ''), 10) || 0;

    //         tbody.innerHTML = `
    //             <tr>
    //                 <td>${jenisTindakan}</td>
    //                 <td>${formatRupiah.format(hargaNumber)}</td>
    //                 <td>${jenisPelaksana}</td>
    //                 <td>${formatRupiah.format(hargaNumber)}</td>
    //             </tr>
    //         `;
    //     }
    // }

    function showDetailTindakan(event, row) {
        if (event.target.closest('td')?.cellIndex === 7) {
            return;
        }

        const isActive = row.getAttribute('data-active') === 'true';

        // Reset semua baris dan preview
        const rows = document.querySelectorAll('#kasirTable tbody tr');
        rows.forEach(r => {
            r.classList.remove('table-primary');
            r.setAttribute('data-active', 'false');
        });

        const tbody = document.querySelector('#previewTabel tbody');
        tbody.innerHTML = '';

        if (!isActive) {
            row.classList.add('table-primary');
            row.setAttribute('data-active', 'true');

            const noRawat = row.getAttribute('data-no_rawat');

            Swal.fire({
                icon: 'info',
                title: 'Memuat...',
                text: 'Mengambil kode faktur',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '/api/kasir/previewData',
                type: 'POST',
                data: { no_rawat: noRawat },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    Swal.close();
                    const formatRupiah = new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0,
                    });

                    let html = '';
                    data.forEach(item => {
                        html += `
                            <tr>
                                <td>${item.jenis_tindakan}</td>
                                <td>${formatRupiah.format(item.harga)}</td>
                                <td>${item.jenis_pelaksana}</td>
                                <td>${formatRupiah.format(item.harga)}</td>
                            </tr>
                        `;
                    });

                    tbody.innerHTML = html;
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    console.error('AJAX Error:', error);
                }
            });
        }
    }



</script>

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

@endsection
