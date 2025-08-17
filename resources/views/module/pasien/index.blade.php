@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h5 class="text-muted text-center">Selamat datang di modul Pendaftaran Pasien</h5>
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
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $pasienallold }}</h3>

                                <p>Total Pasien lama</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-clock "></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $pasienallnewnow }}</h3>

                                <p>Total Pasien Baru Bulan ini</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $pasienall }}</h3>

                                <p>Total Pasien</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $pasiennoverif }}</h3>

                                <p>Pasien Belun Verifikasi</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-xmark"></i>
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
                                <table id="userstabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="10%">Status</th>
                                            <th class="text-center">No.RM</th>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Tanggal Lahir</th>
                                            <th class="text-center">No.Kartu BPJS</th>
                                            <th class="text-center">No.Telepon</th>
                                            <th class="text-center" width="25%">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pasiens as $pasiensdata)
                                            <tr>
                                                <td class="text-center" >
                                                    @if ($pasiensdata->verifikasi == 1)
                                                        <i class="fas fa-user-xmark text-danger fa-fade"  title="Belum Verifikasi" style="cursor: pointer;"></i> <br>
                                                        <span class="badge badge-danger">Belum Verifikasi</span>
                                                    @elseif ($pasiensdata->verifikasi == 2)
                                                        <i class="fas fa-user-check text-success fa-fade"  title="Sudah Verifikasi" style="cursor: pointer;"></i> <br>
                                                        <span class="badge badge-success">Sudah Verifikasi</span>
                                                    @else
                                                        <i class="fas fa-user-slash text-warning fa-fade"  title="Tidak Aktif" style="cursor: pointer;"></i> <br>
                                                        <span class="badge badge-warning">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="text-center" >{{ $pasiensdata->no_rm }}</td>
                                                <td class="text-center" >{{ $pasiensdata->nama }}</td>
                                                <td class="text-center">{{ $pasiensdata->tanggal_lahir }}</td>
                                                <td class="text-center">{{ $pasiensdata->no_bpjs }}</td>
                                                <td class="text-center">{{ $pasiensdata->telepon }}</td>
                                                <td class="text-center">
                                                    @if ($pasiensdata->verifikasi == 1)
                                                        {{-- @can('lengkapi') --}}
                                                            <a class="btn btn-outline-info rounded-pill lengkapi-btn" data-toggle="modal"
                                                                data-target="#lengkapiModal"
                                                                data-id="{{ $pasiensdata->id }}">
                                                                <i class="fa fa-exclamation-circle"></i> Lengkapi
                                                            </a>

                                                            <!-- Tombol Panggil untuk data yang belum dilengkapi -->
                                                            {{-- <a class="btn btn-primary rounded-pill panggil-btn" data-toggle="modal"
                                                                data-target="#panggilModal"
                                                                data-id="{{ $pasiensdata->id }}"
                                                                data-nama="{{ $pasiensdata->nama }}">
                                                                <i class="fa fa-bullhorn"></i> Panggil
                                                            </a> --}}
                                                        {{-- @endcan --}}
                                                    @else
                                                        {{-- @can('rubah') --}}
                                                            <a class="btn btn-outline-warning btn-sm rounded-pill edit-btn" data-toggle="modal"
                                                                data-target="#EditModal"
                                                                data-id="{{ $pasiensdata->id }}">
                                                                <i class="fa-solid fa-user-pen"></i> Edit
                                                            </a>
                                                        {{-- @endcan --}}
                                                    @endif
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

    <!-- Modal Panggil Pasien -->



    <!-- Modal XL -->
      @include('module.pasien.components.modal-pangil-pasien')
      @include('module.pasien.components.modal-lengkapi-pasien')
      @include('module.pasien.components.modal-edit-pasien')
      @include('module.pasien.components.javascript')




@endsection

