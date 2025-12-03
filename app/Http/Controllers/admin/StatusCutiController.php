<?php

namespace App\Http\Controllers\admin;

use App\Models\StatusCuti;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusCutiController extends Controller
{
    public function index()
    {
        $status_cuti = StatusCuti::orderBy('name')->get();
        return view('page.admin.dataEssentials.status_cuti.index', compact('status_cuti'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:status_cuti,code',
        ]);

        StatusCuti::create($request->only('name', 'code'));

        return redirect()->route('status-cuti.index')
            ->withNotify('Data Status Cuti berhasil ditambahkan.');
    }

    public function update(Request $request, StatusCuti $status_cuti)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:status_cuti,code,' . $status_cuti->uuid . ',uuid',
        ]);

        $status_cuti->update($request->only('name', 'code'));

        return redirect()->route('status-cuti.index')
            ->withNotify('Data Status Cuti berhasil diperbarui.');
    }

    public function destroy(StatusCuti $status_cuti)
    {
        $status_cuti->delete();

        return redirect()->route('status-cuti.index')
            ->withNotify('Data Status Cuti berhasil dihapus.');
    }
}