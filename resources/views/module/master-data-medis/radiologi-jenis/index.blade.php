@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Radiologi Jenis</h1>
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

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Radiologi Jenis</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addradiologi_jenisModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>

                                    <a href="{{ route('radiologi_jenis.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>


                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importradiologi_jenisModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="radiologi_jenistabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Radiologi Jenis</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($radiologi_jenis as $radiologi_jenisdata)
                                            <tr>
                                                <td class="text-center">{{ $radiologi_jenisdata->nama }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-radiologi_jenis"
                                                        data-toggle="modal" data-id="{{ $radiologi_jenisdata->id }}"
                                                        data-nama-radiologi_jenis="{{ $radiologi_jenisdata->nama }}"
                                                        data-target="#editradiologi_jenisModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-radiologi_jenis"
                                                        data-toggle="modal"data-id="{{ $radiologi_jenisdata->id }}"
                                                        data-nama-radiologi_jenis="{{ $radiologi_jenisdata->nama }}"
                                                        data-target="#deleteradiologi_jenisModal">
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

    @include('module.master-data-medis.radiologi-jenis.components.modal-add')
    @include('module.master-data-medis.radiologi-jenis.components.modal-delete')
    @include('module.master-data-medis.radiologi-jenis.components.modal-edit')
    @include('module.master-data-medis.radiologi-jenis.components.modal-import')
    @include('module.master-data-medis.radiologi-jenis.components.javascript')

@endsection
