@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Detail Request Obat</h1>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Informasi Request</h3>
                                <div class="card-tools">
                                    <a href="{{ route('request.get') }}" class="btn btn-sm btn-default">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="30%">Kode Request</th>
                                                <td>: {{ $detailData['id'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Klinik</th>
                                                <td>: {{ $detailData['klinik'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal</th>
                                                <td>: {{ $detailData['tanggal'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>: 
                                                    @if($detailData['status'] == 'pending')
                                                        <span class="badge badge-warning">Menunggu</span>
                                                    @elseif($detailData['status'] == 'approved')
                                                        <span class="badge badge-success">Disetujui</span>
                                                    @elseif($detailData['status'] == 'rejected')
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Detail Item</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th class="text-center" width="20%">Kode Obat</th>
                                            <th>Nama Obat</th>
                                            <th class="text-center" width="15%">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($detailData['items'] as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center">{{ $item['kode'] }}</td>
                                            <td>{{ $item['nama'] }}</td>
                                            <td class="text-center">{{ $item['jumlah'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                @if($detailData['status'] == 'pending')
                                <button type="button" class="btn btn-success approve-btn" data-id="{{ $detailData['id'] }}">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                                <button type="button" class="btn btn-danger reject-btn" data-id="{{ $detailData['id'] }}">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Approve button click
            $('.approve-btn').click(function() {
                const id = $(this).data('id');
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin menyetujui request ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Setujui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('request.approve') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        showConfirmButton: true
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat memproses permintaan'
                                });
                            }
                        });
                    }
                });
            });
            
            // Reject button click
            $('.reject-btn').click(function() {
                const id = $(this).data('id');
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin menolak request ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('request.reject') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        showConfirmButton: true
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat memproses permintaan'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
    @endpush
@endsection