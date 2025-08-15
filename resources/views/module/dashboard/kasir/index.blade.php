@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h5 class="text-muted text-center">Dashboard Kasir</h5>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="ksr-total-hari-ini">0</h3>
                                <p>Pendapatan Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="ksr-transaksi-hari-ini">0</h3>
                                <p>Transaksi Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3 id="ksr-rata-rata">0</h3>
                                <p>Rata-rata per Transaksi</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><span id="ksr-jasa">0</span> / <span id="ksr-obat">0</span></h3>
                                <p>Jasa / Obat (Hari Ini)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Komposisi Pendapatan (Hari Ini)</div>
                            <div class="card-body">
                                <canvas id="ksr-chart-komposisi" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Pendapatan Bulanan (Tahun Ini)</div>
                            <div class="card-body">
                                <canvas id="ksr-chart-bulanan" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Transaksi Terbaru Hari Ini</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>No. Rawat</th>
                                    <th>Pasien</th>
                                    <th class="text-end">Total</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody id="ksr-transaksi-terbaru">
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
                const base = `${window.location.origin}/api/kasir-dashboard`;

                const [summaryRes, compRes, monthlyRes, latestRes] = await Promise.all([
                    fetch(`${base}/summary`).then(r => r.json()),
                    fetch(`${base}/komposisi-hari-ini`).then(r => r.json()),
                    fetch(`${base}/bulanan`).then(r => r.json()),
                    fetch(`${base}/transaksi-terbaru`).then(r => r.json()),
                ]);

                // Kartu
                const rupiah = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n || 0);
                document.getElementById('ksr-total-hari-ini').textContent = rupiah(summaryRes.pendapatan_hari_ini);
                document.getElementById('ksr-transaksi-hari-ini').textContent = summaryRes.transaksi_hari_ini ?? 0;
                document.getElementById('ksr-rata-rata').textContent = rupiah(summaryRes.rata_rata);
                document.getElementById('ksr-jasa').textContent = rupiah(summaryRes.jasa_hari_ini);
                document.getElementById('ksr-obat').textContent = rupiah(summaryRes.obat_hari_ini);

                // Tabel transaksi terbaru
                const bodyTbl = document.getElementById('ksr-transaksi-terbaru');
                const rows = (latestRes.data || []).map(it => `
                    <tr>
                        <td>${it.no_rawat ?? '-'}</td>
                        <td>${it.pasien ?? '-'}</td>
                        <td class="text-end">${rupiah(it.total ?? 0)}</td>
                        <td>${it.waktu ?? '-'}</td>
                    </tr>
                `).join('');
                bodyTbl.innerHTML = rows || '<tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>';

                // Chart Komposisi (Doughnut)
                const ctxKomp = document.getElementById('ksr-chart-komposisi').getContext('2d');
                new Chart(ctxKomp, {
                    type: 'doughnut',
                    data: {
                        labels: ['Jasa', 'Obat'],
                        datasets: [{
                            data: [compRes.jasa ?? 0, compRes.obat ?? 0],
                            backgroundColor: ['#17a2b8', '#ffc107']
                        }]
                    },
                    options: {
                        plugins: { legend: { position: 'bottom' } },
                        cutout: '60%'
                    }
                });

                // Chart Bulanan (Bar)
                const ctxBulanan = document.getElementById('ksr-chart-bulanan').getContext('2d');
                new Chart(ctxBulanan, {
                    type: 'bar',
                    data: {
                        labels: monthlyRes.labels || [],
                        datasets: [{
                            label: 'Pendapatan',
                            data: monthlyRes.data || [],
                            backgroundColor: '#28a745'
                        }]
                    },
                    options: {
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            } catch (e) {
                console.error(e);
            }
        });
    </script>
@endsection
