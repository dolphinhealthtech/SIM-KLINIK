{{-- modal Add ICD10 --}}
<div class="modal fade" id="addicd10Modal" tabindex="-1" role="dialog" aria-labelledby="addicd10Label">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addicd10Label">Tambah Master ICD 10</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormicd10" action="{{ route('icd10.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama ICD 10</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama ICD 10" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="kode">Kode ICD 10 </label>
                                <input type="text" class="form-control" id="kode" name="kode" placeholder="Kode ICD 10" required>
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
