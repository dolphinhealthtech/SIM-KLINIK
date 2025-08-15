{{-- modal Add Sub Pemeriksaan HTT --}}
<div class="modal fade" id="addhtt_sub_pemeriksaanModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Pemeriksaan Sub Head To Toe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormhtt_sub_pemeriksaan" action="{{ route('htt_sub_pemeriksaan.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <input type="hidden" id="htt_sub_pemeriksaan_id" name="htt_sub_pemeriksaan_id" value="{{ $htt_pemeriksaan->id }}" >
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bagian Pemeriksaan</label>
                                <input type="text" class="form-control" id="nama_sub_pemeriksaan" name="nama_sub_pemeriksaan" value="{{ $htt_pemeriksaan->nama_pemeriksaan }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bagian Sub Pemeriksaan</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Sub Pemeriksaan" required>
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
