<div class="modal fade" id="dokterAktifModal" tabindex="-1" role="dialog" aria-labelledby="dokterAktifModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="dokterAktifModalLabel">Dokter Aktif Hari Ini</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Nama Dokter</th>
                            <th class="text-center">Poli / Spesialisasi</th>
                            <th class="text-center">Jam Mulai</th>
                            <th class="text-center">Jam Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dokterHariIni as $dokter)
                            <tr>
                                <td class="text-center">{{ $dokter->nama }}</td>
                                <td class="text-center">{{ $dokter->spesialisasi }}</td>
                                <td class="text-center">{{ $dokter->start }}</td>
                                <td class="text-center">{{ $dokter->end }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada dokter aktif hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
