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

                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
           

            <!-- Kode yang sudah ada untuk SOAP Steps -->
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
                            
                            <!-- Input tanggal dan jam yang simpel tapi bagus -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control custom-date-input" id="tanggal" name="tanggal">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jam">Jam</label>
                                        <div class="input-group">
                                            <input type="time" class="form-control custom-time-input" id="jam" name="jam">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                            $(document).ready(function() {
                                // Set current date and time as default
                                var now = new Date();
                                
                                // Format date as YYYY-MM-DD for the date input
                                var year = now.getFullYear();
                                var month = ("0" + (now.getMonth() + 1)).slice(-2);
                                var day = ("0" + now.getDate()).slice(-2);
                                var formattedDate = year + "-" + month + "-" + day;
                                
                                // Format time as HH:MM for the time input
                                var hours = ("0" + now.getHours()).slice(-2);
                                var minutes = ("0" + now.getMinutes()).slice(-2);
                                var formattedTime = hours + ":" + minutes;
                                
                                // Set the values
                                $('#tanggal').val(formattedDate);
                                $('#jam').val(formattedTime);
                            });
                            </script>

                            <style>
                            /* Custom styling for date and time inputs */
                            .custom-date-input, .custom-time-input {
                                border-radius: 4px;
                                border: 1px solid #ced4da;
                                padding: 0.375rem 0.75rem;
                                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
                            }

                            .custom-date-input:focus, .custom-time-input:focus {
                                border-color: #80bdff;
                                outline: 0;
                                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
                            }

                            /* Make the calendar and clock icons look better */
                            .input-group-text {
                                background-color: #f8f9fa;
                                border: 1px solid #ced4da;
                                border-radius: 0 4px 4px 0;
                            }

                            /* Improve the appearance of the native date/time pickers */
                            input[type="date"]::-webkit-calendar-picker-indicator,
                            input[type="time"]::-webkit-calendar-picker-indicator {
                                opacity: 0;
                                width: 100%;
                                height: 100%;
                                position: absolute;
                                top: 0;
                                left: 0;
                                cursor: pointer;
                            }

                            /* Make inputs look consistent across browsers */
                            input[type="date"], input[type="time"] {
                                position: relative;
                            }
                            </style>

                            <div class="form-group">
                                <label for="id_rawat">Id Rawat</label>
                                <input type="text" class="form-control bg-light" id="id_rawat" value="2025/04/10/001" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="nomor_rm">No. RM</label>
                                <input type="text" class="form-control bg-light" id="nomor_rm" value="000001" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="nama">Nama Pasien</label>
                                <input type="text" class="form-control bg-light" id="nama" value="RAHMADI IBRAHIM" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <input type="text" class="form-control bg-light" id="jenis_kelamin" value="Laki-laki" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="penjamin">Penjamin</label>
                                <input type="text" class="form-control bg-light" id="penjamin" value="umum" readonly>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="text" class="form-control bg-light" id="tanggal_lahir" value="1985-02-25" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="umur">Umur</label>
                                        <input type="text" class="form-control bg-light" id="umur" value="40 Tahun 2 Bulan" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Tabs -->
                <div class="col-md-8">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="mainTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" id="timeline-tab" data-toggle="pill" href="#timeline" role="tab" aria-controls="timeline" aria-selected="false">
                                        <i class="fas fa-stream mr-1"></i> Timeline
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="cppt-tab" data-toggle="pill" href="#cppt" role="tab" aria-controls="cppt" aria-selected="false">
                                        <i class="fas fa-notes-medical mr-1"></i> CPPT
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" id="laporan-tab" data-toggle="pill" href="#laporan" role="tab" aria-controls="laporan" aria-selected="true">
                                        <i class="fas fa-file-medical-alt mr-1"></i> Laporan
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                    <div class="tab-content" id="custom-tabs-content">
                    </div>
                </div>
                        <div class="card-body">
                            <div class="tab-content" id="mainTabContent">
                                <!-- Timeline Tab -->
                                <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                                    <div class="timeline-container">
                                        <!-- Timeline item 1 -->
                                        <div class="timeline-date">
                                            <span class="badge badge-danger">06 May 2025</span>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-icon bg-primary">
                                                <i class="fas fa-user-plus text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h4>Pasien Registrasi ke Rawat Jalan</h4>
                                                <p>Pasien dengan No. Rawat: 2025/05/06/001 telah terdaftar di Poli Umum.</p>
                                                <span class="timeline-time">08:15</span>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-item">
                                            <div class="timeline-icon bg-info">
                                                <i class="fas fa-stethoscope text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h4>Pemeriksaan Awal oleh Perawat</h4>
                                                <p>Tensi: 120/80 mmHg, Suhu: 36.5°C, Nadi: 80/menit, RR: 20/menit</p>
                                                <span class="timeline-time">08:30</span>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-item">
                                            <div class="timeline-icon bg-success">
                                                <i class="fas fa-user-md text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h4>Pemeriksaan oleh Dokter</h4>
                                                <p>Diagnosis: Hipertensi Grade I, ISPA</p>
                                                <span class="timeline-time">09:15</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Timeline item 2 -->
                                        <div class="timeline-date">
                                            <span class="badge badge-danger">17 May 2025</span>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-icon bg-primary">
                                                <i class="fas fa-user-plus text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h4>Kunjungan Ulang Rawat Jalan</h4>
                                                <p>Pasien dengan No. Rawat: 2025/05/17/042 terdaftar di Poli Umum.</p>
                                                <span class="timeline-time">10:30</span>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-item">
                                            <div class="timeline-icon bg-info">
                                                <i class="fas fa-stethoscope text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h4>Pemeriksaan Awal oleh Perawat</h4>
                                                <p>Tensi: 130/85 mmHg, Suhu: 36.8°C, Nadi: 76/menit, RR: 18/menit</p>
                                                <span class="timeline-time">10:45</span>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-item">
                                            <div class="timeline-icon bg-success">
                                                <i class="fas fa-user-md text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h4>Pemeriksaan oleh Dokter</h4>
                                                <p>Diagnosis: Hipertensi Grade I, Kontrol rutin</p>
                                                <span class="timeline-time">11:20</span>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-item">
                                            <div class="timeline-icon bg-warning">
                                                <i class="fas fa-pills text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h4>Pengambilan Obat di Farmasi</h4>
                                                <p>Amlodipine 5mg (30 tab), Paracetamol 500mg (10 tab)</p>
                                                <span class="timeline-time">11:45</span>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-item">
                                            <div class="timeline-icon bg-danger">
                                                <i class="fas fa-money-bill text-white"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h4>Pembayaran Pasien</h4>
                                                <p><span class="text-danger"><i class="fas fa-times-circle"></i> Belum Membayar</span><br>
                                                Silakan lakukan pembayaran.</p>
                                                <span class="timeline-time">14:55</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Timeline line -->
                                        <div class="timeline-line"></div>
                                    </div>
                                </div>
                                
                                <!-- CPPT Tab -->
                                <div class="tab-pane fade" id="cppt" role="tabpanel" aria-labelledby="cppt-tab">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Tensi(mmHg)</th>
                                                    <th>Suhu(°C)</th>
                                                    <th>Nadi(menit)</th>
                                                    <th>RR(menit)</th>
                                                    <th>Tinggi(cm)</th>
                                                    <th>Berat(Kg)</th>
                                                    <th>SPO2</th>
                                                    <th>L. Perut</th>
                                                    <th>Alergi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data CPPT akan ditampilkan di sini -->
                                                <tr>
                                                    <td>2023-05-06</td>
                                                    <td>120/80</td>
                                                    <td>36.5</td>
                                                    <td>80</td>
                                                    <td>20</td>
                                                    <td>170</td>
                                                    <td>65</td>
                                                    <td>98%</td>
                                                    <td>85</td>
                                                    <td>Tidak ada</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Laporan Tab -->
                                <div class="tab-pane fade show active" id="laporan" role="tabpanel" aria-labelledby="laporan-tab">
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
        </div>
    </section>
