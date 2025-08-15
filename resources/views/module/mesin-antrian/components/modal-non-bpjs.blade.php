<div class="modal fade" id="nonBpjsModal" tabindex="-1" role="dialog" aria-labelledby="nonBpjsModalLabel" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nonBpjsModalLabel">Daftar Antrian Non-BPJS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="nonBpjsForm" action="{{ route('pendaftaran-online.add.nobpjs') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="nikNnamaInput">Masukkan NIK atau Nama</label>
                                <input type="text" class="form-control" id="nikNnamaInput" name="nikNnamaInput" placeholder="Masukkan NIK atau Nama" required>
                                <small id="nikNnamaFeedback" class="form-text text-muted"></small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Jadwal Kunjungan</label>
                                <input type="text" class="form-control datetimepicker-input" id="tanggal_kunjungan_no" name="tanggal_kunjungan_no" data-toggle="datetimepicker" data-target="#tanggal_kunjungan_no"/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Poli</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="poli_id_no" name="poli_id_no">
                                    <option value="" disabled selected>Pilih Poli</option>
                                    @foreach ($poli as $polidata)
                                        <option value="{{ $polidata->id }}">{{ $polidata->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Dokter</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="dokter_id_no" name="dokter_id_no">
                                    <option value="" disabled selected>Pilih Dokter</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="sumbit" class="btn btn-primary">Selanjutnya</button>
            </div>
                </form>
        </div>
    </div>
</div>
