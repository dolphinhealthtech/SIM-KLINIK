<!-- modal Add Alergi ICD10 -->
<div class="modal fade" id="addalergiModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah ICD 10</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="singForicd10" action="{{ route('icd10.singkron') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="kode_icd">Kode ICD-10</label>
                                <input type="text" class="form-control" id="kode_icd" name="kode_icd" placeholder="Masukkan Kode ICD-10" required>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div id="loadingContainer" class="progress mt-2" style="display: none; height: 25px;">
                                <div id="loadingBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 0%;">
                                    <span id="loadingText" class="w-100 d-block text-center font-weight-bold text-white">0%</span>
                                </div>
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
