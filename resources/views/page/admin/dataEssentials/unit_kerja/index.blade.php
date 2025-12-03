@extends('layout.base')

@section('title-head')
    <title>Masterdata | Daftar Unit Kerja</title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Masterdata</li>
            <li class="breadcrumb-item">Data Essentials</li>
            <li class="breadcrumb-item active">Daftar Unit Kerja</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12 mb-3 text-left">
                            <a href="{{ route('dataEssentials.index') }}" class="btn btn-outline-primary"><i
                                    class="fa fa-arrow-left"></i> Kembali</a>
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#tambahData">Tambah
                                Data</a>
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

    <!-- Modal Tambah Unit Kerja -->
    <div class="modal fade" id="tambahData" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Unit Kerja</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('unit-kerja.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Nama Unit Kerja:</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label class="required">Kode Unit Kerja:</label>
                            <input type="text" class="form-control" name="code" required>
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
    <!-- Modal Tambah Unit Kerja -->

    <!-- Modal Edit Unit Kerja -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Unit Kerja</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="editForm" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Nama Unit Kerja:</label>
                            <input type="text" class="form-control" name="name" id="name_edit" required>
                        </div>
                        <div class="form-group">
                            <label class="required">Kode Unit Kerja:</label>
                            <input type="text" class="form-control" name="code" id="code_edit" required>
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
    <!-- Modal Edit Unit Kerja -->
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#editModal').on('show.bs.modal', function(e) {
                var url = $(e.relatedTarget).data('url');
                var name = $(e.relatedTarget).data('name');
                var code = $(e.relatedTarget).data('code');

                document.getElementById("editForm").action = url;
                $('#name_edit').val(name);
                $('#code_edit').val(code);
            });
        });
    </script>
@endsection
