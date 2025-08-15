    {{-- modal Edit Role --}}
    <div class="modal fade" id="editpendidikanModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit pendidikan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editFormpendidikan" action="{{ route('pendidikan.update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="pendidikanid_edit" name="pendidikanid_edit">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nama pendidikan</label>
                                    <input type="text" class="form-control" id="nama_edit" name="nama_edit"
                                        placeholder="Nama pendidikan" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>kode pendidikan</label>
                                    <input type="text" class="form-control" id="kode_edit" name="kode_edit"
                                        placeholder="kode pendidikan" required>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Uratan pendidikan</label>
                                    <input type="number" class="form-control" id="urutan_edit" name="urutan_edit"
                                        placeholder="urutan pendidikan" required>
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
