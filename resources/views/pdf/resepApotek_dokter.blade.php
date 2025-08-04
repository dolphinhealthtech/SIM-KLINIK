<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resep Dokter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 0mm;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
        }

        .clinic-name {
            font-size: 12px;
            font-weight: bold;
        }

        .clinic-info {
            font-size: 7px;
        }

        .divider {
            border-top: 1px solid #000;
            margin: 5px 0;
        }

        .document-title {
            font-size: 12px;
            font-weight: bold;
            margin: 8px 0;
            text-align: center;
        }

        .content {
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .resep-line {
            margin-bottom: 3px;
            font-size: 9px;
        }

        .note-table {
            width: 100%;
            margin-bottom: 5px;
            margin-top: 25px;
        }

        .note-table td {
            padding: 1px 0;
            vertical-align: top;
            border: none;
        }

        .note-label {
            width: 10px;
        }

        .note-separator {
            width: 10px;
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: 5px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
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

    </style>
</head>
<body>
    <div class="header">
        <div class="clinic-name">{{ $namaKlinik }}</div>
        <div class="clinic-info">
            {!! nl2br(e($alamatKlinik)) !!}
        </div>
    </div>

    <div class="divider"></div>

    <div class="document-title">Resep Obat</div>

    <br>

    <div class="content">
        @foreach ($data as $line)
            <div class="resep-line">
                R:/<br>
                @foreach (explode('|', $line) as $resep)
                    @php
                        // Pecah string resep jadi kata-kata
                        $parts = explode(' ', trim($resep));
                        $firstLine = implode(' ', array_slice($parts, 0, -4)); // nama dan jumlah obat
                        $secondLine = $parts[count($parts) - 4] ?? '';         // oral/injeksi
                        $thirdLine = implode(' ', array_slice($parts, -3));   // frekuensi dan durasi
                    @endphp
                    {!! nl2br(e($firstLine)) !!}<br>
                    {!! nl2br(e($secondLine)) !!}<br>
                    {!! nl2br(e($thirdLine)) !!}<br>
                @endforeach
            </div>
        @endforeach
    </div>

    <table class="note-table" style="width: 100%;">
        <tr>
            <td class="note-label" style="height: 20px;">Note</td>
            <td class="note-separator" style="height: 20px;">:</td>
            <td style="height: 20px;">{{ $note ?? ' ' }}</td>
        </tr>
    </table>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td style="font-weight: bold;">Paraf Petugas</td>
                <td style="font-weight: bold;">Paraf Pasien</td>
            </tr>
            <tr>
                <td style="height: 20px;"></td>
                <td style="height: 20px;"></td>
            </tr>
            <tr>
                <td><div class="signature-line"></div></td>
                <td><div class="signature-line"></div></td>
            </tr>
            <tr>
                <td>{{ auth()->user()->name ?? 'Petugas' }}</td>
                <td></td>
            </tr>
        </table>
    </div>
</body>
</html>
