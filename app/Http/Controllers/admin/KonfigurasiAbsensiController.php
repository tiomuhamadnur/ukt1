<?php

namespace App\Http\Controllers\admin;

use App\DataTables\KonfigurasiAbsensiDataTable;
use App\Http\Controllers\Controller;
use App\Models\JenisAbsensi;
use App\Models\KonfigurasiAbsensi;
use App\Models\KonfigurasiAbsensiTim;
use App\Models\Tim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KonfigurasiAbsensiController extends Controller
{
    public function index(KonfigurasiAbsensiDataTable $dataTable)
    {
        $jenis_absensi = JenisAbsensi::all();
        $tim = Tim::orderBy('name', 'ASC')->get();

        return $dataTable->render('page.admin.konfigurasi_absensi.index', compact([
            'jenis_absensi',
            'tim',
        ]));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_absensi_id' => 'required|exists:jenis_absensi,id',
            'jam_masuk' => 'required|date_format:H:i:s',
            'jam_pulang' => 'required|date_format:H:i:s|after_or_equal:jam_masuk',
            'mulai_absen_masuk' => 'required|date_format:H:i:s',
            'selesai_absen_masuk' => 'required|date_format:H:i:s|after_or_equal:mulai_absen_masuk',
            'mulai_absen_pulang' => 'required|date_format:H:i:s',
            'selesai_absen_pulang' => 'required|date_format:H:i:s|after_or_equal:mulai_absen_pulang',
            'toleransi_masuk' => 'required|integer|min:0',
            'toleransi_pulang' => 'required|integer|min:0',
            'tim_ids' => 'required|array|min:1',
            'tim_ids.*' => 'required|integer|exists:tim,id',
        ]);

        DB::transaction(function () use ($validated) {

            $konfigurasiAbsensi = KonfigurasiAbsensi::updateOrCreate(
                [
                    'jenis_absensi_id' => $validated['jenis_absensi_id'],
                ],
                collect($validated)->except('tim_ids')->toArray()
            );

            foreach ($validated['tim_ids'] as $timId) {
                KonfigurasiAbsensiTim::updateOrCreate(
                    [
                        'konfigurasi_absensi_id' => $konfigurasiAbsensi->id,
                        'tim_id' => $timId,
                    ]
                );
            }
        });

        return redirect()
            ->route('konfigurasi-absensi.index')
            ->withNotify('Data Konfigurasi Absensi berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, KonfigurasiAbsensi $konfigurasi_absensi)
    {
        $validated = $request->validate([
            'jenis_absensi_id' => 'required|exists:jenis_absensi,id',
            'jam_masuk' => 'required|date_format:H:i:s',
            'jam_pulang' => 'required|date_format:H:i:s|after_or_equal:jam_masuk',
            'mulai_absen_masuk' => 'required|date_format:H:i:s',
            'selesai_absen_masuk' => 'required|date_format:H:i:s|after_or_equal:mulai_absen_masuk',
            'mulai_absen_pulang' => 'required|date_format:H:i:s',
            'selesai_absen_pulang' => 'required|date_format:H:i:s|after_or_equal:mulai_absen_pulang',
            'toleransi_masuk' => 'required|integer|min:0',
            'toleransi_pulang' => 'required|integer|min:0',
            'tim_ids' => 'required|array|min:1',
            'tim_ids.*' => 'required|integer|exists:tim,id',
        ]);

        DB::transaction(function () use ($validated, $konfigurasi_absensi) {

            // 1️⃣ update konfigurasi utama
            $konfigurasi_absensi->update(
                collect($validated)->except('tim_ids')->toArray()
            );

            // 2️⃣ hapus relasi tim yang tidak dipilih lagi
            KonfigurasiAbsensiTim::where('konfigurasi_absensi_id', $konfigurasi_absensi->id)
                ->whereNotIn('tim_id', $validated['tim_ids'])
                ->forceDelete();

            // 3️⃣ insert / keep relasi tim yang dipilih
            foreach ($validated['tim_ids'] as $timId) {
                KonfigurasiAbsensiTim::updateOrCreate(
                    [
                        'konfigurasi_absensi_id' => $konfigurasi_absensi->id,
                        'tim_id' => $timId,
                    ]
                );
            }
        });

        return redirect()
            ->route('konfigurasi-absensi.index')
            ->withNotify('Data Konfigurasi Absensi berhasil diperbarui.');
    }

    public function destroy(KonfigurasiAbsensi $konfigurasi_absensi)
    {
        $konfigurasi_absensi->delete();

        return back()->withNotify("Data Konfigurasi Absensi berhasil dihapus.");
    }
}
