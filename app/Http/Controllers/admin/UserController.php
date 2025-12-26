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
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        $roles = Role::when(
    !Auth::user()->hasRole('superadmin'),
            fn ($q) => $q->where('name', '!=', 'superadmin')
        )->get();
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

    public function update(Request $request, User $user, ImageUploadService $imageService)
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

        $request->validate([
            'photo' => 'nullable|file|image',
            'ttd' => 'nullable|file|image',
        ]);

        $user->update($rawData);

        $user->syncRoles([$rawData['role_name']]);

        // Photo Profile
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $imagePath = $imageService->uploadImage(
                $request->file('photo'),
                'user/profile/',
                null,
                250,
                60
            );

            $user->update([
                'photo' => $imagePath
            ]);
        }

        // Photo TTD
        if ($request->hasFile('ttd')) {
            if ($user->ttd && Storage::disk('public')->exists($user->ttd)) {
                Storage::disk('public')->delete($user->ttd);
            }

            $imagePath = $imageService->uploadImage(
                $request->file('ttd'),
                'user/ttd/',
                null,
                250,
                60
            );

            $user->update([
                'ttd' => $imagePath
            ]);
        }

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
            return back()->withNotify("User <b>{$user->name}</b> berhasil di-banned.");
        }
    }






    public function profile()
    {
        $user = Auth::user();
        $tahun = date('Y');
        return view('page.users.profile.index', compact([
            'user',
            'tahun',
        ]));
    }

    public function password()
    {
        return view('page.users.profile.update_password');
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required', 'confirmed', 'min:8'],
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak sesuai!',
            'new_password.min' => 'Password minimal 8 karakter',
        ]);


        $user = Auth::user();

        // cek password lama
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withError('Password lama tidak sesuai.');
        }

        // cek password baru tidak boleh sama dengan password lama
        if (Hash::check($request->new_password, $user->password)) {
            return back()->withError('Password baru tidak boleh sama dengan password lama.');
        }

        // update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        Auth::logout();

        return redirect()
            ->route('login')
            ->withErrors(['email' => 'Password berhasil diubah. Silakan login kembali.']);
    }

    public function update_photo($uuid, Request $request, ImageUploadService $imageService)
    {
        $request->validate([
            'photo' => 'required|file|image',
        ]);

        $user = User::where('uuid', $uuid)->firstOrFail();

        if ($request->hasFile('photo')) {

            // HAPUS FOTO LAMA (JIKA ADA)
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // UPLOAD FOTO BARU
            $imagePath = $imageService->uploadImage(
                $request->file('photo'),
                'user/profile/',
                null,
                250,
                60
            );

            // UPDATE DB
            $user->update([
                'photo' => $imagePath
            ]);
        }

        return back()->withNotify("Photo profil <strong>{$user->name}</strong> berhasil diperbarui.");
    }
}
