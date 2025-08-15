@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Bidang Laboratorium</h1>
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
                                <h3 class="card-title">Bidang Laboratorium</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addpemeriksaan_httModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('laboratorium_bidang.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importpemeriksaan_httModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="pemeriksaan_htttabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama Bidang Laboratorium</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($laboratorium_bidang as $laboratorium_bidangdata)
                                            <tr>
                                                <td class="text-center">{{ $laboratorium_bidangdata->nama }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-pemeriksaan_htt"
                                                        data-toggle="modal" data-id="{{ $laboratorium_bidangdata->id }}"
                                                        data-nama-pemeriksaan_htt="{{ $laboratorium_bidangdata->nama }}"
                                                        data-target="#editpemeriksaan_httModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-pemeriksaan_htt"
                                                        data-toggle="modal"data-id="{{ $laboratorium_bidangdata->id }}"
                                                        data-nama-pemeriksaan_htt="{{ $laboratorium_bidangdata->nama }}"
                                                        data-target="#deletepemeriksaan_httModal">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </a>

                                                    <a href="{{ route('laboratorium_bidang_sub.get', ['kode' => $laboratorium_bidangdata->id]) }}" class="btn btn-info btn-sm"><i class="fa-solid fa-briefcase-medical"></i> Sub Pemeriksaan</a>
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

    @include('module.master-data.medis.laboratorium-bidang.components.modal-add')
    @include('module.master-data.medis.laboratorium-bidang.components.modal-edit')
    @include('module.master-data.medis.laboratorium-bidang.components.modal-delete')
    @include('module.master-data.medis.laboratorium-bidang.components.modal-import')
    @include('module.master-data.medis.laboratorium-bidang.components.javascript')

@endsection
