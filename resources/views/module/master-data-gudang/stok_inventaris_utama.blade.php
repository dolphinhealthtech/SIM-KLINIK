@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Stok Barang Inventaris Utama</h1>
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
                                <h3 class="card-title">Stok Barang Inventaris Utama</h3>
                            </div>
                            <div class="card-body">
                                <table id="stok_inventaristabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Kategori</th>
                                            <th class="text-center">Jenis</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Tanggal Pembelian</th>
                                            <th class="text-center">Akhir Penggunaan</th>
                                            <th class="text-center">Detail Barang</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stok_inventaris as $stok_inventarisdata)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $stok_inventarisdata->nama_barang }}</td>
                                                <td class="text-center">{{ $stok_inventarisdata->kategori_barang }}</td>
                                                <td class="text-center">{{ $stok_inventarisdata->jenis_barang }}</td>
                                                <td class="text-center">{{ $stok_inventarisdata->total_qty_barang       }}</td>
                                                <td class="text-center">{{ $stok_inventarisdata->tanggal_pembelian }}</td>
                                                <td class="text-center">{{ $stok_inventarisdata->masa_akhir_penggunaan }}</td>
                                                <td class="text-center">{{ $stok_inventarisdata->detail_barang }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('stokin_data_utama.get', ['id' => $stok_inventarisdata->kode_pembelian, 'kode' => $stok_inventarisdata->kode_barang]) }}" class="btn btn-info btn-sm"><i class="fa-solid fa-clipboard-list"></i> Detail Data</a>
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

<script>
    $(document).ready(function() {
        $("#stok_inventaristabel").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
        }).buttons().container().appendTo('#stok_inventaristabel_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection
