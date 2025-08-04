@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        {{-- <h1 class="m-0">Dashboard</h1> --}}
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Reusable Card Template -->
                    <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                        <div class="card text-white bg-success shadow">
                            <div class="card-body position-relative">
                                <div class="text-center">
                                    <h3 class="font-weight-bold">{{ $datadokter }}</h3>
                                    <p class="mb-0">Dokter Aktif</p>
                                </div>
                                <div class="position-absolute" style="top: 15px; right: 15px;">
                                    <i class="fas fa-user-md fa-2x opacity-50"></i>
                                </div>
                            </div>
                            <div class="card-footer text-white clearfix small z-1"data-toggle="modal" data-target="#dokterAktifModal" >
                                <a href="#" class="text-white">
                                    Lihat Dokter <i class="fas fa-arrow-circle-right float-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                        <div class="card text-white bg-info shadow">
                            <div class="card-body position-relative">
                                <div class="text-center">
                                    <h3 class="font-weight-bold">{{ $datapasien }}</h3>
                                    <p class="mb-0">Jumlah Pasien Terdaftar</p>
                                </div>
                                <div class="position-absolute" style="top: 15px; right: 15px;">
                                    <i class="fas fa-users fa-2x opacity-50"></i>
                                </div>
                            </div>
                            <div class="card-footer text-white clearfix small z-1" style="visibility: hidden;">...</div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                        <div class="card text-white bg-warning shadow">
                            <div class="card-body position-relative">
                                <div class="text-center">
                                    <h3 class="font-weight-bold">{{ $datakunjungan }}</h3>
                                    <p class="mb-0">Kunjungan Hari Ini</p>
                                </div>
                                <div class="position-absolute" style="top: 15px; right: 15px;">
                                    <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                                </div>
                            </div>
                            <div class="card-footer text-white clearfix small z-1" style="visibility: hidden;">...</div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
                        <div class="card text-white bg-danger shadow">
                            <div class="card-body position-relative">
                                <div class="text-center">
                                    <h3 class="font-weight-bold" id="pendapatan">Rp0</h3>
                                    <p class="mb-0">Pendapatan Hari Ini</p>
                                </div>
                                <div class="position-absolute" style="top: 15px; right: 15px;">
                                    <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                                </div>
                            </div>
                            <div class="card-footer text-white clearfix small z-1">
                                <a href="#" class="text-white" data-toggle="modal" data-target="#modalPendapatan" onclick="openModalPendapatan()">
                                    Rincian <i class="fas fa-arrow-circle-right float-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Modal Dokter Aktif Hari Ini -->
                <div class="modal fade" id="dokterAktifModal" tabindex="-1" role="dialog" aria-labelledby="dokterAktifModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="dokterAktifModalLabel">Dokter Aktif Hari Ini</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">Nama Dokter</th>
                                    <th class="text-center">Poli / Spesialisasi</th>
                                    <th class="text-center">Jam Mulai</th>
                                    <th class="text-center">Jam Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dokterHariIni as $dokter)
                                    <tr>
                                        <td class="text-center">{{ $dokter->nama }}</td>
                                        <td class="text-center">{{ $dokter->spesialisasi }}</td>
                                        <td class="text-center">{{ $dokter->start }}</td>
                                        <td class="text-center">{{ $dokter->end }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada dokter aktif hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                    </div>
                </div>
                </div>

                <!-- Modal rincian pendapatan harian -->
                <div class="modal fade" id="modalPendapatan" tabindex="-1" role="dialog" aria-labelledby="modalPendapatanLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h4 class="modal-title text-white" id="modalPendapatanLabel">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Rincian Pendapatan Hari Ini
                                </h4>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <!-- Row untuk 2 kategori -->
                                <div class="row">
                                    <!-- KIRI: Penghasilan Jasa -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-success h-100">
                                            <div class="card-header bg-success text-white text-center">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-hand-holding-medical mr-2"></i>
                                                    Pendapatan Jasa dan Tindakan
                                                </h6>
                                            </div>
                                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                                <h4 class="text-success font-weight-bold" id="pendapatan-jasa">
                                                    <i class="fas fa-spinner fa-spin"></i> Loading...
                                                </h4>
                                                <small class="text-muted">Pendapatan dari Tindakan Medis, Administrasi & Materai</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- KANAN: Penghasilan Obat -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-info h-100">
                                            <div class="card-header bg-info text-white text-center">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-pills mr-2"></i>
                                                    Pendapatan Obat
                                                </h6>
                                            </div>
                                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                                <h4 class="text-info font-weight-bold" id="pendapatan-obat">
                                                    <i class="fas fa-spinner fa-spin"></i> Loading...
                                                </h4>
                                                <small class="text-muted">Pendapatan dari Penjualan Obat</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <hr class="my-4">

                                <!-- Total Pendapatan -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning text-center">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-chart-line mr-2"></i>
                                                    Total Pendapatan Hari Ini
                                                </h5>
                                            </div>
                                            <div class="card-body text-center">
                                                <h1 class="text-warning font-weight-bold" id="total-pendapatan">
                                                    <i class="fas fa-spinner fa-spin"></i> Loading...
                                                </h1>
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt mr-1"></i>
                                                    {{ date('d F Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Breakdown -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-light">
                                            <div class="row text-center">
                                                <div class="col-md-3 col-6 mb-2">
                                                    <strong>Jasa dan Tindakan</strong><br>
                                                    <span class="text-success" id="persentase-jasa">0%</span>
                                                </div>
                                                <div class="col-md-3 col-6 mb-2">
                                                    <strong>Obat</strong><br>
                                                    <span class="text-info" id="persentase-obat">0%</span>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <strong>Total Kategori</strong><br>
                                                    <span class="text-warning" id="jumlah-transaksi">0 kategori</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fas fa-times mr-1"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" onclick="openModalPendapatan()">
                                    <i class="fas fa-sync-alt mr-1"></i> Refresh Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Grafik Kunjungan per Hari</h3>
                            <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="kunjunganChart" height="100"></canvas>
                        </div>
                        </div>
                    </div>

                    <!-- Card Grafik Pendapatan -->
                    <div class="col-md-6">
                        <div class="card">
                        <div class="card-header bg-success">
                            <h3 class="card-title">Grafik Pendapatan Bulanan</h3>
                            <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="pendapatanChart" height="100"></canvas>
                        </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-header bg-info">
                          <h5 class="card-title">Report Kunjungan</h5>
                          <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                          </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                          <div class="row">
                            <!-- Chart -->
                            <div class="col-md-8">
                              <p class="text-center">
                                @php
                                  use Carbon\Carbon;
                                  $tanggalAwal = Carbon::now()->startOfMonth();
                                  $tanggalAkhir = Carbon::now()->endOfMonth();
                                @endphp
                                <strong>Kunjungan: {{ $tanggalAwal->format('j M') }} - {{ $tanggalAkhir->format('j M Y') }}</strong>
                              </p>
                              <div class="chart position-relative" style="height: 180px;">
                                <canvas id="salesChart"></canvas>
                              </div>
                            </div>

                            <!-- Progress Completion -->
                            <div class="col-md-4">
                            <p class="text-center"><strong>Completion</strong></p>

                            <div id="progressPoliContainer">
                                <!-- Data progress per poli akan dimasukkan lewat JavaScript -->
                            </div>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- /.content -->
    </div>

<!-- SCRIPT SECTION - DIPERBAIKI -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1. Fetch total pendapatan harian
    fetch('/api/pendapatan-hari-ini')
        .then(response => response.json())
        .then(data => {
            let total = Math.round(data.pendapatan * 1000);
            let formatted = new Intl.NumberFormat('id-ID', {
                maximumFractionDigits: 0
            }).format(total);
            document.getElementById('pendapatan').innerText = 'Rp' + formatted;
        })
        .catch(error => {
            console.error('Error fetching pendapatan harian:', error);
            document.getElementById('pendapatan').innerText = 'Error';
        });

    // 2. Chart Kunjungan Harian
    fetch('/api/kunjungan-harian')
        .then(response => response.json())
        .then(result => {
            const ctx = document.getElementById('kunjunganChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: result.labels,
                    datasets: [{
                        label: 'Jumlah Kunjungan',
                        data: result.data,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching kunjungan harian:', error));

    // 3. Chart Pendapatan Bulanan
    fetch('/api/pendapatan-bulanan')
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById('pendapatanChart').getContext('2d');
            const totals = data.totals;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: totals,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            max: 100000000,
                            ticks: {
                                stepSize: 10000000,
                                precision: 0,
                                callback: v => 'Rp' + v.toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });
        })
        .catch(err => console.error('Error fetching pendapatan:', err));

    // 4. Chart Kunjungan per Poli
    fetch('/api/kunjungan-per-poli')
        .then(response => response.json())
        .then(result => {
            const ctx = document.getElementById('salesChart').getContext('2d');

            // Render Chart
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: result.labels,
                    datasets: [{
                        label: 'Jumlah Kunjungan',
                        data: result.data,
                        fill: true,
                        backgroundColor: 'rgba(60,141,188,0.2)',
                        borderColor: 'rgba(60,141,188,1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });

            // Render Progress Bar
            const progressContainer = document.getElementById('progressPoliContainer');
            progressContainer.innerHTML = '';

            result.labels.forEach((label, index) => {
                const jumlah = result.data[index];
                const target = Math.ceil(jumlah / 100) * 100;
                const persen = Math.round((jumlah / target) * 100);

                const warnaList = ['bg-primary', 'bg-danger', 'bg-success', 'bg-warning', 'bg-info', 'bg-secondary'];
                const warna = warnaList[index % warnaList.length];

                const el = `
                    <div class="progress-group">
                    ${label}
                    <span class="float-right"><b>${jumlah}</b>/${target}</span>
                    <div class="progress progress-sm">
                        <div class="progress-bar ${warna}" style="width: ${persen}%"></div>
                    </div>
                    </div>
                `;
                progressContainer.innerHTML += el;
            });

        })
        .catch(error => console.error("Gagal mengambil data kunjungan-per-poli:", error));

});

// FUNGSI MODAL PENDAPATAN - UPDATED WITH 2 CATEGORIES (jasa + obat)
function openModalPendapatan() {
    // Reset loading state dengan spinner
    document.getElementById('pendapatan-jasa').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    document.getElementById('pendapatan-obat').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    document.getElementById('total-pendapatan').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    document.getElementById('persentase-jasa').innerText = '0%';
    document.getElementById('persentase-obat').innerText = '0%';
    document.getElementById('jumlah-transaksi').innerText = '0 kategori';

    // Fetch data detail dari API
    fetch('/api/pendapatan-detail')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('API Response:', data); // Debug log

            if (data.status === 'success') {
                // Ambil data dari response
                let jasa = parseFloat(data.jasa) || 0;
                let obat = parseFloat(data.obat) || 0;
                let total = jasa + obat;

                console.log('Data mentah:', {
                    jasa: data.jasa,
                    obat: data.obat
                });

                // Format currency
                let format = new Intl.NumberFormat('id-ID', {
                    maximumFractionDigits: 0
                });

                // Update tampilan
                document.getElementById('pendapatan-jasa').innerHTML = 'Rp ' + format.format(jasa);
                document.getElementById('pendapatan-obat').innerHTML = 'Rp ' + format.format(obat);
                document.getElementById('total-pendapatan').innerHTML = 'Rp ' + format.format(total);

                // Hitung persentase
                if (total > 0) {
                    let persenJasa = Math.round((jasa / total) * 100);
                    let persenObat = Math.round((obat / total) * 100);

                    document.getElementById('persentase-jasa').innerText = persenJasa + '%';
                    document.getElementById('persentase-obat').innerText = persenObat + '%';
                } else {
                    document.getElementById('persentase-jasa').innerText = '0%';
                    document.getElementById('persentase-obat').innerText = '0%';
                }

                // Update jumlah kategori aktif
                let kategoriAktif = 0;
                if (jasa > 0) kategoriAktif++;
                if (obat > 0) kategoriAktif++;

                document.getElementById('jumlah-transaksi').innerText = kategoriAktif + ' kategori';

            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error fetching pendapatan detail:', error);

            // Tampilkan error di modal
            document.getElementById('pendapatan-jasa').innerHTML = '<span class="text-danger">Error</span>';
            document.getElementById('pendapatan-obat').innerHTML = '<span class="text-danger">Error</span>';
            document.getElementById('total-pendapatan').innerHTML = '<span class="text-danger">Error Loading Data</span>';

            if (typeof showAlert === 'function') {
                showAlert('error', 'Gagal memuat data pendapatan: ' + error.message);
            } else {
                alert('Gagal memuat data pendapatan: ' + error.message);
            }
        });
}

</script>

@endsection
