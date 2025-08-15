<div class="modal fade" id="EditModal" tabindex="-1"
    aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Edit Data Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pasien.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <style>
                                .alert {
                                    padding: 15px;
                                    margin-bottom: 20px;
                                    border: 1px solid transparent;
                                    border-radius: 4px;
                                }
                                .alert-danger {
                                    color: #721c24;
                                    background-color: #f8d7da;
                                    border-color: #f5c6cb;
                                }
                            </style>
                            <div class="text-center col-sm-6">
                                <div class="form-group">
                                    <div id="bpjs_error_edit_1" class="alert alert-warning" style="display: none;"></div>
                                </div>
                            </div>
                            <div class="text-center col-sm-6">
                                <div class="form-group">
                                    <div id="bpjs_error_edit" class="alert alert-danger" style="display: none;"></div>
                                </div>
                            </div>
                        <div class="col-md-3 d-flex justify-content-center">
                            <div class="position-relative text-center">
                                <!-- Input Gambar -->
                                <input type="file" id="profileImageInput_edit" name="profile_image_edit" accept="image/*" class="d-none" onchange="previewImage_edit(event)">

                                <!-- Bingkai Gambar dengan Rasio 3:4 -->
                                <label for="profileImageInput_edit" class="d-block" style="cursor: pointer;">
                                    <div style="width: 100%;border: 2px solid #ccc; max-width: 180px; aspect-ratio: 3 / 4; overflow: hidden; border-radius: 10px;  background: #f0f0f0; display: flex; align-items: center; margin-top: 75px;  justify-content: center;">
                                        <img id="profileImage_edit" class="img-fluid rounded"
                                            alt="User profile picture" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h3 style="text-align: left;">BIODATA</h3>
                            <div class="col-2" style="padding-left: 0; text-align: left;">
                                <hr style="width: 85%; margin-left: 0;">
                            </div>
                            <div class="row">

                                <input type="hidden" class="form-control" placeholder="nomor_rm" id="nomor_rm_edit" name="nomor_rm_edit" value="{{ old('nomor_rm_edit') }}">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" class="form-control" placeholder="nama"
                                            id="nama_edit" name="nama_edit" readonly value="{{ old('nama_edit') }}" required>
                                    </div>
                                    @error('nama_edit')
                                        <div style="color: red;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Nomor NIK</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" id="nik_edit" name="nik_edit"required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Tempat & Tanggal Lahir</label>
                                        <div class="input-group">
                                            <!-- Input Tempat Lahir -->
                                            <input type="text" class="form-control" id="tempat_lahir_edit"
                                                name="tempat_lahir_edit" value="{{ old('tempat_lahir_edit') }}" required
                                                placeholder="Tempat" style="width: 50%;">

                                            <!-- Input Tanggal Lahir -->
                                            <input type="date" class="form-control" id="tgllahir_edit"
                                                name="tgllahir_edit" value="{{ old('tgllahir_edit') }}" required
                                                style="width: 50%;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Provinsi</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="provinsi_edit"
                                            name="provinsi_edit" value="{{ old('provinsi_edit') }}" required>
                                            <option value="" disabled selected>Provinsi</option>
                                            @foreach ($provinsi as $provinsidata)
                                                <option value="{{ $provinsidata->kode }}">{{ $provinsidata->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Kota/Kabupaten</label>
                                        <select class="form-control select2bs4" style="width: 100%;"
                                            id="kabupaten_edit" name="kabupaten_edit" value="{{ old('kabupaten_edit') }}" required>
                                            <option value="" disabled selected>Kota/Kabupaten</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Kecamatan</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="kecamatan_edit"
                                            name="kecamatan_edit" value="{{ old('kecamatan_edit') }}" required>
                                            <option value="" disabled selected>Kecamatan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Desa/Kelurahan</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="desa_edit"
                                            name="desa_edit" value="{{ old('desa_edit') }}" required>
                                            <option value="" disabled selected>Desa/Kelurahan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label>RT</label>
                                        <input type="text" class="form-control" placeholder="001" id="rt_edit"
                                            name="rt_edit" value="{{ old('rt_edit') }}" required>
                                    </div>
                                    @error('rt_edit')
                                        <div style="color: red;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label>RW</label>
                                        <input type="text" class="form-control" placeholder="002" id="rw_edit"
                                            name="rw_edit" value="{{ old('rw_edit') }}" required>
                                    </div>
                                    @error('rw')
                                        <div style="color: red;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Kode Pos</label>
                                        <input type="text" class="form-control" id="kode_pos_edit" name="kode_pos_edit"
                                            value="{{ old('kode_pos_edit') }}" required>
                                    </div>
                                    @error('kode_pos_edit')
                                        <div style="color: red;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea class="form-control" placeholder="Masukkan alamat" id="alamat_edit" name="alamat_edit" rows="1" value="{{ old('alamat_edit') }}" required></textarea>
                                    </div>
                                    @error('alamat')
                                        <div style="color: red;">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group text-center">
                                        <label>Nomor BPJS & Satusehat</label>
                                        <div class="input-group">
                                            <!-- Input Tempat Lahir -->
                                            <input type="text" class="form-control text-center" id="noka_edit"
                                                name="noka_edit" value="{{ old('noka_edit') }}"
                                                placeholder="Noka BPJS" style="width: 50%;">

                                            <!-- Input Tanggal Lahir -->
                                            <input type="text" class="form-control text-center" id="noihs_edit"
                                            name="noihs_edit" value="{{ old('noihs_edit') }}"
                                            placeholder="Noka SatuSehat" style="width: 50%;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group text-center">
                                        <label>Jenis & Kelas BPJS</label>
                                        <div class="input-group">

                                            <input type="text" class="form-control text-center" id="jenis_kartu_edit"
                                                name="jenis_kartu_edit" value="{{ old('jenis_kartu_edit') }}"
                                                placeholder="Jenis Peserta BPJS" style="width: 50%;">

                                            <input type="text" class="form-control text-center" id="kelas_edit"
                                            name="kelas_edit" value="{{ old('kelas_edit') }}"
                                            placeholder="Kelas BPJS" style="width: 50%;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group text-center">
                                        <label>Faskes & Masa Berlaku Kartu BPJS</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" id="provide_edit"
                                            name="provide_edit" value="{{ old('provide_edit') }}"
                                            placeholder="Provide BPJS" style="width: 50%;">

                                            <input type="date" class="form-control text-center" id="tgl_exp_bpjs_edit"
                                                name="tgl_exp_bpjs_edit" value="{{ old('tgl_exp_bpjs_edit') }}"
                                                placeholder="Masa Berlaku Kartu BPJS" style="width: 50%;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="seks_edit"
                                            name="seks_edit" value="{{ old('seks_edit') }}" required>
                                            <option value="" disabled selected>Jenis Kelamin</option>
                                            @foreach ($kelamin as $kelamindata)
                                                <option value="{{ $kelamindata->kode }}">{{ $kelamindata->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Golongan Darah</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="goldar_edit"
                                            name="goldar_edit" value="{{ old('goldar_edit') }}" required>
                                            <option value="" disabled selected>--- pilih ---</option>
                                            @foreach ($goldar as $goldardata)
                                                <option value="{{ $goldardata->id }}">
                                                    @if ($goldardata->resus == "null")
                                                        {{ $goldardata->nama }}
                                                    @else
                                                        {{ $goldardata->nama . $goldardata->resus }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('goldar')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Status Pernikahan</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="pernikahan_edit"
                                            name="pernikahan_edit" value="{{ old('pernikahan_edit') }}" required>
                                            <option value="" disabled selected>--- pilih ---</option>
                                            @foreach ($pernikahan as $pernikahandata)
                                                <option value="{{ $pernikahandata->id }}">{{ $pernikahandata->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('pernikahan')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Kewarganegaraan</label>
                                        <select class="form-control select2bs4" style="width: 100%;"
                                            id="kewarganegaraan_edit" name="kewarganegaraan_edit" required>
                                            <option value=""
                                                {{ old('kewarganegaraan_edit') == '' ? 'selected' : '' }}>--- pilih ---
                                            </option>
                                            <option value="wni"
                                                {{ old('kewarganegaraan_edit') == 'wni' ? 'selected' : '' }}>Warga Negara
                                                Indonesia</option>
                                            <option value="wna"
                                                {{ old('kewarganegaraan_edit') == 'wna' ? 'selected' : '' }}>Warga Negara
                                                Asing</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Agama</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="agama_edit"
                                            name="agama_edit" value="{{ old('agama_edit') }}" required>
                                            <option value="" disabled selected>--- pilih ---</option>
                                            @foreach ($agama as $agamadata)
                                                <option value="{{ $agamadata->id }}">{{ $agamadata->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('agama')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Pendidikan</label>
                                        <select class="form-control select2bs4" style="width: 100%;"
                                            id="pendidikan_edit" name="pendidikan_edit" value="{{ old('pendidikan_edit') }}" required>
                                            <option value="" disabled selected>--- pilih ---</option>
                                            @foreach ($pendidikan as $pendidikandata)
                                            <option value="{{ $pendidikandata->kode }}">{{ $pendidikandata->nama }}</option>
                                        @endforeach
                                        </select>
                                        @error('pendidikan')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Pekerja</label>
                                        <select class="form-control select2bs4" style="width: 100%;"
                                            id="status_kerja_edit" name="status_kerja_edit" value="{{ old('status_kerja_edit') }}" required>
                                            <option value="" disabled selected>Pilih Pekerjaan</option>
                                            @foreach ($pekerjaan as $pekerjaandata)
                                                <option value="{{ $pekerjaandata->id }}">{{ $pekerjaandata->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Telepon</label>
                                        <input type="text" class="form-control" id="telepon_edit" name="telepon_edit" value="{{ old('telepon_edit') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Suku</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="suku_edit"
                                            name="suku_edit" value="{{ old('suku_edit') }}" required>
                                            <option value="" disabled selected>Pilih Suku</option>
                                            @foreach ($suku as $sukudata)
                                                <option value="{{ $sukudata->id }}">{{ $sukudata->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('suku')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bangsa</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="bangsa_edit"
                                            name="bangsa_edit" value="{{ old('bangsa_edit') }}">
                                            <option value="" disabled selected>Pilih Bangsa</option>
                                            @foreach ($bangsa as $bangsadata)
                                                <option value="{{ $bangsadata->id }}">{{ $bangsadata->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('bangsa')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bahasa</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="bahasa_edit"
                                            name="bahasa_edit" value="{{ old('bahasa_edit') }}">
                                            <option value="" disabled selected> Pilih Bahasa </option>
                                            @foreach ($bahasa as $bahasadata)
                                                <option value="{{ $bahasadata->id }}">{{ $bahasadata->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('bahasa')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="Email" class="form-control" id="email_edit" name="email_edit" value="{{ old('email_edit') }}" required>
                                    </div>
                                </div>
                                    {{-- Checkbox Penjamin 2 --}}
                                <div class="col-sm-2 d-flex align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label" for="aktif_penjamin_2_edit">&nbsp;</label>
                                        <input type="checkbox" class="form-check-input" id="aktif_penjamin_2_edit">
                                        <label class="form-check-label" for="aktif_penjamin_2_edit">Penjamin 2</label>
                                    </div>
                                </div>

                                {{-- Select Penjamin 2 --}}
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="penjamin_2_edit">Penjamin 2</label>
                                        <select class="form-control" name="penjamin_2_edit" id="penjamin_2_edit" disabled>
                                            <option value="">-- Pilih Penjamin 2 --</option>
                                            @foreach($asuransi as $asuransi2_edit)
                                                <option value="{{ $asuransi2_edit->nama }}">{{ $asuransi2_edit->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Input Penjamin 2 --}}
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="penjamin_2_info_edit">No. Penjamin 2</label>
                                        <input type="text" class="form-control" name="penjamin_2_info_edit" id="penjamin_2_info_edit" placeholder="No. Penjamin 2" disabled>
                                    </div>
                                </div>

                                {{-- Checkbox Penjamin 3 --}}
                                <div class="col-sm-2 d-flex align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label" for="aktif_penjamin_3_edit">&nbsp;</label>
                                        <input type="checkbox" class="form-check-input" id="aktif_penjamin_3_edit">
                                        <label class="form-check-label" for="aktif_penjamin_3_edit">Penjamin 3</label>
                                    </div>
                                </div>

                                {{-- Select Penjamin 3 --}}
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="penjamin_3_edit">Penjamin 3</label>
                                        <select class="form-control" name="penjamin_3_edit" id="penjamin_3_edit" disabled>
                                            <option value="">-- Pilih Penjamin 3 --</option>
                                            @foreach($asuransi as $asuransi_3_edit)
                                                <option value="{{ $asuransi_3_edit->nama }}">{{ $asuransi_3_edit->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Input Penjamin 3 --}}
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="penjamin_3_info_edit">No. Penjamin 3</label>
                                        <input type="text" class="form-control" name="penjamin_3_info_edit" id="penjamin_3_info_edit" placeholder="No. Penjamin 3" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="user_edit" name="user_edit" value="{{ old('user_edit') }}">
                    <input type="hidden" id="kodeprovide_edit" name="kodeprovide_edit" value="{{ old('kodeprovide_edit') }}">
                    <input type="hidden" id="hubungan_keluarga_edit" name="hubungan_keluarga_edit" value="{{ old('hubungan_keluarga_edit') }}">
                    <input type="hidden" id="userinput" name="userinput" value="{{ auth()->user()->name }}">
                    <input type="hidden" id="userinputid" name="userinputid" value="{{ auth()->user()->id }}">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" onclick="handleClick_edit()" id="syncButton" title="Ambil NIK"><i id="syncIcon_edit" class="fas fa-sync-alt"></i>Cek Nik / Noka</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="sumbit" class="btn btn-primary">Simpan</button>
            </div>
                </form>
        </div>
    </div>
</div>
