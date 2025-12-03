<?php

namespace App\Http\Controllers\admin;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::orderBy('name')->get();
        return view('page.admin.dataEssentials.jabatan.index', compact('jabatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:jabatan,code',
        ]);

        Jabatan::create($request->only('name', 'code'));

        return redirect()->route('jabatan.index')
            ->withNotify('Data Jabatan berhasil ditambahkan.');
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:jabatan,code,' . $jabatan->uuid . ',uuid',
        ]);

        $jabatan->update($request->only('name', 'code'));

        return redirect()->route('jabatan.index')
            ->withNotify('Data Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();

        return redirect()->route('jabatan.index')
            ->withNotify('Data Jabatan berhasil dihapus.');
    }
}
