<script>
        var calendar;
        let name = $(this).data('nama-dokter-jadwal');
        let id = $(this).data('id');

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendarDokter');
            var Calendar = FullCalendar.Calendar;


            calendar = new Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                height: 450,
                slotMinTime: "00:00:00",
                slotMaxTime: "24:00:00",
                selectable: true,
                selectMirror: true,
                nowIndicator: true,

                select: function (info) {
                    const dokter_id = window.selectedDokterId;
                    const title = "Jadwal Masuk";

                    if (title) {
                        // Kirim ke server
                        fetch("{{ route('dokter.jadwal') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                dokter_id: dokter_id,
                                title: title,
                                start: info.startStr,
                                end: info.endStr
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            // Tambahkan langsung ke kalender
                            calendar.addEvent({
                                id: data.id,
                                title: title,
                                start: info.startStr,
                                end: info.endStr
                            });
                        })
                        .catch(err => console.error("Error:", err));
                    }

                    calendar.unselect();
                },

                events: function(fetchInfo, successCallback, failureCallback) {
                    if (!window.selectedDokterId) return;

                    fetch(`/api/jadwal/json/${window.selectedDokterId}`)
                        .then(res => res.json())
                        .then(data => successCallback(data))
                        .catch(err => failureCallback(err));
                },

                eventClick: function(info) {
                    Swal.fire({
                        title: 'Hapus Jadwal?',
                        text: "Apakah Anda yakin ingin menghapus jadwal ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const eventId = info.event.id;
                            let url = "{{ route('dokter.jadwal.hapus', ['id' => ':id']) }}";
                            url = url.replace(':id', eventId);


                            fetch(url, {
                                method: "DELETE",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    info.event.remove();
                                    Swal.fire(
                                        'Terhapus!',
                                        'Jadwal berhasil dihapus.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Gagal!',
                                        'Tidak dapat menghapus jadwal.',
                                        'error'
                                    );
                                }
                            })
                            .catch(err => {
                                console.error("Error:", err);
                                Swal.fire(
                                    'Error!',
                                    'Terjadi kesalahan saat menghapus.',
                                    'error'
                                );
                            });
                        }
                    });
                }


            });

            $(document).on('click', '.jadwal-data-dokter', function () {
                // Ambil data dari tombol yang diklik
                let name = $(this).data('nama-dokter-jadwal');
                let id = $(this).data('id');

                $('#nama-dokter-jadwal').html(name);

                window.selectedDokterId = id;


                // Render calendar
                setTimeout(function () {
                    // Update source event sesuai ID dokter yang diklik
                    calendar.removeAllEvents();
                    calendar.refetchEvents(); // kalau pakai dynamic source
                    calendar.render();
                }, 200);
            });

            $('.sinkron-jadwal-btn').on('click', function () {
                if (!selectedDokterId) {
                    Swal.fire('Gagal', 'ID Dokter tidak ditemukan.', 'error');
                    return;
                }

                const routeTemplate = $(this).data('route-template');
                const realRoute = routeTemplate.replace('__ID__', selectedDokterId);

                Swal.fire({
                    title: 'Sinkronisasi Jadwal?',
                    text: 'Yakin ingin sinkronisasi jadwal dokter ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Sinkronkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(realRoute)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Berhasil', 'Sinkron jadwal berhasil dilakukan.', 'success');
                                    setTimeout(function () {
                                        // Update source event sesuai ID dokter yang diklik
                                        calendar.removeAllEvents();
                                        calendar.refetchEvents(); // kalau pakai dynamic source
                                        calendar.render();
                                    }, 200)
                                } else {
                                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan saat sinkronisasi.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error(error);
                                Swal.fire('Gagal', 'Gagal melakukan sinkronisasi.', 'error');
                            });
                    }
                });
            });


        });
    </script>

    <script>
        function updateInputValue(inputElement, newValue) {
            if (inputElement.value.trim() !== newValue) {
                inputElement.value = newValue;
            }
        }
        function handleClick() {
            const icon = document.getElementById("syncIcon");
            icon.classList.add('fa-spin'); // Start spinning icon

            let nik = document.getElementById("nik").value;
            let noihsApiUrl = `{{ route('satusehat.nik_practitione', ':nik') }}`.replace(':nik', nik);

            fetch(noihsApiUrl, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                }
            })
            .then(response => response.json())
            .then(noihsData => {
                let noihsInput = document.getElementById("kode_satu");
                let strInput = document.getElementById("str");
                let expstrInput = document.getElementById("expstr");

                if (noihsData.status === "success" && noihsData.data) {
                    updateInputValue(noihsInput, noihsData.data.id); // Set nilai IHS
                    updateInputValue(strInput, noihsData.data.str_number); // Set nilai STR
                    updateInputValue(expstrInput, noihsData.data.str_expired); // Set nilai Expired STR
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: "No IHS tidak ditemukan."
                    });
                }
            })
            .catch(error => {
                console.error("Gagal mengambil No IHS:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Gagal mengambil No IHS dari API."
                });
            })
            .finally(() => {
                icon.classList.remove('fa-spin'); // Stop spinning
            });
        }

    </script>

    <script>
        function updateInputValue_edit(inputElement, newValue) {
            if (inputElement.value.trim() !== newValue) {
                inputElement.value = newValue;
            }
        }
        function handleClick_edit() {
            const icon = document.getElementById("syncIcon");
            icon.classList.add('fa-spin'); // Start spinning icon

            let nik = document.getElementById("nik").value;
            let noihsApiUrl = `{{ route('satusehat.nik_practitione', ':nik') }}`.replace(':nik', nik);

            fetch(noihsApiUrl, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                }
            })
            .then(response => response.json())
            .then(noihsData => {
                let noihsInput = document.getElementById("kode_satu_edit");
                let strInput = document.getElementById("str_edit");
                let expstrInput = document.getElementById("expstr_edit");

                if (noihsData.status === "success" && noihsData.data) {
                    updateInputValue_edit(noihsInput, noihsData.data.id); // Set nilai IHS
                    updateInputValue_edit(strInput, noihsData.data.str_number); // Set nilai STR
                    updateInputValue_edit(expstrInput, noihsData.data.str_expired); // Set nilai Expired STR
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: "No IHS tidak ditemukan."
                    });
                }
            })
            .catch(error => {
                console.error("Gagal mengambil No IHS:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Gagal mengambil No IHS dari API."
                });
            })
            .finally(() => {
                icon.classList.remove('fa-spin'); // Stop spinning
            });
        }

    </script>

    <script>
        $(document).ready(function () {

            function loadKabupaten(provinsiID, selectedKabupaten = "", callback = null) {
                if (!provinsiID) return;
                console.log("Loading Kabupaten untuk Provinsi ID:", provinsiID);

                $('#kabupaten_edit').html('<option value="">Memuat...</option>');
                $('#kecamatan_edit').html('<option value="" disabled selected>Pilih Kecamatan</option>');
                $('#desa_edit').html('<option value="" disabled selected>Pilih Kelurahan</option>');

                $.get("{{ route('get.kabupaten') }}", { provinsi_id: provinsiID })
                    .done(function (data) {
                        console.log("Data Kabupaten:", data);
                        let options = '<option value="" disabled selected>Pilih Kabupaten</option>';
                        $.each(data, function (index, kab) {
                            options += `<option value="${kab.kode}">${kab.name}</option>`;
                        });
                        $('#kabupaten_edit').html(options);

                        if (selectedKabupaten) {
                            $('#kabupaten_edit').val(selectedKabupaten);
                            console.log("Kabupaten Selected:", selectedKabupaten);
                            $('#kabupaten_edit').trigger('change');
                            if (callback) callback(selectedKabupaten);
                        }
                    })
                    .fail(function () {
                        console.error("Gagal mengambil data kabupaten.");
                    });
            }

            function loadKecamatan(kabupatenID, selectedKecamatan = "", callback = null) {
                if (!kabupatenID) return;
                console.log("Loading Kecamatan untuk Kabupaten ID:", kabupatenID);

                $('#kecamatan_edit').html('<option value="">Memuat...</option>');
                $('#desa_edit').html('<option value="" disabled selected>Pilih Kelurahan</option>');

                $.get("{{ route('get.kecamatan') }}", { kabupaten_id: kabupatenID })
                    .done(function (data) {
                        console.log("Data Kecamatan:", data);
                        let options = '<option value="" disabled selected>Pilih Kecamatan</option>';
                        $.each(data, function (index, kec) {
                            options += `<option value="${kec.kode}">${kec.name}</option>`;
                        });
                        $('#kecamatan_edit').html(options);

                        if (selectedKecamatan) {
                            $('#kecamatan_edit').val(selectedKecamatan);
                            console.log("Kecamatan Selected:", selectedKecamatan);
                            $('#kecamatan_edit').trigger('change');
                            if (callback) callback(selectedKecamatan);
                        }
                    })
                    .fail(function () {
                        console.error("Gagal mengambil data kecamatan.");
                    });
            }

            function loadDesa(kecamatanID, selectedDesa = "") {
                if (!kecamatanID) return;
                console.log("Loading Desa untuk Kecamatan ID:", kecamatanID);

                $('#desa_edit').html('<option value="">Memuat...</option>');

                $.get("{{ route('get.kelurahan') }}", { kecamatan_id: kecamatanID })
                    .done(function (data) {
                        console.log("Data Desa:", data);
                        let options = '<option value="" disabled selected>Pilih Kelurahan</option>';
                        $.each(data, function (index, kel) {
                            options += `<option value="${kel.kode}">${kel.name}</option>`;
                        });
                        $('#desa_edit').html(options);

                        if (selectedDesa) {
                            $('#desa_edit').val(selectedDesa);
                            console.log("Desa Selected:", selectedDesa);
                            $('#desa_edit').trigger('change');
                        }
                    })
                    .fail(function () {
                        console.error("Gagal mengambil data kelurahan.");
                    });
            }

            // Event saat Provinsi dipilih ulang
            $('#provinsi_edit').on('change', function () {
                let provinsiID = $(this).val();
                console.log("Provinsi dipilih:", provinsiID);
                loadKabupaten(provinsiID);
            });

            // Event saat Kabupaten dipilih ulang
            $('#kabupaten_edit').on('change', function () {
                let kabupatenID = $(this).val();
                console.log("Kabupaten dipilih:", kabupatenID);
                loadKecamatan(kabupatenID);
            });

            // Event saat Kecamatan dipilih ulang
            $('#kecamatan_edit').on('change', function () {
                let kecamatanID = $(this).val();
                console.log("Kecamatan dipilih:", kecamatanID);
                loadDesa(kecamatanID);
            });

            // Event saat tombol "Lengkapi" diklik
            $('.edit-btn').on('click', function () {
                // Ambil data-id dari tombol yang diklik
                let dokterId = $(this).data('id');

                // Contoh: Ambil data pasien dari server (opsional)
                $.get(`/api/get-dokter-all/${dokterId}`, function (data) {
                    $('#dokterid_update').val(data.dokter.id);
                    $('#nama_edit').val(data.dokter.users).trigger('change');
                    $('#kode_edit').val(data.dokter.kode);
                    $('#poli_edit').val(data.dokter.poli).trigger('change');
                    $('#nik_edit').val(data.dokter.nik);
                    $('#npwp_edit').val(data.dokter.npwp);
                    $('#kode_satu_edit').val(data.dokter.kode_satu);
                    $('#str_edit').val(data.dokter.str);
                    $('#expstr_edit').val(data.dokter.exp_str);
                    $('#sip_edit').val(data.dokter.sip);
                    $('#expspri_edit').val(data.dokter.exp_spri);
                    $('#tgl_masuk_edit').val(data.dokter.tgl_masuk);
                    $('#rt_edit').val(data.dokter.rt);
                    $('#rw_edit').val(data.dokter.rw);
                    $('#kode_pos_edit').val(data.dokter.kode_pos);
                    $('#alamat_edit').val(data.dokter.alamat);
                    $('#seks_edit').val(data.dokter.seks).trigger('change');
                    $('#goldar_edit').val(data.dokter.goldar).trigger('change');
                    $('#pernikahan_edit').val(data.dokter.pernikahan).trigger('change');
                    $('#kewarganegaraan_edit').val(data.dokter.kewarganegaraan).trigger('change');
                    $('#agama_edit').val(data.dokter.agama).trigger('change');
                    $('#pendidikan_edit').val(data.dokter.pendidikan).trigger('change');
                    $('#telepon_edit').val(data.dokter.telepon);
                    $('#suku_edit').val(data.dokter.suku).trigger('change');
                    $('#bangsa_edit').val(data.dokter.bangsa).trigger('change');
                    $('#bahasa_edit').val(data.dokter.bahasa).trigger('change');
                    $('#tempat_lahir_edit').val(data.dokter.tempat_lahir);
                    $('#tgl_lahir_edit').val(data.dokter.tanggal_lahir).trigger('change');
                    $('#posker_edit').val(data.dokter.status_pegawaian).trigger('change');

                    if (data.dokter.provinsi_kode) {
                        $('#provinsi_edit').val(data.dokter.provinsi_kode).trigger('change');
                            loadKabupaten(data.dokter.provinsi_kode, data.dokter.kabupaten_kode, function (kabupatenID) {
                            loadKecamatan(kabupatenID, data.dokter.kecamatan_kode, function (kecamatanID) {
                                loadDesa(kecamatanID, data.dokter.desa_kode);
                            });
                        });
                    }
                    let list = '';
                    if (data.dokter && data.dokter.verifikasi && Array.isArray(data.dokter.verifikasi.pendidikan)) {
                        data.dokter.verifikasi.pendidikan.forEach((item, index) => {
                            list += `
                                <div class="row align-items-end mb-3">
                                    <input type="hidden" name="pendidikan[${index}][kode]" value="${item.kode}">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Sekolah ${item.kode}</label>
                                        <input type="text" name="pendidikan[${index}][nama_sekolah]" class="form-control" value="${item.nama_sekolah ?? ''}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Tahun Lulus ${item.kode}</label>
                                        <input type="month" name="pendidikan[${index}][tahun_lulus]" class="form-control" value="${item.tahun_lulus ?? ''}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Ijazah ${item.kode}</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="ijasah-${index}" name="pendidikan[${index}][ijasah]">
                                            <label class="custom-file-label" for="ijasah-${index}">${item.ijasah ? item.ijasah.split('/').pop() : 'Pilih file'}</label>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        $('#editdokterModal .pendidikan-list_edit').html(list);
                    } else {
                        $('#editdokterModal .pendidikan-list_edit').html('<div class="text-muted">Belum ada data pendidikan.</div>');
                    }

                    // Spesialis
                    let spesialisList = '';
                    data.dokter.verifikasi.spesialis.forEach((item, index) => {
                        spesialisList += `
                            <div class="row align-items-end mb-3 spesialis-item_edit">
                                <div class="col-md-3">
                                    <label class="form-label">Nama Spesialis</label>
                                    <input type="text" name="spesialis[${index}][nama]" class="form-control" value="${item.nama ?? ''}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Institusi</label>
                                    <input type="text" name="spesialis[${index}][institusi]" class="form-control" value="${item.institusi ?? ''}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Tahun Lulus</label>
                                    <input type="month" name="spesialis[${index}][tahun_lulus]" class="form-control" value="${item.tahun_lulus ?? ''}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Ijazah</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="spesialis-ijasah-${index}" name="spesialis[${index}][ijasah]">
                                        <label class="custom-file-label" for="spesialis-ijasah-${index}">${item.ijasah ?? 'Pilih file'}</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-remove-spesialis_edit">×</button>
                                </div>
                            </div>
                        `;
                    });
                    $('#spesialis-container_edit').html(spesialisList);

                    // Pelatihan
                    let pelatihanList = '';
                    data.dokter.verifikasi.pelatihan.forEach((item, index) => {
                        pelatihanList += `
                            <div class="row align-items-end mb-3 pelatihan-item_edit">
                                <div class="col-md-3">
                                    <label class="form-label">Nama Pelatihan</label>
                                    <input type="text" name="pelatihan[${index}][nama]" class="form-control" value="${item.nama ?? ''}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Penyelenggara</label>
                                    <input type="text" name="pelatihan[${index}][penyelenggara]" class="form-control" value="${item.penyelenggara ?? ''}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Tahun</label>
                                    <input type="month" name="pelatihan[${index}][tahun]" class="form-control" value="${item.tahun ?? ''}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Upload Sertifikat</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="pelatihan-sertifikat-${index}" name="pelatihan[${index}][sertifikat]">
                                        <label class="custom-file-label" for="pelatihan-sertifikat-${index}">${item.sertifikat ?? 'Pilih file'}</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-remove-pelatihan_edit">×</button>
                                </div>
                            </div>
                        `;
                    });
                    $('#pelatihan-container_edit').html(pelatihanList);

                    // Informasi Bank
                    $('#nama_bank_edit').val(data.dokter.verifikasi.nama_bank).trigger('change');
                    $('#norek_edit').val(data.dokter.verifikasi.norek);
                    $('#cabang_bank_edit').val(data.dokter.verifikasi.cabang_bank);

                }).fail(function (error) {
                    console.error("Gagal mengambil data pasien:", error);
                });
            });
        });

        $(document).ready(function () {
            // Fitur Tambah Spesialis
            $('#tambah-spesialis_edit').on('click', function () {
                let index = $('#spesialis-container_edit .spesialis-item_edit').length;  // Ambil jumlah item spesialis saat ini
                let newItem = `
                    <div class="row align-items-end mb-3 spesialis-item_edit">
                        <div class="col-md-3">
                            <label class="form-label">Nama Spesialis</label>
                            <input type="text" name="spesialis[${index}][nama]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Institusi</label>
                            <input type="text" name="spesialis[${index}][institusi]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tahun Lulus</label>
                            <input type="month" name="spesialis[${index}][tahun_lulus]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ijazah</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="spesialis-ijasah-${index}" name="spesialis[${index}][ijasah]">
                                <label class="custom-file-label" for="spesialis-ijasah-${index}">Pilih file</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-remove-spesialis_edit">×</button>
                        </div>
                    </div>
                `;
                $('#spesialis-container_edit').append(newItem);
            });

            // Fitur Hapus Spesialis
            $(document).on('click', '.btn-remove-spesialis_edit', function () {
                $(this).closest('.spesialis-item_edit').remove();
            });

            // Fitur Tambah Pelatihan
            $('#tambah-pelatihan_edit').on('click', function () {
                let index = $('#pelatihan-container_edit .pelatihan-item_edit').length;  // Ambil jumlah item pelatihan saat ini
                let newItem = `
                    <div class="row align-items-end mb-3 pelatihan-item_edit">
                        <div class="col-md-3">
                            <label class="form-label">Nama Pelatihan</label>
                            <input type="text" name="pelatihan[${index}][nama]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Penyelenggara</label>
                            <input type="text" name="pelatihan[${index}][penyelenggara]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tahun</label>
                            <input type="month" name="pelatihan[${index}][tahun]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Upload Sertifikat</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="pelatihan-sertifikat-${index}" name="pelatihan[${index}][sertifikat]">
                                <label class="custom-file-label" for="pelatihan-sertifikat-${index}">Pilih file</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-remove-pelatihan_edit">×</button>
                        </div>
                    </div>
                `;
                $('#pelatihan-container_edit').append(newItem);
            });

            // Fitur Hapus Pelatihan
            $(document).on('click', '.btn-remove-pelatihan_edit', function () {
                $(this).closest('.pelatihan-item_edit').remove();
            });
        });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
    </script>

    <script>
        $(document).on('change', '.custom-file-input', function (e) {
            const fileName = e.target.files[0]?.name;
            $(this).next('.custom-file-label').html(fileName);
        });

        $('.lengkapi-btn').on('click', function () {
            let dokterId = $(this).data('id');

            $('#dokterid_verifikasi').val(dokterId);

            $.get(`/api/get-dokter/${dokterId}`, function (data) {
                let list = '';
                data.pendidikans.forEach((item, index) => {
                    list += `
                        <div class="row align-items-end mb-3">
                            <input type="hidden" name="pendidikan[${index}][kode]" value="${item.kode}">

                            <div class="col-md-6">
                                <label class="form-label">Nama Sekolah ${item.kode}</label>
                                <input type="text" name="pendidikan[${index}][nama_sekolah]" class="form-control" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Tahun Lulus ${item.kode}</label>
                                <input type="month" name="pendidikan[${index}][tahun_lulus]" class="form-control" required>
                            </div>

                           <div class="col-md-4">
                                <label class="form-label">Ijazah ${item.kode}</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="ijasah-${index}" name="pendidikan[${index}][ijasah]">
                                    <label class="custom-file-label" for="ijasah-${index}">Pilih file</label>
                                </div>
                            </div>

                        </div>
                    `;
                });

                $('#lengkapiModal .pendidikan-list').html(list);
                $('#modalVerifikasi').modal('show');


            });

            let spesialisIndex = 0;

            $('#tambah-spesialis').on('click', function () {
                const html = `
                    <div class="row align-items-end mb-3 spesialis-item">
                        <div class="col-md-3">
                            <label class="form-label">Nama Spesialis</label>
                            <input type="text" name="spesialis[${spesialisIndex}][nama]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Institusi</label>
                            <input type="text" name="spesialis[${spesialisIndex}][institusi]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tahun Lulus</label>
                            <input type="month" name="spesialis[${spesialisIndex}][tahun_lulus]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ijazah</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="spesialis-ijasah-${spesialisIndex}" name="spesialis[${spesialisIndex}][ijasah]">
                                <label class="custom-file-label" for="spesialis-ijasah-${spesialisIndex}">Pilih file</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-remove-spesialis">×</button>
                        </div>
                    </div>
                `;
                $('#spesialis-container').append(html);
                spesialisIndex++;
            });

            $(document).on('click', '.btn-remove-spesialis', function () {
                $(this).closest('.spesialis-item').remove();
            });

            let pelatihanIndex = 0;

            $('#tambah-pelatihan').on('click', function () {
                const html = `
                    <div class="row align-items-end mb-3 pelatihan-item">
                        <div class="col-md-3">
                            <label class="form-label">Nama Pelatihan</label>
                            <input type="text" name="pelatihan[${pelatihanIndex}][nama]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Penyelenggara</label>
                            <input type="text" name="pelatihan[${pelatihanIndex}][penyelenggara]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tahun</label>
                            <input type="month" name="pelatihan[${pelatihanIndex}][tahun]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Upload Sertifikat</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="pelatihan-sertifikat-${pelatihanIndex}" name="pelatihan[${pelatihanIndex}][sertifikat]">
                                <label class="custom-file-label" for="pelatihan-sertifikat-${pelatihanIndex}">Pilih file</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-remove-pelatihan">×</button>
                        </div>
                    </div>
                `;
                $('#pelatihan-container').append(html);
                    pelatihanIndex++;
                });

                // Tampilkan nama file
                $(document).on('change', '.custom-file-input', function (e) {
                    const fileName = e.target.files[0]?.name;
                    $(this).next('.custom-file-label').html(fileName);
                });

                // Hapus baris pelatihan
                $(document).on('click', '.btn-remove-pelatihan', function () {
                    $(this).closest('.pelatihan-item').remove();
                });


        });
    </script>

    <script>
        $('#addFormdokter').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#adddokterModal').modal('hide');
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
                        let errorMessage = "Terjadi kesalahan dalam menyimpan data!";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            });


        $(document).on('click', '.delete-data-dokter', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-dokter');

            $('#dokterid_delete').val(id);
            $('#deleteTextdokter').html(
            `<span>Apa Anda yakin ingin menghapus data dokter <b>${name}</b> ?</span>`);
        });

        $('#deleteFormdokter').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#jadwaldokterModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Dokter!',
                    });
                }
            });
        });

        $('#lengkapiFormdokter').on('submit', function(e) {
            e.preventDefault();

            let form = $(this)[0];
            let url = $(this).attr('action');
            let formData = new FormData(form); // Kunci utamanya di sini

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                processData: false,  // <-- wajib untuk FormData
                contentType: false,  // <-- wajib untuk FormData
                success: function(response) {
                    if (response.success) {
                        $('#lengkapiModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat Melengkapi Data Dokter. Silakan coba lagi!',
                    });
                }
            });
        });

        $('#updatedokterForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editdokterModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Dokter!',
                    });
                }
            });
        });
        $('#updatedokterForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editdokterModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Dokter!',
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#provinsi').change(function () {
                let provinsiID = $(this).val();
                $('#kabupaten').html('<option value="">Memuat...</option>');
                $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
                $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');

                if (provinsiID) {
                    $.get("{{ route('get.kabupaten') }}", { provinsi_id: provinsiID }, function (data) {
                        let options = '<option value="">Pilih Kabupaten</option>';
                        $.each(data, function (index, kab) {
                            options += `<option value="${kab.kode}">${kab.name}</option>`;
                        });
                        $('#kabupaten').html(options);
                    });
                }
            });

            $('#kabupaten').change(function () {
                let kabupatenID = $(this).val();
                $('#kecamatan').html('<option value="">Memuat...</option>');
                $('#kelurahan').html('<option value="">Pilih Kelurahan</option>');

                if (kabupatenID) {
                    $.get("{{ route('get.kecamatan') }}", { kabupaten_id: kabupatenID }, function (data) {
                        let options = '<option value="">Pilih Kecamatan</option>';
                        $.each(data, function (index, kec) {
                            options += `<option value="${kec.kode}">${kec.name}</option>`;
                        });
                        $('#kecamatan').html(options);
                    });
                }
            });

            $('#kecamatan').change(function () {
                let kecamatanID = $(this).val();
                $('#kelurahan').html('<option value="">Memuat...</option>');

                if (kecamatanID) {
                    $.get("{{ route('get.kelurahan') }}", { kecamatan_id: kecamatanID }, function (data) {
                        let options = '<option value="">Pilih Kelurahan</option>';
                        $.each(data, function (index, kel) {
                            options += `<option value="${kel.kode}">${kel.name}</option>`;
                        });
                        $('#desa').html(options);
                    });
                }
            });
        });
    </script>

    <script>
        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function() {
                document.getElementById('profileImage').src = reader.result;
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <script>
        let dokterApiMap_edit = {};

        $(document).ready(function () {
            // Ambil data dokter dari API BPJS
            fetch("{{ route('pcare.dokter') }}")
                .then(response => response.json())
                .then(result => {
                    result.data.list.forEach(dokter => {
                        dokterApiMap_edit[dokter.nmDokter.toLowerCase()] = dokter.kdDokter;
                    });
                });

            // Saat dokter dipilih dari select
            $('#nama_edit').on('change', function () {
                let selectedName = $(this).find(':selected').data('name').toLowerCase();

                // Cari kecocokan nama di API
                let matchedKode = null;
                for (const namaApi in dokterApiMap_edit) {
                    if (namaApi.includes(selectedName) || selectedName.includes(namaApi)) {
                        matchedKode = dokterApiMap_edit[namaApi];
                        break;
                    }
                }

                $('#kode_edit').val(matchedKode ?? '');
            });
        });
    </script>

    <script>
        let dokterApiMap = {};

        $(document).ready(function () {
            // Ambil data dokter dari API BPJS
            fetch("{{ route('pcare.dokter') }}")
                .then(response => response.json())
                .then(result => {
                    result.data.list.forEach(dokter => {
                        dokterApiMap[dokter.nmDokter.toLowerCase()] = dokter.kdDokter;
                    });
                });

            // Saat dokter dipilih dari select
            $('#nama').on('change', function () {
                let selectedName = $(this).find(':selected').data('name').toLowerCase();

                // Cari kecocokan nama di API
                let matchedKode = null;
                for (const namaApi in dokterApiMap) {
                    if (namaApi.includes(selectedName) || selectedName.includes(namaApi)) {
                        matchedKode = dokterApiMap[namaApi];
                        break;
                    }
                }

                $('#kode').val(matchedKode ?? '');
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#doktertabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    {
                        text: '<i class="fas fa-plus"></i> Tambah',
                        type: 'button',
                        className: 'btn btn-primary',
                        action: function () {
                                $('#adddokterModal').modal('show'); // <== Bootstrap 4-compatible
                        }
                    }
                ],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                }
            }).buttons().container().appendTo('#doktertabel_wrapper .col-md-6:eq(0)');
        });
    </script>
