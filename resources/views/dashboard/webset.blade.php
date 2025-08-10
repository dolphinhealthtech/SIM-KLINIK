@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <section class="content mt-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Web Setting</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="col-md-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-header d-flex justify-content-center align-items-center">
                                        <h3 class="card-title">App Seting</h3>
                                    </div>
                                    <form action="{{ route('web.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">
                                            <div class="text-center">
                                                <label for="profileImageInput">
                                                    <img id="profileImage" class="profile-user-img img-fluid"
                                                        src="{{ asset('setting/' . ($setting->profile_image ?? 'default.jpg')) }}"
                                                        alt="User profile picture"
                                                        style="width: 150px; height: 150px; cursor: pointer;">

                                                </label>
                                                <input type="file" id="profileImageInput" name="profile_image"
                                                    accept="image/*" class="d-none">
                                            </div>

                                            <div class="form-group row mt-3">
                                                <label for="nama" class="col-sm-4 col-form-label text-left font-weight-bold">Nama Klinik:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="nama" name="nama"
                                                        value="{{ $setting->nama ?? '' }}" placeholder="Masukkan Nama">
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label for="kode_klinik" class="col-sm-4 col-form-label text-left font-weight-bold">Kode Klinik:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="kode_klinik"
                                                        name="kode_klinik" value="{{ $setting->kode_klinik ?? '' }}"
                                                        placeholder="Masukkan Kode">
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <label for="alamat" class="col-sm-3 col-form-label text-left font-weight-bold">Alamat:</label>
                                                <div class="col-sm-9">
                                                    <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat" rows="2">{{ $setting->alamat ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row justify-content-center  mb-8">
                                                <button type="submit" class="btn btn-sm btn-primary w-100">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12">

                                <div class="card card-primary card-outline">
                                    <div class="card-header d-flex justify-content-center align-items-center">
                                        <h3 class="card-title">Menu Setting</h3>
                                    </div>
                                    <div class="card-body d-flex flex-wrap gap-2" style="gap: 10px;">
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#paymentModal">
                                            Atur Pembayaran Bank Klinik
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary"
                                            id="btnPilihGudangUtama" style="display:none;" data-toggle="modal"
                                            data-target="#gudangUtamaModal">
                                            Pilih Gudang Utama
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-number text-center text-muted mb-0">
                                                <div class="time-container">
                                                    <!-- Time will be displayed here -->
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- SATUSEHAT Form -->
                                    <div class="card card-primary card-outline mb-3">
                                        <div class="card-header d-flex justify-content-center align-items-center">
                                            <h3 class="card-title">Satu Sehat</h3>
                                        </div>
                                        <form action="{{ route('web.update.satusehat') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-body">
                                                @foreach ($set_Sehat as $setsatusehat)
                                                    <div class="row">
                                                        @foreach ([
                                                            'org_id' => 'ID',
                                                            'client_id' => 'Client ID',
                                                            'client_secret' => 'Client Secret',
                                                            'SATUSEHAT_BASE_URL' => 'SATUSEHAT BASE URL',
                                                        ] as $name => $label)
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="{{ $name }}">{{ $label }}</label>
                                                                    <input type="text"
                                                                        value="{{ $setsatusehat->$name }}"
                                                                        name="{{ $name }}" class="form-control"
                                                                        id="{{ $name }}"
                                                                        placeholder="Enter {{ $label }}" required>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Tambahan Card Baru di bawahnya -->

                                    <div class="card card-primary card-outline">
                                        <div class="card-header d-flex justify-content-center align-items-center">
                                            <h3 class="card-title">Menu Setting</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="toggleBPJS"
                                                        name="toggleBPJS" checked>
                                                    <label class="custom-control-label" for="toggleBPJS">Aktifkan Fitur
                                                        BPJS</label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="toggleSatusehat"
                                                        name="toggleSatusehat" checked>
                                                    <label class="custom-control-label" for="toggleSatusehat">Aktifkan Fitur
                                                        SATUSEHAT</label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="toggleGudangutama"
                                                        name="toggleGudangutama" checked>
                                                    <label class="custom-control-label" for="toggleGudangutama">Aktifkan Fitur
                                                        Gudang Utama</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row col-md-6">
                                    <!-- BPJS Form -->
                                    <div class="col-md-12">
                                        <div class="card card-primary card-outline">
                                            <div class="card-header d-flex justify-content-center align-items-center">
                                                <h3 class="card-title">BPJS</h3>
                                            </div>
                                            <form action="{{ route('web.update.bpjs') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="card-body">
                                                    @foreach ($set_bpjs as $setbpjs)
                                                        <div class="row">
                                                            @foreach ([
                                                                'CONSID' => 'CONSID',
                                                                'KPFK' => 'Kode Faskes',
                                                                'USERNAME' => 'Username',
                                                                'PASSWORD' => 'Password',
                                                                'SCREET_KEY' => 'Secret Key',
                                                                'USER_KEY' => 'User Key',
                                                                'SERVICE_ANTREAN' => 'Service Antrean',
                                                                'SERVICE' => 'Service',
                                                                'APP_CODE' => 'App Code',
                                                                'BASE_URL' => 'Base URL',
                                                            ] as $name => $label)
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="{{ $name }}">{{ $label }}</label>
                                                                        <input type="text"
                                                                            value="{{ $setbpjs->$name }}"
                                                                            name="{{ $name }}"
                                                                            class="form-control" id="{{ $name }}"
                                                                            placeholder="Enter {{ $label }}"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Pengaturan Pembayaran Bank</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="#" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="bank">Pilih Bank</label>
                            <select name="bank" id="bank" class="form-control" required>
                                <option value="">-- Pilih Bank --</option>
                                <option value="BCA">BCA</option>
                                <option value="BRI">BRI</option>
                                <option value="Mandiri">Mandiri</option>
                                <option value="BNI">BNI</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nominal">Nomor rekning</label>
                            <input type="number" name="nominal" id="nominal" class="form-control"
                                placeholder="Masukkan nominal" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal Pilih Gudang Utama -->
    <div class="modal fade" id="gudangUtamaModal" tabindex="-1" role="dialog" aria-labelledby="gudangUtamaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gudangUtamaModalLabel">Pilih Gudang Utama</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formPilihGudangUtama">
                        <div class="form-group">
                            <label for="gudangUtamaSelect">Gudang Utama</label>
                            <select class="form-control" id="gudangUtamaSelect" name="gudang_utama_id" required>
                                <option value="" disabled selected>Pilih Gudang Utama</option>
                                @foreach ($singkron as $gudang)
                                    <option value="{{ $gudang->database }}">{{ $gudang->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="formPilihGudangUtama">Simpan</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.getElementById('profileImageInput').addEventListener('change', function(event) {
            let reader = new FileReader();
            reader.onload = function() {
                document.getElementById('profileImage').src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>
    <script>
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour12: false
            });
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.querySelector('.time-container').innerHTML = timeString + ' <span>' + dateString + '</span>';
        }
        setInterval(updateTime, 1000);
        updateTime(); // Initialize immediately
    </script>

    <!-- AJAX Script untuk Toggle Switch -->
    <script>
        $(document).ready(function() {
            // Load current toggle states saat halaman dibuka
            loadToggleStates();

            // Handle toggle switches
            $('.custom-control-input').on('change', function() {
                const toggleId = $(this).attr('id');
                const isChecked = $(this).is(':checked');
                const value = isChecked ? 1 : 0;

                // Show loading state
                $(this).prop('disabled', true);

                // AJAX request untuk update toggle
                $.ajax({
                    url: "{{ route('web.update.toggle') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        toggle_type: toggleId,
                        value: value
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                            // Refresh halaman setelah notifikasi selesai
                            window.location.reload();
                        });
                        } else {
                            // Show error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat mengupdate pengaturan'
                        });
                    },
                    complete: function() {
                        // Re-enable toggle
                        $('.custom-control-input').prop('disabled', false);
                    }
                });
            });
        });

        function loadToggleStates() {
            $.ajax({
                url: "{{ route('web.get.toggle.states') }}",
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const states = response.data;

                        // Update toggle states
                        $('#toggleBPJS').prop('checked', states.is_bpjs_active == 1);
                        $('#toggleSatusehat').prop('checked', states.is_satusehat_active == 1);
                        $('#toggleGudangutama').prop('checked', states.is_gudangutama_active == 1);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading toggle states:', xhr);
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            // Cek status toggleGudangUtama secara realtime
            function cekToggleGudangUtama() {
                setTimeout(function () {
                    $.ajax({
                        url: "{{ route('web.get.toggle.states') }}",
                        method: 'GET',
                        success: function(response) {
                            if (response.success) {
                                if (response.data.is_gudangutama_active == 0) {
                                    $('#btnPilihGudangUtama').show();
                                } else {
                                    $('#btnPilihGudangUtama').hide();
                                }
                            }
                        }
                    });
                }, 2000); // Delay 5 detik (5000 ms)
            }

            cekToggleGudangUtama();

            // Juga cek ulang saat toggle diubah
            $('#toggleGudangutama').on('change', function() {

                // Jika toggle dimatikan, reset semua active ke 0
                if (!$(this).is(':checked')) {
                    $.ajax({
                        url: "{{ route('web.reset.gudang.utama') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            cekToggleGudangUtama();
                            // Swal.fire('Berhasil', 'Semua gudang utama dinonaktifkan',
                            //     'success');
                        }
                    });
                } else {
                    cekToggleGudangUtama();
                }
            });

            // Submit pilih gudang utama
            $('#formPilihGudangUtama').on('submit', function(e) {
                e.preventDefault();
                var gudangId = $('#gudangUtamaSelect').val();
                $.ajax({
                    url: "{{ route('web.set.gudang.utama') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        gudang_utama_id: gudangId
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Berhasil', 'Gudang utama berhasil diaktifkan',
                                'success');
                            $('#gudangUtamaModal').modal('hide');
                        }
                    }
                });
            });
        });
    </script>
@endsection
