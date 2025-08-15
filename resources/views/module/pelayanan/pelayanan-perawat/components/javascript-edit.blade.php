
<script>
    $(document).ready(function() {
        $('#jenis_alergi').on('change', function () {
            const kode = $(this).val();

            if (kode) {
                $.ajax({
                    url: '/api/alergi/by-jenis/' + kode,
                    method: 'GET',
                    success: function(response) {
                        const select2 = $('#alergi');
                        select2.empty().append('<option value="" disabled selected>-- Pilih Data Alergi --</option>');

                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function(item) {
                                select2.append(`<option value="${item.kode_alergi}">${item.nama_jenis_alergi}</option>`);
                            });
                        } else {
                            select2.append('<option value="00">Tidak ada data</option>');
                        }
                    },
                    error: function() {
                        alert('Gagal memuat data alergi dari server.');
                    }
                });
            }
        });
    });
</script>


{{-- htt Script --}}
<script>
    $(document).ready(function () {
        const pemeriksaanSelect = $('#htt_pemeriksaan');
        const subSelect = $('#sub-pemeriksaan-select');
        const inputDetail = $('#htt_pemeriksaan_detail');

        function toggleInput() {
            const pemeriksaanValid = pemeriksaanSelect.val() && pemeriksaanSelect.val() !== "-";
            const subValid = subSelect.val() && subSelect.val() !== "";
            inputDetail.prop('disabled', !(pemeriksaanValid && subValid));
        }

        // Ketika pemeriksaan berubah
        pemeriksaanSelect.on('change', function () {
            let id = $(this).val();
            subSelect.empty().append('<option value="">-- Pilih Sub Pemeriksaan --</option>');
            inputDetail.prop('disabled', true); // Nonaktifkan input saat sub di-reset

            if (id && id !== "-") {
                $.ajax({
                    url: '/api/sub-pemeriksaan/' + id,
                    type: 'GET',
                    success: function (data) {
                        data.forEach(function (item) {
                            subSelect.append('<option value="' + item.id + '">' + item.nama_subpemeriksaan + '</option>');
                        });
                        subSelect.trigger('change');
                    },
                    error: function () {
                        alert('Gagal mengambil data sub pemeriksaan.');
                    }
                });
            }
        });

        // Aktifkan input hanya jika kedua dropdown sudah terisi
        subSelect.on('change', toggleInput);
    });

    function addDataHtt_Text() {
        const pemeriksaan = $('#htt_pemeriksaan option:selected').text().trim();
        const sub = $('#sub-pemeriksaan-select option:selected').text().trim();
        const detail = $('#htt_pemeriksaan_detail').val().trim();

        if (!pemeriksaan || !sub || !detail || pemeriksaan === '-- Silahkan Pilih --') {
            alert('Harap lengkapi semua data terlebih dahulu.');
            return;
        }

        const summernote = $('#summernote');
        let currentContent = summernote.summernote('code');

        const parser = new DOMParser();
        const doc = parser.parseFromString(currentContent || '<p><br></p>', 'text/html');

        // Ambil atau buat <ul> utama
        let ulMain = doc.body.querySelector('ul');
        if (!ulMain) {
            ulMain = doc.createElement('ul');
            doc.body.innerHTML = '';
            doc.body.appendChild(ulMain);
        }

        // ===== 1. Cari/muat LI PEMERIKSAAN =====
        let liPemeriksaan = Array.from(ulMain.children).find(li => li.innerText.trim().startsWith(pemeriksaan));
        if (!liPemeriksaan) {
            liPemeriksaan = doc.createElement('li');
            liPemeriksaan.innerHTML = `<strong>${pemeriksaan}</strong>`;
            ulMain.appendChild(liPemeriksaan);
        }

        // ===== 2. Ambil/buat UL di dalam pemeriksaan =====
        let ulSub = liPemeriksaan.querySelector('ul');
        if (!ulSub) {
            ulSub = doc.createElement('ul');
            liPemeriksaan.appendChild(ulSub);
        }

        // ===== 3. Cari/muat LI SUB =====
        let liSub = Array.from(ulSub.children).find(li => li.innerText.trim().startsWith(sub));
        if (!liSub) {
            liSub = doc.createElement('li');
            liSub.innerText = sub;
            ulSub.appendChild(liSub);
        }

        // ===== 4. Ambil/buat UL detail =====
        let ulDetail = liSub.querySelector('ul');
        if (!ulDetail) {
            ulDetail = doc.createElement('ul');
            liSub.appendChild(ulDetail);
        }

        // ===== 5. Tambahkan detail jika belum ada =====
        const exists = Array.from(ulDetail.children).some(li => li.innerText.trim() === detail);
        if (!exists) {
            const liDetail = doc.createElement('li');
            liDetail.innerText = detail;
            ulDetail.appendChild(liDetail);
        }

        // Simpan hasil kembali ke Summernote
        summernote.summernote('code', doc.body.innerHTML);

        // Reset input
        $('#htt_pemeriksaan').val(null).trigger('change');
        $('#sub-pemeriksaan-select').html('<option value="">-- Pilih Sub Pemeriksaan --</option>').trigger('change');
        $('#htt_pemeriksaan_detail').val('');
    }

