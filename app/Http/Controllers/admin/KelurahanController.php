<?php

namespace App\Http\Controllers\admin;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KelurahanController extends Controller
{
    public function index()
    {
        $kelurahan = Kelurahan::with('kecamatan')->orderBy('name')->get();
        $kecamatan = Kecamatan::orderBy('name')->get();

        return view('page.admin.dataEssentials.kelurahan.index', compact('kelurahan', 'kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kelurahan,code',
            'kecamatan_id' => 'required|exists:kecamatan,id',
        ]);

        Kelurahan::create($request->only('name', 'code', 'kecamatan_id'));

        return redirect()->route('kelurahan.index')
            ->withNotify('Data Kelurahan berhasil ditambahkan.');
    }

    public function update(Request $request, Kelurahan $kelurahan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kelurahan,code,' . $kelurahan->uuid . ',uuid',
            'kecamatan_id' => 'required|exists:kecamatan,id',
        ]);

        $kelurahan->update($request->only('name', 'code', 'kecamatan_id'));

        return redirect()->route('kelurahan.index')
            ->withNotify('Data Kelurahan berhasil diperbarui.');
    }

    public function destroy(Kelurahan $kelurahan)
    {
        try {
            $kelurahan->delete();
            return redirect()->route('kelurahan.index')
                ->withNotify('Kelurahan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kelurahan.index')
                ->withError($e->getMessage());
        }
    }
}
