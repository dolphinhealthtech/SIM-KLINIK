<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resep Dokter</title>
    <style>
        @page {
            size: A6 portrait;
            margin: 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 0;
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

        .content {
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .resep-line {
            margin-bottom: 3px;
            font-size: 9px;
        }

        .footer {
            position: absolute;
            bottom: 10mm;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            text-align: center;
            margin-top: 25px;
        }

        .signature-label {
            font-size: 8px;
            font-weight: bold;
            margin-bottom: 10px; /* tambahkan jarak ke bawah dari label */
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 70%;
            margin: 10px auto 0 auto; /* atur jarak dari label ke garis tanda tangan */
        }

    </style>
</head>
<body>
    <div class="header">
        <div class="clinic-name">KLINIK OMEGA CITRA RAYA</div>
        <div class="clinic-info">
            Ruko Danau Citra, Jl. Citra Raya Boulevard No.10<br>
            Kec. Cikupa, Kab. Tangerang, Banten 15131<br>
            Telp: 0813-1089-4294
        </div>
    </div>

    <div class="divider"></div>
    <br>
    <div class="content">
        @foreach ($resepList as $line)
            <div class="resep-line">{!! nl2br(e($line)) !!}</div>
        @endforeach
    </div>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td class="signature-label">Dokter</td>
                <td class="signature-label">Pasien</td>
            </tr>
            <tr>
                <td style="height: 60px;"></td>
                <td style="height: 60px;"></td>
            </tr>
            <tr>
                <td><div class="signature-line"></div></td>
                <td><div class="signature-line"></div></td>
            </tr>
        </table>
    </div>
</body>
</html>
