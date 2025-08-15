@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h5 class="text-muted text-center">Selamat datang di modul data master Asuransi</h5>
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
                                <table id="asuransitabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asuransi as $asuransidata)
                                            <tr>
                                                <td class="text-center">{{ $asuransidata->nama }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-asuransi"
                                                        data-toggle="modal"
                                                        data-id="{{ $asuransidata->id }}"
                                                        data-nama="{{ $asuransidata->nama }}"
                                                        data-kode="{{ $asuransidata->kode }}"
                                                        data-jenis="{{ $asuransidata->jenis_asuransi }}"
                                                        data-verifikasi="{{ $asuransidata->verif_pasien }}"
                                                        data-filter="{{ $asuransidata->filter_obat }}"
                                                        data-tglmulai="{{ $asuransidata->tanggal_mulai }}"
                                                        data-tglakhir="{{ $asuransidata->tanggal_akhir }}"
                                                        data-alamat="{{ $asuransidata->alamat_asuransi }}"
                                                        data-telpas="{{ $asuransidata->no_telp_asuransi }}"
                                                        data-faks="{{ $asuransidata->faksimil }}"
                                                        data-pic="{{ $asuransidata->pic }}"
                                                        data-telppic="{{ $asuransidata->no_telp_pic }}"
                                                        data-jabatan="{{ $asuransidata->jabatan_pic }}"
                                                        data-bank="{{ $asuransidata->bank }}"
                                                        data-rekening="{{ $asuransidata->no_rekening }}"
                                                        data-target="#editasuransiModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>

                                                    <a href="#" class="btn btn-danger btn-sm delete-data-asuransi"
                                                        data-toggle="modal"data-id="{{ $asuransidata->id }}"
                                                        data-nama-asuransi="{{ $asuransidata->nama }}"
                                                        data-target="#deleteasuransiModal">
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

@include('module.master-data.umum.asuransi.components.modal-add')
@include('module.master-data.umum.asuransi.components.modal-edit')
@include('module.master-data.umum.asuransi.components.modal-delete')
@include('module.master-data.umum.asuransi.components.modal-import')
@include('module.master-data.umum.asuransi.components.javascript')


@endsection
