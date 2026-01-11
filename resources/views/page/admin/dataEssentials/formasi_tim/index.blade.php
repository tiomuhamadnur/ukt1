@extends('layout.base')

@section('title-head')
    <title>Masterdata | Daftar Formasi Tim</title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Masterdata</li>
            <li class="breadcrumb-item">Data Essentials</li>
            <li class="breadcrumb-item">Tim</li>
            <li class="breadcrumb-item active">Formasi Tim Tahun {{ $periode }}</li>
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
                            <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                                    class="fa fa-arrow-left"></i> Kembali</a>
                            <a href="#" class="btn btn-primary" data-toggle="modal"
                                data-target="#tambahData">Tambah
                                Data</a>
                            <a href="javascript:;" class="btn btn-primary mb-2 mb-sm-0" data-toggle="modal"
                                data-target="#modalFilter" title="Filter"><i class="fa fa-filter"></i></a>
                            <a href="{{ route('formasi-tim.index') }}" class="btn btn-primary mb-2 mb-sm-0">
                                <i class="fa fa-refresh"></i>
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

    <!-- Modal Tambah Formasi Tim -->
    <div class="modal fade" id="tambahData" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Formasi Tim</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('formasi-tim.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Rumpun:</label>
                            <select name="tim_id" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Rumpun --</option>
                                @foreach ($tim as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }} - Seksi {{ $item->seksi->name ?? 'N/A' }}
                                    </option>
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
                            <label class="required">Pengawas:</label>
                            <select name="koordinator_id" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Pengawas --</option>
                                @foreach ($pengawas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Personel:</label>
                            <select name="user_id" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Personel --</option>
                                @foreach ($user as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Periode:</label>
                            <select name="periode" class="form-control" required>
                                <option value="" selected disabled>-- Pilih Tahun --</option>
                                @foreach ($tahun as $pilih_tahun)
                                    <option value="{{ $pilih_tahun }}">{{ $pilih_tahun }}</option>
                                @endforeach
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
    <!-- End Modal Tambah Formasi Tim -->

    <!-- Modal Edit Formasi Tim -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Formasi Tim</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="editForm" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Rumpun:</label>
                            <select name="tim_id" id="tim_id_edit" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Rumpun --</option>
                                @foreach ($tim as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }} - Seksi {{ $item->seksi->name ?? 'N/A' }}
                                    </option>
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
                            <label class="required">Pengawas:</label>
                            <select name="koordinator_id" id="koordinator_id_edit" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Pengawas --</option>
                                @foreach ($pengawas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Personel:</label>
                            <select name="user_id" id="user_id_edit" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Personel --</option>
                                @foreach ($user as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Periode:</label>
                            <select name="periode" id="periode_edit" class="form-control" required>
                                <option value="" selected disabled>-- Pilih Tahun --</option>
                                @foreach ($tahun as $pilih_tahun)
                                    <option value="{{ $pilih_tahun }}">{{ $pilih_tahun }}</option>
                                @endforeach
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
    <!-- End Modal Edit Formasi Tim -->

    {{-- START: FILTER KINERJA --}}
    <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Data Kinerja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formFilter" action="{{ route('formasi-tim.index') }}" method="GET">
                        @csrf
                        @method('GET')
                        <div class="form-row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="periode">Periode Tahun:</label>
                                    <input type="number" class="form-control" name="periode"
                                        value="{{ $periode }}" min="1900" max="3000" step="1"
                                        placeholder="YYYY">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
                    <button type="submit" form="formFilter" class="btn btn-primary">Filter Data</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: FILTER KINERJA --}}
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#editModal').on('show.bs.modal', function(e) {
                var url = $(e.relatedTarget).data('url');
                var tim_id = $(e.relatedTarget).data('tim_id');
                var pulau_id = $(e.relatedTarget).data('pulau_id');
                var koordinator_id = $(e.relatedTarget).data('koordinator_id');
                var user_id = $(e.relatedTarget).data('user_id');
                var periode = $(e.relatedTarget).data('periode');

                document.getElementById("editForm").action = url;
                $('#tim_id_edit').val(tim_id);
                $('#pulau_id_edit').val(pulau_id);
                $('#koordinator_id_edit').val(koordinator_id);
                $('#user_id_edit').val(user_id);
                $('#periode_edit').val(periode);
            });
        });
    </script>
@endsection
