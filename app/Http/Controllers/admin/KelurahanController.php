<?php

namespace App\Http\Controllers\admin;

use App\DataTables\KelurahanDataTable;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KelurahanController extends Controller
{
    public function index(KelurahanDataTable $dataTable)
    {
        $kecamatan = Kecamatan::orderBy('name')->get();
        return $dataTable->render('page.admin.dataEssentials.kelurahan.index', compact([
            'kecamatan'
        ]));
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kelurahan,code',
            'kecamatan_id' => 'required|exists:kecamatan,id',
        ]);

        $kelurahan = Kelurahan::updateOrCreate($rawData, $rawData);

        return redirect()->route('kelurahan.index')
            ->withNotify("Data Kelurahan {$kelurahan->name} berhasil ditambahkan.");
    }

    public function update(Request $request, Kelurahan $kelurahan)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kelurahan,code,' . $kelurahan->uuid . ',uuid',
            'kecamatan_id' => 'required|exists:kecamatan,id',
        ]);

        $kelurahan->update($rawData);

        return redirect()->route('kelurahan.index')
            ->withNotify("Data Kelurahan {$kelurahan->name} berhasil diperbarui.");
    }

    public function destroy(Kelurahan $kelurahan)
    {
        $kelurahan->delete();

        return back()->withNotify("Data Kelurahan {$kelurahan->name} berhasil dihapus.");
    }
}
