<?php

namespace App\Http\Controllers\admin;

use App\Models\Cuti;
use App\Models\User;
use App\Models\JenisCuti;
use App\Http\Controllers\Controller;

class CutiController extends Controller
{
    // ADMIN
    public function index()
    {
        return view('page.admin.cuti.index');
    }

    public function konfigurasi_cuti()
    {
        $cuti = Cuti::with(['user', 'jenis_cuti'])->get();
        $user = User::orderBy('name')->orderBy('name', 'desc')->get();
        $jenis_cuti = JenisCuti::orderBy('name')->get();

        $tahun = [];
        $currentYear = date('Y');
        for ($i = 0; $i <= 2; $i++) {
            $tahun[] = $currentYear + $i;
        }

        return view('page.admin.cuti.konfigurasi', compact('cuti', 'user', 'jenis_cuti', 'tahun'));
    }

    public function approval_cuti()
    {
        return view('page.admin.cuti.approval');
    }

    // KANIT
    public function kanit_index()
    {
        return view('page.users.sigma.kanit.cuti.index');
    }

    public function kanit_approval()
    {
        return view('page.users.sigma.kanit.cuti.approval');
    }

    // KASI
    public function kasi_index()
    {
        return view('page.users.sigma.kanit.cuti.approval');
    }

    public function kasi_approval()
    {
        return view('page.users.sigma.kanit.cuti.approval');
    }

    // PJLP
    public function pjlp_index()
    {
        return view('page.users.sigma.pjlp.cuti.index');
    }

    public function pjlp_create()
    {
        return view('page.users.sigma.pjlp.cuti.create');
    }
}
