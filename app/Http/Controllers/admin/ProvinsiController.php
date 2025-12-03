<?php

namespace App\Http\Controllers\admin;

use App\DataTables\ProvinsiDataTable;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProvinsiController extends Controller
{
    public function index(ProvinsiDataTable $dataTable)
    {
        return $dataTable->render('page.admin.dataEssentials.provinsi.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:provinsi,code',
        ]);

        $provinsi = Provinsi::updateOrCreate($rawData, $rawData);

        return redirect()->route('provinsi.index')
                ->withNotify("Data Provinsi {$provinsi->name} berhasil ditambahkan.");
    }

    public function update(Request $request, Provinsi $provinsi)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:provinsi,code,' . $provinsi->uuid . ',uuid',
        ]);

        $provinsi->update($rawData);

        return redirect()->route('provinsi.index')
            ->withNotify("Data Provinsi {$provinsi->name} berhasil diperbarui.");
    }

    public function destroy(Provinsi $provinsi)
    {
        $provinsi->delete();

        return back()->withNotify("Data Provinsi {$provinsi->name} berhasil dihapus.");
    }
}
