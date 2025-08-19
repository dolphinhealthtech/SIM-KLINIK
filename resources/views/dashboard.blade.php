<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WhatsApp Gateway Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-ready {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-qr_ready {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-initializing {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-disconnected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.2s ease;
        }
        .qr-container {
            text-align: center;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 2px dashed #cbd5e1;
        }
        .token-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
        }
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">📱 WhatsApp Gateway Dashboard</h2>
                        <p class="text-muted mb-0">Kelola multi-session WhatsApp dalam satu dashboard</p>
                    </div>
                    <div>
                        <button class="btn btn-primary me-2" onclick="refreshSessions()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button class="btn btn-success" onclick="showCreateSessionModal()">
                            <i class="fas fa-plus"></i> Buat Session
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions List -->
        <div class="row" id="sessions-container">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Memuat sessions...</p>
            </div>
        </div>
    </div>

    <!-- Create Session Modal -->
    <div class="modal fade" id="createSessionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle text-success"></i> Buat Session Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="createSessionForm">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Session ID:</label>
                            <input type="text" class="form-control" id="sessionId"
                                   placeholder="contoh: project1, client-abc, dll" required>
                            <div class="form-text">Gunakan format: huruf, angka, underscore, dan hyphen saja</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="createSession()">
                        <i class="fas fa-rocket"></i> Buat Session
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Detail Modal -->
    <div class="modal fade" id="sessionDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sessionDetailTitle">
                        <i class="fas fa-mobile-alt"></i> Session Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="sessionDetailBody">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="refreshSessionBtn" onclick="refreshSessionDetail()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Message Modal -->
    <div class="modal fade" id="sendMessageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-paper-plane text-primary"></i> Kirim Pesan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="sendMessageForm">
                        <input type="hidden" id="sendSessionId" value="">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor HP:</label>
                            <input type="text" class="form-control" id="phoneNumber"
                                   placeholder="628123456789" required>
                            <div class="form-text">Format: 628xxxxxxxxx (tanpa +)</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pesan:</label>
                            <textarea class="form-control" id="messageText" rows="4"
                                      placeholder="Tulis pesan Anda..." required></textarea>
                        </div>
                        <div id="tokenStatusInfo" class="alert alert-info" style="display: none;">
                            <!-- Token info will be shown here -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="sendMessage()" id="sendMessageBtn">
                        <i class="fas fa-paper-plane"></i> Kirim Pesan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Token Management Modal -->
    <div class="modal fade" id="tokenModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key text-warning"></i> Kelola Token
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="tokenModalBody">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global variables
        let currentSessionId = '';
        let sessionDetailInterval = null;

        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadSessions();

            // Auto refresh sessions every 30 seconds
            setInterval(loadSessions, 30000);
        });

        /**
         * Load all WhatsApp sessions
         */
        async function loadSessions() {
            try {
                const response = await fetch('/api/whatsapp/sessions', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    displaySessions(data.sessions || []);
                } else {
                    console.error('Failed to load sessions:', data.error);
                    showError('Gagal memuat sessions: ' + data.error);
                }
            } catch (error) {
                console.error('Error loading sessions:', error);
                showError('Error koneksi: ' + error.message);
            }
        }

        /**
         * Display sessions in the UI
         */
        function displaySessions(sessions) {
            const container = document.getElementById('sessions-container');

            if (sessions.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="card text-center py-5">
                            <div class="card-body">
                                <i class="fas fa-mobile-alt fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">Belum Ada Session</h4>
                                <p class="text-muted">Buat session baru untuk memulai</p>
                                <button class="btn btn-success" onclick="showCreateSessionModal()">
                                    <i class="fas fa-plus"></i> Buat Session Pertama
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                return;
            }

            let html = '';
            sessions.forEach(session => {
                const statusConfig = getStatusConfig(session.status);

                html += `
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card h-100 card-hover">
                            <div class="card-header bg-white border-bottom-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold">📱 ${session.sessionId}</h6>
                                    <span class="status-badge ${statusConfig.class}">
                                        ${statusConfig.icon} ${statusConfig.text}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">${statusConfig.description}</p>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary btn-sm"
                                            onclick="showSessionDetail('${session.sessionId}')">
                                        <i class="fas fa-eye"></i> Detail & QR
                                    </button>

                                    <div class="btn-group" role="group">
                                        <button class="btn btn-outline-success btn-sm"
                                                onclick="showSendMessageModal('${session.sessionId}')"
                                                ${session.ready ? '' : 'disabled'}>
                                            <i class="fas fa-paper-plane"></i> Kirim
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm"
                                                onclick="showTokenModal('${session.sessionId}')">
                                            <i class="fas fa-key"></i> Token
                                        </button>
                                    </div>

                                    <div class="btn-group" role="group">
                                        <button class="btn btn-outline-secondary btn-sm"
                                                onclick="logoutSession('${session.sessionId}')">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm"
                                                onclick="deleteSession('${session.sessionId}')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        /**
         * Get status configuration for display
         */
        function getStatusConfig(status) {
            const configs = {
                ready: {
                    class: 'status-ready',
                    icon: '🚀',
                    text: 'Siap',
                    description: 'Session siap untuk mengirim pesan'
                },
                qr_ready: {
                    class: 'status-qr_ready',
                    icon: '📱',
                    text: 'QR Ready',
                    description: 'Scan QR code untuk login'
                },
                initializing: {
                    class: 'status-initializing',
                    icon: '🔄',
                    text: 'Loading',
                    description: 'Sedang menginisialisasi...'
                },
                authenticated: {
                    class: 'status-qr_ready',
                    icon: '✅',
                    text: 'Auth OK',
                    description: 'Berhasil login, menunggu ready...'
                },
                disconnected: {
                    class: 'status-disconnected',
                    icon: '❌',
                    text: 'Terputus',
                    description: 'Koneksi terputus, perlu login ulang'
                },
                auth_failed: {
                    class: 'status-disconnected',
                    icon: '⚠️',
                    text: 'Auth Gagal',
                    description: 'Login gagal, coba lagi'
                }
            };

            return configs[status] || configs.initializing;
        }

        /**
         * Show create session modal
         */
        function showCreateSessionModal() {
            const modal = new bootstrap.Modal(document.getElementById('createSessionModal'));
            document.getElementById('sessionId').value = '';
            modal.show();
        }

        /**
         * Create new session
         */
        async function createSession() {
            const sessionId = document.getElementById('sessionId').value.trim();

            if (!sessionId) {
                showError('Session ID tidak boleh kosong');
                return;
            }

            // Validate session ID format
            if (!/^[a-zA-Z0-9_-]+$/.test(sessionId)) {
                showError('Session ID hanya boleh mengandung huruf, angka, underscore, dan hyphen');
                return;
            }

            try {
                // Show loading
                showLoading('Membuat session...');

                // Check session status to create it
                const response = await fetch(`/api/whatsapp/session/${sessionId}/status`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                hideLoading();

                if (data.success) {
                    showSuccess(`Session "${sessionId}" berhasil dibuat!`);
                    bootstrap.Modal.getInstance(document.getElementById('createSessionModal')).hide();

                    // Refresh sessions and show detail
                    setTimeout(() => {
                        loadSessions();
                        showSessionDetail(sessionId);
                    }, 1000);
                } else {
                    showError('Gagal membuat session: ' + data.error);
                }

            } catch (error) {
                hideLoading();
                console.error('Create session error:', error);
                showError('Error: ' + error.message);
            }
        }

        /**
         * Show session detail modal with QR code
         */
        async function showSessionDetail(sessionId) {
            currentSessionId = sessionId;
            const modal = new bootstrap.Modal(document.getElementById('sessionDetailModal'));

            // Update modal title
            document.getElementById('sessionDetailTitle').innerHTML =
                `<i class="fas fa-mobile-alt"></i> Session: ${sessionId}`;

            // Show modal first
            modal.show();

            // Load session detail
            await loadSessionDetail(sessionId);

            // Set up auto refresh for QR and status
            if (sessionDetailInterval) {
                clearInterval(sessionDetailInterval);
            }

            sessionDetailInterval = setInterval(() => {
                if (currentSessionId) {
                    loadSessionDetail(currentSessionId);
                }
            }, 5000);

            // Clear interval when modal is hidden
            document.getElementById('sessionDetailModal').addEventListener('hidden.bs.modal', function () {
                if (sessionDetailInterval) {
                    clearInterval(sessionDetailInterval);
                    sessionDetailInterval = null;
                }
                currentSessionId = '';
            });
        }

        /**
         * Load session detail content
         */
        async function loadSessionDetail(sessionId) {
            const body = document.getElementById('sessionDetailBody');

            try {
                // Load status and QR in parallel
                const [statusResponse, qrResponse, tokenResponse] = await Promise.all([
                    fetch(`/api/whatsapp/session/${sessionId}/status`, {
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                    }),
                    fetch(`/api/whatsapp/session/${sessionId}/qr`, {
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                    }),
                    fetch(`/api/whatsapp/session/${sessionId}/token`, {
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                    })
                ]);

                const statusData = await statusResponse.json();
                const qrData = await qrResponse.json();
                const tokenData = await tokenResponse.json();

                let html = '';

                // Status Section
                if (statusData.success) {
                    const statusConfig = getStatusConfig(statusData.status);
                    html += `
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Status Session</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="status-badge ${statusConfig.class}">
                                    ${statusConfig.icon} ${statusConfig.text}
                                </span>
                                <small class="text-muted">Ready: ${statusData.ready ? 'Yes' : 'No'}</small>
                            </div>
                            <p class="mt-2 mb-0">${statusConfig.description}</p>
                        </div>
                    `;
                }

                // QR Code Section
                if (qrData.success && qrData.qrImage) {
                    html += `
                        <div class="qr-container mb-4">
                            <h6><i class="fas fa-qrcode"></i> Scan QR Code</h6>
                            <img src="${qrData.qrImage}" class="img-fluid" style="max-width: 300px; border-radius: 8px;">
                            <p class="text-muted small mt-2">
                                Buka WhatsApp → Menu (⋮) → Linked devices → Link a device
                            </p>
                        </div>
                    `;
                } else if (statusData.success && statusData.status !== 'qr_ready') {
                    html += `
                        <div class="qr-container mb-4">
                            <h6>${getStatusConfig(statusData.status).icon} ${getStatusConfig(statusData.status).text}</h6>
                            <p class="text-muted">${getStatusConfig(statusData.status).description}</p>
                        </div>
                    `;
                }

                // Token Information
                if (tokenData.success && tokenData.tokenInfo) {
                    const token = tokenData.tokenInfo;
                    html += `
                        <div class="token-info mb-4">
                            <h6><i class="fas fa-key"></i> Token Information</h6>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="fw-bold fs-4">${token.remaining || 0}</div>
                                    <small>Tersisa</small>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold fs-4">${token.messagesSent || 0}</div>
                                    <small>Terpakai</small>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold fs-4">${token.messageLimit || 0}</div>
                                    <small>Total</small>
                                </div>
                            </div>

                            ${token.freeToken ? `
                                <div class="mt-3 p-2 bg-white bg-opacity-25 rounded">
                                    <small><strong>Free Token:</strong> ${token.freeToken.used || 0}/${token.freeToken.limit || 0}
                                    (${token.freeToken.active ? 'Active' : 'Inactive'})</small>
                                </div>
                            ` : ''}

                            ${token.premiumToken && token.premiumToken.token ? `
                                <div class="mt-2 p-2 bg-white bg-opacity-25 rounded">
                                    <small><strong>Premium Token:</strong> ${token.premiumToken.used || 0}/${token.premiumToken.limit || 0}
                                    (${token.premiumToken.active ? 'Active' : 'Inactive'})</small>
                                    ${token.premiumToken.expiry ? `<br><small><strong>Expires:</strong> ${new Date(token.premiumToken.expiry).toLocaleString('id-ID')}</small>` : ''}
                                </div>
                            ` : ''}
                        </div>
                    `;
                }

                // Action Buttons
                html += `
                    <div class="d-grid gap-2">
                        <div class="btn-group">
                            <button class="btn btn-success" onclick="showSendMessageModal('${sessionId}')"
                                    ${statusData.ready ? '' : 'disabled'}>
                                <i class="fas fa-paper-plane"></i> Kirim Pesan
                            </button>
                            <button class="btn btn-warning" onclick="showTokenModal('${sessionId}')">
                                <i class="fas fa-key"></i> Kelola Token
                            </button>
                        </div>

                        <div class="btn-group">
                            <button class="btn btn-secondary" onclick="logoutSession('${sessionId}')">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                            <button class="btn btn-info" onclick="restartSession('${sessionId}')">
                                <i class="fas fa-redo"></i> Restart
                            </button>
                        </div>
                    </div>
                `;

                body.innerHTML = html;

            } catch (error) {
                console.error('Load session detail error:', error);
                body.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> Error loading session detail: ${error.message}
                    </div>
                `;
            }
        }

        /**
         * Refresh current session detail
         */
        function refreshSessionDetail() {
            if (currentSessionId) {
                loadSessionDetail(currentSessionId);
            }
        }

        /**
         * Show send message modal
         */
        async function showSendMessageModal(sessionId) {
            document.getElementById('sendSessionId').value = sessionId;
            document.getElementById('phoneNumber').value = '';
            document.getElementById('messageText').value = '';

            // Load token info
            try {
                const response = await fetch(`/api/whatsapp/session/${sessionId}/token`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                });

                const data = await response.json();

                if (data.success && data.tokenInfo) {
                    const token = data.tokenInfo;
                    const infoDiv = document.getElementById('tokenStatusInfo');

                    if (token.hasToken && token.remaining > 0) {
                        infoDiv.className = 'alert alert-success';
                        infoDiv.innerHTML = `
                            <i class="fas fa-check-circle"></i>
                            <strong>Token tersedia:</strong> ${token.remaining} pesan tersisa dari ${token.messageLimit} total
                        `;
                        infoDiv.style.display = 'block';
                        document.getElementById('sendMessageBtn').disabled = false;
                    } else {
                        infoDiv.className = 'alert alert-warning';
                        infoDiv.innerHTML = `
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Peringatan:</strong> ${!token.hasToken ? 'Tidak ada token aktif' : 'Limit pesan habis'}.
                            Kelola token terlebih dahulu.
                        `;
                        infoDiv.style.display = 'block';
                        document.getElementById('sendMessageBtn').disabled = true;
                    }
                } else {
                    document.getElementById('tokenStatusInfo').style.display = 'none';
                    document.getElementById('sendMessageBtn').disabled = false;
                }
            } catch (error) {
                console.error('Error loading token info:', error);
                document.getElementById('tokenStatusInfo').style.display = 'none';
            }

            const modal = new bootstrap.Modal(document.getElementById('sendMessageModal'));
            modal.show();
        }

        /**
         * Send message
         */
        async function sendMessage() {
            const sessionId = document.getElementById('sendSessionId').value;
            const phoneNumber = document.getElementById('phoneNumber').value.trim();
            const messageText = document.getElementById('messageText').value.trim();

            if (!phoneNumber || !messageText) {
                showError('Nomor HP dan pesan harus diisi');
                return;
            }

            // Validate phone number format
            if (!/^628\d{8,12}$/.test(phoneNumber)) {
                showError('Format nomor HP tidak valid. Gunakan format: 628xxxxxxxxx');
                return;
            }

            try {
                const sendBtn = document.getElementById('sendMessageBtn');
                const originalText = sendBtn.innerHTML;
                sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
                sendBtn.disabled = true;

                const response = await fetch(`/api/whatsapp/session/${sessionId}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        number: phoneNumber,
                        message: messageText
                    })
                });

                const data = await response.json();

                sendBtn.innerHTML = originalText;
                sendBtn.disabled = false;

                if (data.success) {
                    showSuccess(`Pesan berhasil dikirim ke ${phoneNumber}!`);
                    bootstrap.Modal.getInstance(document.getElementById('sendMessageModal')).hide();

                    // Update token info if available
                    if (data.data && data.data.tokenUsage) {
                        const usage = data.data.tokenUsage;
                        showInfo(`Token tersisa: ${usage.remaining} dari ${usage.limit}`);
                    }
                } else {
                    showError('Gagal mengirim pesan: ' + data.error);
                }

            } catch (error) {
                document.getElementById('sendMessageBtn').innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Pesan';
                document.getElementById('sendMessageBtn').disabled = false;
                console.error('Send message error:', error);
                showError('Error: ' + error.message);
            }
        }

        /**
         * Show token management modal
         */
        async function showTokenModal(sessionId) {
            const modal = new bootstrap.Modal(document.getElementById('tokenModal'));
            const body = document.getElementById('tokenModalBody');

            // Show loading
            body.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat informasi token...</p>
                </div>
            `;

            modal.show();

            try {
                const response = await fetch(`/api/whatsapp/session/${sessionId}/token`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                });

                const data = await response.json();

                let html = `<h6><i class="fas fa-key"></i> Token Management - ${sessionId}</h6>`;

                if (data.success && data.tokenInfo) {
                    const token = data.tokenInfo;

                    // Current Token Status
                    html += `
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">📊 Status Token Saat Ini</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center mb-3">
                                    <div class="col-4">
                                        <div class="fw-bold fs-5 ${token.remaining > 0 ? 'text-success' : 'text-danger'}">${token.remaining || 0}</div>
                                        <small class="text-muted">Tersisa</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold fs-5">${token.messagesSent || 0}</div>
                                        <small class="text-muted">Terpakai</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold fs-5">${token.messageLimit || 0}</div>
                                        <small class="text-muted">Total Limit</small>
                                    </div>
                                </div>
                    `;

                    // Free Token
                    if (token.freeToken) {
                        const freeRemaining = Math.max(0, (token.freeToken.limit || 0) - (token.freeToken.used || 0));
                        html += `
                            <div class="alert alert-success">
                                <strong>🆓 Free Token:</strong> ${freeRemaining}/${token.freeToken.limit || 0} tersisa
                                (${token.freeToken.active ? 'Active' : 'Inactive'})
                            </div>
                        `;
                    }

                    // Premium Token
                    if (token.premiumToken && token.premiumToken.token) {
                        const premiumRemaining = Math.max(0, (token.premiumToken.limit || 0) - (token.premiumToken.used || 0));
                        const isExpired = token.premiumToken.expiry && new Date() > new Date(token.premiumToken.expiry);

                        html += `
                            <div class="alert ${token.premiumToken.active && !isExpired ? 'alert-info' : 'alert-warning'}">
                                <strong>💎 Premium Token:</strong> ${premiumRemaining}/${token.premiumToken.limit || 0} tersisa<br>
                                <small><strong>Token:</strong> ${token.premiumToken.token}</small><br>
                                <small><strong>Status:</strong> ${token.premiumToken.active ? 'Active' : 'Inactive'}</small>
                                ${token.premiumToken.expiry ? `<br><small><strong>Expires:</strong> ${new Date(token.premiumToken.expiry).toLocaleString('id-ID')}</small>` : ''}
                                ${isExpired ? '<br><span class="text-danger"><strong>⚠️ Token sudah expired!</strong></span>' : ''}

                                <div class="mt-2">
                                    <button class="btn btn-sm ${token.premiumToken.active ? 'btn-warning' : 'btn-success'}"
                                            onclick="togglePremiumToken('${sessionId}', ${!token.premiumToken.active})">
                                        <i class="fas ${token.premiumToken.active ? 'fa-pause' : 'fa-play'}"></i>
                                        ${token.premiumToken.active ? 'Deactivate' : 'Activate'}
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deletePremiumToken('${sessionId}')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        `;
                    }

                    html += '</div></div>';

                    // Add Premium Token Form
                    html += `
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">➕ Tambah Premium Token</h6>
                            </div>
                            <div class="card-body">
                                <form id="addTokenForm">
                                    <div class="mb-3">
                                        <label class="form-label">Token:</label>
                                        <input type="text" class="form-control" id="newToken"
                                               placeholder="Masukkan token premium" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label">Limit Pesan:</label>
                                            <input type="number" class="form-control" id="tokenLimit"
                                                   min="1" value="100" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Berlaku (jam):</label>
                                            <input type="number" class="form-control" id="tokenExpiry"
                                                   min="1" value="24" required>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary" onclick="addPremiumToken('${sessionId}')">
                                            <i class="fas fa-plus"></i> Tambah Token
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    `;

                } else {
                    html += `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Tidak dapat memuat informasi token
                        </div>
                    `;
                }

                body.innerHTML = html;

            } catch (error) {
                console.error('Load token info error:', error);
                body.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> Error: ${error.message}
                    </div>
                `;
            }
        }

        /**
         * Add premium token
         */
        async function addPremiumToken(sessionId) {
            const token = document.getElementById('newToken').value.trim();
            const limit = document.getElementById('tokenLimit').value;
            const expiryHours = document.getElementById('tokenExpiry').value;

            if (!token || !limit || !expiryHours) {
                showError('Semua field harus diisi');
                return;
            }

            try {
                showLoading('Menambah token...');

                const response = await fetch(`/api/whatsapp/session/${sessionId}/token`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        token: token,
                        limit: parseInt(limit),
                        expiryHours: parseInt(expiryHours),
                        active: true
                    })
                });

                const data = await response.json();

                hideLoading();

                if (data.success) {
                    showSuccess('Premium token berhasil ditambahkan!');
                    showTokenModal(sessionId); // Refresh modal
                } else {
                    showError('Gagal menambah token: ' + data.error);
                }

            } catch (error) {
                hideLoading();
                console.error('Add token error:', error);
                showError('Error: ' + error.message);
            }
        }

        /**
         * Toggle premium token status
         */
        async function togglePremiumToken(sessionId, active) {
            try {
                showLoading(active ? 'Mengaktifkan token...' : 'Menonaktifkan token...');

                const response = await fetch(`/api/whatsapp/session/${sessionId}/token/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        active: active
                    })
                });

                const data = await response.json();

                hideLoading();

                if (data.success) {
                    showSuccess(`Token berhasil ${active ? 'diaktifkan' : 'dinonaktifkan'}!`);
                    showTokenModal(sessionId); // Refresh modal
                } else {
                    showError('Gagal mengubah status token: ' + data.error);
                }

            } catch (error) {
                hideLoading();
                console.error('Toggle token error:', error);
                showError('Error: ' + error.message);
            }
        }

        /**
         * Delete premium token
         */
        async function deletePremiumToken(sessionId) {
            const result = await Swal.fire({
                title: 'Hapus Premium Token?',
                text: 'Token premium akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });

            if (!result.isConfirmed) return;

            try {
                showLoading('Menghapus token...');

                const response = await fetch(`/api/whatsapp/session/${sessionId}/token`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                hideLoading();

                if (data.success) {
                    showSuccess('Premium token berhasil dihapus!');
                    showTokenModal(sessionId); // Refresh modal
                } else {
                    showError('Gagal menghapus token: ' + data.error);
                }

            } catch (error) {
                hideLoading();
                console.error('Delete token error:', error);
                showError('Error: ' + error.message);
            }
        }

        /**
         * Logout session
         */
        async function logoutSession(sessionId) {
            const result = await Swal.fire({
                title: 'Logout Session?',
                text: `Session "${sessionId}" akan logout dan perlu scan QR lagi`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Batal'
            });

            if (!result.isConfirmed) return;

            try {
                showLoading('Logout session...');

                const response = await fetch(`/api/whatsapp/session/${sessionId}/logout`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                hideLoading();

                if (data.success) {
                    showSuccess(`Session "${sessionId}" berhasil logout!`);
                    loadSessions(); // Refresh sessions list

                    // Close session detail modal if open
                    const modal = bootstrap.Modal.getInstance(document.getElementById('sessionDetailModal'));
                    if (modal) modal.hide();
                } else {
                    showWarning('Logout completed dengan warning: ' + (data.warning || data.message));
                    loadSessions();
                }

            } catch (error) {
                hideLoading();
                console.error('Logout error:', error);
                showError('Error: ' + error.message);
            }
        }

        /**
         * Delete session
         */
        async function deleteSession(sessionId) {
            const result = await Swal.fire({
                title: 'Hapus Session?',
                html: `
                    <div class="text-start">
                        <p><strong>⚠️ PERINGATAN!</strong></p>
                        <p>Session "<strong>${sessionId}</strong>" akan dihapus permanen:</p>
                        <ul class="text-muted small">
                            <li>Session akan dihentikan</li>
                            <li>Data WhatsApp akan dihapus</li>
                            <li>Perlu scan QR lagi untuk login</li>
                            <li>Token akan hilang</li>
                        </ul>
                        <p class="text-danger"><strong>Tindakan ini tidak dapat dibatalkan!</strong></p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus Permanen!',
                cancelButtonText: 'Batal'
            });

            if (!result.isConfirmed) return;

            try {
                showLoading('Menghapus session...');

                const response = await fetch(`/api/whatsapp/session/${sessionId}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                hideLoading();

                if (data.success) {
                    showSuccess(`Session "${sessionId}" berhasil dihapus!`);
                    loadSessions(); // Refresh sessions list

                    // Close session detail modal if open
                    const modal = bootstrap.Modal.getInstance(document.getElementById('sessionDetailModal'));
                    if (modal) modal.hide();
                } else {
                    showError('Gagal menghapus session: ' + data.error);
                }

            } catch (error) {
                hideLoading();
                console.error('Delete session error:', error);
                showError('Error: ' + error.message);
            }
        }

        /**
         * Restart session
         */
        async function restartSession(sessionId) {
            const result = await Swal.fire({
                title: 'Restart Session?',
                text: `Session "${sessionId}" akan direstart`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Restart!',
                cancelButtonText: 'Batal'
            });

            if (!result.isConfirmed) return;

            try {
                showLoading('Restart session...');

                const response = await fetch(`/api/whatsapp/session/${sessionId}/restart`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                hideLoading();

                if (data.success) {
                    showSuccess(`Session "${sessionId}" berhasil direstart!`);
                    loadSessions(); // Refresh sessions list

                    // Refresh session detail if modal is open
                    if (currentSessionId === sessionId) {
                        setTimeout(() => loadSessionDetail(sessionId), 2000);
                    }
                } else {
                    showError('Gagal restart session: ' + data.error);
                }

            } catch (error) {
                hideLoading();
                console.error('Restart session error:', error);
                showError('Error: ' + error.message);
            }
        }

        /**
         * Refresh all sessions
         */
        async function refreshSessions() {
            try {
                showLoading('Refresh sessions...');

                // First try to refresh from API
                const response = await fetch('/api/whatsapp/refresh-sessions', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                hideLoading();

                if (data.success) {
                    showSuccess(`Sessions refreshed! Total: ${data.totalSessions || 0}`);
                } else {
                    showWarning('Refresh completed with warnings');
                }

                // Always reload sessions from our API
                loadSessions();

            } catch (error) {
                hideLoading();
                console.error('Refresh sessions error:', error);
                showWarning('Refresh may have issues, but reloading sessions...');
                loadSessions(); // Still try to reload
            }
        }

        // Utility functions for notifications
        function showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message,
                confirmButtonColor: '#dc3545'
            });
        }

        function showWarning(message) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: message,
                confirmButtonColor: '#ffc107'
            });
        }

        function showInfo(message) {
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: message,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }

        function showLoading(message) {
            Swal.fire({
                title: 'Please wait...',
                text: message,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function hideLoading() {
            Swal.close();
        }

        // Add jQuery support for CSRF
        if (typeof $ !== 'undefined') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        // Global error handler for fetch requests
        window.addEventListener('unhandledrejection', function(event) {
            console.error('Unhandled promise rejection:', event.reason);
        });
    </script>
</body>
</html>
