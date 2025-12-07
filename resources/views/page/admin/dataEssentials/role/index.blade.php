@extends('layout.base')

@section('title-head')
    <title>Masterdata | Daftar Role</title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Masterdata</li>
            <li class="breadcrumb-item">Data Essentials</li>
            <li class="breadcrumb-item active">Daftar Role</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3 text-left">
                            <a href="{{ route('dataEssentials.index') }}" class="btn btn-outline-primary">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#tambahData">
                                Tambah Data
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="table-responsive mt-2">
                            {{ $dataTable->table([
                                'class' => 'table table-bordered table-striped',
                            ]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Role -->
    <div class="modal fade" id="tambahData" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Role</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('role.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Nama Role:</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label class="required mb-2">Permissions</label>
                            <div class="card shadow-sm border">
                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($permissions as $perm)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input edit-permission" type="checkbox"
                                                        name="permission_names[]" value="{{ $perm->name }}"
                                                        id="perm_{{ $perm->id }}">

                                                    <label class="form-check-label" for="perm_{{ $perm->id }}">
                                                        {{ $perm->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
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
    <!-- End Modal Tambah Role -->

    <!-- Modal Edit Role -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Role</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="editForm" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Nama Role:</label>
                            <input type="text" class="form-control" name="name" id="name_edit" required>
                        </div>
                        <div class="form-group">
                            <label class="required mb-2">Permissions</label>
                            <div class="card shadow-sm border">
                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($permissions as $perm)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input edit-permission" type="checkbox"
                                                        name="permission_names[]" value="{{ $perm->name }}"
                                                        id="perm_edit_{{ $perm->id }}">

                                                    <label class="form-check-label" for="perm_{{ $perm->id }}">
                                                        {{ $perm->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
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
    <!-- End Modal Edit Role -->
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#editModal').on('show.bs.modal', function(e) {
                var button = $(e.relatedTarget);
                var url = button.data('url');
                var name = button.data('name');
                var permissionsArray = button.data('permissions'); // sudah array

                // set form action & name
                $('#editForm').attr('action', url);
                $('#name_edit').val(name);

                // reset semua checkbox
                $('.edit-permission').prop('checked', false);

                // centang sesuai permissions
                permissionsArray.forEach(function(p) {
                    $('.edit-permission[value="' + p + '"]').prop('checked', true);
                });
            });

        });
    </script>
@endsection
