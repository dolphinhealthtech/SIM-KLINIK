{{-- modal Edit Radiologi Pemeriksaan --}}
<div class="modal fade" id="editpemeriksaan_httModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Radiologi Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormpemeriksaan_htt" action="{{ route('radiologi_pemeriksaan.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="bidang_labotorium_edit" name="bidang_labotorium_edit">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama Radiologi Pemeriksaan</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama Kategori Perawatan" required>
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
