<?php

namespace App\Http\Controllers\admin;

use App\Models\UnitKerja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnitKerjaController extends Controller
{
    public function index()
    {
        $unit_kerja = UnitKerja::orderBy('name')->get();
        return view('page.admin.dataEssentials.unit_kerja.index', compact('unit_kerja'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:unit_kerja,code',
        ]);

        UnitKerja::create($request->only('name', 'code'));

        return redirect()->route('unit-kerja.index')
            ->withNotify('Data Unit Kerja berhasil ditambahkan.');
    }

    public function update(Request $request, UnitKerja $unit_kerja)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:unit_kerja,code,' . $unit_kerja->uuid . ',uuid',
        ]);

        $unit_kerja->update($request->only('name', 'code'));

        return redirect()->route('unit-kerja.index')
            ->withNotify('Data Unit Kerja berhasil diperbarui.');
    }

    public function destroy(UnitKerja $unit_kerja)
    {
        try {
            $unit_kerja->delete();
            return redirect()->route('unit-kerja.index')
                ->withNotify('Unit Kerja berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('unit-kerja.index')
                ->withError($e->getMessage());
        }
    }
}
