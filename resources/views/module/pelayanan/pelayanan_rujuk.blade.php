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
                            <form action="{{ route('sopelayana.add') }}" method="POST">
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

                                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                              </div>

                                              {{-- setp ke 2 --}}
                                              <div id="Obyektif-part" class="content" role="tabpanel" aria-labelledby="Obyektif-part-trigger">


                                                <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                              </div>

                                              {{-- step ke 3 --}}
                                              <div id="htt-part" class="content" role="tabpanel" aria-labelledby="htt-part-trigger">

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


@endsection
