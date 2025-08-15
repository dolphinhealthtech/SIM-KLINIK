
    <script>
        // Script untuk modal panggil
        $(document).on('click', '.panggil-btn', function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');

            $('#panggilText').html(`<span>Apakah Anda yakin ingin memanggil pasien <b>${nama}</b>?</span>`);

            // Ketika tombol konfirmasi diklik
            $('#konfirmasiPanggil').off('click').on('click', function() {
                $.ajax({
                    url: "{{ route('pasien.panggil', ['id' => '__ID__']) }}".replace('__ID__', id),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#panggilModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: true
                            }).then(() => {
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
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat memanggil pasien.'
                        });
                    }
                });
            });
        });
    </script>

    <script>
        $('#aktif_penjamin_2').on('change', function () {
            const aktif = $(this).is(':checked');
            $('#penjamin_2, #penjamin_2_info').prop('disabled', !aktif);
        });

        $('#aktif_penjamin_3').on('change', function () {
            const aktif = $(this).is(':checked');
            $('#penjamin_3, #penjamin_3_info').prop('disabled', !aktif);
        });
            $('#aktif_penjamin_2_edit').on('change', function () {
            const aktif = $(this).is(':checked');
            $('#penjamin_2_edit, #penjamin_2_info_edit').prop('disabled', !aktif);
        });

        $('#aktif_penjamin_3_edit').on('change', function () {
            const aktif = $(this).is(':checked');
            $('#penjamin_3_edit, #penjamin_3_info_edit').prop('disabled', !aktif);
        });

    </script>

    <script>
        function formatDate_edit(dateString) {
                let parts = dateString.split("-"); // Pisahkan berdasarkan "-"
                return `${parts[2]}-${parts[1]}-${parts[0]}`; // Susun ulang menjadi "yyyy-MM-dd"
            }

            function updateInputValue_edit(inputElement, newValue) {
                if (inputElement.value.trim() !== newValue) {
                    inputElement.value = newValue;
                }
            }

            function handleClick_edit() {
                const icon = document.getElementById("syncIcon_edit"); // Ambil ikon di dalam tombol
                icon.classList.add('fa-spin'); // Mulai animasi putar

                let nik = document.getElementById("nik_edit").value.trim();
                let noka = document.getElementById("noka_edit").value.trim();

                // Tentukan parameter untuk API
                let param, apiUrl;

                if (noka === "" && nik !== "") {
                    // Gunakan NIK
                    param = nik;
                    apiUrl = `{{ route('pcare.nik', ':param') }}`.replace(':param', param);
                } else if (nik === "" && noka !== "") {
                    // Gunakan NoKartu
                    param = noka;
                    apiUrl = `{{ route('pcare.noka', ':param') }}`.replace(':param', param);
                } else if (nik !== "" && noka !== "") {
                    // Bisa pilih prioritas, misal pakai NIK
                    param = nik;
                    apiUrl = `{{ route('pcare.nik', ':param') }}`.replace(':param', param);
                } else {
                    console.error("NIK dan NoKartu tidak boleh kosong!");
                    return;
                }

                // Fetch data dari API
                fetch(apiUrl, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                    }
                })
                .then(response => response.json()) // Ubah respons ke JSON
                .then(responseData => {
                    if (responseData.status === "success" && responseData.data) {
                        let data = responseData.data;

                        // Ambil elemen input
                        let nokaInput = document.getElementById("noka_edit");
                        let jenisKartuInput = document.getElementById("jenis_kartu_edit");
                        let kelasInput = document.getElementById("kelas_edit");
                        let provideInput = document.getElementById("provide_edit");
                        let expbpjsInput = document.getElementById("tgl_exp_bpjs_edit");
                        let tgllahirInput = document.getElementById("tgllahir_edit");
                        let namaInput = document.getElementById("nama_edit");
                        let kodeprovide = document.getElementById("kodeprovide_edit");
                        let hubungankeluarga = document.getElementById("hubungan_keluarga_edit");

                        // Update nilai input hanya jika berbeda
                        updateInputValue_edit(nokaInput, data.noKartu);
                        updateInputValue_edit(jenisKartuInput, data.jnsPeserta.nama);
                        updateInputValue_edit(kelasInput, data.jnsKelas.nama);
                        updateInputValue_edit(provideInput, data.kdProviderPst.nmProvider);
                        updateInputValue_edit(kodeprovide, data.kdProviderPst.kdProvider);
                        updateInputValue_edit(hubungankeluarga, data.hubunganKeluarga);
                        if (data.tglAkhirBerlaku) {
                            updateInputValue_edit(expbpjsInput, formatDate_edit(data.tglAkhirBerlaku));
                        }
                        if (data.tglLahir) {
                            updateInputValue_edit(tgllahirInput, formatDate_edit(data.tglLahir));
                        }
                        updateInputValue_edit(namaInput, data.nama);

                        // Langsung ubah warna background
                        nokaInput.style.backgroundColor = '#fff9c4';
                        jenisKartuInput.style.backgroundColor = '#fff9c4';
                        kelasInput.style.backgroundColor = '#fff9c4';
                        provideInput.style.backgroundColor = '#fff9c4';
                        expbpjsInput.style.backgroundColor = '#fff9c4';

                        var ket = data.aktif || false;

                        if (ket === true) {
                            $('#bpjs_error_edit').hide();
                        } else {
                            $('#bpjs_error_edit').text(data.ketAktif || 'Status tidak aktif').show();
                        }

                        const kode = {
                            KPFK: "{{ $kodefasyankes->KPFK }}"
                        };
                        console.log(kode);
                        if (kode.KPFK === data.kdProviderPst.kdProvider) {
                            $('#bpjs_error_edit_1').hide();
                        } else {
                            $('#bpjs_error_edit_1').text('Faskes BPJS tidak Sesuai').show();
                        }

                            // **Panggil route tambahan setelah namaInput diperbarui**
                            let noihsApiUrl = `{{ route('satusehat.nik', ':nik') }}`.replace(':nik', nik); // Sesuaikan URL
                            fetch(noihsApiUrl, {
                                method: "GET",
                                headers: {
                                    "Content-Type": "application/json",
                                }
                            })
                            .then(response => response.json())
                            .then(noihsData => {
                                let noihsInput = document.getElementById("noihs_edit"); // Ambil elemen input noihs
                                if (noihsData.status === "success" && noihsData.data) {
                                    updateInputValue_edit(noihsInput, noihsData.data); // Update No IHS
                                    noihsInput.style.backgroundColor = '#fff9c4';
                                }
                            })
                            .catch(error => {
                                console.error("Gagal mengambil No IHS:", error);
                            }).finally(() => {
                                icon.classList.remove('fa-spin'); // Stop animasi setelah proses selesai
                            });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: "Data tidak ditemukan."
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: "Gagal mengambil data dari API."
                        });
                    icon.classList.remove('fa-spin');

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
            $(document).on('click', '.edit-btn', function () {
                let pasienId = $(this).data('id');
                console.log("Edit Pasien ID:", pasienId);

                $.get(`/api/get-pasien/${pasienId}`)
                    .done(function (data) {
                        $('#nomor_rm_edit').val(data.no_rm);
                        $('#nama_edit').val(data.nama);
                        $('#nik_edit').val(data.nik);
                        $('#tempat_lahir_edit').val(data.tempat_lahir);
                        $('#tgllahir_edit').val(data.tanggal_lahir);
                        $('#rt_edit').val(data.rt);
                        $('#rw_edit').val(data.rw);
                        $('#kode_pos_edit').val(data.kode_pos);
                        $('#alamat_edit').val(data.alamat);
                        $('#noka_edit').val(data.no_bpjs);
                        $('#noihs_edit').val(data.kode_ihs);
                        $('#jenis_kartu_edit').val(data.jenis_Kartu_bpjs);
                        $('#kelas_edit').val(data.kelas_bpjs);
                        $('#provide_edit').val(data.provide);
                        $('#tgl_exp_bpjs_edit').val(data.tgl_exp_bpjs);
                        $('#goldar_edit').val(data.goldar).trigger('change');
                        $('#seks_edit').val(data.seks).trigger('change');
                        $('#pernikahan_edit').val(data.pernikahan).trigger('change');
                        $('#kewarganegaraan_edit').val(data.kewarganegaraan).trigger('change');
                        $('#agama_edit').val(data.agama).trigger('change');
                        $('#pendidikan_edit').val(data.pendidikan).trigger('change');
                        $('#status_kerja_edit').val(data.pekerjaan).trigger('change');
                        $('#telepon_edit').val(data.telepon);
                        $('#user_edit').val(data.users);
                        $('#kodeprovide_edit').val(data.kodeprovide);
                        $('#hubungan_keluarga_edit').val(data.hubungan_keluarga);
                        $('#suku_edit').val(data.suku).trigger('change');
                        $('#bangsa_edit').val(data.bangsa).trigger('change');
                        $('#bahasa_edit').val(data.bahasa).trigger('change');
                        $('#email_edit').val(data.getnama?.email ?? '');

                        if (data.getnama?.profile) {
                            $('#profileImage_edit').attr('src', `/profile/${data.getnama.profile}`);
                        } else {
                            // Gunakan default jika kosong
                            $('#profileImage_edit').attr('src', `/profile/default.png`);
                        }

                        // Load Provinsi, Kabupaten, Kecamatan, Desa secara berurutan
                        if (data.provinsi_kode) {
                            $('#provinsi_edit').val(data.provinsi_kode).trigger('change');
                            loadKabupaten(data.provinsi_kode, data.kabupaten_kode, function (kabupatenID) {
                                loadKecamatan(kabupatenID, data.kecamatan_kode, function (kecamatanID) {
                                    loadDesa(kecamatanID, data.desa_kode);
                                });
                            });
                        }
                    })
                    .fail(function (error) {
                        console.error("Gagal mengambil data pasien:", error);
                    });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Event saat tombol "Lengkapi" diklik
            $('.lengkapi-btn').on('click', function () {
                // Ambil data-id dari tombol yang diklik
                let pasienId = $(this).data('id');

                // Contoh: Ambil data pasien dari server (opsional)
                $.get(`/api/get-pasien/${pasienId}`, function (data) {
                    $('#nomor_rm').val(data.no_rm);
                    $('#nama').val(data.nama);
                    $('#nik').val(data.nik);
                    $('#tgllahir').val(data.tanggal_lahir);
                    $('#noka').val(data.no_bpjs);
                    $('#noihs').val(data.kode_ihs);
                    $('#alamat').val(data.alamat);
                    $('#telepon').val(data.telepon);
                    $('#pernikahan').val(data.pernikahan);
                    $('#goldar').val(data.goldar).trigger('change');
                    $('#seks').val(data.seks).trigger('change');
                    $('#email').val(data.getnama?.email ?? '');
                    $('#bangsa').val(data.bangsa).trigger('change');
                    $('#bahasa').val(data.bahasa).trigger('change');

                    if (data.getnama?.profile) {
                        $('#profileImage').attr('src', `/profile/${data.getnama.profile}`);
                    } else {
                        // Gunakan default jika kosong
                        $('#profileImage').attr('src', `/profile/default.png`);
                    }

                    // generateCredentials();
                }).fail(function (error) {
                    console.error("Gagal mengambil data pasien:", error);
                });
            });
        });
    </script>

    <script>
        function formatDate(dateString) {
            let parts = dateString.split("-"); // Pisahkan berdasarkan "-"
            return `${parts[2]}-${parts[1]}-${parts[0]}`; // Susun ulang menjadi "yyyy-MM-dd"
        }

        function updateInputValue(inputElement, newValue) {
            if (inputElement.value.trim() !== newValue) {
                inputElement.value = newValue;
            }
        }

        function handleClick() {
            const icon = document.getElementById("syncIcon"); // Ambil ikon di dalam tombol
            icon.classList.add('fa-spin'); // Mulai animasi putar

            let nik = document.getElementById("nik").value.trim();
            let noka = document.getElementById("noka").value.trim();

            // Tentukan parameter untuk API
            let param, apiUrl;

            if (noka === "" && nik !== "") {
                // Gunakan NIK
                param = nik;
                apiUrl = `{{ route('pcare.nik', ':param') }}`.replace(':param', param);
            } else if (nik === "" && noka !== "") {
                // Gunakan NoKartu
                param = noka;
                apiUrl = `{{ route('pcare.noka', ':param') }}`.replace(':param', param);
            } else if (nik !== "" && noka !== "") {
                // Bisa pilih prioritas, misal pakai NIK
                param = nik;
                apiUrl = `{{ route('pcare.nik', ':param') }}`.replace(':param', param);
            } else {
                console.error("NIK dan NoKartu tidak boleh kosong!");
                return;
            }

            // Fetch data dari API
            fetch(apiUrl, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                }
            })
            .then(response => response.json()) // Ubah respons ke JSON
            .then(responseData => {
                if (responseData.status === "success" && responseData.data) {
                    let data = responseData.data;

                    // Ambil elemen input
                    let nokaInput = document.getElementById("noka");
                    let nikInput = document.getElementById("nik");
                    let jenisKartuInput = document.getElementById("jenis_kartu");
                    let kelasInput = document.getElementById("kelas");
                    let provideInput = document.getElementById("provide");
                    let expbpjsInput = document.getElementById("tgl_exp_bpjs");
                    let tgllahirInput = document.getElementById("tgllahir");
                    let namaInput = document.getElementById("nama");
                    let kodeprovide = document.getElementById("kodeprovide");
                    let hubungankeluarga = document.getElementById("hubungan_keluarga");

                    // Update nilai input hanya jika berbeda
                    // Update NIK jika kosong
                    if (!nikInput.value) {
                        updateInputValue(nikInput, data.noKTP);
                    }

                    // Update NoKartu jika kosong
                    if (!nokaInput.value) {
                        updateInputValue(nokaInput, data.noKartu);
                    }
                    updateInputValue(jenisKartuInput, data.jnsPeserta.nama);
                    updateInputValue(kelasInput, data.jnsKelas.nama);
                    updateInputValue(provideInput, data.kdProviderPst.nmProvider);
                    updateInputValue(kodeprovide, data.kdProviderPst.kdProvider);
                    updateInputValue(hubungankeluarga, data.hubunganKeluarga);
                    if (data.tglAkhirBerlaku) {
                        updateInputValue(expbpjsInput, formatDate(data.tglAkhirBerlaku));
                    }
                    if (data.tglLahir) {
                        updateInputValue(tgllahirInput, formatDate(data.tglLahir));
                    }
                    updateInputValue(namaInput, data.nama);

                    // Langsung ubah warna background
                    nokaInput.style.backgroundColor = '#fff9c4';
                    jenisKartuInput.style.backgroundColor = '#fff9c4';
                    kelasInput.style.backgroundColor = '#fff9c4';
                    provideInput.style.backgroundColor = '#fff9c4';
                    expbpjsInput.style.backgroundColor = '#fff9c4';

                    var ket = data.aktif || false;
                        if (ket === true) {
                            $('#bpjs_error').hide();
                        } else {
                            $('#bpjs_error').text(data.ketAktif || 'Status tidak aktif').show();
                        }
                        const kode = {
                            KPFK: "{{ $kodefasyankes->KPFK }}"
                        };
                        console.log(kode);
                        if (kode.KPFK === data.kdProviderPst.kdProvider) {
                            $('#bpjs_error1').hide();
                        } else {
                            $('#bpjs_error1').text('Faskes BPJS tidak Sesuai').show();
                        }

                        // **Panggil route tambahan setelah namaInput diperbarui**
                        let noihsApiUrl = `{{ route('satusehat.nik', ':nik') }}`.replace(':nik', nik); // Sesuaikan URL
                        fetch(noihsApiUrl, {
                            method: "GET",
                            headers: {
                                "Content-Type": "application/json",
                            }
                        })
                        .then(response => response.json())
                        .then(noihsData => {
                            let noihsInput = document.getElementById("noihs"); // Ambil elemen input noihs
                            if (noihsData.status === "success" && noihsData.data) {
                                updateInputValue(noihsInput, noihsData.data); // Update No IHS
                                noihsInput.style.backgroundColor = '#fff9c4';
                            }
                        })
                        .catch(error => {
                            console.error("Gagal mengambil No IHS:", error);
                        }).finally(() => {
                            icon.classList.remove('fa-spin'); // Stop animasi setelah proses selesai
                        });


                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: "Data tidak ditemukan."
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "Gagal mengambil data dari API."
                });
                icon.classList.remove('fa-spin');

            });
        }
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
        function previewImage_edit(event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function() {
                document.getElementById('profileImage_edit').src = reader.result;
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
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
        $(document).ready(function() {
            $("#userstabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": false,
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ total entri)",
                }
            }).buttons().container().appendTo('#userstabel_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#formLengkapi').on('submit', function(e) {
                e.preventDefault(); // cegah submit default

                let form = $(this);
                let url = form.attr('action');
                let method = form.attr('method');

                let formData = new FormData(this);

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false, // penting untuk FormData
                    contentType: false, // penting untuk FormData
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Menyimpan...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                    },
                    success: function(response) {
                        Swal.close();
                        if(response.success) {
                            $('#lengkapiModal').modal('hide'); // ganti sesuai id modal
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: true
                            }).then(() => {
                                $('.modal-backdrop').remove(); // hapus backdrop jika masih ada
                                location.reload(); // reload halaman jika perlu
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan',
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        if(xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = '';
                            for(let key in errors) {
                                errorMessage += errors[key] + '\n';
                            }
                            Swal.fire('Validasi Error', errorMessage, 'error');
                        } else {
                            Swal.fire('Error', 'Terjadi kesalahan server', 'error');
                        }
                    }
                });
            });
        });
    </script>

