<?php

namespace App\Http\Controllers\admin;

use App\Models\Gender;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenderController extends Controller
{
    public function index()
    {
        $genders = Gender::orderBy('name')->get();

        return view('page.admin.dataEssentials.gender.index', compact('genders'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:gender,code',
        ]);

        Gender::create($request->only('name', 'code'));

        return redirect()->route('gender.index')
            ->withNotify('Data Gender berhasil ditambahkan.');
    }

    public function update(Request $request, Gender $gender)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:gender,code,' . $gender->uuid . ',uuid',
        ]);

        $gender->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return redirect()->route('gender.index')
            ->withNotify('Data Gender berhasil diperbarui.');
    }

    public function destroy(Gender $gender)
    {
        $gender->delete();

        return redirect()->route('gender.index')
            ->withNotify('Data Gender berhasil dihapus.');
    }
}
