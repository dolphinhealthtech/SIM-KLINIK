
    <div class="modal fade" id="bpjsModal" tabindex="-1" role="dialog" aria-labelledby="bpjsModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bpjsModalLabel">Antrian BPJS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="bpjsForm" action="{{ route('monitor.add.bpjs') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="nikNokaInput">Masukkan NIK atau No. Kartu BPJS</label>
                                    <input type="text" class="form-control" id="nikNokaInput" name="nikNokaInput"
                                        placeholder="Masukkan NIK atau No. Kartu BPJS" required>
                                    <small id="nikNokaFeedback" class="form-text text-muted"></small>
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
                                    <label>Poli</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="poli_id" name="poli_id">
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
                                    <select class="form-control select2bs4" style="width: 100%;" id="dokter_id" name="dokter_id">
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
