@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pelayanan rujuk</h1>
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
                            <form id="addFormagama" action="{{ route('pelayana_rujuk.add') }}" method="POST">
                                @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="nomor_rm">Nomor RM</label>
                                                <input type="text" class="form-control" id="nomor_rm" name="nomor_rm" value="{{$pelayanan->nomor_rm}}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="nama">Nama</label>
                                                <input type="text" class="form-control" id="nama" name="nama" value="{{$pelayanan->pasien->nama}}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="no_rawat">Nomor Rawat</label>
                                                <input type="text" class="form-control" id="no_rawat" name="no_rawat" value="{{$pelayanan->nomor_register}}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="sex">Jenis Kelamin</label>
                                                <input type="text" class="form-control" id="sex" name="sex" value="{{$pelayanan->pasien->kelamin->nama}}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="penjamin">Penjamin</label>
                                                <input type="text" class="form-control" id="penjamin" name="penjamin" value="{{$pelayanan->pendaftaran->penjamin->nama}}" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                                <input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{$pelayanan->pasien->tanggal_lahir}}" readonly>
                                                <input type="hidden" class="form-control" id="no_bpjs" name="no_bpjs" value="{{$pelayanan->pasien->no_bpjs}}" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="umur">Umur</label>
                                                <input type="text" class="form-control" id="umur" name="umur" value="{{$umur}}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                          <div class="bs-stepper">
                                            <div class="bs-stepper-header" role="tablist">
                                              <!-- your steps here -->
                                              <div class="step" data-target="#Subyektif-part">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="Subyektif-part" id="Subyektif-part-trigger">
                                                  <span class="bs-stepper-circle">1</span>
                                                </button>
                                              </div>
                                              <div class="line"></div>
                                              <div class="step" data-target="#Obyektif-part">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="Obyektif-part" id="Obyektif-part-trigger">
                                                  <span class="bs-stepper-circle">2</span>
                                                </button>
                                              </div>
                                              <div class="line"></div>
                                              <div class="step" data-target="#htt-part">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="htt-part" id="htt-part-trigger">
                                                  <span class="bs-stepper-circle">3</span>
                                                </button>
                                              </div>
                                            </div>
                                            <div class="bs-stepper-content">

                                              <!-- your steps content here -->
                                              <div id="Subyektif-part" class="content" role="tabpanel" aria-labelledby="Subyektif-part-trigger">
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <label for="jenis_rujukan">Jenis Rujukan</label>
                                                        <select class="form-control select2bs4" id="jenis_rujukan" name="jenis_rujukan">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            <option value="sehat">Sehat</option>
                                                            <option value="sakit">Sakit</option>

                                                        </select>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="tujuan_rujukan">Tujukan Rujukan</label>
                                                        <select class="form-control select2bs4" id="tujuan_rujukan" name="tujuan_rujukan">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            <option value="verti">vertikal</option>
                                                            <option value="horizontal">Horizontal</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                              </div>

                                              {{-- setp ke 2 --}}
                                              <div id="Obyektif-part" class="content" role="tabpanel" aria-labelledby="Obyektif-part-trigger">
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <label for="opsi_rujukan">Opsi Rujukan</label>
                                                        <select class="form-control select2bs4" id="opsi_rujukan" name="opsi_rujukan">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                              </div>

                                              {{-- step ke 3 --}}
                                              <div id="htt-part" class="content" role="tabpanel" aria-labelledby="htt-part-trigger">
                                                <div id="input_rujukan_khusus" style="display:none;">
                                                    <div class="form-group row">
                                                        <div class="col-md-6">
                                                            <label for="igd_rujukan_khusus">Tujuan</label>
                                                            <select id="igd_rujukan_khusus" name="igd_rujukan_khusus" class="form-control select2bs4">
                                                                <option value="" disabled selected>-- Pilih Tujuan --</option>
                                                                <option value="IGD">IGD</option>
                                                                <option value="HDL">HDL</option>
                                                                <option value="JIW">JIW</option>
                                                                <option value="KLT">KLT</option>
                                                                <option value="PAR">PAR</option>
                                                                <option value="KEM">KEM</option>
                                                                <option value="RAT">RAT</option>
                                                                <option value="HIV">HIV</option>
                                                                <option value="THA">THA</option>
                                                                <option value="HEM">HEM</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="subspesialis_khusus">Subspesialis Khusus</label>
                                                            <select id="subspesialis_khusus" name="subspesialis_khusus" class="form-control select2bs4" disabled>
                                                                <option value="" disabled selected>-- Pilih Subspesialis Khusus --</option>
                                                                @foreach ($subspesialis as $subspesialisdata)
                                                                    <option value="{{ $subspesialisdata->kode }}">{{ $subspesialisdata->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="tanggal_rujukan_khusus">Tanggal Rujukan</label>
                                                            <input type="date" id="tanggal_rujukan_khusus" name="tanggal_rujukan_khusus" class="form-control" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="tujuan_rujukan_khusus">Tujuan Rujukan</label>
                                                            <select id="tujuan_rujukan_khusus" name="tujuan_rujukan_khusus" class="form-control select2bs4">
                                                                <option value="" disabled selected>-- Pilih Tujuan Rujukan --</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mt-3">
                                                            <button type="button" class="btn btn-primary" id="cari_provider_khusus">
                                                                <i class="fas fa-search"></i> Cari Provider
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="input_spesialis" style="display:none;">
                                                    <div class="form-group row">
                                                        <!-- Checkbox untuk Aktifkan Pilihan Sarana -->
                                                        <div class="col-md-12 mb-3">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="aktifkan_sarana">
                                                                <label class="custom-control-label" for="aktifkan_sarana">Aktifkan Pilihan Sarana</label>
                                                            </div>
                                                        </div>

                                                        <!-- Sarana (hidden by default) -->
                                                        <div class="col-md-12" id="sarana_container" style="display:none;">
                                                            <label for="sarana">Sarana</label>
                                                            <select id="sarana" name="sarana" class="form-control select2bs4">
                                                                <option value="" disabled selected>-- Pilih Sarana --</option>
                                                                <option value="tidak ada">tidak ada </option>
                                                                @foreach ($sarana as $saranadata)
                                                                    <option value="{{ $saranadata->kode }}">{{ $saranadata->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="kategori_rujukan">Kategori Rujukan</label>
                                                            <select id="kategori_rujukan" name="kategori_rujukan" class="form-control select2bs4">
                                                            <option value="" disabled selected>-- Pilih Kategori --</option>
                                                                <option value="-1">Tanpa Alasan</option>
                                                                <option value="1">Time</option>
                                                                <option value="2">Age</option>
                                                                <option value="3">Complication</option>
                                                                <option value="4">Comorbidity</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="alasan_rujukan">Alasan Rujukan Spesialis</label>
                                                            <input type="text" id="alasan_rujukan" name="alasan_rujukan" class="form-control" placeholder="Masukkan alasan rujukan" disabled />
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="spesialis">Spesialis</label>
                                                            <select id="spesialis" name="spesialis" class="form-control select2bs4">
                                                            <option value="" disabled selected>-- Pilih Spesialis --</option>
                                                            @foreach ($spesialis as $spesialisdata)
                                                                    <option value="{{ $spesialisdata->kode }}">{{ $spesialisdata->nama }}</option>
                                                            @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="sub_spesialis">Sub Spesialis</label>
                                                            <select id="sub_spesialis" name="sub_spesialis" class="form-control select2bs4" disabled>
                                                            <option value="" disabled selected>-- Pilih Sub Spesialis --</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="tanggal_rujukan">Tanggal Rujukan</label>
                                                            <input type="date" id="tanggal_rujukan" name="tanggal_rujukan" class="form-control" />
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="tujuan_rujukan_spesialis">Tujuan Rujukan</label>
                                                            <select id="tujuan_rujukan_spesialis" name="tujuan_rujukan_spesialis" class="form-control select2bs4">
                                                            <option value="" disabled selected>-- Pilih Tujuan Rujukan --</option>
                                                            </select>
                                                        </div>

                                                        <!-- Tombol Cari Provider -->
                                                        <div class="col-md-12 mt-3">
                                                            <button type="button" class="btn btn-primary" id="cari_provider_spesialis">
                                                                <i class="fas fa-search"></i> Cari Provider
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>




                                                <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                              </div>
                                            </div>
                                          </div>
                                      <!-- /.card -->
                                    </div>
                                </div>
                            </div>
                            </form>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    <!-- /.content -->
</div>

<script>
$(document).ready(function () {

    function formatTanggal(rawTanggal) {
        if (!rawTanggal) return '';
        const parts = rawTanggal.split('-');
        return `${parts[2]}-${parts[1]}-${parts[0]}`; // YYYY-MM-DD → DD-MM-YYYY
    }

    function showWarning(msg = 'Harap lengkapi semua isian terlebih dahulu.') {
        Swal.fire({
            icon: 'warning',
            title: 'Data Belum Lengkap',
            text: msg
        });
    }

    function tampilkanHasil(response, targetSelectId) {
        const providers = response.data.list;
        const $select = $(targetSelectId);

        $select.empty().append('<option disabled selected>-- Pilih Tujuan Rujukan --</option>');

        providers.forEach(function (item) {
            const option = $('<option>', {
                value: item.kdppk,
                text: `${item.nmppk} (${item.kdppk})`,
                'data-info': JSON.stringify(item)
            });
            $select.append(option);
        });
    }

    // Tombol "Cari Provider Spesialis"
    $('#cari_provider_spesialis').on('click', function () {
        const spesialis = $('#sub_spesialis').val();
        const sarana = $('#sarana').val() || "0";
        const tanggal = formatTanggal($('#tanggal_rujukan').val());

        if (!spesialis || !sarana || !tanggal) {
            showWarning('Harap isi Sub Spesialis, Sarana, dan Tanggal Rujukan terlebih dahulu.');
            return;
        }

        const $btn = $(this);
        $.ajax({
            url: `/api/pcare/provide_rujuk/${spesialis}/${sarana}/${tanggal}`,
            type: 'GET',
            beforeSend: function () {
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mencari...');
            },
            success: function (response) {
                tampilkanHasil(response, '#tujuan_rujukan_spesialis');
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengambil Data',
                    text: 'Terjadi kesalahan saat mengambil data provider.'
                });
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fas fa-search"></i> Cari Provider');
            }
        });
    });

    // Tombol "Cari Provider Khusus"
    $('#cari_provider_khusus').on('click', function () {
        const spesialishusus = $('#igd_rujukan_khusus').val();
        const subspesialishusus = $('#subspesialis_khusus').val();
        const nobpjs = $('#no_bpjs').val() || "0";
        const tanggal = formatTanggal($('#tanggal_rujukan_khusus').val());

        if (!spesialishusus || !nobpjs || !tanggal) {
            showWarning('Harap isi Spesialis, No BPJS, dan Tanggal Rujukan terlebih dahulu.');
            console.log(`Spesialis: ${spesialishusus}, No BPJS: ${nobpjs}, Tanggal: ${tanggal}`);
            return;
        }

        let url = '';

        if (subspesialishusus) {
            url = `/api/pcare/provide_rujuk_husus_subspesialis/${subspesialishusus}/${spesialishusus}/${nobpjs}/${tanggal}`;
        } else {
            url = `/api/pcare/provide_rujuk_husus/${spesialishusus}/${nobpjs}/${tanggal}`;
        }

        const $btn = $(this);
        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function () {
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mencari...');
            },
            success: function (response) {
                tampilkanHasil(response, '#tujuan_rujukan_khusus');
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengambil Data',
                    text: 'Terjadi kesalahan saat mengambil data provider.'
                });
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fas fa-search"></i> Cari Provider');
            }
        });
    });


});
</script>



