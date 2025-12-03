<?php

namespace App\Http\Controllers\admin;

use App\DataTables\SeksiDataTable;
use App\Models\Seksi;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SeksiController extends Controller
{
    public function index(SeksiDataTable $dataTable)
    {
        $unit_kerja = UnitKerja::orderBy('name')->get();
        return $dataTable->render('page.admin.dataEssentials.seksi.index', compact([
            'unit_kerja'
        ]));
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:seksi,code',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $seksi = Seksi::updateOrCreate($rawData, $rawData);

        return redirect()->route('seksi.index')
            ->withNotify("Data Seksi {$seksi->name} berhasil ditambahkan.");
    }

    public function update(Request $request, Seksi $seksi)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:seksi,code,' . $seksi->uuid . ',uuid',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $seksi->update($rawData);

        return redirect()->route('seksi.index')
            ->withNotify("Data Seksi {$seksi->name} berhasil diperbarui.");
    }

    public function destroy(Seksi $seksi)
    {
        $seksi->delete();

        return back()->withNotify("Data Seksi {$seksi->name} berhasil dihapus.");
    }
}
