@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Setting harga jual</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">

                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form id="addFormsetharga" action="{{ route('setharga.store') }}" method="POST">
                                @csrf
                                <div class="card-header">
                                    <h3 class="card-title">Setting Harga Jual</h3>
                                    <div class="card-tools">

                                        @php
                                            use App\Models\WebSetting;

                                            $setting = WebSetting::first();
                                            $isGudangUtamaActive = $setting->is_gudangutama_active ?? true;
                                        @endphp

                                        @if($isGudangUtamaActive)
                                            <a id="btnSinkronHarga" class="btn btn-info">
                                                <i class="fas fa-file-upload"></i> Sinkron
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#singkrondabarModal">
                                                <i class="fas fa-file-upload"></i> Sinkron
                                            </button>
                                        @endif
                                        <!-- Tombol Sinkron (Memunculkan Modal) -->
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{-- HARGA JUAL 1 --}}
                                            <div class="form-group">
                                                <label>Setup % Untuk Auto Import dari Faktur ke Harga Jual Netto :</label>
                                            </div>
                                            <div class="form-group row align-items-center">
                                                <label for="harga_jual_1" class="col-md-3 col-form-label">Setting Harga Jual 1 :</label>

                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" id="harga_jual_1" name="harga_jual_1" placeholder="Harga Jual 1" value="{{ $setharga ? $setharga->harga_jual_1 : '' }}" readonly>
                                                </div>

                                                <div class="col-md-3">
                                                    <span class="form-text">BPJS</span>
                                                </div>
                                            </div>
                                            {{-- HARGA JUAL 2 --}}
                                            <div class="form-group row align-items-center">
                                                <label for="harga_jual_2" class="col-md-3 col-form-label">Setting Harga Jual 2 :</label>

                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" id="harga_jual_2" name="harga_jual_2" placeholder="Harga Jual 2" value="{{ $setharga ? $setharga->harga_jual_2 : '' }}" readonly>
                                                </div>

                                                <div class="col-md-3">
                                                    <span class="form-text">Asuransi</span>
                                                </div>
                                            </div>
                                            {{-- HARGA JUAL 3 --}}
                                            <div class="form-group row align-items-center">
                                                <label for="harga_jual_3" class="col-md-3 col-form-label">Setting Harga Jual 3 :</label>

                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" id="harga_jual_3" name="harga_jual_3" placeholder="Harga Jual 3" value="{{ $setharga ? $setharga->harga_jual_3 : '' }}" readonly>
                                                </div>

                                                <div class="col-md-3">
                                                    <span class="form-text">Pasien Umum</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Mode Embalase (Untuk Resep)</label>
                                            </div>
                                            <div class="form-group row align-items-center">
                                                <label for="" class="col-md-4 col-form-label">Embalase Kelipatan : 1 Poin = </label>

                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" id="embalase_poin" name="embalase_poin" placeholder="Masukan Nominal Embalase" value="{{ $setharga ? $setharga->embalase_poin : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer form-group row align-items-center">
                                    <div class="col-md-6">
                                        <small class="text-muted mb-0">Terakhir diperbarui {{ $lastUpdated }}</small>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    <!-- /.content -->
</div>

<!-- Modal Singkron -->
<div class="modal fade" id="singkrondabarModal" tabindex="-1" role="dialog" aria-labelledby="singkrondabarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="singkrondabarModalLabel">Sinkron Setting Harga Jual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="singkronDabar">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12" id="containerExternal">
                            <label for="external_database">Pilih Tujuan</label>
                            <select class="form-control select2bs4" style="width: 100%;" id="external_database" name="external_database">
                                <option value="" disabled selected>Pilih Nama Tujuan</option>
                                @foreach ($singkron as $datasingkron)
                                    <option value="{{ $datasingkron->id }}">{{ $datasingkron->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <div id="loadingContainer" class="progress" style="display: none; height: 25px;">
                                <div id="loadingBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 0%;">
                                    <span id="loadingText" style="font-weight: bold; color: white; display: block; text-align: center;">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Tambah</button> <!-- Submit button -->
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btnSinkronHarga').addEventListener('click', function () {
    Swal.fire({
        title: 'Yakin ingin sinkron data harga?',
        text: 'Proses ini akan memperbarui data harga klinik.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Sinkronkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect ke route
            window.location.href = "{{ route('setharga_klinik.singkron') }}";
        }
    });
});
</script>


<script>
    $(document).ready(function () {
        function resetModal() {
            $("#loadingContainer").show();         // Sembunyikan loading bar
            $("#loadingBar").css("width", "0%");   // Reset progress ke 0%
            $("#loadingText").text("0%");          // Reset teks ke 0%
            $(".btn-info").prop("disabled", false).text("Tambah"); // Aktifkan kembali tombol

            $("#singkronDabar")[0].reset(); // Reset form input
        }

        // Reset progress bar saat modal dibuka
        $("#singkrondabarModal").on("show.bs.modal", function () {
            $("#loadingContainer").hide(); // Tampilkan loading saat modal dibuka
            $("#loadingBar").css("width", "0%"); // Reset ke 0%
            $("#loadingText").text("0%"); // Reset teks ke 0%
        });

        $("#singkronDabar").submit(function (e) {
            e.preventDefault(); // Mencegah submit form default

            const id_db = $("#external_database").val();

            // Tampilkan loading bar dan reset ke 0%
            $("#loadingContainer").show();
            $("#containerExternal").hide();
            $("#loadingBar").css("width", "0%");
            $("#loadingText").text("0%");
            $(".btn-info").prop("disabled", true).text("Menambahkan...");

            // Simulasi animasi progress dari 0% hingga 100% sebelum AJAX dijalankan
            let progress = 0;
            let interval = setInterval(function () {
                progress += 10; // Tambah 10% setiap 300ms
                $("#loadingBar").css("width", progress + "%");
                $("#loadingText").text(progress + "%"); // Update teks

                if (progress >= 100) {
                    clearInterval(interval); // Hentikan animasi
                    $("#loadingText").text("Complete"); // Ubah teks jadi "Complete"

                    // ** Setelah 100%, jalankan AJAX request **
                    $.ajax({
                        url: "{{ route('setharga.singkron', ['id' => '__ID__']) }}".replace('__ID__', id_db),
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                $('#singkrondabarModal').modal('hide');
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
                            }).then(() => {
                                // **🔹 Reset modal ke kondisi awal setelah OK ditekan**
                                resetModal();
                            });

                        }
                    });
                }
            }, 300); // Setiap 300ms, naik 10%
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Mask untuk persen (maksimal 3 digit)
        Inputmask({
            alias: "numeric",
            suffix: '%',
            min: 0,
            max: 100,
            rightAlign: false,
            placeholder: "",
            allowMinus: false,
            removeMaskOnSubmit: true
        }).mask("#harga_jual_1, #harga_jual_2, #harga_jual_3, #harga_jual_4, #harga_jual_5");

        // Mask untuk rupiah
        Inputmask({
            alias: "numeric",
            groupSeparator: ".",
            radixPoint: ",",
            autoGroup: true,
            digitsOptional: true,
            digits: 0,
            placeholder: "",
            prefix: "Rp ",
            rightAlign: false,
            removeMaskOnSubmit: true
        }).mask("#embalase_poin");
    });

    $('#addFormsetharga').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
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
                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    let errorList = '';

                    // Hapus class 'is-invalid' dari semua input dulu (optional, biar bersih)
                    $('#addFormpembelian').find('.is-invalid').removeClass('is-invalid');

                    Object.entries(xhr.responseJSON.errors).forEach(([key, value]) => {
                        errorList += `- ${value[0]}<br>`;
                        $(`#${key}`).addClass('is-invalid'); // Tambahkan class error ke input
                    });

                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi Gagal!',
                        html: `Terdapat beberapa input yang belum valid:<br><br>${errorList}`,
                    });
                } else {
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
            }
        });
    });
</script>
@endsection
