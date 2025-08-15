@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"> Makanan</h1>
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
                                <h3 class="card-title">Makanan</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addnama_makananModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>

                                    <a href="{{ route('nama_makanan.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>


                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importnama_makananModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="nama_makanantabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama makanan</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($nama_makanan as $nama_makanandata)
                                            <tr>
                                                <td class="text-center">{{ $nama_makanandata->nama }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-nama_makanan"
                                                        data-toggle="modal" data-id="{{ $nama_makanandata->id }}"
                                                        data-nama-nama_makanan="{{ $nama_makanandata->nama }}"
                                                        data-target="#editnama_makananModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-nama_makanan"
                                                        data-toggle="modal"data-id="{{ $nama_makanandata->id }}"
                                                        data-nama-nama_makanan="{{ $nama_makanandata->nama }}"
                                                        data-target="#deletenama_makananModal">
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

    @include('module.master-data-medis.nama-makanan.components.modal-add')
    @include('module.master-data-medis.nama-makanan.components.modal-edit')
    @include('module.master-data-medis.nama-makanan.components.modal-delete')
    @include('module.master-data-medis.nama-makanan.components.modal-import')
    @include('module.master-data-medis.nama-makanan.components.javascript')

@endsection
