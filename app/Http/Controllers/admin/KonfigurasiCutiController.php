<?php

namespace App\Http\Controllers\admin;

use App\DataTables\KonfigurasiCutiDataTable;
use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use App\Models\KonfigurasiCuti;
use App\Models\User;
use Illuminate\Http\Request;

class KonfigurasiCutiController extends Controller
{
    public function index(KonfigurasiCutiDataTable $dataTable, Request $request)
    {
        $request->validate([
            'periode' => 'nullable|digits:4|integer'
        ]);

        $periode = $request->periode ?? date('Y');

        $jenis_cuti = JenisCuti::orderBy('name')->get();
        $user = User::notBanned()->orderBy('name')->get();
        $tahun_ini = date('Y');
        $tahun = [$tahun_ini, $tahun_ini + 1, $tahun_ini + 2];

        return $dataTable->with([
            'periode' => $periode,
        ])->render('page.admin.konfigurasi_cuti.index', compact([
            'jenis_cuti',
            'user',
            'tahun',
            'periode',
        ]));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'periode' => 'required|digits:4|integer',
            'jenis_cuti_id' => 'required|exists:jenis_cuti,id',
            'user_id' => 'required|exists:users,id',
            'jumlah_awal' => 'required|min:1|integer',
        ]);

        $data = KonfigurasiCuti::updateOrCreate($rawData, $rawData);

        return redirect()->route('konfigurasi-cuti.index')
            ->withNotify("Data Cuti {$data->user->name} tahun {$data->periode} berhasil ditambahkan.");
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, KonfigurasiCuti $konfigurasi_cuti)
    {
        $rawData = $request->validate([
            'periode' => 'required|digits:4|integer',
            'jenis_cuti_id' => 'required|exists:jenis_cuti,id',
            'user_id' => 'required|exists:users,id',
            'jumlah_awal' => 'required|min:1|integer',
        ]);

        $konfigurasi_cuti->update($rawData);

        return redirect()->route('konfigurasi-cuti.index')
            ->withNotify("Data Cuti {$konfigurasi_cuti->user->name} tahun {$konfigurasi_cuti->periode} berhasil diperbarui.");
    }

    public function destroy(KonfigurasiCuti $konfigurasi_cuti)
    {
        $konfigurasi_cuti->delete();

        return back()->withNotify("Data Cuti {$konfigurasi_cuti->user->name} tahun {$konfigurasi_cuti->periode} berhasil dihapus.");
    }
}
