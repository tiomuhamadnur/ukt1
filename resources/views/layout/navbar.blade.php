<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="icon-devices_other nav-icon"></i>
        Dashboard
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('dashboard.index') }}">Dashboard</a>
        </li>
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="icon-database nav-icon"></i>
        Masterdata
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('dataEssentials.index') }}">Data Essentials</a>
        </li>
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-user nav-icon"></i>
        Laman Admin
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('user.index') }}">Data User</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('kegiatan.index') }}">Data Kegiatan</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('formasi-tim.index') }}">Data Formasi Tim</a>
        </li>
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-archive nav-icon"></i>
        Arsip Data
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('kinerja.index') }}">Data Kinerja</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('absensi.index') }}">Data Absensi</a>
        </li>
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-calendar-times nav-icon"></i>
        Cuti
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('konfigurasi-cuti.index') }}">Konfigurasi Cuti</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('cuti.index') }}">Data Pengajuan Cuti/Izin</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('approval-cuti.index') }}">Halaman Approval</a>
        </li>
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-bars nav-icon"></i>
        Sigma
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route("kanit.index") }}">Kanit</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route("kasi.index") }}">Kasi</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route("pjlp.index") }}">PJLP</a>
        </li>
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-bars nav-icon"></i>
        Sigma II
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        <li>
            <a class="dropdown-item" href="#">TBD</a>
        </li>
    </ul>
</li>
