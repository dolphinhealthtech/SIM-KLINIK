<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Gudang\Set_Harga;

use App\Http\Controllers\Controller;
use App\Models\gudang_setting_harga;
use App\Models\external_database;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Gudang_setting_hargaExport;
use App\Imports\Gudang_setting_hargaImport;
use App\Models\gudang_setting_harga_utama;
use Illuminate\Http\Request;


class Set_Harga_Utama_Controller extends Controller
{


    public function setharga_utama()
    {
        $title = "Master Setting Harga Jual Utama";
        $setharga = gudang_setting_harga_utama::first();
        Carbon::setLocale('id');
        $lastUpdated = $setharga ? Carbon::parse($setharga->updated_at)->diffForHumans() : 'belum ada update';
        $singkron = external_database::all();

        return view('module.master-data-gudang.setting_harga_jual_utama', compact('title', 'setharga', 'lastUpdated', 'singkron'));
    }


    public function sethargaadd_utama(Request $request)
    {
        try {
            $request->validate([
                'harga_jual_1'  => 'required|string',
                'harga_jual_2'  => 'required|string',
                'harga_jual_3'  => 'required|string',

            ], [
                'harga_jual_1'  => 'Harga Jual 1',
                'harga_jual_2'  => 'Harga Jual 2',
                'harga_jual_3'  => 'Harga Jual 3'
            ]);

            // Bersihkan prefix atau simbol dari input agar hanya angka saja
            $harga_jual_1  = preg_replace('/[^\d]/', '', $request->input('harga_jual_1'));
            $harga_jual_2  = preg_replace('/[^\d]/', '', $request->input('harga_jual_2'));
            $harga_jual_3  = preg_replace('/[^\d]/', '', $request->input('harga_jual_3'));

            // Simpan data ke database
            $setharga = gudang_setting_harga_utama::first();

            if ($setharga) {
                $setharga->update([
                    'harga_jual_1' => $harga_jual_1,
                    'harga_jual_2' => $harga_jual_2,
                    'harga_jual_3' => $harga_jual_3,
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);
            } else {
                $setharga = gudang_setting_harga_utama::create([
                    'harga_jual_1' => $harga_jual_1,
                    'harga_jual_2' => $harga_jual_2,
                    'harga_jual_3' => $harga_jual_3,
                    'user_input_id' => Auth::user()->id,
                    'user_input_name' => Auth::user()->name,
                ]);
            }
            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Setting harga jual berhasil ditambahkan!',
                'data' => $setharga
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Setting harga jual sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan setting harga jual!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
