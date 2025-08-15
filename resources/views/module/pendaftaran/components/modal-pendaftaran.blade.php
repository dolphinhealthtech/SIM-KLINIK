<div class="modal fade" id="addreispasienModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Daftarkan Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormsuku" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Pasien</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="pasien" name="pasien" value="{{ old('pasien') }}">
                                    <option value="" disabled selected>Pilih Pasien</option>
                                    @foreach ($pasiens as $pasiendata)
                                        <option value="{{ $pasiendata->id }}">{{ $pasiendata->nama }} - {{ $pasiendata->no_rm }}</option>
                                    @endforeach
                                </select>
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Jadwal Kunjungan</label>
                                <input type="text" class="form-control datetimepicker-input" id="tanggal_kunjungan" name="tanggal_kunjungan" data-toggle="datetimepicker" data-target="#tanggal_kunjungan"/>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Dokter</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="dokter_id" name="dokter_id">
                                    <option value="" disabled selected>Pilih Dokter</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Penjamin Pasien</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="penjamin_id" name="penjamin_id">
                                    @foreach ($penjamin as $penjamindata)
                                        <option value="{{ $penjamindata->id }}">{{ $penjamindata->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
            </form>
        </div>
    </div>
</div>
