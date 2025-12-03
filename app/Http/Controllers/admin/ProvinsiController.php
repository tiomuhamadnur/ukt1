<?php

namespace App\Http\Controllers\admin;

use App\Models\Provinsi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProvinsiController extends Controller
{

    public function index()
    {
        $provinsi = Provinsi::orderBy('name')->get();

        return view('page.admin.dataEssentials.provinsi.index', compact('provinsi'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:provinsi,code',
        ]);

        Provinsi::create($request->only('name', 'code'));

        return redirect()->route('provinsi.index')
            ->withNotify('Data Provinsi berhasil ditambahkan.');
    }

    public function update(Request $request, Provinsi $provinsi)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:provinsi,code,' . $provinsi->uuid . ',uuid',
        ]);

        $provinsi->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return redirect()->route('provinsi.index')
            ->withNotify('Data Provinsi berhasil diperbarui.');
    }

    public function destroy(Provinsi $provinsi)
    {
        try {
            $provinsi->delete();
            return redirect()->route('provinsi.index')
                ->withNotify('Provinsi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('provinsi.index')
                ->withError($e->getMessage());
        }
    }
}
