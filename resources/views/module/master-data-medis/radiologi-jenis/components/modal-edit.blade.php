{{-- modal Edit Radiologi Jenis --}}
<div class="modal fade" id="editradiologi_jenisModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit jenis radiologi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormradiologi_jenis" action="{{ route('radiologi_jenis.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="radiologi_jenisid_edit" name="radiologi_jenisid_edit">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Radiologi Jenis</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama Radiologi Jenis" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
