@extends('layout.base')

@section('title-head')
    <title>
        Admin Dashboard | UKT1.ORG Kep. Seribu
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Superadmin</li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="{{ route('user.index') }}">
                <div class="info-stats4">
                    <div class="info-icon">
                        <i class="icon-user"></i>
                    </div>
                    <div class="sale-num">
                        <h4>{{ $total_user ?? 'N/A' }}</h4>
                        <p>Total Pengguna Aktif (Tahun {{ $tahun }})</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="{{ route('absensi.index') }}">
                <div class="info-stats4">
                    <div class="info-icon">
                        <i class="icon-database"></i>
                    </div>
                    <div class="sale-num">
                        <h4>{{ $sudahAbsen ?? '0' }} / {{ $sudahAbsen + $belumAbsen }}</h4>
                        <p>PJLP Absen Hari Ini</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="{{ route('kinerja.index') }}">
                <div class="info-stats4">
                    <div class="info-icon">
                        <i class="icon-shopping-bag1"></i>
                    </div>
                    <div class="sale-num">
                        <h4>{{ number_format($total_kinerja ?? 0, 0, ',', '.') }}</h4>
                        <p>Total Input Kinerja (Tahun {{ $tahun }})</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
            <a href="{{ route('cuti.index') }}">
                <div class="info-stats4">
                    <div class="info-icon">
                        <i class="icon-activity"></i>
                    </div>
                    <div class="sale-num">
                        <h4>{{ $total_cuti ?? 'N/A' }}</h4>
                        <p>PJLP Cuti Hari Ini</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-right">
                        <div class="col-md-6">
                            <div class="card-title">Pintasan</div>
                            <p id="jam">Pintasan Generate Report PDF</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="container-fluid">
                            <div class="row gutters">
                                <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="doc-block">
                                        <div class="doc-icon">
                                            <i class="fa fa-calendar fa-2x"></i>
                                        </div>
                                        <div class="doc-title text-white">Generate Report Absensi PJLP</div>
                                        <div class="dropdown">
                                            <button class="btn btn-dark mr-2 mb-2 mb-sm-0 text-white" href="#"
                                                id="appsDropdown1" role="button" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false" title="Export">
                                                <i class="fa fa-paper-plane"></i> Export PDF
                                            </button>

                                            <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
                                                <li>
                                                    <a class="dropdown-item" href="javascript:;" data-toggle="modal"
                                                        data-target="#modalDownloadPDFAbsensi">
                                                        <i class="fa fa-file-pdf text-danger"></i> Export PDF
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="doc-block">
                                        <div class="doc-icon">
                                            <i class="fa fa-list fa-2x"></i>
                                        </div>
                                        <div class="doc-title text-white">Generate Report Kegiatan PJLP</div>
                                        <div class="dropdown">
                                            <button class="btn btn-dark mr-2 mb-2 mb-sm-0 text-white" href="#"
                                                id="appsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false" title="Export">
                                                <i class="fa fa-paper-plane"></i> Export PDF
                                            </button>

                                            <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
                                                <li>
                                                    <a class="dropdown-item" href="javascript:;" data-toggle="modal"
                                                        data-target="#modalDownloadPDFKegiatanPersonel">
                                                        <i class="fa fa-file-pdf text-danger"></i> PDF per Personil
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-right">
                        <div class="col-md-6">
                            <div class="card-title">Absensi Akumulatif Hari Ini</div>
                            <p id="jam">{{ $tanggal }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chartist donut-scheme-two">
                        <div id="absensiAkumulatif"></div>
                    </div>
                    <div class="row gutters">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="info-stats3 shade-one-a">
                                <i class="icon-opacity"></i>
                                <h6>Sudah Absen</h6>
                                <h3 id="sudah-absen">0 PJLP</h3>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="info-stats3 shade-one-b">
                                <i class="icon-opacity"></i>
                                <h6>Belum Absen</h6>
                                <h3 id="belum-absen">0 PJLP</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ABSENSI --}}
    {{-- BEGIN: Konfirmasi PDF --}}
    <div id="modalDownloadPDFAbsensi" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Export Laporan Absensi</h5>
                </div>
                <div class="modal-body">
                    <form id="formPDFAbsensi" action="{{ route('absensi.export.pdf') }}" method="GET">
                        @csrf
                        @method('GET')
                        <div class="form-row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label class="required">Personil</label>
                                    <select name="user_id" class="form-control" required>
                                        <option value="" selected disabled>- Pilih Personil -</option>
                                        @foreach ($user as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }} - {{ $item->nip ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="required">Periode</label>
                                    <input type="month" class="form-control" name="periode" value="" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
                    <button type="submit" form="formPDFAbsensi" formtarget="_blank"
                        class="btn btn-primary">Buat</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Konfirmasi PDF --}}



    {{-- KEGIATAN --}}
    {{-- BEGIN: Konfirmasi PDF --}}
    <div id="modalDownloadPDFKegiatanPersonel" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Export Laporan Kinerja</h5>
                </div>
                <div class="modal-body">
                    <form id="formPDFKegiatanPersonel" action="{{ route('kinerja.export.pdf') }}" method="GET">
                        @csrf
                        @method('GET')
                        <div class="form-row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label class="required">Personil</label>
                                    <select name="user_id" class="form-control" required>
                                        <option value="" selected disabled>- Pilih Personil -</option>
                                        @foreach ($user as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }} - {{ $item->nip ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
                    <button type="submit" form="formPDFKegiatanPersonel" formtarget="_blank"
                        class="btn btn-primary">Buat</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Konfirmasi PDF --}}
@endsection

@section('javascript')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // === DATA DARI BACKEND ===
            // Misal hasil query Laravel, kirim via blade:
            let sudahAbsen = @json($sudahAbsen ?? 0);
            let belumAbsen = @json($belumAbsen ?? 0);

            // Update text info di card
            document.getElementById("sudah-absen").innerText = `${sudahAbsen} PJLP`;
            document.getElementById("belum-absen").innerText = `${belumAbsen} PJLP`;

            // Render Pie Chart Highcharts
            Highcharts.chart('absensiAkumulatif', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '<b>{point.y} Pegawai ({point.percentage:.1f}%)</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}: {point.y}'
                        }
                    }
                },
                series: [{
                    name: 'Pegawai',
                    colorByPoint: true,
                    data: [{
                            name: 'Sudah Absen',
                            y: sudahAbsen,
                            color: '#28a745' // hijau
                        },
                        {
                            name: 'Belum Absen',
                            y: belumAbsen,
                            color: '#dc3545' // merah
                        }
                    ]
                }]
            });

        });
    </script>
@endsection
