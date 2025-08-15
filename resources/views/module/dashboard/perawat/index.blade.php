@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h5 class="text-muted text-center">Dashboard Perawat</h5>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="prw-total">0</h3>
                                <p>Total Antrian Hari Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="prw-menunggu">0</h3>
                                <p>Menunggu</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3 id="prw-dipanggil">0</h3>
                                <p>Dipanggil</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><span id="prw-pemeriksaan">0</span> / <span id="prw-selesai">0</span></h3>
                                <p>Dalam Pemeriksaan / Selesai</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Antrian Hari Ini</div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>No. Rawat</th>
                                            <th>Pasien</th>
                                            <th>Poli</th>
                                            <th>Dokter</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="prw-antrian">
                                        <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">SOAP Perawat Terbaru</div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>No. Rawat</th>
                                            <th>Pasien</th>
                                            <th>Perawat</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody id="prw-soap">
                                        <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            try {
                const base = `${window.location.origin}/api/perawat-dashboard`;
                const [ringkasan, antrian, soap] = await Promise.all([
                    fetch(`${base}/ringkasan`).then(r => r.json()),
                    fetch(`${base}/antrian-hari-ini`).then(r => r.json()),
                    fetch(`${base}/soap-terbaru`).then(r => r.json()),
                ]);

                document.getElementById('prw-total').textContent = ringkasan.antrian_total ?? 0;
                document.getElementById('prw-menunggu').textContent = ringkasan.status_menunggu ?? 0;
                document.getElementById('prw-dipanggil').textContent = ringkasan.status_dipanggil ?? 0;
                document.getElementById('prw-pemeriksaan').textContent = ringkasan.status_pemeriksaan ?? 0;
                document.getElementById('prw-selesai').textContent = ringkasan.status_selesai ?? 0;

                const bodyAntrian = document.getElementById('prw-antrian');
                const rowsAntrian = (antrian.data || []).map(it => `
                    <tr>
                        <td>${it.no_rawat ?? '-'}</td>
                        <td>${it.pasien ?? '-'}</td>
                        <td>${it.poli ?? '-'}</td>
                        <td>${it.dokter ?? '-'}</td>
                        <td>${it.status ?? '-'}</td>
                    </tr>
                `).join('');
                bodyAntrian.innerHTML = rowsAntrian || '<tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>';

                const bodySoap = document.getElementById('prw-soap');
                const rowsSoap = (soap.data || []).map(it => `
                    <tr>
                        <td>${it.no_rawat ?? '-'}</td>
                        <td>${it.pasien ?? '-'}</td>
                        <td>${it.perawat ?? '-'}</td>
                        <td>${it.waktu ?? '-'}</td>
                    </tr>
                `).join('');
                bodySoap.innerHTML = rowsSoap || '<tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>';
            } catch (e) {
                console.error(e);
            }
        });
    </script>
@endsection
