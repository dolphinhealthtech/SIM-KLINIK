<div class="modal fade" id="deletebatalModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormbatal" action="{{ route('pendaftaran.batal') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Batal Pendaftaran Pasien</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="batalid_delete" name="batalid_delete">
                    <div id="deleteTextbatal"></div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="alasanpembatalan">Alasan Pembatalan</label>
                            <input type="text" class="form-control" id="alasanpembatalan" name="alasanpembatalan" placeholder="Masukkan lasan pembatalan" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>
