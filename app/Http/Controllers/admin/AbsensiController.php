<?php

namespace App\Http\Controllers\admin;

use App\DataTables\AbsensiDataTable;
use App\DataTables\AbsensiSayaDataTable;
use App\Exports\absensi\AbsensiExport;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\FormasiTim;
use App\Models\JenisAbsensi;
use App\Models\KonfigurasiAbsensi;
use App\Models\KonfigurasiAbsensiTim;
use App\Models\Pulau;
use App\Models\Seksi;
use App\Models\User;
use App\Services\ImageUploadService;
use App\Services\ReverseGeocodingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiController extends Controller
{
    public function index(AbsensiDataTable $dataTable, Request $request)
    {
        $tahun = $tahun ?? date('Y');

        $request->validate([
            'seksi_id' => 'nullable|exists:seksi,id',
            'user_id' => 'nullable|exists:users,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'bulan' => 'nullable|numeric',
            'tahun' => 'nullable|numeric',
        ]);

        $seksi_id = $request->seksi_id ?? null;
        $user_id = $request->user_id ?? null;
        $pulau_id = $request->pulau_id ?? null;

        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');

        // Buat string periode Y-m
        $periode = $tahun . '-' . $bulan;

        // Parse periode dengan format Y-m
        $periodeCarbon = Carbon::createFromFormat('Y-m', $periode);

        // Ambil tanggal awal & akhir bulan
        $start_date = $periodeCarbon->startOfMonth()->toDateString();
        $end_date   = $periodeCarbon->endOfMonth()->toDateString();

        $user = User::where('user_type_id', 4) //Hanya PJLP
                ->notBanned()
                ->orderBy('name', 'ASC')
                ->get();

        $seksi = Seksi::orderBy('name', 'ASC')->get();
        $pulau = Pulau::orderBy('name', 'ASC')->get();
        $tahuns = Absensi::selectRaw('YEAR(tanggal) as tahun')
                ->distinct()
                ->orderBy('tahun', 'asc')
                ->pluck('tahun');


        return $dataTable->with([
            'seksi_id' => $seksi_id,
            'user_id' => $user_id,
            'pulau_id' => $pulau_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ])->render('page.admin.absensi.index', compact([
            'user',
            'pulau',
            'seksi',
            'seksi_id',
            'user_id',
            'pulau_id',
            'start_date',
            'end_date',
            'periode',
            'tahuns',
            'tahun',
            'bulan',
        ]));
    }

    public function export_excel(Request $request)
    {
        $request->validate([
            'seksi_id' => 'nullable|exists:seksi,id',
            'user_id' => 'nullable|exists:users,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date|required_with:start_date',
        ]);

        $seksi_id = $request->seksi_id;
        $user_id = $request->user_id;
        $pulau_id = $request->pulau_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date ?? $start_date;

        $waktu = Carbon::now()->format('Ymd');

        return Excel::download(new AbsensiExport($seksi_id, $user_id, $pulau_id, $start_date, $end_date), $waktu . '_data absensi.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function export_pdf_kasi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'periode' => 'required|string',
        ]);

        $user_id = $request->user_id;
        $periode = $request->periode;

        $start_date = Carbon::createFromFormat('Y-m', $periode)->startOfMonth()->toDateString();
        $end_date   = Carbon::createFromFormat('Y-m', $periode)->endOfMonth()->toDateString();

        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date) ?? $start_date;

        $user = FormasiTim::where('periode', Carbon::now()->year)
                ->where('user_id', $user_id)
                ->first();

        $absensi = Absensi::where('user_id', $user_id)
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->get()
            ->pluck('tanggal');

        $datesInRange = [];
        for ($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $absen = Absensi::where('user_id', $user_id)
                ->whereDate('tanggal', $date)
                ->first();

            if ($absen) {
                if ($absen->jam_masuk == null or $absen->jam_pulang == null) {
                    $bg = 'bg-warning';
                } else {
                    $bg = '';
                }
            } else {
                $bg = 'bg-danger';
            }


            $datesInRange[] = [
                'hari' => $date->isoFormat('dddd'),
                'tanggal' => $date->copy(),
                'jam_masuk' => $absen->jam_masuk ?? '',
                'jam_pulang' => $absen->jam_pulang ?? '',
                'status' => $absen->status_absensi->name ?? 'Tidak Absen',
                'bg' => $bg,
                'status_masuk' => $absen->status_masuk ?? '',
                'status_pulang' => $absen->status_pulang ?? '',
                'url_photo_masuk' => $absen && $absen->photo_masuk ? public_path('storage/' . $absen->photo_masuk) : '',
                'url_photo_pulang' => $absen && $absen->photo_pulang ? public_path('storage/' . $absen->photo_pulang) : '',
            ];
        }

        $pdf = Pdf::loadView('page.admin.absensi.pdf', [
            'user' => $user,
            'datesInRange' => $datesInRange,
            'absensi' => $absensi,
            'start_date' => $start_date->isoFormat('D MMMM Y'),
            'end_date' => $end_date->isoFormat('D MMMM Y'),
        ]);

        return $pdf->stream(Carbon::now()->format('Ymd_') . 'Data Absensi_' . $user->user->name ?? null . '_' . $user->user->nip ?? null . '_Seksi ' . $user->formasi_tim->tim->seksi->name ?? null . '_Pulau ' . $user->formasi_tim->pulau->name ?? null . '.pdf');
    }

    public function export_pdf(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'periode' => 'required|string',
        ]);

        $user_id = $request->user_id;
        $periode = $request->periode;

        $start_date = Carbon::createFromFormat('Y-m', $periode)->startOfMonth()->toDateString();
        $end_date   = Carbon::createFromFormat('Y-m', $periode)->endOfMonth()->toDateString();

        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date) ?? $start_date;

        $formasi_tim = FormasiTim::where('user_id', $user_id)
                        ->orderBy('periode', 'DESC')
                        ->first();

        $kepala_unit = User::where('jabatan_id', 2) //Kepala Unit
                        ->where('unit_kerja_id', $formasi_tim->tim->seksi->unit_kerja_id)
                        ->orderBy('updated_at', 'DESC')
                        ->first();

        $kepala_seksi = User::where('jabatan_id', 3) //Kepala Seksi
                        ->where('unit_kerja_id', $formasi_tim->tim->seksi->unit_kerja_id)
                        ->where('seksi_id', $formasi_tim->tim->seksi_id)
                        ->orderBy('updated_at', 'DESC')
                        ->first();

        $pengawas = User::where('jabatan_id', 4) //Pengawas
                        ->where('unit_kerja_id', $formasi_tim->tim->seksi->unit_kerja_id)
                        ->where('seksi_id', $formasi_tim->tim->seksi_id)
                        ->orderBy('updated_at', 'DESC')
                        ->first();

        $absensi = Absensi::where('user_id', $user_id)
                        ->whereBetween('tanggal', [$start_date, $end_date])
                        ->get()
                        ->pluck('tanggal');

        $datesInRange = [];
        for ($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $absen = Absensi::where('user_id', $user_id)
                        ->whereDate('tanggal', $date)
                        ->first();

            if($absen){
                if($absen->jam_masuk == null or $absen->jam_pulang == null){
                    $bg = 'bg-warning';
                } else {
                    $bg = '';
                }
            } else {
                $bg = 'bg-danger';
            }


            $datesInRange[] = [
                'hari' => $date->isoFormat('dddd'),
                'tanggal' => $date->copy(),
                'jam_masuk' => $absen->jam_masuk ?? '',
                'jam_pulang' => $absen->jam_pulang ?? '',
                'status_masuk' => $absen->status_masuk ?? '',
                'status_pulang' => $absen->status_pulang ?? '',
                'status' => $absen->status_absensi->name ?? 'Tidak Absen',
                'bg' => $bg,
                'url_photo_masuk' => $absen && $absen->photo_masuk ? public_path('storage/' . $absen->photo_masuk) : '',
                'url_photo_pulang' => $absen && $absen->photo_pulang ? public_path('storage/' . $absen->photo_pulang) : '',
                'url_dokumentasi_masuk' => $absen && $absen->dokumentasi_masuk ? public_path('storage/' . $absen->dokumentasi_masuk) : '',
                'url_dokumentasi_pulang' => $absen && $absen->dokumentasi_pulang ? public_path('storage/' . $absen->dokumentasi_pulang) : '',
            ];
        }

        $jumlah_hari_kerja = $start_date->diffInDays($end_date) + 1;
        $jumlah_hari_masuk = Absensi::where('user_id', $user_id)
                            ->whereBetween('tanggal', [$start_date, $end_date])
                            ->where(function ($q) {
                                $q->whereNotNull('jam_masuk')
                                    ->orWhereNotNull('jam_pulang');
                            })
                            ->count();
        $jumlah_hari_tidak_masuk = $jumlah_hari_kerja - $jumlah_hari_masuk;
        $persentase_kehadiran = $jumlah_hari_kerja > 0
                                ? round(($jumlah_hari_masuk / $jumlah_hari_kerja) * 100)
                                : 0;
        $jumlah_hari_ok = Absensi::where('user_id', $user_id)
                            ->whereBetween('tanggal', [$start_date, $end_date])
                            ->whereNotNull('jam_masuk')
                            ->whereNotNull('jam_pulang')
                            ->where('telat_masuk', 0)
                            ->where('telat_pulang', 0)
                            ->count();
        $jumlah_hari_tidak_ok = $jumlah_hari_kerja - $jumlah_hari_ok;
        $jumlah_hari_lengkap = Absensi::where('user_id', $user_id)
                            ->whereBetween('tanggal', [$start_date, $end_date])
                            ->whereNotNull('jam_masuk')
                            ->whereNotNull('jam_pulang')
                            ->count();
        $jumlah_hari_tidak_lengkap = $jumlah_hari_kerja - $jumlah_hari_lengkap;
        $persentase_ketertiban = $jumlah_hari_kerja > 0
                                ? round(($jumlah_hari_ok / $jumlah_hari_kerja) * 100)
                                : 0;

        $cuti = Absensi::where('user_id', $user_id)
                            ->whereBetween('tanggal', [$start_date, $end_date])
                            ->where('status_absensi_id', 4)
                            ->count();

        $sakit = Absensi::where('user_id', $user_id)
                            ->whereBetween('tanggal', [$start_date, $end_date])
                            ->where('status_absensi_id', 5)
                            ->count();

        $konfigurasi = KonfigurasiAbsensi::latest()->first();
        $jamStandarMasuk  = Carbon::parse($konfigurasi->jam_masuk);   // 07:30
        $jamStandarPulang = Carbon::parse($konfigurasi->jam_pulang);  // 16:00
        $jamKerjaHarian   = $jamStandarMasuk->floatDiffInHours($jamStandarPulang);

        // total jam kerja standar (efektif)
        $total_jam_kerja = $jamKerjaHarian * $jumlah_hari_kerja;

        // total jam kerja aktual
        $total_jam_kerja_aktual = Absensi::where('user_id', $user_id)
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->get()
            ->sum(function ($absen) use ($jamStandarMasuk, $jamStandarPulang, $jamKerjaHarian) {
                if ($absen->jam_masuk && $absen->jam_pulang) {
                    $jamMasuk  = Carbon::parse($absen->jam_masuk);
                    $jamPulang = Carbon::parse($absen->jam_pulang);

                    if ($jamMasuk->greaterThan($jamStandarMasuk) || $jamPulang->lessThan($jamStandarPulang)) {
                        // real jam kerja (karena telat masuk atau cepat pulang)
                        return $jamMasuk->floatDiffInHours($jamPulang);
                    } else {
                        // full ikut standar
                        return $jamKerjaHarian;
                    }
                }
                return 0; // absen tidak lengkap
            });

        // persentase jam kerja aktual
        $persentase_jam_kerja_aktual = $total_jam_kerja > 0
            ? round(($total_jam_kerja_aktual / $total_jam_kerja) * 100)
            : 0;

        $total_jam_kerja_aktual = round($total_jam_kerja_aktual);

        $total_menit_kerja = $total_jam_kerja * 60; //menit

        $total_menit_telat = Absensi::where('user_id', $user_id)
                    ->whereBetween('tanggal', [$start_date, $end_date])
                    ->selectRaw('SUM(telat_masuk + telat_pulang) as total_telat')
                    ->value('total_telat');

        $persentase_menit_telat = $total_menit_kerja > 0
            ? round(($total_menit_telat / $total_menit_kerja) * 100)
            : 0;

        $pdf = Pdf::loadView('page.admin.absensi.pdf', [
            'user' => $formasi_tim,
            'pengawas' => $pengawas,
            'kepala_seksi' => $kepala_seksi,
            'kepala_unit' => $kepala_unit,
            'jumlah_hari_kerja' => $jumlah_hari_kerja,
            'jumlah_hari_masuk' => $jumlah_hari_masuk,
            'jumlah_hari_tidak_masuk' => $jumlah_hari_tidak_masuk,
            'persentase_kehadiran' => $persentase_kehadiran,
            'jumlah_hari_ok' => $jumlah_hari_ok,
            'jumlah_hari_tidak_ok' => $jumlah_hari_tidak_ok,
            'persentase_ketertiban' => $persentase_ketertiban,
            'jumlah_hari_lengkap' => $jumlah_hari_lengkap,
            'jumlah_hari_tidak_lengkap' => $jumlah_hari_tidak_lengkap,
            'cuti' => $cuti,
            'sakit' => $sakit,
            'total_jam_kerja' => $total_jam_kerja,
            'total_jam_kerja_aktual' => $total_jam_kerja_aktual,
            'persentase_jam_kerja_aktual' => $persentase_jam_kerja_aktual,
            'total_menit_kerja' => $total_menit_kerja,
            'total_menit_telat' => $total_menit_telat,
            'persentase_menit_telat' => $persentase_menit_telat,
            'datesInRange' => $datesInRange,
            'absensi' => $absensi,
            'start_date' => $start_date->isoFormat('D MMMM Y'),
            'end_date' => $end_date->isoFormat('D MMMM Y'),
        ]);

        return $pdf->stream(Carbon::now()->format('Ymd_') . 'Data Absensi_' . $formasi_tim->user->name . '_' . $formasi_tim->user->nip . '_Seksi ' . $formasi_tim->tim->seksi->name . '_Pulau ' . $formasi_tim->pulau->name . '.pdf');
    }








    public function kanit_index(AbsensiDataTable $dataTable, Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'bulan' => 'nullable|numeric',
            'tahun' => 'nullable|numeric',
        ]);

        $user_id = $request->user_id ?? null;
        $pulau_id = $request->pulau_id ?? null;

        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');

        // Buat string periode Y-m
        $periode = $tahun . '-' . $bulan;

        // Parse periode dengan format Y-m
        $periodeCarbon = Carbon::createFromFormat('Y-m', $periode);

        // Ambil tanggal awal & akhir bulan
        $start_date = $periodeCarbon->startOfMonth()->toDateString();
        $end_date   = $periodeCarbon->endOfMonth()->toDateString();

        $user = User::where('user_type_id', 4) //Hanya PJLP
                ->orderBy('name', 'ASC')
                ->get();

        $seksi = Seksi::orderBy('name', 'ASC')->get();
        $pulau = Pulau::orderBy('name', 'ASC')->get();
        $tahuns = Absensi::selectRaw('YEAR(tanggal) as tahun')
                ->distinct()
                ->orderBy('tahun', 'asc')
                ->pluck('tahun');


        return $dataTable->with([
            'user_id' => $user_id,
            'pulau_id' => $pulau_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ])->render('page.users.sigma.kanit.absensi.index', compact([
            'user',
            'pulau',
            'seksi',
            'user_id',
            'pulau_id',
            'start_date',
            'end_date',
            'periode',
            'tahuns',
            'tahun',
            'bulan',
        ]));
    }

    public function kasi_index(AbsensiDataTable $dataTable, Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'bulan' => 'nullable|numeric',
            'tahun' => 'nullable|numeric',
        ]);

        $user_id = $request->user_id ?? null;
        $pulau_id = $request->pulau_id ?? null;

        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');

        $seksi_id = Auth::user()->seksi_id;

        // Buat string periode Y-m
        $periode = $tahun . '-' . $bulan;

        // Parse periode dengan format Y-m
        $periodeCarbon = Carbon::createFromFormat('Y-m', $periode);

        // Ambil tanggal awal & akhir bulan
        $start_date = $periodeCarbon->startOfMonth()->toDateString();
        $end_date   = $periodeCarbon->endOfMonth()->toDateString();

        $user = User::where('user_type_id', 4) //Hanya PJLP
                ->whereRelation('formasi_tim.tim', 'seksi_id', '=', $seksi_id)
                ->orderBy('name', 'ASC')
                ->get();

        $seksi = Seksi::orderBy('name', 'ASC')->get();
        $pulau = Pulau::orderBy('name', 'ASC')->get();
        $tahuns = Absensi::selectRaw('YEAR(tanggal) as tahun')
                ->distinct()
                ->orderBy('tahun', 'asc')
                ->pluck('tahun');


        return $dataTable->with([
            'seksi_id' => $seksi_id,
            'user_id' => $user_id,
            'pulau_id' => $pulau_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ])->render('page.users.sigma.kasi.absensi.index', compact([
            'user',
            'pulau',
            'seksi',
            'seksi_id',
            'user_id',
            'pulau_id',
            'start_date',
            'end_date',
            'periode',
            'tahuns',
            'tahun',
            'bulan',
        ]));
    }





    public function pjlp_index(AbsensiSayaDataTable $dataTable, Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $periode = Carbon::now()->format('Y-m');
        $start_date = $request->start_date ?? Carbon::createFromFormat('Y-m', $periode)->startOfMonth()->toDateString();
        $end_date = $request->end_date ?? Carbon::createFromFormat('Y-m', $periode)->endOfMonth()->toDateString();

        return $dataTable->with([
            'start_date' => $start_date,
            'end_date' => $end_date,
        ])->render('page.users.sigma.pjlp.absensi.index', compact([
            'start_date',
            'end_date',
            'periode',
        ]));
    }

    public function pjlp_create()
    {
        $user = Auth::user();
        $tanggal = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $tim_id = $user->formasi_tim->tim_id;
        $jenis_absensi = KonfigurasiAbsensi::whereHas('tims', function ($q) use ($tim_id) {
                    $q->where('tim_id', $tim_id);
                })->get();

        $periode = date('Y');
        $formasi_tim = FormasiTim::where('periode', $periode)
                    ->where('user_id', $user->id)
                    ->first();

        return view('page.users.sigma.pjlp.absensi.create', compact([
            'user',
            'tanggal',
            'jenis_absensi',
            'formasi_tim',
        ]));
    }

    public function pjlp_store(Request $request, ReverseGeocodingService $geoService, ImageUploadService $imageService)
    {
        $request->validate([
            'photo' => 'required',
            'jenis_absensi_id' => 'required|exists:jenis_absensi,id',
            'dokumentasi' => 'required|file|image',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'catatan' => 'nullable|string|max:255',
        ]);

        $img = $request->photo;
        $jenis_absensi_id = $request->jenis_absensi_id;
        $catatan = $request->catatan;
        $latitude = $request->latitude ?? null;
        $longitude = $request->longitude ?? null;

        $now = Carbon::now();
        $tanggal = Carbon::parse($now)->format('Y-m-d');
        $waktu = Carbon::parse($now);
        $konfigurasi_absensi = KonfigurasiAbsensi::where('jenis_absensi_id', $jenis_absensi_id)->first();

        if(!$konfigurasi_absensi) {
            return back()->withError('Konfigurasi Jenis Absensi yang dipilih belum diatur, silahkan hubungi admin.');
        }

        $toleransi_masuk = $konfigurasi_absensi->toleransi_masuk;
        $toleransi_pulang = $konfigurasi_absensi->toleransi_pulang;
        $jam_masuk = Carbon::parse($konfigurasi_absensi->jam_masuk)->addMinutes($toleransi_masuk);
        $jam_pulang = Carbon::parse($konfigurasi_absensi->jam_pulang)->subMinutes($toleransi_pulang);

        $user = Auth::user();
        $user_id = $user->id;

        $mode = '';     // logic untuk simpan foto (masuk / pulang)
        $status_absensi_id = null;   // status absensi untuk DB
        $label = '';    // teks untuk watermark
        $telat = '';
        $status_absensi = '';

        $batas_mulai_absen_masuk = Carbon::parse($konfigurasi_absensi->mulai_absen_masuk);
        $batas_selesai_absen_masuk = Carbon::parse($konfigurasi_absensi->selesai_absen_masuk);
        $batas_mulai_absen_pulang = Carbon::parse($konfigurasi_absensi->mulai_absen_pulang);
        $batas_selesai_absen_pulang = Carbon::parse($konfigurasi_absensi->selesai_absen_pulang);

        $hari = Carbon::parse($waktu)->isoFormat('dddd');
        if($hari == 'Jumat'){
            $jam_pulang = $jam_pulang->addMinutes(30);
            $batas_mulai_absen_pulang = $batas_mulai_absen_pulang->addMinutes(30);
        }

        $formasi = $user->formasi_tim;

        if(!$formasi) {
            return back()->withError('Anda belum memiliki Formasi Tim, silahkan hubungi admin.');
        }

        $nama = strtoupper($formasi->user->name) . ' - ' . $formasi->user->nip;
        $jam = Carbon::parse($waktu)->format('H:i:s') . ' WIB';
        $date = Carbon::parse($waktu)->isoFormat('dddd, D MMMM Y') . ' - ' . $jam;
        $seksi = 'Seksi ' . $formasi->tim->seksi->name;
        $pulau = 'Pulau ' . $formasi->pulau->name;

        // Tentukan mode absensi
        if(($waktu >= $batas_mulai_absen_masuk) and ($waktu <= $batas_selesai_absen_masuk)) {
            $mode = 'masuk';
            $status_absensi_id = 1; //Absensi Datang
            $label = 'Absensi Datang';

            // Cek keterlambatan
            if ($waktu > $jam_masuk){
                $telat = $waktu->diffInMinutes($jam_masuk);
                $telat = abs($telat);
                $status_absensi = 'Datang terlambat';
            } else {
                $telat = 0;
                $status_absensi = 'Datang tepat waktu';
            }
        }
        elseif(($waktu >= $batas_mulai_absen_pulang) and ($waktu <= $batas_selesai_absen_pulang)) {
            $mode = 'pulang';
            $status_absensi_id = 2; //Absensi Lengkap/Pulang
            $label = 'Absensi Pulang';

            // Cek pulang cepat
            if ($waktu < $jam_pulang){
                $telat = $waktu->diffInMinutes($jam_pulang);
                $telat = abs($telat);
                $status_absensi = 'Pulang Cepat';
            } else {
                $telat = 0;
                $status_absensi = 'Pulang tepat waktu';
            }
        }
        else {
            return back()->withError('Anda harus melakukan absensi, pada rentang Waktu yang telah ditentukan!');
        }

        // GeoLocation
        $lokasi = $geoService->getAddress($latitude, $longitude);

        // Simpan ke DB
        if ($mode == 'masuk') {
            $validasi = Absensi::where('user_id', $user_id)
                        ->whereDate('tanggal', $tanggal)
                        ->whereNot('jam_masuk', null)
                        ->count();

            if($validasi > 0) {
                return back()->withError("Anda sudah melakukan <strong>Absensi {$mode}</strong> hari ini.");
            }

            $absensi = Absensi::create([
                'jenis_absensi_id' => 1,
                'user_id' => $user_id,
                'tanggal' => $tanggal,
                'jam_masuk' => $waktu,
                'telat_masuk' => $telat,
                'latitude_masuk' => $latitude,
                'longitude_masuk' => $longitude,
                'status_masuk' => $status_absensi,
                'status_absensi_id' => $status_absensi_id,
                'catatan_masuk' => $catatan,
                'lokasi_masuk' => $lokasi,
            ]);
        } else { // pulang
            $validasi = Absensi::where('user_id', $user_id)
                        ->whereDate('tanggal', $tanggal)
                        ->whereNot('jam_pulang', null)
                        ->count();

            if($validasi > 0) {
                return back()->withError("Anda sudah melakukan <strong>Absensi {$mode}</strong> hari ini.");
            } else {
                $status_absensi_id = 2; // Absensi Lengkap (ubah status, tapi tidak ubah $mode)
            }

            $absensi = Absensi::where('user_id', $user_id)
                            ->whereDate('tanggal', $tanggal)
                            ->first();

            if($absensi) {
                $absensi->update([
                    'jam_pulang' => $waktu,
                    'telat_pulang' => $telat,
                    'latitude_pulang' => $latitude,
                    'longitude_pulang' => $longitude,
                    'status_pulang' => $status_absensi,
                    'status_absensi_id'=> $status_absensi_id,
                    'catatan_pulang' => $catatan,
                    'lokasi_pulang' => $lokasi,
                ]);
            } else {
                $absensi = Absensi::create([
                    'jenis_absensi_id' => 1,
                    'user_id' => $user_id,
                    'tanggal' => $tanggal,
                    'jam_pulang' => $waktu,
                    'telat_pulang' => $telat,
                    'latitude_pulang' => $latitude,
                    'longitude_pulang' => $longitude,
                    'status_pulang' => $status_absensi,
                    'status_absensi_id'=> 3, // Tidak Absen Datang
                    'catatan_pulang' => $catatan,
                    'lokasi_pulang' => $lokasi,
                ]);
            }
        }

        // Simpan foto absensi
        $folderPath = "absensi/";
        $storagePath = public_path("storage/" . $folderPath);

        // Buat folder jika belum ada
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.' . $image_type;

        $file = $folderPath . $fileName;

        Storage::put($file, $image_base64);


        // Photo Dokumentasi Timemark
        $dokumentasiPath = null;
        if ($request->hasFile('dokumentasi')) {
            $dokumentasiPath = $imageService->uploadImage(
                $request->file('dokumentasi'),
                'absensi/dokumentasi/',
                null,
                300,
                60
            );
        }

        $absen = Absensi::find($absensi->id);

        if ($mode == 'pulang') {
            $absen->update([
                'photo_pulang' => $file,
                'dokumentasi_pulang' => $dokumentasiPath,
            ]);

            $path = public_path('storage/'. $absen->photo_pulang);
        } else { // masuk
            $absen->update([
                'photo_masuk' => $file,
                'dokumentasi_masuk' => $dokumentasiPath,
            ]);

            $path = public_path('storage/'. $absen->photo_masuk);
        }

        // Tambah watermark
        $pathWatermark = public_path('assets/img/watermark.png');
        $imageName = basename($path);
        $manager = ImageManager::imagick();
        // $image = Image::make($path);
        $image = $manager->read($path);

        $image->place($pathWatermark, 'bottom-center', 0, 0);
        $image->text($nama, 150, 245, function($font) {
            $font->file(public_path('assets/fonts/Roboto-Regular.ttf'));
            $font->color('#000000');
            $font->align('center');
            $font->valign('bottom');
            $font->size(13);
        });
        $image->text($label, 150, 260, function($font) {
            $font->file(public_path('assets/fonts/Roboto-Regular.ttf'));
            $font->color('#000000');
            $font->align('center');
            $font->valign('bottom');
            $font->size(10);
        });
        $image->text($date, 150, 270, function($font) {
            $font->file(public_path('assets/fonts/Roboto-Regular.ttf'));
            $font->color('#000000');
            $font->align('center');
            $font->valign('bottom');
            $font->size(10);
        });
        $image->text($seksi, 150, 280, function($font) {
            $font->file(public_path('assets/fonts/Roboto-Regular.ttf'));
            $font->color('#000000');
            $font->align('center');
            $font->valign('bottom');
            $font->size(10);
        });
        $image->text($pulau, 150, 290, function($font) {
            $font->file(public_path('assets/fonts/Roboto-Regular.ttf'));
            $font->color('#000000');
            $font->align('center');
            $font->valign('bottom');
            $font->size(10);
        });
        $image->text($this->wrapText($lokasi, 11, public_path('assets/fonts/Roboto-Regular.ttf'), 390), 10, 10, function($font) {
            $font->file(public_path('assets/fonts/Roboto-Regular.ttf'));
            $font->color('#000000');
            $font->align('left');   // kiri
            $font->valign('top');   // atas
            $font->size(11);
        });

        $destinationPath = public_path('storage/'. $folderPath);
        $image->save($destinationPath.$imageName);

        return redirect()->route('pjlp-absensi.index')->withNotify("Data absensi berhasil disimpan!");
    }

    // Fungsi untuk membungkus teks
    private function wrapText($text, $fontSize, $fontFile, $maxWidth) {
        $words = explode(' ', $text);
        $lines = '';
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = $currentLine . ' ' . $word;
            $bbox = imagettfbbox($fontSize, 0, $fontFile, trim($testLine));
            $lineWidth = $bbox[2] - $bbox[0];

            if ($lineWidth > $maxWidth) {
                $lines .= trim($currentLine) . "\n";
                $currentLine = $word . ' ';
            } else {
                $currentLine = $testLine . ' ';
            }
        }
        $lines .= trim($currentLine);

        return $lines;
    }
}
