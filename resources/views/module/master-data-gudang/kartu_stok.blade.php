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

                    <style>
.table-container {
    height: 270px;
    overflow-y: auto;
    overflow-x: hidden;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

.table-container table {
    margin-bottom: 0;
}

.table-container thead th {
    position: sticky;
    top: 0;
    z-index: 10;
}

.table-container .table-success th {
    background-color: #d3ffea !important;
}

.table-container .table-danger th {
    background-color: hsl(354, 100%, 81%) !important;
}

.card-body-fixed {
    height: 320px;
    display: flex;
    flex-direction: column;
}

.table-container {
    flex: 1;
}

.table-container::-webkit-scrollbar {
    width: 6px;
}

.table-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.table-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

<div class="row">
    <!-- Barang Masuk -->
    <div class="col-md-6">
        <div class="card border-success mb-3">
            <div class="card-header bg-success text-white fw-bold">Barang Masuk</div>
            <div class="card-body p-2 card-body-fixed">
                <div class="table-container">
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
                </div>
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
            <div class="card-body p-2 card-body-fixed">
                <div class="table-container">
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
                </div>
                <div class="mt-2 fw-semibold text-danger">
                    Total Keluar:
                </div>
            </div>
        </div>
    </div>
</div>

                    
                </div>
                <div class="card-footer">
                    <div class="d-flex align-items-stretch gap-3">
                        <div class="alert alert-secondary text-center fw-bold mb-0 d-flex align-items-center justify-content-center" style="width: 100%; ">
                            Saldo Stok:
                        </div>
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
