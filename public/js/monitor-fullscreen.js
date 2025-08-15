/**
 * Monitor Fullscreen State Manager
 * Menangani state fullscreen saat refresh dan form submission
 */

(function ($) {
    "use strict";

    var MonitorFullscreen = {
        isInitialized: false,
        retryCount: 0,
        maxRetries: 3,

        init: function () {
            try {
                this.bindEvents();
                this.restoreFullscreenState();
                this.detectBrowserFullscreen();
                this.isInitialized = true;
            } catch (error) {
                this.handleError(error);
            }
        },

        // Error handling
        handleError: function (error) {
            if (this.retryCount < this.maxRetries) {
                this.retryCount++;
                setTimeout(function () {
                    MonitorFullscreen.init();
                }, 1000 * this.retryCount);
            }
        },

        // Menyimpan state fullscreen ke localStorage
        saveFullscreenState: function () {
            try {
                var isFullscreen = this.isFullscreen();
                localStorage.setItem("monitorFullscreen", isFullscreen);
            } catch (error) {
                // Silent error handling
            }
        },

        // Mengecek apakah sedang dalam mode fullscreen
        isFullscreen: function () {
            try {
                return !!(
                    document.fullscreenElement ||
                    document.mozFullScreenElement ||
                    document.webkitFullscreenElement ||
                    document.msFullscreenElement
                );
            } catch (error) {
                return false;
            }
        },

        // Deteksi browser fullscreen mode (F11)
        detectBrowserFullscreen: function () {
            var self = this;

            // Deteksi perubahan ukuran window untuk F11
            var lastWidth = window.innerWidth;
            var lastHeight = window.innerHeight;

            $(window).on("resize", function () {
                try {
                    var currentWidth = window.innerWidth;
                    var currentHeight = window.innerHeight;

                    // Jika ukuran berubah signifikan, kemungkinan F11
                    if (
                        Math.abs(currentWidth - lastWidth) > 100 ||
                        Math.abs(currentHeight - lastHeight) > 100
                    ) {
                        setTimeout(function () {
                            self.saveFullscreenState();
                        }, 200);
                    }

                    lastWidth = currentWidth;
                    lastHeight = currentHeight;
                } catch (error) {
                    // Silent error handling
                }
            });
        },

        // Memulihkan state fullscreen dari localStorage
        restoreFullscreenState: function () {
            try {
                var wasFullscreen =
                    localStorage.getItem("monitorFullscreen") === "true";

                if (wasFullscreen) {
                    // Tampilkan notifikasi untuk user mengklik fullscreen
                    this.showFullscreenPrompt();
                }
            } catch (error) {
                // Silent error handling
            }
        },

        // Tampilkan prompt untuk user mengklik fullscreen
        showFullscreenPrompt: function () {
            var self = this;

            // Cek apakah user sudah pernah menolak prompt ini
            var promptDismissed =
                localStorage.getItem("fullscreenPromptDismissed") === "true";
            if (promptDismissed) {
                return; // Jangan tampilkan lagi
            }

            // Buat overlay prompt
            var overlay = $("<div>", {
                class: "fullscreen-prompt-overlay",
                css: {
                    position: "fixed",
                    top: 0,
                    left: 0,
                    width: "100%",
                    height: "100%",
                    backgroundColor: "rgba(0,0,0,0.8)",
                    zIndex: 9999,
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                },
            });

            var prompt = $("<div>", {
                class: "fullscreen-prompt",
                css: {
                    backgroundColor: "#fff",
                    padding: "20px",
                    borderRadius: "10px",
                    textAlign: "center",
                    maxWidth: "400px",
                },
                html: `
                    <h4>Fullscreen Mode</h4>
                    <p>Halaman sebelumnya dalam mode fullscreen. Klik tombol di bawah untuk mengaktifkan fullscreen kembali.</p>
                    <button class="btn btn-primary" id="enable-fullscreen-btn">
                        <i class="fas fa-expand-arrows-alt"></i> Aktifkan Fullscreen
                    </button>
                    <button class="btn btn-secondary" id="dismiss-fullscreen-btn" style="margin-left: 10px;">
                        Tutup
                    </button>
                    <div style="margin-top: 10px;">
                        <label style="font-size: 12px; color: #999;">
                            <input type="checkbox" id="dont-show-again"> Jangan tampilkan lagi
                        </label>
                    </div>
                `,
            });

            overlay.append(prompt);
            $("body").append(overlay);

            // Event untuk tombol aktifkan fullscreen
            $("#enable-fullscreen-btn").on("click", function () {
                overlay.remove();
                self.enterFullscreen();
            });

            // Event untuk tombol tutup
            $("#dismiss-fullscreen-btn").on("click", function () {
                overlay.remove();
                localStorage.setItem("monitorFullscreen", "false");

                // Cek checkbox "jangan tampilkan lagi"
                if ($("#dont-show-again").is(":checked")) {
                    localStorage.setItem("fullscreenPromptDismissed", "true");
                }
            });

            // Auto hide setelah 15 detik
            setTimeout(function () {
                if (overlay.length) {
                    overlay.remove();
                }
            }, 15000);
        },

        // Masuk ke mode fullscreen
        enterFullscreen: function () {
            try {
                var fullscreenBtn = $('[data-widget="fullscreen"]');
                if (fullscreenBtn.length && !this.isFullscreen()) {
                    // Gunakan click() langsung untuk user gesture
                    fullscreenBtn[0].click();
                }
            } catch (error) {
                // Silent error handling
            }
        },

        // Keluar dari mode fullscreen
        exitFullscreen: function () {
            try {
                if (this.isFullscreen()) {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                }
            } catch (error) {
                // Silent error handling
            }
        },

        // Bind semua event listeners
        bindEvents: function () {
            var self = this;

            // Event saat fullscreen berubah
            $(document).on(
                "webkitfullscreenchange mozfullscreenchange fullscreenchange MSFullscreenChange",
                function () {
                    try {
                        self.saveFullscreenState();
                    } catch (error) {
                        // Silent error handling
                    }
                }
            );

            // Event saat tombol fullscreen diklik
            $(document).on("click", '[data-widget="fullscreen"]', function () {
                setTimeout(function () {
                    self.saveFullscreenState();
                }, 150);
            });

            // Event saat form di-submit
            $(document).on("submit", "form", function () {
                self.saveFullscreenState();
            });

            // Event saat AJAX request dikirim
            $(document).ajaxSend(function () {
                self.saveFullscreenState();
            });

            // Event sebelum halaman unload (refresh, close tab, dll)
            $(window).on("beforeunload", function () {
                self.saveFullscreenState();
            });

            // Event saat visibility berubah (tab switch)
            $(document).on("visibilitychange", function () {
                if (!document.hidden) {
                    setTimeout(function () {
                        self.saveFullscreenState();
                    }, 100);
                }
            });

            // Event saat focus kembali ke window
            $(window).on("focus", function () {
                setTimeout(function () {
                    self.saveFullscreenState();
                }, 100);
            });

            // Event untuk keyboard shortcuts
            $(document).on("keydown", function (e) {
                try {
                    // Deteksi F11
                    if (e.keyCode === 122) {
                        setTimeout(function () {
                            self.saveFullscreenState();
                        }, 200);
                    }

                    // Deteksi Ctrl+R atau F5
                    if ((e.ctrlKey && e.keyCode === 82) || e.keyCode === 116) {
                        self.saveFullscreenState();
                    }
                } catch (error) {
                    // Silent error handling
                }
            });

            // Event untuk mouse events yang mungkin mempengaruhi fullscreen
            $(document).on("mousedown mouseup click", function (e) {
                // Jika klik di area yang mungkin mempengaruhi fullscreen
                if ($(e.target).closest('[data-widget="fullscreen"]').length) {
                    setTimeout(function () {
                        self.saveFullscreenState();
                    }, 100);
                }
            });

            // Event untuk page load completion
            $(window).on("load", function () {
                setTimeout(function () {
                    self.saveFullscreenState();
                }, 500);
            });
        },
    };

    // Inisialisasi saat document ready
    $(document).ready(function () {
        MonitorFullscreen.init();
    });

    // Fallback jika document ready sudah terlewat
    if (
        document.readyState === "complete" ||
        document.readyState === "interactive"
    ) {
        MonitorFullscreen.init();
    }

    // Expose ke global scope untuk debugging
    window.MonitorFullscreen = MonitorFullscreen;

    // Fungsi untuk reset prompt (bisa dipanggil dari console)
    window.resetFullscreenPrompt = function () {
        localStorage.removeItem("fullscreenPromptDismissed");
        console.log(
            "Fullscreen prompt reset. Prompt akan muncul lagi saat refresh."
        );
    };
})(jQuery);
