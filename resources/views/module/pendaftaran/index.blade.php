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
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $pasienallold }}</h3>
                            <p>Total Pasien Terdaftar</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a class="small-box-footer">&nbsp;</a>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <div class="row text-center text-white">
                                <div class="col-md-6">
                                    <h3>{{ $jumlahDokter }}</h3>
                                    <p>Jumlah Dokter</p>
                                </div>
                                <div class="col-md-6">
                                    <h3>{{ $totalPasien }}</h3>
                                    <p>Total Pasien</p>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="small-box-footer" data-toggle="modal" data-target="#rekapModal">
                            Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $pasienallnewnow }}</h3>
                            <p>Total Pasien Selesai Di layani</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a class="small-box-footer">&nbsp;</a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="userstabel" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Pasien</th>
                                        <th class="text-center">Pendaftaran</th>
                                        <th class="text-center">No.Registrasi</th>
                                        <th class="text-center">Tanggal Registrasi</th>
                                        <th class="text-center">No.RM</th>
                                        <th class="text-center">No.Antrian</th>
                                        <th class="text-center">Poli</th>
                                        <th class="text-center">Penjamin</th>
                                        <th class="text-center">Dokter</th>
                                        <th class="text-center" width="15%">Tindakan</th>
                                    </tr>
                                </thead>
                                @foreach ($pendaftaran as $pendaftarandata)
                                <tbody>
                                        <td class="text-center">{{ $pendaftarandata->pasien->nama }}</td>
                                        <td class="text-center">
                                            @switch($pendaftarandata->status->Status_aplikasi)
                                                @case(1)
                                                    Aplikasi Offline
                                                    @break
                                                @case(2)
                                                    Aplikasi Online
                                                    @break
                                                @case(3)
                                                    Sistem BPJS / MJKN
                                                    @break
                                                @default
                                                    Tidak diketahui
                                            @endswitch
                                        </td>
                                        <td class="text-center">{{ $pendaftarandata->nomor_register }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($pendaftarandata->tanggal_kujungan)->format('d-m-Y') }}</td>
                                        <td class="text-center">{{ $pendaftarandata->nomor_rm }}</td>
                                        <td class="text-center">{{ $pendaftarandata->antrian }}</td>
                                        <td class="text-center">{{ $pendaftarandata->poli->nama }}</td>
                                        <td class="text-center">{{ $pendaftarandata->penjamin->nama }}</td>
                                        <td class="text-center">{{ $pendaftarandata->dokter->namauser->name }}</td>
                                        <td class="text-center">
                                            <!-- Batal -->
                                            @if ($pendaftarandata->is_apotek == 0)
                                                @if ($pendaftarandata->status->status_pendaftaran == 1)
                                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill batal-data-pasien me-1"
                                                        data-id="{{ $pendaftarandata->status->id }}"
                                                        data-nama-pasien="{{ $pendaftarandata->pasien->nama }}">
                                                        <i class="fas fa-times-circle"></i> Batal
                                                    </button>

                                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill panggil-data-pasien me-1"
                                                        data-id="{{ $pendaftarandata->status->id }}"
                                                        data-nama-pasien="{{ $pendaftarandata->pasien->nama }}">
                                                        <i class="fas fa-phone"></i> Hadir
                                                    </button>
                                                    @if ($pendaftarandata->status->Status_aplikasi == 1)
                                                    <br>
                                                            <button type="button" class="btn btn-outline-warning btn-sm rounded-pill dokter-data-pasien mt-2"
                                                                data-id="{{ $pendaftarandata->nomor_register }}"
                                                                data-poli="{{ $pendaftarandata->poli_id }}"
                                                                data-nama="{{ $pendaftarandata->pasien->nama }}"
                                                                data-tgl-kunjung="{{ $pendaftarandata->tanggal_kujungan }}">
                                                                <i class="fas fa-user-md"></i> Ubah Dokter
                                                        </button>
                                                    @endif
                                                @endif

                                                @if ($pendaftarandata->status->status_pendaftaran == 2)
                                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill batal-data-pasien-pcare me-1"
                                                    data-id="{{ $pendaftarandata->status->id }}"
                                                    data-nama-pasien="{{ $pendaftarandata->pasien->nama }}">
                                                    <i class="fas fa-times-circle"></i> Batal
                                                </button>
                                                @endif
                                            @else
                                                <button type="button"
                                                        class="btn btn-outline-success btn-sm rounded-pill disabled"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Sudah selesai diperiksa oleh dokter">
                                                    <i class="fas fa-check-circle"></i>  Selesai
                                                </button>
                                            @endif
                                        </td>

                                    </tbody>
                                    @endforeach
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




      @include('module.pendaftaran.components.modal-batal-pendaftaran')
      @include('module.pendaftaran.components.modal-pendaftaran')
      @include('module.pendaftaran.components.modal-pangil-pendaftaran')
      @include('module.pendaftaran.components.modal-status-pendaftaran')
      @include('module.pendaftaran.components.javascript')



@endsection
