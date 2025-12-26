<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Absensi</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    No.
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Tanggal
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Nama
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    NIP
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Jabatan
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Pulau
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Seksi
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Jam Masuk
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Telat Masuk (Menit)
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Status Masuk
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Catatan Masuk
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Jam Pulang
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Cepat Pulang (Menit)
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Status Pulang
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Catatan Pulang
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Status
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Photo Masuk
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Photo Pulang
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Lokasi Masuk
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Lokasi Pulang
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Photo Timemark Masuk
                </th>
                <th style="border: 3px; background-color:gray; font-weight:bolder; text-align:center;">
                    Photo Timemark Pulang
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensi as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->formatted_tanggal }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ $item->user->nip ?? '-' }}</td>
                    <td>{{ $item->user->jabatan->name ?? '-' }}</td>
                    <td>{{ $item->user->formasi_tim->pulau->name ?? '-' }}</td>
                    <td>{{ $item->user->formasi_tim->tim->seksi->name ?? '-' }}</td>
                    <td>{{ $item->jam_masuk }}</td>
                    <td>{{ $item->telat_masuk }}</td>
                    <td>{{ $item->status_masuk }}</td>
                    <td>{{ $item->catatan_masuk }}</td>
                    <td>{{ $item->jam_pulang }}</td>
                    <td>{{ $item->telat_pulang }}</td>
                    <td>{{ $item->status_pulang }}</td>
                    <td>{{ $item->catatan_pulang }}</td>
                    <td>{{ $item->status_absensi->name ?? '-' }}</td>
                    <td>
                        {{ $item->photo_masuk ? asset('storage/' . $item->photo_masuk) : '' }}
                    </td>
                    <td>
                        {{ $item->photo_pulang ? asset('storage/' . $item->photo_pulang) : '' }}
                    </td>
                    <td>{{ $item->lokasi_masuk }}</td>
                    <td>{{ $item->lokasi_pulang }}</td>
                    <td>
                        {{ $item->dokumentasi_masuk ? asset('storage/' . $item->dokumentasi_masuk) : '' }}
                    </td>
                    <td>
                        {{ $item->dokumentasi_pulang ? asset('storage/' . $item->dokumentasi_pulang) : '' }}
                    </td>
                </tr>
            @endforeach
            @if ($absensi->count() == 0)
                <tr>
                    <td style="text-align: center;" colspan="22">
                        Tidak ada data.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>
