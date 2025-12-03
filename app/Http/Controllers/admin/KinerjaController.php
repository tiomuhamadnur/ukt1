<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KinerjaController extends Controller
{
    //ADMIN
    public function index()
    {
        return view('page.admin.cuti.index');
    }

    // KANIT
    public function kanit_index()
    {
        return view('page.admin.arsip.kinerja.index');
    }

    // KASI
    public function kasi_index()
    {
        return view('page.admin.arsip.kinerja.index');
    }

    // PJLP
    public function pjlp_index()
    {
        return view('page.users.sigma.pjlp.kinerja.index');
    }

    public function pjlp_create()
    {
        return view('page.users.sigma.pjlp.kinerja.create');
    }
}