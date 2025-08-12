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
            overflow-x: hidden;
        }

        .content-wrapper {
            min-height: 100vh;
            padding: 0.5rem !important;
        }

        .content {
            height: 100%;
        }

        .container-fluid {
            height: 100%;
        }

        /* Responsive spacing */
        .row {
            margin-bottom: 0.5rem !important;
        }

        .mb-4 {
            margin-bottom: 0.5rem !important;
        }

        .py-4,
        .py-5,
        .py-3 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        /* Responsive typography */
        .page-title {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            margin-bottom: 0 !important;
            font-weight: 800;
            letter-spacing: 1px;
            color: var(--primary-dark);
            text-transform: uppercase;
        }

        .subtitle {
            font-size: clamp(1rem, 2.5vw, 1.5rem);
            color: var(--primary-color);
            font-weight: 500;
        }

        .queue-number {
            font-size: clamp(3rem, 10vw, 6rem);
            font-weight: 800;
            color: var(--accent-color);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .loket-number {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            font-weight: 700;
        }

        /* Responsive card styling */
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: none;
            transition: transform 0.3s ease;
            margin-bottom: 1rem;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            padding: 0.75rem;
        }

        .card-header {
            padding: 0.75rem;
            border-bottom: none;
        }

        .card-header h2,
        .card-header h3 {
            font-size: clamp(1rem, 2.5vw, 1.2rem);
            margin-bottom: 0;
        }

        /* Theme colors */
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

        .badge-custom {
            background-color: rgba(255, 255, 255, 0.2);
            color: var(--text-light);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: clamp(0.8rem, 2vw, 1rem);
        }

        .time-date-box {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 0.5rem 1rem;
            display: inline-block;
            margin-top: 1rem;
        }

        .time-date-box h4 {
            font-size: clamp(0.9rem, 2vw, 1.2rem);
        }

        .announcement-card {
            border-left: 5px solid var(--primary-color);
            background-color: var(--primary-light);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .auto-announce-label {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-left: 0.5rem;
        }

        /* Responsive video container */
        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio */
            overflow: hidden;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Mobile-first responsive design */
        @media (max-width: 576px) {
            .content-wrapper {
                padding: 0.25rem !important;
            }

            .card-body {
                padding: 0.5rem;
            }

            .card-header {
                padding: 0.5rem;
            }

            .queue-number {
                font-size: 4rem;
            }

            .loket-number {
                font-size: 2rem;
            }

            .time-date-box {
                padding: 0.25rem 0.5rem;
            }

            .badge-custom {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }

            .announcement-card {
                padding: 0.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .subtitle {
                font-size: 1rem;
            }
        }

        @media (max-width: 768px) {

            .col-md-7,
            .col-md-5 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 1rem;
            }

            .video-card {
                height: auto !important;
                min-height: 200px;
            }

            .announcement-card-container {
                height: auto !important;
                margin-top: 1rem !important;
            }

            .loket-status-row {
                margin-top: 1rem !important;
            }
        }

        @media (min-width: 769px) and (max-width: 992px) {
            .queue-number {
                font-size: 5rem;
            }

            .loket-number {
                font-size: 2rem;
            }
        }

        @media (min-width: 993px) {
            .video-card {
                height: 455px;
            }

            .announcement-card-container {
                height: 180px;
                margin-top: 27px;
            }

            .loket-status-row {
                margin-top: 30px;
            }
        }

        /* Ensure proper spacing for mobile */
        @media (max-width: 768px) {
            .row {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }

            .row>[class*="col-"] {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }

        /* Responsive text in announcement */
        .announcement-card h4 {
            font-size: clamp(0.9rem, 2vw, 1.2rem);
        }

        .announcement-card p {
            font-size: clamp(0.8rem, 1.5vw, 0.9rem);
        }
    </style>

    <div class="content-wrapper py-4">
        <div class="content">
            <div class="container-fluid">
                <!-- Header -->
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <h1 class="page-title mb-2">Sistem Antrian Pasien</h1>
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
                                        ->select(
                                            'nomor_antrian as antrian',
                                            'created_at',
                                            DB::raw("'A' as loket"),
                                            DB::raw("'LOKET A' as loket_nama"),
                                        )
                                        ->first();

                                    if ($antrianA) {
                                        $allQueues->push([
                                            'antrian' => $antrianA->antrian,
                                            'loket' => 'A',
                                            'loket_nama' => 'LOKET A',
                                            'status_display' => 'SILAHKAN KE LOKET A',
                                            'created_at' => $antrianA->created_at,
                                        ]);
                                    }

                                    // Ambil antrian B (perawat)
                                    $antrianB = DB::table('pendaftaran_rawat_jalans')
                                        ->join(
                                            'pendaftaran_rawat_jalan_statuses',
                                            'pendaftaran_rawat_jalan_statuses.nomor_register',
                                            '=',
                                            'pendaftaran_rawat_jalans.nomor_register',
                                        )
                                        ->where('pendaftaran_rawat_jalan_statuses.status_panggil', 1)
                                        ->whereDate('pendaftaran_rawat_jalans.created_at', \Carbon\Carbon::today())
                                        ->select(
                                            'pendaftaran_rawat_jalans.antrian',
                                            'pendaftaran_rawat_jalans.created_at',
                                        )
                                        ->first();

                                    if ($antrianB) {
                                        $allQueues->push([
                                            'antrian' => $antrianB->antrian,
                                            'loket' => 'B',
                                            'loket_nama' => 'LOKET B',
                                            'status_display' => 'SILAHKAN KE LOKET B',
                                            'created_at' => $antrianB->created_at,
                                        ]);
                                    }

                                    // Ambil antrian C (dokter)
                                    $antrianC = DB::table('pendaftaran_rawat_jalans')
                                        ->join(
                                            'pendaftaran_rawat_jalan_statuses',
                                            'pendaftaran_rawat_jalan_statuses.nomor_register',
                                            '=',
                                            'pendaftaran_rawat_jalans.nomor_register',
                                        )
                                        ->where('pendaftaran_rawat_jalan_statuses.status_panggil', 2)
                                        ->whereDate('pendaftaran_rawat_jalans.created_at', \Carbon\Carbon::today())
                                        ->select(
                                            'pendaftaran_rawat_jalans.antrian',
                                            'pendaftaran_rawat_jalans.created_at',
                                        )
                                        ->first();

                                    if ($antrianC) {
                                        $allQueues->push([
                                            'antrian' => $antrianC->antrian,
                                            'loket' => 'C',
                                            'loket_nama' => 'LOKET C',
                                            'status_display' => 'SILAHKAN KE LOKET C',
                                            'created_at' => $antrianC->created_at,
                                        ]);
                                    }

                                    // Convert collection to array for JavaScript
                                    $allQueuesArray = $allQueues->toArray();
                                @endphp

                                <div class="bg-white rounded-lg p-4 shadow-sm mx-auto" style="max-width: 400px;">
                                    <h1 class="queue-number mb-0" id="displayed-queue-number">--</h1>
                                    <h3 class="text-theme-primary font-weight-bold mt-3" id="displayed-status">MENUNGGU
                                        PANGGILAN</h3>
                                </div>

                                <div class="time-date-box mt-4">
                                    <h4 class="mb-0"><i class="far fa-clock mr-2"></i><span
                                            id="current-time">00:00:00</span></h4>
                                    <h4 class="mb-0"><i class="far fa-calendar-alt mr-2"></i><span id="current-date">01
                                            Januari 2023</span></h4>
                                    <div class="mt-2"><small>Update otomatis dalam <span id="countdown">10</span>
                                            detik</small>
                                    </div>
                                </div>

                                <!-- Toggle untuk auto-announce -->
                                <div class="custom-control custom-switch mt-3">
                                    <input type="checkbox" class="custom-control-input" id="autoAnnounceToggle">
                                    <label class="custom-control-label auto-announce-label"
                                        for="autoAnnounceToggle"></label>
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
                                            const options = {
                                                day: 'numeric',
                                                month: 'long',
                                                year: 'numeric'
                                            };
                                            document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', options);
                                        }

                                        // Polling data tanpa reload halaman
                                        function setupAutoRefresh() {
                                            let countdown = 10; // 10 detik
                                            const countdownElement = document.getElementById('countdown');

                                            async function fetchQueues() {
                                                try {
                                                    const response = await fetch("{{ route('monitor.loket.antrian.data') }}", {
                                                        headers: {
                                                            'Accept': 'application/json'
                                                        }
                                                    });
                                                    if (!response.ok) return;
                                                    const data = await response.json();

                                                    // Perbarui sumber data allQueues untuk siklus tampilan/announce
                                                    if (Array.isArray(data.queues)) {
                                                        // Gabungkan antrian baru yang belum ada di displayedQueues
                                                        const newAll = [];
                                                        data.queues.forEach(q => newAll.push(q));
                                                        // Replace allQueues ref (agar checkAndDisplayNewQueues membaca data terbaru)
                                                        allQueues.length = 0;
                                                        newAll.forEach(q => allQueues.push(q));
                                                    }

                                                    if (data.loket_statuses) {
                                                        ['A', 'B', 'C'].forEach(key => {
                                                            const numberEl = document.getElementById(`loket-number-${key}`);
                                                            const statusEl = document.getElementById(`loket-status-${key}`);
                                                            const st = data.loket_statuses[key];
                                                            if (numberEl && st) numberEl.textContent = st.nomor ?? '--';
                                                            if (statusEl && st) statusEl.innerHTML =
                                                                `${st.icon ?? ''} ${st.label ?? ''}`;
                                                        });
                                                    }
                                                } catch (e) {
                                                    // silent fail untuk display publik
                                                }
                                            }

                                            function tick() {
                                                countdownElement.textContent = countdown;
                                                countdown--;
                                                if (countdown < 0) {
                                                    countdown = 10;
                                                    fetchQueues();
                                                }
                                                setTimeout(tick, 1000);
                                            }

                                            // initial fetch supaya langsung sinkron
                                            fetchQueues();
                                            tick();
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
                        <div class="row loket-status-row">
                            @php
                                // Definisikan loket-loket yang ada
                                $lokets = [['nama' => 'LOKET A'], ['nama' => 'LOKET B'], ['nama' => 'LOKET C']];
                            @endphp

                            @foreach ($lokets as $loket)
                                <div class="col-md-4 col-12">
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
                                                $labelStatus = 'Siap Melayani';
                                                $iconStatus = '<i class="fas fa-check-circle mr-1"></i>';

                                                if ($loketPrefix === 'A') {
                                                    $antrianLoket = App\Models\pasien_antrian::where(
                                                        'nomor_antrian',
                                                        'like',
                                                        'A-%',
                                                    )
                                                        ->where('status_panggil', '1')
                                                        ->whereDate('created_at', \Carbon\Carbon::today())
                                                        ->first();
                                                    if ($antrianLoket) {
                                                        $nomorAntrianDiLoket = $antrianLoket->nomor_antrian;
                                                        $labelStatus = 'Sedang Dilayani';
                                                        $iconStatus = '<i class="fas fa-user-nurse mr-1"></i>';
                                                    }
                                                } elseif ($loketPrefix === 'B') {
                                                    $antrianLoket = \DB::table('pendaftaran_rawat_jalan_statuses')
                                                        ->join(
                                                            'pendaftaran_rawat_jalans',
                                                            'pendaftaran_rawat_jalan_statuses.nomor_register',
                                                            '=',
                                                            'pendaftaran_rawat_jalans.nomor_register',
                                                        )
                                                        ->where('pendaftaran_rawat_jalan_statuses.status_panggil', 1)
                                                        ->whereDate(
                                                            'pendaftaran_rawat_jalan_statuses.created_at',
                                                            \Carbon\Carbon::today(),
                                                        )
                                                        ->orderBy('pendaftaran_rawat_jalan_statuses.created_at', 'desc')
                                                        ->select('pendaftaran_rawat_jalans.antrian')
                                                        ->first();
                                                    if ($antrianLoket && $antrianLoket->antrian) {
                                                        $nomorAntrianDiLoket = $antrianLoket->antrian;
                                                        $labelStatus = 'Sedang Dilayani Perawat';
                                                        $iconStatus = '<i class="fas fa-user-nurse mr-1"></i>';
                                                    }
                                                } elseif ($loketPrefix === 'C') {
                                                    $antrianLoket = \DB::table('pendaftaran_rawat_jalan_statuses')
                                                        ->join(
                                                            'pendaftaran_rawat_jalans',
                                                            'pendaftaran_rawat_jalan_statuses.nomor_register',
                                                            '=',
                                                            'pendaftaran_rawat_jalans.nomor_register',
                                                        )
                                                        ->where('pendaftaran_rawat_jalan_statuses.status_panggil', 2)
                                                        ->whereDate(
                                                            'pendaftaran_rawat_jalan_statuses.created_at',
                                                            \Carbon\Carbon::today(),
                                                        )
                                                        ->orderBy('pendaftaran_rawat_jalan_statuses.created_at', 'desc')
                                                        ->select('pendaftaran_rawat_jalans.antrian')
                                                        ->first();
                                                    if ($antrianLoket && $antrianLoket->antrian) {
                                                        $nomorAntrianDiLoket = $antrianLoket->antrian;
                                                        $labelStatus = 'Sedang Dilayani Dokter';
                                                        $iconStatus = '<i class="fas fa-user-md mr-1"></i>';
                                                    }
                                                }
                                            @endphp

                                            {{-- Tampilkan nomor antrian --}}
                                            <h2 class="loket-number text-theme-primary"
                                                id="loket-number-{{ $loketPrefix }}">{{ $nomorAntrianDiLoket }}</h2>
                                            {{-- Badge / Label status --}}
                                            <div class="badge-custom bg-theme-primary mt-2"
                                                id="loket-status-{{ $loketPrefix }}">
                                                {!! $iconStatus !!} {{ $labelStatus }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Video and Information - Right Side -->
                    <div class="col-md-5 col-12">
                        <div class="card mb-4 video-card">
                            <div class="card-header bg-theme-primary text-center py-3">
                                <h3 class="mb-0"><i class="fas fa-video mr-2"></i>INFORMASI</h3>
                            </div>
                            <div class="card-body p-0" style="flex: 1; overflow: hidden;">
                                <div class="video-container">
                                    <iframe
                                        src="https://www.youtube.com/embed/your-video-id?autoplay=1&mute=1&loop=1&playlist=your-video-id"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>

                        <!-- Announcement Card -->
                        <div class="card announcement-card-container">
                            <div class="card-header bg-theme-primary text-center py-3">
                                <h3 class="mb-0"><i class="fas fa-bullhorn mr-2"></i>PENGUMUMAN</h3>
                            </div>
                            <div class="card-body p-2" style="overflow: hidden;">
                                <div class="announcement-card p-2">
                                    <h4 class="mb-1"><i class="fas fa-info-circle mr-1 text-theme-primary"></i> Perhatian
                                    </h4>
                                    <p class="mb-0">Mohon perhatikan nomor antrian Anda. Pastikan Anda berada di area
                                        tunggu saat nomor Anda mendekati giliran.</p>
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
            const options = {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', options);
        }

        // Update time every second
        setInterval(updateDateTime, 1000);
        updateDateTime(); // Initial call
    </script>
@endsection
