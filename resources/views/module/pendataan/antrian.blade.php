@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Antrian</h1>
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
                                </div>
                                <div class="col-md-3 d-flex flex-column justify-content-end align-items-end">
                                    <button id="btnFilter" class="btn btn-outline-secondary w-100">Filter</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="antrianTable" class="table table-bordered table-striped thin-table-border kasir-table" style="min-width: 1500px">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">No RM</th>
                                            <th class="text-center">NIK</th>
                                            <th class="text-center">Nama </th>
                                            <th class="text-center">Jenis Kelamin</th>
                                            <th class="text-center">Nomor Antrian</th>
                                            <th class="text-center">Tanggal</th>
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
    const antrianData = @json($data);

    $(document).ready(function() {
        // Inisialisasi DataTable sederhana
        let table = $('#antrianTable').DataTable({
            data: [], // Awalnya kosong
            columns: [
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'pasien.no_rm', className: "text-center" },
                { data: 'pasien.nik', className: "text-center" },
                { data: 'pasien.nama', className: "text-center" },
                { data: 'pasien.seks', className: "text-center" },
                { data: 'nomor_antrian', className: "text-center" },
                {
                    data: 'created_at',
                    className: "text-center",
                    render: function(data, type, row) {
                        if (!data) return '-';
                        const dateObj = new Date(data);
                        const year = dateObj.getFullYear();
                        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                        const day = String(dateObj.getDate()).padStart(2, '0');
                        return `${year}-${month}-${day}`; // Output: 2025-05-28
                    }
                },
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

            const tanggal = data[6]; // kolom ke-13 (index ke-12) = Tanggal

            // Filter tanggal
            if (tanggalAwal && tanggal < tanggalAwal) return false;
            if (tanggalAkhir && tanggal > tanggalAkhir) return false;

            return true;
        });

        $('#btnFilter').on('click', function () {
            const tanggalAwal = $('#tanggalAwal').val();
            const tanggalAkhir = $('#tanggalAkhir').val();

            // Filter manual dari antrianData
            const filteredData = antrianData.filter(item => {
                if (tanggalAwal && item.tanggal < tanggalAwal) return false;
                if (tanggalAkhir && item.tanggal > tanggalAkhir) return false;
                return true;
            });

            // Tambahkan nomor urut
            filteredData.forEach((item, index) => {
                item.no = index + 1;
            });

            // Tampilkan hasil ke tabel
            table.clear().rows.add(filteredData).draw();
        });

        $('#btnReset').on('click', function() {
            $('#tanggalAwal').val('');
            $('#tanggalAkhir').val('');
            table.clear().draw();
        });


        $('#btnPrint').on('click', function () {
            var table = $('#antrianTable').DataTable();
            var allData = table.rows({ search: 'applied' }).data().toArray();

            let tanggalAwal = $('#tanggalAwal').val();
            let tanggalAkhir = $('#tanggalAkhir').val();

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
                        action: "{{ route('print_antrian') }}",
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
