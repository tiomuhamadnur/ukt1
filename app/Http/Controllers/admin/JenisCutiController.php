<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use Illuminate\Http\Request;

class JenisCutiController extends Controller
{
    public function index()
    {
        $jenis_cuti = JenisCuti::orderBy('name')->get();
        return view('page.admin.dataEssentials.jenis_cuti.index', compact('jenis_cuti'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:jenis_cuti,code',
        ]);

        JenisCuti::create($request->only('name', 'code'));

        return redirect()->route('jenis-cuti.index')
            ->withNotify('Data Jenis Cuti berhasil ditambahkan.');
    }

    public function update(Request $request, JenisCuti $jenis_cuti)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:jenis_cuti,code,' . $jenis_cuti->uuid . ',uuid',
        ]);

        $jenis_cuti->update($request->only('name', 'code'));

        return redirect()->route('jenis-cuti.index')
            ->withNotify('Data Jenis Cuti berhasil diperbarui.');
    }

    public function destroy(JenisCuti $jenis_cuti)
    {
        $jenis_cuti->delete();

        return redirect()->route('jenis-cuti.index')
            ->withNotify('Data Jenis Cuti berhasil dihapus.');
    }
}

