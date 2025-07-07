<?php

namespace App\Http\Controllers\DataMaster\soap;

use App\Http\Controllers\Controller;
use App\Models\odontogram;
use App\Models\odontogram_details;
use Illuminate\Http\Request;


class OdoController extends Controller
{
        public function odontogramadd(Request $request)
    {
        $data = $request->all(); // Data berupa array JSON dari JS

        foreach ($data as $item) {
            odontogram::updateOrCreate(
                [
                    'nomor_rm'    => $item['nomor_rm'],
                    'nama'  => $item['nama'],
                    'no_rawat'  => $item['no_rawat'],
                    'sex'  => $item['sex'],
                    'penjamin'  => $item['penjamin'],
                    'tanggal_lahir'  => $item['tanggal_lahir'],
                    'tooth_number'  => $item['tooth_number']
                ],
                [
                    'condition' => $item['condition'],
                    'note'      => $item['note']
                ]
            );
        }

        return response()->json(['message' => 'Data kondisi gigi berhasil disimpan atau diperbarui.']);
    }

    public function odontogramload(Request $request)
    {
        $request->validate([
            'nomor_rm'    => 'required|string',
            'no_rawat'  => 'required|string',
        ]);

        $conditions = odontogram::where('nomor_rm', $request->nomor_rm)
            ->where('no_rawat', $request->no_rawat)
            ->get();

        return response()->json($conditions);
    }

    public function odontogramdetailsadd(Request $request)
    {
        $validated = $request->validate([
            'nomor_rm' => 'required|string',
            'no_rawat' => 'required|string',
            'nama' => 'required|string',
            'sex' => 'nullable|string',
            'penjamin' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'Decayed' => 'nullable|string',
            'Missing' => 'nullable|string',
            'Filled' => 'nullable|string',
            'Oclusi' => 'nullable|string',
            'Palatinus' => 'nullable|string',
            'Mandibularis' => 'nullable|string',
            'Platum' => 'nullable|string',
            'Diastema' => 'nullable|string',
            'Anomali' => 'nullable|string',
        ]);

        // Simpan atau update data
        odontogram_details::updateOrCreate(
            [
                'nomor_rm' => $validated['nomor_rm'],
                'no_rawat' => $validated['no_rawat']
            ],
            $validated
        );

        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function odontogramdetailsload(Request $request)
    {
        $request->validate([
            'nomor_rm' => 'required|string',
            'no_rawat' => 'required|string',
        ]);

        $data = odontogram_details::where('nomor_rm', $request->nomor_rm)
                        ->where('no_rawat', $request->no_rawat)
                        ->first();

        return response()->json($data);
    }
}
