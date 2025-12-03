<?php

namespace App\Http\Controllers\admin;

use App\DataTables\UnitKerjaDataTable;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnitKerjaController extends Controller
{
    public function index(UnitKerjaDataTable $dataTable)
    {
        return $dataTable->render('page.admin.dataEssentials.unit_kerja.index');
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:unit_kerja,code',
        ]);

        $unit_kerja = UnitKerja::updateOrCreate($rawData, $rawData);

        return redirect()->route('unit-kerja.index')
            ->withNotify("Data {$unit_kerja->name} berhasil ditambahkan.");
    }

    public function update(Request $request, UnitKerja $unit_kerja)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:unit_kerja,code,' . $unit_kerja->uuid . ',uuid',
        ]);

        $unit_kerja->update($rawData);

        return redirect()->route('unit-kerja.index')
            ->withNotify("Data {$unit_kerja->name} berhasil diperbarui.");
    }

    public function destroy(UnitKerja $unit_kerja)
    {
        $unit_kerja->delete();

        return back()->withNotify("Data {$unit_kerja->name} berhasil dihapus.");
    }
}
