<?php

namespace App\Http\Controllers;

use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebSettingController extends Controller
{
    // Menyimpan atau memperbarui pengaturan web
    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $setting = WebSetting::first() ?? new WebSetting();

        if ($request->hasFile('profile_image')) {
            // Hapus gambar lama jika ada
            if ($setting->profile_image && file_exists(public_path('setting/' . $setting->profile_image))) {
                unlink(public_path('setting/' . $setting->profile_image));
            }

            // Simpan gambar baru langsung ke public/setting
            $file = $request->file('profile_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('setting'), $filename);

            // Simpan path ke database tanpa 'public/'
            $setting->profile_image = $filename;
        }



        // Simpan nama dan alamat
        $setting->nama = $request->nama;
        $setting->alamat = $request->alamat;
        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
