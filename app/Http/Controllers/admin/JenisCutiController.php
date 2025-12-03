<?php

namespace App\Http\Controllers\admin;

use App\DataTables\JenisCutiDataTable;
use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use Illuminate\Http\Request;

class JenisCutiController extends Controller
{
    public function index(JenisCutiDataTable $dataTable)
    {
        return $dataTable->render('page.admin.dataEssentials.jenis_cuti.index');
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:jenis_cuti,code',
        ]);

        $jenis_cuti = JenisCuti::updateOrCreate($rawData, $rawData);

        return redirect()->route('jenis-cuti.index')
            ->withNotify("Data Jenis Cuti {$jenis_cuti->name} berhasil ditambahkan.");
    }

    public function update(Request $request, JenisCuti $jenis_cuti)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:jenis_cuti,code,' . $jenis_cuti->uuid . ',uuid',
        ]);

        $jenis_cuti->update($request->only('name', 'code'));

        return redirect()->route('jenis-cuti.index')
            ->withNotify("Data Jenis Cuti {$jenis_cuti->name} berhasil diperbarui.");
    }

    public function destroy(JenisCuti $jenis_cuti)
    {
        $jenis_cuti->delete();

        return back()->withNotify("Data Jenis Cuti {$jenis_cuti->name} berhasil dihapus.");
    }
}

