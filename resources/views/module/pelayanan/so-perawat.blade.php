@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard v1</li>
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
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="no_rawat">Nama</label>
                                                <input type="text" class="form-control" id="no_rawat" name="no_rawat" value="{{$pelayanan->nomor_rm}}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="no_rawat">Nomor RM</label>
                                                <input type="text" class="form-control" id="no_rawat" name="no_rawat" value="{{$pelayanan->pasien->nama}}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="no_rawat">Nomor Rawat</label>
                                                <input type="text" class="form-control" id="no_rawat" name="no_rawat" value="{{$pelayanan->nomor_register}}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="no_rawat">Jenis Kelamin</label>
                                                <input type="text" class="form-control" id="no_rawat" name="no_rawat" value="{{$pelayanan->pasien->kelamin->nama}}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="no_rawat">Penjamin</label>
                                                <input type="text" class="form-control" id="no_rawat" name="no_rawat" value="{{$pelayanan->pendaftaran->penjamin->nama}}" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="no_rawat">Tanggal Lahir</label>
                                                <input type="text" class="form-control" id="no_rawat" name="no_rawat" value="{{$pelayanan->pasien->tanggal_lahir}}" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="no_rawat">Umur</label>
                                                <input type="text" class="form-control" id="no_rawat" name="no_rawat" value="{{$umur}}" readonly>
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
                                                  <span class="bs-stepper-label">Subyektif</span>
                                                </button>
                                              </div>
                                              <div class="line"></div>
                                              <div class="step" data-target="#Obyektif-part">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="Obyektif-part" id="Obyektif-part-trigger">
                                                  <span class="bs-stepper-circle">2</span>
                                                  <span class="bs-stepper-label">Obyektif</span>
                                                </button>
                                              </div>
                                              <div class="line"></div>
                                              <div class="step" data-target="#htt-part">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="htt-part" id="htt-part-trigger">
                                                  <span class="bs-stepper-circle">3</span>
                                                  <span class="bs-stepper-label">Head To Toe</span>
                                                </button>
                                              </div>
                                            </div>
                                            <div class="bs-stepper-content">

                                              <!-- your steps content here -->
                                              <div id="Subyektif-part" class="content" role="tabpanel" aria-labelledby="Subyektif-part-trigger">
                                                <div class="form-group">
                                                    <label>Keluhan :</label>
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" id="penyakit" name="penyakit" placeholde="Masukan Keluhan">
                                                        </div>
                                                        <div class="col-md-5 d-flex align-items-center">
                                                            <label class="mr-3 mb-0">Sejak</label>
                                                            <input type="number" class="form-control mr-2" id="durasi" name="durasi" placeholder="Masukkan durasi">
                                                            <select class="form-control select2bs4" id="waktu" name="waktu">
                                                                <option value="" disabled selected>-- Pilih Hari --</option>
                                                                <option value="Hari">Hari</option>
                                                                <option value="Minggu">Minggu</option>
                                                                <option value="Bulan">Bulan</option>
                                                                <option value="Tahun">Tahun</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 d-flex justify-content-end">
                                                            <button type="button" class="btn btn-primary" onclick="addData()">Tambahkan</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" id="tableData" name="tableData" value="[]">

                                               <!-- Tabel -->
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <table class="table table-bordered" id="SubTabel">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 5%; text-align: center;">No</th>
                                                                    <th style="width: 80%">Subyektif</th>
                                                                    <th style="width: 15%; text-align: center;">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Data akan diisi secara dinamis -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary" onclick="stepper.next()">Next</button>
                                              </div>

                                              {{-- setp ke 2 --}}
                                              <div id="Obyektif-part" class="content" role="tabpanel" aria-labelledby="Obyektif-part-trigger">

                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label>Tensi (mmHg)</label>
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                <input type="text" class="form-control" id="sistol" name="sistol" onchange="updateTensi()">
                                                            </div>
                                                            <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                                <span>/</span> <!-- Menambahkan pemisah / -->
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="text" class="form-control" id="distol" name="distol" onchange="updateTensi()">
                                                            </div>
                                                            <input type="hidden" id="tensi" name="tensi">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="suhu">Suhu (°C)</label>
                                                        <input type="text" class="form-control" id="suhu" name="suhu" onchange="validateSuhu(this)">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="nadi">Nadi (/mnt)</label>
                                                        <input type="text" class="form-control" id="nadi" name="nadi" onchange="validateNadi()">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="rr">RR (/mnt)</label>
                                                        <input type="text" class="form-control" id="rr" name="rr" onchange="validateRR(this)">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="tinggi">Tinggi (Cm)</label>
                                                        <input type="text" class="form-control" id="tinggi" name="tinggi" onchange="validateTB()">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="berat">Berat (/Kg)</label>
                                                        <input type="text" class="form-control" id="berat" name="berat" onchange="validateTB()">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-3">
                                                        <label for="spo2">SpO2</label>
                                                        <input type="text" class="form-control" id="spo2" name="spo2" onchange="validateSpO2(this)">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="alergi">Alergi</label>
                                                        <input type="text" class="form-control" id="alergi" name="alergi">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="lingkar_perut">Lingkar Perut</label>
                                                        <input type="text" class="form-control" id="lingkar_perut" name="lingkar_perut">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Data BMI</label>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <input type="text" class="form-control" id="nilai_bmi" name="nilai_bmi" readonly>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control" id="status_bmi" name="status_bmi" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-3">
                                                        <label for="eye">EYE</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="eye" name="eye">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            {{-- @foreach ($eye as $data)
                                                                <option value="{{$data->skor}}">{{$data->nama}}</option>
                                                            @endforeach --}}
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="verbal">VERBAL</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="verbal" name="verbal">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            {{-- @foreach ($verbal as $data)
                                                                <option value="{{$data->skor}}">{{$data->nama}}</option>
                                                            @endforeach --}}
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="motorik">MOTORIK</label>
                                                        <select class="form-control select2bs4" style="width: 100%;" id="motorik" name="motorik">
                                                            <option value="" disabled selected>-- Pilih --</option>
                                                            {{-- @foreach ($motorik as $data)
                                                                <option value="{{$data->skor}}">{{$data->nama}}</option>
                                                            @endforeach --}}
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="sadar">Kesadaran</label>
                                                        <select class="form-control" style="width: 100%;" id="sadar" name="sadar" readonly>
                                                            <option value="" disabled selected> </option>
                                                            {{-- @foreach ($nilai as $data)
                                                                <option value="{{ $data->skor }}">{{ $data->nama }}</option>
                                                            @endforeach --}}
                                                        </select>
                                                    </div>
                                                </div>

                                                <button class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                                <button class="btn btn-primary" onclick="stepper.next()">Next</button>
                                              </div>

                                              {{-- step ke 3 --}}
                                              <div id="htt-part" class="content" role="tabpanel" aria-labelledby="htt-part-trigger">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <select class="form-control select2bs4" style="width: 100%;" id="htt_pemeriksaan" name="htt_pemeriksaan">
                                                                <option value="-" disabled selected> -- Silahkan Pilih -- </option>
                                                                {{-- @foreach ($htt_pemeriksaan as $data)
                                                                    <option value="{{ $data->nama_pemeriksaan }}" data-kode_pemeriksaan="{{ $data->kode_pemeriksaan }}">
                                                                        {{ $data->nama_pemeriksaan }}
                                                                    </option>
                                                                @endforeach --}}
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 d-flex align-items-center">
                                                            <label class="mb-0 text-center mr-3 ">Di</label>
                                                            <select class="form-control select2bs4" style="width: 100%;" id="htt_pemeriksaan_sub" name="htt_pemeriksaan_sub">
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3 d-flex align-items-center">
                                                            <label class="mb-0 text-center mr-3 ">Pada</label>
                                                            <input type="text" class="form-control" id="htt_pemeriksaan_detail" name="htt_pemeriksaan_detail">
                                                        </div>
                                                        <div class="col-md-2 d-flex justify-content-end">
                                                            <button type="button" class="btn btn-primary" onclick="addDataHtt_Text()">Tambahkan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <textarea class="form-control" id="summernote" name="summernote"></textarea>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                              </div>
                                            </div>
                                          </div>
                                      <!-- /.card -->
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    <!-- /.content -->
</div>

