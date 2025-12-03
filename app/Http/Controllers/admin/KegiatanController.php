<?php

namespace App\Http\Controllers\admin;

use App\DataTables\KegiatanDataTable;
use App\Models\Seksi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KegiatanController extends Controller
{
    public function index(KegiatanDataTable $dataTable)
    {
        $seksi = Seksi::orderBy('name')->get();
        return $dataTable->render('page.admin.dataEssentials.kegiatan.index', compact([
            'seksi'
        ]));
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kegiatan,code',
            'seksi_id' => 'required|exists:seksi,id',
        ]);

        Kegiatan::updateOrCreate($rawData, $rawData);

        return redirect()->route('kegiatan.index')
            ->withNotify('Data Kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kegiatan,code,' . $kegiatan->uuid . ',uuid',
            'seksi_id' => 'required|exists:seksi,id',
        ]);

        $kegiatan->update($rawData);

        return redirect()->route('kegiatan.index')
            ->withNotify('Data Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return back()->withNotify('Data Kegiatan berhasil dihapus.');
    }
}
