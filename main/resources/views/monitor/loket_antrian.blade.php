@extends('layouts.monitor')

@section('content')
<style>
    :root {
        --primary-color: #00a3a3;
        --primary-dark: #008080;
        --primary-light: #b3e6e6;
        --accent-color: #ff6b6b;
        --text-dark: #2c3e50;
        --text-light: #ecf0f1;
        --bg-light: #f8f9fa;
        --bg-dark: #1a2a2a;
    }

    body {
        background-color: var(--bg-light);
        color: var(--text-dark);
        overflow: hidden;
    }

    .content-wrapper {
        overflow: hidden;
        height: 100vh;
        padding: 0.5rem !important;
    }

    .content {
        height: 100%;
    }

    .container-fluid {
        height: 100%;
        overflow: hidden;
    }

    /* Reduce spacing */
    .row {
        max-height: 100%;
        margin-bottom: 0.5rem !important;
    }

    .mb-4 {
        margin-bottom: 0.5rem !important;
    }

    .py-4, .py-5, .py-3 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    /* Scale down elements */
    .page-title {
        font-size: 1.8rem;
        margin-bottom: 0 !important;
    }

    .subtitle {
        font-size: 1.2rem;
    }

    .queue-number {
        font-size: 6rem;
    }

    .loket-number {
        font-size: 2.5rem;
    }

    .card-body {
        padding: 0.75rem;
    }

    .card-header {
        padding: 0.75rem;
    }

    .card-header h2, .card-header h3 {
        font-size: 1.2rem;
        margin-bottom: 0;
    }

    /* Keep other existing styles */
    .bg-theme-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: var(--text-light);
    }

    .bg-theme-accent {
        background: linear-gradient(135deg, var(--accent-color), #e74c3c);
        color: var(--text-light);
    }

    .text-theme-primary {
        color: var(--primary-color);
    }

    .border-theme {
        border: 2px solid var(--primary-color);
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        border: none;
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        border-bottom: none;
        padding: 1.25rem;
    }

    .queue-number {
        font-size: 9rem;
        font-weight: 800;
        color: var(--accent-color);
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .loket-number {
        font-size: 4rem;
        font-weight: 700;
    }

    .badge-custom {
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 1rem;
    }

    .time-date-box {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 0.5rem 1rem;
        display: inline-block;
        margin-top: 1rem;
    }

    .announcement-card {
        border-left: 5px solid var(--primary-color);
        background-color: var(--primary-light);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .page-title {
        font-weight: 800;
        letter-spacing: 1px;
        color: var(--primary-dark);
        text-transform: uppercase;
    }

    .subtitle {
        color: var(--primary-color);
        font-weight: 500;
    }

    .auto-announce-label {
        color: var(--text-light);
        font-size: 0.9rem;
        margin-left: 0.5rem;
    }
</style>

<div class="content-wrapper py-4">
    <div class="content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h1 class="display-4 page-title mb-2">Sistem Antrian Pasien</h1>
                    <h3 class="subtitle">{{ $settings->nama ?? 'Klinik' }}</h3>
                </div>
            </div>

            <!-- Main Content -->
            <div class="row">
                <!-- Current Queue Section - Left Side -->
                <div class="col-md-7">
                    <div class="card mb-4">
                        <div class="card-header bg-theme-primary">
                            <h2 class="text-center mb-0">NOMOR ANTRIAN YANG DIPANGGIL</h2>
                        </div>
                       <div class="card-body text-center py-5 bg-theme-primary">
        @php
            // Ambil semua antrian yang tersedia untuk hari ini
            $allQueues = collect();

            // Ambil antrian A (pasien_antrian)
            $antrianA = App\Models\pasien_antrian::where('nomor_antrian', 'like', 'A-%')
                ->where('status_panggil', '1')
                ->whereDate('created_at', \Carbon\Carbon::today())
                ->select('nomor_antrian as antrian', 'created_at', DB::raw("'A' as loket"), DB::raw("'LOKET A' as loket_nama"))
                ->first();

            if ($antrianA) {
                $allQueues->push([
                    'antrian' => $antrianA->antrian,
                    'loket' => 'A',
                    'loket_nama' => 'LOKET A',
                    'status_display' => 'SILAHKAN KE LOKET A',
                    'created_at' => $antrianA->created_at
                ]);
            }

            // Ambil antrian B (perawat)
            $antrianB = DB::table('pendaftaran_rawat_jalans')
                ->join('pendaftaran_rawat_jalan_statuses', 'pendaftaran_rawat_jalan_statuses.nomor_register', '=', 'pendaftaran_rawat_jalans.nomor_register')
                ->where('pendaftaran_rawat_jalan_statuses.status_panggil', 1)
                ->whereDate('pendaftaran_rawat_jalans.created_at', \Carbon\Carbon::today())
                ->select('pendaftaran_rawat_jalans.antrian', 'pendaftaran_rawat_jalans.created_at')
                ->first();

            if ($antrianB) {
                $allQueues->push([
                    'antrian' => $antrianB->antrian,
                    'loket' => 'B',
                    'loket_nama' => 'LOKET B',
                    'status_display' => 'SILAHKAN KE LOKET B',
                    'created_at' => $antrianB->created_at
                ]);
            }

            // Ambil antrian C (dokter)
            $antrianC = DB::table('pendaftaran_rawat_jalans')
                ->join('pendaftaran_rawat_jalan_statuses', 'pendaftaran_rawat_jalan_statuses.nomor_register', '=', 'pendaftaran_rawat_jalans.nomor_register')
                ->where('pendaftaran_rawat_jalan_statuses.status_panggil', 2)
                ->whereDate('pendaftaran_rawat_jalans.created_at', \Carbon\Carbon::today())
                ->select('pendaftaran_rawat_jalans.antrian', 'pendaftaran_rawat_jalans.created_at')
                ->first();

            if ($antrianC) {
                $allQueues->push([
                    'antrian' => $antrianC->antrian,
                    'loket' => 'C',
                    'loket_nama' => 'LOKET C',
                    'status_display' => 'SILAHKAN KE LOKET C',
                    'created_at' => $antrianC->created_at
                ]);
            }

            // Convert collection to array for JavaScript
            $allQueuesArray = $allQueues->toArray();
        @endphp

        <div class="bg-white rounded-lg p-4 shadow-sm mx-auto" style="max-width: 400px;">
            <h1 class="queue-number mb-0" id="displayed-queue-number">--</h1>
            <h3 class="text-theme-primary font-weight-bold mt-3" id="displayed-status">MENUNGGU PANGGILAN</h3>
        </div>

        <div class="time-date-box mt-4">
            <h4 class="mb-0"><i class="far fa-clock mr-2"></i><span id="current-time">00:00:00</span></h4>
            <h4 class="mb-0"><i class="far fa-calendar-alt mr-2"></i><span id="current-date">01 Januari 2023</span></h4>
            <div class="mt-2"><small>Refresh dalam <span id="countdown">10</span> detik</small></div>
        </div>

        <!-- Toggle untuk auto-announce -->
        <div class="custom-control custom-switch mt-3">
            <input type="checkbox" class="custom-control-input" id="autoAnnounceToggle">
            <label class="custom-control-label auto-announce-label" for="autoAnnounceToggle"></label>
        </div>

        <script>
        // Data antrian dari PHP
        const allQueues = @json($allQueuesArray);

        document.addEventListener('DOMContentLoaded', function() {
            let displayInterval;

            // Track which queues have been displayed (persist in sessionStorage)
            let displayedQueues = [];

            // Try to load previously displayed queues from sessionStorage
            try {
                const savedDisplayedQueues = sessionStorage.getItem('displayedQueues');
                if (savedDisplayedQueues) {
                    displayedQueues = JSON.parse(savedDisplayedQueues);
                }
            } catch (e) {
                console.error('Error loading displayed queues:', e);
                displayedQueues = [];
            }

            // Simpan status autoplay di localStorage
            const autoAnnounceToggle = document.getElementById('autoAnnounceToggle');
            autoAnnounceToggle.checked = localStorage.getItem('autoAnnounce') === 'true';

            autoAnnounceToggle.addEventListener('change', function() {
                localStorage.setItem('autoAnnounce', this.checked);

                if (this.checked) {
                    startContinuousDisplay();
                } else {
                    stopContinuousDisplay();
                    // Reset display ke "--"
                    document.getElementById('displayed-queue-number').textContent = '--';
                    document.getElementById('displayed-status').textContent = 'MENUNGGU PANGGILAN';
                }
            });

            // Fungsi untuk memulai tampilan bergantian
            function startContinuousDisplay() {
                if (allQueues.length === 0) {
                    document.getElementById('displayed-queue-number').textContent = '--';
                    document.getElementById('displayed-status').textContent = 'TIDAK ADA ANTRIAN';
                    return;
                }

                // Set interval untuk memeriksa antrian baru setiap 5 detik
                displayInterval = setInterval(function() {
                    checkAndDisplayNewQueues();
                }, 5000);

                // Periksa dan tampilkan antrian baru segera
                checkAndDisplayNewQueues();
            }

            // Fungsi untuk memeriksa dan menampilkan antrian baru
            function checkAndDisplayNewQueues() {
                console.log('Checking for new queues. Currently displayed:', displayedQueues);
                console.log('All queues:', allQueues);

                // Filter queues that haven't been displayed for their current counter
                const newQueues = allQueues.filter(queue => {
                    const key = queue.antrian + '-' + queue.loket;
                    return !displayedQueues.includes(key);
                });

                console.log('New queues to display:', newQueues);

                if (newQueues.length === 0) {
                    document.getElementById('displayed-queue-number').textContent = '--';
                    document.getElementById('displayed-status').textContent = 'MENUNGGU PANGGILAN';
                    return;
                }

                // Ambil antrian pertama yang belum ditampilkan
                const queueToDisplay = newQueues[0];

                // Tampilkan antrian
                document.getElementById('displayed-queue-number').textContent = queueToDisplay.antrian;
                document.getElementById('displayed-status').textContent = queueToDisplay.status_display;

                // Tandai antrian ini sudah ditampilkan
                const queueKey = queueToDisplay.antrian + '-' + queueToDisplay.loket;
                displayedQueues.push(queueKey);

                // Save displayed queues to sessionStorage
                try {
                    sessionStorage.setItem('displayedQueues', JSON.stringify(displayedQueues));
                } catch (e) {
                    console.error('Error saving displayed queues:', e);
                }

                // Umumkan antrian ini
                if (autoAnnounceToggle.checked) {
                    announceQueue(queueToDisplay);
                }
            }

            // Fungsi untuk mengumumkan antrian
            function announceQueue(queue) {
                const announcementText = `Nomor Antrian ${queue.antrian}, silakan menuju ${queue.loket_nama}`;

                // Hentikan pengumuman sebelumnya jika masih berjalan
                speechSynthesis.cancel();

                const utterance = new SpeechSynthesisUtterance(announcementText);
                utterance.lang = 'id-ID';
                utterance.rate = 0.8;
                utterance.pitch = 1;
                utterance.volume = 1;

                speechSynthesis.speak(utterance);
            }

            // Fungsi untuk menghentikan tampilan bergantian
            function stopContinuousDisplay() {
                if (displayInterval) {
                    clearInterval(displayInterval);
                    displayInterval = null;
                }
                // Hentikan speech synthesis
                speechSynthesis.cancel();
            }

            // Mulai tampilan bergantian jika toggle aktif
            if (autoAnnounceToggle.checked) {
                startContinuousDisplay();
            }

            // Fungsi untuk update waktu dan tanggal
            function updateDateTime() {
                const now = new Date();

                // Format waktu: HH:MM:SS
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;

                // Format tanggal: DD Bulan YYYY
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', options);
            }

            // Fungsi untuk auto-refresh halaman
            function setupAutoRefresh() {
                let countdown = 10; // 10 detik
                const countdownElement = document.getElementById('countdown');

                function updateCountdown() {
                    countdownElement.textContent = countdown;
                    countdown--;

                    if (countdown < 0) {
                        // Refresh halaman
                        window.location.reload();
                    } else {
                        setTimeout(updateCountdown, 1000);
                    }
                }

                updateCountdown();
            }

            // Update waktu dan tanggal setiap detik
            updateDateTime();
            setInterval(updateDateTime, 1000);

            // Setup auto-refresh
            setupAutoRefresh();
        });
        </script>

    </div>
                    </div>

                    <!-- Loket Status Cards -->
                    <div class="row" style="margin-top: 30px;">
                        @php
                            // Definisikan loket-loket yang ada
                            $lokets = [
                                ['nama' => 'LOKET A'],
                                ['nama' => 'LOKET B'],
                                ['nama' => 'LOKET C']
                            ];
                        @endphp

                        @foreach($lokets as $loket)
                                <div class="col-md-4">
                                    <div class="card mb-4">
                                        <div class="card-header bg-theme-primary text-center py-3">
                                            <h3 class="mb-0">{{ $loket['nama'] }}</h3>
                                        </div>
                                        <div class="card-body text-center py-4">
                                            @php
                                                // Dapatkan prefix (A/B/C)
                                                $loketPrefix = substr($loket['nama'], -1);

                                                // Ambil nomor antrian sesuai loketPrefix
                                                $nomorAntrianDiLoket = '--';
                                                $labelStatus        = 'Siap Melayani';
                                                $iconStatus         = '<i class="fas fa-check-circle mr-1"></i>';

                                                if ($loketPrefix === 'A') {
                                                    $antrianLoket = App\Models\pasien_antrian::where('nomor_antrian', 'like', 'A-%')
                                                        ->where('status_panggil', '1')
                                                        ->whereDate('created_at', \Carbon\Carbon::today())
                                                        ->first();
                                                    if ($antrianLoket) {
                                                        $nomorAntrianDiLoket = $antrianLoket->nomor_antrian;
                                                        $labelStatus        = 'Sedang Dilayani';
                                                        $iconStatus         = '<i class="fas fa-user-nurse mr-1"></i>';
                                                    }
                                                }
                                                elseif ($loketPrefix === 'B') {
                                                    $antrianLoket = \DB::table('pendaftaran_rawat_jalan_statuses')
                                                        ->join('pendaftaran_rawat_jalans','pendaftaran_rawat_jalan_statuses.nomor_register','=','pendaftaran_rawat_jalans.nomor_register')
                                                        ->where('pendaftaran_rawat_jalan_statuses.status_panggil', 1)
                                                        ->whereDate('pendaftaran_rawat_jalan_statuses.created_at', \Carbon\Carbon::today())
                                                        ->orderBy('pendaftaran_rawat_jalan_statuses.created_at','desc')
                                                        ->select('pendaftaran_rawat_jalans.antrian')
                                                        ->first();
                                                    if ($antrianLoket && $antrianLoket->antrian) {
                                                        $nomorAntrianDiLoket = $antrianLoket->antrian;
                                                        $labelStatus        = 'Sedang Dilayani Perawat';
                                                        $iconStatus         = '<i class="fas fa-user-nurse mr-1"></i>';
                                                    }
                                                }
                                                elseif ($loketPrefix === 'C') {
                                                    $antrianLoket = \DB::table('pendaftaran_rawat_jalan_statuses')
                                                        ->join('pendaftaran_rawat_jalans','pendaftaran_rawat_jalan_statuses.nomor_register','=','pendaftaran_rawat_jalans.nomor_register')
                                                        ->where('pendaftaran_rawat_jalan_statuses.status_panggil', 2)
                                                        ->whereDate('pendaftaran_rawat_jalan_statuses.created_at', \Carbon\Carbon::today())
                                                        ->orderBy('pendaftaran_rawat_jalan_statuses.created_at','desc')
                                                        ->select('pendaftaran_rawat_jalans.antrian')
                                                        ->first();
                                                    if ($antrianLoket && $antrianLoket->antrian) {
                                                        $nomorAntrianDiLoket = $antrianLoket->antrian;
                                                        $labelStatus        = 'Sedang Dilayani Dokter';
                                                        $iconStatus         = '<i class="fas fa-user-md mr-1"></i>';
                                                    }
                                                }
                                            @endphp

                                            {{-- Tampilkan nomor antrian --}}
                                            <h2 class="loket-number text-theme-primary">{{ $nomorAntrianDiLoket }}</h2>
                                            {{-- Badge / Label status --}}
                                            <div class="badge-custom bg-theme-primary mt-2">
                                                {!! $iconStatus !!} {{ $labelStatus }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                    </div>
                </div>

                <!-- Video and Information - Right Side -->
                <div class="col-md-5">
                    <div class="card mb-4" style="height: 455px;"> <!-- Adjust this height to match NOMOR ANTRIAN YANG DIPANGGIL -->
                        <div class="card-header bg-theme-primary text-center py-3">
                            <h3 class="mb-0"><i class="fas fa-video mr-2"></i>INFORMASI</h3>
                        </div>
                        <div class="card-body p-0" style="flex: 1; overflow: hidden;">
                            <div class="embed-responsive embed-responsive-16by9" style="height: 100%;">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/your-video-id?autoplay=1&mute=1&loop=1&playlist=your-video-id" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>

                    <!-- Announcement Card -->
                    <div class="card" style="height: 180px; margin-top: 27px;"> <!-- Adjust this height to match the loket cards -->
                        <div class="card-header bg-theme-primary text-center py-3">
                            <h3 class="mb-0"><i class="fas fa-bullhorn mr-2"></i>PENGUMUMAN</h3>
                        </div>
                    <div class="card-body p-2" style="overflow: hidden; max-height: 180px;">
                        <div class="announcement-card p-2" style="max-height: 140px;" >
                            <h4 class="mb-1" style="font-size: 1 rem;"><i class="fas fa-info-circle mr-1 text-theme-primary"></i> Perhatian</h4>
                            <p style="font-size: 0.9rem; margin-bottom: 0;">Mohon perhatikan nomor antrian Anda. Pastikan Anda berada di area tunggu saat nomor Anda mendekati giliran.</p>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update time and date
    function updateDateTime() {
        const now = new Date();

        // Format time: HH:MM:SS
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;

        // Format date: DD Month YYYY
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', options);
    }

    // Update time every second
    setInterval(updateDateTime, 1000);
    updateDateTime(); // Initial call
</script>
@endsection
