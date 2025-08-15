{{-- modal Edit Role --}}
<div class="modal fade" id="editbankModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit bank</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormbank" action="{{ route('bank.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="bankid_edit" name="bankid_edit">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label>Nama bank</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama bank" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Kode bank</label>
                                <input type="text" class="form-control" id="kode_edit" name="kode_edit" placeholder="Kode bank" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Perbarui</button> <!-- Submit button -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>