<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Panggilan MCU</title>

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

        /* ================= HEADER ================= */

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

        /* ================= GARIS ================= */

        .line-top {
            border-top: 2.5px solid #000;
            margin-top: 8px;
        }

        .line-bottom {
            border-top: 1px solid #000;
            margin-top: 2px;
            margin-bottom: 14px;
        }

        /* ================= NOMOR ================= */

        .nomor {
            margin-bottom: 12px;
            font-size: 11pt;
        }

        .nomor td {
            padding: 1px 0;
        }

        /* ================= JUDUL ================= */

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

        /* ================= CONTENT ================= */

        .content p {
            margin: 0 0 9px 0;
            text-align: justify;
        }

        .kepada {
            margin-bottom: 10px;
        }

        .kepada p {
            margin: 0;
        }

        .info-table {
            margin: 8px 0 10px 0;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 11pt;
        }

        .info-table td:first-child {
            width: 165px;
        }

        .persyaratan-title {
            margin-top: 8px;
            margin-bottom: 4px;
            font-weight: bold;
        }

        ol {
            margin-top: 4px;
            margin-bottom: 10px;
            padding-left: 22px;
        }

        ol li {
            margin-bottom: 2px;
        }

        /* ================= SIGNATURE ================= */

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

        /* ================= PAGE BREAK ================= */

        .header-wrapper,
        .info-table,
        .signature,
        .judul,
        .nomor {
            page-break-inside: avoid;
        }

        p,
        li {
            orphans: 2;
            widows: 2;
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

        <!-- ================= HEADER ================= -->

        <div class="header-wrapper">

            <table class="header-table">

                <tr>

                    <!-- LOGO KIRI -->
                    <td class="logo-left">

                        @if ($logo1_base64)
                            <img src="{{ $logo1_base64 }}">
                        @endif

                    </td>

                    <!-- COMPANY -->
                    <td class="company">

                        <h1>
                            PT RIAU SAKTI UNITED PLANTATION
                        </h1>

                        <h2>
                            SAMBU GROUP
                        </h2>

                        <p>
                            KM. 00 Desa Pulau Burung,
                            Kec. Pulau Burung,
                            Kab. Indragiri Hilir
                        </p>

                        <p>
                            Provinsi Riau, Indonesia 29256
                        </p>

                        <p>
                            Telp: +62 (779) 541888 |
                            +62-811703197 |
                            +62 (779) 541000
                        </p>

                        <p>
                            Email: p2-general@rsup.co.id
                        </p>

                    </td>

                    <!-- LOGO KANAN -->
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

        <!-- ================= NOMOR ================= -->

        <table class="nomor">

            <tr>
                <td width="70">Nomor</td>
                <td width="10">:</td>
                <td>
                    {{ date('Y') }}/RSUP-MCU/{{ str_pad($mcu->id, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>

        </table>

        <!-- ================= JUDUL ================= -->

        <div class="judul">

            <h3>
                SURAT PANGGILAN MEDICAL CHECK UP
            </h3>

        </div>

        <!-- ================= CONTENT ================= -->

        <div class="content">

            <div class="kepada">

                <p>Kepada Yth,</p>

                <p class="bold">
                    {{ strtoupper($mcu->pelamar->nama_lengkap) }}
                </p>

                <p>Di Tempat</p>

            </div>

            <p>
                Dengan hormat,
            </p>

            <p>
                Berdasarkan hasil tahapan seleksi administrasi serta
                psikotes yang telah Saudara ikuti,
                maka dengan ini kami mengundang Saudara untuk mengikuti
                proses
                <span class="bold">
                    Medical Check Up (MCU)
                </span>
                yang akan dilaksanakan pada:
            </p>

            <!-- ================= DETAIL ================= -->

            <table class="info-table">

                <tr>
                    <td>Hari / Tanggal</td>
                    <td width="10">:</td>
                    <td>
                        {{ Carbon\Carbon::parse($mcu->jadwal_mcu)->translatedFormat('l, d F Y') }}
                    </td>
                </tr>

                <tr>
                    <td>Pukul</td>
                    <td>:</td>
                    <td>
                        {{ Carbon\Carbon::parse($mcu->jadwal_mcu)->format('H:i') }} WIB
                    </td>
                </tr>

                <tr>
                    <td>Tempat</td>
                    <td>:</td>
                    <td>
                        {{ $mcu->lokasi_mcu }}
                    </td>
                </tr>

                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>
                        {{ $mcu->alamat_lokasi }}
                    </td>
                </tr>

                @if ($mcu->nama_rs)
                    <tr>
                        <td>Rumah Sakit</td>
                        <td>:</td>
                        <td>
                            {{ $mcu->nama_rs }}
                        </td>
                    </tr>
                @endif

                @if ($mcu->dokter_pemeriksa)
                    <tr>
                        <td>Dokter Pemeriksa</td>
                        <td>:</td>
                        <td>
                            {{ $mcu->dokter_pemeriksa }}
                        </td>
                    </tr>
                @endif

            </table>

            <!-- ================= PERSYARATAN ================= -->

            <p class="persyaratan-title">
                Persyaratan yang harus dibawa:
            </p>

            <ol>
                <li>Fotokopi KTP dan Kartu Keluarga</li>
                <li>Pas foto ukuran 3x4 sebanyak 2 lembar</li>
                <li>Puasa minimal 8 jam sebelum pemeriksaan</li>
                <li>Menggunakan pakaian yang rapi dan sopan</li>
                <li>Membawa dokumen kesehatan pendukung bila diperlukan</li>
            </ol>

            @if ($mcu->keterangan)
                <p>
                    <span class="bold">
                        Keterangan Tambahan:
                    </span>

                    {{ $mcu->keterangan }}
                </p>
            @endif

            <p>
                Demikian surat panggilan ini kami sampaikan.
                Atas perhatian dan kehadiran Saudara,
                kami ucapkan terima kasih.
            </p>

        </div>

        <!-- ================= TTD ================= -->

        <div class="signature">

            <p>
                Padang,
                {{ Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </p>

            <p>
                Hormat kami,
            </p>

            <div class="signature-space"></div>

            <p class="bold" style="margin-bottom:0;">
                {{ $mcu->pewawancara ?? 'SOEPRIONO' }}
            </p>

            <p style="margin-top:0;">
                HRD Manager PT RSUP
            </p>

        </div>

    </div>

</body>

</html>
