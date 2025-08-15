
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tanggal_pemeriksaan_skd = document.getElementById('tanggal_pemeriksaan_skd');
            const tanggal_mulai_istirahat_skd = document.getElementById('tanggal_mulai_istirahat_skd');
            const tanggal_akhir_istirahat_skd = document.getElementById('tanggal_akhir_istirahat_skd');
            const tanggal_periksa_laboratorium = document.getElementById('tanggal_periksa_laboratorium');
            const tanggal_periksa_radiologi = document.getElementById('tanggal_periksa_radiologi');

            tanggal_pemeriksaan_skd.addEventListener('click', function() {
                tanggal_pemeriksaan_skd.showPicker?.() || tanggal_pemeriksaan_skd
                    .focus(); // Buka date picker jika didukung, atau fokus
            });

            tanggal_mulai_istirahat_skd.addEventListener('click', function() {
                tanggal_mulai_istirahat_skd.showPicker?.() || tanggal_mulai_istirahat_skd
                    .focus(); // Buka date picker jika didukung, atau fokus
            });

            tanggal_akhir_istirahat_skd.addEventListener('click', function() {
                tanggal_akhir_istirahat_skd.showPicker?.() || tanggal_akhir_istirahat_skd
                    .focus(); // Buka date picker jika didukung, atau fokus
            });
            tanggal_periksa_laboratorium.addEventListener('click', function() {
                tanggal_periksa_laboratorium.showPicker?.() || tanggal_periksa_laboratorium
                    .focus(); // Buka date picker jika didukung, atau fokus
            });

            tanggal_periksa_radiologi.addEventListener('click', function() {
                tanggal_periksa_radiologi.showPicker?.() || tanggal_periksa_radiologi
                    .focus(); // Buka date picker jika didukung, atau fokus
            });

            // Handle checkbox buta warna
            const butaWarnaCheck = document.getElementById('buta_warna_check');
            const butaWarnaStatus = document.querySelector('select[name="buta_warna_status"]');

            if (butaWarnaCheck && butaWarnaStatus) {
                butaWarnaCheck.addEventListener('change', function() {
                    if (this.checked) {
                        butaWarnaStatus.value = 'Ya';
                    } else {
                        butaWarnaStatus.value = 'Tidak';
                    }
                });
            }

            // Handle radio button penyebab kematian lainnya
            const radioLainnya = document.getElementById('radio_lainnya');
            const penyebabLainnya = document.getElementById('penyebab_lainnya');

            if (radioLainnya && penyebabLainnya) {
                radioLainnya.addEventListener('change', function() {
                    if (this.checked) {
                        penyebabLainnya.style.display = 'block';
                    } else {
                        penyebabLainnya.style.display = 'none';
                    }
                });
            }

            // Handle checkbox penandatangan
            const penandatanganCheck = document.getElementById('penandatangan_check');
            const penandatanganSelect = document.getElementById('penandatangan');

            if (penandatanganCheck && penandatanganSelect) {
                penandatanganCheck.addEventListener('change', function() {
                    if (this.checked) {
                        penandatanganSelect.disabled = false;
                    } else {
                        penandatanganSelect.disabled = true;
                        penandatanganSelect.value = '';
                    }
                });
            }
        });
    </script>

    <script>
        function getCurrentDateTimeLocal() {
            const now = new Date();

            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // bulan dimulai dari 0
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }


        $(document).ready(function() {
            const datetimeInputRadiologi = document.getElementById('tanggal_periksa_radiologi');
            const datetimeInputLaboratorium = document.getElementById('tanggal_periksa_laboratorium');
            const datetimeInputSkd = document.getElementById('tanggal_pemeriksaan_skd');

            // Isi otomatis dengan waktu sekarang
            const now = getCurrentDateTimeLocal();
            if (datetimeInputRadiologi) datetimeInputRadiologi.value = now;
            if (datetimeInputLaboratorium) datetimeInputLaboratorium.value = now;
            if (datetimeInputSkd) datetimeInputSkd.value = now;

            datetimeInputRadiologi.addEventListener('click', function() {
                this.showPicker && this.showPicker(); // untuk browser yang support
            });
            datetimeInputLaboratorium.addEventListener('click', function() {
                this.showPicker && this.showPicker(); // untuk browser yang support
            });
            datetimeInputSkd.addEventListener('click', function() {
                this.showPicker && this.showPicker(); // untuk browser yang support
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#bidang_laboratorium').on('change', function() {
                // Ambil id dari atribut data-id, bukan dari value
                let id = $(this).find(':selected').data('id');

                $('#pemeriksaan_laboratorium').empty().append(
                    '<option disabled selected>Loading...</option>');

                $.ajax({
                    url: `/api/get-pemeriksaan-laboratorium/${id}`,
                    type: 'GET',
                    success: function(data) {
                        $('#pemeriksaan_laboratorium').empty().append(
                            '<option value="" disabled selected>-- Pilih --</option>');
                        $.each(data, function(key, value) {
                            $('#pemeriksaan_laboratorium').append(
                                `<option value="${value.nama_sublaboratorium_bidang}">${value.nama_sublaboratorium_bidang}</option>`
                            );
                        });
                    }
                });
            });
        });
    </script>

    <script>
        let selectedRow = null;
        let labData = [];

        function refreshTable() {
            let tbody = $('#lab_table tbody');
            tbody.empty();

            labData.forEach((item, index) => {
                tbody.append(`
                <tr data-index="${index}" class="lab-row">
                    <td>${index + 1}</td>
                    <td>${item}</td>
                </tr>
            `);
            });

            // Update hidden input as JSON string
            $('#lab_table_hidden').val(JSON.stringify(labData));

            console.log('Data : ', JSON.stringify(labData));
        }

        // Tambah data (dengan cek duplikat)
        $('#btn-tambah-lab').on('click', function() {
            let selected = $('#pemeriksaan_laboratorium').val();

            if (!selected) {
                Swal.fire('Pilih Pemeriksaan terlebih dahulu.', '', 'warning');
                return;
            }

            // Cek duplikat
            if (labData.includes(selected)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Duplikat',
                    text: `"${selected}" sudah ada dalam tabel.`,
                    showConfirmButton: true
                });
                return;
            }

            labData.push(selected);
            refreshTable();
            $('#pemeriksaan_laboratorium').val(null).trigger('change');
        });

        // Pilih baris
        $(document).on('click', '.lab-row', function() {
            $('.lab-row').removeClass('table-primary');
            $(this).addClass('table-primary');
            selectedRow = $(this).data('index');
        });

        // Hapus data
        $('#btn-hapus-lab').on('click', function() {
            if (selectedRow === null) {
                Swal.fire('Pilih baris yang ingin dihapus.', '', 'info');
                return;
            }

            labData.splice(selectedRow, 1);
            selectedRow = null;
            refreshTable();
        });
    </script>

    <script>
        $('#btn-print-lab').on('click', function() {
            const labData = $('#lab_table_hidden').val();
            const diagnosa = $('#diagnosa_laboratorium').val();
            const tanggal = $('#tanggal_periksa_laboratorium').val();
            const catatan = $('#catatan_dokter_laboratorium').val();
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const poli = $('#poli').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const penjamin = $('#penjamin').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!labData || !diagnosa || !tanggal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan data pemeriksaan dan tanggal periksa sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak Permintaan Laboratorium?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form dinamis
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('laboratorium.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'lab_table_hidden',
                        value: labData
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosa_laboratorium',
                        value: diagnosa
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_periksa_laboratorium',
                        value: tanggal
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'catatan_dokter_laboratorium',
                        value: catatan
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'poli',
                        value: poli
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'penjamin',
                        value: penjamin
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    // Setelah submit, redirect ke route dokter
                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000); // delay 1 detik agar PDF sempat terbuka
                }
            });
        });
    </script>

    <script>
        let selectedRadRow = null;
        let radData = [];

        function refreshRadTable() {
            let tbody = $('#rad_table tbody');
            tbody.empty();

            radData.forEach((item, index) => {
                tbody.append(`
                <tr data-index="${index}" class="rad-row">
                    <td>${index + 1}</td>
                    <td>${item.pemeriksaan}</td>
                    <td>${item.jenis_posisi} - ${item.posisi}</td>
                    <td>${item.metode}</td>
                </tr>
            `);
            });

            $('#rad_table_hidden').val(JSON.stringify(radData));
            console.log('Data : ', JSON.stringify(radData));
        }

        $('#btn-tambah-rad').on('click', function() {
            const pemeriksaan = $('#pemeriksaan_radiologi').val();
            const jenisPosisi = $('#jenis_posisi_radiologi').val();
            const posisi = $('#posisi_radiologi').val();
            const metode = $('#metode_radiologi').val();

            if (!pemeriksaan || !jenisPosisi || !posisi || !metode) {
                Swal.fire('Lengkapi semua field hingga metode sebelum menambah data.', '', 'warning');
                return;
            }
        });

        $('#btn-print-skd').on('click', function() {
            const tgl_pemeriksaan = $('#tanggal_pemeriksaan_skd').val();
            const kode_surat = $('#kode_surat_skd').val();
            const tgl_awal = $('#tanggal_mulai_istirahat_skd').val();
            const tgl_akhir = $('#tanggal_akhir_istirahat_skd').val();
            const diagnosa = $('#diagnosa_skd').val();
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const umur = $('#umur').val();
            const no_bpjs = $('#no_bpjs').val();
            const csrfToken = '{{ csrf_token() }}';

            const isDuplicate = radData.some(item =>
                item.pemeriksaan === newItem.pemeriksaan
            );

            if (isDuplicate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Duplikat',
                    text: `Data "${pemeriksaan}" sudah ditambahkan ke dalam tabel.`,
                });

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: csrfToken
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'tgl_pemeriksaan_skd',
                    value: tgl_pemeriksaan
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'kode_surat_skd',
                    value: kode_surat
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'tgl_awal_skd',
                    value: tgl_awal
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'tgl_akhir_skd',
                    value: tgl_akhir
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'diagnosa_skd',
                    value: diagnosa
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'nama_pasien',
                    value: nama_pasien
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'dokter_pengirim',
                    value: dokter_pengirim
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'jenis_kelamin',
                    value: jenis_kelamin
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'tanggal_lahir',
                    value: tanggal_lahir
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'alamat',
                    value: alamat
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'umur',
                    value: umur
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'no_bpjs',
                    value: no_bpjs
                }));

                $('body').append(form);
                form.submit();
                form.remove();

                // Setelah submit, redirect ke route dokter
                setTimeout(() => {
                    window.location.href = '{{ route('pelayanad.get') }}';
                }, 1000); // delay 1 detik agar PDF sempat terbuka
            }

            radData.push(newItem);
            refreshRadTable();

            // Reset input
            $('#pemeriksaan_radiologi').val(null).trigger('change');
            $('#jenis_posisi_radiologi').val(null).trigger('change');
            $('#posisi_radiologi').val(null).trigger('change');
            $('#metode_radiologi').val(null).trigger('change');
        });

        $(document).on('click', '.rad-row', function() {
            $('.rad-row').removeClass('table-primary');
            $(this).addClass('table-primary');
            selectedRadRow = $(this).data('index');
        });

        $('#btn-hapus-rad').on('click', function() {
            if (selectedRadRow === null) {
                Swal.fire('Pilih baris yang ingin dihapus.', '', 'info');
                return;
            }

            radData.splice(selectedRadRow, 1);
            selectedRadRow = null;
            refreshRadTable();
        });
    </script>

    <script>
        $('#btn-print-rad').on('click', function() {
            const radData = $('#rad_table_hidden').val();
            const diagnosa = $('#diagnosa_radiologi').val();
            const tanggal = $('#tanggal_periksa_radiologi').val();
            const catatan = $('#catatan_dokter_radiologi').val();
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const poli = $('#poli').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const penjamin = $('#penjamin').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!radData || !diagnosa || !tanggal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan data pemeriksaan dan tanggal periksa sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak Permintaan Laboratorium?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form dinamis
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('radiologi.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'rad_table_hidden',
                        value: radData
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosa_radiologi',
                        value: diagnosa
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_periksa_radiologi',
                        value: tanggal
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'catatan_dokter_radiologi',
                        value: catatan
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'poli',
                        value: poli
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'penjamin',
                        value: penjamin
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    // Setelah submit, redirect ke route dokter
                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000); // delay 1 detik agar PDF sempat terbuka
                }
            });
        });
    </script>

    <script>
        $('#btn-print-skd').on('click', function() {
            const tgl_pemeriksaan = $('#tanggal_pemeriksaan_skd').val();
            const kode_surat = $('#kode_surat_skd').val();
            const tgl_awal = $('#tanggal_mulai_istirahat_skd').val();
            const tgl_akhir = $('#tanggal_akhir_istirahat_skd').val();
            const diagnosa = $('#diagnosa_skd').val();
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const umur = $('#umur').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!tgl_pemeriksaan || !tgl_awal || !tgl_akhir || !diagnosa || !kode_surat) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan data diagnosa dan tanggal periksa sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak Permintaan SKD?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form dinamis
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('skd.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tgl_pemeriksaan_skd',
                        value: tgl_pemeriksaan
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'kode_surat_skd',
                        value: kode_surat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tgl_awal_skd',
                        value: tgl_awal
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tgl_akhir_skd',
                        value: tgl_akhir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosa_skd',
                        value: diagnosa
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'umur',
                        value: umur
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    // Setelah submit, redirect ke route dokter
                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000); // delay 1 detik agar PDF sempat terbuka
                }
            });
        });

        // Script untuk Surat Sakit
        $('#btn-print-sakit').on('click', function() {
            const diagnosis_utama =
                '{{ $pelayanan->icd->kode_icd10 ?? '' }} - {{ $pelayanan->icd->nama_icd10 ?? '' }}';
            const diagnosis_penyerta_1 = $('input[name="diagnosis_penyerta_1"]').val();
            const diagnosis_penyerta_2 = $('input[name="diagnosis_penyerta_2"]').val();
            const diagnosis_penyerta_3 = $('input[name="diagnosis_penyerta_3"]').val();
            const komplikasi_1 = $('input[name="komplikasi_1"]').val();
            const komplikasi_2 = $('input[name="komplikasi_2"]').val();
            const komplikasi_3 = $('input[name="komplikasi_3"]').val();
            const lama_istirahat = $('input[name="lama_istirahat"]').val();
            const terhitung_mulai = $('input[name="terhitung_mulai"]').val();
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const umur = $('#umur').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!lama_istirahat || !terhitung_mulai) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan lama istirahat dan terhitung mulai sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak Surat Sakit?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('permintaan.sakit.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosis_utama',
                        value: diagnosis_utama
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosis_penyerta_1',
                        value: diagnosis_penyerta_1
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosis_penyerta_2',
                        value: diagnosis_penyerta_2
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diagnosis_penyerta_3',
                        value: diagnosis_penyerta_3
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'komplikasi_1',
                        value: komplikasi_1
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'komplikasi_2',
                        value: komplikasi_2
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'komplikasi_3',
                        value: komplikasi_3
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'lama_istirahat',
                        value: lama_istirahat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'terhitung_mulai',
                        value: terhitung_mulai
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'umur',
                        value: umur
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000);
                }
            });
        });

        // Script untuk Surat Sehat
        $('#btn-print-sehat').on('click', function() {
            const tgl_periksa = $('input[name="tgl_periksa_sehat"]').val();
            const sistole = $('input[name="sistole"]').val();
            const diastole = $('input[name="diastole"]').val();
            const suhu = $('input[name="suhu"]').val();
            const berat = $('input[name="berat"]').val();
            const respiratory_rate = $('input[name="respiratory_rate"]').val();
            const nadi = $('input[name="nadi"]').val();
            const tinggi = $('input[name="tinggi"]').val();
            const buta_warna_status = $('select[name="buta_warna_status"]').val() || 'Tidak';
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const umur = $('#umur').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!tgl_periksa) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan tanggal periksa sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak Surat Sehat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('permintaan.sehat.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tgl_periksa_sehat',
                        value: tgl_periksa
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'sistole',
                        value: sistole
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'diastole',
                        value: diastole
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'suhu',
                        value: suhu
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'berat',
                        value: berat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'respiratory_rate',
                        value: respiratory_rate
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nadi',
                        value: nadi
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tinggi',
                        value: tinggi
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'buta_warna_status',
                        value: buta_warna_status
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'umur',
                        value: umur
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000);
                }
            });
        });

        // Script untuk Surat Kematian
        $('#btn-print-kematian').on('click', function() {
            const tgl_periksa = $('input[name="tgl_periksa_kematian"]').val();
            const dokter_kematian = $('input[name="dokter_kematian"]').val();
            const penandatangan = $('#penandatangan').val() || '';
            const tanggal_meninggal = $('input[name="tanggal_meninggal"]').val();
            const jam_meninggal = $('input[name="jam_meninggal"]').val();
            const ref_tgl_jam = $('input[placeholder="Contoh: UGD, Poli Umum, Ranap"]').val() || '';
            const penyebab_kematian = $('input[name="penyebab_kematian"]:checked').val() || 'Sakit';
            const penyebab_lainnya = $('input[name="penyebab_lainnya"]').val();
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const umur = $('#umur').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!tgl_periksa || !tanggal_meninggal || !jam_meninggal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan tanggal periksa, tanggal meninggal, dan jam meninggal sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak Surat Kematian?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('permintaan.kematian.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tgl_periksa_kematian',
                        value: tgl_periksa
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_kematian',
                        value: dokter_kematian
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'penandatangan',
                        value: penandatangan
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_meninggal',
                        value: tanggal_meninggal
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jam_meninggal',
                        value: jam_meninggal
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'ref_tgl_jam',
                        value: ref_tgl_jam
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'penyebab_kematian',
                        value: penyebab_kematian
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'penyebab_lainnya',
                        value: penyebab_lainnya
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'umur',
                        value: umur
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000);
                }
            });
        });

        // Script untuk SKDP
        $('#btn-print-skdp').on('click', function() {
            const tanggal_pemeriksaan = $('#tanggal_pemeriksaan_skd').val();
            const kode_surat = $('#kode_surat_skd').val();
            const jenis_skdp = $('input[name="jenis_skdp"]:checked').val() || 'BPJS';
            const sep_bpjs = $('input[name="sep_bpjs"]').val();
            const no_kartu = '{{ $pelayanan->pasien->no_bpjs ?? '' }}';
            const untuk_skdp = $('select[name="untuk_skdp"]').val();
            const pada_skdp = $('input[name="pada_skdp"]').val();
            const poli_unit_skdp = $('select[name="poli_unit_skdp"]').val();
            const alasan1_skdp = $('input[name="alasan1_skdp"]').val();
            const alasan2_skdp = $('input[name="alasan2_skdp"]').val();
            const rencana1_skdp = $('input[name="rencana1_skdp"]').val();
            const rencana2_skdp = $('input[name="rencana2_skdp"]').val();
            const nama_pasien = $('#nama').val();
            const dokter_pengirim = $('#dokter_pengirim').val();
            const jenis_kelamin = $('#jenis_kelamin').val();
            const tanggal_lahir = $('#tanggal_lahir').val();
            const alamat = $('#alamat').val();
            const umur = $('#umur').val();
            const csrfToken = '{{ csrf_token() }}';

            if (!tanggal_pemeriksaan || !kode_surat || !untuk_skdp || !pada_skdp) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Tidak Lengkap',
                    text: 'Pastikan tanggal pemeriksaan, kode surat, untuk, dan pada sudah diisi.'
                });
                return;
            }

            Swal.fire({
                title: 'Cetak SKDP?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('skd.print') }}',
                        target: '_blank'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: csrfToken
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_pemeriksaan_skd',
                        value: tanggal_pemeriksaan
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'kode_surat_skd',
                        value: kode_surat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_skdp',
                        value: jenis_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'sep_bpjs',
                        value: sep_bpjs
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'no_kartu',
                        value: no_kartu
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'untuk_skdp',
                        value: untuk_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'pada_skdp',
                        value: pada_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'poli_unit_skdp',
                        value: poli_unit_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alasan1_skdp',
                        value: alasan1_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alasan2_skdp',
                        value: alasan2_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'rencana1_skdp',
                        value: rencana1_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'rencana2_skdp',
                        value: rencana2_skdp
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'nama_pasien',
                        value: nama_pasien
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'dokter_pengirim',
                        value: dokter_pengirim
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'jenis_kelamin',
                        value: jenis_kelamin
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'tanggal_lahir',
                        value: tanggal_lahir
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'alamat',
                        value: alamat
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'umur',
                        value: umur
                    }));

                    $('body').append(form);
                    form.submit();
                    form.remove();

                    setTimeout(() => {
                        window.location.href = '{{ route('pelayanad.get') }}';
                    }, 1000);
                }
            });
        });
    </script>
