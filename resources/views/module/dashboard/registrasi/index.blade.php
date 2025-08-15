@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h5 class="text-muted text-center">Dashboard Registrasi</h5>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="reg-hari-ini">0</h3>
                                <p>Terdaftar Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3 id="reg-bulan-ini">0</h3>
                                <p>Terdaftar Bulan Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="reg-menunggu">0</h3>
                                <p>Antrian Menunggu</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><span id="reg-dipanggil">0</span> / <span id="reg-pemeriksaan">0</span></h3>
                                <p>Dipanggil / Pemeriksaan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Jadwal Kunjungan Pasien Hari Ini</div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Pasien</th>
                                            <th>Poli</th>
                                            <th>Dokter</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reg-jadwal-hari-ini">
                                        <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Kunjungan per Poli (Bulan Ini)</div>
                            <div class="card-body">
                                <canvas id="reg-chart-poli" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Registrasi Terbaru Hari Ini</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>No. Rawat</th>
                                    <th>Pasien</th>
                                    <th>Poli</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody id="reg-terbaru">
                                <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            try {
                const base = `${window.location.origin}/api/registrasi-dashboard`;

                const [summaryRes, jadwalRes, poliRes, terbaruRes] = await Promise.all([
                    fetch(`${base}/summary`).then(r => r.json()),
                    fetch(`${base}/jadwal-hari-ini`).then(r => r.json()),
                    fetch(`${base}/kunjungan-per-poli`).then(r => r.json()),
                    fetch(`${base}/registrasi-terbaru`).then(r => r.json()),
                ]);

                // Kartu ringkasan
                document.getElementById('reg-hari-ini').textContent = summaryRes.hari_ini ?? 0;
                document.getElementById('reg-bulan-ini').textContent = summaryRes.bulan_ini ?? 0;
                document.getElementById('reg-menunggu').textContent = summaryRes.antrian_menunggu ?? 0;
                document.getElementById('reg-dipanggil').textContent = summaryRes.antrian_dipanggil ?? 0;
                document.getElementById('reg-pemeriksaan').textContent = summaryRes.antrian_pemeriksaan ?? 0;

                // Tabel jadwal hari ini
                const bodyJadwal = document.getElementById('reg-jadwal-hari-ini');
                const rowsJadwal = (jadwalRes.data || []).map(it => `
                    <tr>
                        <td>${it.waktu ?? '-'}</td>
                        <td>${it.pasien ?? '-'}</td>
                        <td>${it.poli ?? '-'}</td>
                        <td>${it.dokter ?? '-'}</td>
                    </tr>
                `).join('');
                bodyJadwal.innerHTML = rowsJadwal || '<tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>';

                // Chart Kunjungan per Poli
                const ctxPoli = document.getElementById('reg-chart-poli').getContext('2d');
                new Chart(ctxPoli, {
                    type: 'bar',
                    data: {
                        labels: poliRes.labels || [],
                        datasets: [{
                            label: 'Kunjungan',
                            data: poliRes.data || [],
                            backgroundColor: '#007bff'
                        }]
                    },
                    options: {
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                // Tabel registrasi terbaru
                const bodyTerbaru = document.getElementById('reg-terbaru');
                const rowsTerbaru = (terbaruRes.data || []).map(it => `
                    <tr>
                        <td>${it.no_rawat ?? '-'}</td>
                        <td>${it.pasien ?? '-'}</td>
                        <td>${it.poli ?? '-'}</td>
                        <td>${it.waktu ?? '-'}</td>
                    </tr>
                `).join('');
                bodyTerbaru.innerHTML = rowsTerbaru || '<tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>';
            } catch (e) {
                console.error(e);
            }
        });
    </script>
@endsection
