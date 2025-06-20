@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Users</h1>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar User</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addusersModal">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="userstabel" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama Role</th>
                                            <th class="text-center">Nama Role</th>
                                            <th class="text-center">Nama Permission</th>
                                            <th class="text-center" width="25%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $usersdata)
                                            <tr>
                                                <td>{{ $usersdata->name }}</td>
                                                <td>
                                                    @if ($usersdata->roles->isNotEmpty())
                                                        {{ $usersdata->roles->pluck('name')->implode(', ') }}
                                                    @else
                                                        <span class="text-danger">Tidak ada permission</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($usersdata->permissions->isNotEmpty())
                                                        {{ $usersdata->permissions->pluck('name')->implode(', ') }}
                                                    @else
                                                        <span class="text-danger">Tidak ada permission</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="#"
                                                        class="btn btn-sm non-data-users {{ $usersdata->is_active ? 'btn-warning' : 'btn-success' }}"
                                                        data-toggle="modal" data-id="{{ $usersdata->id }}"
                                                        data-nama-users="{{ $usersdata->name }}"
                                                        data-target="#nonaktifusersModal">

                                                        @if ($usersdata->is_active)
                                                            <i class="far fa-stop-circle"></i> Nonaktifkan
                                                        @else
                                                            <i class="far fa-play-circle"></i> Aktifkan
                                                        @endif
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm delete-data-users"
                                                        data-toggle="modal"data-id="{{ $usersdata->id }}"
                                                        data-nama-users="{{ $usersdata->name }}"
                                                        data-target="#deleteusersModal">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                    <a href="#" class="btn btn-info btn-sm give-data-role"
                                                        data-toggle="modal" data-id="{{ $usersdata->id }}"
                                                        data-roles="{{ json_encode($usersdata->roles->pluck('name')->toArray()) }}"
                                                        data-target="#giveroleModal">
                                                        <i class="fas fa-user-shield"></i> Give Role
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- modal Aktivasi User --}}
    <div class="modal fade" id="nonaktifusersModal" tabindex="-1" role="dialog" aria-labelledby="nonaktifusersModalLabel">
        <div class="modal-dialog">
            <form id="nonaktifusersFormrole" action="{{ route('user.aktiva') }}" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nonaktifusersModalLabel">Nonaktivasi Users</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="usersids" name="usersids">
                        <div id="nonaktifusersText"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger" id="buttonaktiva"></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- modal give Role --}}
    <div class="modal fade" id="giveroleModal" tabindex="-1" role="dialog" aria-labelledby="giveroleModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="giveroleModalLabel">Edit Data Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="giveFormrole" action="{{ route('user.giverole') }}" method="POST">
                        @csrf
                        <input type="hidden" id="userid_give" name="userid_give">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="form-control select2bs4" id="role-give" name="role-give[]"
                                        multiple="multiple" style="width: 100%;">
                                        @foreach ($role as $roledata)
                                            <option value="{{ $roledata->name }}">{{ $roledata->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Tambah</button> <!-- Submit button -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- modal delete Users --}}
    <div class="modal fade" id="deleteusersModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteusersModalLabel">
        <div class="modal-dialog">
            <form id="deleteFormusers" action="{{ route('user.destroy') }}" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteusersModalLabel">Hapus Users Permanen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="usersid_delete" name="usersid_delete">
                        <div id="userdeleteText"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

{{-- modal Add Role --}}
<div class="modal fade" id="addusersModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah  user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormusers" action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nama" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>User Name</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Nama User" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="email users" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password users" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Repeat Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="password confirmation users" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button> <!-- Submit button -->
            </div>
            </form>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {
            $("#userstabel").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    "csv",
                    "excel",
                    "pdf",
                    "print",
                ]
            }).buttons().container().appendTo('#userstabel_wrapper .col-md-6:eq(0)');
        });

        $(document).on('click', '.non-data-users', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-users');
            var isActive = $(this).hasClass('btn-warning'); // Cek apakah tombol dalam kondisi "Nonaktifkan"

            // Ubah teks dalam modal sesuai status user
            var actionText = isActive ? "Menonaktifkan" : "Mengaktifkan";

            $('#usersids').val(id);
            $('#buttonaktiva').html(`${actionText}`);
            $('#nonaktifusersText').html(`<span>Apa Anda yakin ingin ${actionText} user <b>${nama}</b>?</span>`);
        });

        $('#addFormusers').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addusersModal').modal('hide');
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
                    });
                }
            });
        });

        $('#nonaktifusersFormrole').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#nonaktifusersModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate role!',
                    });
                }
            });
        });

        $(document).on('click', '.give-data-role', function() {
            var id = $(this).data('id');
            var roles = $(this).data('roles'); // Ambil Role User (Array)

            $('#userid_give').val(id);
            $('#role-give').val(null).trigger('change');

            // Set opsi yang sesuai dengan role user
            $('#role-give').val(roles).trigger('change');

        });
        $('#giveFormrole').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#giveroleModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat mengupdate role!',
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-users', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama-users');


            $('#usersid_delete').val(id);
            $('#userdeleteText').html(`<span>Apa Anda yakin ingin Menghpaus user <b>${nama}</b>?</span>`);
        });

        $('#deleteFormusers').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#deleteusersModal').modal('hide');
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
                        text: 'Terjadi kesalahan saat Menghapus Users',
                    });
                }
            });
        });
    </script>
@endsection
