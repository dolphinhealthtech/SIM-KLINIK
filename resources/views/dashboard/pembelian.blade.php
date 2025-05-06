@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pembelian Obat / Alkes</h1>
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
                                <form id="addFormdabar" action="{{ route('pembelian.add') }}" method="POST">
                                    @csrf
                                    <div class="bs-stepper">
                                        <div class="bs-stepper-header" role="tablist">
                                            <!-- your steps here -->
                                            <div class="step" data-target="#data-awal">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="data-awal" id="data-awal-trigger">
                                                    <span class="bs-stepper-circle">1</span>
                                                    <span class="bs-stepper-label">Data Awal Pembelian</span>
                                                </button>
                                            </div>

                                            <div class="line"></div>

                                            <div class="step" data-target="#data-pembelian">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="data-pembelian" id="data-pembelian-trigger">
                                                    <span class="bs-stepper-circle">2</span>
                                                    <span class="bs-stepper-label">Data Pembelian</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="bs-stepper-content">
                                            <!-- your steps content here -->
                                            <div id="data-awal" class="content" role="tabpanel" aria-labelledby="data-awal-trigger">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="nomor_faktur">No Faktur</label>
                                                            {{-- JANGAN LUPA SCRIPT --}}
                                                            <input type="text" class="form-control" id="nomor_faktur" name="nomor_faktur" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="d-flex align-items-center">
                                                                <label for="supplierCheck" class="mr-2 mb-0">Supplier</label>
                                                                <input type="checkbox" id="supplierCheck" name="supplierCheck" onclick="toggle_supplier()" style="margin-left: 5px;">
                                                            </div>
                                                            <div class="d-flex align-items-center mt-2">
                                                                <div id="supplier_select_wrapper" style="width: 100%;">
                                                                    <select class="form-control select2bs4 mt-2" style="width: 100%;" id="supplier_select" name="supplier_select">
                                                                        <option value="" disabled selected>Pilih Supplier</option>
                                                                        @foreach ($supplier as $supplierData)
                                                                            <option value="{{ $supplierData->nama }}">{{ $supplierData->nama }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <input type="text" class="form-control" id="supplier_input" name="supplier_input" placeholder="Masukan nama perusahaan supplier" style="display: none;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="d-flex align-items-center">
                                                                <label for="no_po_spCheck" class="mr-2 mb-0">No. PO / SP</label>
                                                                <input type="checkbox" id="no_po_spCheck" name="no_po_spCheck" onclick="toggle_no_po_sp()" style="margin-left: 5px;">
                                                            </div>
                                                            <div class="d-flex align-items-center mt-2">
                                                                <input type="text" class="form-control" id="no_po_sp" name="no_po_sp" placeholder="Masukan nomor PO / SP">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="no_faktur_supplier">No. Faktur Supplier</label>
                                                            <input type="text" class="form-control" id="no_faktur_supplier" name="no_faktur_supplier">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="d-flex align-items-center">
                                                                <label for="tanggal_terima_barangCheck" class="mr-2 mb-0">Tanggal Terima Barang</label>
                                                                <input type="checkbox" id="tanggal_terima_barangCheck" name="tanggal_terima_barangCheck" onclick="toggle_tanggal_terima_barang()" style="margin-left: 5px;">
                                                            </div>
                                                            <div class="d-flex align-items-center mt-2">
                                                                <input type="date" class="form-control" id="tanggal_terima_barang" name="tanggal_terima_barang">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tanggal_faktur">Tanggal Faktur</label>
                                                            <input type="date" class="form-control" id="tanggal_faktur" name="tanggal_faktur">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tanggal_jatuh_tempo" class="mr-2 mb-0">Tanggal Jatuh Tempo Pembayaran</label>
                                                            <div class="d-flex align-items-center mt-2">
                                                                <div class="input-group">
                                                                    <!-- Tombol untuk Date Range Picker -->
                                                                    <div class="input-group-prepend">
                                                                        <button type="button" class="btn btn-default" id="range_tanggal_jatuh_tempo" style="font-size: 0.9rem;">
                                                                            <i class="far fa-calendar-alt"></i> Date
                                                                            <i class="fas fa-caret-down"></i>
                                                                        </button>
                                                                    </div>

                                                                    <!-- Input untuk menampilkan rentang tanggal yang disabled, menempel pada tombol -->
                                                                    <input type="text" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" readonly style="font-size: 0.9rem;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="pajak_ppn">Pajak / PPN</label>
                                                            <input type="text" class="form-control" id="pajak_ppn" name="pajak_ppn">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="metode_hna">Metode HNA</label>
                                                            <select class="form-control select2bs4" style="width: 100%;" id="metode_hna" name="metode_hna">
                                                                <option value="" disabled selected>Pilih Supplier</option>
                                                                <option value="1">Tanpa PPN Dan Diskon</option>
                                                                <option value="2">Dengan PPN</option>
                                                                <option value="3">Dengan Diskon</option>
                                                                <option value="4">Dengan PPN Dan Diskon</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                            </div>
                                            <div id="data-pembelian" class="content" role="tabpanel" aria-labelledby="data-pembelian-trigger">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">File input</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="exampleInputFile"/>
                                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Upload</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                                <button type="submit" class="btn btn-primary"> Submit </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    <!-- /.content -->
</div>


{{-- SCRIPT GLOBAL --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            window.stepper = new Stepper(document.querySelector(".bs-stepper"));
        });
    </script>

{{-- SCRIPT DATA AWAL PEMBELIAN --}}

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Event listener untuk input tanggal_terima_barang
            document.getElementById('tanggal_terima_barang').addEventListener('click', function() {
                this.showPicker();
            });

            // Event listener untuk input tanggal_faktur
            document.getElementById('tanggal_faktur').addEventListener('click', function() {
                this.showPicker();
            });

            $('#range_tanggal_jatuh_tempo').daterangepicker(
                {
                    ranges   : {
                        'Today'       : [moment(), moment()],
                        'Tomorrow'    : [moment().add(1, 'days'), moment().add(1, 'days')],
                        'Next 7 Days' : [moment(), moment().add(6, 'days')],
                        'Next 30 Days': [moment(), moment().add(29, 'days')],
                        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                        'Next Month'  : [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')]
                    },
                    startDate: moment(),  // Set awal ke "Today"
                    endDate  : moment(),  // Set akhir ke "Today"
                    locale: {
                        format: 'D MMMM, YYYY'  // Format tanggal yang diinginkan
                    },
                    autoUpdateInput: false // Agar input tidak otomatis diperbarui
                },
                function (start, end, label) {
                    // Set nilai input dengan rentang tanggal yang dipilih
                    $('#tanggal_jatuh_tempo').val(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
                }
            );

            // Set nilai awal untuk input tanggal saat halaman dimuat (opsional)
            $('#tanggal_jatuh_tempo').val(
                moment().format('D MMMM, YYYY') + ' - ' + moment().format('D MMMM, YYYY')
            );

            const input = document.getElementById('pajak_ppn');
            const im = new Inputmask("percentage", {
                suffix: '%',
                rightAlign: false,
                min: 0,
                max: 100
            });
            im.mask(input);

        });

        document.addEventListener("DOMContentLoaded", () => {
            toggle_tanggal_terima_barang();
        });

        function toggle_supplier() {
            const isChecked = document.getElementById("supplierCheck").checked;
            const supplierSelectWrapper = document.getElementById("supplier_select_wrapper");
            const supplierInput = document.getElementById("supplier_input");

            if (isChecked) {
                $('#supplier_select').select2('destroy');
                supplierSelectWrapper.style.visibility = "hidden";
                supplierSelectWrapper.style.position = "absolute";
                supplierInput.style.display = "block";
            } else {
                supplierInput.value = "";
                supplierInput.style.display = "none";
                supplierSelectWrapper.style.visibility = "visible";
                supplierSelectWrapper.style.position = "relative";

                $('#supplier_select').select2({
                    theme: "bootstrap4",
                    dropdownParent: $('#supplier_select').parent()
                });
            }
        }

        function toggle_no_po_sp() {
            const checkbox = document.getElementById("no_po_spCheck");
            const input = document.getElementById("no_po_sp");

            if (checkbox.checked) {
                input.value = "KONSINYASI";
                input.setAttribute("readonly", true);
            } else {
                input.value = "";
                input.removeAttribute("readonly");
            }
        }

        function toggle_tanggal_terima_barang() {
            const checkbox = document.getElementById("tanggal_terima_barangCheck");
            const input = document.getElementById("tanggal_terima_barang");

            // Jika checkbox tidak dicentang, set input menjadi readonly dan set tanggal hari ini
            if (!checkbox.checked) {
                const today = new Date();
                const dd = String(today.getDate()).padStart(2, '0');
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const yyyy = today.getFullYear();

                const formattedDate = yyyy + '-' + mm + '-' + dd;  // Format: yyyy-mm-dd

                input.setAttribute("readonly", true);
                input.value = formattedDate;
                input.blur();
            } else {
                input.removeAttribute("readonly");
                input.value = '';
            }
        }

    </script>
@endsection
