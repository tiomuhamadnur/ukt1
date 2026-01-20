<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Absensi - {{ $user->user->name }}</title>

        <style>
            /* @page {
                margin: 20mm 5mm 20mm 5mm;
            } */

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
            }

            .text-center {
                text-align: center;
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

            .mt-3 {
                margin-top: 1rem;
            }

            .mt-2 {
                margin-top: 0.75rem;
            }

            .mb-1 {
                margin-bottom: .25rem;
            }

            .ml-4 {
                margin-left: 1.5rem;
            }

            .mt-5 {
                margin-top: 3rem;
            }

            u {
                text-decoration: underline;
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

            .py-1 {
                padding-top: 1px !important;
                padding-bottom: 1px !important;
            }

            .table-borderless td {
                border: none;
            }

            .img-thumbnail {
                border: 1px solid #ddd;
                padding: 2px;
                border-radius: 5px;
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
                bottom: -330px;
                left: 0;
                right: 0;
                text-align: right;
                font-size: 12px;
                color: #555;
            }

            .page-break {
                page-break-after: always;
            }

            /* Header box like Bootstrap */
            .summary-box {
                display: inline-block;
                background: #90ee90;
                padding: 18px;
                width: 20%;
                text-align: center;
                border-radius: 12px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                margin-right: 2%;
            }

            .bg-yellow {
                background-color:#ffe282;
            }

            .photo-cell {
                vertical-align: middle;
                text-align: center;
                padding: 5px;
                /* height: 60px; */
                white-space: nowrap;
            }

            .photo-cell img {
                display: inline-block;
                height: 60px;
                width: auto;
                margin: 5px 2px 0 0;
                vertical-align: middle;
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

        {{-- <div class="footer-last">
            SIGMA UKT 1 - Dibuat {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            <span class="page-number">Page [page] / [topage]</span>
        </div> --}}

        <div>
            <div class="text-center">
                <h3 class="mt-3 mb-1 text-uppercase font-weight-bold"><u>LAPORAN PRESENSI</u></h3>
            </div>

            <div class="mt-2">
                <table style="font-size: 14px; margin-left: 1.5rem;">
                    <tr>
                        <td style="width: 20mm">Nama</td>
                        <td style="width: 5mm">:</td>
                        <td class="font-weight-bold text-uppercase">{{ $user->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>ID PJLP</td>
                        <td>:</td>
                        <td>{{ $user->user->nip ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>Petugas {{ $user->user->jabatan->name ?? '-' }} {{ $user->tim->seksi->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Seksi</td>
                        <td>:</td>
                        <td>{{ $user->tim->seksi->name ?? '-' }}</td>
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

            {{-- <p class="text-center mt-3 text-uppercase font-weight-bold"><u>SUMMARY PRESENSI</u></p>

            <p class="ml-4"><u>Total Hari Kerja : {{ $jumlah_hari_kerja ?? 'N/A' }} Hari</u></p>

            <table class="table table-bordered" style="width: 90%; margin: auto; font-size: 13px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Presensi</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>Presensi Masuk & Pulang</td>
                        <td style="text-align:center">{{ $jumlah_hari_masuk ?? '0' }} hari</td>
                        <td></td>
                    </tr>
                    <tr @if(($jumlah_hari_tidak_lengkap ?? 0) > 0) class="bg-yellow" @endif>
                        <td class="text-center">2</td>
                        <td>Presensi Tidak Lengkap</td>
                        <td style="text-align:center">{{ $jumlah_hari_tidak_lengkap ?? '0' }} hari</td>
                        <td></td>
                    </tr>
                    <tr @if(($jumlah_hari_tidak_ok ?? 0) > 0) class="bg-yellow" @endif>
                        <td class="text-center">3</td>
                        <td>Presensi Tidak Tertib</td>
                        <td style="text-align:center">{{ $jumlah_hari_tidak_ok ?? '0' }} hari</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td>Tidak Hadir</td>
                        <td style="text-align:center">{{ $jumlah_hari_tidak_masuk ?? '0' }} hari</td>
                        <td>
                            Cuti: {{ $cuti ?? 'N/A' }} <br>
                            Sakit: {{ $sakit ?? 'N/A' }} <br>
                            Tanpa Keterangan: {{ $jumlah_hari_tidak_lengkap ?? 'N/A' }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div style="text-align:center; margin-top:30px;">

                @php
                    function getColor($value)
                    {
                        if ($value === null) {
                            return '#d3d3d3'; // abu-abu kalau N/A
                        } elseif ($value >= 90) {
                            return '#f08080'; // merah muda
                        } elseif ($value >= 70) {
                            return '#fffacd'; // kuning muda
                        } else {
                            return '#90ee90'; // hijau muda
                        }
                    }

                    $ketertiban = 100 - ($persentase_menit_telat ?? 0);
                    $ketertiban = ($ketertiban == floor($ketertiban))
                        ? number_format($ketertiban, 0, ',', '.')
                        : number_format($ketertiban, 1, ',', '.');
                @endphp

                <div
                    style="display:inline-block; background:{{ getColor($persentase_menit_telat ?? null) }}; color:#000; padding:18px; width:20%; text-align:center; border-radius:12px; box-shadow:0 4px 8px rgba(0,0,0,0.1); margin-right:2%;">
                    <div style="font-size:50px; font-weight:bold;">
                        {{ $ketertiban }}%
                    </div>
                    <div style="margin-top:5px; font-size:14px;">
                        Ketertiban Absensi <br>
                        <p style="font-size:10px">{{ $total_menit_telat }} Menit Waktu Telat</p>
                    </div>
                </div>

            </div> --}}

            {{-- <div class="footer">
                <i>SIGMA UKT 1 - Dibuat {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</i>
            </div> --}}
        </div>

        {{-- <div class="page-break"></div> --}}

        <div class="text-center">
            <p class="mt-3 mb-1 text-uppercase font-weight-bold"><u>DETAIL PRESENSI</u></p>
        </div>

        <div class="mt-3">
            <table class="table table-bordered text-center" style="font-size: 11px;">
                <thead>
                    <tr style="background-color: grey">
                        <th>No.</th>
                        <th>Hari</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Photo Masuk</th>
                        <th>Photo Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datesInRange as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-left">{{ $item['hari'] }}</td>
                            <td>{{ $item['tanggal']->locale('id')->translatedFormat('d F Y') }}</td>
                            <td>{{ $item['jam_masuk'] }}<br><span>{{ $item['status_masuk'] }}</span></td>
                            <td>{{ $item['jam_pulang'] }}<br><span>{{ $item['status_pulang'] }}</span></td>
                            <td class="photo-cell">
                                <img class="img-thumbnail" src="{{ $item['url_photo_masuk'] }}" alt="photo_sigma">
                                <img class="img-thumbnail" src="{{ $item['url_dokumentasi_masuk'] }}" alt="photo_timemark">
                            </td>
                            <td class="photo-cell">
                                <img class="img-thumbnail" src="{{ $item['url_photo_pulang'] }}" alt="photo_sigma">
                                <img class="img-thumbnail" src="{{ $item['url_dokumentasi_pulang'] }}" alt="photo_timemark">
                            </td>
                            <td class="{{ $item['bg'] }}">{{ $item['status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- <div class="ml-4" style="font-size:12px;">
            <p><u>Catatan:</u></p>
            <p>Total Waktu Telat (Menit):
                {{ $total_menit_telat ?? '0' }} menit dari
                {{ number_format($total_menit_kerja ?? 0, 0, ',', '.') }}
                menit jam kerja</p>
            <p>Total Waktu Telat (%): {{ $persentase_menit_telat ?? '0' }}% dari 100% jam kerja</p>
        </div> --}}

        <div class="mt-5 text-center" style="font-size: 14px;">
            <table class="table table-borderless">
                <tr>
                    <td style="width: 7cm;" class="text-center py-1">Pengawas Seksi {{ $user->tim->seksi->name ?? '-' }}</td>
                    <td style="width: auto;" class=" py-1"></td>
                    <td style="width: 7cm;" class="text-center py-1">Petugas PJLP</td>
                </tr>
                <tr>
                    <td class="text-center py-1">{{ $user->tim->seksi->unit_kerja->name ?? '-' }}</td>
                    <td class="py-1"></td>
                    <td class="text-center py-1">{{ $user->tim->name ?? '-' }}</td>
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
                        {{ $user->user->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="text-center py-1">NIP. {{ $pengawas->nip ?? 'N/A' }}</td>
                    <td class="py-1"></td>
                    <td class="text-center py-1">ID PJLP. {{ $user->user->nip ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        <div class="mt-5 text-center" style="font-size: 14px;">
            <table class="table table-borderless">
                <tr>
                    <td style="width: 7cm;" class="py-1"></td>
                    <td class="text-center py-1">@if(optional($kepala_seksi)->is_plt == true)Plt.@endif Kepala Seksi {{ $kepala_seksi?->seksi?->name ?? 'N/A' }}</td>
                    <td style="width: 7cm;" class="py-1"></td>
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
    </body>

</html>
