@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Poli</h1>
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
                                <h3 class="card-title">poli</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addpoliModal">
                                        <i class="fas fa-plus"></i> Sinkron
                                    </button>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importpoliModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('poli.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="politabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode poli</th>
                                            <th class="text-center">Nama poli</th>
                                            <th class="text-center">Jenis poli</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($poli as $polidata)
                                            <tr>
                                                <td class="text-center">{{ $polidata->kode }}</td>
                                                <td class="text-center">{{ $polidata->nama }}</td>
                                                <td class="text-center">{{ $polidata->jenis }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-goldar"
                                                        data-toggle="modal"data-id="{{ $polidata->id }}"
                                                        data-nama-poli="{{ $polidata->nama }}"
                                                        data-target="#deletepoliModal">
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

    @include('module.master-data.medis.poli.components.modal-add')
    @include('module.master-data.medis.poli.components.modal-import')
    @include('module.master-data.medis.poli.components.modal-delete')
    @include('module.master-data.medis.poli.components.javascript')

@endsection
