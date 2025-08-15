<?php

namespace App\Http\Controllers\Module\Data_Master\Data_Medis\Perawatan_Tindakan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Brijing_Intergrasi\Pcare_Controller;
use App\Models\perawatan_tindakan;

class Perawatan_Tindakan_Api_Controller extends Controller
{
    protected $PcareController;

    // Gunakan dependency injection
    public function __construct(Pcare_Controller $PcareController)
    {
        $this->PcareController = $PcareController;
    }

    // API Get Kode Kategori Perawatan

    public function getLastKode()
    {
        $last = perawatan_tindakan::orderBy('id', 'desc')->first();

        return response()->json([
            'kode' => $last ? $last->kode : null
        ]);
    }
}
