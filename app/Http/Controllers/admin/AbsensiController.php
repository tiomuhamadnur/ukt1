<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function kanit_index()
    {
        return view('page.admin.arsip.absensi.index');
    }

    public function kasi_index()
    {
        return view('page.admin.arsip.absensi.index');
    }

    public function pjlp_index()
    {
        return view('page.users.sigma.pjlp.absensi.index');
    }

    public function pjlp_create()
    {
        return view('page.users.sigma.pjlp.absensi.create');
    }
}
