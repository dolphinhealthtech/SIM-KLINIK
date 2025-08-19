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
                                    <div class="card-body d-flex flex-column gap-3">

                                        <!-- Baris Pertama: Tombol tunggal -->
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#paymentModal">
                                                Atur Pembayaran Bank Klinik
                                            </button>
                                        </div>

                                        <!-- Baris Kedua: Dua tombol sejajar kiri dan kanan -->
                                        <div class="d-flex justify-content-between align-items-center" style="margin-top: 15px;">
                                            <!-- Kiri -->
                                            <button type="button" class="btn btn-sm btn-primary"
                                                id="btnPilihGudangUtama" style="display:none;" data-toggle="modal"
                                                data-target="#gudangUtamaModal">
                                                Pilih Gudang Utama
                                            </button>

                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="openWaGatewayModal('OMEGACITRA')">
                                                Info WhatsApp Gateway
                                            </button>
                                        </div>
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

                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="toggleTindakanAll"
                                                        name="toggleTindakanAll" checked>
                                                    <label class="custom-control-label" for="toggleTindakanAll">Aktifkan Fitur
                                                        Tindakan Pemeriksaan</label>
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


<!-- Modal WhatsApp Gateway -->
<div class="modal fade" id="waGatewayModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-mobile-alt"></i> WhatsApp Gateway</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="waGatewayBody">
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-3 text-muted">Memuat informasi...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="logoutBtn" style="display: none;" onclick="logoutSession()">Logout</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
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
// Global variables untuk modal WhatsApp Gateway
let currentWaSessionId = '';
let waSessionInterval = null;
let pasienData = []; // Store patient data for autocomplete

async function loadPasienData() {
    try {
        const possibleEndpoints = [
            '/api/pasiens/search',
            '/pasiens/search',
            '/web-setting/pasiens/search'
        ];

        for (let endpoint of possibleEndpoints) {
            try {
                console.log(`Trying endpoint: ${endpoint}`);
                const response = await fetch(endpoint, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || ''
                    }
                });

                console.log(`Response status for ${endpoint}:`, response.status);

                if (response.ok) {
                    const data = await response.json();
                    console.log(`Response data for ${endpoint}:`, data);

                    if (data.success && data.data) {
                        pasienData = data.data;
                        console.log('Patient data loaded successfully:', pasienData.length, 'records');
                        return; // Exit if successful
                    }
                }
            } catch (err) {
                console.error(`Error with endpoint ${endpoint}:`, err);
            }
        }

        console.warn('All endpoints failed. Patient data could not be loaded.');
        pasienData = []; // kosongkan kalau gagal

    } catch (error) {
        console.error('Error loading patient data:', error);
        pasienData = []; // kosongkan kalau error
    }
}


/**
 * Get queue message by patient phone
 */
async function getQueueMessage(phoneNumber) {
    try {
        // Try different possible endpoints
        const possibleEndpoints = [
            `/api/pasiens/queue-message?telepon=${encodeURIComponent(phoneNumber)}`,
            `/pasiens/queue-message?telepon=${encodeURIComponent(phoneNumber)}`,
            `/web-setting/pasiens/queue-message?telepon=${encodeURIComponent(phoneNumber)}`
        ];

        for (let endpoint of possibleEndpoints) {
            try {
                console.log(`Trying queue endpoint: ${endpoint}`);
                const response = await fetch(endpoint, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || ''
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log(`Queue response for ${endpoint}:`, data);

                    if (data.success && data.message) {
                        return data.message;
                    }
                }
            } catch (err) {
                console.error(`Error with queue endpoint ${endpoint}:`, err);
            }
        }

        // Fallback: Create message from patient data
        const patient = pasienData.find(p => p.telepon === phoneNumber);
        if (patient) {
            return `Halo ${patient.nama}, terima kasih telah menggunakan layanan klinik kami. Nomor antrian Anda akan segera dipanggil.`;
        }

        return `Halo, terima kasih telah menggunakan layanan klinik kami. Silakan tunggu informasi lebih lanjut.`;

    } catch (error) {
        console.error('Error getting queue message:', error);
        return `Halo, terima kasih telah menggunakan layanan klinik kami.`;
    }
}

