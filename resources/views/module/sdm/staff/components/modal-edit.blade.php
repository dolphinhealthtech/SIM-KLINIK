{{-- modal edit Dokter --}}
    <div class="modal fade" id="editdokterModal" tabindex="-1" role="dialog" aria-labelledby="editdokterModalLabel">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editdokterModalLabel">Edit Data Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="bs-stepper">
                        <div class="bs-stepper-header" role="tablist">
                            <!-- your steps here -->
                            <div class="step" data-target="#biodata">
                                <button type="button" class="step-trigger" role="tab" aria-controls="biodata"
                                    id="biodata-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Biodata</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#biodata-lanjutan">
                                <button type="button" class="step-trigger" role="tab"
                                    aria-controls="biodata-lanjutan" id="biodata-lanjutan-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">biodata lanjutan</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <form id="updatedokterForm" action="{{ route('staff.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="dokterid_update" name="dokterid_update">

                                <!-- your steps content here -->
                                <div id="biodata" class="content" role="tabpanel" aria-labelledby="biodata-trigger">
                                    <div class="row">
                                        <div class="col-md-3 d-flex justify-content-center">
                                            <div class="position-relative text-center">
                                                <!-- Input Gambar -->
                                                <input type="file" id="profileImageInput" name="profile_image" accept="image/*" class="d-none" onchange="previewImage(event)">

                                                <!-- Bingkai Gambar dengan Rasio 3:4 -->
                                                <label for="profileImageInput" class="d-block" style="cursor: pointer;">
                                                    <div style="width: 100%;border: 2px solid #ccc; max-width: 180px; aspect-ratio: 3 / 4; overflow: hidden; border-radius: 10px;  background: #f0f0f0; display: flex; align-items: center; margin-top: 75px;  justify-content: center;">
                                                        <img id="profileImage" class="img-fluid rounded" src="{{ asset('setting/' . ($setting->profile_image ?? 'default.png')) }}"
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

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Nama </label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="nama_edit"
                                                            name="nama_edit">
                                                            <option value="" disabled selected>Nama</option>
                                                            @foreach ($user as $userdata)
                                                                <option value="{{ $userdata->id }}">{{ $userdata->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('nama')
                                                        <div style="color: red;">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Nomor NIK</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control text-center" id="nik_edit" name="nik_edit">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Nomor NPWP</label>
                                                        <input type="text" class="form-control" id="npwp_edit" name="npwp_edit">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Mulai Kerja Sejak</label>
                                                        <div class="input-group">
                                                            <input type="date" class="form-control" id="tgl_masuk_edit" name="tgl_masuk_edit" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <br>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Provinsi</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="provinsi_edit"
                                                            name="provinsi_edit">
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
                                                            id="kabupaten_edit" name="kabupaten_edit">
                                                            <option value="" disabled selected>Kota/Kabupaten</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Kecamatan</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="kecamatan_edit"
                                                            name="kecamatan_edit" value="{{ old('kecamatan') }}">
                                                            <option value="" disabled selected>Kecamatan</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Desa/Kelurahan</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="desa_edit"
                                                            name="desa_edit" value="{{ old('desa') }}">
                                                            <option value="" disabled selected>Desa/Kelurahan</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-1">
                                                    <div class="form-group">
                                                        <label>RT </label>
                                                        <input type="text" class="form-control" placeholder="001" id="rt_edit"
                                                            name="rt_edit" value="{{ old('rt') }}">
                                                    </div>
                                                    @error('rt')
                                                        <div style="color: red;">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-1">
                                                    <div class="form-group">
                                                        <label>RW </label>
                                                        <input type="text" class="form-control" placeholder="002" id="rw_edit"
                                                            name="rw_edit" value="{{ old('rw') }}">
                                                    </div>
                                                    @error('rw')
                                                        <div style="color: red;">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label>Kode Pos </label>
                                                        <input type="text" class="form-control" id="kode_pos_edit" name="kode_pos_edit"
                                                            value="{{ old('kode_pos') }}">
                                                    </div>
                                                    @error('kode_pos')
                                                        <div style="color: red;">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <label>Alamat</label>
                                                        <textarea class="form-control" placeholder="Masukkan alamat" id="alamat_edit" name="alamat_edit" rows="1"></textarea>
                                                    </div>
                                                    @error('alamat')
                                                        <div style="color: red;">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Jenis Kelamin</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="seks_edit"
                                                            name="seks_edit">
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
                                                            name="goldar_edit">
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
                                                            name="pernikahan_edit">
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
                                                            id="kewarganegaraan_edit" name="kewarganegaraan_edit">
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
                                                        <select class="form-control select2bs4" style="width: 100%;" id="agama_edit"
                                                            name="agama_edit">
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
                                                            id="pendidikan_edit" name="pendidikan_edit">
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
                                                        <label>Telepon</label>
                                                        <input type="text" class="form-control" id="telepon_edit" name="telepon_edit">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Suku</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="suku_edit"
                                                            name="suku_edit">
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
                                                            name="bangsa_edit">
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
                                                            name="bahasa_edit">
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
                                                        <label>Tempat & Tanggal Lahir</label>
                                                        <div class="input-group">
                                                            <!-- Input Tempat Lahir -->
                                                            <input type="text" class="form-control" id="tempat_lahir_edit"
                                                                name="tempat_lahir_edit"
                                                                placeholder="Tempat" style="width: 50%;">

                                                            <!-- Input Tanggal Lahir -->
                                                            <input type="date" class="form-control" id="tgl_lahir_edit"
                                                                name="tgl_lahir_edit"
                                                                style="width: 50%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Status Kerja</label>
                                                        <select class="form-control select2bs4"  style="width: 100%;"  id="posker_edit" name="posker_edit">
                                                            <option value="" disabled selected>--- Pilih Posisi ---</option>
                                                            @foreach ($posker as $poskerd)
                                                            <option value="{{$poskerd->id}}">{{$poskerd->nama}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                </div>



                                <div id="biodata-lanjutan" class="content" role="tabpanel" aria-labelledby="biodata-lanjutan-trigger">
                                        <div class="form-group">
                                            <label><strong>Pendidikan</strong></label>
                                            <hr>
                                            <div class="pendidikan-list_edit col-12"></div>
                                        </div>

                                        <br>

                                        <div class="form-group">
                                            <label><strong>Sertifikat Pelatihan Khusus</strong></label>
                                            <hr>
                                            <div id="pelatihan-container_edit" class="col-12"></div>
                                            <div class="text-center">
                                                <button type="button" class="btn btn-sm btn-success mt-2" id="tambah-pelatihan_edit">+ Tambah Pelatihan</button>
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
                                                            <select class="form-control select2bs4" style="width: 100%;" id="nama_bank_edit"
                                                                name="nama_bank_edit">
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
                                                            <input type="text" class="form-control" id="norek_edit" name="norek_edit">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Cabang</label>
                                                            <input type="text" class="form-control" id="cabang_bank_edit" name="cabang_bank_edit">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>