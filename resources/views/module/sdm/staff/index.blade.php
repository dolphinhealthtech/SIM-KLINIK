@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h5 class="text-muted text-center">Selamat datang di modul Pendaftaran Staff</h5>
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
                    <!-- ./col -->
                    <div class="col-lg-6 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $dokterall }}</h3>

                                <p>Total Staff</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-6 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $dokternoverif }}</h3>

                                <p>Staff Belun Verifikasi</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="doktertabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">tanggal Masuk</th>
                                            <th class="text-center">status Pegawai</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dokter as $dokterdata)
                                            <tr>
                                                <td class="text-center">
                                                    @if ($dokterdata->verifikasi == 1)
                                                        <span class="position-relative d-inline-block">
                                                            <i class="fas fa-user text-danger fa-fade"
                                                                style="font-size: 24px;"></i>
                                                            <i class="fas fa-xmark text-danger fa-beat position-absolute"
                                                                style="top: -8px; right: -8px; font-size: 12px;"></i>
                                                        </span> <br>
                                                        <span class="badge badge-danger">Belum Verifikasi</span>
                                                    @elseif ($dokterdata->verifikasi == 2)
                                                        <span class="position-relative d-inline-block"
                                                            title="Belum Verifikasi" style="cursor: pointer;">
                                                            <i class="fas fa-user text-success fa-fade"
                                                                style="font-size: 20px;"></i>
                                                            <i class="fas fa-check text-success position-absolute"
                                                                style="top: -6px; right: -6px; font-size: 10px;"></i>
                                                        </span> <br>
                                                        <span class="badge badge-success">Sudah Verifikasi</span>
                                                    @else
                                                        <i class="fas fa-user-slash text-warning fa-fade"
                                                            title="Tidak Aktif" style="cursor: pointer;"></i> <br>
                                                        <span class="badge badge-warning">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $dokterdata->namauser->name }}</td>
                                                <td class="text-center">{{ $dokterdata->tgl_masuk }}</td>
                                                <td class="text-center">{{ $dokterdata->namastatuspegawai->nama }}</td>
                                                <td class="text-center">
                                                    {{-- Jika dokter sudah verifikasi --}}
                                                    @if ($dokterdata->verifikasi == 1)
                                                        <a class="btn btn-outline-info btn-sm rounded-pill lengkapi-btn"
                                                            data-toggle="modal" data-target="#lengkapiModal"
                                                            data-id="{{ $dokterdata->id }}">
                                                            <i class="fa fa-exclamation-circle"></i> Lengkapi
                                                        </a>
                                                    @else
                                                        <a class="btn btn-outline-warning btn-sm rounded-pill edit-btn"
                                                            data-toggle="modal" data-target="#editdokterModal"
                                                            data-id="{{ $dokterdata->id }}">
                                                            <i class="fa-solid fa-user-pen"></i> Edit
                                                        </a>
                                                    @endif

                                                    {{-- Tombol Delete --}}
                                                    <a class="btn btn-outline-danger btn-sm rounded-pill delete-data-dokter"
                                                        data-toggle="modal" data-id="{{ $dokterdata->id }}"
                                                        data-nama-dokter="{{ $dokterdata->namauser->name }}"
                                                        data-target="#deletedokterModal">
                                                        <i class="fas fa-trash"></i> Delete
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

    @include('module.sdm.staff.components.modal-add')
    @include('module.sdm.staff.components.modal-edit')
    @include('module.sdm.staff.components.modal-delete')
    @include('module.sdm.staff.components.modal-verifikasi')
    @include('module.sdm.staff.components.javascript')
@endsection
