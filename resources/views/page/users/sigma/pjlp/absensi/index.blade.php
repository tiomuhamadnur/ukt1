@extends('layout.userbase')

@section('title-head')
    <title>
        Absensi | Daftar Absensi
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pjlp.index') }}">Absensi</a></li>
            <li class="breadcrumb-item active">Daftar Absensi Saya</li>
    </div>
@endsection


@section('content')
    <div class="row gutters d-flex justify-content-center align-item-center">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="d-flex justify-content-center mb-3 text-center" style="text-decoration: underline">Absensi
                        Saya - {{ auth()->user()->name }}
                    </h4>
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 mb-3 text-left">
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <a href="{{ route('pjlp.index') }}"
                                    class="btn btn-outline-primary mr-2 mb-2 mb-sm-0"><i class="fa fa-arrow-left"></i>
                                    Kembali</a>
                                <a href="{{ route('pjlp-absensi.create') }}"
                                    class="btn btn-primary mr-2 mb-2 mb-sm-0">Tambah
                                    Data</a>
                                <a href="javascript" class="btn btn-primary mb-2 mr-2 mb-sm-0" data-toggle="modal"
                                    data-target="#modalFilter" title="Filter"><i class="fa fa-filter"></i></a>
                                <a href="{{ route('pjlp-absensi.index') }}" class="btn btn-primary mr-2 mb-2 mb-sm-0"
                                    title="Reset Filter">
                                    <i class="fa fa-refresh"></i>
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-primary mr-2 mb-2 mb-sm-0 text-white" id="exportDropdown"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        title="Export">
                                        <i class="fa fa-paper-plane"></i> Export
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                        <li>
                                            <a class="dropdown-item" href="javascript:;" data-toggle="modal"
                                                data-target="#modalDownloadExcel" title="Export Excel">
                                                <i class="fa fa-file-excel text-primary"></i> Export Excel
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:;" data-toggle="modal"
                                                data-target="#modalDownloadPDF" title="Export PDF">
                                                <i class="fa fa-file-pdf text-danger"></i> Export PDF
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
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

    {{-- START: FILTER ABSENSI --}}
    <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Data Absensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formFilter" action="{{ route('pjlp-absensi.index') }}" method="GET">
                        @csrf
                        @method('GET')
                        <div class="form-row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="">Personel</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                                </div>
                            </div>
                        </div>
                        <label for="periode">Periode</label>
                        <div class="form-row gutters">
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <input type="date" class="form-control" value="{{ $start_date }}"
                                        name="start_date">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <input type="date" class="form-control" value="{{ $end_date }}" name="end_date">
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
    {{-- END: FILTER ABSENSI --}}

    {{-- START: MODAL DOKUMENTASI --}}
    <div class="modal fade" id="modalDokumentasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalAdminTitle">Dokumentasi Absensi</h4>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-4 text-center align-middle">
                            <div class="border mx-auto" style="width: 80%">
                                <h6 class="fw-bolder mb-0">Dokumentasi Masuk</h6>
                                <img style="height: 250px" src="#" id="photo_masuk_modal" class="img-thumbnail"
                                    alt="No Photo SIGMA">
                                <img style="height: 250px" src="#" id="dokumentasi_masuk_modal" class="img-thumbnail"
                                    alt="No Photo TIMEMARK">
                            </div>
                        </div>
                        <div class="mb-4 text-center align-middle">
                            <div class="border mx-auto" style="width: 80%">
                                <h6 class="fw-bolder mb-0">Dokumentasi Pulang</h6>
                                <img style="height: 250px" src="#" id="photo_pulang_modal" class="img-thumbnail"
                                    alt="No Photo SIGMA">
                                <img style="height: 250px" src="#" id="dokumentasi_pulang_modal" class="img-thumbnail"
                                    alt="No Photo TIMEMARK">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END: MODAL DOKUMENTASI --}}

    {{-- BEGIN: Konfirmasi Excel --}}
    <div id="modalDownloadExcel" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-2">
                    <div class="p-2 text-center">
                        <div class="mt-2 fw-bolder">Apakah anda yakin?</div>
                        <div class="mt-2">
                            <img style="height: 100px;"
                                src="https://i.pinimg.com/originals/1b/db/8a/1bdb8ac897512116cbac58ffe7560d82.png"
                                alt="PDF">
                        </div>
                        <div class="text-slate-500 mt-2">
                            <p>
                                Data ini akan di-generate dalam format Excel!
                            </p>
                        </div>
                        <form id="exportExcel" action="{{ route('absensi.export.excel') }}" method="GET"
                            hidden>
                            @csrf
                            @method('GET')
                            {{-- <input type="hidden" name="seksi_id" value="{{ $seksi_id ?? '' }}"> --}}
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            {{-- <input type="hidden" name="pulau_id" value="{{ $pulau_id ?? '' }}"> --}}
                            <input type="hidden" name="start_date" value="{{ $start_date ?? '' }}">
                            <input type="hidden" name="end_date" value="{{ $end_date ?? '' }}">
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
                    <button type="submit" form="exportExcel" formtarget="_blank" class="btn btn-primary">Unduh</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Konfirmasi Excel --}}

    {{-- BEGIN: Konfirmasi PDF --}}
    <div id="modalDownloadPDF" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Export PDF Absensi</h5>
                </div>
                <div class="modal-body">
                    <form id="formPDF" action="{{ route('absensi.export.pdf') }}" method="GET">
                        @csrf
                        @method('GET')
                        <div class="form-row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label class="required">Personel</label>
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                </div>
                                {{-- <div class="form-group">
                                    <label class="required">Periode</label>
                                    <input type="month" class="form-control" name="periode"
                                        value="{{ $periode }}">
                                </div> --}}
                                <label for="periode" class="required">Periode</label>
                                <div class="form-row gutters">
                                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <input type="date" class="form-control" value="" name="start_date"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <input type="date" class="form-control" value="" name="end_date" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
                    <button type="submit" form="formPDF" formtarget="_blank" class="btn btn-primary">Generate</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Konfirmasi PDF --}}
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#modalDokumentasi').on('show.bs.modal', function(e) {
                var photoMasuk = $(e.relatedTarget).data('photo_masuk');
                var photoPulang = $(e.relatedTarget).data('photo_pulang');
                var dokumentasiMasuk = $(e.relatedTarget).data('dokumentasi_masuk');
                var dokumentasiPulang = $(e.relatedTarget).data('dokumentasi_pulang');

                document.getElementById("photo_masuk_modal").src = photoMasuk;
                document.getElementById("photo_pulang_modal").src = photoPulang;
                document.getElementById("dokumentasi_masuk_modal").src = dokumentasiMasuk;
                document.getElementById("dokumentasi_pulang_modal").src = dokumentasiPulang;
            });
        });
    </script>
@endsection
