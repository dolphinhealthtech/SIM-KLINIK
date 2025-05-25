@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pendataan Faktur Lunas Kasir</h1>
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
                                <div class="col-md-3">
                                    <label for="tanggalAwal">Tanggal Awal:</label>
                                    <input type="date" id="tanggalAwal" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="tanggalAkhir">Tanggal Akhir:</label>
                                    <input type="date" id="tanggalAkhir" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="filterPoli">Poli:</label>
                                    <select id="filterPoli" class="form-control">
                                        <option value="">-- Semua Poli --</option>
                                        @foreach ($header->pluck('poli')->unique() as $poli)
                                            <option value="{{ $poli }}">{{ $poli }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex flex-column justify-content-end align-items-end">
                                    <button id="btnFilter" class="btn btn-outline-secondary w-100">Filter</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="kasirTable" class="table table-bordered table-striped thin-table-border kasir-table" style="min-width: 1500px">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Invoice</th>
                                            <th class="text-center">No RM</th>
                                            <th class="text-center">No Rawat</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Poli</th>
                                            <th class="text-center">Dokter</th>
                                            <th class="text-center">Penjamin</th>
                                            <th class="text-center">Sub Total</th>
                                            <th class="text-center">TAMBAHAN</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Pembayaran</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Petugas Entry</th>
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
    const kasirData = @json($header);

    $(document).ready(function() {
        // Inisialisasi DataTable sederhana
        let table = $('#kasirTable').DataTable({
            data: [], // Awalnya kosong
            columns: [
                { data: 'no', className: "text-center" },
                { data: 'kode_faktur', className: "text-center" },
                { data: 'no_rm', className: "text-center" },
                {
                    data: 'no_rawat',
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (data === null || data === undefined || data === '') {
                        return '-';
                        }
                        return data;
                    }
                },
                { data: 'nama', className: "text-center" },
                { data: 'poli', className: "text-center" },
                {
                    data: 'dokter',
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (data === null || data === undefined || data === '') {
                        return '-';
                        }
                        return data;
                    }
                },
                { data: 'penjamin', className: "text-center" },
                { data: 'sub_total', className: "text-center" },
                {
                    data: null,
                    className: "text-center",
                    render: function(data, type, row) {
                        let result = [];
                        if (row.potongan_harga && row.potongan_harga != 0) {
                        result.push(`Diskon: ${row.potongan_harga}`);
                        }
                        if (row.administrasi && row.administrasi != 0) {
                        result.push(`Administrasi: ${row.administrasi}`);
                        }
                        if (row.materai && row.materai != 0) {
                        result.push(`Materai: ${row.materai}`);
                        }
                        return result.join('<br>') || '-';  // Kalau semua 0 atau kosong, tampilkan tanda strip
                    }
                },
                { data: 'total', className: "text-center" },
                {
                    data: null,
                    className: "text-center",
                    render: function(data, type, row) {
                        let paymentTexts = [];

                        for (let i = 1; i <= 3; i++) {
                            let method = row[`payment_method_${i}`];
                            let nominal = row[`payment_nominal_${i}`];

                            if (method && nominal) {
                                // Tulis format: Method : Nominal (contoh: Cash : 100.000)
                                paymentTexts.push(`${method.charAt(0).toUpperCase() + method.slice(1)}: ${nominal}`);
                            }
                        }

                        // Gabung semua metode pembayaran dengan <br>
                        return paymentTexts.join('<br>');
                    }
                },
                { data: 'tanggal', className: "text-center" },
                { data: 'user_input_name', className: "text-center" },
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
            const poli = $('#filterPoli').val();

            const tanggal = data[12]; // kolom ke-13 (index ke-12) = Tanggal
            const dataPoli = data[5]; // kolom ke-6 (index ke-5) = Poli

            // Filter tanggal
            if (tanggalAwal && tanggal < tanggalAwal) return false;
            if (tanggalAkhir && tanggal > tanggalAkhir) return false;

            // Filter poli
            if (poli && dataPoli !== poli) return false;

            return true;
        });

        $('#btnFilter').on('click', function () {
            const tanggalAwal = $('#tanggalAwal').val();
            const tanggalAkhir = $('#tanggalAkhir').val();
            const poli = $('#filterPoli').val();

            // Filter manual dari kasirData
            const filteredData = kasirData.filter(item => {
                if (tanggalAwal && item.tanggal < tanggalAwal) return false;
                if (tanggalAkhir && item.tanggal > tanggalAkhir) return false;
                if (poli && item.poli !== poli) return false;
                return true;
            });

            // Tambahkan nomor urut
            filteredData.forEach((item, index) => {
                item.no = index + 1;
            });

            // Tampilkan hasil ke tabel
            table.clear().rows.add(filteredData).draw();
        });

        $('#btnPrint').on('click', function () {
            var table = $('#kasirTable').DataTable();
            var allData = table.rows({ search: 'applied' }).data().toArray();

            let tanggalAwal = $('#tanggalAwal').val();
            let tanggalAkhir = $('#tanggalAkhir').val();
            let poli = $('#filterPoli').val();

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
                        action: "{{ route('datakasir_lunas.print') }}",
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
                        name: 'poli',
                        value: poli
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'data',
                        value: JSON.stringify(allData)
                    }));

                    // Submit form
                    $('body').append(form);
                    form.submit();
                    form.remove();
                }
            });
        });

        $('#btnReset').on('click', function() {
            $('#tanggalAwal').val('');
            $('#tanggalAkhir').val('');
            $('#filterPoli').val('');
            table.draw(); // reload datatable jika perlu
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
