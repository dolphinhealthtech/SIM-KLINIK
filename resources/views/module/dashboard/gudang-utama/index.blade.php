@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h5 class="text-muted text-center">Dashboard Gudang Utama</h5>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="gdu-item">0</h3>
                                <p>Jumlah Item Utama</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="gdu-stok-utama">0</h3>
                                <p>Total Stok Utama</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="gdu-request-hari-ini">0</h3>
                                <p>Request Klinik (Hari Ini)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Pergerakan Stok Utama (Hari Ini)</div>
                            <div class="card-body">
                                <canvas id="gdu-chart-move" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Top Low Stock (Utama)</div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead><tr><th>Nama Obat/Alkes</th><th class="text-end">Qty</th></tr></thead>
                                    <tbody id="gdu-low-stock">
                                        <tr><td colspan="2" class="text-center text-muted">Belum ada data</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Pengiriman Terbaru ke Klinik</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Kode Request</th><th>Tanggal</th><th>Klinik</th><th>Kode Obat</th><th class="text-end">Qty</th></tr></thead>
                            <tbody id="gdu-pengiriman">
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
                const base = `${window.location.origin}/api/gudang-utama-dashboard`;
                const [sum, move, low, ship] = await Promise.all([
                    fetch(`${base}/ringkasan`).then(r => r.json()),
                    fetch(`${base}/pergerakan-hari-ini`).then(r => r.json()),
                    fetch(`${base}/low-stock`).then(r => r.json()),
                    fetch(`${base}/pengiriman-terbaru`).then(r => r.json()),
                ]);

                document.getElementById('gdu-item').textContent = sum.total_item ?? 0;
                document.getElementById('gdu-stok-utama').textContent = intl(sum.stok_utama);
                document.getElementById('gdu-request-hari-ini').textContent = sum.request_hari_ini ?? 0;

                const ctx = document.getElementById('gdu-chart-move').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: { labels: ['Masuk','Keluar','Penyesuaian Masuk','Penyesuaian Keluar'], datasets: [{ data: [move.masuk||0, move.keluar||0, move.penyesuaian_masuk||0, move.penyesuaian_keluar||0], backgroundColor: ['#28a745','#dc3545','#17a2b8','#ffc107'] }] },
                    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
                });

                const bodyLow = document.getElementById('gdu-low-stock');
                const rowsLow = (low.data || []).map(it => `
                    <tr><td>${it.nama_obat_alkes}</td><td class="text-end">${intl(it.qty)}</td></tr>
                `).join('');
                bodyLow.innerHTML = rowsLow || '<tr><td colspan="2" class="text-center text-muted">Belum ada data</td></tr>';

                const bodyShip = document.getElementById('gdu-pengiriman');
                const rowsShip = (ship.data || []).map(it => `
                    <tr><td>${it.kode_request}</td><td>${it.tanggal}</td><td>${it.klinik}</td><td>${it.kode_obat}</td><td class="text-end">${intl(it.qty)}</td></tr>
                `).join('');
                bodyShip.innerHTML = rowsShip || '<tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>';

                function intl(n){ return new Intl.NumberFormat('id-ID').format(n || 0); }
            } catch (e) {
                console.error(e);
            }
        });
    </script>
@endsection
