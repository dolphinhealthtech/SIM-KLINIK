@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h5 class="text-muted text-center">Selamat datang di modul Pelayanan Dokter</h5>
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
                            <div class="card-body">
                                <table id="banktabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">No.RM</th>
                                            <th class="text-center">Pasien</th>
                                            <th class="text-center">No.Antrian</th>
                                            <th class="text-center">No.Registrasi</th>
                                            <th class="text-center">Tanggal Kunjungan</th>
                                            <th class="text-center">Poli</th>
                                            <th class="text-center">Dokter</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pelayanan as $pelayanandata)
                                            <tr>
                                                <td class="text-center">
                                                    @if ($pelayanandata->tindakan_button == 'panggil')
                                                        <span class="badge badge-warning rounded-pill">Belum Hadir</span>
                                                    @elseif ($pelayanandata->tindakan_button == 'soap')
                                                        <span class="badge badge-primary rounded-pill">Pemeriksaan</span>
                                                    @elseif ($pelayanandata->tindakan_button == 'edit')
                                                        <span class="badge badge-info rounded-pill">Selesai Cek</span>
                                                    @elseif ($pelayanandata->tindakan_button == 'Complete')
                                                        <span class="badge badge-info rounded-pill">Selesai</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $pelayanandata->nomor_rm }}</td>
                                                <td class="text-center">{{ $pelayanandata->pasien->nama }}</td>
                                                <td class="text-center">{{ $pelayanandata->pendaftaran->antrian }}</td>
                                                <td class="text-center">{{ $pelayanandata->nomor_register }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($pelayanandata->tanggal_kujungan)->format('d-m-Y') }}</td>
                                                <td class="text-center">{{ $pelayanandata->poli->nama }}</td>
                                                <td class="text-center">{{ $pelayanandata->dokter->namauser->name }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $norawat = base64_encode($pelayanandata->nomor_register);
                                                    @endphp


                                                    @if ($pelayanandata->tindakan_button == 'panggil')
                                                        <button type="button"
                                                                class="btn btn-outline-warning btn-sm rounded-pill pasien-hadir"
                                                                data-url="{{ route('soappelayana.hadir.hadir', ['norawat' => $norawat]) }}"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Panggil pasien ke ruangan">
                                                            <i class="fas fa-bell"></i> Panggil
                                                        </button>

                                                    @elseif ($pelayanandata->tindakan_button == 'soap')
                                                        <button type="button"
                                                                class="btn btn-outline-primary btn-sm rounded-pill"
                                                                onclick="window.location.href='{{ route('pelayana_dokter.get', ['norawat' => $norawat]) }}'"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Lanjutkan ke SOAP & Pemeriksaan">
                                                            <i class="fas fa-file-medical-alt"></i> Pemeriksaan
                                                        </button>

                                                    @elseif ($pelayanandata->tindakan_button == 'edit')
                                                        <button type="button"
                                                                class="btn btn-outline-primary btn-sm rounded-pill"
                                                                onclick="window.location.href='{{ route('pelayana_rujuk.get', ['norawat' => $norawat]) }}'"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Edit data SOAP yang sudah diisi">
                                                            <i class="fas fa-paper-plane"></i> Rujuk
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-outline-warning btn-sm rounded-pill"
                                                                onclick="window.location.href='{{ route('pelayana_dokter.edit', ['norawat' => $norawat]) }}'"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Edit data SOAP yang sudah diisi">
                                                            <i class="fa-solid fa-user-pen"></i> Edit
                                                        </button>
                                                        <br>
                                                        <button type="button"
                                                                class="btn btn-outline-info btn-sm rounded-pill mt-2"
                                                                onclick="window.location.href='{{ route('pelayana_permintaan.get', ['norawat' => $norawat]) }}'"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Edit data SOAP yang sudah diisi">
                                                            <i class="fas fa-file-alt"></i> Permintaan
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-outline-success btn-sm rounded-pill pasien-selesai"
                                                                data-url="{{ route('pelayana_dokter.selesai', ['norawat' => $norawat]) }}"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Pasien Selesai">
                                                            <i class="fas fa-user-check"></i> Selesai
                                                        </button>
                                                    @elseif ($pelayanandata->tindakan_button == 'Complete')
                                                        <button type="button"
                                                                class="btn btn-outline-success btn-sm rounded-pill disabled"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Sudah selesai diperiksa oleh dokter">
                                                            <i class="fas fa-check-circle"></i> Dicek
                                                        </button>
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


@include('module.pelayanan.pelayanan-dokter.components.javascript')
@endsection
