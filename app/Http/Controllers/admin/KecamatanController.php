<?php

namespace App\Http\Controllers\admin;

use App\DataTables\KecamatanDataTable;
use App\Models\Kota;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KecamatanController extends Controller
{
    public function index(KecamatanDataTable $dataTable)
    {
        $kota = Kota::orderBy('name')->get();
        return $dataTable->render('page.admin.dataEssentials.kecamatan.index', compact([
            'kota'
        ]));
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kecamatan,code',
            'kota_id' => 'required|exists:kota,id',
        ]);

        $kecamatan = Kecamatan::updateOrCreate($rawData, $rawData);

        return redirect()->route('kecamatan.index')
            ->withNotify("Data Kecamatan {$kecamatan->name} berhasil ditambahkan.");
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kecamatan,code,' . $kecamatan->uuid . ',uuid',
            'kota_id' => 'required|exists:kota,id',
        ]);

        $kecamatan->update($rawData);

        return redirect()->route('kecamatan.index')
            ->withNotify("Data Kecamatan {$kecamatan->name} berhasil diperbarui.");
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();

        return back()->withNotify("Data Kecamatan {$kecamatan->name} berhasil dihapus.");
    }
}
