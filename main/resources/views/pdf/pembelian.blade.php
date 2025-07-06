<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tanda Terima Apotek</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px; /* Lebih kecil */
            line-height: 1.2; /* Lebih rapat */
            margin: 10px; /* Margin lebih kecil */
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-container {
            text-align: center;
            margin-right: 10px;
        }
        .logo {
            width: 50px; /* Lebih kecil */
            height: 50px; /* Lebih kecil */
            border-radius: 50%;
        }
        .header-text {
            text-align: center;
        }
        .header-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .header-address, .header-phone {
            font-size: 9px;
            margin-bottom: 2px;
        }
        .divider {
            border-top: 1px solid #000;
            margin: 5px 0;
        }
        .document-title {
            font-size: 12px;
            font-weight: bold;
            margin: 8px 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 5px;
        }
        .info-table td {
            padding: 1px 0;
            vertical-align: top;
            border: none;
        }
        .info-label {
            width: 120px;
        }
        .info-separator {
            width: 10px;
            text-align: center;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        table.items th, table.items td {
            border: 1px solid #000;
            padding: 2px;
            text-align: left;
            font-size: 8px;
        }
        table.items th {
            background-color: #f2f2f2;
        }
        .summary-table {
            width: 300px;
            margin-left: 0;
            margin-right: auto;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 3px 0;
            vertical-align: top;
            border: none;
        }
        .total-row td {
            font-weight: bold;
            padding-top: 3px;
        }
        .summary-divider {
            border-top: 1px solid #000;
            margin: 5px 0;
            width: 100%;
        }
        .footer {
            position: fixed;
            bottom: 5px;
            width: 100%;
            text-align: center;
        }
        .signature-table {
            width: 100%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 0 auto;
            margin-top: 40px; /* Lebih kecil */
        }
        .page-break {
            page-break-after: always;
        }
        .content-wrapper {
            min-height: auto; /* Hapus fixed height */
        }
        .footnote {
            font-size: 8px;
            font-style: italic;
            margin-top: 3px;
            margin-bottom: 8px;
            text-align: right;
            font-weight: bold;
        }
        .total-divider {
            border-top: 1px solid #000;
            width: 100%;
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Brand Logo dari sidebar dengan path yang diubah ke public/profile/default.png -->
        <div class="logo-container">
            <img src="{{ public_path('profile/default.png') }}" 
                alt="Klinik Logo" class="logo" 
                style="width: 80px; height: 80px; opacity: .8">
        </div>
        <div class="header-text">
            <div class="header-title">KLINIK OMEGA CITRA RAYA</div>
            <div class="header-address">Ruko Danau Citra, Jl. Citra Raya Boulevard No.10, Kec. Cikupa, Kabupaten Tangerang, Banten 15131</div>
            <div class="header-phone">0813-1089-4294</div>
        </div>
    </div>
    
    <div class="divider"></div>
    
    <div class="document-title">Tanda Terima Apotek (TT. APOTEK)</div>
    
    <table class="info-table" style="width: 100%;">
        <tr>
            <td class="info-label">No. Faktur</td>
            <td class="info-separator">:</td>
            <td>{{ $pembelian->nomor_faktur }}</td>
            
            <td class="info-label" style="padding-left: 50px;">Tanggal Faktur</td>
            <td class="info-separator">:</td>
            <td>{{ date('Y-m-d', strtotime($pembelian->tanggal_faktur)) }}</td>
        </tr>
        
        <tr>
            <td class="info-label">No. SP/PO</td>
            <td class="info-separator">:</td>
            <td>{{ $pembelian->no_po_sp }}</td>
            
            <td class="info-label" style="padding-left: 50px;">Jatuh Tempo</td>
            <td class="info-separator">:</td>
            <td>{{ date('d F, Y', strtotime($pembelian->tanggal_jatuh_tempo)) }}</td>
        </tr>
        
        <tr>
            <td class="info-label">Faktur Supplier</td>
            <td class="info-separator">:</td>
            <td>{{ $pembelian->no_faktur_supplier }}</td>
            
            <td class="info-label" style="padding-left: 50px;">PPN</td>
            <td class="info-separator">:</td>
            <td>{{ $pembelian->pajak_ppn }}%</td>
        </tr>
        
        <tr>
            <td class="info-label">Supplier</td>
            <td class="info-separator">:</td>
            <td>{{ $pembelian->supplier }}</td>
            
            <td class="info-label" style="padding-left: 50px;">Tanggal Terima</td>
            <td class="info-separator">:</td>
            <td>{{ date('Y-m-d', strtotime($pembelian->tanggal_terima_barang)) }}</td>
        </tr>
    </table>
    
    <div class="content-wrapper">
        @php
            // Proses ulang detail untuk memastikan subtotal sudah termasuk diskon
            foreach ($details as $detail) {
                // Bersihkan format mata uang
                $hargaSatuan = floatval(str_replace(['Rp', '.', ','], ['', '', '.'], $detail->harga_satuan));
                $qty = floatval($detail->qty);
                $diskonText = $detail->diskon;
                
                // Hitung diskon
                $diskonValue = 0;
                if (strpos($diskonText, '%') !== false) {
                    // Jika diskon dalam persen
                    $diskonPersen = floatval(str_replace('%', '', $diskonText));
                    $diskonValue = ($hargaSatuan * $qty) * ($diskonPersen / 100);
                } elseif (strpos($diskonText, 'Rp') !== false) {
                    // Jika diskon dalam rupiah
                    $diskonValue = floatval(str_replace(['Rp', '.', ','], ['', '', '.'], $diskonText));
                }
                
                // Hitung subtotal setelah diskon
                $detail->subtotal_setelah_diskon = ($hargaSatuan * $qty) - $diskonValue;
            }
        @endphp

        <table class="items">
            <thead>
                <tr>
                    <th style="width: 25%;">Nama Barang</th>
                    <th style="width: 5%;">Qty</th>
                    <th style="width: 15%;">Harga Sat</th>
                    <th style="width: 12%;">Expired</th>
                    <th style="width: 8%;">Disc</th>
                    <th style="width: 15%;">Batch No</th>
                    <th style="width: 20%;">Subtotal*</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $detail)
                <tr>
                    <td>{{ $detail->nama_obat_alkes }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td>Rp {{ number_format(floatval(str_replace(['Rp', '.', ','], ['', '', '.'], $detail->harga_satuan)), 0, ',', '.') }}</td>
                    <td>{{ $detail->exp }}</td>
                    <td>{{ $detail->diskon }}</td>
                    <td>{{ $detail->batch }}</td>
                    <td>Rp {{ number_format($detail->subtotal_setelah_diskon, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Catatan kaki untuk menjelaskan subtotal - Diperbaiki dengan font yang lebih jelas -->
        <div style="font-size: 8px; font-style: italic; margin-top: 3px; margin-bottom: 8px; text-align: right; font-weight: bold;">
            * Subtotal sudah termasuk diskon per item
        </div>
        
        <div class="clearfix">
            <table class="summary-table">
                <tr>
                    <td style="width: 40%;">Sub Total</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 55%; text-align: right;">
                        @php
                            $totalSubtotal = 0;
                            foreach ($details as $item) {
                                // Pastikan kita menggunakan nilai yang sudah dihitung sebelumnya
                                $totalSubtotal += $item->subtotal_setelah_diskon;
                            }
                        @endphp
                        Rp {{ number_format($totalSubtotal, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>PPN ({{ $pembelian->pajak_ppn }}%)</td>
                    <td>:</td>
                    <td style="text-align: right;">Rp {{ number_format($pembelian->ppn_total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Materai</td>
                    <td>:</td>
                    <td style="text-align: right;">Rp {{ number_format($pembelian->materai, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Koreksi</td>
                    <td>:</td>
                    <td style="text-align: right;">Rp {{ number_format($pembelian->koreksi, 0, ',', '.') }}</td>
                </tr>
                <!-- Garis sebelum total -->
                <tr>
                    <td colspan="3" style="padding: 2px 0;">
                        <div style="border-top: 1px solid #000; width: 100%;"></div>
                    </td>
                </tr>
                <tr class="total-row">
                    <td style="font-weight: bold;">Total</td>
                    <td style="font-weight: bold;">:</td>
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    
    
    <div class="footer">
        <table class="signature-table">
            <tr>
                <td style="width: 33%; padding-bottom: 20px;">Petugas Entry Apotik</td>
                <td style="width: 33%; padding-bottom: 20px;">Petugas Penerima Apotik</td>
                <td style="width: 33%; padding-bottom: 20px;">Penanggung Jawab Apotek</td>
            </tr>
            <tr>
                <td style="height: 60px;"></td>
                <td style="height: 60px;"></td>
                <td style="height: 60px;"></td>
            </tr>
            <tr>
                <td><div class="signature-line"></div></td>
                <td><div class="signature-line"></div></td>
                <td><div class="signature-line"></div></td>
            </tr>
        </table>
    </div>
</body>
</html>
