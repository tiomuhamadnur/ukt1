<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ asset('assets/img/ukt1logo.png') }}" />
        <title>Surat Izin - {{ $cuti->user->name }}</title>
        <style>
            /* === Pengaturan halaman untuk Dompdf === */
            @page {
                size: A4;
                margin: 25mm 20mm 20mm 20mm;
            }

            body {
                font-family: 'DejaVu Sans', Arial, sans-serif;
                font-size: 16px;
                color: #000;
            }

            .header {
                width: 100%;
                margin-bottom: 20px;
            }

            .header img {
                height: 60px;
            }

            .header .left {
                float: left;
            }

            .header .right {
                float: right;
            }

            .clearfix {
                clear: both;
            }

            .footer {
                position: fixed;
                bottom: -15mm;
                left: 0;
                right: 0;
                text-align: right;
                font-size: 11px;
                color: #555;
            }

            .content {
                margin-top: 0mm;
            }

            .title {
                text-align: center;
                text-transform: uppercase;
                font-weight: bold;
                text-decoration: underline;
                font-size: 15px;
                margin-bottom: 0;
            }

            .subtitle {
                text-align: center;
                margin-top: 4px;
                font-size: 15px;
            }

            .table-detail {
                margin-left: 5mm;
                margin-top: 3mm;
                font-size: 16px;
                border-collapse: collapse;
            }

            .table-detail td {
                padding: 2px 5px;
                vertical-align: top;
            }

            .text-justify {
                text-align: justify;
            }

            .signature-space {
                height: 40mm;
            }

            .img-lampiran {
                max-width: 100%;
                height: auto;
                max-height: 250mm;
                border: 1px solid #000;
                margin-top: 10mm;
            }

            ol {
                padding-left: 20px;
            }

            table,
            tr,
            td {
                page-break-inside: avoid;
            }

            .py-1 {
                padding-top: 0px !important;
                padding-bottom: 0px !important;
            }

            .page-break {
                page-break-after: always;
            }
        </style>
    </head>

    <body>
        <!-- Konten utama -->
        <div class="content">

            <!-- Judul surat -->
            <div>
                <p class="title">
                    SURAT IZIN
                    @if ($cuti->jenis_cuti_id == 1)
                        CUTI TAHUNAN
                    @else
                        SAKIT
                    @endif
                </p>
                <p class="subtitle">Nomor: {{ $cuti->nomor_surat ?? 'N/A' }}</p>
            </div>

            <!-- Isi utama -->
            <div style="margin-top: 8mm;">
                <ol>
                    <li class="text-justify" style="margin-bottom: 2mm;">
                        Diberikan {{ $cuti->jenis_cuti->name ?? '-' }}
                        @if ($cuti->jenis_cuti_id == 1)
                            {{ $tahun ?? '-' }}
                        @endif
                        kepada Penyedia Jasa Lainnya Perorangan dengan Perjanjian Kontrak (PJLP)

                        <table class="table-detail">
                            <tbody>
                                <tr>
                                    <td style="width: 30mm;">Nama</td>
                                    <td style="width: 3mm;">:</td>
                                    <td><strong>{{ $cuti->user->name ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td>ID PJLP</td>
                                    <td>:</td>
                                    <td>{{ $cuti->user->nip ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>:</td>
                                    <td>Petugas {{ $cuti->user->jabatan->name ?? '-' }} {{ $cuti->user->formasi_tim->tim->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>seksi</td>
                                    <td>:</td>
                                    <td>
                                        {{ $cuti->user->formasi_tim->tim->seksi->name ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Unit Kerja</td>
                                    <td>:</td>
                                    <td>
                                        {{ $cuti->user->formasi_tim->tim->seksi->unit_kerja->name ?? '-' }} Setkab. Adm.
                                        Kep. Seribu
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <p style="margin-top: 3mm;" class="text-justify">
                            Selama <strong>{{ $cuti->jumlah ?? '-' }} hari</strong> pada tanggal
                            <strong>{{ $tanggal ?? '-' }}</strong>, dengan ketentuan sebagai berikut:
                        </p>

                        <ol type="a" style="margin-left: 5mm;" class="text-justify">
                            @if ($cuti->jenis_cuti_id == 1)
                                <li>
                                    Sebelum menjalankan cuti wajib menyelesaikan pekerjaan dan melaporkan kepada atasan
                                    langsung.
                                </li>
                                <li>
                                    Setelah menjalankan cuti wajib melaporkan diri kepada atasan langsung dan bekerja
                                    kembali sebagaimana mestinya.
                                </li>
                            @else
                                <li>
                                    Periksakan diri ke klinik/puskesmas/rumah sakit agar mendapatkan penanganan yang
                                    tepat.
                                </li>
                                <li>
                                    Segera melaporkan perkembangan kondisi kesehatan kepada atasan langsung dan kembali
                                    bekerja sebagaimana mestinya setelah pulih.
                                </li>
                            @endif
                        </ol>
                    </li>

                    <li class="text-justify" style="margin-top: 5mm;">
                        Demikian Surat Izin ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
                    </li>
                </ol>
            </div>

            <!-- Tanda tangan -->
            <div style="margin-top: 15mm;">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="text-align: center; width: 80mm;" class="py-1"></td>
                            <td style="width: auto;" class="py-1"></td>
                            <td style="text-align: center; width: 7cm;" class="py-1">
                                <p>{{ $tanggal_approve ?? '-' }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;" class="py-1">
                                @if ($cuti->disetujui_oleh->is_plt == true)
                                    Plt.
                                @endif
                                Kepala {{ $cuti->disetujui_oleh->unit_kerja->name ?? '-' }}
                            </td>
                            <td class="py-1"></td>
                            <td style="text-align: center;" class="py-1">
                                @if ($cuti->diketahui_oleh->is_plt == true)
                                    Plt.
                                @endif
                                Kepala Seksi {{ $cuti->diketahui_oleh->seksi->name ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;" class="py-1">Kabupaten Adm. Kep. Seribu</td>
                            <td class="py-1"></td>
                            <td style="text-align: center;" class="py-1">{{ $cuti->diketahui_oleh->seksi->unit_kerja->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;" class="py-1"></td>
                            <td class="py-1"></td>
                            <td style="text-align: center;" class="py-1">Kabupaten Adm. Kep. Seribu</td>
                        </tr>
                        <tr>
                            <td class="signature-space" style="text-align: center;">
                                {{-- <img style="height: 35mm" src="{{ public_path('storage/' . $cuti->disetujui_oleh->ttd) }}" alt="TTD"> --}}
                            </td>
                            <td></td>
                            <td class="signature-space" style="text-align: center;">
                                {{-- <img style="height: 35mm" src="{{ public_path('storage/' . $cuti->diketahui_oleh->ttd) }}" alt="TTD"> --}}
                            </td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1pt solid black; text-align: center;" class="py-1">
                                <strong>{{ $cuti->disetujui_oleh->name ?? '-' }}</strong>
                            </td>
                            <td class="py-1"></td>
                            <td style="border-bottom: 1pt solid black; text-align: center;" class="py-1">
                                <strong>{{ $cuti->diketahui_oleh->name ?? '-' }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;" class="py-1">NIP. {{ $cuti->disetujui_oleh->nip ?? '-' }}</td>
                            <td class="py-1"></td>
                            <td style="text-align: center;" class="py-1">NIP. {{ $cuti->diketahui_oleh->nip ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tembusan -->
            {{-- <div style="margin-top: 10mm;">
                <p class="mb-0">Tembusan:</p>
                <ol style="margin-top: 0;">
                    <li>Kepala Unit Kerja Teknis 1 Kabupaten Administrasi Kepulauan Seribu</li>
                    <li>Pejabat Pembuat Komitmen Unit Kerja Teknis 1 Kabupaten Administrasi Kepulauan Seribu</li>
                </ol>
            </div> --}}

        </div>

        <!-- Lampiran (halaman baru jika ada) -->
        @if ($cuti->lampiran != null)
            <div class="page-break"></div>
            <div style="text-align: center;">
                <p
                    style="margin-bottom: 0; text-transform: uppercase; font-weight: bold; text-decoration: underline; font-size: 15px;">
                    Lampiran
                </p>
                <img class="img-lampiran" src="{{ public_path('storage/' . $cuti->lampiran) }}" alt="Lampiran">
            </div>
        @endif

    </body>

</html>
