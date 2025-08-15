{{-- modal Edit Nama Makanan --}}
<div class="modal fade" id="editnama_makananModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Nama Makanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormnama_makanan" action="{{ route('nama_makanan.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="nama_makananid_edit" name="nama_makananid_edit">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama Makanan</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama nama_makanan" required>
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
