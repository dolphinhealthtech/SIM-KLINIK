{{-- modal Add Role --}}
<div class="modal fade" id="addloketModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah loket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormloket" action="{{ route('loket.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama loket</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama loket" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Poli</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="poli_id" name="poli_id">
                                    <option value="" disabled selected>Pilih Poli</option>
                                    @foreach ($poli as $polidata)
                                        <option value="{{ $polidata->id }}">{{ $polidata->nama }}</option>
                                    @endforeach
                                </select>
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