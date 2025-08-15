@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Sub Pemeriksaan Laboratorium</h1>
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
                                <h3 class="card-title">Sub pemeriksaan</h3>
                                <div class="card-tools">
                                    <a href="{{ route('laboratorium_bidang.get') }}" class="btn btn-info">
                                        <i class="fa-solid fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addhtt_sub_pemeriksaanModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="htt_sub_pemeriksaantabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Pemeriksaan Laboratorium</th>
                                            <th class="text-center">Sub Pemeriksaan Laboratorium</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($laboratorium_bidang_sub as $laboratorium_bidang_subdata)
                                            <tr>
                                                <td class="text-center">{{ $laboratorium_bidang_subdata->nama_laboratorium_bidang }}</td>
                                                <td class="text-center">{{ $laboratorium_bidang_subdata->nama_sublaboratorium_bidang }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-goldar"
                                                        data-toggle="modal"data-id="{{ $laboratorium_bidang_subdata->id }}"
                                                        data-nama-htt_sub_pemeriksaan="{{ $laboratorium_bidang_subdata->nama_sublaboratorium_bidang }}"
                                                        data-target="#edithtt_sub_pemeriksaanModal">
                                                        <i class="fas fa-trash"></i> edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-pemeriksaan_htt"
                                                        data-toggle="modal"data-id="{{ $laboratorium_bidang_subdata->id }}"
                                                        data-nama-htt_sub_pemeriksaan="{{ $laboratorium_bidang_subdata->nama_sublaboratorium_bidang }}"
                                                        data-target="#deletehtt_sub_pemeriksaanModal">
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

    @include('module.master-data.medis.laboratorium-bidang-sub.components.modal-add')
    @include('module.master-data.medis.laboratorium-bidang-sub.components.modal-edit')
    @include('module.master-data.medis.laboratorium-bidang-sub.components.modal-delete')
    @include('module.master-data.medis.laboratorium-bidang-sub.components.javascript')

@endsection
