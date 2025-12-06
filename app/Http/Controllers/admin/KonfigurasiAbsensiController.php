<?php

namespace App\Http\Controllers\admin;

use App\DataTables\KonfigurasiAbsensiDataTable;
use App\Http\Controllers\Controller;
use App\Models\JenisAbsensi;
use App\Models\KonfigurasiAbsensi;
use Illuminate\Http\Request;

class KonfigurasiAbsensiController extends Controller
{
    public function index(KonfigurasiAbsensiDataTable $dataTable)
    {
        $jenis_absensi = JenisAbsensi::orderBy('name')->get();

        return $dataTable->render('page.admin.konfigurasi_absensi.index', compact([
            'jenis_absensi',
        ]));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'jenis_absensi_id' => 'required|exists:jenis_absensi,id',
            'jam_masuk' => 'required|date_format:H:i:s',
            'jam_pulang' => 'required|date_format:H:i:s|after_or_equal:jam_masuk',
            'mulai_absen_masuk' => 'required|date_format:H:i:s',
            'selesai_absen_masuk' => 'required|date_format:H:i:s|after_or_equal:mulai_absen_masuk',
            'mulai_absen_pulang' => 'required|date_format:H:i:s',
            'selesai_absen_pulang' => 'required|date_format:H:i:s|after_or_equal:mulai_absen_pulang',
            'toleransi_masuk' => 'required|min:0|integer',
            'toleransi_pulang' => 'required|min:0|integer',
        ]);

        KonfigurasiAbsensi::updateOrCreate($rawData, $rawData);

        return redirect()->route('konfigurasi-absensi.index')
            ->withNotify("Data Konfigurasi Absensi berhasil ditambahkan.");
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
        $rawData = $request->validate([
            'jenis_absensi_id' => 'required|exists:jenis_absensi,id',
            'jam_masuk' => 'required|date_format:H:i:s',
            'jam_pulang' => 'required|date_format:H:i:s|after_or_equal:jam_masuk',
            'mulai_absen_masuk' => 'required|date_format:H:i:s',
            'selesai_absen_masuk' => 'required|date_format:H:i:s|after_or_equal:mulai_absen_masuk',
            'mulai_absen_pulang' => 'required|date_format:H:i:s',
            'selesai_absen_pulang' => 'required|date_format:H:i:s|after_or_equal:mulai_absen_pulang',
            'toleransi_masuk' => 'required|min:0|integer',
            'toleransi_pulang' => 'required|min:0|integer',
        ]);

        $konfigurasi_absensi->update($rawData);

        return redirect()->route('konfigurasi-absensi.index')
            ->withNotify("Data Konfigurasi Absensi berhasil diperbarui.");
    }

    public function destroy(KonfigurasiAbsensi $konfigurasi_absensi)
    {
        $konfigurasi_absensi->delete();

        return back()->withNotify("Data Konfigurasi Absensi berhasil dihapus.");
    }
}
