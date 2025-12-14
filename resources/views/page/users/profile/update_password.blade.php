@extends('layout.userbase')

@section('title-head')
    <title>
        User Profil | Ubah Password
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Profil</li>
            <li class="breadcrumb-item active">User Profil</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters justify-content-center">
        <div class="col-xl-4 col-lg-4 col-md-5 col-sm-6 col-12">
            <form action="{{ route('user.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card m-0">
                    <div class="card-body">
                        <h4 class="text-center"><u>Form Ubah Password</u></h4>
                        <div class="form-group">
                            <label for="old_password" class="required">Password Lama</label>
                            <input type="password" id="old_password" name="old_password" class="form-control"
                                placeholder="input password lama" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="new_password" class="required">Password Baru</label>
                            <input type="password" id="new_password" name="new_password" class="form-control"
                                placeholder="input password baru" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation" class="required">Konfirmasi Password Baru</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                class="form-control" placeholder="input konfirmasi password baru" required
                                autocomplete="off">
                        </div>
                        <div class="btn group-button mt-2">
                            <button type="submit" id="submit" name="submit"
                                class="btn btn-primary float-right ml-3">Submit</button>
                            <a href="{{ route('profile.index') }}" class="btn btn-dark">Batal</a>
                        </div>
                    </div>
            </form>
        </div>
    </div>
@endsection
