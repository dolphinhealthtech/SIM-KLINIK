{{-- modal Edit Perawatan Tindakan --}}
<div class="modal fade" id="editperawatan_tindakanModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Perawatan Tindakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormperawatan_tindakan" action="{{ route('perawatan_tindakan.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="perawatan_tindakanid_edit" name="perawatan_tindakanid_edit">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama Perawatan Dan Tindakan</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama Perawatan Tindakan" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Kategori Perawatan Dan Tindakan</label>
                                <select id="kategori_edit" name="kategori_edit" class="form-control" required>
                                    @foreach($kategori as $data_kategori_edit)
                                        <option value="{{ $data_kategori_edit->id }}">{{ $data_kategori_edit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tarif Dokter</label>
                                <input type="text" class="form-control rupiah" id="tarif_dokter_edit" name="tarif_dokter_edit" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tarif Perawat</label>
                                <input type="text" class="form-control rupiah" id="tarif_perawat_edit" name="tarif_perawat_edit" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Total Tarif</label>
                                <input type="text" class="form-control rupiah" id="tarif_total_edit" name="tarif_total_edit" readonly required>
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