</script>

{{-- Tensi Script --}}
<script>
    function calculateAge(tanggalLahir) {
        const today = new Date();
        const birthDate = new Date(tanggalLahir);

        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();

        if (today.getDate() < birthDate.getDate()) {
            months--;
        }

        if (months < 0) {
            years--;
            months += 12;
        }

        return { years, months };
    }

    function updateTensi() {
        const sistol = document.getElementById('sistol').value.trim();
        const distol = document.getElementById('distol').value.trim();
        const tanggalLahir = document.getElementById('tanggal_lahir').value.trim();

        if (!tanggalLahir) {
            Swal.fire({
                icon: 'warning',
                title: 'Tanggal Lahir Kosong',
                text: 'Mohon isi tanggal lahir terlebih dahulu.',
            });
            return;
        }

        const { years: tahun } = calculateAge(tanggalLahir);

        // Validasi awal
        if (!sistol || !distol || isNaN(sistol) || isNaN(distol)) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Tidak Valid',
                text: 'Sistol dan Diastol harus diisi dengan angka yang valid.',
            }).then(() => {
                document.getElementById('sistol').value = '';
                document.getElementById('distol').value = '';
                document.getElementById('tensi').value = '';
            });
            return;
        }

        const sistolValue = parseInt(sistol);
        const distolValue = parseInt(distol);
        const tensiValue = `${sistolValue}/${distolValue}`;
        document.getElementById('tensi').value = tensiValue;

        let message = '';
        if (tahun <= 5) {
            if (sistolValue <= 74 || distolValue <= 49)
                message = 'Data Tensi Terdeteksi HIPOTENSI. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 75 && sistolValue <= 100 && distolValue >= 50 && distolValue <= 65)
                message = 'Data Tensi Normal. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 101 || distolValue >= 66)
                message = 'Data Tensi Terdeteksi HIPERTENSI. Apakah Anda ingin melanjutkan?';
        } else if (tahun <= 12) {
            if (sistolValue <= 89 || distolValue <= 59)
                message = 'Data Tensi Terdeteksi HIPOTENSI. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 90 && sistolValue <= 110 && distolValue >= 60 && distolValue <= 75)
                message = 'Data Tensi Normal. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 111 || distolValue >= 76)
                message = 'Data Tensi Terdeteksi HIPERTENSI. Apakah Anda ingin melanjutkan?';
        } else if (tahun <= 17) {
            if (sistolValue <= 89 || distolValue <= 59)
                message = 'Data Tensi Terdeteksi HIPOTENSI. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 90 && sistolValue <= 120 && distolValue >= 60 && distolValue <= 80)
                message = 'Data Tensi Normal. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 121 || distolValue >= 81)
                message = 'Data Tensi Terdeteksi HIPERTENSI. Apakah Anda ingin melanjutkan?';
        } else if (tahun <= 64) {
            if (sistolValue <= 89 || distolValue <= 59)
                message = 'Data Tensi Terdeteksi HIPOTENSI. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 90 && sistolValue <= 120 && distolValue >= 60 && distolValue <= 80)
                message = 'Data Tensi Normal. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 121 || distolValue >= 81)
                message = 'Data Tensi Terdeteksi HIPERTENSI. Apakah Anda ingin melanjutkan?';
        } else if (tahun >= 65) {
            if (sistolValue <= 89 || distolValue <= 59)
                message = 'Data Tensi Terdeteksi HIPOTENSI. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 90 && sistolValue <= 140 && distolValue >= 60 && distolValue <= 90)
                message = 'Data Tensi Normal. Apakah Anda ingin melanjutkan?';
            else if (sistolValue >= 141 || distolValue >= 91)
                message = 'Data Tensi Terdeteksi HIPERTENSI. Apakah Anda ingin melanjutkan?';
        }

        if (message) {
            Swal.fire({
                icon: 'info',
                title: 'Validasi Tensi',
                text: message,
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Ubah Data'
            }).then((result) => {
                if (!result.isConfirmed) {
                    document.getElementById('sistol').value = '';
                    document.getElementById('distol').value = '';
                    document.getElementById('tensi').value = '';
                }
            });
        }
    }