</div>
<!-- Add proper spacing before footer -->
<div style="margin-bottom: 60px;"></div>
</div>
<!-- End of content-wrapper -->

<style>
    /* Footer styling with minimal margin-top */
    .main-footer {
        position: relative;
        bottom: 0;
        width: 100%;
        background-color: #fff;
        border-top: 1px solid #dee2e6;
        margin-top: 5px; /* Reduced from 15px to 5px */
    }
    
    /* Remove fixed positioning and adjust spacing */
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    
    /* Make content take available space with minimal padding */
    .content-wrapper {
        flex: 1;
        padding-bottom: 5px; /* Reduced from 10px to 5px */
    }
    
    /* DataTables specific adjustments */
    .dataTables_scrollBody {
        min-height: 200px;
        max-height: 70vh;
    }
    
    /* Fix for DataTables FixedHeader plugin */
    .dtfh-floatingparenthead {
        top: 0 !important;
    }
    
    .dtfh-floatingparentfoot {
        bottom: 0 !important;
    }
    
    /* Timeline styling remains the same */
    .timeline-container {
        position: relative;
        padding: 20px 0;
    }
    
    .timeline-line {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 20px;
        width: 4px;
        background-color: #e9ecef;
        z-index: 0;
    }
    
    .timeline-date {
        margin-left: 45px;
        margin-bottom: 15px;
    }
    
    .timeline-date .badge {
        font-size: 14px;
        padding: 5px 10px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        margin-left: 45px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    }
    
    .timeline-icon {
        position: absolute;
        left: -45px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        text-align: center;
        line-height: 40px;
    }
    
    .timeline-content {
        padding: 15px;
    }
    
    .timeline-content h4 {
        margin-top: 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .timeline-time {
        position: absolute;
        top: 15px;
        right: 15px;
        color: #6c757d;
        font-size: 12px;
    }
</style>

<script>
    $(function() {
        // Pastikan tab berfungsi dengan benar
        $('#custom-tabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@endsection

<script>
    // Fungsi untuk mendapatkan step yang sedang aktif
    function getCurrentStep() {
        var steps = ['step-a', 'step-b', 'step-c', 'step-d', 'step-e', 'step-f', 'step-g', 'step-h'];
        for (var i = 0; i < steps.length; i++) {
            var step = document.getElementById(steps[i]);
            if (step && (step.style.display === 'block' || step.style.display === '')) {
                return steps[i];
            }
        }
        return 'step-a'; // Default ke step pertama jika tidak ada yang terlihat
    }
    
    // Fungsi untuk navigasi antar step
    function navigateTo(fromStep, toStep) {
        // Sembunyikan step saat ini
        var currentStepElement = document.getElementById(fromStep);
        if (currentStepElement) {
            currentStepElement.style.display = 'none';
            currentStepElement.style.opacity = '0';
        }
        
        // Tampilkan step tujuan
        var nextStepElement = document.getElementById(toStep);
        if (nextStepElement) {
            nextStepElement.style.display = 'block';
            // Gunakan setTimeout untuk memberikan efek transisi
            setTimeout(function() {
                nextStepElement.style.opacity = '1';
            }, 50);
        }
    }
    
    // Fungsi untuk navigasi ke step berikutnya
    function navigateNext() {
        var currentStep = getCurrentStep();
        var steps = ['step-a', 'step-b', 'step-c', 'step-d', 'step-e', 'step-f', 'step-g', 'step-h'];
        var currentIndex = steps.indexOf(currentStep);
        
        if (currentIndex < steps.length - 1) {
            var nextStep = steps[currentIndex + 1];
            navigateTo(currentStep, nextStep);
        } else if (currentIndex === steps.length - 1) {
            // Jika ini adalah step terakhir, lakukan aksi selesai
            // Misalnya submit form atau redirect
            alert('Proses selesai!');
            // Atau submit form jika ada
            // document.getElementById('form-soap').submit();
        }
    }
    
    // Fungsi untuk navigasi ke step sebelumnya
    function navigateBack() {
        var currentStep = getCurrentStep();
        var steps = ['step-a', 'step-b', 'step-c', 'step-d', 'step-e', 'step-f', 'step-g', 'step-h'];
        var currentIndex = steps.indexOf(currentStep);
        
        if (currentIndex > 0) {
            var prevStep = steps[currentIndex - 1];
            navigateTo(currentStep, prevStep);
        }
    }
    
    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Tampilkan hanya step pertama saat halaman dimuat
        var steps = ['step-a', 'step-b', 'step-c', 'step-d', 'step-e', 'step-f', 'step-g', 'step-h'];
        steps.forEach(function(step, index) {
            var el = document.getElementById(step);
            if (el) {
                if (index === 0) {
                    el.style.display = 'block';
                    el.style.opacity = '1';
                } else {
                    el.style.display = 'none';
                    el.style.opacity = '0';
                }
            }
        });
        
        // Tambahkan CSS untuk transisi
        var style = document.createElement('style');
        style.innerHTML = `
            .step-content {
                transition: opacity 0.3s ease-in-out;
            }
        `;
        document.head.appendChild(style);
        
        // Ubah teks tombol Next menjadi Selesai pada step terakhir
        var stepH = document.querySelector('#step-h .btn-next');
        if (stepH) {
            stepH.innerText = 'Selesai';
        }
    });
</script>

<!-- CSS untuk Timeline -->
<style>
    .timeline-container {
        position: relative;
        padding: 20px 0;
    }
    
    .timeline-line {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 20px;
        width: 4px;
        background-color: #e9ecef;
        z-index: 0;
    }
    
    .timeline-date {
        margin-left: 45px;
        margin-bottom: 15px;
    }
    
    .timeline-date .badge {
        font-size: 14px;
        padding: 5px 10px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        margin-left: 45px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    }
    
    .timeline-icon {
        position: absolute;
        left: -45px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        text-align: center;
        line-height: 40px;
    }
    
    .timeline-content {
        padding: 15px;
    }
    
    .timeline-content h4 {
        margin-top: 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .timeline-time {
        position: absolute;
        top: 15px;
        right: 15px;
        color: #6c757d;
        font-size: 12px;
    }
</style>

<script>
    $(function() {
        // Pastikan tab berfungsi dengan benar
        $('#custom-tabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
