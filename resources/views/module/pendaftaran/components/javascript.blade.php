    <script>
        $(document).on('click', '.dokter-data-pasien', function() {
            let id = $(this).data('id');
            let tanggal = $(this).data('tgl-kunjung');
            let poli = $(this).data('poli');
            let nama = $(this).data('nama');

            // SweetAlert untuk konfirmasi rubah dokter
            Swal.fire({
                title: 'Rubah Dokter',
                html: `
                    <div class="text-left">
                        <p><strong>Pasien:</strong> ${nama}</p>
                        <div class="form-group">
                            <label for="dokter_select">Pilih Dokter Baru:</label>
                            <select id="dokter_select" class="form-control" style="width: 100%;">
                                <option value="">Memuat dokter...</option>
                            </select>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                preConfirm: () => {
                    const dokterId = document.getElementById('dokter_select').value;
                    if (!dokterId) {
                        Swal.showValidationMessage('Silakan pilih dokter terlebih dahulu');
                        return false;
                    }
                    return dokterId;
                },
                didOpen: () => {
                    // Load dokter berdasarkan poli dan tanggal
                    if (poli && tanggal) {
                        let formattedDatetime = tanggal.replace('T', ' ') + ':00';

                        $.ajax({
                            url: `/api/get-dokter-by-poli/${poli}`,
                            method: 'GET',
                            data: { datetime: formattedDatetime },
                            success: function (data) {
                                let select = document.getElementById('dokter_select');
                                select.innerHTML = '<option value="">Pilih Dokter</option>';
                                data.forEach(function (dokter) {
                                    select.innerHTML += `<option value="${dokter.id}">${dokter.namauser.name}</option>`;
                                });
                            },
                            error: function (xhr, status, error) {
                                document.getElementById('dokter_select').innerHTML = '<option value="">Gagal memuat dokter</option>';
                            }
                        });
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(result);

                    // Tampilkan loading
                    Swal.fire({
                        title: 'Mengupdate...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Submit update dokter
                    $.ajax({
                        url: "{{ route('pendaftaran.dokter.update') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            rubahdokter_id: id,
                            poli_id_update: poli,
                            tanggal_kunjungan_update: tanggal,
                            dokter_id_update: result.value
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Dokter berhasil diupdate',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = 'Terjadi kesalahan saat mengupdate dokter';

                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                let errors = xhr.responseJSON.errors;
                                let errorList = [];
                                Object.keys(errors).forEach(function(field) {
                                    errors[field].forEach(function(message) {
                                        errorList.push(message);
                                    });
                                });
                                errorMessage = errorList.join('<br>');
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Update Dokter!',
                                html: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    </script>

    <script>
        $(function () {
            $('#tanggal_kunjungan').datetimepicker({
                format: 'YYYY-MM-DD HH:mm', // Format 24 jam
                icons: { time: 'far fa-clock' } // Tidak muncul icon di input
            });
        });
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

            // Form submit untuk tambah pasien dengan SweetAlert
            $('#addFormsuku').on('submit', function(e) {
                e.preventDefault();

                // Validasi form sebelum submit
                let isValid = true;
                let errorMessages = [];

                // Validasi Pasien
                if (!$('#pasien').val()) {
                    isValid = false;
                    errorMessages.push('Pasien harus dipilih');
                }

                // Validasi Poli
                if (!$('#poli_id').val()) {
                    isValid = false;
                    errorMessages.push('Poli harus dipilih');
                }

                // Validasi Tanggal Kunjungan
                if (!$('#tanggal_kunjungan').val()) {
                    isValid = false;
                    errorMessages.push('Tanggal kunjungan harus diisi');
                }

                // Validasi Dokter
                if (!$('#dokter_id').val()) {
                    isValid = false;
                    errorMessages.push('Dokter harus dipilih');
                }

                // Validasi Penjamin
                if (!$('#penjamin_id').val()) {
                    isValid = false;
                    errorMessages.push('Penjamin pasien harus dipilih');
                }

                // Jika tidak valid, tampilkan error
                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Tidak Lengkap!',
                        html: errorMessages.join('<br>'),
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menambah data pasien ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Tambah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });

                        // Submit form dengan AJAX
                        $.ajax({
                            url: "{{ route('pendaftaran.add') }}",
                            method: 'POST',
                            data: $(this).serialize(),
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
                            error: function(xhr, status, error) {
                                let errorMessage = 'Terjadi kesalahan saat menyimpan data.';

                                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                    // Validasi Laravel
                                    let messages = [];
                                    Object.values(xhr.responseJSON.errors).forEach(msgArr => {
                                        msgArr.forEach(msg => messages.push(msg));
                                    });
                                    errorMessage = messages.join('<br>');
                                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                    // Coba ambil dari "message"
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.responseJSON && xhr.responseJSON.error) {
                                    // Ambil dari "error" (kasus kamu)
                                    errorMessage = xhr.responseJSON.error;
                                } else if (xhr.status >= 500) {
                                    errorMessage = 'Terjadi kesalahan server.';
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    html: errorMessage,
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        $('#deleteFormhadir').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#panggilModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            $('.modal-backdrop').remove(); // Hapus backdrop jika masih ada
                            location.reload(); // Reload halaman untuk update data
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus Goldar!',
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#userstabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    {
                        text: '<i class="fas fa-plus"></i> Tambah',
                        type: 'button',
                        className: 'btn btn-primary',
                        action: function () {
                        $('#addreispasienModal').modal('show'); // <== Bootstrap 4-compatible
                        }
                    },
                ],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Tidak ada entri yang tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total entri)",
                }
            }).buttons().container().appendTo('#userstabel_wrapper .col-md-6:eq(0)');
        });

        // SweetAlert untuk Batal Pendaftaran
        $(document).on('click', '.batal-data-pasien-pcare', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-pasien');

            Swal.fire({
                title: 'Batal Pendaftaran Pelayanan',
                html: `
                    <div class="text-left">
                        <p>Apakah Anda yakin ingin membatalkan antrian pasien <strong>${name}</strong>?</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Membatalkan...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Submit pembatalan
                    $.ajax({
                        url: "{{ route('pendaftaran.batal.pcare') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            batalid_delete: id
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Pendaftaran berhasil dibatalkan',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = 'Terjadi kesalahan saat membatalkan pendaftaran';

                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                let errors = xhr.responseJSON.errors;
                                let errorList = [];
                                Object.keys(errors).forEach(function(field) {
                                    errors[field].forEach(function(message) {
                                        errorList.push(message);
                                    });
                                });
                                errorMessage = errorList.join('<br>');
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Batalkan Pendaftaran!',
                                html: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        // SweetAlert untuk Batal Pendaftaran
        $(document).on('click', '.batal-data-pasien', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-pasien');

            Swal.fire({
                title: 'Batal Pendaftaran Antrian',
                html: `
                    <div class="text-left">
                        <p>Apakah Anda yakin ingin membatalkan antrian pasien <strong>${name}</strong>?</p>
                        <div class="form-group mt-3">
                            <label for="alasan_batal">Alasan Pembatalan:</label>
                            <input type="text" id="alasan_batal" class="form-control" placeholder="Masukkan alasan pembatalan" required>
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak',
                preConfirm: () => {
                    const alasan = document.getElementById('alasan_batal').value;
                    if (!alasan) {
                        Swal.showValidationMessage('Alasan pembatalan harus diisi');
                        return false;
                    }
                    return alasan;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Membatalkan...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Submit pembatalan
                    $.ajax({
                        url: "{{ route('pendaftaran.batal') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            batalid_delete: id,
                            alasanpembatalan: result.value
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Pendaftaran berhasil dibatalkan',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = 'Terjadi kesalahan saat membatalkan pendaftaran';

                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                let errors = xhr.responseJSON.errors;
                                let errorList = [];
                                Object.keys(errors).forEach(function(field) {
                                    errors[field].forEach(function(message) {
                                        errorList.push(message);
                                    });
                                });
                                errorMessage = errorList.join('<br>');
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Batalkan Pendaftaran!',
                                html: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        // SweetAlert untuk Panggil Pasien (Hadir)
        $(document).on('click', '.panggil-data-pasien', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-pasien');

            Swal.fire({
                title: 'Konfirmasi Kehadiran',
                text: `Apakah pasien ${name} sudah hadir?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hadir!',
                cancelButtonText: 'Belum Hadir'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Submit kehadiran
                    $.ajax({
                        url: "{{ route('pendaftaran.hadir') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            hadirid_delete: id
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Status kehadiran pasien berhasil diupdate',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = 'Terjadi kesalahan saat mengupdate status kehadiran';

                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                let errors = xhr.responseJSON.errors;
                                let errorList = [];
                                Object.keys(errors).forEach(function(field) {
                                    errors[field].forEach(function(message) {
                                        errorList.push(message);
                                    });
                                });
                                errorMessage = errorList.join('<br>');
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Update Status!',
                                html: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    </script>

    {{-- Script untuk menampilkan pesan sukses/error dari session --}}
    @if(session('success'))
    <script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                showConfirmButton: true
            });
        });
    </script>
    @endif

    @if($errors->any())
    <script>
        $(document).ready(function() {
            let errorMessages = @json($errors->all());
            Swal.fire({
                icon: 'error',
                title: 'Validation Error!',
                html: errorMessages.join('<br>'),
                showConfirmButton: true
            });
        });
    </script>
    @endif
