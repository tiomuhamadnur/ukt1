@extends('layout.base')

@section('title-head')
    <title>
        Masterdata | Data Users
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Masterdata</li>
            <li class="breadcrumb-item">Data Essentials</li>
            <li class="breadcrumb-item active">Daftar User</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-3 text-left">
                            <a href="{{ route('dataEssentials.index') }}" class="btn btn-outline-primary"><i
                                    class="fa fa-arrow-left"></i> Kembali</a>
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#tambahData">Tambah
                                Data</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        {{ $dataTable->table([
                            'class' => 'table table-bordered table-striped',
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="tambahData" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data User</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Nama Lengkap:</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label class="required">Email:</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label class="required">Gender:</label>
                            <select name="gender_id" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Gender --</option>
                                @foreach ($gender as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Jabatan:</label>
                            <select name="jabatan_id" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Jabatan --</option>
                                @foreach ($jabatan as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">User Type:</label>
                            <select name="user_type_id" class="form-control" required>
                                <option value="" disabled selected>-- Pilih User Type --</option>
                                @foreach ($user_type as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Pulau:</label>
                            <select name="pulau_id" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Pulau --</option>
                                @foreach ($pulau as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">NIP:</label>
                            <input type="text" class="form-control" name="nip" required>
                        </div>
                        <div class="form-group">
                            <label class="optional">NIK KTP:</label>
                            <input type="number" min="1" class="form-control" name="nik">
                        </div>
                        <div class="form-group">
                            <label class="optional">Tempat Lahir:</label>
                            <input type="text" class="form-control" name="tempat_lahir">
                        </div>
                        <div class="form-group">
                            <label class="optional">Tanggal Lahir:</label>
                            <input type="date" class="form-control" name="tanggal_lahir">
                        </div>
                        <div class="form-group">
                            <label class="optional">Alamat Lengkap:</label>
                            <textarea name="alamat" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="optional">Bio:</label>
                            <textarea name="bio" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="required">Apakah PLT?:</label>
                            <select name="is_plt" class="form-control" required>
                                <option value="" disabled selected>-- Apakah akun ini PLT? --</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer custom">
                        <div class="left-side">
                            <button type="button" class="btn btn-link danger" data-dismiss="modal">Batal</button>
                        </div>
                        <div class="divider"></div>
                        <div class="right-side">
                            <button type="submit" class="btn btn-link success">Buat Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Tambah User -->

    <!-- Modal Edit User -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data User</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="editForm" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Nama Lengkap:</label>
                            <input type="text" class="form-control" name="name" id="name_edit" required>
                        </div>
                        <div class="form-group">
                            <label class="required">Email:</label>
                            <input type="email" class="form-control" id="email_edit" required readonly>
                        </div>
                        <div class="form-group">
                            <label class="required">Gender:</label>
                            <select name="gender_id" id="gender_id_edit" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Gender --</option>
                                @foreach ($gender as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Jabatan:</label>
                            <select name="jabatan_id" id="jabatan_id_edit" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Jabatan --</option>
                                @foreach ($jabatan as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">User Type:</label>
                            <select name="user_type_id" id="user_type_id_edit" class="form-control" required>
                                <option value="" disabled selected>-- Pilih User Type --</option>
                                @foreach ($user_type as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Pulau:</label>
                            <select name="pulau_id" id="pulau_id_edit" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Pulau --</option>
                                @foreach ($pulau as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">NIP:</label>
                            <input type="text" class="form-control" name="nip" id="nip_edit" required>
                        </div>
                        <div class="form-group">
                            <label class="optional">NIK KTP:</label>
                            <input type="number" min="1" class="form-control" name="nik" id="nik_edit">
                        </div>
                        <div class="form-group">
                            <label class="optional">Tempat Lahir:</label>
                            <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir_edit">
                        </div>
                        <div class="form-group">
                            <label class="optional">Tanggal Lahir:</label>
                            <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir_edit">
                        </div>
                        <div class="form-group">
                            <label class="optional">Alamat Lengkap:</label>
                            <textarea name="alamat" id="alamat_edit" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="optional">Bio:</label>
                            <textarea name="bio" id="bio_edit" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="required">Apakah PLT?:</label>
                            <select name="is_plt" id="is_plt_edit" class="form-control" required>
                                <option value="" disabled selected>-- Apakah akun ini PLT? --</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer custom">
                        <div class="left-side">
                            <button type="button" class="btn btn-link danger" data-dismiss="modal">Batal</button>
                        </div>
                        <div class="divider"></div>
                        <div class="right-side">
                            <button type="submit" class="btn btn-link success">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Edit User -->

    <!-- Modal Ban User -->
    <div class="modal fade" id="banModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="banForm" action="#" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body text-center">
                        <p>Apakah Anda yakin ingin melakukan ini?</p>
                    </div>

                    <div class="modal-footer custom">
                        <div class="left-side">
                            <button type="button" class="btn btn-link danger" data-dismiss="modal">Tidak</button>
                        </div>

                        <div class="divider"></div>

                        <div class="right-side">
                            <button type="submit" class="btn btn-link success">Ya</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- End Modal Ban User -->

    <!-- Modal Reset Password User -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="resetPasswordForm" action="#" method="GET">
                    @csrf
                    <div class="modal-body text-center">
                        <p>Apakah Anda yakin ingin me-reset password akun ini?</p>
                    </div>

                    <div class="modal-footer custom">
                        <div class="left-side">
                            <button type="button" class="btn btn-link danger" data-dismiss="modal">Tidak</button>
                        </div>

                        <div class="divider"></div>

                        <div class="right-side">
                            <button type="submit" class="btn btn-link success">Ya</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- End Modal Reset Password User -->
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

@section('javascript')
    <script>
            $(document).ready(function() {
            $('#banModal').on('show.bs.modal', function(e) {
                var url = $(e.relatedTarget).data('url');

                document.getElementById("banForm").action = url;
            });

            $('#resetPasswordModal').on('show.bs.modal', function(e) {
                var url = $(e.relatedTarget).data('url');

                document.getElementById("resetPasswordForm").action = url;
            });

            $('#editModal').on('show.bs.modal', function(e) {
                var url = $(e.relatedTarget).data('url');
                var name = $(e.relatedTarget).data('name');
                var email = $(e.relatedTarget).data('email');
                var nik = $(e.relatedTarget).data('nik');
                var nip = $(e.relatedTarget).data('nip');
                var no_hp = $(e.relatedTarget).data('no_hp');
                var tempat_lahir = $(e.relatedTarget).data('tempat_lahir');
                var tanggal_lahir = $(e.relatedTarget).data('tanggal_lahir');
                var alamat = $(e.relatedTarget).data('alamat');
                var bio = $(e.relatedTarget).data('bio');
                var is_plt = $(e.relatedTarget).data('is_plt');
                var user_type_id = $(e.relatedTarget).data('user_type_id');
                var gender_id = $(e.relatedTarget).data('gender_id');
                var pulau_id = $(e.relatedTarget).data('pulau_id');
                var jabatan_id = $(e.relatedTarget).data('jabatan_id');

                document.getElementById("editForm").action = url;
                $('#name_edit').val(name);
                $('#email_edit').val(email);
                $('#nik_edit').val(nik);
                $('#nip_edit').val(nip);
                $('#no_hp_edit').val(no_hp);
                $('#tempat_lahir_edit').val(tempat_lahir);
                $('#tanggal_lahir_edit').val(tanggal_lahir);
                $('#alamat_edit').val(alamat);
                $('#bio_edit').val(bio);
                $('#is_plt_edit').val(is_plt);
                $('#user_type_id_edit').val(user_type_id);
                $('#gender_id_edit').val(gender_id);
                $('#pulau_id_edit').val(pulau_id);
                $('#jabatan_id_edit').val(jabatan_id);
            });
        })
    </script>
@endsection
