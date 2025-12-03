<?php

namespace App\Http\Controllers\admin;

use App\Models\Tim;
use App\Models\Seksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimController extends Controller
{
    public function index()
    {
        $tim = Tim::with('seksi')->orderBy('name')->get();
        $seksi = Seksi::orderBy('name')->get();

        return view('page.admin.dataEssentials.tim.index', compact('tim', 'seksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:tim,code',
            'seksi_id' => 'required|exists:seksi,id',
        ]);

        Tim::create($request->only('name', 'code', 'seksi_id'));

        return redirect()->route('tim.index')
            ->withNotify('Data Tim berhasil ditambahkan.');
    }

    public function update(Request $request, Tim $tim)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:tim,code,' . $tim->uuid . ',uuid',
            'seksi_id' => 'required|exists:seksi,id',
        ]);

        $tim->update($request->only('name', 'code', 'seksi_id'));

        return redirect()->route('tim.index')
            ->withNotify('Data Tim berhasil diperbarui.');
    }

    public function destroy(Tim $tim)
    {
        $tim->delete();

        return redirect()->route('tim.index')
            ->withNotify('Data Tim berhasil dihapus.');
    }
}
