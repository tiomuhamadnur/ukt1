<?php

namespace App\Http\Controllers\admin;

use App\Models\Pulau;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PulauController extends Controller
{
    public function index()
    {
        $pulau = Pulau::with('kelurahan')->orderBy('name')->get();
        $kelurahan = Kelurahan::orderBy('name')->get();

        return view('page.admin.dataEssentials.pulau.index', compact('pulau', 'kelurahan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:pulau,code',
            'kelurahan_id' => 'required|exists:kelurahan,id',
        ]);

        Pulau::create($request->only('name', 'code', 'kelurahan_id'));

        return redirect()->route('pulau.index')
            ->withNotify('Data Pulau berhasil ditambahkan.');
    }

    public function update(Request $request, Pulau $pulau)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:pulau,code,' . $pulau->uuid . ',uuid',
            'kelurahan_id' => 'required|exists:kelurahan,id',
        ]);

        $pulau->update($request->only('name', 'code', 'kelurahan_id'));

        return redirect()->route('pulau.index')
            ->withNotify('Data Pulau berhasil diperbarui.');
    }

    public function destroy(Pulau $pulau)
    {
        try {
            $pulau->delete();
            return redirect()->route('pulau.index')
                ->withNotify('Pulau berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('pulau.index')
                ->withError($e->getMessage());
        }
    }
}
