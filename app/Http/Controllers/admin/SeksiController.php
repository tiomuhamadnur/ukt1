<?php

namespace App\Http\Controllers\admin;

use App\Models\Seksi;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SeksiController extends Controller
{
    public function index()
    {
        $seksi = Seksi::with('unit_kerja')->orderBy('name')->get();

        $unit_kerja = UnitKerja::orderBy('name')->get();

        return view('page.admin.dataEssentials.seksi.index', compact('seksi', 'unit_kerja'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:seksi,code',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        Seksi::create($request->only('name', 'code', 'unit_kerja_id'));

        return redirect()->route('seksi.index')
            ->withNotify('Data Seksi berhasil ditambahkan.');
    }

    public function update(Request $request, Seksi $seksi)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:seksi,code,' . $seksi->uuid . ',uuid',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $seksi->update($request->only('name', 'code', 'unit_kerja_id'));

        return redirect()->route('seksi.index')
            ->withNotify('Data Seksi berhasil diperbarui.');
    }

    public function destroy(Seksi $seksi)
    {
        try {
            $seksi->delete();
            return redirect()->route('seksi.index')
                ->withNotify('Seksi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('seksi.index')
                ->withError($e->getMessage());
        }
    }
}