/**
 * Setup autocomplete for phone input
 */
function setupAutocomplete(inputElement) {
    let currentFocus = -1;

    console.log('Setting up autocomplete for:', inputElement.id, 'with data:', pasienData);

    inputElement.addEventListener('input', function() {
        const value = this.value.toLowerCase();
        console.log('Input changed:', value);

        closeAllLists();

        if (!value || value.length < 1) {
            console.log('Input too short, not showing suggestions');
            return;
        }

        currentFocus = -1;
        const listContainer = document.createElement('div');
        listContainer.setAttribute('id', this.id + 'autocomplete-list');
        listContainer.setAttribute('class', 'autocomplete-items');

        // Make sure parent has position relative
        this.parentNode.style.position = 'relative';
        this.parentNode.appendChild(listContainer);

        let itemCount = 0;
        const maxItems = 10;

        console.log('Searching through', pasienData.length, 'patients');

        for (let i = 0; i < pasienData.length && itemCount < maxItems; i++) {
            const pasien = pasienData[i];
            const telepon = (pasien.telepon || '').toString().toLowerCase();
            const nama = (pasien.nama || '').toString().toLowerCase();

            if (telepon.includes(value) || nama.includes(value)) {
                console.log('Match found:', pasien);

                const itemDiv = document.createElement('div');
                itemDiv.setAttribute('class', 'autocomplete-item');

                // Highlight matching text
                const teleponDisplay = highlightText(pasien.telepon || '', value);
                const namaDisplay = highlightText(pasien.nama || '', value);

                itemDiv.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div><strong>${teleponDisplay}</strong></div>
                            <div><small class="text-muted">${namaDisplay}</small></div>
                        </div>
                        <small class="text-primary">ID: ${pasien.id}</small>
                    </div>
                `;

                // Add click event
                itemDiv.addEventListener('click', async function() {
                    console.log('Patient selected:', pasien);
                    inputElement.value = pasien.telepon;
                    closeAllLists();

                    // Auto-fill message
                    const messageArea = document.getElementById('waMessageText');
                    if (messageArea) {
                        messageArea.value = 'Memuat pesan antrian...';
                        messageArea.style.backgroundColor = '#fff3cd';

                        const queueMessage = await getQueueMessage(pasien.telepon);
                        messageArea.value = queueMessage;
                        messageArea.style.backgroundColor = '#f8f9fa';

                        console.log('Message loaded:', queueMessage);
                    }
                });

                listContainer.appendChild(itemDiv);
                itemCount++;
            }
        }

        if (itemCount === 0) {
            const noResultDiv = document.createElement('div');
            noResultDiv.setAttribute('class', 'autocomplete-item');
            noResultDiv.innerHTML = `
                <div class="text-muted text-center py-2">
                    <i class="fas fa-search"></i> Tidak ada pasien ditemukan
                </div>
            `;
            listContainer.appendChild(noResultDiv);
        }

        console.log('Autocomplete suggestions created:', itemCount, 'items');
    });

    // Keyboard navigation
    inputElement.addEventListener('keydown', function(e) {
        let items = document.getElementById(this.id + 'autocomplete-list');
        if (items) items = items.getElementsByTagName('div');

        if (e.keyCode === 40) { // DOWN
            currentFocus++;
            addActive(items);
            e.preventDefault();
        } else if (e.keyCode === 38) { // UP
            currentFocus--;
            addActive(items);
            e.preventDefault();
        } else if (e.keyCode === 13) { // ENTER
            e.preventDefault();
            if (currentFocus > -1) {
                if (items && items[currentFocus]) {
                    items[currentFocus].click();
                }
            }
        } else if (e.keyCode === 27) { // ESCAPE
            closeAllLists();
        }
    });

    function highlightText(text, searchTerm) {
        if (!searchTerm || !text) return text;
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    function addActive(items) {
        if (!items) return false;
        removeActive(items);
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (items.length - 1);
        if (items[currentFocus]) {
            items[currentFocus].classList.add('autocomplete-active');
        }
    }

    function removeActive(items) {
        for (let i = 0; i < items.length; i++) {
            items[i].classList.remove('autocomplete-active');
        }
    }

    function closeAllLists(elmnt) {
        const items = document.getElementsByClassName('autocomplete-items');
        for (let i = 0; i < items.length; i++) {
            if (elmnt != items[i] && elmnt != inputElement) {
                items[i].parentNode.removeChild(items[i]);
            }
        }
    }

    // Close on outside click
    document.addEventListener('click', function(e) {
        if (!inputElement.contains(e.target)) {
            closeAllLists(e.target);
        }
    });
}

/**
 * Buka modal WhatsApp Gateway dengan informasi lengkap
 */
async function openWaGatewayModal(sessionId) {
    currentWaSessionId = sessionId;
    const modalEl = document.getElementById('waGatewayModal');
    const bodyEl = document.getElementById('waGatewayBody');
    const modal = new bootstrap.Modal(modalEl);

    // Update modal title
    document.querySelector('#waGatewayModal .modal-title').innerHTML =
        `<i class="fas fa-mobile-alt"></i> WhatsApp Gateway - ${sessionId}`;

    // Show loading state
    bodyEl.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Memuat informasi WhatsApp Gateway...</p>
        </div>
    `;

    // Show modal
    modal.show();

    // Load patient data first
    await loadPasienData();

    // Load data
    await loadWaGatewayData(sessionId);

    // Set up auto refresh every 10 seconds (dikurangi frekuensi untuk debugging)
    if (waSessionInterval) {
        clearInterval(waSessionInterval);
    }

    waSessionInterval = setInterval(() => {
        if (currentWaSessionId) {
            loadWaGatewayData(currentWaSessionId);
        }
    }, 10000);

    // Clear interval when modal is closed
    modalEl.addEventListener('hidden.bs.modal', function () {
        if (waSessionInterval) {
            clearInterval(waSessionInterval);
            waSessionInterval = null;
        }
        currentWaSessionId = '';
    }, { once: true });
}

/**
 * Load data untuk modal WhatsApp Gateway
 */
async function loadWaGatewayData(sessionId) {
    const bodyEl = document.getElementById('waGatewayBody');

    try {
        // Test koneksi ke API dulu
        console.log(`Loading data for session: ${sessionId}`);

        // Fetch data satu per satu untuk debugging
        let statusData = null;
        let qrData = null;
        let tokenData = null;

        // 1. Get Status
        try {
            const statusRes = await fetch(`/api/whatsapp/session/${sessionId}/status`, {
                headers: { 'Accept': 'application/json' }
            });
            statusData = await statusRes.json();
            console.log('Status response:', statusData);
        } catch (err) {
            console.error('Status fetch error:', err);
        }

        // 2. Get QR
        try {
            const qrRes = await fetch(`/api/whatsapp/session/${sessionId}/qr`, {
                headers: { 'Accept': 'application/json' }
            });
            qrData = await qrRes.json();
            console.log('QR response:', qrData);
        } catch (err) {
            console.error('QR fetch error:', err);
        }

        // 3. Get Token
        try {
            const tokenRes = await fetch(`/api/whatsapp/session/${sessionId}/token`, {
                headers: { 'Accept': 'application/json' }
            });
            tokenData = await tokenRes.json();
            console.log('Token response:', tokenData);
        } catch (err) {
            console.error('Token fetch error:', err);
        }

        // Status configurations
        const statusConfigs = {
            ready: {
                class: 'status-ready',
                icon: '🚀',
                text: 'Siap',
                description: 'Session siap untuk mengirim pesan',
                bgClass: 'alert-success'
            },
            qr_ready: {
                class: 'status-qr_ready',
                icon: '📱',
                text: 'QR Ready',
                description: 'Scan QR code untuk login',
                bgClass: 'alert-info'
            },
            initializing: {
                class: 'status-initializing',
                icon: '🔄',
                text: 'Loading',
                description: 'Sedang menginisialisasi...',
                bgClass: 'alert-warning'
            },
            authenticated: {
                class: 'status-qr_ready',
                icon: '✅',
                text: 'Authenticated',
                description: 'Berhasil login, menunggu ready...',
                bgClass: 'alert-info'
            },
            disconnected: {
                class: 'status-disconnected',
                icon: '❌',
                text: 'Terputus',
                description: 'Koneksi terputus, perlu login ulang',
                bgClass: 'alert-danger'
            },
            auth_failed: {
                class: 'status-disconnected',
                icon: '⚠️',
                text: 'Auth Gagal',
                description: 'Login gagal, coba lagi',
                bgClass: 'alert-danger'
            }
        };

        let html = '';

        // 1. STATUS SECTION
        if (statusData && statusData.success) {
            const config = statusConfigs[statusData.status] || statusConfigs.initializing;

            html += `
                <div class="alert ${config.bgClass} border-0 shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Status Session</h6>
                        <span class="badge bg-light text-dark">${statusData.ready ? 'Ready' : 'Not Ready'}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="status-badge ${config.class} px-3 py-2 rounded-pill">
                            ${config.icon} ${config.text}
                        </span>
                        <small class="text-muted">Session ID: ${sessionId}</small>
                    </div>
                    <p class="mt-2 mb-0 small">${config.description}</p>
                </div>
            `;

            // Show logout button if authenticated or ready
            const logoutBtn = document.getElementById('logoutBtn');
            if (logoutBtn) {
                if (statusData.status === 'ready' || statusData.status === 'authenticated') {
                    logoutBtn.style.display = 'inline-block';
                } else {
                    logoutBtn.style.display = 'none';
                }
            }
        } else {
            html += `
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning:</strong> Tidak dapat memuat status session.
                    ${statusData ? `<br><small>Error: ${statusData.error || 'Unknown error'}</small>` : ''}
                </div>
            `;
        }

        // 2. QR CODE SECTION
        if (qrData && qrData.success && qrData.qrImage) {
            html += `
                <div class="card border-primary mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-qrcode"></i> Scan QR Code untuk Login</h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="${qrData.qrImage}" class="img-fluid rounded shadow" style="max-width: 300px; max-height: 300px;">
                        <div class="mt-3">
                            <p class="text-muted mb-2">
                                <strong>Langkah-langkah:</strong>
                            </p>
                            <ol class="text-muted small text-start" style="max-width: 300px; margin: 0 auto;">
                                <li>Buka WhatsApp di ponsel Anda</li>
                                <li>Ketuk Menu (⋮) atau Settings</li>
                                <li>Pilih "Linked Devices"</li>
                                <li>Ketuk "Link a Device"</li>
                                <li>Scan QR code di atas</li>
                            </ol>
                        </div>
                    </div>
                </div>
            `;
        } else if (statusData && statusData.success && statusData.status !== 'qr_ready') {
            html += `
                <div class="card border-secondary mb-4">
                    <div class="card-body text-center py-4">
                        <div class="display-1 mb-3">${statusConfigs[statusData.status]?.icon || '🔄'}</div>
                        <h6>${statusConfigs[statusData.status]?.text || 'Processing...'}</h6>
                        <p class="text-muted mb-0">${statusConfigs[statusData.status]?.description || 'Please wait...'}</p>
                    </div>
                </div>
            `;
        }

        // 3. TOKEN INFORMATION SECTION
        if (tokenData && tokenData.success && tokenData.tokenInfo) {
            const token = tokenData.tokenInfo;

            html += `
                <div class="card border-warning mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="fas fa-key"></i> Informasi Token</h6>
                    </div>
                    <div class="card-body">
                        <!-- Token Usage Summary -->
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <div class="fw-bold fs-4 ${(token.remaining || 0) > 0 ? 'text-success' : 'text-danger'}">${token.remaining || 0}</div>
                                    <small class="text-muted">Tersisa</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <div class="fw-bold fs-4">${token.messagesSent || 0}</div>
                                    <small class="text-muted">Terpakai</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-2">
                                    <div class="fw-bold fs-4">${token.messageLimit || 0}</div>
                                    <small class="text-muted">Total Limit</small>
                                </div>
                            </div>
                        </div>

                        <!-- Free Token Info -->
                        ${token.freeToken ? `
                            <div class="alert alert-success alert-sm mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><strong>🆓 Free Token</strong></span>
                                    <span class="badge ${token.freeToken.active ? 'bg-success' : 'bg-secondary'}">${token.freeToken.active ? 'Active' : 'Inactive'}</span>
                                </div>
                                <small>Used: ${token.freeToken.used || 0}/${token.freeToken.limit || 0}</small>
                            </div>
                        ` : ''}

                        <!-- Premium Token Info -->
                        ${token.premiumToken && token.premiumToken.token ? `
                            <div class="alert alert-info alert-sm mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><strong>💎 Premium Token</strong></span>
                                    <span class="badge ${token.premiumToken.active ? 'bg-info' : 'bg-secondary'}">${token.premiumToken.active ? 'Active' : 'Inactive'}</span>
                                </div>
                                <small>Used: ${token.premiumToken.used || 0}/${token.premiumToken.limit || 0}</small>
                                <br><small><strong>Token:</strong> ${token.premiumToken.token.substring(0, 10)}...</small>
                                ${token.premiumToken.expiry ? `<br><small><strong>Expires:</strong> ${new Date(token.premiumToken.expiry).toLocaleString('id-ID')}</small>` : ''}
                            </div>
                        ` : ''}

                        <!-- Token Management Buttons -->
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="openTokenManagement('${sessionId}')">
                                <i class="fas fa-cog"></i> Kelola Token
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // 4. SEND MESSAGE FORM
        const sessionReady = statusData && statusData.success && statusData.ready;

        html += `
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-paper-plane"></i> Kirim Pesan WhatsApp</h6>
                </div>
                <div class="card-body">
                    <form id="waGatewaySendForm">
                        <input type="hidden" id="waSendSessionId" value="${sessionId}">

                        <!-- Input nomor telepon dengan autocomplete -->
                        <div class="mb-3 position-relative">
                            <label for="waPhoneNumber" class="form-label fw-semibold">
                                <i class="fas fa-phone"></i> Nomor Telepon Pasien:
                            </label>
                            <input type="text" class="form-control" id="waPhoneNumber"
                                   placeholder="Ketik nama pasien atau nomor telepon..."
                                   autocomplete="off" required>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Ketik untuk mencari pasien berdasarkan nama atau nomor telepon
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-comment"></i> Pesan Antrian:
                            </label>
                            <textarea class="form-control" id="waMessageText" rows="4"
                                      placeholder="Pilih nomor telepon untuk melihat pesan antrian..."
                                      readonly style="background-color: #f8f9fa;"></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Pesan akan terisi otomatis berdasarkan data antrian pasien
                            </div>
                        </div>

                        <!-- Token Status untuk Send Message -->
                        <div id="waTokenStatusInfo" class="alert" style="display: none;"></div>

                        <div class="d-grid">
                            <button type="button" class="btn btn-success" id="waSendMessageBtn"
                                    onclick="sendWaGatewayMessage()"
                                    ${sessionReady ? '' : 'disabled'}>
                                <i class="fas fa-paper-plane"></i> Kirim Pesan
                            </button>
                        </div>

                        ${!sessionReady ? `
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Session belum ready!</strong> Scan QR code terlebih dahulu untuk mengaktifkan session.
                            </div>
                        ` : ''}
                    </form>
                </div>
            </div>
        `;

        bodyEl.innerHTML = html;

        // Setup autocomplete after DOM is updated
        setTimeout(() => {
            const phoneInput = document.getElementById('waPhoneNumber');
            if (phoneInput) {
                console.log('Setting up autocomplete for phone input');
                console.log('Patient data available:', pasienData.length, 'records');

                // Make parent container position relative
                phoneInput.parentNode.classList.add('position-relative');

                if (pasienData.length > 0) {
                    setupAutocomplete(phoneInput);
                    console.log('Autocomplete setup completed');
                } else {
                    console.warn('No patient data available for autocomplete');

                    // Add a test button for debugging
                    const testBtn = document.createElement('button');
                    testBtn.type = 'button';
                    testBtn.className = 'btn btn-sm btn-info mt-2';
                    testBtn.innerHTML = '<i class="fas fa-bug"></i> Test Load Data';
                    testBtn.onclick = async function() {
                        console.log('Testing data load...');
                        await loadPasienData();
                        console.log('Data after test load:', pasienData);
                        if (pasienData.length > 0) {
                            setupAutocomplete(phoneInput);
                            this.style.display = 'none';
                        }
                    };
                    phoneInput.parentNode.appendChild(testBtn);
                }
            } else {
                console.error('Phone input element not found');
            }
        }, 500);

    } catch (error) {
        console.error('Error loading WA Gateway data:', error);
        bodyEl.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Error!</strong> Gagal memuat data WhatsApp Gateway.
                <br><small class="text-muted">Error: ${error.message}</small>
                <hr>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-danger btn-sm" onclick="loadWaGatewayData('${sessionId}')">
                        <i class="fas fa-redo"></i> Coba Lagi
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="debugAPI('${sessionId}')">
                        <i class="fas fa-bug"></i> Debug API
                    </button>
                </div>
            </div>
        `;
    }
}

/**
 * Kirim pesan melalui WhatsApp Gateway
 */
async function sendWaGatewayMessage() {
    const sessionId = document.getElementById('waSendSessionId').value;
    const phoneNumber = document.getElementById('waPhoneNumber').value.trim();
    const messageText = document.getElementById('waMessageText').value.trim();
    const statusInfo = document.getElementById('waTokenStatusInfo');
    const sendBtn = document.getElementById('waSendMessageBtn');

    // Validation
    if (!phoneNumber || !messageText) {
        showWaAlert('danger', 'Nomor HP dan pesan harus diisi!');
        return;
    }

    if (!/^628\d{8,12}$/.test(phoneNumber)) {
        showWaAlert('danger', 'Format nomor HP tidak valid. Gunakan format: 628xxxxxxxxx');
        return;
    }

    if (messageText.length > 1000) {
        showWaAlert('danger', 'Pesan terlalu panjang! Maksimal 1000 karakter.');
        return;
    }

    // Set loading state
    const originalBtnText = sendBtn.innerHTML;
    sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
    sendBtn.disabled = true;

    try {
        const response = await fetch(`/api/whatsapp/session/${sessionId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                number: phoneNumber,
                message: messageText
            })
        });

        const data = await response.json();
        console.log('Send message response:', data);

        if (data.success) {
            showWaAlert('success', `✅ Pesan berhasil dikirim ke ${phoneNumber}!`);

            // Clear form
            document.getElementById('waPhoneNumber').value = '';
            document.getElementById('waMessageText').value = '';

            // Show token usage info if available
            if (data.data && data.data.tokenUsage) {
                const usage = data.data.tokenUsage;
                setTimeout(() => {
                    showWaAlert('info', `📊 Token tersisa: ${usage.remaining} dari ${usage.limit}`);
                }, 2000);
            }

            // Refresh data setelah 2 detik
            setTimeout(() => {
                loadWaGatewayData(sessionId);
            }, 2000);

        } else {
            showWaAlert('danger', `❌ Gagal mengirim pesan: ${data.error}`);
        }

    } catch (error) {
        console.error('Send message error:', error);
        showWaAlert('danger', `❌ Error: ${error.message}`);
    } finally {
        // Reset button
        sendBtn.innerHTML = originalBtnText;
        sendBtn.disabled = false;
    }
}


/**
 * Debug API connections
 */
async function debugAPI(sessionId) {
    console.log('=== DEBUG API CONNECTIONS ===');

    const endpoints = [
        `/api/whatsapp/session/${sessionId}/status`,
        `/api/whatsapp/session/${sessionId}/qr`,
        `/api/whatsapp/session/${sessionId}/token`
    ];

    for (let endpoint of endpoints) {
        try {
            console.log(`Testing: ${endpoint}`);
            const response = await fetch(endpoint, {
                headers: { 'Accept': 'application/json' }
            });

            console.log(`Status: ${response.status} ${response.statusText}`);

            if (response.ok) {
                const data = await response.json();
                console.log('Response:', data);
            } else {
                const text = await response.text();
                console.log('Error response:', text);
            }
        } catch (err) {
            console.error(`Error for ${endpoint}:`, err);
        }
        console.log('---');
    }

    alert('Debug selesai. Lihat console untuk detail.');
}

/**
 * Show alert dalam WhatsApp Gateway modal
 */
function showWaAlert(type, message) {
    const statusInfo = document.getElementById('waTokenStatusInfo');
    if (!statusInfo) return;

    const alertClass = {
        'success': 'alert-success',
        'danger': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    };

    statusInfo.className = `alert ${alertClass[type]}`;
    statusInfo.innerHTML = message;
    statusInfo.style.display = 'block';

    // Auto hide after 5 seconds
    setTimeout(() => {
        statusInfo.style.display = 'none';
    }, 5000);
}

/**
 * Logout WhatsApp session
 */
async function logoutSession() {
    if (!currentWaSessionId) return;

    // Gunakan confirm biasa jika SweetAlert2 tidak tersedia
    let confirmed = false;

    if (typeof Swal !== 'undefined') {
        const result = await Swal.fire({
            title: 'Logout WhatsApp?',
            text: `Session "${currentWaSessionId}" akan logout dan perlu scan QR lagi`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal'
        });
        confirmed = result.isConfirmed;
    } else {
        confirmed = confirm(`Logout session "${currentWaSessionId}"? Session akan logout dan perlu scan QR lagi.`);
    }

    if (!confirmed) return;

    try {
        const response = await fetch(`/api/whatsapp/session/${currentWaSessionId}/logout`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Success!', `Session "${currentWaSessionId}" berhasil logout!`, 'success');
            } else {
                alert(`Session "${currentWaSessionId}" berhasil logout!`);
            }
            // Refresh data
            loadWaGatewayData(currentWaSessionId);
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Warning!', 'Logout completed dengan warning: ' + (data.warning || data.message), 'warning');
            } else {
                alert('Logout completed dengan warning: ' + (data.warning || data.message));
            }
            loadWaGatewayData(currentWaSessionId);
        }

    } catch (error) {
        console.error('Logout error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error!', 'Error: ' + error.message, 'error');
        } else {
            alert('Error: ' + error.message);
        }
    }
}

/**
 * Open token management
 */
async function openTokenManagement(sessionId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Token Management',
            text: `Kelola token untuk session: ${sessionId}`,
            icon: 'info',
            html: `
                <div class="text-start">
                    <p><strong>Session:</strong> ${sessionId}</p>
                    <p class="text-muted">Fitur kelola token akan segera tersedia.</p>
                    <p class="small">Anda dapat menambah, mengedit, atau menghapus token premium di sini.</p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'OK',
            cancelButtonText: 'Tutup'
        });
    } else {
        alert(`Token Management untuk session: ${sessionId}\nFitur akan segera tersedia.`);
    }
}

// Initialize saat DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('WhatsApp Gateway Modal initialized');

    // Test API availability saat load
    if (window.location.pathname.includes('whatsapp') || document.querySelector('[onclick*="openWaGatewayModal"]')) {
        console.log('WhatsApp Gateway features detected, ready to use.');
    }

    // Add CSS for autocomplete
    const style = document.createElement('style');
    style.textContent = `
        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4edda;
            border-top: none;
            z-index: 99;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 250px;
            overflow-y: auto;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            border-radius: 0 0 8px 8px;
        }
        .autocomplete-item {
            padding: 12px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s ease;
        }
        .autocomplete-item:last-child {
            border-bottom: none;
        }
        .autocomplete-item:hover, .autocomplete-active {
            background-color: #e3f2fd !important;
        }
        .autocomplete-item strong {
            color: #007bff;
            font-size: 14px;
        }
        .autocomplete-item mark {
            background-color: #fff3cd;
            padding: 1px 2px;
            border-radius: 2px;
        }
        #waPhoneNumber {
            position: relative;
        }
        .position-relative {
            position: relative !important;
        }
    `;
    document.head.appendChild(style);
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
                        $('#toggleTindakanAll').prop('checked', states.is_tindakan_active == 1);
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
