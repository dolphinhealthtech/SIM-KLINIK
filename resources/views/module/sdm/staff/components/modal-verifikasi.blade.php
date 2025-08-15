{{-- modal Verifikasi Dokter --}}
    <div class="modal fade" id="lengkapiModal" tabindex="-1" role="dialog" aria-labelledby="lengkapiModalLabel">
        <div class="modal-dialog modal-xl">
            <form id="lengkapiFormdokter" action="{{ route('staff.verifikasi') }}" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="lengkapiModalLabel">Verifikasi Data </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="dokterid_verifikasi" name="dokterid_verifikasi">
                        <div class="form-group">
                            <label><strong>Pendidikan</strong></label>
                            <hr>
                            <div class="pendidikan-list col-12"></div>
                        </div>

                        <br>

                        <div class="form-group">
                            <label><strong>Sertifikat Pelatihan Khusus</strong></label>
                            <hr>
                            <div id="pelatihan-container" class="col-12"></div>
                            <div class="text-center">
                                <button type="button" class="btn btn-sm btn-success mt-2" id="tambah-pelatihan">+ Tambah Pelatihan</button>
                            </div>
                        </div>

                        <br>

                        <div>
                            <label><strong>Informasi Bank</strong></label>
                            <hr>
                            <div class="col-12">
                                <div class="row align-items-end mb-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nama Bank</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="nama_bank"
                                                name="nama_bank">
                                                <option value="" disabled selected>Nama</option>
                                                @foreach ($bank as $bankdata)
                                                    <option value="{{ $bankdata->nama }}">{{ $bankdata->nama }} - {{ $bankdata->kode }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>No Rekening</label>
                                            <input type="text" class="form-control" id="norek" name="norek">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Cabang</label>
                                            <input type="text" class="form-control" id="cabang_bank" name="cabang_bank" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>