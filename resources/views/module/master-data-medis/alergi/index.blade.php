@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Alergi</h1>
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
                                <h3 class="card-title">alergi</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addalergiModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="alergitabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Jenis alergi</th>
                                            <th class="text-center">Nama alergi</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($alergi as $alergidata)
                                            <tr>
                                                <td class="text-center">@if ($alergidata->kode_jenis_alergi == '01')
                                                    Makanan
                                                @elseif ($alergidata->kode_jenis_alergi == '02')
                                                    Udara
                                                @elseif ($alergidata->kode_jenis_alergi == '03')
                                                    Obat
                                                @else
                                                    Tidak Diketahui
                                                @endif</td>
                                                <td class="text-center">{{ $alergidata->nama_jenis_alergi }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-alergi"
                                                        data-toggle="modal"data-id="{{ $alergidata->id }}"
                                                        data-nama-alergi="{{ $alergidata->nama_jenis_alergi }}"
                                                        data-target="#deletealergiModal">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </a>
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

    <!-- Modal Tambah Alergi -->
    @include('module.master-data-medis.alergi.components.modal-add')

    <!-- Modal Hapus Alergi -->
    @include('module.master-data-medis.alergi.components.modal-delete')

    <!-- Script -->
    @include('module.master-data-medis.alergi.components.javascript')
@endsection
