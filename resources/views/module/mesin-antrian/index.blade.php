@extends('layouts.monitor')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
            </div>
        </div>
        <div class="content">
            <div class="row justify-content-center mt-4">
                <!-- BPJS Card -->
                <div class="col-md-5 mb-4">
                    <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="text-success mb-4">Daftar Antrian BPJS</h5>
                        <button class="btn btn-success btn-lg mt-auto"
                                style="font-size: 1.5rem; padding: 15px;"
                                data-toggle="modal" data-target="#bpjsModal">
                        Daftar Antrian BPJS
                        </button>
                    </div>
                    </div>
                </div>

                <!-- Non-BPJS Card -->
                <div class="col-md-5 mb-4">
                    <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="text-primary mb-4">Daftar Antrian Non-BPJS</h5>
                        <button class="btn btn-primary btn-lg mt-auto"
                                style="font-size: 1.5rem; padding: 15px;"
                                data-toggle="modal" data-target="#nonBpjsModal">
                        Daftar Antrian Non-BPJS
                        </button>
                    </div>
                    </div>
                </div>

                <!-- Pasien Baru Card (Full Width) -->
                <div class="col-md-10">
                    <div class="card text-center">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Daftar Pasien Baru</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-outline-info btn-lg"
                                style="font-size: 1.5rem; padding: 15px;"
                                data-toggle="modal" data-target="#ddaftarModal">
                        Daftar Pasien Baru
                        </button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('module.mesin-antrian.components.modal-bpjs')

    @include('module.mesin-antrian.components.modal-non-bpjs')

    @include('module.mesin-antrian.components.modal-pasien-baru')

    @include('module.mesin-antrian.components.javascript')

@endsection
