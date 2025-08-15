@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h5 class="text-muted text-center">Dashboard Pasien</h5>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="psn-total">0</h3>
                                <p>Total Pasien</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="psn-baru-bulan-ini">0</h3>
                                <p>Pasien Baru Bulan Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3 id="psn-bpjs">0</h3>
                                <p>Jumlah BPJS</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="psn-non-bpjs">0</h3>
                                <p>Jumlah Non-BPJS</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Distribusi Jenis Kelamin</div>
                            <div class="card-body">
                                <canvas id="psn-chart-kelamin" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Pasien Baru per Bulan (Tahun Ini)</div>
                            <div class="card-body">
                                <canvas id="psn-chart-bulanan" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Pasien Terbaru</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>No. RM</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>JK</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="psn-terbaru">
                                <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
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
                const base = `${window.location.origin}/api/pasien-dashboard`;
                const [sum, kel, bul, list] = await Promise.all([
                    fetch(`${base}/ringkasan`).then(r => r.json()),
                    fetch(`${base}/distribusi-kelamin`).then(r => r.json()),
                    fetch(`${base}/pasien-baru-bulanan`).then(r => r.json()),
                    fetch(`${base}/terbaru`).then(r => r.json()),
                ]);

                document.getElementById('psn-total').textContent = sum.total ?? 0;
                document.getElementById('psn-baru-bulan-ini').textContent = sum.baru_bulan_ini ?? 0;
                document.getElementById('psn-bpjs').textContent = sum.bpjs ?? 0;
                document.getElementById('psn-non-bpjs').textContent = sum.non_bpjs ?? 0;

                const ctxKel = document.getElementById('psn-chart-kelamin').getContext('2d');
                new Chart(ctxKel, {
                    type: 'doughnut',
                    data: { labels: kel.labels || [], datasets: [{ data: kel.data || [], backgroundColor: ['#17a2b8','#ffc107','#6c757d'] }] },
                    options: { plugins: { legend: { position: 'bottom' } }, cutout: '60%' }
                });

                const ctxBul = document.getElementById('psn-chart-bulanan').getContext('2d');
                new Chart(ctxBul, {
                    type: 'bar',
                    data: { labels: bul.labels || [], datasets: [{ label: 'Pasien Baru', data: bul.data || [], backgroundColor: '#007bff' }] },
                    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
                });

                const body = document.getElementById('psn-terbaru');
                const rows = (list.data || []).map(it => `
                    <tr>
                        <td>${it.no_rm ?? '-'}</td>
                        <td>${it.nik ?? '-'}</td>
                        <td>${it.nama ?? '-'}</td>
                        <td>${it.kelamin ?? '-'}</td>
                        <td>${it.tanggal ?? '-'}</td>
                    </tr>
                `).join('');
                body.innerHTML = rows || '<tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>';
            } catch (e) {
                console.error(e);
            }
        });
    </script>
@endsection
