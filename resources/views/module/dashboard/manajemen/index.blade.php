@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h5 class="text-muted text-center">Dashboard Manajemen</h5>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="mnj-pendapatan">0</h3>
                                <p>Pendapatan Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="mnj-kunjungan">0</h3>
                                <p>Kunjungan Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3 id="mnj-pasien-baru">0</h3>
                                <p>Pasien Baru Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="mnj-resep">0</h3>
                                <p>Resep Menunggu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Pendapatan Bulanan (Tahun Ini)</div>
                            <div class="card-body">
                                <canvas id="mnj-chart-pendapatan" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Kunjungan per Poli (Bulan Ini)</div>
                            <div class="card-body">
                                <canvas id="mnj-chart-poli" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Top Dokter (30 Hari)</div>
                    <div class="card-body">
                        <canvas id="mnj-chart-dokter" height="120"></canvas>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            try {
                const base = `${window.location.origin}/api/manajemen-dashboard`;
                const [sum, pend, poli, topdok] = await Promise.all([
                    fetch(`${base}/ringkasan`).then(r => r.json()),
                    fetch(`${base}/pendapatan-bulanan`).then(r => r.json()),
                    fetch(`${base}/kunjungan-per-poli`).then(r => r.json()),
                    fetch(`${base}/top-dokter-30-hari`).then(r => r.json()),
                ]);

                const rupiah = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n || 0);
                document.getElementById('mnj-pendapatan').textContent = rupiah(sum.pendapatan_hari_ini || 0);
                document.getElementById('mnj-kunjungan').textContent = sum.kunjungan_hari_ini || 0;
                document.getElementById('mnj-pasien-baru').textContent = sum.pasien_baru_hari_ini || 0;
                document.getElementById('mnj-resep').textContent = sum.resep_menunggu || 0;

                const ctxPend = document.getElementById('mnj-chart-pendapatan').getContext('2d');
                new Chart(ctxPend, { type: 'line', data: { labels: pend.labels || [], datasets: [{ data: pend.data || [], borderColor: '#28a745', backgroundColor: 'rgba(40,167,69,0.15)', fill: true, tension: 0.3, pointRadius: 0 }] }, options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } } });

                const ctxPoli = document.getElementById('mnj-chart-poli').getContext('2d');
                new Chart(ctxPoli, { type: 'bar', data: { labels: poli.labels || [], datasets: [{ data: poli.data || [], backgroundColor: '#007bff' }] }, options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } } });

                const ctxDok = document.getElementById('mnj-chart-dokter').getContext('2d');
                new Chart(ctxDok, { type: 'bar', data: { labels: topdok.labels || [], datasets: [{ data: topdok.data || [], backgroundColor: '#6f42c1' }] }, options: { plugins: { legend: { display: false } }, indexAxis: 'y', scales: { x: { beginAtZero: true } } } });
            } catch (e) {
                console.error(e);
            }
        });
    </script>
@endsection
