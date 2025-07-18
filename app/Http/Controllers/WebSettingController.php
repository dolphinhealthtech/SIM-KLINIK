<?php

namespace App\Http\Controllers;

use App\Models\Set_Bpjs;
use App\Models\Set_Sehat;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\external_database;

class WebSettingController extends Controller
{
    public function update(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'alamat' => 'required|string',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'kode_klinik' => 'required|numeric',
            ]);

            // Ambil pengaturan pertama, atau buat baru jika belum ada
            $setting = WebSetting::first() ?? new WebSetting();

            if ($request->hasFile('profile_image')) {
                // Hapus gambar lama jika ada dan file-nya masih ada
                if ($setting->profile_image) {
                    $oldImagePath = public_path('setting/' . $setting->profile_image);
                    if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Simpan gambar baru langsung ke folder public/setting
                $file = $request->file('profile_image');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('setting'), $filename);

                // Simpan nama file ke database
                $setting->profile_image = $filename;
            }

            // Simpan nama dan alamat
            $setting->nama = $validated['nama'];
            $setting->alamat = $validated['alamat'];
            $setting->kode_klinik = $validated['kode_klinik'];
            $setting->save();

            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateToggle(Request $request)
    {
        try {
            $validated = $request->validate([
                'toggle_type' => 'required|string|in:toggleBPJS,toggleSatusehat,toggleGudangutama,toggleTindakanAll',
                'value' => 'required|boolean'
            ]);

            $setting = WebSetting::first() ?? new WebSetting();

            // Map toggle type ke field database
            $fieldMap = [
                'toggleBPJS' => 'is_bpjs_active',
                'toggleSatusehat' => 'is_satusehat_active',
                'toggleGudangutama' => 'is_gudangutama_active',
                'toggleTindakanAll' => 'is_tindakan_active',
            ];

            $field = $fieldMap[$validated['toggle_type']];
            $oldValue = $setting->$field;
            $setting->$field = $validated['value'];
            $setting->save();

            // Jika is_gudangutama_active diubah dari 0 ke 1, set semua active di external_database ke 0
            if ($field === 'is_gudangutama_active' && $oldValue == 0 && $validated['value'] == 1) {
                external_database::query()->update(['active' => 0]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan berhasil diperbarui!'

            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }


    }

    public function getToggleStates()
    {
        try {
            $setting = WebSetting::first();

            return response()->json([
                'success' => true,
                'data' => [
                    'is_bpjs_active' => $setting->is_bpjs_active ?? true,
                    'is_satusehat_active' => $setting->is_satusehat_active ?? true,
                    'is_gudangutama_active' => $setting->is_gudangutama_active ?? true,
                    'is_tindakan_active' => $setting->is_tindakan_active ?? true,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show()
    {
        $title = "Seting Websaite";
        $setting = WebSetting::first();
        $set_bpjs = Set_Bpjs::all();
        $set_Sehat = Set_Sehat::all();
        $singkron = external_database::all();
        return view('dashboard.webset', compact('title', 'setting', 'set_bpjs', 'set_Sehat', 'singkron'));
    }

    public function set_satusehat(Request $request)
    {
        $id = 1;

        // Validasi data yang masuk
        $validated = $request->validate([
            'org_id' => 'string',
            'client_id' => 'string',
            'client_secret' => 'string',
            'SCREET_KEY' => 'string',
            'SATUSEHAT_BASE_URL' => 'string',
        ]);

        try {
            // Coba mencari dan mengupdate record
            $record = Set_Sehat::findOrFail($id);
            $record->update($validated);

            return redirect()->route('web.get')->with('berhasil', 'Record berhasil diperbarui.');
        } catch (\Exception $e) {
            // Jika gagal, kembalikan dengan pesan error
            return redirect()->route('web.get')->with('gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function set_bpjs(Request $request)
    {
        $id = 1 ;
        $validated = $request->validate([
            'CONSID' => 'string',
            'USERNAME' => 'string',
            'PASSWORD' => 'string',
            'SCREET_KEY' => 'string',
            'USER_KEY' => 'string',
            'APP_CODE' => 'string',
            'BASE_URL' => 'string',
            'SERVICE' => 'string',
            'SERVICE_ANTREAN' => 'string',
            'KPFK' => 'string',
        ]);

        try {
            // Coba mencari dan mengupdate record
            $record = Set_Bpjs::findOrFail($id);
            $record->update($validated);

            return redirect()->route('web.get')->with('berhasil', 'Record berhasil diperbarui.');
        } catch (\Exception $e) {
            // Jika gagal, kembalikan dengan pesan error
            return redirect()->route('web.get')->with('gagal', 'Terjadi kesalahan: ' . $e->getMessage());
        }

    }

    public function setActiveGudangUtama(Request $request)
    {
        $id = $request->input('gudang_utama_id');
        // Set semua ke 0
        external_database::query()->update(['active' => 0]);
        // Set yang dipilih ke 1
        external_database::where('database', $id)->update(['active' => 1]);
        return response()->json(['success' => true, 'message' => 'Gudang utama berhasil dipilih.']);
    }

    public function resetActiveGudangUtama()
    {
        external_database::query()->update(['active' => 0]);
        return response()->json(['success' => true]);
    }
}
