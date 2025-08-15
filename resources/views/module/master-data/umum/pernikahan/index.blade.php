@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h5 class="text-muted text-center">Selamat datang di modul data master pernikahan</h5>
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
                            <div class="card-body">
                                <table id="pernikahantabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama pernikahan</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pernikahan as $pernikahandata)
                                            <tr>
                                                <td class="text-center">{{ $pernikahandata->nama }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-pernikahan"
                                                        data-toggle="modal" data-id="{{ $pernikahandata->id }}"
                                                        data-nama-pernikahan="{{ $pernikahandata->nama }}"
                                                        data-target="#editpernikahanModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-pernikahan"
                                                        data-toggle="modal"data-id="{{ $pernikahandata->id }}"
                                                        data-nama-pernikahan="{{ $pernikahandata->nama }}"
                                                        data-target="#deletepernikahanModal">
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

    @include('module.master-data.umum.pernikahan.components.modal-add')
    @include('module.master-data.umum.pernikahan.components.modal-edit')
    @include('module.master-data.umum.pernikahan.components.modal-delete')
    @include('module.master-data.umum.pernikahan.components.modal-import')
    @include('module.master-data.umum.pernikahan.components.javascript')
@endsection
