<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Gudang Utama</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Lebih kecil */
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
            font-size: 12px;
            margin-bottom: 2px;
        }
        .divider {
            border-top: 1px solid #000;
            margin: 5px 0;
        }
        .document-title {
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 10px;
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
        table.items th {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            font-size: 10px;
            height: 30px;
        }
        table.items td {
            border: 1px solid #000;
            padding: 2px;
            text-align: left;
            font-size: 10px;
            height: 30px;
        }
        table.items th {
            background-color: #f2f2f2;
        }
        .summary-table {
            width: 300px;
            margin-left: 0;
            margin-right: auto;
            border-collapse: collapse;
            margin-top: 10px;
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
        }

        .signature-table {
            width: 175%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 120px;
            margin: 0 auto;
            margin-top: 40px;
        }

        .page-break {
            page-break-after: always;
        }
        .content-wrapper {
            min-height: auto; /* Hapus fixed height */
        }
        .footnote {
            font-size: 10px;
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

    <div class="document-title">LAPORAN GUDANG UTAMA</div>

    <table class="info-table" style="width: 100%;">
        <tr>
            <td class="info-label">Dicetak pada</td>
            <td class="info-separator">:</td>
            <td>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</td>
        </tr>

        <tr>
            <td class="info-label">Laporan Gudang Utama</td>
            <td class="info-separator">:</td>
            <td>[{{ auth()->user()->name ?? 'Petugas' }}]</td>
        </tr>

        <tr>
            <td class="info-label">Klinik</td>
            <td class="info-separator">:</td>
            <td>{{ $klinik ?? 'Semua Klinik' }}</td>
        </tr>

        <tr>
            <td colspan="3">
                Periode : {{ $tanggal_awal }} sampai {{ $tanggal_akhir }}
            </td>
        </tr>
    </table>

    <div class="content-wrapper">
        <table class="items">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Request</th>
                    <th>Nama Klinik</th>
                    <th>Nama Obat / Alkes</th>
                    <th>Qty</th>
                    <th>Tanggal</th>
                    <th>Petugas Entry</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $i => $item)
                    <tr>
                        {{-- <td>{{ $i + 1 }}</td> --}}
                        <td style="text-align: center;">{{ $item['no'] }}</td>
                        <td>{{ $item['kode_request'] }}</td>
                        <td>{{ $item['nama_klinik'] }}</td>
                        <td>{{ $item['nama_obat_alkes'] ?? '-' }}</td>
                        <td style="text-align: center;">{{ $item['qty'] }}</td>
                        <td>{{ $item['tanggal'] }}</td>
                        <td>{{ $item['petugas_entry'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Catatan kaki untuk menjelaskan subtotal - Diperbaiki dengan font yang lebih jelas -->
        {{-- <div style="font-size: 8px; font-style: italic; margin-top: 3px; margin-bottom: 8px; text-align: right; font-weight: bold;">
            * Subtotal sudah termasuk diskon per item
        </div> --}}

        <div class="clearfix">
            <table class="summary-table">
                <tr>
                    <td>Jumlah Invoice</td>
                    <td>:</td>
                    <td style="text-align: right;">{{ $total_invoice }} Lembar</td>
                </tr>
                @foreach ($obatQtySummary as $nama_obat => $qty)
                    <tr>
                        <td>{{ $nama_obat }}</td>
                        <td>:</td>
                        <td style="text-align: right;">{{ $qty }} Buah</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>



    <div class="footer">
        <table class="signature-table">
            <tr>
                <td></td>
                <td></td>
                <td>Paraf Petugas</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="height: 40px;"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><div class="signature-line"></div></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>{{ auth()->user()->name ?? 'Petugas' }}</td>
            </tr>
        </table>
    </div>

</body>
</html>
