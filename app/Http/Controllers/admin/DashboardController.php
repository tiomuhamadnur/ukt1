<?php

namespace App\Http\Controllers\admin;

use App\Models\Tim;
use App\Models\Kota;
use App\Models\Pulau;
use App\Models\Seksi;
use App\Models\Gender;
use App\Models\Jabatan;
use App\Models\Kegiatan;
use App\Models\Provinsi;
use App\Models\UserType;
use App\Models\JenisCuti;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\UnitKerja;
use App\Models\FormasiTim;
use App\Models\StatusCuti;
use App\Models\JenisAbsensi;
use Illuminate\Http\Request;
use App\Models\StatusAbsensi;
use App\Http\Controllers\Controller;
use App\Models\KonfigurasiAbsensi;
use App\Models\KonfigurasiCuti;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('page.admin.dashboard.index');
    }

    public function dataEssentials()
    {
        $user = User::count();
        $provinsi = Provinsi::count();
        $kota = Kota::count();
        $kecamatan = Kecamatan::count();
        $kelurahan = Kelurahan::count();
        $pulau = Pulau::count();
        $unit_kerja = UnitKerja::count();
        $seksi = Seksi::count();
        $tim = Tim::count();
        $formasi_tim = FormasiTim::count();
        $gender = Gender::count();
        $user_type = UserType::count();
        $jabatan = Jabatan::count();
        $jenis_cuti = JenisCuti::count();
        $status_cuti = StatusCuti::count();
        $jenis_absensi = JenisAbsensi::count();
        $status_absensi = StatusAbsensi::count();
        $kegiatan = Kegiatan::count();
        $konfigurasi_cuti = KonfigurasiCuti::count();
        $konfigurasi_absensi = KonfigurasiAbsensi::count();

        return view('page.admin.dashboard.dataEssentials', compact([
            'user',
            'provinsi',
            'kota',
            'kecamatan',
            'kelurahan',
            'pulau',
            'unit_kerja',
            'seksi',
            'tim',
            'formasi_tim',
            'gender',
            'user_type',
            'jabatan',
            'jenis_cuti',
            'status_cuti',
            'status_absensi',
            'jenis_absensi',
            'kegiatan',
            'konfigurasi_cuti',
            'konfigurasi_absensi',
        ]));
    }

    public function kanit()
    {
        return view('page.users.sigma.kanit.dashboard.index');
    }

    public function kasi()
    {
        return view('page.users.sigma.kasi.dashboard.index');
    }

    public function pjlp()
    {
        return view('page.users.sigma.pjlp.dashboard.index');
    }

}
