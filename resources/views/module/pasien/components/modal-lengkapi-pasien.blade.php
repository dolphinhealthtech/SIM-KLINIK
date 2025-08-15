<div class="modal fade" id="lengkapiModal" tabindex="-1"
        aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Lengkapi Data Pasien</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formLengkapi" action="{{ route('pasien.verifikasi') }}" method="POST" enctype="multipart/form-data">
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
                                        <div id="bpjs_error1" class="alert alert-warning" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="text-center col-sm-6">
                                    <div class="form-group">
                                        <div id="bpjs_error" class="alert alert-danger" style="display: none;"></div>
                                    </div>
                                </div>
                            <div class="col-md-3 d-flex justify-content-center">
                                <div class="position-relative text-center">
                                    <!-- Input Gambar -->
                                    <input type="file" id="profileImageInput" name="profile_image" accept="image/*" class="d-none" onchange="previewImage(event)">

                                    <!-- Bingkai Gambar dengan Rasio 3:4 -->
                                    <label for="profileImageInput" class="d-block" style="cursor: pointer;">
                                        <div style="width: 100%;border: 2px solid #ccc; max-width: 180px; aspect-ratio: 3 / 4; overflow: hidden; border-radius: 10px;  background: #f0f0f0; display: flex; align-items: center; margin-top: 75px;  justify-content: center;">
                                            <img id="profileImage" class="img-fluid rounded"
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

                                    <input type="hidden" class="form-control" placeholder="nomor_rm" id="nomor_rm" name="nomor_rm">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" placeholder="nama"
                                                id="nama" name="nama" readonly required>
                                        </div>
                                        @error('nama')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Nomor NIK</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-center" id="nik" name="nik" required>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Tempat & Tanggal Lahir</label>
                                            <div class="input-group">
                                                <!-- Input Tempat Lahir -->
                                                <input type="text" class="form-control" id="tempat_lahir"
                                                    name="tempat_lahir" required
                                                    placeholder="Tempat" style="width: 50%;">

                                                <!-- Input Tanggal Lahir -->
                                                <input type="date" class="form-control" id="tgllahir"
                                                    name="tgllahir" required
                                                    style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Provinsi</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="provinsi"
                                                name="provinsi" required>
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
                                                id="kabupaten" name="kabupaten" required>
                                                <option value="" disabled selected>Kota/Kabupaten</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Kecamatan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="kecamatan"
                                                name="kecamatan" value="{{ old('kecamatan') }}" required>
                                                <option value="" disabled selected>Kecamatan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Desa/Kelurahan</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="desa"
                                                name="desa" value="{{ old('desa') }}" required>
                                                <option value="" disabled selected>Desa/Kelurahan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label>RT</label>
                                            <input type="text" class="form-control" placeholder="001" id="rt"
                                                name="rt" value="{{ old('rt') }}" required>
                                        </div>
                                        @error('rt')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label>RW</label>
                                            <input type="text" class="form-control" placeholder="002" id="rw"
                                                name="rw" value="{{ old('rw') }}" required>
                                        </div>
                                        @error('rw')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Kode Pos</label>
                                            <input type="text" class="form-control" id="kode_pos" name="kode_pos"
                                                value="{{ old('kode_pos') }}" required>
                                        </div>
                                        @error('kode_pos')
                                            <div style="color: red;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea class="form-control" placeholder="Masukkan alamat" id="alamat" name="alamat" rows="1" required></textarea>
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
                                                <input type="text" class="form-control text-center" id="noka"
                                                    name="noka"
                                                    placeholder="Noka BPJS" style="width: 50%;">

                                                <!-- Input Tanggal Lahir -->
                                                <input type="text" class="form-control text-center" id="noihs"
                                                name="noihs"
                                                placeholder="Noka SatuSehat" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group text-center">
                                            <label>Jenis & Kelas BPJS</label>
                                            <div class="input-group">

                                                <input type="text" class="form-control text-center" id="jenis_kartu"
                                                    name="jenis_kartu"
                                                    placeholder="Jenis Peserta BPJS" style="width: 50%;">

                                                <input type="text" class="form-control text-center" id="kelas"
                                                name="kelas"
                                                placeholder="Kelas BPJS" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group text-center">
                                            <label>Faskes & Masa Berlaku Kartu BPJS</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-center" id="provide"
                                                name="provide"
                                                placeholder="Provide BPJS" style="width: 50%;">

                                                <input type="date" class="form-control text-center" id="tgl_exp_bpjs"
                                                    name="tgl_exp_bpjs"
                                                    placeholder="Masa Berlaku Kartu BPJS" style="width: 50%;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Jenis Kelamin</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="seks"
                                                name="seks" required>
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
                                            <select class="form-control select2bs4" style="width: 100%;" id="goldar"
                                                name="goldar" required>
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
                                            <select class="form-control select2bs4" style="width: 100%;" id="pernikahan"
                                                name="pernikahan" required>
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
                                                id="kewarganegaraan" name="kewarganegaraan" required>
                                                <option value=""
                                                    {{ old('kewarganegaraan') == '' ? 'selected' : '' }}>--- pilih ---
                                                </option>
                                                <option value="wni"
                                                    {{ old('kewarganegaraan') == 'wni' ? 'selected' : '' }}>Warga Negara
                                                    Indonesia</option>
                                                <option value="wna"
                                                    {{ old('kewarganegaraan') == 'wna' ? 'selected' : '' }}>Warga Negara
                                                    Asing</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Agama</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="agama"
                                                name="agama" required>
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
                                                id="pendidikan" name="pendidikan" required>
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
                                                id="status_kerja" name="status_kerja" required>
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
                                            <input type="text" class="form-control" id="telepon" name="telepon" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Suku</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="suku"
                                                name="suku" required>
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
                                            <select class="form-control select2bs4" style="width: 100%;" id="bangsa"
                                                name="bangsa">
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
                                            <select class="form-control select2bs4" style="width: 100%;" id="bahasa"
                                                name="bahasa">
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
                                            <input type="Email" class="form-control" id="email" name="email" required>
                                        </div>
                                    </div>
                                          {{-- Checkbox Penjamin 2 --}}
                                    <div class="col-sm-2 d-flex align-items-center">
                                        <div class="form-check">
                                            <label class="form-check-label" for="aktif_penjamin_2">&nbsp;</label>
                                            <input type="checkbox" class="form-check-input" id="aktif_penjamin_2">
                                            <label class="form-check-label" for="aktif_penjamin_2">Penjamin 2</label>
                                        </div>
                                    </div>

                                    {{-- Select Penjamin 2 --}}
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="penjamin_2">Penjamin 2</label>
                                            <select class="form-control" name="penjamin_2" id="penjamin_2" disabled>
                                                <option value="">-- Pilih Penjamin 2 --</option>
                                                @foreach($asuransi as $asuransi2)
                                                    <option value="{{ $asuransi2->nama }}">{{ $asuransi2->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Input Penjamin 2 --}}
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="penjamin_2_info">No. Penjamin 2</label>
                                            <input type="text" class="form-control" name="penjamin_2_info" id="penjamin_2_info" placeholder="No. Penjamin 2" disabled>
                                        </div>
                                    </div>

                                    {{-- Checkbox Penjamin 3 --}}
                                    <div class="col-sm-2 d-flex align-items-center">
                                        <div class="form-check">
                                            <label class="form-check-label" for="aktif_penjamin_3">&nbsp;</label>
                                            <input type="checkbox" class="form-check-input" id="aktif_penjamin_3">
                                            <label class="form-check-label" for="aktif_penjamin_3">Penjamin 3</label>
                                        </div>
                                    </div>

                                    {{-- Select Penjamin 3 --}}
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="penjamin_3">Penjamin 3</label>
                                            <select class="form-control" name="penjamin_3" id="penjamin_3" disabled>
                                                <option value="">-- Pilih Penjamin 3 --</option>
                                                @foreach($asuransi as $asuransi3)
                                                    <option value="{{ $asuransi3->nama }}">{{ $asuransi3->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Input Penjamin 3 --}}
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="penjamin_3_info">No. Penjamin 3</label>
                                            <input type="text" class="form-control" name="penjamin_3_info" id="penjamin_3_info" placeholder="No. Penjamin 3" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="kodeprovide" name="kodeprovide">
                        <input type="hidden" id="hubungan_keluarga" name="hubungan_keluarga">
                        <input type="hidden" id="userinput" name="userinput" value="{{ auth()->user()->name }}">
                        <input type="hidden" id="userinputid" name="userinputid" value="{{ auth()->user()->id }}">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" onclick="handleClick()" id="syncButton"><i id="syncIcon" class="fas fa-sync-alt"></i>Cek Nik / Noka</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                    </form>
            </div>
        </div>
    </div>