</script>

{{-- RR Script --}}
<script>
    function calculateAge(tanggalLahir) {
        const today = new Date();
        const birthDate = new Date(tanggalLahir);

        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();

        if (today.getDate() < birthDate.getDate()) {
            months--;
        }

        if (months < 0) {
            years--;
            months += 12;
        }

        return { years, months };
    }

    function validateRR(input) {
        const rrValue = parseInt(input.value.trim());
        const tanggalLahir = document.getElementById('tanggal_lahir').value.trim();

        // Cek input tanggal lahir
        if (!tanggalLahir) {
            Swal.fire({
                icon: 'warning',
                title: 'Tanggal Lahir Kosong',
                text: 'Mohon isi tanggal lahir terlebih dahulu.',
            });
            return;
        }

        const { years: tahun, months: bulan } = calculateAge(tanggalLahir);

        if (isNaN(rrValue)) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Tidak Valid',
                text: 'Mohon masukkan angka Respiratory Rate (RR) yang benar!',
            }).then(() => {
                input.value = '';
                input.focus();
            });
            return;
        }

        let status = '';
        let pesan = '';
        let icon = 'info';

        const checkRange = (min, max) => {
            if (rrValue < min) {
                status = 'RR Terlalu Rendah';
                pesan = `RR Anda (${rrValue}) di bawah batas normal (${min} - ${max})`;
                icon = 'warning';
            } else if (rrValue > max) {
                status = 'RR Terlalu Cepat';
                pesan = `RR Anda (${rrValue}) di atas batas normal (${min} - ${max})`;
                icon = 'warning';
            } else {
                status = 'RR Normal';
                pesan = `RR Anda (${rrValue}) berada dalam rentang normal (${min} - ${max})`;
                icon = 'success';
            }
        };

        if (tahun === 0 && bulan <= 12) checkRange(30, 60);
        else if (tahun >= 1 && tahun <= 2) checkRange(24, 40);
        else if (tahun >= 3 && tahun <= 5) checkRange(22, 34);
        else if (tahun >= 6 && tahun <= 12) checkRange(18, 30);
        else if (tahun >= 13 && tahun <= 17) checkRange(12, 20);
        else if (tahun >= 18 && tahun <= 64) checkRange(18, 24);
        else if (tahun >= 65) checkRange(12, 28);

        Swal.fire({
            icon: icon,
            title: status,
            text: pesan,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Ubah Data',
        }).then((result) => {
            if (!result.isConfirmed) {
                input.value = '';
                input.focus();
            }
        });
    }
</script>

