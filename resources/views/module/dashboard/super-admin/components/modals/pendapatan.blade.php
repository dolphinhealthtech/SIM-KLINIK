<div class="modal fade" id="modalPendapatan" tabindex="-1" role="dialog" aria-labelledby="modalPendapatanLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white" id="modalPendapatanLabel">
                    <i class="fas fa-money-bill-wave mr-2"></i>Rincian Pendapatan Hari Ini
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Row untuk 2 kategori -->
                <div class="row">
                    <!-- KIRI: Penghasilan Jasa -->
                    <div class="col-md-6 mb-3">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white text-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-hand-holding-medical mr-2"></i>
                                    Pendapatan Jasa dan Tindakan
                                </h6>
                            </div>
                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                <h4 class="text-success font-weight-bold">
                                    Rp {{ number_format($totalJasaGabungan, 0, ',', '.') }}
                                </h4>
                                <small class="text-muted">Pendapatan dari Tindakan Medis, Administrasi & Materai</small>
                            </div>
                        </div>
                    </div>

                    <!-- KANAN: Penghasilan Obat -->
                    <div class="col-md-6 mb-3">
                        <div class="card border-info h-100">
                            <div class="card-header bg-info text-white text-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-pills mr-2"></i>
                                    Pendapatan Obat
                                </h6>
                            </div>
                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                <h4 class="text-info font-weight-bold">
                                    Rp {{ number_format($totalObat, 0, ',', '.') }}
                                </h4>
                                <small class="text-muted">Pendapatan dari Penjualan Obat</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <hr class="my-4">

                <!-- Total Pendapatan -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Total Pendapatan Hari Ini
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <h1 class="text-warning font-weight-bold">
                                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                </h1>
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Breakdown -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-light">
                            <div class="row text-center">
                                <div class="col-md-3 col-6 mb-2">
                                    <strong>Jasa dan Tindakan</strong><br>
                                    <span class="text-success">{{ $persenJasa }}%</span>
                                </div>
                                <div class="col-md-3 col-6 mb-2">
                                    <strong>Obat</strong><br>
                                    <span class="text-info">{{ $persenObat }}%</span>
                                </div>
                                <div class="col-md-6 col-12">
                                    <strong>Total Kategori</strong><br>
                                    <span class="text-warning">{{ $kategoriAktif }} kategori</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
