<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ asset('assets/img/ukt1logo.png') }}" />
        <title>Laporan Kinerja - {{ $user->name ?? '-' }}</title>
        <style>
            /* @page {
                margin: 20mm 5mm 20mm 5mm;
            } */

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 10px;
                margin: 0;
                padding: 0;
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

            /* ==== OVERRIDE HEADER ==== */
            .header table,
            .header th,
            .header td {
                border: none !important;
                padding: 0 !important;
            }

            .footer {
                position: fixed;
                bottom: -60px;
                left: 0;
                right: 0;
                text-align: right;
                font-size: 12px;
                color: #555;
            }

            .text-center {
                text-align: center;
            }

            .text-right {
                text-align: right;
            }

            .text-left {
                text-align: left;
            }

            .text-uppercase {
                text-transform: uppercase;
            }

            .font-weight-bold {
                font-weight: bold;
            }

            .mb-0 {
                margin-bottom: 0;
            }

            .mt-3 {
                margin-top: 15px;
            }

            .mt-5 {
                margin-top: 3rem;
            }

            .table {
                width: 100%;
                border-collapse: collapse;
            }

            .table-bordered,
            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000;
            }

            .table th,
            .table td {
                padding: 4px;
            }

            .table-borderless td {
                border: none;
            }

            .text-nowrap {
                white-space: nowrap;
            }

            .text-wrap {
                white-space: normal;
            }

            .py-1 {
                padding-top: 1px !important;
                padding-bottom: 1px !important;
            }

            .img-thumbnail {
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 2px;
                height: 80px;
                margin: 12px 3px 1px 0;
                vertical-align: middle;
                display: inline-block;
            }

            .page-break {
                page-break-after: always;
            }
        </style>
    </head>

    <body>
        {{-- <div class="header">
            <div class="left">
                <img src="{{ public_path('assets/img/ukt1logo.png') }}">
            </div>
            <div class="right">
                <img src="{{ public_path('assets/img/logo-dki-jakarta.png') }}">
            </div>
            <div class="clearfix"></div>
        </div> --}}

        {{-- <div class="footer">
            <i>SIGMA UKT 1 - Dibuat {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</i>
        </div> --}}

        <div>
            <div class="text-center" style="margin-top: 0">
                <h1 class="mb-0 text-uppercase font-weight-bold">
                    <u>LAPORAN KINERJA</u>
                </h1>
            </div>

            <div class="mt-2">
                <table style="font-size: 14px; margin-left: 1.5rem;">
                    <tr>
                        <td style="width: 20mm">Nama</td>
                        <td style="width: 5mm">:</td>
                        <td class="font-weight-bold text-uppercase">{{ $user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>ID PJLP</td>
                        <td>:</td>
                        <td>{{ $user->nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>Petugas {{ $user->jabatan->name ?? '-' }} {{ $user->formasi_tim->tim->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Seksi</td>
                        <td>:</td>
                        <td>{{ $user->formasi_tim->tim->seksi->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Lokasi</td>
                        <td>:</td>
                        <td>{{ $user->pulau->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Periode</td>
                        <td>:</td>
                        <td>{{ $start_date }} s/d {{ $end_date }}</td>
                    </tr>
                </table>
            </div>

            <div class="text-center" style="margin-top: 0">
                <h2 class="mb-0 text-uppercase font-weight-bold">
                    <u>DETAIL KEGIATAN</u>
                </h2>
            </div>

            <div class="mt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-uppercase">
                            <th style="width: 12px">No.</th>
                            <th style="width: 40px">Hari</th>
                            <th style="width: 60px">Tanggal</th>
                            <th style="width: 130px">Personel</th>
                            <th>Kegiatan</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kinerja as $item)
                            <tr>
                                <td class="text-center" rowspan="2">{{ $loop->iteration }}</td>
                                <td class="text-nowrap">{{ $item->hari }}</td>
                                <td class="text-nowrap">{{ $item->formatted_tanggal }}</td>
                                <td class="text-nowrap font-weight-bold">{{ $item->user->name ?? '-' }}</td>
                                <td class="text-wrap">{{ $item->kegiatan->name ?? $item->kegiatan_lainnya }}</td>
                                <td class="text-wrap">{{ $item->lokasi }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="mb-0">
                                    @if ($item->kinerja_photos)
                                        @foreach ($item->kinerja_photos as $i)
                                            <img class="img-thumbnail" src="{{ public_path('storage/' . $i->photo) }}"
                                                alt="Foto Kegiatan">
                                        @endforeach
                                    @endif
                                    <p class="mb-0 mt-0">
                                        Waktu: {{ $item->waktu_mulai ?? '-' }} s/d {{ $item->waktu_selesai ?? '-' }} <br>
                                        Catatan: {{ $item->deskripsi ?? '-' }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                        @if ($kinerja->count() == 0)
                            <tr>
                                <td class="text-center" colspan="6">
                                    <p>Tidak ada data kinerja.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="page-break"></div>

            <div class="text-center" style="margin-top: 0">
                <h2 class="mb-0 text-uppercase font-weight-bold">
                    <u>PERSETUJUAN</u>
                </h2>
            </div>

            <div class="mt-5 text-center" style="font-size: 14px;">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 7cm;" class="text-center py-1">Pengawas Seksi {{ $user->formasi_tim->tim->seksi->name ?? '-' }}</td>
                        <td style="width: auto;" class="py-1"></td>
                        <td style="width: 7cm;" class="text-center py-1">Petugas PJLP</td>
                    </tr>
                    <tr>
                        <td class="text-center py-1">{{ $user->formasi_tim->tim->seksi->unit_kerja->name ?? '-' }}</td>
                        <td class="py-1"></td>
                        <td class="text-center py-1">{{ $user->formasi_tim->tim->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-center py-1">Kabupaten Adm. Kep. Seribu</td>
                        <td class="py-1"></td>
                        <td class="text-center py-1"></td>
                    </tr>
                    <tr>
                        <td style="height: 40mm;"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center font-weight-bold py-1" style="border-bottom:1pt solid black;">
                            {{ $pengawas->name ?? 'N/A' }}</td>
                        <td class="py-1"></td>
                        <td class="text-center font-weight-bold py-1" style="border-bottom:1pt solid black;">
                            {{ $user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-center py-1">NIP. {{ $pengawas->nip ?? 'N/A' }}</td>
                        <td class="py-1"></td>
                        <td class="text-center py-1">ID PJLP. {{ $user->nip ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="mt-5 text-center" style="font-size: 14px;">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: auto;" class="py-1"></td>
                        <td style="width: 7cm;" class="text-center py-1">@if(optional($kepala_seksi)->is_plt == true)Plt.@endif Kepala Seksi {{ $kepala_seksi?->seksi?->name ?? 'N/A' }}</td>
                        <td style="width: auto;" class="py-1"></td>
                    </tr>
                    <tr>
                        <td class="py-1"></td>
                        <td class="text-center py-1">{{ $kepala_seksi?->seksi?->unit_kerja?->name ?? 'N/A' }}</td>
                        <td class="py-1"></td>
                    </tr>
                    <tr>
                        <td class="py-1"></td>
                        <td class="text-center py-1">Kabupaten Adm. Kep. Seribu</td>
                        <td class="py-1"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="height: 40mm;"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="py-1"></td>
                        <td class="text-center font-weight-bold py-1" style="border-bottom:1pt solid black;">
                            {{ $kepala_seksi->name ?? 'N/A' }}</td>
                        <td class="py-1"></td>
                    </tr>
                    <tr>
                        <td class="py-1"></td>
                        <td class="text-center py-1">NIP. {{ $kepala_seksi->nip ?? 'N/A' }}</td>
                        <td class="py-1"></td>
                    </tr>
                </table>
            </div>
        </div>
    </body>

</html>
