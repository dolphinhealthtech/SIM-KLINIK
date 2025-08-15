@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">subspesialis</h1>
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
                                <h3 class="card-title">subspesialis</h3>
                                <div class="card-tools">
                                    <a href="{{ route('spesialis.get') }}" class="btn btn-info">
                                        <i class="fa-solid fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addsubspesialisModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="subspesialistabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode subspesialis</th>
                                            <th class="text-center">Nama subspesialis</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subspesialis as $subspesialisdata)
                                            <tr>
                                                <td class="text-center">{{ $subspesialisdata->kode }}</td>
                                                <td class="text-center">{{ $subspesialisdata->nama }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-goldar"
                                                        data-toggle="modal"data-id="{{ $subspesialisdata->id }}"
                                                        data-nama-subspesialis="{{ $subspesialisdata->nama }}"
                                                        data-target="#deletesubspesialisModal">
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

    @include('module.master-data.medis.subspesialis.components.modal-add')
    @include('module.master-data.medis.subspesialis.components.modal-delete')
    @include('module.master-data.medis.subspesialis.components.javascript')

@endsection
