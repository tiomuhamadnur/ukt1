<?php

namespace App\Http\Controllers\admin;

use App\DataTables\GenderDataTable;
use App\Models\Gender;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenderController extends Controller
{
    public function index(GenderDataTable $dataTable)
    {
        return $dataTable->render('page.admin.dataEssentials.gender.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:gender,code',
        ]);

        $gender = Gender::updateOrCreate($rawData, $rawData);

        return redirect()->route('gender.index')
            ->withNotify("Data Gender {$gender->name} berhasil ditambahkan.");
    }

    public function update(Request $request, Gender $gender)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:gender,code,' . $gender->uuid . ',uuid',
        ]);

        $gender->update($rawData);

        return redirect()->route('gender.index')
            ->withNotify("Data Gender {$gender->name} berhasil diperbarui.");
    }

    public function destroy(Gender $gender)
    {
        $gender->delete();

        return back()->withNotify("Data Gender {$gender->name} berhasil dihapus.");
    }
}
