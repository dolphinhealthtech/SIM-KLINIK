@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pendidikan</h1>
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
                                <h3 class="card-title">pendidikan</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addpendidikanModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('pendidikan.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#importpendidikanModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="pendidikantabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama pendidikan</th>
                                            <th class="text-center">Kode pendidikan</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendidikan as $pendidikandata)
                                            <tr>
                                                <td class="text-center">{{ $pendidikandata->nama }}</td>
                                                <td class="text-center">{{ $pendidikandata->kode }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-pendidikan"
                                                        data-toggle="modal" data-id="{{ $pendidikandata->id }}"
                                                        data-nama-pendidikan="{{ $pendidikandata->nama }}"
                                                        data-kode-pendidikan="{{ $pendidikandata->kode }}"
                                                        data-urutan-pendidikan="{{ $pendidikandata->urutan }}"
                                                        data-target="#editpendidikanModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-pendidikan"
                                                        data-toggle="modal"data-id="{{ $pendidikandata->id }}"
                                                        data-nama-pendidikan="{{ $pendidikandata->nama }}"
                                                        data-target="#deletependidikanModal">
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
    @include('module.master-data.umum.pendidikan.components.modal-add')
    @include('module.master-data.umum.pendidikan.components.modal-edit')
    @include('module.master-data.umum.pendidikan.components.modal-delete')
    @include('module.master-data.umum.pendidikan.components.modal-import')
    @include('module.master-data.umum.pendidikan.components.javascript')
@endsection
