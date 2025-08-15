@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h5 class="text-muted text-center">Dashboard Personalia</h5>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="psn-total-staff">0</h3>
                                <p>Total Staff</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3 id="psn-staff-bulan-ini">0</h3>
                                <p>Staff Bergabung Bulan Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="psn-belum-verif">0</h3>
                                <p>Belum Verifikasi</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="psn-sudah-verif">0</h3>
                                <p>Sudah Verifikasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Distribusi Status Pegawai</div>
                            <div class="card-body">
                                <canvas id="psn-chart-status" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Rekrutmen Staff per Bulan (Tahun Ini)</div>
                            <div class="card-body">
                                <canvas id="psn-chart-bulanan" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Staff Terbaru</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>Status</th>
                                    <th>Tgl Masuk</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody id="psn-staff-terbaru">
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
                const base = `${window.location.origin}/api/personalia-dashboard`;
                const [sum, dist, bul, staf] = await Promise.all([
                    fetch(`${base}/ringkasan`).then(r => r.json()),
                    fetch(`${base}/distribusi-status`).then(r => r.json()),
                    fetch(`${base}/rekrut-bulanan`).then(r => r.json()),
                    fetch(`${base}/staf-terbaru`).then(r => r.json()),
                ]);

                document.getElementById('psn-total-staff').textContent = sum.total_staff ?? 0;
                document.getElementById('psn-staff-bulan-ini').textContent = sum.staff_bulan_ini ?? 0;
                document.getElementById('psn-belum-verif').textContent = sum.belum_verifikasi ?? 0;
                document.getElementById('psn-sudah-verif').textContent = sum.sudah_verifikasi ?? 0;

                const ctxStatus = document.getElementById('psn-chart-status').getContext('2d');
                new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: dist.labels || [],
                        datasets: [{ data: dist.data || [], backgroundColor: ['#17a2b8','#ffc107','#28a745','#007bff','#6c757d'] }]
                    },
                    options: { plugins: { legend: { position: 'bottom' } }, cutout: '60%' }
                });

                const ctxBul = document.getElementById('psn-chart-bulanan').getContext('2d');
                new Chart(ctxBul, {
                    type: 'bar',
                    data: { labels: bul.labels || [], datasets: [{ label: 'Staff', data: bul.data || [], backgroundColor: '#007bff' }] },
                    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
                });

                const bodyStaf = document.getElementById('psn-staff-terbaru');
                const rows = (staf.data || []).map(it => `
                    <tr>
                        <td>${it.nama ?? '-'}</td>
                        <td>${it.nik ?? '-'}</td>
                        <td>${it.status ?? '-'}</td>
                        <td>${it.tgl_masuk ?? '-'}</td>
                        <td>${it.dibuat ?? '-'}</td>
                    </tr>
                `).join('');
                bodyStaf.innerHTML = rows || '<tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>';
            } catch (e) {
                console.error(e);
            }
        });
    </script>
@endsection
