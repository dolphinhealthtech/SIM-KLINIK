@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Harga Jual Utama</h1>
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
                                <h3 class="card-title">Harga Jual Obat / Alkes Utama</h3>
                            </div>
                            <div class="card-body">
                                <table id="harga_jualtabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama Obat / Alkes</th>
                                            <th class="text-center">Harga Dasar</th>
                                            <th class="text-center">HJ Rawat Jalan</th>
                                            <th class="text-center">HJ Asuransi</th>
                                            <th class="text-center">HJ Umum</th>
                                            <th class="text-center">Tanggal Input</th>
                                            <th class="text-center">User Input</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($harga_jual as $harga_jualdata)
                                            <tr>
                                                <td class="text-center">{{ $harga_jualdata->nama_obat_alkes }}</td>
                                                <td class="text-center">{{ $harga_jualdata->harga_dasar }}</td>
                                                <td class="text-center">{{ $harga_jualdata->harga_jual_1 }}</td>
                                                <td class="text-center">{{ $harga_jualdata->harga_jual_2 }}</td>
                                                <td class="text-center">{{ $harga_jualdata->harga_jual_3 }}</td>
                                                <td class="text-center">{{ $harga_jualdata->tanggal_obat_masuk }}</td>
                                                <td class="text-center">{{ $harga_jualdata->user_input_name }}</td>
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
            $("#harga_jualtabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#harga_jualtabel_wrapper .col-md-6:eq(0)');
        });
</script>
@endsection
