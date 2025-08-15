<div class="row">
    <!-- Reusable Card Template -->
    <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
        <div class="card text-white bg-success shadow">
            <div class="card-body position-relative">
                <div class="text-center">
                    <h3 class="font-weight-bold">{{ $datadokter }}</h3>
                    <p class="mb-0">Dokter Aktif</p>
                </div>
                <div class="position-absolute" style="top: 15px; right: 15px;">
                    <i class="fas fa-user-md fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer text-white clearfix small z-1"data-toggle="modal" data-target="#dokterAktifModal">
                <a href="#" class="text-white">
                    Lihat Dokter <i class="fas fa-arrow-circle-right float-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
        <div class="card text-white bg-info shadow">
            <div class="card-body position-relative">
                <div class="text-center">
                    <h3 class="font-weight-bold">{{ $datapasien }}</h3>
                    <p class="mb-0">Jumlah Pasien Terdaftar</p>
                </div>
                <div class="position-absolute" style="top: 15px; right: 15px;">
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer text-white clearfix small z-1" style="visibility: hidden;">...</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
        <div class="card text-white bg-warning shadow">
            <div class="card-body position-relative">
                <div class="text-center">
                    <h3 class="font-weight-bold">{{ $datakunjungan }}</h3>
                    <p class="mb-0">Kunjungan Hari Ini</p>
                </div>
                <div class="position-absolute" style="top: 15px; right: 15px;">
                    <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer text-white clearfix small z-1" style="visibility: hidden;">...</div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-sm-12 mb-4">
        <div class="card text-white bg-danger shadow">
            <div class="card-body position-relative">
                <div class="text-center">
                    <h3 class="font-weight-bold" id="pendapatan">Rp0</h3>
                    <p class="mb-0">Pendapatan Hari Ini</p>
                </div>
                <div class="position-absolute" style="top: 15px; right: 15px;">
                    <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer text-white clearfix small z-1" data-toggle="modal" data-target="#modalPendapatan">
                <a href="#" class="text-white">
                    Rincian <i class="fas fa-arrow-circle-right float-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
