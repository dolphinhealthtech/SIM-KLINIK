{{-- modal Add Role --}}
<div class="modal fade" id="addasuransiModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah asuransi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormasuransi" action="{{ route('asuransi.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Nama asuransi</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama asuransi" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Kode asuransi</label>
                                <input type="text" class="form-control" id="kode" name="kode" placeholder="Kode asuransi" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Jenis Asuransi</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="jenis" name="jenis">
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
                                <select class="form-control select2bs4" style="width: 100%;" id="verifikasi" name="verifikasi">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="Prosedural (Managed)">Prosedural (Managed)</option>
                                    <option value="Bebas (Un-Managed)">Bebas (Un-Managed)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Filter Obat Ditanggung</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="filter_obat" name="filter_obat">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Berlaku Mulai</label>
                                <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Berlaku Hingga</label>
                                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Alamat Asuransi</label>
                                <input type="text" class="form-control" id="alamat" name="alamat">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>No Telp Asuransi</label>
                                <input type="text" class="form-control" id="no_telp_asuransi" name="no_telp_asuransi">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Faksimil</label>
                                <input type="text" class="form-control" id="faksimil_asuransi" name="faksimil_asuransi">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Contact Person</label>
                                <input type="text" class="form-control" id="pic" name="pic">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Telp Contact Person</label>
                                <input type="text" class="form-control" id="no_telp_pic" name="no_telp_pic">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Jabatan Contact Person</label>
                                <input type="text" class="form-control" id="jabatan_pic" name="jabatan_pic">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Bank Akun</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="bank" name="bank">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach ($bank as $bankdata)
                                        <option value="{{ $bankdata->nama }}">{{ $bankdata->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>No Rekening</label>
                                <input type="text" class="form-control" id="no_rekening" name="no_rekening">
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