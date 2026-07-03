<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hasil Medical Check Up</title>
    <style>
        @page {
            margin: 20px 28px;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11pt;
            line-height: 1.35;
            color: #000;
            margin: 0;
            padding: 0;
        }

        * {
            box-sizing: border-box;
        }

        .container {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-wrapper {
            margin-bottom: 10px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo-left {
            width: 120px;
            text-align: left;
        }

        .logo-right {
            width: 120px;
            text-align: right;
        }

        .logo-left img {
            width: 95px;
            height: auto;
        }

        .logo-right img {
            width: 100px;
            height: auto;
        }

        .company {
            text-align: center;
            padding: 0 8px;
        }

        .company h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .company h2 {
            margin: 3px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .company p {
            margin: 1px 0;
            font-size: 10.5px;
        }

        .line-top {
            border-top: 2.5px solid #000;
            margin-top: 8px;
        }

        .line-bottom {
            border-top: 1px solid #000;
            margin-top: 2px;
            margin-bottom: 14px;
        }

        .judul {
            text-align: center;
            margin-bottom: 16px;
        }

        .judul h3 {
            margin: 0;
            font-size: 14pt;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }

        .content p {
            margin: 0 0 9px 0;
            text-align: justify;
        }

        .info-table {
            margin: 10px 0;
        }

        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 180px;
            font-weight: bold;
        }

        .hasil-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .hasil-table th,
        .hasil-table td {
            border: 1px solid #000;
            padding: 6px 10px;
            text-align: left;
        }

        .hasil-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .hasil-table td {
            vertical-align: middle;
        }

        .signature {
            width: 260px;
            margin-left: auto;
            text-align: center;
            margin-top: 20px;
        }

        .signature-space {
            height: 60px;
        }

        .bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .status-badge {
            font-weight: bold;
            padding: 3px 12px;
            border-radius: 4px;
            display: inline-block;
        }

        .status-sangat-sehat {
            color: #1a7a3a;
        }

        .status-sehat {
            color: #2d7d2d;
        }

        .status-cukup {
            color: #b8860b;
        }

        .status-kurang {
            color: #cc5500;
        }

        .status-tidak {
            color: #cc0000;
        }
    </style>
</head>

<body>
    @php
        $logo1 = public_path('images/logorsup.png');
        $logo2 = public_path('images/sambugrup.png');
        $logo1_base64 = file_exists($logo1) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo1)) : '';
        $logo2_base64 = file_exists($logo2) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo2)) : '';
    @endphp

    <div class="container">
        <div class="header-wrapper">
            <table class="header-table">
                <tr>
                    <td class="logo-left">
                        @if ($logo1_base64)
                            <img src="{{ $logo1_base64 }}">
                        @endif
                    </td>
                    <td class="company">
                        <h1>PT RIAU SAKTI UNITED PLANTATION</h1>
                        <h2>SAMBU GROUP</h2>
                        <p>KM. 00 Desa Pulau Burung, Kec. Pulau Burung, Kab. Indragiri Hilir</p>
                        <p>Provinsi Riau, Indonesia 29256</p>
                        <p>Telp: +62 (779) 541888 | +62-811703197 | +62 (779) 541000</p>
                        <p>Email: p2-general@rsup.co.id</p>
                    </td>
                    <td class="logo-right">
                        @if ($logo2_base64)
                            <img src="{{ $logo2_base64 }}">
                        @endif
                    </td>
                </tr>
            </table>
            <div class="line-top"></div>
            <div class="line-bottom"></div>
        </div>

        <div class="judul">
            <h3>HASIL MEDICAL CHECK UP</h3>
        </div>

        <div class="content">
            <table class="info-table">
                <tr>
                    <td>Nama Pelamar</td>
                    <td>:</td>
                    <td>{{ $mcu->pelamar->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td>Posisi yang Dilamar</td>
                    <td>:</td>
                    <td>{{ $mcu->lowongan->judul_lowongan }}</td>
                </tr>
                <tr>
                    <td>Tanggal MCU</td>
                    <td>:</td>
                    <td>{{ Carbon\Carbon::parse($mcu->jadwal_mcu)->translatedFormat('l, d F Y H:i') }} WIB</td>
                </tr>
                <tr>
                    <td>Rumah Sakit</td>
                    <td>:</td>
                    <td>{{ $mcu->nama_rs ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Dokter Pemeriksa</td>
                    <td>:</td>
                    <td>{{ $mcu->dokter_pemeriksa ?? '-' }}</td>
                </tr>
            </table>

            <h4 style="margin-top:12px; margin-bottom:6px;">Hasil Pemeriksaan</h4>

            <table class="hasil-table">
                <thead>
                    <tr>
                        <th style="width:45%;">Parameter</th>
                        <th style="width:55%;">Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tinggi Badan</td>
                        <td>{{ $mcu->tinggi_badan ? $mcu->tinggi_badan . ' cm' : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Berat Badan</td>
                        <td>{{ $mcu->berat_badan ? $mcu->berat_badan . ' kg' : '-' }}</td>
                    </tr>
                    <tr>
                        <td>BMI</td>
                        <td>{{ $mcu->bmi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tekanan Darah</td>
                        <td>{{ $mcu->tekanan_darah ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Visus Mata</td>
                        <td>{{ $mcu->visus_mata ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Buta Warna</td>
                        <td>{{ $mcu->buta_warna ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Hasil Tes Narkoba</td>
                        <td>{{ $mcu->hasil_narkoba ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Riwayat Penyakit</td>
                        <td>{{ $mcu->riwayat_penyakit ?? 'Tidak ada' }}</td>
                    </tr>
                    @if ($mcu->catatan_admin)
                        <tr>
                            <td>Catatan Tambahan</td>
                            <td>{{ $mcu->catatan_admin }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div style="margin-top:14px; padding:10px; border:1px solid #000; border-radius:4px; background:#f9f9f9;">
                <table style="width:100%;">
                    <tr>
                        <td style="width:40%; font-weight:bold;">Status Kesehatan</td>
                        <td style="width:60%;">
                            <span
                                class="status-badge
                                @if ($mcu->kondisi_kesehatan == 'Sangat Sehat') status-sangat-sehat
                                @elseif($mcu->kondisi_kesehatan == 'Sehat') status-sehat
                                @elseif($mcu->kondisi_kesehatan == 'Cukup Sehat') status-cukup
                                @elseif($mcu->kondisi_kesehatan == 'Kurang Sehat') status-kurang
                                @elseif($mcu->kondisi_kesehatan == 'Tidak Lulus') status-tidak @endif
                            ">
                                {{ $mcu->kondisi_kesehatan ?? 'Belum Ditentukan' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold;">Skor MCU</td>
                        <td>
                            @if ($mcu->nilai_mcu)
                                {{ number_format($mcu->nilai_mcu, 2) }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <p style="margin-top:12px;">
                Demikian hasil Medical Check Up ini kami sampaikan sebagai bahan pertimbangan
                untuk kelanjutan proses rekrutmen.
            </p>
        </div>

        <div class="signature">
            <p>Padang, {{ Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Hormat kami,</p>
            <div class="signature-space"></div>
            <p class="bold" style="margin-bottom:0;">{{ $mcu->dokter_pemeriksa ?? 'SOEPRIONO' }}</p>
            <p style="margin-top:0;">Dokter Pemeriksa </p>
        </div>
    </div>
</body>

</html>
