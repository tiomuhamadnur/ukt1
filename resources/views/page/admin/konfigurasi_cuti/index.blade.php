@extends('layout.base')

@section('title-head')
    <title>Masterdata | Konfigurasi Cuti</title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Masterdata</li>
            <li class="breadcrumb-item">Data Essentials</li>
            <li class="breadcrumb-item active">Daftar Konfigurasi Cuti</li>
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
                            <a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#filterModal"
                                title="Filter"><i class="fa fa-filter"></i></a>
                            <a href="{{ route('konfigurasi-cuti.index') }}" title="Reset Filter" class="btn btn-primary"><i
                                    class="fa fa-refresh"></i>
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

    <!-- Modal Tambah Konfigurasi Cuti -->
    <div class="modal fade" id="tambahData" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Konfigurasi Cuti</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('konfigurasi-cuti.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Periode:</label>
                            <select name="periode" class="form-control" required>
                                <option value="" selected disabled>-- Pilih Tahun --</option>
                                @foreach ($tahun as $pilih_tahun)
                                    <option value="{{ $pilih_tahun }}">{{ $pilih_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Jenis Cuti:</label>
                            <select name="jenis_cuti_id" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Jenis Cuti --</option>
                                @foreach ($jenis_cuti as $item)
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
                            <label class="required">Jumlah Jatah Awal (Hari):</label>
                            <input type="number" min="1" class="form-control" name="jumlah_awal" required>
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
    <!-- End Modal Tambah Konfigurasi Cuti -->

    <!-- Modal Tambah Konfigurasi Cuti -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Konfigurasi Cuti</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="editForm" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Periode:</label>
                            <select name="periode" id="periode_edit" class="form-control" required>
                                <option value="" selected disabled>-- Pilih Tahun --</option>
                                @foreach ($tahun as $pilih_tahun)
                                    <option value="{{ $pilih_tahun }}">{{ $pilih_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Jenis Cuti:</label>
                            <select name="jenis_cuti_id" id="jenis_cuti_id_edit" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Jenis Cuti --</option>
                                @foreach ($jenis_cuti as $item)
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
                            <label class="required">Jumlah Jatah Awal (Hari):</label>
                            <input type="number" min="1" class="form-control" name="jumlah_awal" id="jumlah_awal_edit" required>
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
    <!-- End Modal Tambah Konfigurasi Cuti -->

    <!-- Modal Filter Konfigurasi Cuti -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Data Konfigurasi Cuti</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('konfigurasi-cuti.index') }}" method="GET">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Periode:</label>
                            <select name="periode" class="form-control" required>
                                <option value="" selected disabled>-- Pilih Tahun --</option>
                                @foreach ($tahun as $y)
                                    <option value="{{ $y }}" @selected($y == $periode)>{{ $y }}</option>
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
                            <button type="submit" class="btn btn-link success">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Filter Konfigurasi Cuti -->
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#editModal').on('show.bs.modal', function(e) {
                var url = $(e.relatedTarget).data('url');
                var periode = $(e.relatedTarget).data('periode');
                var jenis_cuti_id = $(e.relatedTarget).data('jenis_cuti_id');
                var user_id = $(e.relatedTarget).data('user_id');
                var jumlah_awal = $(e.relatedTarget).data('jumlah_awal');

                document.getElementById("editForm").action = url;
                $('#periode_edit').val(periode);
                $('#jenis_cuti_id_edit').val(jenis_cuti_id);
                $('#user_id_edit').val(user_id);
                $('#jumlah_awal_edit').val(jumlah_awal);
            });
        });
    </script>
@endsection
