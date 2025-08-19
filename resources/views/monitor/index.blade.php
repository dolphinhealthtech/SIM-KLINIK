@extends('layouts.monitor')


@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
            </div>
        </div>
        <div class="content">
            <!-- Centered Queue Buttons Section -->
            <div class="mt-4"></div>
            <div class="row justify-content-center align-items-center" style="height: 25vh;">
                @if($is_bpjs_active)
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class=" text-success">Antrian BPJS</h5>
                            <button class="btn btn-success btn-lg btn-block" style="font-size: 2rem; padding: 20px;"
                                data-toggle="modal" data-target="#bpjsModal">
                                Antrian BPJS
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class=" text-primary">Antrian Non-BPJS</h5>
                            <button class="btn btn-primary btn-lg btn-block" style="font-size: 2rem; padding: 20px;"
                                data-toggle="modal" data-target="#nonBpjsModal">
                                Antrian Non-BPJS
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="text-center">
                        <div class="card">
                            <div class="card-header text-center">
                                <h5 class="text-info">Daftar Pasien Baru</h5>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-info btn-lg btn-block" style="font-size: 2rem; padding: 20px;"
                                    data-toggle="modal" data-target="#ddaftarModal">
                                    Daftar Pasien Baru
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class=" text-primary">Antrian Non-BPJS</h5>
                            <hr>
                            <p class="card-text">Klik tombol di bawah untuk mengambil antrian Non-BPJS dan mendapatkan
                                pelayanan kesehatan terbaik.</p>
                            <button class="btn btn-primary btn-lg btn-block" style="font-size: 2rem; padding: 20px;"
                                data-toggle="modal" data-target="#nonBpjsModal">
                                Antrian Non-BPJS
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center">
                        <div class="card">
                            <div class="card-header text-center">
                                <h5 class="text-info">Daftar Pasien Baru</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Klik tombol di bawah untuk mendaftar sebagai pasien baru dan
                                    mendapatkan pelayanan kesehatan terbaik.</p>
                                <button class="btn btn-info btn-lg btn-block" style="font-size: 2rem; padding: 20px;"
                                    data-toggle="modal" data-target="#ddaftarModal">
                                    Daftar Pasien Baru
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="mt-5"></div>
            </div><!-- /.container-fluid -->
        </div>
    </div>


    <div class="modal fade" id="bpjsModal" tabindex="-1" role="dialog" aria-labelledby="bpjsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bpjsModalLabel">Antrian BPJS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
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

    <!-- Modal for Antrian Non-BPJS -->
    <div class="modal fade" id="nonBpjsModal" tabindex="-1" role="dialog" aria-labelledby="nonBpjsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nonBpjsModalLabel">Antrian Non-BPJS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="nonBpjsForm" action="{{ route('monitor.add.nobpjs') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="nikNnamaInput">Masukkan NIK atau Nama</label>
                                    <input type="text" class="form-control" id="nikNnamaInput" name="nikNnamaInput"
                                        placeholder="Masukkan NIK atau Nama" required>
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
                                <form id="daftarForm" action="{{ route('monitor.add.pasien') }}" method="POST" enctype="multipart/form-data">
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="gender">Jenis Kelamin</label>
                                                    <select class="form-control" id="gender" name="gender">
                                                        <option selected="selected" disabled>Pilih Jenis Kelamin</option>
                                                        @foreach ($kelamin as $kelamindata)
                                                            <option value="{{ $kelamindata->kode }}">{{ $kelamindata->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="phoneNumber">Nomor Telepon</label>
                                                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Masukkan nomor telepon">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="address">Alamat</label>
                                                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
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
                                                    <select class="form-control" name="bloodType" id="bloodType">
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
                                                    <select class="form-control" id="maritalStatus" name="maritalStatus">
                                                        <option value="" disabled>Pilih Status Pernikahan</option>
                                                        @foreach ($pernikaha as $pernikahadata)
                                                            <option value="{{ $pernikahadata->id }} Menikah">{{ $pernikahadata->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @if($is_bpjs_active)
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
                                            @else
                                            <div class="col-md-12 d-flex justify-content-center">
                                                <div class="form-group w-50">
                                                    <label for="nik">Nomor induk kependudukan</label>
                                                    <input type="text" class="form-control" id="nik" name="nik" placeholder="nomor nik">
                                                </div>
                                            </div>
                                            @endif
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


    <script>
        $(function () {
            $('#tanggal_kunjungan').datetimepicker({
                format: 'YYYY-MM-DD HH:mm', // Format 24 jam
                icons: { time: 'far fa-clock' } // Tidak muncul icon di input
            });
            $('#tanggal_kunjungan_no').datetimepicker({
                format: 'YYYY-MM-DD HH:mm', // Format 24 jam
                icons: { time: 'far fa-clock' } // Tidak muncul icon di input
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            let sudahRequest = false;

            $('#noka').on('input', function() {
                let noka = $(this).val();

                if (noka.length === 13 && !sudahRequest) {
                    sudahRequest = true;

                    // Jalankan AJAX
                    $.ajax({
                        url: '/api/pcare/noka/' + noka,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            // Misal isi otomatis field lain:
                            $('#patientName').val(response.data.nama);
                            $('#nik').val(response.data.noKTP);
                            $('#phoneNumber').val(response.data.noHP);
                            let tgl = response.data.tglLahir;
                            if (tgl.includes('-')) {
                                let parts = tgl.split('-'); // DD-MM-YYYY
                                let isoDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                                $('#tanggallahir').val(isoDate);
                            }
                        }
                    });
                }

                if (noka.length < 13) {
                    sudahRequest = false; // reset kalau panjang kurang
                }
            });
        });
    </script>

    <script>
        let video = document.getElementById('cameraPreview');
        let canvas = document.getElementById('capturedCanvas');
        let photoPreview = document.getElementById('photoPreview');
        let cameraIcon = document.getElementById('cameraIcon');
        let photoInput = document.getElementById('photoInput');
        let patientNameInput = document.getElementById('patientName');
        let timestampInput = document.getElementById('timestamp');

        function openCameraModal() {
            $('#cameraModal').modal('show');

            navigator.mediaDevices.getUserMedia({ video: true })
                .then((stream) => {
                    video.srcObject = stream;
                })
                .catch((error) => {
                    console.error("Gagal mengakses kamera:", error);
                });
        }

        function capturePhoto() {
            let context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            let imageDataUrl = canvas.toDataURL('image/png');
            photoPreview.src = imageDataUrl;
            photoPreview.style.display = 'block';
            cameraIcon.style.display = 'none';

            // Ambil nama pasien (4 huruf pertama) dan timestamp
            let patientName = patientNameInput.value.trim();
            let shortName = patientName.substring(0, 4).toUpperCase(); // 4 huruf pertama dalam huruf besar
            let now = new Date();
            let timestamp = now.getFullYear().toString() +
                            String(now.getMonth() + 1).padStart(2, '0') +
                            String(now.getDate()).padStart(2, '0') + "_" +
                            String(now.getHours()).padStart(2, '0') +
                            String(now.getMinutes()).padStart(2, '0') +
                            String(now.getSeconds()).padStart(2, '0');

            let fileName = shortName + "_" + timestamp + ".png"; // Format nama file

            // Simpan timestamp ke input hidden
            timestampInput.value = timestamp;

            // Konversi Data URL ke Blob
            fetch(imageDataUrl)
                .then(res => res.blob())
                .then(blob => {
                    let file = new File([blob], fileName, { type: "image/png" });

                    // Masukkan file ke input file
                    let dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    photoInput.files = dataTransfer.files;
                });

            // Hentikan kamera setelah menangkap foto
            let stream = video.srcObject;
            let tracks = stream.getTracks();
            tracks.forEach(track => track.stop());

            // Tutup modal
            $('#cameraModal').modal('hide');
        }
    </script>

    <script>
        $(document).ready(function () {
            $('#poli_id').on('change', ambilDokter);
            $('#tanggal_kunjungan').on('change.datetimepicker', ambilDokter);

            function ambilDokter() {
                let poliId = $('#poli_id').val();
                let datetime = $('#tanggal_kunjungan').val();

                if (poliId && datetime) {
                    let formattedDatetime = datetime + ':00';
                    console.log("Mengambil dokter...");
                    console.log("Poli ID:", poliId);
                    console.log("Datetime:", formattedDatetime);

                    $.ajax({
                        url: `/api/get-dokter-by-poli/${poliId}`,
                        method: 'GET',
                        data: { datetime: formattedDatetime },
                        success: function (data) {
                            $('#dokter_id').empty().append(`<option value="">Pilih Dokter</option>`);
                            data.forEach(function (dokter) {
                                $('#dokter_id').append(`<option value="${dokter.id}">${dokter.namauser.name}</option>`);
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Gagal mengambil data dokter!'
                            });
                        }
                    });
                }
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#poli_id_no').on('change', ambilDokter);
            $('#tanggal_kunjungan_no').on('change.datetimepicker', ambilDokter);

            function ambilDokter() {
                let poliId = $('#poli_id_no').val();
                let datetime = $('#tanggal_kunjungan_no').val();

                if (poliId && datetime) {
                    let formattedDatetime = datetime + ':00';
                    console.log("Mengambil dokter...");
                    console.log("Poli ID:", poliId);
                    console.log("Datetime:", formattedDatetime);

                    $.ajax({
                        url: `/api/get-dokter-by-poli/${poliId}`,
                        method: 'GET',
                        data: { datetime: formattedDatetime },
                        success: function (data) {
                            $('#dokter_id_no').empty().append(`<option value="">Pilih Dokter</option>`);
                            data.forEach(function (dokter) {
                                $('#dokter_id_no').append(`<option value="${dokter.id}">${dokter.namauser.name}</option>`);
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Gagal mengambil data dokter!'
                            });
                        }
                    });
                }
            }
        });
    </script>

    <script>
        document.getElementById("nikNnamaInput").addEventListener("blur", function () {
            const inputValue = this.value.trim();
            const feedbackElement = document.getElementById("nikNnamaFeedback");

            if (inputValue.length === 0) {
                feedbackElement.textContent = '';
                return;
            }

            fetch('/api/get-pasien-nikornama', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ nikNama: inputValue })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    feedbackElement.textContent = 'Data ditemukan: ' + data.nama;
                    feedbackElement.classList.remove('text-danger');
                    feedbackElement.classList.add('text-success');
                } else {
                    feedbackElement.textContent = 'Data tidak ditemukan';
                    feedbackElement.classList.remove('text-success');
                    feedbackElement.classList.add('text-danger');
                }
            })
            .catch(error => {
                feedbackElement.textContent = 'Terjadi kesalahan saat mencari data';
                feedbackElement.classList.remove('text-success');
                feedbackElement.classList.add('text-danger');
                console.error('Error:', error);
            });
        });
    </script>

    <script>
        document.getElementById("nikNokaInput").addEventListener("blur", function () {
            const inputValue = this.value.trim();
            const feedbackElement = document.getElementById("nikNokaFeedback");

            if (inputValue.length === 0) {
                feedbackElement.textContent = '';
                return;
            }

            fetch('/api/get-pasien-nikornoka', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ nikNoka: inputValue })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    feedbackElement.textContent = 'Data ditemukan: ' + data.nama;
                    feedbackElement.classList.remove('text-danger');
                    feedbackElement.classList.add('text-success');
                } else {
                    feedbackElement.textContent = 'Data tidak ditemukan';
                    feedbackElement.classList.remove('text-success');
                    feedbackElement.classList.add('text-danger');
                }
            })
            .catch(error => {
                feedbackElement.textContent = 'Terjadi kesalahan saat mencari data';
                feedbackElement.classList.remove('text-success');
                feedbackElement.classList.add('text-danger');
                console.error('Error:', error);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
    </script>

    <script>
        $(document).ready(function() {
            // Add novalidate attribute to your form to disable browser validation
            $('form').attr('novalidate', 'novalidate');

            // Custom form validation and submission
            $('form').on('submit', function(e) {
                e.preventDefault();

                // Get form data
                let form = $(this);
                let formData = new FormData(this);
                let isValid = true;
                let errorMessages = [];

                // Check required fields
                form.find('[required]').each(function() {
                    let field = $(this);
                    let fieldName = field.attr('name');
                    let fieldLabel = field.prev('label').text() || fieldName;

                    // Remove is-invalid class first
                    field.removeClass('is-invalid');

                    if (!field.val()) {
                        isValid = false;
                        field.addClass('is-invalid');
                        errorMessages.push(`${fieldLabel} harus diisi`);
                    }
                });

                // If validation fails, show SweetAlert with errors
                if (!isValid) {
                    let errorList = errorMessages.map(msg => `- ${msg}`).join('<br>');

                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi Gagal!',
                        html: `Terdapat beberapa input yang belum valid:<br><br>${errorList}`,
                        confirmButtonText: 'OK'
                    });

                    return false;
                }

                // If validation passes, submit the form via AJAX
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Cetak atau tampilkan nomor antrian
                                    let noAntrian = response.noantrian || 'Tidak ada nomor antrian';
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Nomor Antrian Anda',
                                        html: `<div id="printArea"><h1 style="font-size: 3rem; text-align: center;">${noAntrian}</h1></div>`,
                                        showConfirmButton: true
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Buat jendela print khusus
                                            let printContents = document.getElementById('printArea').innerHTML;
                                            let originalTitle = document.title;
                                            let printWindow = window.open('', '', 'height=500,width=400');

                                            printWindow.document.write('<html><head><title>Cetak Nomor Antrian</title>');
                                            printWindow.document.write('</head><body style="text-align:center; font-family:sans-serif;">');
                                            printWindow.document.write(printContents);
                                            printWindow.document.write('</body></html>');

                                            printWindow.document.close();
                                            printWindow.focus();
                                            printWindow.print();
                                            printWindow.close();
                                        }
                                    }).then(() => {
                                        location.reload(); // Reload halaman untuk update data
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan saat memproses data'
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            let errorList = '';

                            // Loop through validation errors
                            Object.entries(xhr.responseJSON.errors).forEach(([key, value]) => {
                                errorList += `- ${value[0]}<br>`;
                                // Add is-invalid class to the field
                                $(`[name="${key}"]`).addClass('is-invalid');
                            });

                            Swal.fire({
                                icon: 'warning',
                                title: 'Validasi Gagal!',
                                html: `Terdapat beberapa input yang belum valid:<br><br>${errorList}`,
                                confirmButtonText: 'OK'
                            });
                        } else {
                            let errorMessage = "Terjadi kesalahan dalam menyimpan data!";
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection

