@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Data Master Gudang</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard v1</li>
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
                                <h3 class="card-title">Harga Jual Obat / Alkes</h3>
                            </div>
                            <div class="card-body">
                                <table id="stoktabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Obat / Alkes</th>
                                            <th class="text-center">Stok</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stok as $index => $stokdata)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $stokdata->nama_obat_alkes }}</td>
                                                <td class="text-center">{{ $stokdata->qty }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-primary"
                                                        data-toggle="modal"
                                                        data-target="#detailModal{{ $stokdata->kode_obat_alkes }}">
                                                        Detail
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Modal untuk setiap item -->
                                            <div class="modal fade" id="detailModal{{ $stokdata->kode_obat_alkes }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $stokdata->kode_obat_alkes }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalLabel{{ $stokdata->kode_obat_alkes }}">Detail Obat</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <ul>
                                                                <li><strong>Tanggal Masuk Barang:</strong> {{ $stokdata->tanggal_terima_obat ?? '-' }}</li>
                                                                <li><strong>Kode:</strong> {{ $stokdata->kode_obat_alkes }}</li>
                                                                <li><strong>Nama:</strong> {{ $stokdata->nama_obat_alkes }}</li>
                                                                <li><strong>Stok:</strong> {{ $stokdata->qty }}</li>
                                                                <li><strong>Expired:</strong> {{ $stokdata->expired ?? '-' }}</li>
                                                            </ul>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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

{{-- <div class="modal fade" id="addsatuanModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Master Data Jenis Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormsatuan" action="{{ route('satuan.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama Jenis Satuan</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Jenis Satuan" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button> <!-- Submit button -->
            </div>
            </form>
        </div>
    </div>
</div> --}}

<script>
        $(document).ready(function() {
            $("#stoktabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#stoktabel_wrapper .col-md-6:eq(0)');
        });
</script>
@endsection