{{-- Suhu Script --}}
<script>
    function validateSuhu(input) {
        let suhuValue = input.value.trim();

        // Cek jika nilai menggunakan koma
        if (suhuValue.includes(',')) {
            Swal.fire({
                icon: 'warning',
                title: 'Format tidak valid',
                text: 'Gunakan titik (.) sebagai pemisah desimal, bukan koma!',
            }).then(() => {
                input.value = '';
                input.focus();
            });
            return;
        }

        const suhuNumber = parseFloat(suhuValue);

        // Validasi angka
        if (isNaN(suhuNumber)) {
            Swal.fire({
                icon: 'warning',
                title: 'Data tidak valid',
                text: 'Mohon masukkan suhu dalam angka yang benar!',
            }).then(() => {
                input.value = '';
                input.focus();
            });
            return;
        }

        // Tentukan kondisi berdasarkan suhu
        let status = '';
        let pesan = '';
        let icon = 'info';

        if (suhuNumber < 34.4) {
            status = 'Hipotermia';
            pesan = 'Suhu tubuh terlalu rendah. Segera konsultasi medis jika perlu.';
            icon = 'error';
        } else if (suhuNumber >= 34.4 && suhuNumber <= 37.4) {
            status = 'Suhu Normal';
            pesan = 'Suhu tubuh Anda berada dalam rentang normal.';
            icon = 'success';
        } else if (suhuNumber >= 37.5 && suhuNumber <= 37.9) {
            status = 'Demam Ringan';
            pesan = 'Kemungkinan terdapat infeksi ringan atau peradangan.';
            icon = 'warning';
        } else if (suhuNumber >= 38 && suhuNumber <= 38.9) {
            status = 'Demam';
            pesan = 'Tubuh sedang melawan infeksi atau peradangan.';
            icon = 'warning';
        } else if (suhuNumber >= 39) {
            status = 'Demam Tinggi';
            pesan = 'Segera konsultasi medis bila gejala berlanjut.';
            icon = 'error';
        }

        // Tampilkan pesan konfirmasi
        Swal.fire({
            icon: icon,
            title: status,
            text: `${pesan} (Suhu: ${suhuNumber}°C)`,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Ubah Data',
        }).then((result) => {
            if (!result.isConfirmed) {
                input.value = '';
                input.focus();
            }
        });
    }
</script>

{{-- SPO2 Script --}}
<script>
    function validateSpO2(input) {
        const spo2Value = parseFloat(input.value.trim());

        // Jika bukan angka
        if (isNaN(spo2Value)) {
            Swal.fire({
                icon: 'warning',
                title: 'SpO2 tidak valid',
                text: 'Mohon masukkan angka yang benar!',
            }).then(() => {
                input.value = '';
                input.focus();
            });
            return;
        }

        // Jika nilai tidak dalam rentang normal
        if (spo2Value < 95 || spo2Value > 100) {
            let pesan = '';

            if (spo2Value < 95) {
                pesan = `SpO2 Anda (${spo2Value}%) terlalu rendah. Normal: 95% - 100%.`;
            } else {
                pesan = `SpO2 Anda (${spo2Value}%) terlalu tinggi. Normal: 95% - 100%.`;
            }

            Swal.fire({
                icon: 'warning',
                title: 'SpO2 Tidak Normal',
                text: pesan,
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Ubah Data',
            }).then((result) => {
                if (!result.isConfirmed) {
                    input.value = '';
                    input.focus();
                }
            });
        } else {
            // Nilai normal, tampilkan notifikasi sukses
            Swal.fire({
                icon: 'success',
                title: 'SpO2 Normal',
                text: `SpO2 Anda (${spo2Value}%) berada dalam rentang normal.`,
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Ubah Data',
            });
        }

    }
</script>

{{-- Nadi Script --}}
<script>
    function calculateAge(tanggalLahir) {
        const today = new Date();
        const birthDate = new Date(tanggalLahir);

        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();

        if (today.getDate() < birthDate.getDate()) {
            months--;
        }

        if (months < 0) {
            years--;
            months += 12;
        }

        return { years, months };
    }

    function validateNadi() {
        const nadiInput = document.getElementById('nadi');
        const tanggalLahir = document.getElementById('tanggal_lahir').value.trim();
        const nadi = parseInt(nadiInput.value.trim());

        if (!tanggalLahir) {
            Swal.fire({
                icon: 'error',
                title: 'Tanggal lahir kosong',
                text: 'Data tanggal lahir tidak tersedia.',
            });
            return;
        }

        if (isNaN(nadi)) {
            Swal.fire({
                icon: 'warning',
                title: 'Nadi tidak valid',
                text: 'Masukkan angka nadi yang benar!',
            }).then(() => {
                nadiInput.value = '';
                nadiInput.focus();
            });
            return;
        }

        const { years, months } = calculateAge(tanggalLahir);

        let rentang = { min: 0, max: 0 };
        if (years === 0 && months <= 12) {
            rentang = { min: 100, max: 160 };
        } else if (years <= 2) {
            rentang = { min: 90, max: 150 };
        } else if (years <= 5) {
            rentang = { min: 80, max: 140 };
        } else if (years <= 10) {
            rentang = { min: 70, max: 130 };
        } else {
            rentang = { min: 60, max: 100 };
        }

        const dalamRentang = nadi >= rentang.min && nadi <= rentang.max;
        const status = dalamRentang ? 'Data Nadi Sesuai' : 'Data Nadi Tidak Sesuai';
        const pesan = dalamRentang
            ? `Nadi Anda (${nadi} bpm) sesuai untuk umur ${years} Tahun ${months} Bulan.`
            : `Nadi Anda (${nadi} bpm) di luar rentang normal (${rentang.min}-${rentang.max} bpm) untuk umur ${years} Tahun ${months} Bulan.`;

        Swal.fire({
            icon: dalamRentang ? 'success' : 'warning',
            title: status,
            text: pesan,
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Ubah Data'
        }).then((result) => {
            if (!result.isConfirmed) {
                nadiInput.value = '';
                nadiInput.focus();
            }
        });
    }

