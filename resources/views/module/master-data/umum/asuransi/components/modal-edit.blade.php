{{-- modal Edit Role --}}
<div class="modal fade" id="editasuransiModa" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit asuransi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormasuransi" action="{{ route('asuransi.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="asuransiid_edit" name="asuransiid_edit">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Nama asuransi</label>
                                <input type="text" class="form-control" id="nama_edit" name="nama_edit" placeholder="Nama asuransi" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Kode asuransi</label>
                                <input type="text" class="form-control" id="kode_edit" name="kode_edit" placeholder="Kode asuransi" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Jenis Asuransi</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="jenis_edit" name="jenis_edit">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="Asuransi">Asuransi</option>
                                    <option value="Perusahaan Swasta">Perusahaan Swasta</option>
                                    <option value="Perusahaan Pemerintah/BUMN/BUMD">Perusahaan Pemerintah/BUMN/BUMD</option>
                                    <option value="Institusi Pemerintah">Institusi Pemerintah</option>
                                    <option value="Yayasan Sosial">Yayasan Sosial</option>
                                    <option value="Lain Lain">Lain Lain</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Verifikai Pasien</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="verifikasi_edit" name="verifikasi_edit">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="Prosedural (Managed)">Prosedural (Managed)</option>
                                    <option value="Bebas (Un-Managed)">Bebas (Un-Managed)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Filter Obat Ditanggung</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="filter_obat_edit" name="filter_obat_edit">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Berlaku Mulai</label>
                                <input type="date" class="form-control" id="tgl_mulai_edit" name="tgl_mulai_edit">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Berlaku Hingga</label>
                                <input type="date" class="form-control" id="tgl_akhir_edit" name="tgl_akhir_edit">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Alamat Asuransi</label>
                                <input type="text" class="form-control" id="alamat_edit" name="alamat_edit">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>No Telp Asuransi</label>
                                <input type="text" class="form-control" id="no_telp_asuransi_edit" name="no_telp_asuransi_edit">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Faksimil</label>
                                <input type="text" class="form-control" id="faksimil_asuransi_edit" name="faksimil_asuransi_edit">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Contact Person</label>
                                <input type="text" class="form-control" id="pic_edit" name="pic_edit">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Telp Contact Person</label>
                                <input type="text" class="form-control" id="no_telp_pic_edit" name="no_telp_pic_edit">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Jabatan Contact Person</label>
                                <input type="text" class="form-control" id="jabatan_pic_edit" name="jabatan_pic_edit">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bank Akun</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="bank_edit" name="bank_edit">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach ($bank as $bankdata_edit)
                                        <option value="{{ $bankdata_edit->nama }}">{{ $bankdata_edit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>No Rekening</label>
                                <input type="text" class="form-control" id="no_rekening_edit" name="no_rekening_edit">
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