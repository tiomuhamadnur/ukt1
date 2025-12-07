@extends('layout.userbase')

@section('title-head')
    <title>
        Kinerja | Daftar Kinerja
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Kinerja</a></li>
            <li class="breadcrumb-item active">Daftar Kinerja Seksi {{ auth()->user()->seksi->name ?? 'N/A' }}</li>
        </ol>
    </div>
@endsection


@section('content')
    <div class="row gutters d-flex justify-content-center align-item-center">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="d-flex justify-content-center mb-3 text-center" style="text-decoration: underline">Rekap
                        Kinerja - Seksi {{ auth()->user()->seksi->name ?? 'N/A' }}</h4>
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 mb-3 text-left">
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <a href="{{ route('kasi.index') }}"
                                    class="btn btn-outline-primary mr-2 mb-2 mb-sm-0"><i class="fa fa-arrow-left"></i>
                                    Kembali</a>
                                <a href="javascript:;" class="btn btn-primary mr-2 mb-2 mb-sm-0" data-toggle="modal"
                                    data-target="#modalFilter" title="Filter"><i class="fa fa-filter"></i></a>
                                <a href="{{ route('kasi-kinerja.index') }}" class="btn btn-primary mr-2 mb-2 mb-sm-0"><i
                                        class="fa fa-refresh"></i>
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

    {{-- START: FILTER KINERJA --}}
    <div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Data Kinerja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formFilter" action="{{ route('kasi-kinerja.index') }}" method="GET">
                        @csrf
                        @method('GET')
                        <div class="form-row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="">Personel</label>
                                    <select name="user_id" class="form-control">
                                        <option value="" selected disabled>- Pilih Personel -</option>
                                        @foreach ($user as $item)
                                            <option value="{{ $item->id }}" @selected($item->id == $user_id)>
                                                {{ $item->name }} - {{ $item->nip ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Pulau</label>
                                    <select name="pulau_id" class="form-control">
                                        <option value="" selected disabled>- Pilih Pulau -</option>
                                        @foreach ($pulau as $item)
                                            <option value="{{ $item->id }}" @selected($item->id == $pulau_id)>
                                                Pulau {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Kegiatan</label>
                                    <select name="kegiatan_id" class="form-control">
                                        <option value="" selected disabled>- Pilih Kegiatan -</option>
                                        @foreach ($kegiatan as $item)
                                            <option value="{{ $item->id }}" @selected($item->id == $kegiatan_id)>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                    <input type="date" class="form-control" value="{{ $end_date }}"
                                        name="end_date">
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

    {{-- START: MODAL DOKUMENTASI --}}
    <div class="modal fade" id="modalDokumentasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalAdminTitle">Dokumentasi Pekerjaan</h4>
                </div>
                <div class="modal-body">
                    <div class="mb-4 text-center align-middle">
                        <div class="border mx-auto" style="width: 70%">
                            <p class="fw-bolder mb-0">Dokumentasi Pekerjaan</p>
                            <div id="photo_modal" class="container"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: MODAL DOKUMENTASI --}}
@endsection


@push('scripts')
    {{ $dataTable->scripts() }}
@endpush


@section('javascript')
    <script type="text/javascript">
        function toggleModal(id) {
            $('#id').val(id);
        }

        $(document).ready(function() {
            $('#modalDokumentasi').on('show.bs.modal', function(e) {
                var photoArray = $(e.relatedTarget).data('photo');
                var photoHTML = '';

                photoArray.forEach(function(item) {
                    var photoPath = "{{ asset('storage/') }}" + '/' + item;
                    photoHTML +=
                        '<div class""><img class="img-thumbnail img-fluid" style="width: 400px;" src="' +
                        photoPath + '" alt="photo"></div>';
                });

                document.getElementById("photo_modal").innerHTML = photoHTML;
            });
        });
    </script>
@endsection
