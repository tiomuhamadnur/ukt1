<?php

namespace App\Http\Controllers\admin;

use App\DataTables\JabatanDataTable;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JabatanController extends Controller
{
    public function index(JabatanDataTable $dataTable)
    {
        return $dataTable->render('page.admin.dataEssentials.jabatan.index');
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:jabatan,code',
        ]);

        $jabatan = Jabatan::create($request->only('name', 'code'));

        return redirect()->route('jabatan.index')
            ->withNotify("Data Jabatan {$jabatan->name} berhasil ditambahkan.");
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:jabatan,code,' . $jabatan->uuid . ',uuid',
        ]);

        $jabatan->update($rawData);

        return redirect()->route('jabatan.index')
            ->withNotify("Data Jabatan {$jabatan->name} berhasil diperbarui.");
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();

        return back()->withNotify("Data Jabatan {$jabatan->name} berhasil dihapus.");
    }
}
