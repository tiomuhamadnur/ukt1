<?php

namespace App\Http\Controllers\admin;

use App\Models\JenisAbsensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JenisAbsensiController extends Controller
{
    public function index()
    {
        $jenis_absensi = JenisAbsensi::orderBy('name')->get();
        return view('page.admin.dataEssentials.jenis_absensi.index', compact('jenis_absensi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:jenis_absensi,code',
        ]);

        JenisAbsensi::create($request->only('name', 'code'));

        return redirect()->route('jenis-absensi.index')
            ->withNotify('Data Jenis Absensi berhasil ditambahkan.');
    }

    public function update(Request $request, JenisAbsensi $jenis_absensi)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:jenis_absensi,code,' . $jenis_absensi->uuid . ',uuid',
        ]);

        $jenis_absensi->update($request->only('name', 'code'));

        return redirect()->route('jenis-absensi.index')
            ->withNotify('Data Jenis Absensi berhasil diperbarui.');
    }

    public function destroy(JenisAbsensi $jenis_absensi)
    {
        $jenis_absensi->delete();

        return redirect()->route('jenis-absensi.index')
            ->withNotify('Data Jenis Absensi berhasil dihapus.');
    }
}
