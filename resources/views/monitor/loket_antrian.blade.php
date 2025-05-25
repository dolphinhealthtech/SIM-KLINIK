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
                            <div class="bg-white rounded-lg p-4 shadow-sm mx-auto" style="max-width: 400px;">
                                <h1 class="queue-number mb-0">A-12</h1>
                                <h3 class="text-theme-primary font-weight-bold mt-3">SILAHKAN KE LOKET A</h3>
                            </div>
                            <div class="time-date-box mt-4">
                                <h4 class="mb-0"><i class="far fa-clock mr-2"></i><span id="current-time">00:00:00</span></h4>
                                <h4 class="mb-0"><i class="far fa-calendar-alt mr-2"></i><span id="current-date">01 Januari 2023</span></h4>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loket Status Cards -->
                    <div class="row" style="margin-top: 30px;">
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header bg-theme-primary text-center py-3">
                                    <h3 class="mb-0">LOKET A</h3>
                                </div>
                                <div class="card-body text-center py-4">
                                    <h2 class="loket-number text-theme-primary">A-12</h2>
                                    <div class="badge-custom bg-theme-primary mt-2">
                                        <i class="fas fa-user-nurse mr-1"></i> Sedang Dilayani
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header bg-theme-primary text-center py-3">
                                    <h3 class="mb-0">LOKET B</h3>
                                </div>
                                <div class="card-body text-center py-4">
                                    <h2 class="loket-number text-theme-primary">B-05</h2>
                                    <div class="badge-custom bg-theme-primary mt-2">
                                        <i class="fas fa-user-nurse mr-1"></i> Sedang Dilayani
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header bg-theme-primary text-center py-3">
                                    <h3 class="mb-0">LOKET C</h3>
                                </div>
                                <div class="card-body text-center py-4">
                                    <h2 class="loket-number text-theme-primary">C-08</h2>
                                    <div class="badge-custom bg-theme-primary mt-2">
                                        <i class="fas fa-user-nurse mr-1"></i> Sedang Dilayani
                                    </div>
                                </div>
                            </div>
                        </div>
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
    
    // Auto refresh page every 30 seconds
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
@endsection
