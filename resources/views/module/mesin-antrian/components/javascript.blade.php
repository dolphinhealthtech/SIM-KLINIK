
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

            $('#nik').on('input', function() {
                let nik = $(this).val();

                if (nik.length === 16 && !sudahRequest) {
                    sudahRequest = true;

                    // Jalankan AJAX
                    $.ajax({
                        url: '/api/pcare/nik/' + nik,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            // Misal isi otomatis field lain:
                            $('#patientName').val(response.data.nama);
                            $('#noka').val(response.data.noKartu);
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
