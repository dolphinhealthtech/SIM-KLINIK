@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">ICD 10</h1>
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
                                <h3 class="card-title">ICD 10</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addalergiModal">
                                        <i class="fas fa-plus"></i> Sinkron
                                    </button>

                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addicd10Modal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('icd10.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importGoldarModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="icd10tabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama ICD 10</th>
                                            <th class="text-center">Kode ICD 10 </th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($icd10 as $icd10data)
                                            <tr>
                                                <td class="text-center">{{ $icd10data->nama_icd10 }}</td>
                                                <td class="text-center">{{ $icd10data->kode_icd10 }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-icd10"
                                                        data-toggle="modal" data-id="{{ $icd10data->id }}"
                                                        data-nama-icd10="{{ $icd10data->nama_icd10 }}"
                                                        data-rhesus="{{ $icd10data->kode_icd10 }}"
                                                        data-target="#editicd10Moda">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-icd10"
                                                        data-toggle="modal"data-id="{{ $icd10data->id }}"
                                                        data-nama-icd10="{{ $icd10data->nama_icd10 }}"
                                                        data-rhesus="{{ $icd10data->kode_icd10 }}"
                                                        data-target="#deleteicd10Modal">
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
    <!-- Modal untuk Tambah ICD 10 -->
    @include('module.master-data.medis.icd10.components.modal-add')
    <!-- Modal untuk Edit ICD 10 -->
    @include('module.master-data.medis.icd10.components.modal-edit')
    <!-- Modal untuk Hapus ICD 10 -->
    @include('module.master-data.medis.icd10.components.modal-delete')
    <!-- Modal untuk Import ICD 10 -->
    @include('module.master-data.medis.icd10.components.modal-import')
    <!-- Modal untuk Add Alergi ICD 10 -->
    @include('module.master-data.medis.icd10.components.modal-add-alergi')
    <!-- Modal untuk Add Alergi ICD 10 -->
    @include('module.master-data.medis.icd10.components.javascript')
@endsection
