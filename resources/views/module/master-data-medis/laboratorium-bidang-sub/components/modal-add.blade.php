{{-- modal Add Laboratorium Bidang Sub --}}
<div class="modal fade" id="addhtt_sub_pemeriksaanModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Medis htt_sub_pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormhtt_sub_pemeriksaan" action="{{ route('laboratorium_bidang_sub.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <input type="hidden" id="laboratorium_bidang_sub_id" name="laboratorium_bidang_sub_id" value="{{ $laboratorium_bidang->id }}" >
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bagian Pemeriksaan</label>
                                <input type="text" class="form-control" id="nama_sub_pemeriksaan" name="nama_sub_pemeriksaan" value="{{ $laboratorium_bidang->nama }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bagian Pemeriksaan</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Pemeriksaan" required>
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
