<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\FormasiTimDataTable;
use App\Http\Controllers\Controller;
use App\Models\FormasiTim;
use App\Models\Tim;
use App\Models\Pulau;
use App\Models\User;
use Illuminate\Http\Request;

class FormasiTimController extends Controller
{
    public function index(FormasiTimDataTable $dataTable, Request $request)
    {
        $request->validate([
            'periode' => 'nullable|date_format:Y'
        ]);

        $periode = $request->periode ?? date('Y');
        $tim = Tim::orderBy('name')->get();
        $pulau = Pulau::orderBy('name')->get();
        $user = User::where('jabatan_id', 5)->notBanned()->orderBy('name')->get();

        $tahun_ini = date('Y');
        $tahun = [$tahun_ini, $tahun_ini + 1, $tahun_ini + 2];
        return $dataTable->with([
            'periode' => $periode,
        ])->render('page.admin.dataEssentials.formasi_tim.index', compact([
            'tim',
            'pulau',
            'user',
            'periode',
            'tahun_ini',
            'tahun',
        ]));
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'tim_id' => 'required|exists:tim,id',
            'pulau_id' => 'required|exists:pulau,id',
            'user_id' => 'required|exists:users,id',
            // 'koordinator_id' => 'nullable|exists:users,id',
            'periode' => 'required|digits:4|integer',
        ]);

        $user = User::findOrFail($request->user_id);

        $formasi_tim = FormasiTim::updateOrCreate($rawData, $rawData);

        // ✅ cek apakah periode ini adalah yang terbaru untuk user
        $latestPeriode = FormasiTim::where('user_id', $user->id)->max('periode');

        if ($request->periode == $latestPeriode) {
            $user->update([
                'unit_kerja_id' => $formasi_tim->tim->seksi->unit_kerja_id,
                'seksi_id' => $formasi_tim->tim->seksi_id,
                'pulau_id' => $formasi_tim->pulau_id,
                'kelurahan_id' => $formasi_tim->pulau->kelurahan_id,
            ]);
        }

        $message = $formasi_tim->wasRecentlyCreated
            ? "Data baru formasi tim berhasil ditambahkan!"
            : "Data formasi tim untuk user <strong>{$user->name}</strong> di tahun {$request->periode} sudah ada dan berhasil diperbaharui!";

        return redirect()->route('formasi-tim.index')
            ->withNotify($message);
    }

    public function update(Request $request, FormasiTim $formasi_tim)
    {
        $rawData = $request->validate([
            'tim_id' => 'required|exists:tim,id',
            'pulau_id' => 'required|exists:pulau,id',
            'user_id' => 'required|exists:users,id',
            // 'koordinator_id' => 'nullable|exists:users,id',
            'periode' => 'required|digits:4|integer',
        ]);

        $formasi_tim->update($rawData);

        $user = User::findOrFail($request->user_id);

        // ✅ cek apakah periode ini adalah yang terbaru untuk user
        $latestPeriode = FormasiTim::where('user_id', $user->id)->max('periode');

        if ($request->periode == $latestPeriode) {
            $user->update([
                'unit_kerja_id' => $formasi_tim->tim->seksi->unit_kerja_id,
                'seksi_id' => $formasi_tim->tim->seksi_id,
                'pulau_id' => $formasi_tim->pulau_id,
                'kelurahan_id' => $formasi_tim->pulau->kelurahan_id,
            ]);
        }

        $message = $formasi_tim->wasRecentlyCreated
            ? "Data baru formasi tim berhasil ditambahkan!"
            : "Data formasi tim untuk user <strong>{$user->name}</strong> di tahun {$request->periode} sudah ada dan berhasil diperbaharui!";

        return redirect()->route('formasi-tim.index')
            ->withNotify($message);
    }

    public function destroy(FormasiTim $formasi_tim)
    {
        $formasi_tim->delete();

        return back()->withNotify('Data Formasi Tim berhasil dihapus.');
    }
}
