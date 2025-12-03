<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\StatusAbsensi;
use App\Http\Controllers\Controller;

class StatusAbsensiController extends Controller
{
    public function index()
    {
        $status_absensi = StatusAbsensi::orderBy('name')->get();
        return view('page.admin.dataEssentials.status_absensi.index', compact('status_absensi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:status_absensi,code',
        ]);

        StatusAbsensi::create($request->only('name', 'code'));

        return redirect()->route('status-absensi.index')
            ->withNotify('Data Status Absensi berhasil ditambahkan.');
    }

    public function update(Request $request, StatusAbsensi $status_absensi)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:status_absensi,code,' . $status_absensi->uuid . ',uuid',
        ]);

        $status_absensi->update($request->only('name', 'code'));

        return redirect()->route('status-absensi.index')
            ->withNotify('Data Status Absensi berhasil diperbarui.');
    }

    public function destroy(StatusAbsensi $status_absensi)
    {
        $status_absensi->delete();

        return redirect()->route('status-absensi.index')
            ->withNotify('Data Status Absensi berhasil dihapus.');
    }
}