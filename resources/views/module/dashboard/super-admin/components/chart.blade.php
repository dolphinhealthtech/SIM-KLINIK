<!-- /.row -->
<!-- HTML -->
<div class="row">
    <!-- Kunjungan Harian -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">Grafik Kunjungan per Hari</h3>
            </div>
            <div class="card-body">
                <canvas id="kunjunganChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Pendapatan Bulanan -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Grafik Pendapatan Bulanan</h3>
            </div>
            <div class="card-body">
                <canvas id="pendapatanChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Kunjungan Poli Bulanan -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-info">
                <h5 class="card-title">Report Kunjungan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Grafik -->
                    <div class="col-md-8">
                        <p class="text-center">
                            @php
                                use Carbon\Carbon;

                                $start = Carbon::now()->startOfMonth();
                                $end = Carbon::now()->endOfMonth();
                            @endphp

                            <strong>Kunjungan: {{ $start->translatedFormat('j F') }} -
                                {{ $end->translatedFormat('j F Y') }}</strong>
                        </p>
                        <div class="chart" style="height: 180px; position: relative;">
                            <canvas id="poliChart"></canvas>
                        </div>
                    </div>
                    <!-- Progress Bar -->
                    <div class="col-md-4">
                        <p class="text-center"><strong>Completion</strong></p>
                        <div id="progressPoli"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
