@extends('layouts.dashbord')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>SOAP Rawat Jalan Selesaiii</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">SOAP Rawat Jalan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Data Pasien -->
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-injured mr-2"></i>Data Pasien</h3>
                        </div>
                        <div class="card-body">
                            <!-- Brand Logo dari sidebar dengan path yang diubah ke public/profile/default.png -->
                            <div class="text-center mb-4">
                                <img src="{{ asset('profile/default.png') }}"
                                    alt="Klinik Logo" class="img-circle elevation-2"
                                    style="width: 100px; height: 100px; opacity: .8">
                            </div>

                            <div class="form-group">
                                <label for="nomor_rm">No. RM</label>
                                <input type="text" class="form-control bg-light" id="nomor_rm" value="{{ $pelayanan->nomor_rm }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="nama">Nama Pasien</label>
                                <input type="text" class="form-control bg-light" id="nama" value="{{ $pelayanan->pasien->nama }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <input type="text" class="form-control bg-light" id="jenis_kelamin" value="{{ $pelayanan->pasien->kelamin->nama }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="penjamin">Penjamin</label>
                                <input type="text" class="form-control bg-light" id="penjamin" value="{{ $pelayanan->pendaftaran->penjamin->nama }}" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="text" class="form-control bg-light" id="tanggal_lahir" value="{{ $pelayanan->pasien->tanggal_lahir }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="umur">Umur</label>
                                        <input type="text" class="form-control bg-light" id="umur" value="{{ $umur }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-four-Timeline-tab" data-toggle="pill" href="#custom-tabs-four-Timeline" role="tab" aria-controls="custom-tabs-four-Timeline" aria-selected="true">Timeline</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-CPPT-tab" data-toggle="pill" href="#custom-tabs-four-CPPT" role="tab" aria-controls="custom-tabs-four-CPPT" aria-selected="false">CPPT</a>
                            </li>

                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-four-Timeline" role="tabpanel" aria-labelledby="custom-tabs-four-Timeline-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="timeline">
                                            @foreach ($timeline as $item)
                                                <div class="time-label">
                                                    <span class="{{ $item['bg'] }}">{{ $item['date'] }}</span>
                                                </div>
                                                <div>
                                                    <i class="{{ $item['icon'] }} {{ $item['bg'] }}"></i>
                                                    <div class="timeline-item">
                                                        <span class="time"><i class="fas fa-clock"></i> {{ $item['time'] }}</span>
                                                        <h3 class="timeline-header">{{ $item['title'] }}</h3>
                                                        <div class="timeline-body">
                                                            {!! $item['message'] !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div>
                                                <i class="fas fa-clock bg-gray"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="custom-tabs-four-CPPT" role="tabpanel" aria-labelledby="custom-tabs-four-CPPT-tab">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi tabs bootstrap (jika ada)
        $('#custom-tabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // Langkah-langkah step
        const steps = ['step-a', 'step-b', 'step-c', 'step-d', 'step-e', 'step-f', 'step-g', 'step-h'];

        // Inisialisasi: tampilkan hanya step pertama
        steps.forEach((step, index) => {
            const el = document.getElementById(step);
            if (el) {
                el.style.display = index === 0 ? 'block' : 'none';
                el.style.opacity = index === 0 ? '1' : '0';
            }
        });

        // Tambahkan CSS transisi
        const style = document.createElement('style');
        style.innerHTML = `
            .step-content {
                transition: opacity 0.3s ease-in-out;
            }
        `;
        document.head.appendChild(style);

        // Ubah teks tombol "Next" jadi "Selesai" di step terakhir
        const lastStepNextBtn = document.querySelector('#step-h .btn-next');
        if (lastStepNextBtn) {
            lastStepNextBtn.innerText = 'Selesai';
        }
    });

    // Dapatkan step aktif
    function getCurrentStep() {
        const steps = ['step-a', 'step-b', 'step-c', 'step-d', 'step-e', 'step-f', 'step-g', 'step-h'];
        for (let i = 0; i < steps.length; i++) {
            const step = document.getElementById(steps[i]);
            if (step && (step.style.display === 'block' || step.style.display === '')) {
                return steps[i];
            }
        }
        return 'step-a'; // default jika tidak ditemukan
    }

    // Navigasi antar step
    function navigateTo(fromStep, toStep) {
        const currentStepElement = document.getElementById(fromStep);
        if (currentStepElement) {
            currentStepElement.style.display = 'none';
            currentStepElement.style.opacity = '0';
        }

        const nextStepElement = document.getElementById(toStep);
        if (nextStepElement) {
            nextStepElement.style.display = 'block';
            setTimeout(() => {
                nextStepElement.style.opacity = '1';
            }, 50);
        }
    }

    function updateProgressBar(currentIndex) {
        const totalSteps = 8;
        const percentage = ((currentIndex + 1) / totalSteps) * 100;
        const progressBar = document.getElementById('progress-bar');

        if (progressBar) {
            progressBar.style.width = `${percentage}%`;
            progressBar.setAttribute('aria-valuenow', percentage.toFixed(1));
        }
    }


    // Navigasi ke step berikutnya
    function navigateNext() {
        const currentStep = getCurrentStep();
        const steps = ['step-a', 'step-b', 'step-c', 'step-d', 'step-e', 'step-f', 'step-g', 'step-h'];
        const currentIndex = steps.indexOf(currentStep);

        if (currentIndex < steps.length - 1) {
            const nextStep = steps[currentIndex + 1];
            navigateTo(currentStep, nextStep);
            updateProgressBar(currentIndex + 1); // ← Tambahkan ini

        } else if (currentIndex === steps.length - 1) {
            // Step terakhir
            alert('Proses selesai!');
            // document.getElementById('form-soap').submit(); // jika ingin langsung submit

        }
    }

    // Navigasi ke step sebelumnya
    function navigateBack() {
        const currentStep = getCurrentStep();
        const steps = ['step-a', 'step-b', 'step-c', 'step-d', 'step-e', 'step-f', 'step-g', 'step-h'];
        const currentIndex = steps.indexOf(currentStep);

        if (currentIndex > 0) {
            const prevStep = steps[currentIndex - 1];
            navigateTo(currentStep, prevStep);
            updateProgressBar(currentIndex - 1); // ← Tambahkan ini
        }
    }
</script>


@endsection
