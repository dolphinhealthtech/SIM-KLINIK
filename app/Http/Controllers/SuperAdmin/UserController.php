<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;


class UserController extends Controller
{
    // Role
    public function rolecreate()
    {
        $title = "Role Manajemen";

        $role = Role::with('permissions')->get();
        $permission = Permission::all();

        return view('module.permission-role.role', compact('title', 'role', 'permission'));
    }

    public function rolestore(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'rolename' => 'required|string|unique:roles,name'
            ]);

            // Simpan data ke database
            $role = Role::create([
                'name' => $request->rolename
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Role berhasil ditambahkan!',
                'data' => $role
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan role!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function rolesupdate(Request $request)
    {
        $request->validate([
            'rolename_update' => 'required|string|unique:roles,name,' . $request->roleid_update,
        ]);

        $role = Role::find($request->roleid_update);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan!'
            ], 404);
        }

        $role->name = $request->rolename_update;
        $role->save();

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diperbarui!'
        ]);
    }

    public function rolesdestroy(Request $request)
    {
        $id = $request->input('roleids');
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan!'
            ], 404);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus!'
        ]);
    }

    // Permission
    public function givePermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::find($request->role_id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan'
            ], 404);
        }

        // Update permissions
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil diperbarui untuk role ini !'
        ]);
    }

    public function permissioncreate()
    {
        $title = "Permission Manajemen";

        $permission = Permission::all();

        return view('module.permission-role.permission', compact('title', 'permission'));
    }

    public function permissiontore(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'permissionname' => 'required|string|unique:permissions,name'
            ]);

            // Simpan data ke database
            $permission = Permission::create([
                'name' => $request->permissionname
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Role berhasil ditambahkan!',
                'data' => $permission
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan role!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function permissionupdate(Request $request)
    {
        $request->validate([
            'permissionname_update' => 'required|string|unique:permissions,name,' . $request->permissionid_update,
        ]);

        $permission = Permission::find($request->permissionid_update);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission tidak ditemukan!'
            ], 404);
        }

        $permission->name = $request->permissionname_update;
        $permission->save();

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diperbarui!'
        ]);
    }

    public function permissiondestroy(Request $request)
    {
        $id = $request->input('permissionids');
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission tidak ditemukan!'
            ], 404);
        }

        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus!'
        ]);
    }

    // User
    public function usercreate()
    {
        $title = "User Manajemen";

        $users = $users = User::with(['roles', 'permissions'])->get();
        $role = Role::all();
        return view('module.permission-role.user', compact('title', 'users', 'role'));
    }

    public function userstore(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'profile' => 'default.png',
                'is_active' => 0,
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Users berhasil Di Tambahkan !',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menonaktifkan Users!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function usernonaktif(Request $request)
    {
        try {
            // Validasi input
            $user = User::find($request->usersids);


            // Toggle status: jika 1 jadi 0, jika 0 jadi 1
            $user->is_active = $user->is_active ? 0 : 1;
            $user->email_verified_at = $user->is_active ? now() : null; // Set email_verified_at jika diaktifkan
            $user->save();

            // Menentukan pesan berdasarkan status user
            $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Users berhasil Di' . $status . '!',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menonaktifkan Users!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function usersgiverole(Request $request)
    {
        $request->validate([
            'userid_give' => 'required|exists:users,id',
            'role-give' => 'required|array',
            'role-give.*' => 'exists:roles,name'
        ]);

        try {
            // Validasi input
            $user = User::find($request->usersids);


            $user = User::findOrFail($request->userid_give);

            // Sinkronisasi peran pengguna
            $user->syncRoles($request->input('role-give'));
            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Users berhasil Di Berikan Role!',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memberikan Role Users!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function usersdestroy(Request $request)
    {
        $request->validate([
            'usersid_delete' => 'required|exists:users,id',
        ]);
        $user = User::find($request->usersid_delete);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Hapus semua role sebelum menghapus user
        $user->syncRoles([]);

        // Hapus user
        $user->delete();


        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus!'
        ]);
    }
}
