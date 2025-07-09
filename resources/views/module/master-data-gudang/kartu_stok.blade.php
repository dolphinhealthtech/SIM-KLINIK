@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Kartu Stok</h1>
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
                        <div class="col-md-3">
                            <label for="tanggalAwal">Periode Awal:</label>
                            <input type="date" id="tanggalAwal" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="tanggalAkhir">Periode Akhir:</label>
                            <input type="date" id="tanggalAkhir" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="filterObat">Obat:</label>
                            <select id="filterObat" class="form-control select2bs4">
                                @foreach ($data as $data)
                                    <option value="{{ $data->kode_barang }}">{{ $data->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex flex-column justify-content-end align-items-end">
                            <button id="btnFilter" class="btn btn-outline-secondary w-100">Filter</button>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Barang Masuk -->
                        <div class="col-md-6">
                            <div class="card border-success mb-3">
                                <div class="card-header bg-success text-white fw-bold">Barang Masuk</div>
                                <div class="card-body p-2">
                                    <table id="tabelMasuk" class="table table-sm table-bordered table-hover mb-0 kasir-table">
                                        <thead class="table-success text-center">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Masuk</th>
                                                <th>Harga (Rp)</th>
                                                <th>Keterangan</th>
                                                <th>Batch / Expired</th>
                                                <th>User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <div class="mt-2 fw-semibold text-success">
                                        Total Masuk:
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barang Keluar -->
                        <div class="col-md-6">
                            <div class="card border-danger mb-3">
                                <div class="card-header bg-danger text-white fw-bold">Barang Keluar</div>
                                <div class="card-body p-2">
                                    <table id="tabelKeluar" class="table table-sm table-bordered table-hover mb-0 kasir-table">
                                        <thead class="table-danger text-center">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Keluar</th>
                                                <th>Harga (Rp)</th>
                                                <th>Keterangan</th>
                                                <th>User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <div class="mt-2 fw-semibold text-danger">
                                        Total Keluar:
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Saldo -->
                    <div class="alert alert-secondary text-center fw-bold">
                        Saldo Stok:
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
    let tabelMasuk; // inisialisasi variable global
    let tabelKeluar; // inisialisasi variable global

    $(document).ready(function() {
        $('#btnFilter').on('click', function () {
            // Jika tabel belum dibuat, buat pertama kali
            if (!$.fn.DataTable.isDataTable('#tabelMasuk')) {
                tabelMasuk = $('#tabelMasuk').DataTable({
                    paging: false,
                    lengthChange: true,
                    searching: false,
                    ordering: false,
                    info: false,
                    autoWidth: false,
                    responsive: false,
                    processing: false,
                    serverSide: true,
                    language: {
                        zeroRecords: "Tentukan pilihan terlebih dahulu !"
                    },
                    ajax: {
                        url: '{{ route("getKartuStokMasuk") }}',
                        data: function (d) {
                            d.tanggal_awal = $('#tanggalAwal').val();
                            d.tanggal_akhir = $('#tanggalAkhir').val();
                            d.kode_obat = $('#filterObat').val();
                        },
                        dataSrc: function (json) {
                            let total = 0;

                            json.data.forEach(row => {
                                total += parseInt(row.qty);
                            });

                            let satuan = json.satuan_kecil ?? 'Satuan';

                            $('.text-success').text('Total Masuk: ' + total + ' ' + satuan);

                            let stok = json.stok ?? 'Satuan';

                            $('.alert-secondary').text('Saldo Stok: ' + stok + ' ' + satuan);

                            return json.data;
                        }
                    },
                    columns: [
                        { data: 'tanggal' },
                        { data: 'qty', className: 'text-center' },
                        {
                            data: 'harga',
                            className: 'text-end',
                            render: function (data, type, row) {
                                // Hilangkan "Rp", spasi, dan karakter non-digit
                                const angka = parseInt(data.toString().replace(/^Rp\s?/, '').replace(/[^0-9]/g, '')) || 0;
                                return angka.toLocaleString('id-ID');
                            }
                        },
                        { data: 'keterangan' },
                        { data: 'expired' },
                        { data: 'user' }
                    ]
                });
            } else {
                // Jika sudah dibuat, cukup reload datanya saja
                tabelMasuk.ajax.reload();
            }
            if (!$.fn.DataTable.isDataTable('#tabelKeluar')) {
                tabelKeluar = $('#tabelKeluar').DataTable({
                    paging: false,
                    lengthChange: true,
                    searching: false,
                    ordering: false,
                    info: false,
                    autoWidth: false,
                    responsive: false,
                    processing: false,
                    serverSide: true,
                    language: {
                        zeroRecords: "Tentukan pilihan terlebih dahulu !"
                    },
                    ajax: {
                        url: '{{ route("getKartuStokKeluar") }}',
                        data: function (d) {
                            d.tanggal_awal = $('#tanggalAwal').val();
                            d.tanggal_akhir = $('#tanggalAkhir').val();
                            d.kode_obat = $('#filterObat').val();
                        },
                        dataSrc: function (json) {
                            let total = 0;

                            json.data.forEach(row => {
                                total += parseInt(row.qty);
                            });

                            let satuan = json.satuan_kecil ?? 'Satuan';

                            $('.text-danger').text('Total Keluar: ' + total + ' ' + satuan);

                            return json.data;
                        }
                    },
                    columns: [
                        { data: 'tanggal' },
                        { data: 'qty', className: 'text-center' },
                        // { data: 'harga', className: 'text-end' },
                        {
                            data: 'harga',
                            className: 'text-end',
                            render: function (data, type, row) {
                                const angka = parseInt(data.toString().replace(/[^0-9]/g, '')) || 0;
                                return angka.toLocaleString('id-ID');
                            }
                        },
                        { data: 'keterangan' },
                        { data: 'user' }
                    ]
                });
            } else {
                // Jika sudah dibuat, cukup reload datanya saja
                tabelKeluar.ajax.reload();
            }
        });
    });
</script>


<style>
    .kasir-table th,
    .kasir-table td {
        vertical-align: middle;
        padding: 4px 6px;
        font-size: 14px;
    }
</style>


@endsection
