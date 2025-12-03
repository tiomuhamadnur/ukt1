<?php

namespace App\Http\Controllers\admin;

use App\DataTables\StatusCutiDataTable;
use App\Models\StatusCuti;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusCutiController extends Controller
{
    public function index(StatusCutiDataTable $dataTable)
    {
        return $dataTable->render('page.admin.dataEssentials.status_cuti.index');
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:status_cuti,code',
        ]);

        $status_cuti = StatusCuti::updateOrCreate($rawData, $rawData);

        return redirect()->route('status-cuti.index')
            ->withNotify("Data Status Cuti {$status_cuti->name} berhasil ditambahkan.");
    }

    public function update(Request $request, StatusCuti $status_cuti)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:status_cuti,code,' . $status_cuti->uuid . ',uuid',
        ]);

        $status_cuti->update($rawData);

        return redirect()->route('status-cuti.index')
            ->withNotify("Data Status Cuti {$status_cuti->name} berhasil diperbarui.");
    }

    public function destroy(StatusCuti $status_cuti)
    {
        $status_cuti->delete();

        return back()->withNotify("Data Status Cuti {$status_cuti->name} berhasil dihapus.");
    }
}