</script>

{{-- BMI Script --}}
<script>
    function validateTB() {
        const tinggiEl = document.getElementById('tinggi');
        const beratEl = document.getElementById('berat');
        const tinggi = tinggiEl.value.trim();
        const berat = beratEl.value.trim();

        // Fungsi untuk reset input
        function resetInputs() {
            tinggiEl.value = '';
            beratEl.value = '';
            tinggiEl.focus();
        }

        if (!tinggi || !berat) return;

        // Cek apakah input tidak kosong dan valid
        const tinggiVal = parseFloat(tinggi);
        const beratVal = parseFloat(berat);
        const inputInvalid = isNaN(tinggiVal) || isNaN(beratVal)  || tinggiVal <= 0 || beratVal <= 0;

        let message = '';

        if (inputInvalid) {
            message = `Data Tinggi / Berat Badan Ada Yang Tidak Sesuai.\nMohon isi yang benar!`;
        } else {
            const tinggiMeter = tinggiVal / 100;
            const bmi = beratVal / (tinggiMeter * tinggiMeter);
            const bmiFixed = bmi.toFixed(2);

            let bmiCategory = '';
            if (bmi < 18.5) {
                bmiCategory = 'Berat badan kurang (Underweight)';
            } else if (bmi < 25) {
                bmiCategory = 'Berat badan normal';
            } else if (bmi < 30) {
                bmiCategory = 'Kelebihan berat badan (Overweight)';
            } else {
                bmiCategory = 'Obesitas';
            }

            document.getElementById("nilai_bmi").value = bmiFixed;
            document.getElementById("status_bmi").value = bmiCategory;

            message = `Data BMI-nya adalah: ${bmiFixed},\nDengan kategori: ${bmiCategory}\nApakah Anda ingin melanjutkan?`;
        }

        Swal.fire({
            title: 'Konfirmasi',
            text: message,
            icon: inputInvalid ? 'warning' : 'info',
            showCancelButton: true,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Lanjutkan proses jika diperlukan
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                resetInputs();
            }
        });

    }

    validateTB(); // Panggil fungsi validasi saat halaman dimuat
</script>

{{-- GCS Script --}}
<script>
    $(document).ready(function() {
        // Function to calculate and select "sadar" based on sum of eye, verbal, motorik
        function updateSadarSelection() {
            let eyeScore = parseInt($('#eye').val()) || 0;
            let verbalScore = parseInt($('#verbal').val()) || 0;
            let motorikScore = parseInt($('#motorik').val()) || 0;

            // Calculate total score
            let totalScore = eyeScore + verbalScore + motorikScore;

            // Find and select the option in "sadar" that matches the totalScore
            $('#sadar').val(totalScore).trigger('change');
        }

        // Panggil langsung saat halaman dimuat
        updateSadarSelection();

        // Attach event listeners to each dropdown to trigger the update when value changes
        $('#eye, #verbal, #motorik').on('change', updateSadarSelection);
    });
</script>


