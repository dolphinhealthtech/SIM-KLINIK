@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h5 class="text-muted text-center">Dashboard Administrasi</h5>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="adm-pasien-hari-ini">0</h3>
                                <p>Pasien terdaftar hari ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3 id="adm-pasien-bulan-ini">0</h3>
                                <p>Pasien terdaftar bulan ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="adm-lunas">0</h3>
                                <p>Pembayaran Lunas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="adm-belum-lunas">0</h3>
                                <p>Pembayaran Belum Lunas</p>
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
                                    <tbody id="adm-jadwal-hari-ini">
                                        <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Chart Status Pembayaran (Hari Ini)</div>
                            <div class="card-body">
                                <canvas id="adm-chart-bayar" height="160"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-3">
                    <a href="{{ route('pendataan_pendaftaran.get') }}" class="btn btn-primary">Registrasi Pasien</a>
                </div>

                <div class="card">
                    <div class="card-header">Data Pasien Belum Lengkap</div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Nama</th><th>NIK</th><th>Alamat</th></tr></thead>
                            <tbody id="adm-incomplete">
                                <tr><td colspan="3" class="text-center text-muted">Tidak ada data</td></tr>
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
                const base = `${window.location.origin}/api/admin-dashboard`;

                const [summaryRes, scheduleRes, payRes, incompleteRes] = await Promise.all([
                    fetch(`${base}/summary`).then(r => r.json()),
                    fetch(`${base}/schedule-today`).then(r => r.json()),
                    fetch(`${base}/payment-status`).then(r => r.json()),
                    fetch(`${base}/incomplete-data`).then(r => r.json()),
                ]);

                // Cards
                document.getElementById('adm-pasien-hari-ini').textContent = summaryRes.hari_ini ?? 0;
                document.getElementById('adm-pasien-bulan-ini').textContent = summaryRes.bulan_ini ?? 0;
                document.getElementById('adm-lunas').textContent = payRes.lunas ?? 0;
                document.getElementById('adm-belum-lunas').textContent = payRes.belum_lunas ?? 0;

                // Tabel jadwal
                const bodyJadwal = document.getElementById('adm-jadwal-hari-ini');
                bodyJadwal.innerHTML = '';
                const rows = (scheduleRes.data || []).map(it => `
                    <tr>
                        <td>${it.waktu ?? '-'}</td>
                        <td>${it.pasien ?? '-'}</td>
                        <td>${it.poli ?? '-'}</td>
                        <td>${it.dokter ?? '-'}</td>
                    </tr>
                `).join('');
                bodyJadwal.innerHTML = rows || '<tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>';

                // Tabel incomplete data
                const bodyInc = document.getElementById('adm-incomplete');
                bodyInc.innerHTML = '';
                const incRows = (incompleteRes.data || []).map(it => `
                    <tr>
                        <td>${it.nama}</td>
                        <td>${it.nik ?? '-'}</td>
                        <td>${it.alamat ?? '-'}</td>
                    </tr>
                `).join('');
                bodyInc.innerHTML = incRows || '<tr><td colspan="3" class="text-center text-muted">Tidak ada data</td></tr>';

                // Chart status pembayaran
                const ctx = document.getElementById('adm-chart-bayar').getContext('2d');
                const labels = ['Lunas', 'Belum Lunas'];
                const data = [payRes.lunas ?? 0, payRes.belum_lunas ?? 0];
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels,
                        datasets: [{
                            data,
                            backgroundColor: ['#28a745', '#ffc107']
                        }]
                    },
                    options: {
                        plugins: { legend: { position: 'bottom' } },
                        cutout: '60%'
                    }
                });
            } catch (e) {
                console.error(e);
            }
        });
    </script>
@endsection
