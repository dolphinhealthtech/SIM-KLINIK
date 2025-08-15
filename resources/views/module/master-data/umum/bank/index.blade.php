@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h5 class="text-muted text-center">Selamat datang di modul data master bank</h5>
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
                                <table id="banktabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama bank</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bank as $bankdata)
                                            <tr>
                                                <td class="text-center">{{ $bankdata->nama }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-bank"
                                                        data-toggle="modal" data-id="{{ $bankdata->id }}"
                                                        data-nama-bank="{{ $bankdata->nama }}"
                                                        data-kode-bank="{{ $bankdata->kode }}"
                                                        data-target="#editbankModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-bank"
                                                        data-toggle="modal"data-id="{{ $bankdata->id }}"
                                                        data-nama-bank="{{ $bankdata->nama }}"
                                                        data-target="#deletebankModal">
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

@include('module.master-data.umum.bank.components.modal-add')
@include('module.master-data.umum.bank.components.modal-edit')
@include('module.master-data.umum.bank.components.modal-delete')
@include('module.master-data.umum.bank.components.modal-import')
@include('module.master-data.umum.bank.components.javascript')









@endsection
