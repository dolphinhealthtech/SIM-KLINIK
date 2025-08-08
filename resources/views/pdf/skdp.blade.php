<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>SKDP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 6px;
            margin: 0mm;
            padding: 0;
        }

        .divider {
            border-top: 1px solid #000;
            margin: 3px 0;
        }

        .document-title {
            font-size: 8px;
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
            width: 95%;
            margin: 0;
            margin-left: 5px;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
            border: none;
        }

        .info-label {
            width: 65px;
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
                <div style="font-size: 10px; font-weight: bold; margin: 0;">
                    {{ $namaKlinik }}
                </div>
                <div style="font-size: 6px; line-height: 1.2; margin: 0;">
                    {!! nl2br(e($alamatKlinik)) !!}
                </div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="document-title">SURAT KETERANGAN DOKTER PENGGAWAS</div>

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

    <p class="teks">Berdasarkan pemeriksaan yang telah dilakukan pada
        {{ $tanggal_pemeriksaan ? \Carbon\Carbon::parse($tanggal_pemeriksaan)->format('d-m-Y H:i') : '-' }}, yang
        bersangkutan memerlukan {{ $untuk_skdp ?? '-' }} pada
        {{ $pada_skdp ? \Carbon\Carbon::parse($pada_skdp)->format('d-m-Y') : '-' }} di {{ $poli_unit_skdp ?? '-' }}.
    </p>

    <table class="info-table" style="width: 100%;">
        <tr>
            <td class="info-label">Kode Surat</td>
            <td class="info-separator">:</td>
            <td>{{ $kode_surat ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Jenis</td>
            <td class="info-separator">:</td>
            <td>{{ $jenis_skdp ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">SEP BPJS</td>
            <td class="info-separator">:</td>
            <td>{{ $sep_bpjs ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">No Kartu</td>
            <td class="info-separator">:</td>
            <td>{{ $no_kartu ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Untuk</td>
            <td class="info-separator">:</td>
            <td>{{ $untuk_skdp ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Pada</td>
            <td class="info-separator">:</td>
            <td>{{ $pada_skdp ? \Carbon\Carbon::parse($pada_skdp)->format('d-m-Y') : '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Poli/Unit</td>
            <td class="info-separator">:</td>
            <td>{{ $poli_unit_skdp ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Alasan 1</td>
            <td class="info-separator">:</td>
            <td>{{ $alasan1_skdp ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Alasan 2</td>
            <td class="info-separator">:</td>
            <td>{{ $alasan2_skdp ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Rencana 1</td>
            <td class="info-separator">:</td>
            <td>{{ $rencana1_skdp ?? '-' }}</td>
        </tr>

        <tr>
            <td class="info-label">Rencana 2</td>
            <td class="info-separator">:</td>
            <td>{{ $rencana2_skdp ?? '-' }}</td>
        </tr>
    </table>

    <p class="teks">Demikian surat keterangan dokter penggawas ini dibuat dengan sebenar-benarnya dan untuk
        dipergunakan sebagai mestinya.</p>

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
