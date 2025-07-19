@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        {{-- <h1 class="m-0">Dashboard</h1> --}}
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Riwayat Aktivitas User</h3>
                                <div class="card-tools">
                                    <span class="badge badge-primary">{{ $logs->count() }} aktivitas</span>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0" style="max-height: 500px;">
                                <table id="alergitabel" class="table table-hover text-nowrap table-bordered table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>User</th>
                                            <th>Aktivitas</th>
                                            <th>Method</th>
                                            <th>URL</th>
                                            <th>IP</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs as $log)
                                            <tr>
                                                <td>{{ $log->user->name ?? 'Guest' }}</td>
                                                <td>{{ $log->activity }}</td>
                                                <td><span class="badge badge-{{ $log->method === 'POST' ? 'success' : ($log->method === 'GET' ? 'primary' : 'warning') }}">
                                                    {{ $log->method }}
                                                </span></td>
                                                <td><small>{{ Str::limit($log->url, 50) }}</small></td>
                                                <td>{{ $log->ip_address }}</td>
                                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">Belum ada aktivitas.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </section>
        <!-- /.content -->
    </div>

<!-- SCRIPT SECTION - DIPERBAIKI -->
<script>
    $(document).ready(function() {
        $("#alergitabel").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
        }).buttons().container().appendTo('#alergitabel_wrapper .col-md-6:eq(0)');
    });
</script>

@endsection
