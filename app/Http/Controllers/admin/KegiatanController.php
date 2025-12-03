<?php

namespace App\Http\Controllers\admin;

use App\Models\Seksi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::with('seksi')->orderBy('name')->get();
        $seksi = Seksi::orderBy('name')->get();
        return view('page.admin.dataEssentials.kegiatan.index', compact('kegiatan', 'seksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kegiatan,code',
            'seksi_id' => 'required|exists:seksi,id',
        ]);

        Kegiatan::create($request->only('name', 'code', 'seksi_id'));

        return redirect()->route('kegiatan.index')
            ->withNotify('Data Kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:kegiatan,code,' . $kegiatan->uuid . ',uuid',
            'seksi_id' => 'required|exists:seksi,id',
        ]);

        $kegiatan->update($request->only('name', 'code', 'seksi_id'));

        return redirect()->route('kegiatan.index')
            ->withNotify('Data Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()->route('kegiatan.index')
            ->withNotify('Data Kegiatan berhasil dihapus.');
    }
}