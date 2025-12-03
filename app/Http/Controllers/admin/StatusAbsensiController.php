<?php

namespace App\Http\Controllers\admin;

use App\DataTables\StatusAbsensiDataTable;
use Illuminate\Http\Request;
use App\Models\StatusAbsensi;
use App\Http\Controllers\Controller;

class StatusAbsensiController extends Controller
{
    public function index(StatusAbsensiDataTable $dataTable)
    {
        return $dataTable->render('page.admin.dataEssentials.status_absensi.index');
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:status_absensi,code',
        ]);

        $status_absensi = StatusAbsensi::updateOrCreate($rawData, $rawData);

        return redirect()->route('status-absensi.index')
            ->withNotify("Data Status {$status_absensi->name} berhasil ditambahkan.");
    }

    public function update(Request $request, StatusAbsensi $status_absensi)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:status_absensi,code,' . $status_absensi->uuid . ',uuid',
        ]);

        $status_absensi->update($rawData);

        return redirect()->route('status-absensi.index')
            ->withNotify("Data Status {$status_absensi->name} berhasil diperbarui.");
    }

    public function destroy(StatusAbsensi $status_absensi)
    {
        $status_absensi->delete();

        return back()->withNotify("Data Status {$status_absensi->name} berhasil dihapus.");
    }
}
