<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ asset('assets/img/ukt1logo.png') }}" />
        <title>Laporan Kinerja</title>
        <style>
            @page {
                margin: 20mm 5mm 20mm 5mm;
            }

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 10px;
                margin: 0;
                padding: 0;
            }

            .header {
                position: fixed;
                top: -65px;
                left: 20px;
                right: 20px;
                height: 60px;
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

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 10px;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 5px;
                vertical-align: top;
            }

            th {
                background-color: #ccc;
                text-align: center;
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
        <div class="header">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left">
                        <img src="{{ public_path('assets/img/ukt1logo.png') }}" style="height:60px;">
                    </td>
                    <td align="right">
                        <img src="{{ public_path('assets/img/logo-dki-jakarta.png') }}" style="height:60px;">
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <i>SIGMA UKT 1 - Dibuat {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</i>
        </div>

        <div>
            <div class="text-center" style="margin-top: 0">
                <h2 class="mb-0 text-uppercase font-weight-bold">
                    <u>LAPORAN KINERJA</u>
                </h2>
            </div>

            <div class="mt-3">
                <table>
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
                    </tbody>
                </table>
            </div>
        </div>
    </body>

</html>
