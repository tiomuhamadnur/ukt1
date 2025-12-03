@extends('layout.base')

@section('title-head')
    <title>
        Dashboard | Data Essentials
    </title>
@endsection

@section('path')
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Masterdata</li>
            <li class="breadcrumb-item active">Data Essentials Dashboard</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div>
                <div>
                    <h5>Masterdata Essentials</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row gutters">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('user.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $user }}</h3>
                        <p>Users</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('provinsi.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $provinsi }}</h3>
                        <p>Provinsi</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('kota.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $kota }}</h3>
                        <p>Kota</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('kecamatan.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $kecamatan }}</h3>
                        <p>Kecamatan</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('kelurahan.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $kelurahan }}</h3>
                        <p>Kelurahan</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('pulau.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $pulau }}</h3>
                        <p>Pulau</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('unit-kerja.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $unit_kerja }}</h3>
                        <p>Unit Kerja</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('seksi.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $seksi }}</h3>
                        <p>Seksi</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('tim.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $tim }}</h3>
                        <p>Tim</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('formasi-tim.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $formasi_tim }}</h3>
                        <p>Formasi Tim</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('gender.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $gender }}</h3>
                        <p>Gender</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('user-type.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $user_type }}</h3>
                        <p>User Type</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('jabatan.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $jabatan }}</h3>
                        <p>Jabatan</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('jenis-cuti.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $jenis_cuti }}</h3>
                        <p>Jenis Cuti</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('status-cuti.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $status_cuti }}</h3>
                        <p>Status Cuti</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('status-absensi.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $status_absensi }}</h3>
                        <p>Status Absensi</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('jenis-absensi.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $jenis_absensi }}</h3>
                        <p>Jenis Absensi</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('kegiatan.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $kegiatan }}</h3>
                        <p>Kegiatan</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('konfigurasi-cuti.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $konfigurasi_cuti }}</h3>
                        <p>Konfigurasi Cuti</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('konfigurasi-absensi.index') }}">
                <div class="info-tiles">
                    <div class="info-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="stats-detail">
                        <h3>{{ $konfigurasi_absensi }}</h3>
                        <p>Konfigurasi Absensi</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
