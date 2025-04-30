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
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class=" text-success">Antrian BPJS</h5>
                            <hr>
                            <p class="card-text">Klik tombol di bawah untuk mengambil antrian BPJS dan mendapatkan pelayanan
                                kesehatan terbaik.</p>
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
                <div class="col-md-8">
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
                                    <label>Jadwal Kunjunagan</label>
                                    <input type="datetime-local" class="form-control" id="tanggal_kunjungan" name="tanggal_kunjungan">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="sumbit" class="btn btn-primary">Lanjutkan</button>
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
                                    <label>Jadwal Kunjunagan</label>
                                    <input type="datetime-local" class="form-control" id="tanggal_kunjungan_no" name="tanggal_kunjungan_no">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="sumbit" class="btn btn-primary">Lanjutkan</button>
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
                                        <span class="bs-stepper-label">Various information</span>
                                    </button>
                                </div>
                            </div>
                            <div class="bs-stepper-content">
                                <form id="daftarForm" action="{{ route('pasien.store') }}" method="POST" enctype="multipart/form-data">
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
                                            <input type="hidden" id="timestamp" name="timestamp">

                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
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
                                                    <label for="maritalStatus">Status Perkawinan</label>
                                                    <select class="form-control" id="maritalStatus" name="maritalStatus">
                                                        <option value="Belum Menikah">Belum Menikah</option>
                                                        <option value="Menikah">Menikah</option>
                                                        <option value="Cerai">Cerai</option>
                                                    </select>
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
                                        <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                        <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
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
                                        <button type="button" class="btn btn-primary mt-3" onclick="stepper.previous()">Previous</button>
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
        // Jika poli atau tanggal berubah
        $('#poli_id_no, #tanggal_kunjungan_no').on('change', function () {
            let poliId = $('#poli_id_no').val();
            let datetime = $('#tanggal_kunjungan_no').val(); // format input datetime-local: yyyy-MM-ddTHH:mm

            if (poliId && datetime) {
                // Format datetime jadi Y-m-d H:i:s
                let formattedDatetime = datetime.replace('T', ' ') + ':00';

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
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                        console.error("Response Text:", xhr.responseText);
                        alert('Gagal mengambil data dokter.');
                    }
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Jika poli atau tanggal berubah
        $('#poli_id, #tanggal_kunjungan').on('change', function () {
            let poliId = $('#poli_id').val();
            let datetime = $('#tanggal_kunjungan').val(); // format input datetime-local: yyyy-MM-ddTHH:mm

            if (poliId && datetime) {
                // Format datetime jadi Y-m-d H:i:s
                let formattedDatetime = datetime.replace('T', ' ') + ':00';

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
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                        console.error("Response Text:", xhr.responseText);
                        alert('Gagal mengambil data dokter.');
                    }
                });
            }
        });
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
@endsection
