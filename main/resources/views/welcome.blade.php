<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 @php
        use App\Models\WebSetting;
        $settings = WebSetting::first();
    @endphp
  <title>{{ $settings->nama ?? '' }}</title>

  <link rel="icon" sizes="180x180" type="image/x-icon" href="{{ asset('setting/' . ($settings->profile_image ?? 'default.jpg')) }}">
  <!-- Include styles and scripts as needed -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

  <style>
    html, body {
      height: 100%; /* Set full height */
      margin: 0; /* Remove default margin */
    }

    .wrapper {
      min-height: 100vh; /* Set minimum height for the wrapper */
    }

    .content-wrapper {
      min-height: calc(100vh - 56px); /* Adjust height for navbar and footer */
    }

    .content {
      padding: 20px; /* Add padding to content */
    }
  </style>
  <!-- CSS tambahan untuk penataan -->
    <style>
        .profil-perusahaan, .visi-misi, .tim-manajemen,  {
            padding: 15px;

            margin-bottom: 20px;
        }

        h3 {
            color: #333;
            margin-bottom: 15px;
        }

        h5 {
            margin-top: 15px;
        }

        p {
            line-height: 1.5;
        }
    </style>
<!-- CSS tambahan untuk penataan -->
<style>
    .profil-perusahaan {
        padding: 15px;
        margin-bottom: 20px;
    }

    .profil-perusahaan h5 {
        text-align: center; /* Menyelaraskan judul ke tengah */
    }

    .profil-perusahaan p {
        line-height: 1.5;
    }
</style>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
      <a href="#" class="navbar-brand">
        <img src="{{ asset('setting/' . ($settings->profile_image ?? 'default.jpg')) }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ $settings->nama ?? '' }}</span>
      </a>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        {{-- <li class="nav-item">
            <a href="{{ route('antrian.createtiket') }}" class="btn btn-primary btn-sm mr-2">Tiket</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('antrian') }}" class="btn btn-primary btn-sm mr-2">Display-Antrian</a>
        </li> --}}
        @guest
            <!-- Jika pengguna belum login -->
            <li class="nav-item">
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm mr-2">Login</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('register') }}" class="btn btn-success btn-sm">Registrasi</a>
            </li>
        @else
            <!-- Jika pengguna sudah login -->
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="btn btn-info btn-sm">Dashboard</a>
            </li>
        @endguest
    </ul>
    </div>
  </nav>
  <!-- /.navbar -->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <div class="mt-3 col-12">

                   <!-- Profil Perusahaan -->
                   <div class="profil-perusahaan p-4 bg-light rounded shadow">
                    <div class="row align-items-center">
                        <!-- Logo Perusahaan -->
                        <div class="col-md-6 text-center mb-4 mb-md-0">
                            <img src="{{ asset('setting/' . ($settings->profile_image ?? 'default.jpg')) }}" alt="Logo Perusahaan"
                                 class="img-fluid rounded-circle shadow-sm" style="width: 300px; height: auto;">
                        </div>
                        <!-- Informasi Perusahaan -->

                        <div class="col-md-6">
                            <h5 class="text-primary fw-bold mb-3">Aplikasi Sim Klinik Dolphin Healtech</h5>
                            <p class="text-muted fs-5">Aplikasi SIM Klinik Terintegrasi BPJS dan Satu Sehat dengan Teknologi Dolphi AI</p>
                            <hr class="my-3">
                            <h6 class="text-secondary fw-bold">Fitur Unggulan:</h6>
                            <div class="container mt-4">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="card" style="background-color: #cce5ff; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                                <div class="card-body text-center" style="padding: 20px;">
                                                    <span style="color: #004085; font-weight: bold;">Registrasi Pasien</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card" style="background-color: #cce5ff; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                                <div class="card-body text-center" style="padding: 20px;">
                                                    <span style="color: #004085; font-weight: bold;">Rekam Medis Elektronik</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card" style="background-color: #cce5ff; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                                <div class="card-body text-center" style="padding: 20px;">
                                                    <span style="color: #004085; font-weight: bold;">Penunjang Lab dan Radiologi</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="card" style="background-color: #cce5ff; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                                <div class="card-body text-center" style="padding: 20px;">
                                                    <span style="color: #004085; font-weight: bold;">Keuangan dan Kasir</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card" style="background-color: #cce5ff; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                                <div class="card-body text-center" style="padding: 20px;">
                                                    <span style="color: #004085; font-weight: bold;">Personalia</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card" style="background-color: #cce5ff; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                                <div class="card-body text-center" style="padding: 20px;">
                                                    <span style="color: #004085; font-weight: bold;">Gudang Obat / Alkes</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="card" style="background-color: #cce5ff; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                                <div class="card-body text-center" style="padding: 20px;">
                                                    <span style="color: #004085; font-weight: bold;">Apotik / Farmasi</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card" style="background-color: #cce5ff; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                                <div class="card-body text-center" style="padding: 20px;">
                                                    <span style="color: #004085; font-weight: bold;">Management</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card" style="background-color: #cce5ff; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                                <div class="card-body text-center" style="padding: 20px;">
                                                    <span style="color: #004085; font-weight: bold;">Bridging BPJS</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card" style="background-color: #cce5ff; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                                <div class="card-body text-center" style="padding: 20px;">
                                                    <span style="color: #004085; font-weight: bold;">Bridging Satusehat</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card" style="background-color: #cce5ff; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                                <div class="card-body text-center" style="padding: 20px;">
                                                    <span style="color: #004085; font-weight: bold;">Antrian (Dashboard, Panggil Pasien, Antrian Online)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card" style="background-color: #cce5ff; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s, box-shadow 0.3s;">
                                                <div class="card-body text-center" style="padding: 20px;">
                                                    <span style="color: #004085; font-weight: bold;">Dolphi Al Clinical Pathway</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> - Registrasi Pasien</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> - Rekam Medis Elektronik</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> - Penunjang Lab dan Radiologi</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> - Keuangan dan Kasir</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> - Personalia</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> - Gudang Obat / Alkes</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> - Apotik / Farmasi</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> - Management</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> - Bridging BPJS</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> - Bridging Satusehat</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> - Antrian (Dashboard, Panggil Pasien, Antrian Online)</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> - Dolphi AI Clinical Pathway</li>
                            </ul> --}}
                        </div>
                    </div>
                </div>
                    <!-- Visi dan Misi -->
                    <div class="visi-misi mt-4">
                        <div class="row text-center">
                            <div class="col-md-6">
                                <div class="card" style="height: 100%;">
                                    <div class="card-heder">
                                        <h5><strong>Misi:</strong></h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">Mampu meningkatkan pelayanan / services kepada pasien yang datang, sehingga penanganan medis dan non medis terhadap pasien dapat dilakukan lebih cepat dan profesional, hal ini mampu memberikan dampak yang besar terhadap Citra Rumah Sakit.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card" style="height: 100%;">
                                    <div class="card-heder">
                                        <h5><strong>Visi:</strong></h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">Memberikan dukungan di dalam melakukan kontrol logistik untuk menjadi lebih baik lagi, baik di bagian gudang, apotik, poli, laboratorium dan di bagian umum.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tim-manajemen mt-4 text-center">
                        <h3>Dolphin Our Team</h3>
                        <br>
                    </div>
                    <!-- Tim Manajemen Carousel -->
                    <div id="timManajemenCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
                        <div class="carousel-inner text-center">
                            <!-- Slide 1 -->
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                        <div class="card  border-0">
                                            <div class="card-body text-center">
                                                <img src="{{ asset('dist/img/Misbah.png') }}" alt="Nama Manajer 3" class="img-fluid rounded-circle mb-4" style="max-width: 120px; height: auto;">
                                                <h5>DR. MISBAHUL MUNIR</h5>
                                                <p>Komisaris Utama</p>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="card  border-0">
                                        <div class="card-body text-center">
                                            <img src="{{ asset('dist/img/Hemat.png') }}" alt="Nama Manajer 4" class="img-fluid rounded-circle mb-4" style="max-width: 120px; height: auto;">
                                            <h5>HEMAT DWI NURYANTO</h5>
                                            <p>Komisaris</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card  border-0">
                                        <div class="card-body text-center">
                                            <img src="{{ asset('dist/img/Mochamad.png') }}" alt="Nama Manajer 5" class="img-fluid rounded-circle mb-4" style="max-width: 120px; height: auto;">
                                            <h5>MOCHAMAD ZHUHRIANSYAH RAHMAN</h5>
                                            <p>Komisaris</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="card  border-0">
                                        <div class="card-body text-center">
                                            <img src="{{ asset('dist/img/Yudii.png') }}" alt="Nama Manajer 1" class="img-fluid rounded-circle mb-4" style="max-width: 120px; height: auto;">
                                            <h5>YUDI JUHANDIANSYAH</h5>
                                            <p>Direktur Utama</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card  border-0">
                                        <div class="card-body text-center">
                                            <img src="{{ asset('dist/img/Devaqi.png') }}" alt="Nama Manajer 2" class="img-fluid rounded-circle mb-4" style="max-width: 120px; height: auto;">
                                            <h5>DEVAQI ARUM SARI</h5>
                                            <p>Direktur</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                            <div class="col-md-4">
                                    <div class="card  border-0">
                                        <div class="card-body text-center">
                                            <img src="{{ asset('dist/img/Abdul.png') }}" alt="Nama Manajer 6" class="img-fluid rounded-circle mb-4" style="max-width: 120px; height: auto;">
                                            <h5>MUHAMMAD ABDUL FAZRI SUYUDI</h5>
                                            <p>Team</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card  border-0">
                                        <div class="card-body text-center">
                                            <img src="{{ asset('dist/img/Devaa.png') }}" alt="Nama Manajer 6" class="img-fluid rounded-circle mb-4" style="max-width: 120px; height: auto;">
                                            <h5>DEVA INDRAYANA RAHMAT</h5>
                                            <p>Team</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card  border-0">
                                        <div class="card-body text-center">
                                            <img src="{{ asset('dist/img/Daffa.png') }}" alt="Nama Manajer 6" class="img-fluid rounded-circle mb-4" style="max-width: 120px; height: auto;">
                                            <h5>DAFFA ABNA AZARA</h5>
                                            <p>Team</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
        <br>
    </section>

    <br>
    <!-- /.content -->
