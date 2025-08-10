@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>SOAP Rawat Jalan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">SOAP Rawat Jalan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Data Pasien -->
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-injured mr-2"></i>Data Pasien</h3>
                        </div>
                        <div class="card-body">
                            <!-- Brand Logo dari sidebar dengan path yang diubah ke public/profile/default.png -->
                            <div class="text-center mb-4">
                                <img src="{{ asset('profile/default.png') }}"
                                    alt="Klinik Logo" class="img-circle elevation-2"
                                    style="width: 100px; height: 100px; opacity: .8">
                            </div>

                            <div class="form-group">
                                <label for="nomor_rm">No. RM</label>
                                <input type="text" class="form-control bg-light" id="nomor_rm" value="{{ $pelayanan->nomor_rm }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="nama">Nama Pasien</label>
                                <input type="text" class="form-control bg-light" id="nama" value="{{ $pelayanan->pasien->nama }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <input type="text" class="form-control bg-light" id="jenis_kelamin" value="{{ $pelayanan->pasien->kelamin->nama }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="penjamin">Penjamin</label>
                                <input type="text" class="form-control bg-light" id="penjamin" value="{{ $pelayanan->pendaftaran->penjamin->nama }}" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="text" class="form-control bg-light" id="tanggal_lahir" value="{{ $pelayanan->pasien->tanggal_lahir }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="umur">Umur</label>
                                        <input type="text" class="form-control bg-light" id="umur" value="{{ $umur }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="timeline">
                                <!-- Tanggal CPPT -->
                                <div class="time-label">
                                    <span class="bg-primary text-white px-3 py-1 rounded">7 Agustus 2025</span>
                                </div>

                                <!-- Item CPPT -->
                                <div>
                                    <i class="fas fa-user-md bg-success"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> 08:30</span>
                                        <h3 class="timeline-header mb-2">dr. Rudi Hartono – Dokter</h3>

                                        <div class="timeline-body">
                                            <!-- Nav Tabs -->
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="soap-s-tab" data-toggle="tab" href="#soap-s" role="tab">S</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="soap-o-tab" data-toggle="tab" href="#soap-o" role="tab">O</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="soap-a-tab" data-toggle="tab" href="#soap-a" role="tab">A</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="soap-p-tab" data-toggle="tab" href="#soap-p" role="tab">P</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="soap-i-tab" data-toggle="tab" href="#soap-i" role="tab">Instruksi</a>
                                                </li>
                                            </ul>

                                            <!-- Tab Content -->
                                            <div class="tab-content border rounded-bottom p-3">
                                                <div class="tab-pane fade show active" id="soap-s" role="tabpanel" aria-labelledby="soap-s-tab">
                                                    <p><strong>Subjektif:</strong> Keluhan pusing</p>
                                                </div>
                                                <div class="tab-pane fade" id="soap-o" role="tabpanel" aria-labelledby="soap-o-tab">
                                                    <p><strong>Objektif:</strong> TD 130/85 mmHg, suhu 36.8°C</p>
                                                </div>
                                                <div class="tab-pane fade" id="soap-a" role="tabpanel" aria-labelledby="soap-a-tab">
                                                    <p><strong>Asesmen:</strong> Hipertensi ringan</p>
                                                </div>
                                                <div class="tab-pane fade" id="soap-p" role="tabpanel" aria-labelledby="soap-p-tab">
                                                    <p><strong>Planning:</strong> Berikan Amlodipine 5mg</p>
                                                </div>
                                                <div class="tab-pane fade" id="soap-i" role="tabpanel" aria-labelledby="soap-i-tab">
                                                    <p><strong>Instruksi:</strong> Kontrol 7 hari lagi</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
