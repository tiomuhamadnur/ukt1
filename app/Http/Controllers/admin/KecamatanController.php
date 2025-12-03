<?php

namespace App\Http\Controllers\admin;

use App\Models\Kota;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::with('kota')->orderBy('name')->get();

        $kota = Kota::orderBy('name')->get();

        return view('page.admin.dataEssentials.kecamatan.index', compact('kecamatan', 'kota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kecamatan,code',
            'kota_id' => 'required|exists:kota,id',
        ]);

        Kecamatan::create($request->only('name', 'code', 'kota_id'));

        return redirect()->route('kecamatan.index')
            ->withNotify('Data Kecamatan berhasil ditambahkan.');
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kecamatan,code,' . $kecamatan->uuid . ',uuid',
            'kota_id' => 'required|exists:kota,id',
        ]);

        $kecamatan->update($request->only('name', 'code', 'kota_id'));

        return redirect()->route('kecamatan.index')
            ->withNotify('Data Kecamatan berhasil diperbarui.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        try {
            $kecamatan->delete();
            return redirect()->route('kecamatan.index')
                ->withNotify('Kecamatan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kecamatan.index')
                ->withError($e->getMessage());
        }
    }
}
