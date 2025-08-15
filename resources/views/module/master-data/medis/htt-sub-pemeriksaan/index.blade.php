@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Sub pemeriksaan</h1>
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
                                    <a href="{{ route('htt_pemeriksaan.get') }}" class="btn btn-info">
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
                                            <th class="text-center">Nama Pemeriksaan</th>
                                            <th class="text-center">Nama Sub Pemeriksaan</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($htt_sub_pemeriksaan as $htt_sub_pemeriksaandata)
                                            <tr>
                                                <td class="text-center">{{ $htt_sub_pemeriksaandata->nama_pemeriksaan }}</td>
                                                <td class="text-center">{{ $htt_sub_pemeriksaandata->nama_subpemeriksaan }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-pemeriksaan_htt"
                                                        data-toggle="modal"data-id="{{ $htt_sub_pemeriksaandata->id }}"
                                                        data-nama-htt_sub_pemeriksaan="{{ $htt_sub_pemeriksaandata->nama_subpemeriksaan }}"
                                                        data-target="#deletehtt_sub_pemeriksaanModal">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-goldar"
                                                        data-toggle="modal"data-id="{{ $htt_sub_pemeriksaandata->id }}"
                                                        data-nama-htt_sub_pemeriksaan="{{ $htt_sub_pemeriksaandata->nama_subpemeriksaan }}"
                                                        data-target="#edithtt_sub_pemeriksaanModal">
                                                        <i class="fas fa-trash"></i> edit
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

        @include('module.master-data-medis.htt-sub-pemeriksaan.components.modal-add')
        @include('module.master-data-medis.htt-sub-pemeriksaan.components.modal-edit')
        @include('module.master-data-medis.htt-sub-pemeriksaan.components.modal-delete')
        @include('module.master-data-medis.htt-sub-pemeriksaan.components.javascript')
@endsection
