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
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-four-Timeline-tab" data-toggle="pill" href="#custom-tabs-four-Timeline" role="tab" aria-controls="custom-tabs-four-Timeline" aria-selected="true">Timeline</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-CPPT-tab" data-toggle="pill" href="#custom-tabs-four-CPPT" role="tab" aria-controls="custom-tabs-four-CPPT" aria-selected="false">CPPT</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-laporan-tab" data-toggle="pill" href="#custom-tabs-four-laporan" role="tab" aria-controls="custom-tabs-four-laporan" aria-selected="false">Head To Toe</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-four-Timeline" role="tabpanel" aria-labelledby="custom-tabs-four-Timeline-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="timeline">
                                            <div class="time-label">
                                                <span class="bg-red">25 Mar 2025</span>
                                            </div>
                                            <div>
                                                <i class="fas fa-hospital-user bg-blue"></i>
                                                <div class="timeline-item">
                                                    <span class="time"><i class="fas fa-clock"></i>03:51</span>
                                                    <h3 class="timeline-header">Pasien Registrasi ke Rawat Jalan</h3>
                                                        <div class="timeline-body">
                                                            Pasien dengan No. Rawat: 2025/03/25/001 telah terdaftar di Rawat Jalan.
                                                        </div>
                                                    <div class="timeline-footer">
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <i class="fas fa-clock bg-gray"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="custom-tabs-four-CPPT" role="tabpanel" aria-labelledby="custom-tabs-four-CPPT-tab">
                            </div>

                            <div class="tab-pane fade" id="custom-tabs-four-laporan" role="tabpanel" aria-labelledby="custom-tabs-four-laporan-tab">
                                <form id="formLaporan">
                                    <!-- Struktur navigasi yang lebih rapi -->
                                        <div class="card mb-4">
                                            <div class="card-body p-0">
                                                <!-- Progress Bar -->
                                                <div class="progress mb-0" style="height: 8px; border-radius: 0;">
                                                    <div id="progress-bar" class="progress-bar bg-secondary progress-bar-striped progress-bar-animated"
                                                         role="progressbar" style="width: 12.5%" aria-valuenow="12.5" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Judul Laporan dengan warna abu-abu -->
                                        <div class="bg-secondary p-2 mb-3">
                                            <h4 class="text-center text-white mb-0">LAPORAN ASESMEN AWAL RAWAT JALAN</h4>
                                        </div>

                                        <!-- Step A: RISIKO JATUH -->
                                        <div id="step-a" class="step-content">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">A. RISIKO JATUH</h3>
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="font-italic mb-3">GET UP AND GO</h5>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%">No</th>
                                                                    <th width="65%">Penilaian/Pengkajian</th>
                                                                    <th width="15%">Ya</th>
                                                                    <th width="15%">Tidak</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>a.</td>
                                                                    <td>
                                                                        Cara berjalan pasien (salah satu atau lebih)<br>
                                                                        1. Tidak seimbang/sempoyongan/limbung<br>
                                                                        2. Jalan dengan menggunakan alat bantu (kruk, tripot, kursi roda, orang lain)
                                                                    </td>
                                                                    <td class="text-center"><input type="radio" name="jalan_pasien" value="ya"></td>
                                                                    <td class="text-center"><input type="radio" name="jalan_pasien" value="tidak"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>b.</td>
                                                                    <td>Menopang saat akan duduk: tampak memegang pinggiran kursi atau meja/benda lain penopang saat akan duduk</td>
                                                                    <td class="text-center"><input type="radio" name="menopang_duduk" value="ya"></td>
                                                                    <td class="text-center"><input type="radio" name="menopang_duduk" value="tidak"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="mt-3">
                                                        <p><strong>Tidak Berisiko</strong>: a dan b (Tidak)</p>
                                                        <p><strong>Risiko Rendah</strong>: a atau b (Ya)</p>
                                                        <p><strong>Risiko Tinggi</strong>: a dan b (Ya)</p>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-secondary btn-kembali" onclick="navigateBack()" style="visibility:hidden;">Kembali</button>
                                                        <button type="button" class="btn btn-primary btn-next" onclick="navigateNext()">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step B: SKRINING NYERI -->
                                        <div id="step-b" class="step-content" style="display:none; opacity:0;">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">B. SKRINING NYERI</h3>
                                                </div>
                                                <div class="card-body">
                                                    <p class="mb-3">Apakah terdapat keluhan nyeri?</p>
                                                    <div class="form-check form-check-inline mb-3">
                                                        <input class="form-check-input" type="radio" name="keluhan_nyeri" id="nyeri_tidak" value="tidak">
                                                        <label class="form-check-label" for="nyeri_tidak">Tidak</label>
                                                    </div>
                                                    <div class="form-check form-check-inline mb-3">
                                                        <input class="form-check-input" type="radio" name="keluhan_nyeri" id="nyeri_ya" value="ya">
                                                        <label class="form-check-label" for="nyeri_ya">Ya, Jelaskan P:</label>
                                                        <input type="text" class="form-control form-control-sm ml-2" style="width: 300px;">
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-2">Q:</div>
                                                        <div class="col-md-10"><input type="text" class="form-control"></div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-2">R:</div>
                                                        <div class="col-md-10"><input type="text" class="form-control"></div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-2">S:</div>
                                                        <div class="col-md-10"><input type="text" class="form-control"></div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-2">T:</div>
                                                        <div class="col-md-10"><input type="text" class="form-control"></div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-body text-center">
                                                                    <p class="small">Untuk pasien dewasa dan anak lebih dari 3 tahun yang <strong>tidak dapat</strong> menggambarkan intensitas nyerinya dengan angka,</p>
                                                                    <img src="{{ asset('images/wong-baker-faces.png') }}" class="img-fluid" alt="Wong Baker FACES Pain Scale">
                                                                    <p class="small mt-2">Wong Baker FACES Pain Scale</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-body text-center">
                                                                    <p class="small">Untuk pasien dewasa dan anak lebih dari 3 tahun yang <strong>dapat</strong> menggambarkan intensitas nyerinya dengan angka,</p>
                                                                    <img src="{{ asset('images/numeric-pain-scale.png') }}" class="img-fluid" alt="Numeric Pain Scale">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <p><strong>P</strong>: Provoke (pencetus, faktor yang mempengaruhi/gawat tidaknya, atau beratnya nyeri)</p>
                                                            <p><strong>Q</strong>: Quality/kualitas, apakah nyeri seperti tertusuk, tertindih beban, tajam, tumpul, terbakar?</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>R</strong>: Region (daerah, area perjalanan)</p>
                                                            <p><strong>S</strong>: Severity (keparahan, skala nyeri diukur sesuai dengan tingkat usia dan kondisi kesadaran pasien)</p>
                                                            <p><strong>T</strong>: Timing (waktu, durasi atau lama waktu serangan)</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-secondary btn-kembali" onclick="navigateBack()">Kembali</button>
                                                        <button type="button" class="btn btn-primary btn-next" onclick="navigateNext()">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step C: RIWAYAT KESEHATAN -->
                                        <div id="step-c" class="step-content" style="display:none; opacity:0;">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">C. RIWAYAT KESEHATAN</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">a. Penyakit yang pernah diderita</div>
                                                        <div class="col-md-9"><input type="text" class="form-control"></div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">b. Operasi yang dialami</div>
                                                        <div class="col-md-9"><input type="text" class="form-control" placeholder="Jenis, kapan, komplikasi yang ada"></div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">c. Riwayat penyakit herediter</div>
                                                        <div class="col-md-9">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="penyakit_herediter" id="herediter_tidak" value="tidak">
                                                                <label class="form-check-label" for="herediter_tidak">Tidak ada</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="penyakit_herediter" id="herediter_ya" value="ya">
                                                                <label class="form-check-label" for="herediter_ya">Ada, jelaskan:</label>
                                                                <input type="text" class="form-control form-control-sm ml-2" style="width: 300px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">d. Riwayat penyakit dalam keluarga saat ini</div>
                                                        <div class="col-md-9"><input type="text" class="form-control"></div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">e. Ketergantungan terhadap</div>
                                                        <div class="col-md-9">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="ketergantungan_obat" value="obat">
                                                                <label class="form-check-label" for="ketergantungan_obat">Obat-obatan</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="ketergantungan_rokok" value="rokok">
                                                                <label class="form-check-label" for="ketergantungan_rokok">Rokok</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="ketergantungan_alkohol" value="alkohol">
                                                                <label class="form-check-label" for="ketergantungan_alkohol">Alkohol</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="ketergantungan_tidak" value="tidak">
                                                                <label class="form-check-label" for="ketergantungan_tidak">Tidak ada</label>
                                                            </div>
                                                            <div class="mt-2">
                                                                <label>Jelaskan:</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">f. Riwayat pekerjaan</div>
                                                        <div class="col-md-9">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="riwayat_pekerjaan" id="pekerjaan_tidak" value="tidak">
                                                                <label class="form-check-label" for="pekerjaan_tidak">Tidak</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="riwayat_pekerjaan" id="pekerjaan_ya" value="ya">
                                                                <label class="form-check-label" for="pekerjaan_ya">Ya, Jelaskan:</label>
                                                                <input type="text" class="form-control form-control-sm ml-2" style="width: 300px;" placeholder="apakah berhubungan dengan zat-zat berbahaya">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-secondary btn-kembali" onclick="navigateBack()">Kembali</button>
                                                        <button type="button" class="btn btn-primary btn-next" onclick="navigateNext()">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step D: PENGKAJIAN FUNGSIONAL BARTEL INDEKS -->
                                        <div id="step-d" class="step-content" style="display:none; opacity:0;">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">D. PENGKAJIAN FUNGSIONAL BARTEL INDEKS</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th width="40%">INDIKATOR</th>
                                                                    <th width="10%">SKOR</th>
                                                                    <th width="40%">INDIKATOR</th>
                                                                    <th width="10%">SKOR</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Mengendalikan rangsangan Buang Air Besar (BAB)</td>
                                                                    <td><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                    <td>Berubah sikap dari berbaring ke duduk</td>
                                                                    <td><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Mengendalikan rangsangan Buang Air Kecil (BAK)</td>
                                                                    <td><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                    <td>Berpindah/berjalan</td>
                                                                    <td><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Membersihkan diri (cuci muka, menyisir rambut, sikat gigi)</td>
                                                                    <td><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                    <td>Memakai baju</td>
                                                                    <td><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Penggunaan toilet masuk dan keluar (melepas, memakai celana, membersihkan, menyiram)</td>
                                                                    <td><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                    <td>Naik turun tangga</td>
                                                                    <td><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Makan</td>
                                                                    <td><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                    <td>Mandi</td>
                                                                    <td><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3" class="text-right"><strong>TOTAL SKOR</strong></td>
                                                                    <td><input type="number" class="form-control form-control-sm" min="0" readonly></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="mandiri">
                                                                <label class="form-check-label" for="mandiri">Mandiri (skor 20)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="bantuan_ringan">
                                                                <label class="form-check-label" for="bantuan_ringan">Perlu bantuan ringan (12-19)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="ketergantungan_sedang">
                                                                <label class="form-check-label" for="ketergantungan_sedang">Ketergantungan sedang (9-11)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="ketergantungan_berat">
                                                                <label class="form-check-label" for="ketergantungan_berat">Ketergantungan Berat (5-8)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="ketergantungan_total">
                                                                <label class="form-check-label" for="ketergantungan_total">Ketergantungan total (0-4)</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-secondary btn-kembali" onclick="navigateBack()">Kembali</button>
                                                        <button type="button" class="btn btn-primary btn-next" onclick="navigateNext()">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step E: SKRINING GIZI -->
                                        <div id="step-e" class="step-content" style="display:none; opacity:0;">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">E. SKRINING GIZI</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header bg-light">
                                                                    <h5 class="card-title">MST-Malnutrition Screening Tools<br>(untuk dewasa usia > 18 tahun)</h5>
                                                                </div>
                                                                <div class="card-body p-2">
                                                                    <table class="table table-bordered table-sm">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="5%">NO</th>
                                                                                <th width="65%">PARAMETER</th>
                                                                                <th width="15%">SKOR</th>
                                                                                <th width="15%">HASIL</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>
                                                                                    Apakah pasien mengalami penurunan berat badan yang tidak diinginkan selama 6 bulan terakhir<br>
                                                                                    a. Tidak ada penurunan<br>
                                                                                    b. Tidak yakin / tidak tahu / baju terasa longgar<br>
                                                                                    c. Jika Ya, berapa penurunan berat badan tersebut<br>
                                                                                    &nbsp;&nbsp;&nbsp;1) 1-5 kg<br>
                                                                                    &nbsp;&nbsp;&nbsp;2) 6-10 kg<br>
                                                                                    &nbsp;&nbsp;&nbsp;3) 11-15 kg<br>
                                                                                    &nbsp;&nbsp;&nbsp;4) >15 kg
                                                                                </td>
                                                                                <td class="align-middle text-center">
                                                                                    0<br>
                                                                                    2<br><br>
                                                                                    1<br>
                                                                                    2<br>
                                                                                    3<br>
                                                                                    4
                                                                                </td>
                                                                                <td><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>
                                                                                    Apakah asupan makan berkurang karena tidak mau nafsu makan?<br>
                                                                                    a. Ya<br>
                                                                                    b. Tidak
                                                                                </td>
                                                                                <td class="align-middle text-center">
                                                                                    0<br>
                                                                                    1
                                                                                </td>
                                                                                <td><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2" class="text-right"><strong>TOTAL SKOR</strong></td>
                                                                                <td colspan="2"><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div class="mt-2">
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="checkbox" id="diagnosa_khusus_ya">
                                                                            <label class="form-check-label" for="diagnosa_khusus_ya">Pasien dengan diagnose khusus</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="checkbox" id="diagnosa_khusus_tidak">
                                                                            <label class="form-check-label" for="diagnosa_khusus_tidak">Tidak</label>
                                                                        </div>
                                                                        <p class="small mt-1">(DM/Gangguan fungsi Tiroid/Infeksi kronik? Lain-lain, Sebutkan ............................)</p>
                                                                        <p class="small">Bila skor > 2 atau pasien dengan diagnosis/kondisi khusus dilakukan pengkajian lanjut oleh Dietisen</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header bg-light">
                                                                    <h5 class="card-title">Modifikasi STRONG – Kids<br>(untuk anak usia 1 bulan – 18 tahun)</h5>
                                                                </div>
                                                                <div class="card-body p-2">
                                                                    <table class="table table-bordered table-sm">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="5%">NO</th>
                                                                                <th width="65%">PERTANYAAN</th>
                                                                                <th colspan="2" class="text-center">Jawaban (Skor)</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <th colspan="2"></th>
                                                                                <th width="15%" class="text-center">Tidak (0)</th>
                                                                                <th width="15%" class="text-center">Ya (1)</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>Apakah pasien tampak kurus?</td>
                                                                                <td class="text-center"><input type="radio" name="tampak_kurus" value="0"></td>
                                                                                <td class="text-center"><input type="radio" name="tampak_kurus" value="1"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>
                                                                                    Apakah ada penurunan berat badan selama 1 bulan terakhir?<br>
                                                                                    (Berdasarkan penilaian obyektif/data berat badan bila ada ATAU penilaian subyektif orang tua pasien)<br>
                                                                                    ATAU?<br>
                                                                                    Untuk bayi < 1 tahun : berat badan tidak naik selama 3 bulan terakhir
                                                                                </td>
                                                                                <td class="text-center"><input type="radio" name="penurunan_bb" value="0"></td>
                                                                                <td class="text-center"><input type="radio" name="penurunan_bb" value="1"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>
                                                                                    Apakah terdapat SALAH SATU dari kondisi berikut?<br>
                                                                                    1. Diare > 5 kali/hari dan atau muntah > 3 kali/hari dalam seminggu terakhir<br>
                                                                                    2. Asupan makanan berkurang selama 1 minggu terakhir
                                                                                </td>
                                                                                <td class="text-center"><input type="radio" name="kondisi_diare" value="0"></td>
                                                                                <td class="text-center"><input type="radio" name="kondisi_diare" value="1"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>
                                                                                    Apakah terdapat penyakit atau keadaan yang mengakibatkan pasien beresiko mengalami malnutrisi<br>
                                                                                    (Stomatitis, ISPA, nyeri yang tua DM, Diare, dll)
                                                                                </td>
                                                                                <td class="text-center"><input type="radio" name="penyakit_malnutrisi" value="0"></td>
                                                                                <td class="text-center"><input type="radio" name="penyakit_malnutrisi" value="1"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2" class="text-right"><strong>TOTAL SKOR</strong></td>
                                                                                <td colspan="2"><input type="number" class="form-control form-control-sm" min="0"></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div class="mt-2">
                                                                        <p class="mb-1">Interpretasi skor :</p>
                                                                        <p class="mb-1">0 = Resiko rendah &nbsp;&nbsp;&nbsp; 1-3 = Resiko sedang &nbsp;&nbsp;&nbsp; 4-5 = Resiko berat</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-secondary btn-kembali" onclick="navigateBack()">Kembali</button>
                                                        <button type="button" class="btn btn-primary btn-next" onclick="navigateNext()">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step F: PSIKOLOGIS/SPIRITUAL -->
                                        <div id="step-f" class="step-content" style="display:none; opacity:0;">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">F. PSIKOLOGIS/SPIRITUAL</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">Taat beribadah</div>
                                                        <div class="col-md-9">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="taat_beribadah" id="taat_ya" value="ya">
                                                                <label class="form-check-label" for="taat_ya">Ya</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="taat_beribadah" id="taat_tidak" value="tidak">
                                                                <label class="form-check-label" for="taat_tidak">Tidak, Jelaskan</label>
                                                                <input type="text" class="form-control form-control-sm ml-2" style="width: 300px;">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="takut_tindakan" value="takut">
                                                                <label class="form-check-label" for="takut_tindakan">Takut terhadap tindakan lingkungan</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="cemas" value="cemas">
                                                                <label class="form-check-label" for="cemas">Cemas</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="marah_tegang" value="marah">
                                                                <label class="form-check-label" for="marah_tegang">Marah/tegang</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="sedih" value="sedih">
                                                                <label class="form-check-label" for="sedih">Sedih</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="menangis" value="menangis">
                                                                <label class="form-check-label" for="menangis">Menangis</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="senang" value="senang">
                                                                <label class="form-check-label" for="senang">Senang</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="tidak_menahan_diri" value="tidak_menahan">
                                                                <label class="form-check-label" for="tidak_menahan_diri">Tidak mampu menahan diri</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="rendah_diri" value="rendah_diri">
                                                                <label class="form-check-label" for="rendah_diri">Rendah diri</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="gelisah" value="gelisah">
                                                                <label class="form-check-label" for="gelisah">Gelisah</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="tenang" value="tenang">
                                                                <label class="form-check-label" for="tenang">Tenang</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="mudah_tersinggung" value="tersinggung">
                                                                <label class="form-check-label" for="mudah_tersinggung">Mudah tersinggung</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-secondary btn-kembali" onclick="navigateBack()">Kembali</button>
                                                        <button type="button" class="btn btn-primary btn-next" onclick="navigateNext()">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step G: SOSIAL EKONOMI -->
                                        <div id="step-g" class="step-content" style="display:none; opacity:0;">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">G. SOSIAL EKONOMI</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">Kebiasaan bila sakit</div>
                                                        <div class="col-md-9">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="pengobatan_alternatif" value="alternatif">
                                                                <label class="form-check-label" for="pengobatan_alternatif">Pengobatan alternatif</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="pelayanan_kesehatan" value="pelayanan">
                                                                <label class="form-check-label" for="pelayanan_kesehatan">Pelayanan kesehatan</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="beli_obat_warung" value="warung">
                                                                <label class="form-check-label" for="beli_obat_warung">Beli obat di warung</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-3">Penggunaan alat bantu diri</div>
                                                        <div class="col-md-9">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="alat_bantu_tidak" value="tidak">
                                                                <label class="form-check-label" for="alat_bantu_tidak">Tidak</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="alat_bantu_dengar" value="dengar">
                                                                <label class="form-check-label" for="alat_bantu_dengar">Alat bantu dengar</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="kacamata" value="kacamata">
                                                                <label class="form-check-label" for="kacamata">Kacamata/kontak lensa</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="gigi_palsu" value="gigi">
                                                                <label class="form-check-label" for="gigi_palsu">Gigi palsu</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-3">Pasien tinggal di</div>
                                                        <div class="col-md-9">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="rumah_sendiri" value="sendiri">
                                                                <label class="form-check-label" for="rumah_sendiri">Rumah sendiri</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="rumah_ortu" value="ortu">
                                                                <label class="form-check-label" for="rumah_ortu">Rumah orang tua</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="kos" value="kos">
                                                                <label class="form-check-label" for="kos">Kos/kontrak</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="tinggal_lainnya" value="lainnya">
                                                                <label class="form-check-label" for="tinggal_lainnya">Lainnya</label>
                                                                <input type="text" class="form-control form-control-sm ml-2" style="width: 150px;">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-3">Bantuan yang dibutuhkan pasien</div>
                                                        <div class="col-md-9">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="bantuan_mandi" value="mandi">
                                                                <label class="form-check-label" for="bantuan_mandi">Mandi</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="bantuan_bab" value="bab">
                                                                <label class="form-check-label" for="bantuan_bab">BAB/BAK</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="bantuan_mandi" value="mandi">
                                                                <label class="form-check-label" for="bantuan_mandi">Mandi</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="bantuan_bab" value="bab">
                                                                <label class="form-check-label" for="bantuan_bab">BAB/BAK</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-secondary btn-kembali" onclick="navigateBack()">Kembali</button>
                                                        <button type="button" class="btn btn-primary btn-next" onclick="navigateNext()">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step H: EDUKASI -->
                                        <div id="step-h" class="step-content" style="display:none; opacity:0;">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">H. EDUKASI</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">Edukasi yang diberikan</div>
                                                        <div class="col-md-9">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="edukasi_diagnosa" value="diagnosa">
                                                                <label class="form-check-label" for="edukasi_diagnosa">Diagnosa</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="edukasi_obat" value="obat">
                                                                <label class="form-check-label" for="edukasi_obat">Obat-obatan</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="edukasi_diet" value="diet">
                                                                <label class="form-check-label" for="edukasi_diet">Diet</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="edukasi_aktivitas" value="aktivitas">
                                                                <label class="form-check-label" for="edukasi_aktivitas">Aktivitas</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-3">Hambatan edukasi</div>
                                                        <div class="col-md-9">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="hambatan_tidak_ada" value="tidak_ada">
                                                                <label class="form-check-label" for="hambatan_tidak_ada">Tidak ada</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="hambatan_bahasa" value="bahasa">
                                                                <label class="form-check-label" for="hambatan_bahasa">Bahasa</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="hambatan_pendengaran" value="pendengaran">
                                                                <label class="form-check-label" for="hambatan_pendengaran">Pendengaran</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="hambatan_kognitif" value="kognitif">
                                                                <label class="form-check-label" for="hambatan_kognitif">Kognitif</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-3">Metode edukasi</div>
                                                        <div class="col-md-9">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="metode_diskusi" value="diskusi">
                                                                <label class="form-check-label" for="metode_diskusi">Diskusi</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="metode_demonstrasi" value="demonstrasi">
                                                                <label class="form-check-label" for="metode_demonstrasi">Demonstrasi</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="metode_leaflet" value="leaflet">
                                                                <label class="form-check-label" for="metode_leaflet">Leaflet</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-3">Respon pasien/keluarga</div>
                                                        <div class="col-md-9">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="respon_mengerti" value="mengerti">
                                                                <label class="form-check-label" for="respon_mengerti">Mengerti</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="respon_perlu_diulang" value="perlu_diulang">
                                                                <label class="form-check-label" for="respon_perlu_diulang">Perlu diulang</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn btn-secondary btn-kembali" onclick="navigateBack()">Kembali</button>
                                                        <button type="button" class="btn btn-primary btn-next" onclick="navigateNext()">Selesai</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi tabs bootstrap (jika ada)
        $('#custom-tabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // Langkah-langkah step
        const steps = ['step-a', 'step-b', 'step-c', 'step-d', 'step-e', 'step-f', 'step-g', 'step-h'];

        // Inisialisasi: tampilkan hanya step pertama
        steps.forEach((step, index) => {
            const el = document.getElementById(step);
            if (el) {
                el.style.display = index === 0 ? 'block' : 'none';
                el.style.opacity = index === 0 ? '1' : '0';
            }
        });

        // Tambahkan CSS transisi
        const style = document.createElement('style');
        style.innerHTML = `
            .step-content {
                transition: opacity 0.3s ease-in-out;
            }
        `;
        document.head.appendChild(style);

        // Ubah teks tombol "Next" jadi "Selesai" di step terakhir
        const lastStepNextBtn = document.querySelector('#step-h .btn-next');
        if (lastStepNextBtn) {
            lastStepNextBtn.innerText = 'Selesai';
        }
    });

    // Dapatkan step aktif
    function getCurrentStep() {
        const steps = ['step-a', 'step-b', 'step-c', 'step-d', 'step-e', 'step-f', 'step-g', 'step-h'];
        for (let i = 0; i < steps.length; i++) {
            const step = document.getElementById(steps[i]);
            if (step && (step.style.display === 'block' || step.style.display === '')) {
                return steps[i];
            }
        }
        return 'step-a'; // default jika tidak ditemukan
    }

    // Navigasi antar step
    function navigateTo(fromStep, toStep) {
        const currentStepElement = document.getElementById(fromStep);
        if (currentStepElement) {
            currentStepElement.style.display = 'none';
            currentStepElement.style.opacity = '0';
        }

        const nextStepElement = document.getElementById(toStep);
        if (nextStepElement) {
            nextStepElement.style.display = 'block';
            setTimeout(() => {
                nextStepElement.style.opacity = '1';
            }, 50);
        }
    }

    function updateProgressBar(currentIndex) {
        const totalSteps = 8;
        const percentage = ((currentIndex + 1) / totalSteps) * 100;
        const progressBar = document.getElementById('progress-bar');

        if (progressBar) {
            progressBar.style.width = `${percentage}%`;
            progressBar.setAttribute('aria-valuenow', percentage.toFixed(1));
        }
    }


    // Navigasi ke step berikutnya
    function navigateNext() {
        const currentStep = getCurrentStep();
        const steps = ['step-a', 'step-b', 'step-c', 'step-d', 'step-e', 'step-f', 'step-g', 'step-h'];
        const currentIndex = steps.indexOf(currentStep);

        if (currentIndex < steps.length - 1) {
            const nextStep = steps[currentIndex + 1];
            navigateTo(currentStep, nextStep);
            updateProgressBar(currentIndex + 1); // ← Tambahkan ini

        } else if (currentIndex === steps.length - 1) {
            // Step terakhir
            alert('Proses selesai!');
            // document.getElementById('form-soap').submit(); // jika ingin langsung submit

        }
    }

    // Navigasi ke step sebelumnya
    function navigateBack() {
        const currentStep = getCurrentStep();
        const steps = ['step-a', 'step-b', 'step-c', 'step-d', 'step-e', 'step-f', 'step-g', 'step-h'];
        const currentIndex = steps.indexOf(currentStep);

        if (currentIndex > 0) {
            const prevStep = steps[currentIndex - 1];
            navigateTo(currentStep, prevStep);
            updateProgressBar(currentIndex - 1); // ← Tambahkan ini
        }
    }
</script>


@endsection
