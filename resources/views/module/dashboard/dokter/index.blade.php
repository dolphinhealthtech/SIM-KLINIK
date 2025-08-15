@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h5 class="text-muted text-center">Dashboard Dokter</h5>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="dok-pasien-hari-ini">0</h3>
                                <p>Pasien hari ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="dok-rujukan-hari-ini">0</h3>
                                <p>Rujukan hari ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3 id="dok-rme-hari-ini">0</h3>
                                <p>RME hari ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="dok-antrian-menunggu">0</h3>
                                <p>Antrian menunggu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Jadwal Praktik Hari Ini</div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-sm table-striped table-hover mb-0">
                                    <thead><tr><th>Mulai</th><th>Selesai</th></tr></thead>
                                    <tbody id="dok-jadwal">
                                        <tr><td colspan="2" class="text-center text-muted">Belum ada data</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Antrian Pasien Hari Ini</span>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-sm table-striped table-hover mb-0">
                                    <thead><tr><th>No. Rawat</th><th>Pasien</th><th>Poli</th><th>Status</th><th class="text-right pr-3">Aksi</th></tr></thead>
                                    <tbody id="dok-antrian">
                                        <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">RME Terbaru</div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-sm table-striped table-hover mb-0">
                                    <thead><tr><th>Tanggal</th><th>No. Rawat</th><th>No. RM</th><th>Pasien</th></tr></thead>
                                    <tbody id="dok-rme">
                                        <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Rujukan Terbaru</span>
                                <small class="text-muted">Total 30 hari: <span id="dok-total-30">0</span> pasien</small>
                            </div>
                            <div class="card-body p-0 table-responsive">
                                <table class="table table-sm table-striped table-hover mb-0">
                                    <thead><tr><th>Tanggal</th><th>No. Rawat</th><th>Pasien</th><th>Tujuan</th></tr></thead>
                                    <tbody id="dok-rujukan">
                                        <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">Grafik Kunjungan 30 Hari Terakhir</div>
                    <div class="card-body">
                        <canvas id="dok-chart-30" height="120"></canvas>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            try {
                // Gunakan web route yang membawa session agar Auth::user() di controller terbaca
                const base = `${window.location.origin}/dokter-dashboard`;

                const [ringkasanRes, jadwalRes, antrianRes, rmeRes, rujukRes] = await Promise.all([
                    fetch(`${base}/ringkasan`).then(r => r.json()),
                    fetch(`${base}/jadwal-hari-ini`).then(r => r.json()),
                    fetch(`${base}/antrian-hari-ini`).then(r => r.json()),
                    fetch(`${base}/rme-terbaru`).then(r => r.json()),
                    fetch(`${base}/rujukan-terbaru`).then(r => r.json()),
                ]);

                // Cards ringkasan
                document.getElementById('dok-pasien-hari-ini').textContent = ringkasanRes.pasien_hari_ini ?? 0;
                document.getElementById('dok-rujukan-hari-ini').textContent = ringkasanRes.rujukan_hari_ini ?? 0;
                document.getElementById('dok-rme-hari-ini').textContent = ringkasanRes.rme_hari_ini ?? 0;
                document.getElementById('dok-antrian-menunggu').textContent = ringkasanRes.antrian_menunggu ?? 0;
                document.getElementById('dok-total-30').textContent = ringkasanRes.total_30_hari ?? 0;
                document.getElementById('dok-badge-menunggu').textContent = ringkasanRes.status_menunggu ?? 0;
                document.getElementById('dok-badge-dipanggil').textContent = ringkasanRes.status_dipanggil ?? 0;
                document.getElementById('dok-badge-pemeriksaan').textContent = ringkasanRes.status_pemeriksaan ?? 0;
                document.getElementById('dok-badge-selesai').textContent = ringkasanRes.status_selesai ?? 0;

                const jadwalBody = document.getElementById('dok-jadwal');
                jadwalBody.innerHTML = (jadwalRes.data || []).map(it => `
                    <tr><td>${it.mulai}</td><td>${it.selesai}</td></tr>
                `).join('') || '<tr><td colspan="2" class="text-center text-muted">Belum ada data</td></tr>';

                const antrianBody = document.getElementById('dok-antrian');
                const badgeFor = (status) => {
                    const s = (status || '').toLowerCase();
                    if (s.includes('menunggu')) return '<span class="badge badge-secondary">Menunggu</span>';
                    if (s.includes('panggil')) return '<span class="badge badge-info">Dipanggil</span>';
                    if (s.includes('pemeriksaan')) return '<span class="badge badge-primary">Dalam Pemeriksaan</span>';
                    if (s.includes('selesai')) return '<span class="badge badge-success">Selesai</span>';
                    return `<span class="badge badge-light">${status}</span>`;
                };
                const linkFor = (path) => `${window.location.origin}${path}`;
                const enc = (rawat) => btoa(rawat || '');
                antrianBody.innerHTML = (antrianRes.data || []).map(it => `
                    <tr>
                        <td>${it.no_rawat}</td>
                        <td>${it.pasien}</td>
                        <td>${it.poli}</td>
                        <td>${badgeFor(it.status)}</td>
                        <td class="text-right pr-3">
                            <a class="btn btn-xs btn-outline-info mr-1" href="${linkFor('/pemeriksaan/dokter/so/' + enc(it.no_rawat))}">SOAP</a>
                            <a class="btn btn-xs btn-outline-secondary mr-1" href="${linkFor('/pemeriksaan/dokter/so/hadir/' + enc(it.no_rawat))}">Panggil</a>
                            <a class="btn btn-xs btn-outline-success" href="${linkFor('/pemeriksaan/dokter/so/selesai/' + enc(it.no_rawat))}">Selesai</a>
                        </td>
                    </tr>
                `).join('') || '<tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>';

                const rmeBody = document.getElementById('dok-rme');
                rmeBody.innerHTML = (rmeRes.data || []).map(it => `
                    <tr><td>${it.tanggal}</td><td>${it.no_rawat}</td><td>${it.no_rm}</td><td>${it.pasien}</td></tr>
                `).join('') || '<tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>';

                const rujukBody = document.getElementById('dok-rujukan');
                rujukBody.innerHTML = (rujukRes.data || []).map(it => `
                    <tr><td>${it.tanggal}</td><td>${it.no_rawat}</td><td>${it.pasien}</td><td>${it.tujuan}</td></tr>
                `).join('') || '<tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>';
                // Chart 30 hari
                const ctx = document.getElementById('dok-chart-30').getContext('2d');
                const labels = ringkasanRes.labels || [];
                const data = ringkasanRes.data || [];
                if (window.Chart) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Kunjungan',
                                data,
                                borderColor: '#4e73df',
                                backgroundColor: 'rgba(78,115,223,0.15)',
                                fill: true,
                                tension: 0.3,
                                pointRadius: 0
                            }]
                        },
                        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
                    });
                }

            } catch (e) {
                console.error(e);
            }
        });
    </script>
@endsection
