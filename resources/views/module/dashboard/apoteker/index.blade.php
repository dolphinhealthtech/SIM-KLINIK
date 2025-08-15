@extends('layouts.dashbord')

    @section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h5 class="text-muted text-center">Dashboard Apoteker</h5>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="apt-resep-menunggu">0</h3>
                                <p>Resep Menunggu</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="apt-penjualan-hari-ini">0</h3>
                                <p>Penjualan Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="apt-transaksi-hari-ini">0</h3>
                                <p>Transaksi Hari Ini</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Top Obat Terjual (Hari Ini)</div>
                            <div class="card-body">
                                <canvas id="apt-chart-top" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Penjualan Bulanan (Tahun Ini)</div>
                            <div class="card-body">
                                <canvas id="apt-chart-bulanan" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Resep Menunggu Diproses</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>No. Rawat</th>
                                    <th>No. RM</th>
                                    <th>Pasien</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody id="apt-resep-table">
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
                const base = `${window.location.origin}/api/apoteker-dashboard`;
                const [sum, top, bul, resep] = await Promise.all([
                    fetch(`${base}/ringkasan`).then(r => r.json()),
                    fetch(`${base}/top-obat-hari-ini`).then(r => r.json()),
                    fetch(`${base}/penjualan-bulanan`).then(r => r.json()),
                    fetch(`${base}/resep-menunggu`).then(r => r.json()),
                ]);

                const rupiah = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n || 0);
                document.getElementById('apt-resep-menunggu').textContent = sum.resep_menunggu ?? 0;
                document.getElementById('apt-penjualan-hari-ini').textContent = rupiah(sum.penjualan_hari_ini ?? 0);
                document.getElementById('apt-transaksi-hari-ini').textContent = sum.transaksi_hari_ini ?? 0;

                const ctxTop = document.getElementById('apt-chart-top').getContext('2d');
                new Chart(ctxTop, {
                    type: 'bar',
                    data: { labels: top.labels || [], datasets: [{ label: 'Qty', data: top.data || [], backgroundColor: '#17a2b8' }] },
                    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
                });

                const ctxBul = document.getElementById('apt-chart-bulanan').getContext('2d');
                new Chart(ctxBul, {
                    type: 'line',
                    data: { labels: bul.labels || [], datasets: [{ label: 'Penjualan', data: bul.data || [], borderColor: '#28a745', backgroundColor: 'rgba(40,167,69,0.15)', fill: true, tension: 0.3, pointRadius: 0 }] },
                    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
                });

                const body = document.getElementById('apt-resep-table');
                const rows = (resep.data || []).map(it => `
                    <tr>
                        <td>${it.no_rawat ?? '-'}</td>
                        <td>${it.no_rm ?? '-'}</td>
                        <td>${it.pasien ?? '-'}</td>
                        <td>${it.waktu ?? '-'}</td>
                    </tr>
                `).join('');
                body.innerHTML = rows || '<tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>';
            } catch (e) {
                console.error(e);
            }
        });
    </script>
@endsection
