<?php

namespace App\Http\Controllers\admin;

use App\DataTables\JenisAbsensiDataTable;
use App\Models\JenisAbsensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JenisAbsensiController extends Controller
{
    public function index(JenisAbsensiDataTable $dataTable)
    {
        return $dataTable->render('page.admin.dataEssentials.jenis_absensi.index');
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:jenis_absensi,code',
        ]);

        $jenis_absensi = JenisAbsensi::create($rawData);

        return redirect()->route('jenis-absensi.index')
            ->withNotify("Data {$jenis_absensi->name} berhasil ditambahkan.");
    }

    public function update(Request $request, JenisAbsensi $jenis_absensi)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:jenis_absensi,code,' . $jenis_absensi->uuid . ',uuid',
        ]);

        $jenis_absensi->update($request->only('name', 'code'));

        return redirect()->route('jenis-absensi.index')
            ->withNotify("Data {$jenis_absensi->name} berhasil diperbarui.");
    }

    public function destroy(JenisAbsensi $jenis_absensi)
    {
        $jenis_absensi->delete();

        return back()->withNotify("Data {$jenis_absensi->name} berhasil dihapus.");
    }
}
