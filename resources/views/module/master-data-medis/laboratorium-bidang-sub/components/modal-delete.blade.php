{{-- modal Delete Laboratorium Bidang Sub --}}
<div class="modal fade" id="deletehtt_sub_pemeriksaanModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormhtt_sub_pemeriksaan" action="{{ route('laboratorium_bidang_sub.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus htt_sub_pemeriksaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="laboratorium_bidang_subid_delete" name="laboratorium_bidang_subid_delete">
                    <div id="deleteTexthtt_sub_pemeriksaan"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>
