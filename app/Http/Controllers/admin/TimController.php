<?php

namespace App\Http\Controllers\admin;

use App\DataTables\TimDataTable;
use App\Models\Tim;
use App\Models\Seksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimController extends Controller
{
    public function index(TimDataTable $dataTable)
    {
        $seksi = Seksi::orderBy('name')->get();
        return $dataTable->render('page.admin.dataEssentials.tim.index', compact([
            'seksi'
        ]));
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:tim,code',
            'seksi_id' => 'required|exists:seksi,id',
        ]);

        $tim = Tim::updateOrCreate($rawData, $rawData);

        return redirect()->route('tim.index')
            ->withNotify("Data Tim {$tim->name} berhasil ditambahkan.");
    }

    public function update(Request $request, Tim $tim)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:tim,code,' . $tim->uuid . ',uuid',
            'seksi_id' => 'required|exists:seksi,id',
        ]);

        $tim->update($rawData);

        return redirect()->route('tim.index')
            ->withNotify("Data Tim {$tim->name} berhasil diperbarui.");
    }

    public function destroy(Tim $tim)
    {
        $tim->delete();

        return back()->withNotify("Data Tim {$tim->name} berhasil dihapus.");
    }
}
