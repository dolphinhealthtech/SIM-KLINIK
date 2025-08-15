{{-- modal Add ICD9 --}}
<div class="modal fade" id="addicd9Modal" tabindex="-1" role="dialog" aria-labelledby="addicd9Label">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addicd9Label">Tambah Data ICD 9</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormicd9" action="{{ route('icd9.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama ICD 9</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama ICD 9" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="kode">Kode ICD 9</label>
                                <input type="text" class="form-control" id="kode" name="kode" placeholder="Kode ICD 9" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button> <!-- Submit button -->
            </div>
            </form>
        </div>
    </div>
</div>
