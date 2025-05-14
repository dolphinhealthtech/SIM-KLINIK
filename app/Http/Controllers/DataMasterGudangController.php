<?php

namespace App\Http\Controllers;

use App\Exports\Gudang_satuanExport;
use App\Exports\Gudang_kategoriExport;
use App\Exports\Gudang_supplier_industriExport;
use App\Imports\Gudang_satuanImport;
use App\Imports\Gudang_kategoriImport;
use App\Imports\Gudang_supplier_industriImport;
use App\Models\gudang_satuan;
use App\Models\gudang_kategori;
use App\Models\gudang_supplier_industri;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class DataMasterGudangController extends Controller
{
    // Jenis Satuan

    public function satuan()
    {
        $title = "Master Jenis Satuan";
        $satuan = gudang_satuan::all();
        return view('module.master-data-gudang.satuan', compact('title','satuan'));
    }

    public function satuanadd(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string'
            ]);
            // Simpan data ke database
            $satuan = gudang_satuan::create([
                'nama' => $request->input('nama')
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Jenis satuan berhasil ditambahkan!',
                'data' => $satuan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis satuan Sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Jenis satuan!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function satuanedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string'
        ]);

        $satuan = gudang_satuan::find($request->satuanid_edit);

        if (!$satuan) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis satuan tidak ditemukan!'
            ], 404);
        }

        $satuan->nama = $request->nama_edit;
        $satuan->save();

        return response()->json([
            'success' => true,
            'message' => 'Jenis satuan berhasil diperbarui!'
        ]);
    }

    public function satuandelete(Request $request)
    {

        $request->validate([
            'satuanid_delete' => 'required'
        ]);

        $satuan = gudang_satuan::find($request->satuanid_delete);

        if (!$satuan) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis satuan tidak ditemukan!'
            ], 404);
        }

        $satuan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis satuan berhasil dihapus!'
        ]);
    }

    public function satuanexport()
    {
        return Excel::download(new Gudang_satuanExport, 'Jenis Satuan.xlsx');
    }

    public function satuanimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Gudang_satuanImport, $request->file('file'));


        return redirect()->route('satuan.get')->with('success', 'Data berhasil diimpor!');
    }

    // Jenis satuan end

    // Jenis Kategori

    public function kategori()
    {
        $title = "Master Jenis Kategori";
        $kategori = gudang_kategori::all();
        return view('module.master-data-gudang.kategori', compact('title','kategori'));
    }

    public function kategoriadd(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string'
            ]);
            // Simpan data ke database
            $kategori = gudang_kategori::create([
                'nama' => $request->input('nama')
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Jenis kategori berhasil ditambahkan!',
                'data' => $kategori
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kategori sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Jenis kategori!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function kategoriedit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string'
        ]);

        $kategori = gudang_kategori::find($request->kategoriid_edit);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kategori tidak ditemukan!'
            ], 404);
        }

        $kategori->nama = $request->nama_edit;
        $kategori->save();

        return response()->json([
            'success' => true,
            'message' => 'Jenis kategori berhasil diperbarui!'
        ]);
    }

    public function kategoridelete(Request $request)
    {

        $request->validate([
            'kategoriid_delete' => 'required'
        ]);

        $kategori = gudang_kategori::find($request->kategoriid_delete);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kategori tidak ditemukan!'
            ], 404);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis kategori berhasil dihapus!'
        ]);
    }

    public function kategoriexport()
    {
        return Excel::download(new Gudang_kategoriExport, 'Jenis Kategori.xlsx');
    }

    public function kategoriimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Gudang_kategoriImport, $request->file('file'));


        return redirect()->route('kategori.get')->with('success', 'Data berhasil diimpor!');
    }

    // Jenis Kategori end

    // Supplier Industri

    public function supplier()
    {
        $title = "Master Supplier Industri";
        $supplier = gudang_supplier_industri::all();
        return view('module.master-data-gudang.supplier', compact('title','supplier'));
    }

    public function supplieradd(Request $request)
    {
        try {
            $request->validate([
                'kode' => 'required|string',
                'nama' => 'required|string',
                'nama_pic' => 'required|string',
                'telepon_pic' => 'required|string'
            ]);
            // Simpan data ke database
            $supplier = gudang_supplier_industri::create([
                'kode' => $request->input('kode'),
                'nama' => $request->input('nama'),
                'nama_pic' => $request->input('nama_pic'),
                'telepon_pic' => $request->input('telepon_pic'),
            ]);

            // Return response JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Supplier Industri berhasil ditambahkan!',
                'data' => $supplier
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier Industri sudah ada!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Supplier Industri!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function supplieredit(Request $request)
    {
        $request->validate([
            'nama_edit' => 'required|string',
            'nama_pic_edit' => 'required|string',
            'telepon_pic_edit' => 'required|string'
        ]);

        $supplier = gudang_supplier_industri::find($request->supplierid_edit);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier Industri tidak ditemukan!'
            ], 404);
        }

        $supplier->nama = $request->nama_edit;
        $supplier->nama_pic = $request->nama_pic_edit;
        $supplier->telepon_pic = $request->telepon_pic_edit;
        $supplier->save();

        return response()->json([
            'success' => true,
            'message' => 'Supplier Industri berhasil diperbarui!'
        ]);
    }

    public function supplierdelete(Request $request)
    {

        $request->validate([
            'supplierid_delete' => 'required'
        ]);

        $supplier = gudang_supplier_industri::find($request->supplierid_delete);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier Industri tidak ditemukan!'
            ], 404);
        }

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier Industri berhasil dihapus!'
        ]);
    }

    public function supplierexport()
    {
        return Excel::download(new Gudang_supplier_industriExport, 'Supplier Industri.xlsx');
    }

    public function supplierimport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new Gudang_supplier_industriImport, $request->file('file'));


        return redirect()->route('supplier.get')->with('success', 'Data berhasil diimpor!');
    }

        // API Get Kode Supplier Industri

        public function getLastKode()
        {
            $last = gudang_supplier_industri::orderBy('id', 'desc')->first();

            return response()->json([
                'kode' => $last ? $last->kode : null
            ]);
        }

    // Supplier Industri end

    /**
     * Menampilkan halaman request obat
     */
    public function request()
    {
        $title = "Kelola Data Barang";
        
        // Ambil data obat dari tabel gudang_barang
        $obatList = \App\Models\gudang_barang::select('id', 'kode_barang', 'nama_barang')
                        ->orderBy('nama_barang', 'asc')
                        ->get();
        
        // Data dummy untuk tampilan awal (nanti akan diisi dari database)
        $approveData = collect([
            (object)['id' => 'REQ-12345678', 'nama_obat' => 'Paracetamol 500mg', 'jumlah' => 100],
            (object)['id' => 'REQ-87654321', 'nama_obat' => 'Amoxicillin 500mg', 'jumlah' => 50],
        ]);
        
        $requestData = collect([
            (object)['id' => 1, 'kode' => 'REQ-ABCD1234', 'nama_obat' => 'Paracetamol 500mg', 'jumlah' => 100, 'tanggal' => '2023-06-15'],
            (object)['id' => 2, 'kode' => 'REQ-EFGH5678', 'nama_obat' => 'Amoxicillin 500mg', 'jumlah' => 50, 'tanggal' => '2023-06-16'],
        ]);
        
        $stokData = collect([
            (object)['kode' => 'OBT-0001', 'nama_obat' => 'Paracetamol 500mg', 'jumlah' => 500],
            (object)['kode' => 'OBT-0002', 'nama_obat' => 'Amoxicillin 500mg', 'jumlah' => 300],
            (object)['kode' => 'OBT-0003', 'nama_obat' => 'Ibuprofen 400mg', 'jumlah' => 200],
        ]);
        
        return view('module.master-data-gudang.request', compact('title', 'obatList', 'approveData', 'requestData', 'stokData'));
    }

    /**
     * Display the main dashboard for gudang management.
     *
     * @return \Illuminate\View\View
     */
    public function utama()
    {
        $title = "Dashboard Gudang";
        
        // Get real-time data for the dashboard
        $requestData = $this->getRequestDataForDashboard();
        $stokData = $this->getStokDataForDashboard();
        $stokMenipis = $this->getStokMenipisForDashboard();
        
        // Get summary data
        $totalRequest = count($requestData);
        $totalStok = count($stokData);
        $totalStokMenipis = count($stokMenipis);
        
        // Get list of kliniks for filtering
        $klinikList = $this->getKlinikListForDashboard();
        
        return view('module.master-data-gudang.utama', compact(
            'title', 
            'requestData', 
            'stokData', 
            'stokMenipis',
            'totalRequest',
            'totalStok',
            'totalStokMenipis',
            'klinikList'
        ));
    }

    /**
     * Get request data for the dashboard.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getRequestDataForDashboard()
    {
        // In a real application, you would fetch this from your database
        return collect([
            (object) [
                'id' => 'REQ-20230615-1001',
                'klinik' => 'Balaraja',
                'tanggal' => '2023-06-15',
                'status' => 'pending'
            ],
            (object) [
                'id' => 'REQ-20230616-1002',
                'klinik' => 'Jaya',
                'tanggal' => '2023-06-16',
                'status' => 'pending'
            ],
            (object) [
                'id' => 'REQ-20230617-1003',
                'klinik' => 'Sentosa',
                'tanggal' => '2023-06-17',
                'status' => 'pending'
            ],
        ]);
    }

    /**
     * Get stock data for the dashboard.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getStokDataForDashboard()
    {
        // In a real application, you would fetch this from your database
        return collect([
            (object) [
                'id' => 1,
                'kode' => 'OBT-1001',
                'nama_obat' => 'Paracetamol 500mg',
                'harga' => 15000,
                'jumlah' => 100
            ],
            (object) [
                'id' => 2,
                'kode' => 'OBT-1002',
                'nama_obat' => 'Amoxicillin 500mg',
                'harga' => 25000,
                'jumlah' => 75
            ],
            (object) [
                'id' => 3,
                'kode' => 'OBT-1003',
                'nama_obat' => 'Ibuprofen 400mg',
                'harga' => 20000,
                'jumlah' => 50
            ],
            (object) [
                'id' => 4,
                'kode' => 'OBT-1004',
                'nama_obat' => 'Cetirizine 10mg',
                'harga' => 18000,
                'jumlah' => 80
            ],
            (object) [
                'id' => 5,
                'kode' => 'OBT-1005',
                'nama_obat' => 'Omeprazole 20mg',
                'harga' => 30000,
                'jumlah' => 60
            ],
        ]);
    }

    /**
     * Get low stock data for the dashboard.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getStokMenipisForDashboard()
    {
        // In a real application, you would fetch this from your database
        return collect([
            (object) [
                'id' => 3,
                'kode' => 'OBT-1003',
                'nama_obat' => 'Ibuprofen 400mg',
                'harga' => 20000,
                'jumlah' => 10,
                'min_stok' => 20
            ],
            (object) [
                'id' => 5,
                'kode' => 'OBT-1005',
                'nama_obat' => 'Omeprazole 20mg',
                'harga' => 30000,
                'jumlah' => 15,
                'min_stok' => 30
            ]
        ]);
    }

    /**
     * Get list of kliniks for the dashboard.
     *
     * @return array
     */
    private function getKlinikListForDashboard()
    {
        // In a real application, you would fetch this from your database
        return [
            ['id' => 'Balaraja', 'name' => 'Klinik Balaraja'],
            ['id' => 'Jaya', 'name' => 'Klinik Jaya'],
            ['id' => 'Sentosa', 'name' => 'Klinik Sentosa'],
            ['id' => 'Makmur', 'name' => 'Klinik Makmur']
        ];
    }

    /**
     * Get stock data for the dashboard (AJAX).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStokData()
    {
        $stokData = $this->getStokDataForDashboard();
        return response()->json($stokData);
    }

    /**
     * Get request data for the dashboard (AJAX).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRequestData()
    {
        $requestData = $this->getRequestDataForDashboard();
        return response()->json($requestData);
    }

    /**
     * Get detail data for a specific request (AJAX).
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetailData($id)
    {
        // In a real application, you would fetch this data from your database
        // based on the provided $id
        
        // Sample data mapping based on ID
        $detailDataMap = [
            'REQ-20230615-1001' => [
                'id' => 'REQ-20230615-1001',
                'klinik' => 'Klinik Balaraja',
                'tanggal' => '2023-06-15',
                'status' => 'pending',
                'items' => [
                    [
                        'kode' => 'OBT-1001',
                        'nama' => 'Paracetamol 500mg',
                        'jumlah' => 20
                    ],
                    [
                        'kode' => 'OBT-1002',
                        'nama' => 'Amoxicillin 500mg',
                        'jumlah' => 15
                    ]
                ]
            ],
            'REQ-20230616-1002' => [
                'id' => 'REQ-20230616-1002',
                'klinik' => 'Klinik Jaya',
                'tanggal' => '2023-06-16',
                'status' => 'pending',
                'items' => [
                    [
                        'kode' => 'OBT-1003',
                        'nama' => 'Ibuprofen 400mg',
                        'jumlah' => 10
                    ],
                    [
                        'kode' => 'OBT-1004',
                        'nama' => 'Cetirizine 10mg',
                        'jumlah' => 25
                    ]
                ]
            ],
            'REQ-20230617-1003' => [
                'id' => 'REQ-20230617-1003',
                'klinik' => 'Klinik Sentosa',
                'tanggal' => '2023-06-17',
                'status' => 'pending',
                'items' => [
                    [
                        'kode' => 'OBT-1005',
                        'nama' => 'Omeprazole 20mg',
                        'jumlah' => 30
                    ]
                ]
            ]
        ];
        
        // Get the detail data for the requested ID, or return a default structure
        $detailData = $detailDataMap[$id] ?? [
            'id' => $id,
            'klinik' => 'Unknown Klinik',
            'tanggal' => date('Y-m-d'),
            'status' => 'pending',
            'items' => []
        ];
        
        return response()->json($detailData);
    }

    /**
     * Request a specific item.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestItem(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'id' => 'required',
            'jumlah' => 'required|numeric|min:1'
        ]);
        
        // In a real application, you would save this request to your database
        
        return response()->json([
            'success' => true,
            'message' => 'Item berhasil direquest dengan jumlah ' . $validated['jumlah']
        ]);
    }

    /**
     * Approve a request.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveRequest($id)
    {
        // In a real application, you would update the request status in your database
        
        return response()->json([
            'success' => true,
            'message' => "Request $id berhasil disetujui."
        ]);
    }

    /**
     * Reject a request.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectRequest($id)
    {
        // In a real application, you would update the request status in your database
        
        return response()->json([
            'success' => true,
            'message' => "Request $id berhasil ditolak."
        ]);
    }

    /**
     * Confirm multiple requests.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function konfirmasiPermintaan(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|string'
        ]);
        
        // In a real application, you would update the request status in your database
        
        return response()->json([
            'success' => true,
            'message' => count($validated['ids']) . " permintaan berhasil dikonfirmasi."
        ]);
    }

    /**
     * Get list of kliniks for filtering.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKlinikList()
    {
        $klinikList = $this->getKlinikListForDashboard();
        return response()->json($klinikList);
    }

    /**
     * Get list of items with low stock.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStokMenipis()
    {
        $stokMenipis = $this->getStokMenipisForDashboard();
        return response()->json($stokMenipis);
    }
}


