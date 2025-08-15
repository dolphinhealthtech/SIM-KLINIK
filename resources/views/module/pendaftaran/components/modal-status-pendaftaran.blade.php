<div class="modal fade" id="rekapModal" tabindex="-1" role="dialog" aria-labelledby="rekapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="rekapModalLabel"></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Nama Dokter</th>
                                <th>Poli</th>
                                <th>Pasien Sedang Menunggu </th>
                                <th>Pasien Sudah Dilayani </th>
                                <th>No Antrian</th>
                                <th>Status Pemeriksaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapPerDokter as $data)
                                <tr>
                                    <td>{{ $data->dokter->namauser->name ?? '-' }}</td>
                                    <td>{{ $data->poli->nama ?? '-' }}</td>
                                    <td>{{ $data->menunggu }}</td>
                                    <td>{{ $data->dilayani }}</td>
                                    <td>{{ $data->no_antrian }}</td>
                                    <td>
                                        @if($data->status_periksa == 1)
                                            <span class="badge rounded-pill bg-warning text-dark">
                                                <i class="fas fa-user-nurse me-1"></i> Menunggu pemeriksaan perawat
                                            </span>
                                            <br>
                                            <i class="fa-solid fa-circle" style="color: #FFD43B;"></i>
                                            <i class="fa-regular fa-circle" style="color: #000000;"></i>
                                            <i class="fa-regular fa-circle" style="color: #000000;"></i>
                                        @elseif($data->status_periksa == 2)
                                            <span class="badge rounded-pill bg-primary">
                                                <i class="fas fa-user-md me-1"></i> Sedang diperiksa dokter
                                            </span>
                                            <br>
                                            <i class="fa-regular fa-circle" style="color: #000000;"></i>
                                            <i class="fa-solid fa-circle" style="color: #007bff;"></i> {{-- biru sesuai badge --}}
                                            <i class="fa-regular fa-circle" style="color: #000000;"></i>
                                        @elseif($data->status_periksa == 3)
                                            <span class="badge rounded-pill bg-danger">
                                                <i class="fas fa-ban me-1"></i> Tidak ada pasien
                                            </span>
                                            <br>
                                            <i class="fa-regular fa-circle" style="color: #000000;"></i>
                                            <i class="fa-regular fa-circle" style="color: #000000;"></i>
                                            <i class="fa-solid fa-circle" style="color: #dc3545;"></i> {{-- merah sesuai badge --}}
                                        @else
                                            <span class="badge rounded-pill bg-secondary">
                                                <i class="fas fa-question-circle me-1"></i> Status tidak diketahui
                                            </span>
                                            <br>
                                            <i class="fa-regular fa-circle" style="color: #000000;"></i>
                                            <i class="fa-regular fa-circle" style="color: #000000;"></i>
                                            <i class="fa-regular fa-circle" style="color: #000000;"></i>
                                        @endif

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
