@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h5 class="text-muted text-center">Selamat datang di modul data master Golongan Darah</h5>
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
                                <table id="goldartabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Golongan Darah</th>
                                            <th class="text-center">Rhesus Darah </th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($goldar as $goldardata)
                                            <tr>
                                                <td class="text-center">{{ $goldardata->nama }}</td>
                                                <td class="text-center">{{ $goldardata->resus }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-goldar"
                                                        data-toggle="modal" data-id="{{ $goldardata->id }}"
                                                        data-nama-goldar="{{ $goldardata->nama }}"
                                                        data-rhesus="{{ $goldardata->resus }}"
                                                        data-target="#editgoldarModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-goldar"
                                                        data-toggle="modal"data-id="{{ $goldardata->id }}"
                                                        data-nama-goldar="{{ $goldardata->nama }}"
                                                        data-rhesus="{{ $goldardata->resus }}"
                                                        data-target="#deletegoldarModal">
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

    @include('module.master-data.umum.goldar.components.modal-add')
    @include('module.master-data.umum.goldar.components.modal-edit')
    @include('module.master-data.umum.goldar.components.modal-delete')
    @include('module.master-data.umum.goldar.components.modal-import')
    @include('module.master-data.umum.goldar.components.javascript')

@endsection


