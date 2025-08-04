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
        table.items th {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            font-size: 8px;
            height: 30px;
        }
        table.items td {
            border: 1px solid #000;
            padding: 2px;
            text-align: left;
            font-size: 8px;
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
            <div class="header-title">{{ $namaKlinik }}</div>
            <div class="header-address">{!! nl2br(e($alamatKlinik)) !!}</div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="document-title">Laporan Setor Kasir</div>

    <table class="info-table" style="width: 100%;">
        <tr>
            <td class="info-label">Dicetak pada</td>
            <td class="info-separator">:</td>
            <td>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</td>
        </tr>

        <tr>
            <td class="info-label">Laporan Setoran Kasir</td>
            <td class="info-separator">:</td>
            <td>[{{ auth()->user()->name ?? 'Petugas' }}]</td>
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
                    <th>Invoice</th>
                    <th>No RM</th>
                    <th>No Rawat</th>
                    <th>Nama</th>
                    <th>Poli</th>
                    <th>Dokter</th>
                    <th>Penjamin</th>
                    <th>Sub Total</th>
                    <th>Tambahan</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Tanggal</th>
                    <th>Petugas Entry</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item['kode_faktur'] }}</td>
                        <td>{{ $item['no_rm'] }}</td>
                        <td>{{ $item['no_rawat'] ?? '-' }}</td>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ $item['poli'] }}</td>
                        <td>{{ $item['dokter'] ?? '-' }}</td>
                        <td>{{ $item['penjamin'] }}</td>
                        <td>{{ $item['sub_total'] }}</td>
                        <td>
                            @php
                                $tambahan = [];

                                if (!empty($item['potongan_harga']) && $item['potongan_harga'] != 0) {
                                    $tambahan[] = 'Diskon: ' . $item['potongan_harga'];
                                }
                                if (!empty($item['administrasi']) && $item['administrasi'] != 0) {
                                    $tambahan[] = 'Administrasi: ' . $item['administrasi'];
                                }
                                if (!empty($item['materai']) && $item['materai'] != 0) {
                                    $tambahan[] = 'Materai: ' . $item['materai'];
                                }
                            @endphp

                            {!! count($tambahan) > 0 ? implode('<br>', $tambahan) : '-' !!}
                        </td>
                        <td>{{ $item['total'] }}</td>
                        <td>
                            @php
                                $paymentTexts = [];

                                for ($i = 1; $i <= 3; $i++) {
                                    $methodKey = "payment_method_$i";
                                    $nominalKey = "payment_nominal_$i";

                                    if (!empty($item[$methodKey]) && isset($item[$nominalKey])) {
                                        $method = ucfirst($item[$methodKey]);
                                        $nominal = $item[$nominalKey];
                                        $paymentTexts[] = "{$method} : {$nominal}";
                                    }
                                }
                            @endphp

                            {!! count($paymentTexts) > 0 ? implode('<br>', $paymentTexts) : '-' !!}
                        </td>
                        <td>{{ $item['tanggal'] }}</td>
                        <td>{{ $item['user_input_name'] }}</td>
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
                    <td>Jumlah Invoice</td>
                    <td>:</td>
                    <td style="text-align: right;">{{ $total_invoice }} Lembar</td>
                </tr>
                <tr>
                    <td>Cash</td>
                    <td>:</td>
                    <td style="text-align: right;">{{ $cashFormatted }}</td>
                </tr>
                <tr>
                    <td>Debit</td>
                    <td>:</td>
                    <td style="text-align: right;">{{ $debitFormatted }}</td>
                </tr>
                <tr>
                    <td>Credit</td>
                    <td>:</td>
                    <td style="text-align: right;">{{ $creditFormatted }}</td>
                </tr>
                <tr>
                    <td>Transfer</td>
                    <td>:</td>
                    <td style="text-align: right;">{{ $transferFormatted }}</td>
                </tr>
                <tr>
                    <td>Pendapatan</td>
                    <td>:</td>
                    <td style="text-align: right;">{{ $pendapatanFormatted }}</td>
                </tr>
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
