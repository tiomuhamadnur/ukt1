@extends('layout.base')

@section('title-head')
    <title>Masterdata | Konfigurasi Absensi</title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Masterdata</li>
            <li class="breadcrumb-item">Data Essentials</li>
            <li class="breadcrumb-item active">Daftar Konfigurasi Absensi</li>
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

    <!-- Modal Tambah Konfigurasi Absensi -->
    <div class="modal fade" id="tambahData" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Konfigurasi Absensi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('konfigurasi-absensi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Jenis Absensi:</label>
                            <select name="jenis_absensi_id" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Jenis Absensi --</option>
                                @foreach ($jenis_absensi as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Jam Masuk:</label>
                                    <input type="time" step="1" class="form-control" name="jam_masuk" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Jam Pulang:</label>
                                    <input type="time" step="1" class="form-control" name="jam_pulang" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Mulai Absen Masuk:</label>
                                    <input type="time" step="1" class="form-control" name="mulai_absen_masuk" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Selesai Absen Masuk:</label>
                                    <input type="time" step="1" class="form-control" name="selesai_absen_masuk" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Mulai Absen Pulang:</label>
                                    <input type="time" step="1" class="form-control" name="mulai_absen_pulang" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Selesai Absen Pulang:</label>
                                    <input type="time" step="1" class="form-control" name="selesai_absen_pulang" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="required">Toleransi Masuk (Menit):</label>
                            <input type="number" min="0" class="form-control" name="toleransi_masuk" required>
                        </div>
                        <div class="form-group">
                            <label class="required">Toleransi Pulang (Menit):</label>
                            <input type="number" min="0" class="form-control" name="toleransi_pulang" required>
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
    <!-- End Modal Tambah Konfigurasi Absensi -->

    <!-- Modal Edit Konfigurasi Absensi -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Konfigurasi Absensi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="editForm" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="required">Jenis Absensi:</label>
                            <select name="jenis_absensi_id" id="jenis_absensi_id_edit" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Jenis Absensi --</option>
                                @foreach ($jenis_absensi as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Jam Masuk:</label>
                                    <input type="time" step="1" class="form-control" name="jam_masuk" id="jam_masuk_edit"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Jam Pulang:</label>
                                    <input type="time" step="1" class="form-control" name="jam_pulang" id="jam_pulang_edit"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Mulai Absen Masuk:</label>
                                    <input type="time" step="1" class="form-control" name="mulai_absen_masuk"
                                        id="mulai_absen_masuk_edit" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Selesai Absen Masuk:</label>
                                    <input type="time" step="1" class="form-control" name="selesai_absen_masuk"
                                        id="selesai_absen_masuk_edit" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Mulai Absen Pulang:</label>
                                    <input type="time" step="1" class="form-control" name="mulai_absen_pulang"
                                        id="mulai_absen_pulang_edit" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">Selesai Absen Pulang:</label>
                                    <input type="time" step="1" class="form-control" name="selesai_absen_pulang"
                                        id="selesai_absen_pulang_edit" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="required">Toleransi Masuk (Menit):</label>
                            <input type="number" min="0" class="form-control" name="toleransi_masuk"
                                id="toleransi_masuk_edit" required>
                        </div>
                        <div class="form-group">
                            <label class="required">Toleransi Pulang (Menit):</label>
                            <input type="number" min="0" class="form-control" name="toleransi_pulang"
                                id="toleransi_pulang_edit" required>
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
    <!-- End Modal Edit Konfigurasi Absensi -->
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#editModal').on('show.bs.modal', function(e) {
                var url = $(e.relatedTarget).data('url');
                var jenis_absensi_id = $(e.relatedTarget).data('jenis_absensi_id');
                var jam_masuk = $(e.relatedTarget).data('jam_masuk');
                var jam_pulang = $(e.relatedTarget).data('jam_pulang');
                var mulai_absen_masuk = $(e.relatedTarget).data('mulai_absen_masuk');
                var selesai_absen_masuk = $(e.relatedTarget).data('selesai_absen_masuk');
                var mulai_absen_pulang = $(e.relatedTarget).data('mulai_absen_pulang');
                var selesai_absen_pulang = $(e.relatedTarget).data('selesai_absen_pulang');
                var toleransi_masuk = $(e.relatedTarget).data('toleransi_masuk');
                var toleransi_pulang = $(e.relatedTarget).data('toleransi_pulang');

                document.getElementById("editForm").action = url;
                $('#jenis_absensi_id_edit').val(jenis_absensi_id);
                $('#jam_masuk_edit').val(jam_masuk);
                $('#jam_pulang_edit').val(jam_pulang);
                $('#mulai_absen_masuk_edit').val(mulai_absen_masuk);
                $('#selesai_absen_masuk_edit').val(selesai_absen_masuk);
                $('#mulai_absen_pulang_edit').val(mulai_absen_pulang);
                $('#selesai_absen_pulang_edit').val(selesai_absen_pulang);
                $('#toleransi_masuk_edit').val(toleransi_masuk);
                $('#toleransi_pulang_edit').val(toleransi_pulang);
            });
        });
    </script>
@endsection
