@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pendataan Faktur Diskon Kasir</h1>
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
                                    <input type="date" id="tanggalAwal" class="form-control" onfocus="this.showPicker()">
                                </div>
                                <div class="col-md-3">
                                    <label for="tanggalAkhir">Tanggal Akhir:</label>
                                    <input type="date" id="tanggalAkhir" class="form-control" onfocus="this.showPicker()">
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
                                            <th>No</th>
                                            <th>Kode Faktur</th>
                                            <th>No RM</th>
                                            <th>No Rawat</th>
                                            <th>Nama</th>
                                            <th>Nama Diskon</th>
                                            <th>Nominal Diskon</th>
                                            <th>Jumlah Diskon</th>
                                            <th>Total Diskon</th>
                                            <th>Poli</th>
                                            <th>Dokter</th>
                                            <th>Penjamin</th>
                                            <th>Tanggal</th>
                                            <th>User Input</th>
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
    const kasirData = @json($header);  // data dari Laravel

    console.log(kasirData);

    let tableData = [];

    kasirData.forEach((item, index) => {
        const detailList = item.diskon || [];

        // Ambil detail pertama (jika ada)
        const firstDetail = detailList[0];

        // Baris header dengan detail pertama jika ada
        tableData.push({
            no: index + 1,
            kode_faktur: item.kode_faktur,
            no_rm: item.no_rm,
            no_rawat: item.no_rawat,
            nama: item.nama,
            poli: item.poli,
            dokter: item.dokter,
            penjamin: item.penjamin,
            tanggal: item.tanggal,
            user_input_name: item.user_input_name,
            nama_obat_tindakan: firstDetail?.nama_diskon || '-',
            harga_obat_tindakan: firstDetail?.harga_diskon || '-',
            qty_pelaksana: firstDetail?.qty || '-',
            total_sementara: firstDetail?.total || '-',
            is_detail: false
        });

        // Sisanya dimasukkan sebagai baris detail
        detailList.slice(1).forEach(detail => {
            tableData.push({
                no: "",
                kode_faktur: "",
                no_rm: "",
                no_rawat: "",
                nama: "",
                poli: "",
                dokter: "",
                penjamin: "",
                tanggal: "",
                user_input_name: "",
                nama_obat_tindakan: detail.nama_diskon,
                harga_obat_tindakan: detail.harga_diskon,
                qty_pelaksana: detail.qty,
                total_sementara: detail.total,
                is_detail: true
            });
        });
    });

    $(document).ready(function() {
        // Inisialisasi DataTable tanpa data dulu
        let table = $('#kasirTable').DataTable({
            data: [],
            columns: [
                { data: 'no', className: "text-center" },
                { data: 'kode_faktur', className: "text-center" },
                { data: 'no_rm', className: "text-center" },
                // { data: 'no_rawat', className: "text-center" },
                {
                    data: null,
                    className: "text-center",
                    render: function(data, type, row) {
                        if(row.is_detail) return '';
                        return data.no_rawat || '-';
                    }
                },
                { data: 'nama', className: "text-center" },
                // Kolom detail, jika baris detail maka tampilkan nama obat/tindakan, harga, qty, total
                {
                    data: null,
                    className: "text-center",
                    render: function(data, type, row) {
                        return row.nama_obat_tindakan || '-';
                    }
                },
                {
                    data: null,
                    className: "text-center",
                    render: function(data, type, row) {
                        return row.harga_obat_tindakan || '-';
                    }
                },
                {
                    data: null,
                    className: "text-center",
                    render: function(data, type, row) {
                        return row.qty_pelaksana || '-';
                    }
                },
                {
                    data: null,
                    className: "text-center",
                    render: function(data, type, row) {
                        return row.total_sementara || '-';
                    }
                },
                { data: 'poli', className: "text-center" },
                // { data: 'dokter', className: "text-center" },
                {
                    data: null,
                    className: "text-center",
                    render: function(data, type, row) {
                        if(row.is_detail) return '';
                        return data.dokter || '-';
                    }
                },
                { data: 'penjamin', className: "text-center" },
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

        $('#btnFilter').on('click', function () {
            const tanggalAwal = $('#tanggalAwal').val();
            const tanggalAkhir = $('#tanggalAkhir').val();
            const poli = $('#filterPoli').val();

            // Filter hanya data header
            let filteredData = kasirData.filter(item => {
                if (tanggalAwal && item.tanggal < tanggalAwal) return false;
                if (tanggalAkhir && item.tanggal > tanggalAkhir) return false;
                if (poli && item.poli !== poli) return false;
                return true;
            });

            // Susun ulang data untuk tabel
            let filteredTableData = [];
            filteredData.forEach((item, index) => {
                const detailList = item.diskon || [];
                const firstDetail = detailList[0];

                // Baris header dengan isi detail pertama (jika ada)
                filteredTableData.push({
                    no: index + 1,
                    kode_faktur: item.kode_faktur,
                    no_rm: item.no_rm,
                    no_rawat: item.no_rawat,
                    nama: item.nama,
                    poli: item.poli,
                    dokter: item.dokter,
                    penjamin: item.penjamin,
                    tanggal: item.tanggal,
                    user_input_name: item.user_input_name,
                    nama_obat_tindakan: firstDetail?.nama_diskon || '-',
                    harga_obat_tindakan: firstDetail?.harga_diskon || '-',
                    qty_pelaksana: firstDetail?.qty || '-',
                    total_sementara: firstDetail?.total || '-',
                    is_detail: false
                });

                // Baris detail kedua dan seterusnya
                detailList.slice(1).forEach(detail => {
                    filteredTableData.push({
                        no: "",
                        kode_faktur: "",
                        no_rm: "",
                        no_rawat: "",
                        nama: "",
                        poli: "",
                        dokter: "",
                        penjamin: "",
                        tanggal: "",
                        user_input_name: "",
                        nama_obat_tindakan: detail.nama_diskon,
                        harga_obat_tindakan: detail.harga_diskon,
                        qty_pelaksana: detail.qty,
                        total_sementara: detail.total,
                        is_detail: true
                    });
                });
            });

            // Perbarui isi DataTable
            table.clear().rows.add(filteredTableData).draw();
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
                    console.log(allData);
                    var form = $('<form>', {
                        method: 'POST',
                        action: "{{ route('datakasir_diskon.print') }}",
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
            table.clear().draw(); // reload datatable jika perlu
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
