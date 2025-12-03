<?php

namespace App\Http\Controllers\admin;

use App\DataTables\UserTypeDataTable;
use App\Models\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserTypeController extends Controller
{
    public function index(UserTypeDataTable $dataTable)
    {
        return $dataTable->render('page.admin.dataEssentials.user_type.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:user_type,code',
        ]);

        $user_type = UserType::create($request->only('name', 'code'));

        return redirect()->route('user-type.index')
            ->withNotify("Data User Type {$user_type->name} berhasil ditambahkan.");
    }

    public function update(Request $request, UserType $user_type)
    {
        $rawData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:user_type,code,' . $user_type->uuid . ',uuid',
        ]);

        $user_type->update($rawData);

        return redirect()->route('user-type.index')
            ->withNotify("Data User Type {$user_type->name} berhasil diperbarui.");
    }

    public function destroy(UserType $user_type)
    {
        $user_type->delete();

        return back()->withNotify("Data User Type {$user_type->name} berhasil dihapus.");
    }
}
