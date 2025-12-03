<?php

namespace App\Http\Controllers\admin;

use App\Models\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserTypeController extends Controller
{
    public function index()
    {
        $user_type = UserType::orderBy('name')->get();

        return view('page.admin.dataEssentials.user_type.index', compact('user_type'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:user_type,code',
        ]);

        UserType::create($request->only('name', 'code'));

        return redirect()->route('user-type.index')
            ->withNotify('Data User Type berhasil ditambahkan.');
    }

    public function update(Request $request, UserType $user_type)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:user_type,code,' . $user_type->uuid . ',uuid',
        ]);

        $user_type->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return redirect()->route('user-type.index')
            ->withNotify('Data User Type berhasil diperbarui.');
    }

    public function destroy(UserType $user_type)
    {
        $user_type->delete();

        return redirect()->route('user-type.index')
            ->withNotify('Data User Type berhasil dihapus.');
    }
}