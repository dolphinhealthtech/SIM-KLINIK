{{-- modal Edit ICD9 --}}
<div class="modal fade" id="editicd9Moda" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data ICD 9</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormicd9" action="{{ route('icd9.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="icd9id_edit" name="icd9id_edit">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama ICD 9</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama ICD 9" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="kode_edit">Kode ICD 9</label>
                                <input type="text" class="form-control" id="kode_edit" name="kode_edit" placeholder="Kode ICD 9" required>
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
