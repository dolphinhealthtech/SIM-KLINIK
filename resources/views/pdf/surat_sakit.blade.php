<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Sakit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 7px;
            margin: 0mm;
            padding: 0;
        }

        .divider {
            border-top: 1px solid #000;
            margin: 5px 0;
        }

        .document-title {
            font-size: 9px;
            font-weight: bold;
            margin: 0;
            text-align: center;
        }

        .kode-surat {
            font-size: 9px;
            font-weight: bold;
            margin: 0;
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: 5px;
            width: 100%;
        }

        .signature-table {
            width: 180%;
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

        .info-table {
            width: 90%;
            margin: 0;
            margin-left: 10px;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
            border: none;
        }

        .info-label {
            width: 73px;
        }

        .info-separator {
            width: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td style="width: 60px; text-align: center; vertical-align: middle;">
                <img src="{{ public_path('profile/default.png') }}" alt="Logo"
                    style="width: 50px; height: 50px; border-radius: 50%;">
            </td>
            <td style="text-align: center;">
                <div style="font-size: 11px; font-weight: bold; margin: 0;">
                    {{ $namaKlinik }}
                </div>
                <div style="font-size: 7px; line-height: 1.3; margin: 0;">
                    {!! nl2br(e($alamatKlinik)) !!}
                </div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="document-title">SURAT SAKIT</div>

    <p class="teks">Yang bertanda tangan dibawah ini, menerangkan bahwa : </p>

    <table class="info-table" style="width: 100%;">
        <tr>
            <td class="info-label">Nama</td>
            <td class="info-separator">:</td>
            <td>{{ $nama_pasien }}</td>
        </tr>

        <tr>
            <td class="info-label">Umur</td>
            <td class="info-separator">:</td>
            <td>{{ $umur }}</td>
        </tr>

        <tr>
            <td class="info-label">Kelamin/Tgl Lahir</td>
            <td class="info-separator">:</td>
            <td>{{ $jenis_kelamin }} ({{ \Carbon\Carbon::parse($tanggal_lahir)->format('d-m-Y') }})</td>
        </tr>

        <tr>
            <td class="info-label">Alamat</td>
            <td class="info-separator">:</td>
            <td>{{ $alamat }}</td>
        </tr>
    </table>

    <p class="teks">Berdasarkan pemeriksaan yang telah dilakukan, yang bersangkutan dinyatakan SAKIT dan perlu
        istirahat selama {{ $lama_istirahat ?? 0 }} hari terhitung dari
        {{ $terhitung_mulai ? \Carbon\Carbon::parse($terhitung_mulai)->format('d-m-Y') : '-' }}.</p>

    <table class="info-table" style="width: 100%;">
        <tr>
            <td class="info-label">Diagnosis</td>
            <td class="info-separator">:</td>
            <td>{{ $diagnosis_utama }}</td>
        </tr>

        <tr>
            <td class="info-label">Istirahat</td>
            <td class="info-separator">:</td>
            <td>{{ $lama_istirahat ?? 0 }} Hari</td>
        </tr>

        <tr>
            <td class="info-label">Terhitung Mulai</td>
            <td class="info-separator">:</td>
            <td>{{ $terhitung_mulai ? \Carbon\Carbon::parse($terhitung_mulai)->format('d-m-Y') : '-' }}</td>
        </tr>
    </table>

    <p class="teks">Demikian surat sakit ini dibuat dengan sebenar-benarnya dan untuk dipergunakan sebagai mestinya.
    </p>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td>Tangerang, {{ $now->format('d-m-Y') }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Dokter Pemeriksa</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="height: 20px;"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <div class="signature-line"></div>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>{{ $dokter_pengirim ?? (auth()->user()->name ?? 'Petugas') }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
</body>

</html>
