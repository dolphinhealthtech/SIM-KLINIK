{{-- modal Add Perawatan Tindakan --}}
<div class="modal fade" id="addperawatan_tindakanModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Perawatan Tindakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormperawatan_tindakan" action="{{ route('perawatan_tindakan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" class="form-control" id="kode" name="kode">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama Perawatan Dan Tindakan</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Kategori Perawatan" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Kategori Perawatan Dan Tindakan</label>
                                <select id="kategori" name="kategori" class="form-control select2bs4" required>
                                    <option value="" disabled selected >Pilih Kategori</option>
                                    @foreach($kategori as $data_kategori)
                                        <option value="{{ $data_kategori->id }}">{{ $data_kategori->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tarif Dokter</label>
                                <input type="text" class="form-control rupiah" id="tarif_dokter" name="tarif_dokter" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tarif Perawat</label>
                                <input type="text" class="form-control rupiah" id="tarif_perawat" name="tarif_perawat" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Total Tarif</label>
                                <input type="text" class="form-control rupiah" id="tarif_total" name="tarif_total" readonly required>
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
