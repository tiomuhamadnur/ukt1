<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Absensi - {{ $user->user->name }}</title>

        <style>
            @page {
                margin: 20mm 5mm 20mm 5mm;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 14px;
            }

            .text-center {
                text-align: center;
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

            .table-borderless td {
                border: none;
            }

            .img-thumbnail {
                border: 1px solid #ddd;
                padding: 2px;
                border-radius: 4px;
            }

            .header {
                position: fixed;
                top: -65px;
                left: 20px;
                right: 0px;
                height: 60px;
                text-align: left;
                line-height: 35px;
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
        </style>
    </head>

    <body>
        <div class="header">
            <img style="height: 60px" src="{{ public_path('assets/img/ukt1logo.png') }}" alt="logo-ukt1">
        </div>

        <div class="footer">
            <i>SIGMA UKT 1 - Dibuat {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</i>
        </div>

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
                        <td>{{ $user->user->jabatan->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Seksi</td>
                        <td>:</td>
                        <td>{{ $user->tim->seksi->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Pulau</td>
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

            <p class="text-center mt-3 text-uppercase font-weight-bold"><u>SUMMARY PRESENSI</u></p>

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
                        <td style="text-align:center">{{ $jumlah_hari_masuk ?? 'N/A' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td>Presensi Tidak Lengkap</td>
                        <td style="text-align:center">{{ $jumlah_hari_tidak_lengkap ?? 'N/A' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td>Presensi Tidak Tertib</td>
                        <td style="text-align:center">{{ $jumlah_hari_tidak_ok ?? 'N/A' }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td>Tidak Hadir</td>
                        <td style="text-align:center">{{ $jumlah_hari_tidak_masuk ?? 'N/A' }}</td>
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

            </div>

            <div class="ml-4" style="font-size:12px;">
                <p><u>Catatan:</u></p>
                <p>Total Waktu Telat (Menit):
                    {{ $total_menit_telat ?? 'N/A' }} menit dari
                    {{ number_format($total_menit_kerja ?? 0, 0, ',', '.') }}
                    menit jam kerja</p>
                <p>Total Waktu Telat (%): {{ $persentase_menit_telat ?? 'N/A' }}% dari 100% jam kerja</p>
            </div>

            <div class="footer">
                <i>SIGMA UKT 1 - Dibuat {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</i>
            </div>
        </div>

        <div class="page-break"></div>

        <div class="text-center">
            <p class="mt-3 mb-1 text-uppercase font-weight-bold"><u>DETAIL PRESENSI</u></p>
        </div>

        <div class="mt-3">
            <table class="table table-bordered text-center" style="font-size: 12px;">
                <thead>
                    <tr style="background-color: grey">
                        <th>No.</th>
                        <th>Hari</th>
                        <th>Tanggal</th>
                        <th>Jam Datang</th>
                        <th>Jam Pulang</th>
                        <th>Photo Datang</th>
                        <th>Photo Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datesInRange as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['hari'] }}</td>
                            <td>{{ $item['tanggal']->isoFormat('D MMMM Y') }}</td>
                            <td>{{ $item['jam_masuk'] }}<br><span>{{ $item['status_masuk'] }}</span></td>
                            <td>{{ $item['jam_pulang'] }}<br><span>{{ $item['status_pulang'] }}</span></td>
                            <td><img class="img-thumbnail" src="{{ $item['url_photo_masuk'] }}" alt="photo_datang"
                                    style="height: 70px"></td>
                            <td><img class="img-thumbnail" src="{{ $item['url_photo_pulang'] }}" alt="photo_pulang"
                                    style="height: 70px"></td>
                            <td class="{{ $item['bg'] }}">{{ $item['status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5 text-center" style="font-size: 14px;">
            <table class="table table-borderless">
                <tr>
                    <td class="text-center">@if(optional($kepala_seksi)->is_plt == true)Plt.@endif Kepala Seksi</td>
                    <td style="width: 4cm;"></td>
                    <td class="text-center">@if(optional($kepala_unit)->is_plt == true)Plt.@endif Kepala Unit</td>
                </tr>
                <tr>
                    <td class="text-center">{{ $kepala_seksi?->seksi?->name ?? 'N/A' }}</td>
                    <td></td>
                    <td class="text-center">{{ $kepala_unit?->unit_kerja?->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="height: 27mm;"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-center font-weight-bold" style="border-bottom:1pt solid black;">
                        {{ $kepala_seksi->name ?? 'N/A' }}</td>
                    <td></td>
                    <td class="text-center font-weight-bold" style="border-bottom:1pt solid black;">
                        {{ $kepala_unit->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="text-center">NIP. {{ $kepala_seksi->nip ?? 'N/A' }}</td>
                    <td></td>
                    <td class="text-center">NIP. {{ $kepala_unit->nip ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </body>

</html>