</div>


  <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer" style="padding: 20px; position: relative; bottom: 0; width: 100%;">
            <div class="container" style="max-width: 1200px; margin: auto;">
                <!-- Kontak dan Lokasi dalam satu baris -->
                <div class="d-flex justify-content-between align-items-start">
                    <div class="kontak-kami" style="flex: 1; margin-right: 20px;">
                        <h3>Kontak Kami</h3>
                        <p><strong>Email:</strong> info@dolphinhealthtech.co.id</p>
                        <p><strong>Telepon:</strong> 085220561527</p>
                        <p><strong>Alamat:</strong> Jl. Bungur No.9, Cipedes, Kec. Sukajadi, Kota Bandung, Jawa Barat 40162</p>
                    </div>

                    <div class="lokasi-kami" style="flex: 1; margin-left: 20px;">
                        <div style="width: 100%; height: 200px; border: 1px solid #dee2e6;">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.0704240580003!2d107.59200887604698!3d-6.882167193116727!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e68c7ea64fe3%3A0xaa2a71db41907961!2sJl.%20Bungur%20No.9%2C%20Cipedes%2C%20Kec.%20Sukajadi%2C%20Kota%20Bandung%2C%20Jawa%20Barat%2040162!5e0!3m2!1sen!2sid!4v1727770980358!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Teks Copyright di Tengah -->
                <div class="text-center mt-4">

                    <strong>Copyright &copy; 2024 – <?php echo date("Y"); ?>  <a href="https://dolphinhealthtech.co.id">dolphinhealthtech</a>.</strong>
                </div>
            </div>
        </footer>
</div>
<!-- ./wrapper -->

<!-- Include your scripts as needed -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Include Bootstrap JS for carousel functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
