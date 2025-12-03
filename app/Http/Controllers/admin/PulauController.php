<?php

namespace App\Http\Controllers\admin;

use App\DataTables\PulauDataTable;
use App\Models\Pulau;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PulauController extends Controller
{
    public function index(PulauDataTable $dataTable)
    {
        $kelurahan = Kelurahan::orderBy('name')->get();
        return $dataTable->render('page.admin.dataEssentials.pulau.index', compact([
            'kelurahan'
        ]));
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:pulau,code',
            'kelurahan_id' => 'required|exists:kelurahan,id',
        ]);

        $pulau = Pulau::updateOrCreate($rawData, $rawData);

        return redirect()->route('pulau.index')
            ->withNotify("Data Pulau {$pulau->name} berhasil ditambahkan.");
    }

    public function update(Request $request, Pulau $pulau)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:pulau,code,' . $pulau->uuid . ',uuid',
            'kelurahan_id' => 'required|exists:kelurahan,id',
        ]);

        $pulau->update($rawData);

        return redirect()->route('pulau.index')
            ->withNotify("Data Pulau {$pulau->name} berhasil diperbarui.");
    }

    public function destroy(Pulau $pulau)
    {
        $pulau->delete();

        return back()->withNotify("Data Pulau {$pulau->name} berhasil dihapus.");
    }
}
