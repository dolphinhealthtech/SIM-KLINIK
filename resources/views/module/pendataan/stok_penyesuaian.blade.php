@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Selisih Mutasi & Penyesuaian</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="tanggalAwal">Periode Awal:</label>
                            <input type="date" id="tanggalAwal" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="tanggalAkhir">Periode Akhir:</label>
                            <input type="date" id="tanggalAkhir" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="filterObat">Obat:</label>
                            <select id="filterObat" class="form-control">
                                <option value="">-- Semua Obat --</option>
                                @foreach ($data->pluck('nama_obat')->unique() as $nama_obat)
                                    <option value="{{ $nama_obat }}">{{ $nama_obat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filterJenis">Jenis Penyesuaian:</label>
                            <select id="filterJenis" class="form-control">
                                <option value="">-- Semua Jenis --</option>
                                <option value="STOK OPNAME">STOK OPNAME</option>
                                <option value="Penambahan">Penambahan</option>
                                <option value="Pengurangan">Pengurangan</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex flex-column justify-content-end align-items-end">
                            <button id="btnFilter" class="btn btn-outline-secondary w-100">Filter</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="penyesuaianTable" class="table table-bordered table-striped thin-table-border kasir-table" style="min-width: 1500px">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Obat/Alkes</th>
                                    <th>Nama Obat/Alkes</th>
                                    <th>Qty Sebelum</th>
                                    <th>Qty Mutasi</th>
                                    <th>Qty Sesudah</th>
                                    <th>Jenis Penyesuaian</th>
                                    <th>Alasan</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Petugas</th>
                                    <th>Tipe</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
    </section>
</div>

<script>
    $('#tanggalAwal').on('focus', function () {
        this.showPicker && this.showPicker(); // Untuk browser modern
    });
    $('#tanggalAkhir').on('focus', function () {
        this.showPicker && this.showPicker(); // Untuk browser modern
    });
</script>

<script>
    const allData = @json($data);

    $(document).ready(function() {
        const table = $('#penyesuaianTable').DataTable({
            data: [],
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                { data: 'kode_obat' },
                { data: 'nama_obat' },
                { data: 'qty_sebelum' },
                { data: 'qty_mutasi' },
                { data: 'qty_sesudah' },
                { data: 'jenis_penyesuaian' },
                { data: 'alasan' },
                { data: 'tanggal' },
                { data: 'jam' },
                { data: 'user_input_name' },
                { data: 'tipe' }
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

        // Trigger Filter
        $('#btnFilter').on('click', function () {
            const tanggalAwal = $('#tanggalAwal').val();
            const tanggalAkhir = $('#tanggalAkhir').val();
            const filterObat = $('#filterObat').val();
            const filterJenis = $('#filterJenis').val();

            const filteredData = allData.filter(item => {
                const tanggal = item.tanggal;

                if (tanggalAwal && tanggal < tanggalAwal) return false;
                if (tanggalAkhir && tanggal > tanggalAkhir) return false;
                if (filterObat && item.nama_obat !== filterObat) return false;
                if (filterJenis && item.jenis_penyesuaian !== filterJenis) return false;

                return true;
            });

            table.clear().rows.add(filteredData).draw();
        });

        // Reset Button
        $('#btnReset').on('click', function () {
            $('#tanggalAwal').val('');
            $('#tanggalAkhir').val('');
            $('#filterObat').val('');
            $('#filterJenis').val('');
            table.clear().draw();
        });

        $('#btnPrint').on('click', function () {
            var table = $('#penyesuaianTable').DataTable();
            var allData = table.rows({ search: 'applied' }).data().toArray();

            let tanggalAwal = $('#tanggalAwal').val();
            let tanggalAkhir = $('#tanggalAkhir').val();
            let obat = $('#filterObat').val();
            let jenis = $('#filterJenis').val();

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
                        action: "{{ route('print_stok_penyesuaian') }}",
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
                        name: 'obat',
                        value: obat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis',
                        value: jenis
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
