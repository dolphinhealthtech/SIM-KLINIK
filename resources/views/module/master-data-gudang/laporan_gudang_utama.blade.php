@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Gudang Utama</h1>
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
                                    <label for="filterKlinik">Klinik:</label>
                                    <select id="filterKlinik" class="form-control">
                                        <option value="">-- Semua Klinik --</option>
                                        @foreach ($data->pluck('nama_klinik')->unique() as $klinik)
                                            <option value="{{ $klinik }}">{{ $klinik }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex flex-column justify-content-end align-items-end">
                                    <button id="btnFilter" class="btn btn-outline-secondary w-100">Filter</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="gudangTable" class="table table-bordered table-striped thin-table-border kasir-table" style="min-width: 1500px">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Kode Request</th>
                                            <th class="text-center">Nama Klinik</th>
                                            <th class="text-center">Nama Obat / Alkes</th>
                                            <th class="text-center">Qty</th>
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
    const gudangData = @json($data);  // data dari Laravel

    let tableData = [];

    gudangData.forEach((item, index) => {
        const detailList = item.details || [];

        const firstDetail = detailList[0];

        tableData.push({
            no: index + 1,
            kode_request: item.kode_request,
            nama_klinik: item.nama_klinik,
            nama_obat_alkes: firstDetail?.nama_obat_alkes || '-',
            qty: firstDetail?.qty || '-',
            tanggal: item.tanggal_input,
            petugas_entry: item.user_input_name,
            is_detail: false
        });

        detailList.slice(1).forEach(detail => {
            tableData.push({
                no: "",
                kode_request: "",
                nama_klinik: "",
                nama_obat_alkes: detail.nama_obat_alkes,
                qty: detail.qty,
                tanggal: "",
                petugas_entry: "",
                is_detail: true
            });
        });
    });

    $(document).ready(function() {
        // Inisialisasi DataTable tanpa data dulu
        let table = $('#gudangTable').DataTable({
            data: [],
            columns: [
                { data: 'no', className: "text-center" },
                { data: 'kode_request', className: "text-center" },
                { data: 'nama_klinik', className: "text-center" },
                {
                    data: 'nama_obat_alkes',
                    className: "text-center",
                    defaultContent: "-"
                },
                {
                    data: 'qty',
                    className: "text-center",
                    defaultContent: "-"
                },
                {
                    data: 'tanggal',
                    className: "text-center",
                    defaultContent: "-"
                },
                {
                    data: 'petugas_entry',
                    className: "text-center",
                    defaultContent: "-"
                }
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
            const klinik = $('#filterKlinik').val();

            let filteredData = gudangData.filter(item => {
                if (tanggalAwal && item.tanggal_input < tanggalAwal) return false;
                if (tanggalAkhir && item.tanggal_input > tanggalAkhir) return false;
                if (klinik && item.nama_klinik !== klinik) return false;
                return true;
            });

            let filteredTableData = [];

            filteredData.forEach((item, index) => {
                const detailList = item.details || [];
                const firstDetail = detailList[0];

                filteredTableData.push({
                    no: index + 1,
                    kode_request: item.kode_request,
                    nama_klinik: item.nama_klinik,
                    nama_obat_alkes: firstDetail?.nama_obat_alkes || '-',
                    qty: firstDetail?.qty || '-',
                    tanggal: item.tanggal_input,
                    petugas_entry: item.user_input_name,
                    is_detail: false
                });

                detailList.slice(1).forEach(detail => {
                    filteredTableData.push({
                        no: "",
                        kode_request: "",
                        nama_klinik: "",
                        nama_obat_alkes: detail.nama_obat_alkes,
                        qty: detail.qty,
                        tanggal: "",
                        petugas_entry: "",
                        is_detail: true
                    });
                });
            });

            table.clear().rows.add(filteredTableData).draw();
        });

        $('#btnPrint').on('click', function () {
            var table = $('#gudangTable').DataTable();
            var allData = table.rows({ search: 'applied' }).data().toArray();

            let tanggalAwal = $('#tanggalAwal').val();
            let tanggalAkhir = $('#tanggalAkhir').val();
            let klinik = $('#filterKlinik').val();

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
                        action: "{{ route('print_gudang_utama') }}",
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
                        name: 'klinik',
                        value: klinik
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
            $('#filterKlinik').val('');
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
