<!-- Modal for Antrian daftar -->
    <div class="modal fade" id="ddaftarModal" tabindex="-1" role="dialog" aria-labelledby="ddaftarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ddaftarModalLabel">Antrian Pendaftaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
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
                                <div class="line"></div>
                                <div class="step" data-target="#information-part">
                                    <button type="button" class="step-trigger" role="tab"
                                        aria-controls="information-part" id="information-part-trigger">
                                        <span class="bs-stepper-circle">3</span>
                                        <span class="bs-stepper-label">Informasi lanjutan</span>
                                    </button>
                                </div>
                            </div>
                            <div class="bs-stepper-content">
                                <form id="daftarForm" action="{{ route('pendaftaran-online.add.pasien') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <!-- your steps content here -->
                                    <div id="biodata" class="content" role="tabpanel" aria-labelledby="biodata-trigger">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="patientName">Nama Lengkap</label>
                                                    <input type="text" class="form-control" id="patientName" name="patientName" placeholder="Masukkan nama lengkap">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="tanggallahir">Tanggal Lahir</label>
                                                    <input type="date" class="form-control" id="tanggallahir" name="tanggallahir">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="gender">Jenis Kelamin</label>
                                                    <select class="form-control select2bs4" id="gender" name="gender">
                                                        <option selected="selected" disabled >Pilih Jenis Kelamin</option>
                                                        @foreach ($kelamin as $kelamindata)
                                                            <option value="{{ $kelamindata->kode }}">{{ $kelamindata->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phoneNumber">Nomor Telepon</label>
                                                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Masukkan nomor telepon">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nik">Nomor induk kependudukan</label>
                                                    <input type="text" class="form-control" id="nik" name="nik" placeholder="nomor nik">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="noka">Nomor Bpjs</label>
                                                    <input type="text" class="form-control" id="noka" name="noka" placeholder="nomor noka">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="stepper.next()">Selanjutnya</button>
                                    </div>



                                    <div id="biodata-lanjutan" class="content" role="tabpanel" aria-labelledby="biodata-lanjutan-trigger">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bloodType">Golongan Darah</label>
                                                    <select class="form-control select2bs4" name="bloodType" id="bloodType">
                                                        <option selected="selected" disabled>Pilih Golongan darah</option>
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
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="maritalStatus">Status Pernikahan</label>
                                                    <select class="form-control select2bs4" id="maritalStatus" name="maritalStatus">
                                                        <option value="" disabled>Pilih Status Pernikahan</option>
                                                        @foreach ($pernikaha as $pernikahadata)
                                                            <option value="{{ $pernikahadata->id }}">{{ $pernikahadata->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="address">Alamat</label>
                                                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="stepper.previous()">Kembali</button>
                                        <button type="button" class="btn btn-primary" onclick="stepper.next()">Selanjutnya</button>
                                    </div>


                                    <div id="information-part" class="content text-center" role="tabpanel" aria-labelledby="information-part-trigger">
                                        <!-- Kotak Foto (Tengah) -->
                                        <div class="d-flex justify-content-center">
                                            <div class="photo-container" onclick="openCameraModal()">
                                                <i id="cameraIcon" class="fas fa-camera fa-3x text-muted"></i>
                                                <img id="photoPreview" src="" alt="Captured Photo" style="display: none;">
                                            </div>
                                        </div>
                                        <input type="file" id="photoInput" name="gambar" style="display: none;" accept="image/*">


                                        <!-- Tombol Navigasi -->
                                        <button type="button" class="btn btn-primary mt-3" onclick="stepper.previous()">Kembali</button>
                                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <!-- Modal Kamera -->
    <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalLabel">Ambil Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <video id="cameraPreview" autoplay></video>
                    <canvas id="capturedCanvas" style="display: none;"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="capturePhoto()">Ambil Foto</button>
                </div>
            </div>
        </div>
    </div>
