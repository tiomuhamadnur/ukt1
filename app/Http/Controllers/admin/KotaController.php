<?php

namespace App\Http\Controllers\admin;

use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KotaController extends Controller
{
    public function index()
    {
        $kota = Kota::orderBy('name')->get();
        $provinsi = Provinsi::orderBy('name')->get();

        return view('page.admin.dataEssentials.kota.index', compact('kota', 'provinsi'));
    }

    public function store(Request $request, Kota $kota)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kota,code',
            'provinsi_id' => 'required|exists:provinsi,id',
        ]);

        Kota::create($request->only('name', 'code', 'provinsi_id'));

        return redirect()->route('kota.index')
            ->withNotify('Data Kota berhasil ditambahkan.');
    }

    public function update(Request $request, Kota $kota)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kota,code,' . $kota->uuid . ',uuid',
            'provinsi_id' => 'required|exists:provinsi,id',
        ]);

        $kota->update([
            'name' => $request->name,
            'code' => $request->code,
            'provinsi_id' => $request->provinsi_id,
        ]);

        return redirect()->route('kota.index')
            ->withNotify('Data Kota berhasil diperbarui.');
    }

    public function destroy(Kota $kota)
    {
        try {
            $kota->delete();
            return redirect()->route('kota.index')
                ->withNotify('Kota berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kota.index')
                ->withError($e->getMessage());
        }
    }
}
