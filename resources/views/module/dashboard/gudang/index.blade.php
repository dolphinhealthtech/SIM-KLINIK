@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h5 class="text-muted text-center">Dashboard Gudang</h5>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="gdn-item">0</h3>
                                <p>Jumlah Item</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3 id="gdn-stok-klinik">0</h3>
                                <p>Total Stok Klinik</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="gdn-stok-utama">0</h3>
                                <p>Total Stok Utama</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="gdn-pending">0</h3>
                                <p>Permintaan Pending</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Pergerakan Stok (Hari Ini)</div>
                            <div class="card-body">
                                <canvas id="gdn-chart-move" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Top Low Stock</div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead><tr><th>Nama Obat/Alkes</th><th class="text-end">Qty</th></tr></thead>
                                    <tbody id="gdn-low-stock">
                                        <tr><td colspan="2" class="text-center text-muted">Belum ada data</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Permintaan Terbaru</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Kode Request</th><th>Tanggal</th><th>Klinik</th><th>Status</th></tr></thead>
                            <tbody id="gdn-permintaan">
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
                const base = `${window.location.origin}/api/gudang-dashboard`;
                const [sum, move, low, reqs] = await Promise.all([
                    fetch(`${base}/ringkasan`).then(r => r.json()),
                    fetch(`${base}/pergerakan-hari-ini`).then(r => r.json()),
                    fetch(`${base}/low-stock`).then(r => r.json()),
                    fetch(`${base}/permintaan-terbaru`).then(r => r.json()),
                ]);

                document.getElementById('gdn-item').textContent = sum.total_item ?? 0;
                document.getElementById('gdn-stok-klinik').textContent = moveIntl(sum.stok_klinik);
                document.getElementById('gdn-stok-utama').textContent = moveIntl(sum.stok_utama);
                document.getElementById('gdn-pending').textContent = sum.permintaan_pending ?? 0;

                const ctx = document.getElementById('gdn-chart-move').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: { labels: ['Masuk','Keluar','Penyesuaian Masuk','Penyesuaian Keluar'], datasets: [{ data: [move.masuk||0, move.keluar||0, move.penyesuaian_masuk||0, move.penyesuaian_keluar||0], backgroundColor: ['#28a745','#dc3545','#17a2b8','#ffc107'] }] },
                    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
                });

                const bodyLow = document.getElementById('gdn-low-stock');
                const rowsLow = (low.data || []).map(it => `
                    <tr><td>${it.nama_obat_alkes}</td><td class="text-end">${moveIntl(it.qty)}</td></tr>
                `).join('');
                bodyLow.innerHTML = rowsLow || '<tr><td colspan="2" class="text-center text-muted">Belum ada data</td></tr>';

                const bodyReq = document.getElementById('gdn-permintaan');
                const rowsReq = (reqs.data || []).map(it => `
                    <tr><td>${it.kode_request}</td><td>${it.tanggal}</td><td>${it.klinik}</td><td>${it.status}</td></tr>
                `).join('');
                bodyReq.innerHTML = rowsReq || '<tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>';

                function moveIntl(n){
                    return new Intl.NumberFormat('id-ID').format(n || 0);
                }
            } catch (e) {
                console.error(e);
            }
        });
    </script>
@endsection
