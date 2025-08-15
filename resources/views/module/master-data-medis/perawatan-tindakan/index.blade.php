@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pemeriksaan & Tindakan</h1>
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
                                <h3 class="card-title">Pemeriksaan & Tindakan</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addperawatan_tindakanModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                    <!-- Tombol Export -->
                                    <a href="{{ route('perawatan_tindakan.export') }}" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Export
                                    </a>

                                    <!-- Tombol Import (Memunculkan Modal) -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importperawatan_tindakanModal">
                                        <i class="fas fa-file-upload"></i> Import
                                    </button>

                                </div>
                            </div>
                            <div class="card-body">
                                <table id="perawatan_tindakantabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Kategori</th>
                                            <th class="text-center">Tarif Dokter</th>
                                            <th class="text-center">Tarif Perawat</th>
                                            <th class="text-center">Total Tarif</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($perawatan_tindakan as $perawatan_tindakandata)
                                            <tr>
                                                <td class="text-center">{{ $perawatan_tindakandata->kode }}</td>
                                                <td class="text-center">{{ $perawatan_tindakandata->nama }}</td>
                                                <td class="text-center">{{ $perawatan_tindakandata->perawatan_kategori->nama }}</td>
                                                <td class="text-center">Rp {{ number_format((int)$perawatan_tindakandata->tarif_dokter, 0, ',', '.') }}</td>
                                                <td class="text-center">Rp {{ number_format((int)$perawatan_tindakandata->tarif_perawat, 0, ',', '.') }}</td>
                                                <td class="text-center">Rp {{ number_format((int)$perawatan_tindakandata->tarif_total, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-warning btn-sm edit-data-perawatan_tindakan"
                                                        data-toggle="modal" data-id="{{ $perawatan_tindakandata->id }}"
                                                        data-nama-perawatan_tindakan="{{ $perawatan_tindakandata->nama }}"
                                                        data-kategori-perawatan_tindakan="{{ $perawatan_tindakandata->perawatan_kategori_id }}"
                                                        data-tarif_dokter-perawatan_tindakan="{{ $perawatan_tindakandata->tarif_dokter }}"
                                                        data-tarif_perawat-perawatan_tindakan="{{ $perawatan_tindakandata->tarif_perawat }}"
                                                        data-tarif_total-perawatan_tindakan="{{ $perawatan_tindakandata->tarif_total }}"
                                                        data-target="#editperawatan_tindakanModa">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-perawatan_tindakan"
                                                        data-toggle="modal"data-id="{{ $perawatan_tindakandata->id }}"
                                                        data-nama-perawatan_tindakan="{{ $perawatan_tindakandata->nama }}"
                                                        data-target="#deleteperawatan_tindakanModal">
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

    @include('module.master-data-medis.perawatan-tindakan.components.modal-add')
    @include('module.master-data-medis.perawatan-tindakan.components.modal-edit')
    @include('module.master-data-medis.perawatan-tindakan.components.modal-delete')
    @include('module.master-data-medis.perawatan-tindakan.components.modal-import')
    @include('module.master-data-medis.perawatan-tindakan.components.javascript')

@endsection
