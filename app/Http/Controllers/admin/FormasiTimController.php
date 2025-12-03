<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormasiTim;
use App\Models\Tim;
use App\Models\Pulau;
use App\Models\User;
use Illuminate\Http\Request;

class FormasiTimController extends Controller
{
    public function index()
    {
        $formasi_tim = FormasiTim::with(['tim', 'pulau', 'user', 'koordinator'])->orderBy('name')->get();
        $tim = Tim::orderBy('name')->get();
        $pulau = Pulau::orderBy('name')->get();
        $user = User::orderBy('name')->get();

        $tahun_ini = date('Y');
        $tahun = [$tahun_ini, $tahun_ini + 1, $tahun_ini + 2];

        return view('page.admin.dataEssentials.formasi_tim.index', compact('formasi_tim', 'tim', 'pulau', 'user', 'tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:formasi_tim,code',
            'tim_id' => 'nullable|exists:tim,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'user_id' => 'nullable|exists:users,id',
            'koordinator_id' => 'nullable|exists:users,id',
            'periode' => 'nullable|digits:4|integer',
        ]);

        FormasiTim::create($request->only('name', 'code', 'tim_id', 'pulau_id', 'user_id', 'koordinator_id', 'periode'));

        return redirect()->route('formasi-tim.index')
            ->withNotify('Data Formasi Tim berhasil ditambahkan.');
    }

    public function update(Request $request, FormasiTim $formasi_tim)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:formasi_tim,code,' . $formasi_tim->uuid . ',uuid',
            'tim_id' => 'nullable|exists:tim,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'user_id' => 'nullable|exists:users,id',
            'koordinator_id' => 'nullable|exists:users,id',
            'periode' => 'nullable|digits:4|integer',
        ]);

        $formasi_tim->update($request->only('name', 'code', 'tim_id', 'pulau_id', 'user_id', 'koordinator_id', 'periode'));

        return redirect()->route('formasi-tim.index')
            ->withNotify('Data Formasi Tim berhasil diperbarui.');
    }

    public function destroy(FormasiTim $formasi_tim)
    {
        $formasi_tim->delete();

        return redirect()->route('formasi-tim.index')
            ->withNotify('Data Formasi Tim berhasil dihapus.');
    }
}