{{-- Subjectiv Script --}}
<script>
    let dataArray = [];
    let dataTable;

    $(document).ready(function () {
    let initialData = $('#SubTabel').data('value');
    let parsedData;

    try {
        // Jika value adalah string dalam string JSON, parse dua kali
        if (typeof initialData === 'string') {
            parsedData = JSON.parse(initialData); // pertama
        } else {
            parsedData = initialData;
        }

        // Pastikan hasilnya array
        dataArray = Array.isArray(parsedData) ? parsedData : [];

    } catch (e) {
        console.error("Gagal parsing data-value:", e);
        dataArray = [];
    }


    dataTable = $('#SubTabel').DataTable({
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        columnDefs: [
            { targets: 0, className: 'text-center' },
            { targets: 2, className: 'text-center' }
        ]
    });

    function renderTable() {
        dataTable.clear().draw(); // Kosongkan

        dataArray.forEach((item, index) => {
            const aksiBtn = `
                <button class="btn btn-warning btn-sm mr-1" onclick="editData(${index})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="removeData(${index})">Hapus</button>
            `;

            dataTable.row.add([
                index + 1,
                `${item.penyakit} sejak ${item.durasi} ${item.waktu}`,
                aksiBtn
            ]);
        });

        dataTable.draw();
        updateHiddenInput();
    }


    renderTable(); // Munculkan data awal dari controller
    });


    function addData() {
        const penyakit = $('#penyakit').val().trim();
        const durasi = $('#durasi').val().trim();
        const waktu = $('#waktu').val();

        if (!penyakit && !durasi && !waktu) {
            alert("Semua kolom harus diisi!");
            return;
        }
        if (!penyakit || !durasi || !waktu) {
            alert("Semua kolom harus diisi!");
            return;
        }

        const index = dataArray.length;
        const newData = { penyakit, durasi, waktu };
        dataArray.push(newData);

        const aksiBtn = `
            <button class="btn btn-warning btn-sm mr-1" onclick="editData(${index})">Edit</button>
            <button class="btn btn-danger btn-sm" onclick="removeData(${index})">Hapus</button>
        `;

        dataTable.row.add([
            index + 1,
            `${penyakit} sejak ${durasi} ${waktu}`,
            aksiBtn
        ]).draw();

        updateHiddenInput();
        resetInputs();
    }

    function removeData(index) {
        dataArray.splice(index, 1);
        dataTable.clear().draw(); // Kosongkan dan render ulang
        dataArray.forEach((item, i) => {
            dataTable.row.add([
                i + 1,
                `${item.penyakit} sejak ${item.durasi} ${item.waktu}`,
                `<button class="btn btn-warning btn-sm mr-1" onclick="editData(${i})">Edit</button>
                 <button class="btn btn-danger btn-sm" onclick="removeData(${i})">Hapus</button>`
            ]);
        });
        dataTable.draw();
        updateHiddenInput();
    }

    function editData(index) {
        const item = dataArray[index];
        $('#penyakit').val(item.penyakit);
        $('#durasi').val(item.durasi);
        $('#waktu').val(item.waktu).trigger('change');

        removeData(index); // Hapus dulu, nanti ditambah ulang setelah diedit
    }

    function updateHiddenInput() {
        $('#tableData').val(JSON.stringify(dataArray));
    }

    function resetInputs() {
        $('#penyakit').val('');
        $('#durasi').val('');
        $('#waktu').val('').trigger('change');
    }
</script>

{{-- BS-Stepper --}}
<script>
    // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })

  $(function () {
    // Summernote
        $('#summernote').summernote({
            height: 300, // Tentukan tinggi editor (dalam px)
            tabsize: 2,
            disableResizeEditor: true // Menonaktifkan resize editor
        });
    })


</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tangkap form submit
        document.getElementById('formSoapPerawat').addEventListener('submit', function(e) {
            e.preventDefault();

            // Ambil data form
            var formData = new FormData(this);

            // Kirim dengan fetch API
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);

                // Tampilkan SweetAlert sukses
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Data Pemeriksaan berhasil disimpan!',
                    showConfirmButton: true
                }).then(function() {
                    // Redirect ke halaman pelayanan
                    window.location.href = "{{ route('pelayana.get') }}";
                });
            })
            .catch(error => {
                console.error('Error:', error);

                // Tampilkan SweetAlert error
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan Data Pemeriksaan!',
                    showConfirmButton: true
                });
            });
        });
    });
</script>

<script>
    function hapusBaris(button) {
        const row = button.closest("tr");
        row.remove();

        // Optionally: update nomor urut
        const rows = document.querySelectorAll("#SubTabel tbody tr");
        rows.forEach((tr, i) => {
            tr.children[0].textContent = i + 1;
        });
    }
</script>
