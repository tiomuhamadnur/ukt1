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
    public function index(FormasiTimDataTable $dataTable)
    {
        $tim = Tim::orderBy('name')->get();
        $pulau = Pulau::orderBy('name')->get();
        $user = User::orderBy('name')->get();

        $tahun_ini = date('Y');
        $tahun = [$tahun_ini, $tahun_ini + 1, $tahun_ini + 2];
        return $dataTable->render('page.admin.dataEssentials.formasi_tim.index', compact([
            'tim',
            'pulau',
            'user',
            'tahun',
        ]));
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'tim_id' => 'nullable|exists:tim,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'user_id' => 'nullable|exists:users,id',
            'koordinator_id' => 'nullable|exists:users,id',
            'periode' => 'required|digits:4|integer',
        ]);

        FormasiTim::updateOrCreate($rawData, $rawData);

        return redirect()->route('formasi-tim.index')
            ->withNotify('Data Formasi Tim berhasil ditambahkan.');
    }

    public function update(Request $request, FormasiTim $formasi_tim)
    {
        $rawData = $request->validate([
            'tim_id' => 'nullable|exists:tim,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'user_id' => 'nullable|exists:users,id',
            'koordinator_id' => 'nullable|exists:users,id',
            'periode' => 'required|digits:4|integer',
        ]);

        $formasi_tim->update($rawData);

        return redirect()->route('formasi-tim.index')
            ->withNotify('Data Formasi Tim berhasil diperbarui.');
    }

    public function destroy(FormasiTim $formasi_tim)
    {
        $formasi_tim->delete();

        return back()->withNotify('Data Formasi Tim berhasil dihapus.');
    }
}
