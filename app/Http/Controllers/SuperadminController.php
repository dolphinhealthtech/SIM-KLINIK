<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperadminController extends Controller
{
    public function rolecreate()
    {
        $title = "Role Manajemen";

        $role = Role::with('permissions')->get();

        return view('dashboard.role', compact('title','role'));
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


    public function permissioncreate()
    {
        $title = "Permission Manajemen";

        $permission = Permission::all();

        return view('dashboard.permission', compact('title','permission'));
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
}
