@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pendataan Pelayanan Perawat</h1>
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="tanggalAwal">Tanggal Awal:</label>
                                    <input type="date" id="tanggalAwal" class="form-control" onfocus="this.showPicker()">
                                </div>
                                <div class="col-md-2">
                                    <label for="tanggalAkhir">Tanggal Akhir:</label>
                                    <input type="date" id="tanggalAkhir" class="form-control" onfocus="this.showPicker()">
                                </div>
                                <div class="col-md-3">
                                    <label for="filterPoli">Poli:</label>
                                    <select id="filterPoli" class="form-control">
                                        <option value="">-- Semua Poli --</option>
                                        @foreach ($data->pluck('poli.nama')->unique() as $poli)
                                            <option value="{{ $poli }}">{{ $poli }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filterDokter">Dokter:</label>
                                    <select id="filterDokter" class="form-control">
                                        <option value="">-- Semua Dokter --</option>
                                        @foreach ($data->pluck('dokter.namauser.name')->unique() as $dokter)
                                            <option value="{{ $dokter }}">{{ $dokter }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex flex-column justify-content-end align-items-end">
                                    <button id="btnFilter" class="btn btn-outline-secondary w-100">Filter</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="perawatTable" class="table table-bordered table-striped thin-table-border kasir-table" style="min-width: 1500px">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">No RM</th>
                                            <th class="text-center">Nama </th>
                                            <th class="text-center">No Rawat</th>
                                            <th class="text-center">Jenis Kelamin</th>
                                            <th class="text-center">Tanggal Kunjungan</th>
                                            <th class="text-center">Jam Kunjungan</th>
                                            <th class="text-center">Poli</th>
                                            <th class="text-center">Dokter</th>
                                            <th class="text-center">Penjamin</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-end align-items-center">
                                <button id="btnReset" class="btn btn-secondary mr-2">Reset</button>
                                <button id="btnPrint" class="btn btn-primary">Save & Print</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    const perawatData = @json($data);

    $(document).ready(function() {
        // Inisialisasi DataTable sederhana
        let table = $('#perawatTable').DataTable({
            data: [], // Awalnya kosong
            columns: [
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'nomor_rm', className: "text-center" },
                { data: 'pasien.nama', className: "text-center" },
                { data: 'nomor_register', className: "text-center" },
                { data: 'pasien.seks', className: "text-center" },
                {
                    data: 'tanggal_kujungan',
                    className: "text-center",
                    render: function(data, type, row) {
                        if (!data) return '-';
                        return data.split('T')[0];  // Ambil tanggal: "2025-05-12"
                    }
                },
                {
                    data: 'tanggal_kujungan',
                    className: "text-center",
                    render: function(data, type, row) {
                        if (!data) return '-';
                        return data.split('T')[1] || '-';  // Ambil waktu: "21:58"
                    }
                },
                { data: 'poli.nama', className: "text-center" },
                { data: 'dokter.namauser.name', className: "text-center" },
                { data: 'penjamin.nama', className: "text-center" },
            ],
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: false,
            info: true,
            autoWidth: false,
            responsive: false,
            scrollX: true,
            language: {
                zeroRecords: "Tentukan pilihan terlebih dahulu !"
            }
        });


        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            const tanggalAwal = $('#tanggalAwal').val();
            const tanggalAkhir = $('#tanggalAkhir').val();
            const filterPoli = $('#filterPoli').val();
            const filterDokter = $('#filterDokter').val();

            const tanggal = data[5];          // kolom tanggal (sudah dalam format y-m-d)
            const namaPoli = data[7];         // kolom poli (pastikan urutan benar)
            const namaDokter = data[8];       // kolom dokter (pastikan urutan benar)

            if (tanggalAwal && tanggal < tanggalAwal) return false;
            if (tanggalAkhir && tanggal > tanggalAkhir) return false;

            if (filterPoli && namaPoli !== filterPoli) return false;
            if (filterDokter && namaDokter !== filterDokter) return false;

            return true;
        });


        $('#btnFilter').on('click', function () {
            const tanggalAwal = $('#tanggalAwal').val();
            const tanggalAkhir = $('#tanggalAkhir').val();
            const filterPoli = $('#filterPoli').val();
            const filterDokter = $('#filterDokter').val();

            const filteredData = perawatData.filter(item => {
                // Tanggal kunjungan filter
                const tanggalKunjungan = item.tanggal_kujungan?.split('T')[0] ?? '';

                if (tanggalAwal && tanggalKunjungan < tanggalAwal) return false;
                if (tanggalAkhir && tanggalKunjungan > tanggalAkhir) return false;

                // Poli filter
                if (filterPoli && item.poli?.nama !== filterPoli) return false;

                // Dokter filter
                if (filterDokter && item.dokter?.namauser?.name !== filterDokter) return false;

                return true;
            });

            // Tampilkan data hasil filter
            table.clear().rows.add(filteredData).draw();
        });

        $('#btnReset').on('click', function () {
            $('#tanggalAwal').val('');
            $('#tanggalAkhir').val('');
            $('#filterPoli').val('');
            $('#filterDokter').val('');
            table.clear().draw();
        });

        $('#btnPrint').on('click', function () {
            var table = $('#perawatTable').DataTable();
            var allData = table.rows({ search: 'applied' }).data().toArray();

            let tanggalAwal = $('#tanggalAwal').val();
            let tanggalAkhir = $('#tanggalAkhir').val();
            let poli = $('#filterPoli').val();
            let dokter = $('#filterDokter').val();

            Swal.fire({
                title: 'Cetak Data',
                text: "Apakah Anda yakin ingin mencetak data yang sudah difilter?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, cetak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form dinamis
                    var form = $('<form>', {
                        method: 'POST',
                        action: "{{ route('print_perawat') }}",
                        target: '_blank'
                    });

                    // CSRF token
                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}'
                    }));

                    // Data tambahan
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_awal',
                        value: tanggalAwal
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_akhir',
                        value: tanggalAkhir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'data',
                        value: JSON.stringify(allData)
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'poli',
                        value: poli
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter',
                        value: dokter
                    }));

                    // Submit form
                    $('body').append(form);
                    form.submit();
                    form.remove();
                }
            });
        });
    });
</script>

<style>
    .kasir-table th,
    .kasir-table td {
        white-space: nowrap;
        vertical-align: middle;
        padding: 8px 12px;
        font-size: 16px;
    }

    .kasir-table {
        border-collapse: collapse;
    }
</style>


@endsection
