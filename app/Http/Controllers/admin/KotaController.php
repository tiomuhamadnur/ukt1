<?php

namespace App\Http\Controllers\admin;

use App\DataTables\KotaDataTable;
use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KotaController extends Controller
{
    public function index(KotaDataTable $dataTable)
    {
        $provinsi = Provinsi::orderBy('name')->get();
        return $dataTable->render('page.admin.dataEssentials.kota.index', compact([
            'provinsi'
        ]));
    }

    public function store(Request $request, Kota $kota)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kota,code',
            'provinsi_id' => 'required|exists:provinsi,id',
        ]);

        $kota = Kota::updateOrCreate($rawData);

        return redirect()->route('kota.index')
            ->withNotify("Data Kota {$kota->name} berhasil ditambahkan.");
    }

    public function update(Request $request, Kota $kota)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kota,code,' . $kota->uuid . ',uuid',
            'provinsi_id' => 'required|exists:provinsi,id',
        ]);

        $kota->update($rawData);

        return redirect()->route('kota.index')
            ->withNotify("Data Kota {$kota->name} berhasil diperbarui.");
    }

    public function destroy(Kota $kota)
    {
        $kota->delete();

        return back()->withNotify("Data Kota {$kota->name} berhasil dihapus.");
    }
}
