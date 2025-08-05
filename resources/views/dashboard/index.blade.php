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
                                <a href="#" class="text-white" data-toggle="modal" data-target="#modalPendapatan">
                                    Rincian <i class="fas fa-arrow-circle-right float-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                <!-- HTML -->
                <div class="row">
                <!-- Kunjungan Harian -->
                <div class="col-md-6">
                    <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">Grafik Kunjungan per Hari</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="kunjunganChart" height="100"></canvas>
                    </div>
                    </div>
                </div>

                <!-- Pendapatan Bulanan -->
                <div class="col-md-6">
                    <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">Grafik Pendapatan Bulanan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="pendapatanChart" height="100"></canvas>
                    </div>
                    </div>
                </div>

                <!-- Kunjungan Poli Bulanan -->
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header bg-info">
                        <h5 class="card-title">Report Kunjungan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <!-- Grafik -->
                        <div class="col-md-8">
                            <p class="text-center">
                                @php
                                    use Carbon\Carbon;

                                    $start = Carbon::now()->startOfMonth();
                                    $end = Carbon::now()->endOfMonth();
                                @endphp

                                <strong>Kunjungan: {{ $start->translatedFormat('j F') }} - {{ $end->translatedFormat('j F Y') }}</strong>
                            </p>
                            <div class="chart" style="height: 180px; position: relative;">
                            <canvas id="poliChart"></canvas>
                            </div>
                        </div>
                        <!-- Progress Bar -->
                        <div class="col-md-4">
                            <p class="text-center"><strong>Completion</strong></p>
                            <div id="progressPoli"></div>
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
                                <h4 class="text-success font-weight-bold">
                                    Rp {{ number_format($totalJasaGabungan, 0, ',', '.') }}
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
                                <h4 class="text-info font-weight-bold">
                                    Rp {{ number_format($totalObat, 0, ',', '.') }}
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
                                <h1 class="text-warning font-weight-bold">
                                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                </h1>
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
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
                                    <span class="text-success">{{ $persenJasa }}%</span>
                                </div>
                                <div class="col-md-3 col-6 mb-2">
                                    <strong>Obat</strong><br>
                                    <span class="text-info">{{ $persenObat }}%</span>
                                </div>
                                <div class="col-md-6 col-12">
                                    <strong>Total Kategori</strong><br>
                                    <span class="text-warning">{{ $kategoriAktif }} kategori</span>
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
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Simulasi data kosong
    const dataKunjunganHarian = { labels: [], data: [] };
    const dataPendapatanBulanan = { labels: [], data: [] };
    const dataKunjunganPoli = { labels: [], data: [] };

    renderChartKunjunganHarian(dataKunjunganHarian);
    renderChartPendapatanBulanan(dataPendapatanBulanan);
    renderChartKunjunganPerPoli(dataKunjunganPoli);

    function renderChartKunjunganHarian(result) {
        const ctx = document.getElementById('kunjunganChart').getContext('2d');
        const labels = result.labels.length ? result.labels : ['Tidak ada data'];
        const dataPoints = result.data.length ? result.data : [0];

        new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
            label: 'Jumlah Kunjungan',
            data: dataPoints,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
        });
    }

    function renderChartPendapatanBulanan(data) {
        const ctx = document.getElementById('pendapatanChart').getContext('2d');
        const labels = data.labels.length ? data.labels : ['Tidak ada data'];
        const totals = data.data.length ? data.data : [0];

        new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
            label: 'Pendapatan (Rp)',
            data: totals,
            backgroundColor: 'rgba(255, 206, 86, 0.3)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 2,
            fill: true,
            tension: 0.3,
            pointRadius: 0
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
        });
    }

    function renderChartKunjunganPerPoli(result) {
        const ctx = document.getElementById('poliChart').getContext('2d');
        const labels = result.labels.length ? result.labels : ['Tidak ada data'];
        const dataPoints = result.data.length ? result.data : [0];
        const bgColors = result.labels.length
        ? ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796']
        : ['rgba(200,200,200,0.3)'];

        new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
            data: dataPoints,
            backgroundColor: bgColors,
            borderWidth: 1
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } },
            cutout: '60%'
        }
        });

        const progressContainer = document.getElementById('progressPoli');
        progressContainer.innerHTML = '';

        if (result.labels.length === 0 || result.data.length === 0) {
        progressContainer.innerHTML = `<p class="text-muted text-center">Tidak ada data kunjungan</p>`;
        return;
        }

        const total = result.data.reduce((a, b) => a + b, 0);
        result.labels.forEach((label, i) => {
        const percentage = ((result.data[i] / total) * 100).toFixed(1);
        progressContainer.innerHTML += `
            <div class="mb-2">
            <div class="d-flex justify-content-between">
                <small><strong>${label}</strong></small>
                <small>${percentage}%</small>
            </div>
            <div class="progress">
                <div class="progress-bar" style="width: ${percentage}%;">${percentage}%</div>
            </div>
            </div>
        `;
        });
    }
    });
</script>

@endsection