<script>
    $(document).ready(function() {
        $('#spesialis').on('change', function() {
            var kodeSpesialis = $(this).val();

            if (kodeSpesialis) {
                $.ajax({
                    url: '/api/get-subspesialis/' + kodeSpesialis,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#sub_spesialis').empty().append('<option selected disabled>-- Pilih Sub Spesialis --</option>');
                        $.each(data, function(key, value) {
                            $('#sub_spesialis').append('<option value="' + value.kode + '">' + value.nama + '</option>');
                        });
                        $('#sub_spesialis').prop('disabled', false);
                    }
                });
            } else {
                $('#sub_spesialis').empty().append('<option selected disabled>-- Pilih Sub Spesialis --</option>');
                $('#sub_spesialis').prop('disabled', true);
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
    // Step 1: update tujuan_rujukan saat jenis_rujukan berubah
    $('#jenis_rujukan').on('change', function() {
        const jenisValue = $(this).val();
        console.log('Jenis rujukan berubah:', jenisValue);

        const tujuanSelect = $('#tujuan_rujukan');
        tujuanSelect.empty().append('<option value="" disabled selected>-- Pilih --</option>');

        if (jenisValue === 'sehat') {
        tujuanSelect.append('<option value="develop" disabled selected>Develop</option>');
        } else if (jenisValue === 'sakit') {
        tujuanSelect.append('<option value="horizontal">Horizontal</option>');
        tujuanSelect.append('<option value="vertikal">Vertikal</option>');
        }

        tujuanSelect.trigger('change.select2');

        // Bersihkan opsi rujukan di step 2 tiap kali jenis_rujukan berubah
        $('#opsi_rujukan').empty().append('<option value="" disabled selected>-- Pilih --</option>').trigger('change.select2');
    });

    // Step 2: update opsi_rujukan saat tujuan_rujukan berubah
    $('#tujuan_rujukan').on('change', function() {
        const tujuanValue = $(this).val();
        console.log('Tujuan rujukan berubah:', tujuanValue);

        const opsiSelect = $('#opsi_rujukan');
        opsiSelect.empty().append('<option value="" disabled selected>-- Pilih --</option>');

        if (tujuanValue === 'vertikal') {
        opsiSelect.append('<option value="rujukan_khusus">Rujukan Khusus</option>');
        opsiSelect.append('<option value="spesialis">Spesialis</option>');
        } else if (tujuanValue === 'horizontal') {
        // Contoh opsi lain jika perlu, atau kosongkan
        opsiSelect.append('<option value="opsi_horizontal_1">Pelayanan Tindakan Non-Kapitasi</option>');
        opsiSelect.append('<option value="opsi_horizontal_2">Pelayanan Laboratorium</option>');
        opsiSelect.append('<option value="opsi_horizontal_3">Pelayanan Program</option>');
        opsiSelect.append('<option value="opsi_horizontal_4">Rujukan Kacamata</option>');
        }

        opsiSelect.trigger('change.select2');
    });

    $('#opsi_rujukan').on('change', function() {
        const val = $(this).val();
        console.log('Opsi rujukan dipilih:', val);

        // Hide semua form input dulu
        $('#input_rujukan_khusus, #input_spesialis').hide();

        // Tampilkan sesuai pilihan
        if(val === 'rujukan_khusus') {
        $('#input_rujukan_khusus').show();
        } else if(val === 'spesialis') {
        $('#input_spesialis').show();
        }
    });

    // Menangani perubahan pada dropdown tujuan rujukan khusus
    $('#igd_rujukan_khusus').on('change', function() {
        const tujuanValue = $(this).val();
        const subspesialisSelect = $('#subspesialis_khusus');

        // Cek apakah tujuan adalah THA atau HEM
        if (tujuanValue === 'THA' || tujuanValue === 'HEM') {
        // Enable subspesialis khusus
        subspesialisSelect.prop('disabled', false);
        } else {
        // Disable dan reset nilai
        subspesialisSelect.prop('disabled', true);
        subspesialisSelect.val('').trigger('change.select2');
        }
    });

    // Checkbox untuk aktifkan sarana
    $('#aktifkan_sarana').on('change', function() {
        if($(this).is(':checked')) {
        $('#sarana_container').show();
        } else {
        $('#sarana_container').hide();
        $('#sarana').val('').trigger('change.select2');
        }
    });

    // Kategori rujukan - disable/enable alasan rujukan
    $('#kategori_rujukan').on('change', function() {
        const kategoriValue = $(this).val();
        const alasanInput = $('#alasan_rujukan');

        if(!kategoriValue || kategoriValue === '' || kategoriValue === '-1') { // Belum dipilih atau Tanpa Alasan
        alasanInput.prop('disabled', true);
        alasanInput.val('');
        } else {
        alasanInput.prop('disabled', false);
        }
    });

    // Spesialis - enable/disable sub spesialis
    $('#spesialis').on('change', function() {
        const spesialisValue = $(this).val();
        const subSpesialisSelect = $('#sub_spesialis');

        if(spesialisValue && spesialisValue !== '') {
        subSpesialisSelect.prop('disabled', false);

        // Clear existing options except the first one
        subSpesialisSelect.find('option:not(:first)').remove();

        // Add sub-spesialis options based on selected spesialis
        if(spesialisValue === 'kardiologi') {
            subSpesialisSelect.append('<option value="kardiologi_intervensi">Kardiologi Intervensi</option>');
            subSpesialisSelect.append('<option value="kardiologi_anak">Kardiologi Anak</option>');
        } else if(spesialisValue === 'neurologi') {
            subSpesialisSelect.append('<option value="neurologi_anak">Neurologi Anak</option>');
            subSpesialisSelect.append('<option value="neurologi_stroke">Neurologi Stroke</option>');
        } else if(spesialisValue === 'ortopedi') {
            subSpesialisSelect.append('<option value="ortopedi_spine">Ortopedi Spine</option>');
            subSpesialisSelect.append('<option value="ortopedi_trauma">Ortopedi Trauma</option>');
        }

        subSpesialisSelect.trigger('change.select2');
        } else {
        subSpesialisSelect.prop('disabled', true);
        subSpesialisSelect.val('').trigger('change.select2');
        }
    });

    // Enable tujuan_rujukan_spesialis dropdown (removing disabled attribute)
    $('#tujuan_rujukan_spesialis').prop('disabled', false);
    });
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
    $('#addFormagama').on('submit', function(e) {
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
                            $('.modal-backdrop').remove(); // Hapus backdrop jika masih ada
                            var noRawat = $('#no_rawat').val();
                            window.location.href = '/rujukan/cetak/' + noRawat;
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

</script>

@endsection































