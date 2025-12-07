<?php

namespace App\Http\Controllers\admin;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\Gender;
use App\Models\Jabatan;
use App\Models\Kelurahan;
use App\Models\Pulau;
use App\Models\Seksi;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable, Request $request)
    {
        $request->validate([
            'user_type_id' => 'nullable|exists:user_type,id',
        ]);

        $user_type_id = $request->user_type_id;

        $gender = Gender::all();
        $user_type = UserType::all();
        $jabatan = Jabatan::all();
        $kelurahan = Kelurahan::all();
        $pulau = Pulau::all();
        $roles = Role::all();
        $unit_kerja = UnitKerja::all();
        $seksi = Seksi::all();

        return $dataTable->with([
            'user_type_id' => $user_type_id,
        ])->render('page.admin.user.index', compact([
            'gender',
            'user_type',
            'jabatan',
            'kelurahan',
            'pulau',
            'roles',
            'unit_kerja',
            'seksi',
        ]));
    }

    public function profile()
    {
        return view('page.users.profile.index');
    }

    public function update_password()
    {
        return view('page.users.profile.update_password');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rawData = $request->validate([
            'name' => 'required|max:50|string',
            'email' => 'required|email|unique:users,email',
            'nik' => 'nullable|digits:16|integer|unique:users,nik',
            'nip' => 'nullable|max:50|string|unique:users,nip',
            'no_hp' => 'nullable|string|unique:users,no_hp',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:255',
            'is_plt' => 'nullable|boolean',
            'user_type_id' => 'required|exists:user_type,id',
            'gender_id' => 'required|exists:gender,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'jabatan_id' => 'required|exists:jabatan,id',
            'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
            'seksi_id' => 'nullable|exists:seksi,id',
        ]);

        $validated = $request->validate([
            'role_name' => 'required|string|exists:roles,name',
        ]);

        $defaultPassword = env('DEFAULT_PASSWORD', 'user123');

        $rawData['password'] = Hash::make($defaultPassword);

        $user = User::updateOrCreate($rawData, $rawData);

        $user->syncRoles([$validated['role_name']]);

        return back()->withNotify("Data user <b>{$user->name}</b> berhasil ditambahkan, dengan default password: <br> <b>{$defaultPassword}</b>");
    }

    public function show(User $user)
    {
        $defaultPassword = env('DEFAULT_PASSWORD', 'user123');

        $password = Hash::make($defaultPassword);

        $user->update([
            'password' => $password
        ]);

        return back()->withNotify("Data user <b>{$user->name}</b>, dengan Password: <br> <b>{$defaultPassword}</b>");
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        $rawData = $request->validate([
            'name' => 'required|max:50|string',
            'nik' => 'nullable|digits:16|integer|unique:users,nik,' . $user->uuid . ',uuid',
            'nip' => 'nullable|max:50|string|unique:users,nip,' . $user->uuid . ',uuid',
            'no_hp' => 'nullable|string|unique:users,no_hp,' . $user->uuid . ',uuid',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:255',
            'is_plt' => 'nullable|boolean',
            'user_type_id' => 'required|exists:user_type,id',
            'gender_id' => 'required|exists:gender,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'jabatan_id' => 'required|exists:jabatan,id',
            'role_name' => 'required|string|exists:roles,name',
            'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
            'seksi_id' => 'nullable|exists:seksi,id',
        ]);

        $user->update($rawData);

        $user->syncRoles([$rawData['role_name']]);

        return back()->withNotify("Data user <b>{$user->name}</b> berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $validate = $user->isBanned();

        if($validate){
            $user->unban();
            return back()->withNotify("User <b>{$user->name}</b> berhasil diaktifkan kembali.");
        } else {
            $user->ban();
            return back()->withNotify("User <b>{$user->name}</b> berhasil di-ban.");
        }
    }
}
