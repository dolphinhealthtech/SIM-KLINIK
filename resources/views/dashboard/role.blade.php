@extends('layouts.dashbord')


@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="#">
                                Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Dashboard v1
                        </li>
                    </ol>
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
                            <h3 class="card-title">Role Manajemen</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addroleModal">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="roletabel" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Role</th>
                                        <th class="text-center">Nama Permission</th>
                                        <th class="text-center" width="25%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($role as $roledata)
                                        <tr>
                                            <td>{{ $roledata->name }}</td>
                                            <td>
                                                @if ($roledata->permissions->isNotEmpty())
                                                    {{ $roledata->permissions->pluck('name')->implode(', ') }}
                                                @else
                                                    <span class="text-danger">Tidak ada permission</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-warning btn-sm edit-data-role" data-toggle="modal" data-id="{{ $roledata->id }}" data-nama-role="{{ $roledata->name }}" data-target="#editroleModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="#" class="btn btn-danger btn-sm delete-data-role" data-toggle="modal"data-id="{{ $roledata->id }}" data-nama-role="{{ $roledata->name }}" data-target="#deleteroleModal">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                                <a href="#" class="btn btn-info btn-sm give-role-permission" data-toggle="modal" data-id="{{ $roledata->id }}" data-nama-role="{{ $roledata->name }}" data-permissions='@json($roledata->permissions->pluck("name"))'data-target="#giveRolePermissionModal">
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

{{-- modal Add Role --}}
<div class="modal fade" id="addroleModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add New Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span >&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addFormrole" action="{{ route('role.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama Role</label>
                                <input type="text" class="form-control" id="rolename" name="rolename">
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
{{-- modal Edit Role --}}
<div class="modal fade" id="editroleModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span >&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editFormrole" action="{{ route('role.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="roleid_update" name="roleid_update">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Nama Role</label>
                                <input type="text" class="form-control" id="rolename_update" name="rolename_update">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Perbarui</button> <!-- Submit button -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- modal Delete Role --}}
<div class="modal fade" id="deleteroleModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog">
        <form id="deleteFormrole" action="{{ route('role.destroy') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Data Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span >&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="roleids" name="roleids">
                    <div id="deleteTextrole"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal XL untuk Give Role Permissions -->
<div class="modal fade" id="giveRolePermissionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Beri Permission ke Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="giveRolePermissionForm" action="{{ route('role.givePermission') }}"  method="POST">
                @csrf
                <input type="hidden" id="role_id" name="role_id">

                <div class="modal-body">
                    <div class="row">
                        <!-- Role yang Dipilih -->
                        <div class="col-md-12 mb-3">
                            <h5>Role: <span id="role_name_display"></span></h5>
                        </div>

                        <!-- Daftar Permission -->
                        <div class="col-md-12">
                            <h5>Permission</h5>
                            <div class="form-group">
                                @foreach ($permission as $permissiondata)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input permission-checkbox"
                                               name="permissions[]" value="{{ $permissiondata->name }}"
                                               id="perm_{{ $permissiondata->id }}">
                                        <label class="form-check-label" for="perm_{{ $permissiondata->id }}">
                                            {{ $permissiondata->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#roletabel").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [
                "csv",
                "excel",
                "pdf",
                "print",
            ]
        }).buttons().container().appendTo('#roletabel_wrapper .col-md-6:eq(0)');
    });

    $('#addFormrole').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#addroleModal').modal('hide');
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

    $(document).on('click', '.edit-data-role', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama-role');

        $('#roleid_update').val(id);
        $('#rolename_update').val(nama);
    });

    $('#editFormrole').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');

        $.ajax({
            url: url,
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editroleModal').modal('hide');
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

    $(document).on('click', '.delete-data-role', function() {
        let id = $(this).data('id');
        let name = $(this).data('nama-role');

        $('#roleids').val(id);
        $('#deleteTextrole').html(`<span>Apa Anda yakin ingin menghapus data Permission <b>${name}</b>?</span>`);
    });

    $('#deleteFormrole').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');

        $.ajax({
            url: url,
            type: "POST",
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#deleteroleModal').modal('hide');
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
                    text: 'Terjadi kesalahan saat menghapus role!',
                });
            }
        });
    });

    $('.give-role-permission').click(function () {
        var roleId = $(this).data('id');
        var roleName = $(this).data('nama-role');
        var rolePermissions = $(this).data('permissions'); // Data dari Blade

        console.log("Raw rolePermissions:", rolePermissions);

        // Pastikan rolePermissions adalah array
        if (!Array.isArray(rolePermissions)) {
            try {
                rolePermissions = JSON.parse(rolePermissions);
            } catch (e) {
                console.error("Error parsing JSON:", e);
                rolePermissions = [];
            }
        }

        console.log("Parsed rolePermissions:", rolePermissions);

        // Reset semua checkbox sebelum menyesuaikan dengan role
        $('.permission-checkbox').prop('checked', false);

        // Ceklis sesuai permission yang sudah dimiliki role
        rolePermissions.forEach(function (perm) {
            var checkbox = $('input[name="permissions[]"][value="' + perm + '"]');

            console.log("Mencari checkbox dengan value:", perm, "Ditemukan:", checkbox.length > 0);

            checkbox.prop('checked', true);
        });

        // Set role_id ke modal
        $('#role_id').val(roleId);
        $('#role_name_display').text(roleName);
    });

    $('#giveRolePermissionForm').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');

        $.ajax({
            url: url,
            type: "POST",
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#giveRolePermissionModal').modal('hide');
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
                    text: 'Terjadi kesalahan saat menghapus role!',
                });
            }
        });
    });

  </script>
@endsection
