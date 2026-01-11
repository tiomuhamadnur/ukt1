<?php

namespace App\Http\Controllers\admin;

use App\DataTables\KegiatanDataTable;
use App\Models\Seksi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tim;

class KegiatanController extends Controller
{
    public function index(KegiatanDataTable $dataTable)
    {
        $seksi = Seksi::orderBy('name')->get();
        $tim = Tim::orderBy('name')->get();
        return $dataTable->render('page.admin.dataEssentials.kegiatan.index', compact([
            'seksi',
            'tim',
        ]));
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kegiatan,code',
            'seksi_id' => 'nullable|exists:seksi,id',
            'tim_id' => 'required|exists:tim,id',
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
            'seksi_id' => 'nullable|exists:seksi,id',
            'tim_id' => 'required|exists:tim,id',
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