<script>
    let dataArray = []; // Array untuk menyimpan data sementara

    // Fungsi menambahkan data ke array dan tabel
    function addData() {
        const penyakit = document.getElementById("penyakit").value;
        const durasi = document.getElementById("durasi").value;
        const waktu = document.getElementById("waktu").value;

        // if (!penyakit || !durasi || !waktu) {
        //     alert("Semua kolom harus diisi!");
        //     return;
        // }

        // Cek jika semua kolom kosong
        if (!penyakit && !durasi && !waktu) {
            alert("Semua kolom harus diisi!");
            return;
        } else if (!penyakit) {
            alert("Kolom Penyakit harus diisi!");
            return;
        } else if (!durasi) {
            alert("Kolom Durasi harus diisi!");
            return;
        } else if (!waktu) {
            alert("Kolom Pilihan hari, bulan, tahun harus diisi!");
            return;
        }

        // Tambahkan data ke array
        dataArray.push({ penyakit, durasi, waktu });
        console.log("Data ditambahkan:", { penyakit, durasi, waktu });

        // Render ulang tabel
        renderTable();

        // Reset input fields setelah data ditambahkan
        $('#penyakit').val('');
        $('#durasi').val('');
        $("#waktu").val("").trigger("change");

        // Setelah data ditambahkan, ubah dataArray menjadi JSON dan simpan di input hidden
        const tableData = document.getElementById("tableData");
        tableData.value = JSON.stringify(dataArray); // Mengubah array menjadi string JSON
    }

    // Fungsi untuk merender tabel
    function renderTable() {
        const tableBody = document.getElementById("SubTabel").querySelector("tbody");
        tableBody.innerHTML = ""; // Kosongkan tabel
        console.log("Merender tabel...");

        dataArray.forEach((data, index) => {
            const row = `
                <tr>
                    <td style="text-align: center;">${index + 1}</td>
                    <td>${data.penyakit} Sejak ${data.durasi} ${data.waktu}</td>
                    <td style="text-align: center;">
                        <button class="btn btn-danger btn-sm" onclick="removeData(${index})">Hapus</button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });

        console.log("Tabel dirender, data array saat ini:", dataArray);
    }

    // Fungsi untuk menghapus data dari array
    function removeData(index) {
        console.log("Menghapus data index:", index);
        dataArray.splice(index, 1); // Hapus data berdasarkan index
        console.log("Data setelah dihapus:", dataArray);

        renderTable(); // Render ulang tabel

        // Setelah data dihapus, ubah dataArray menjadi JSON dan simpan di input hidden
        const tableData = document.getElementById("tableData");
        tableData.value = JSON.stringify(dataArray); // Mengubah array menjadi string JSON
    }
</script>
<script>
    // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })
</script>

<script>
        $(document).ready(function() {
            $("#banktabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#banktabel_wrapper .col-md-6:eq(0)');
        });

        $(document).on('click', '.delete-data-bank', function() {
            let id = $(this).data('id');
            let name = $(this).data('nama-bank');

            $('#bankid_delete').val(id);
            $('#deleteTextbank').html(
            `<span>Apa Anda yakin ingin menghapus data bank <b>${name}</b> ?</span>`);
        });

        $('#deleteFormbank').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deletebankModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat menghapus Goldar!',
                    });
                }
            });
        });
</script>
@endsection
