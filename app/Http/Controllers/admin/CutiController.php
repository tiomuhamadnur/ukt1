<?php

namespace App\Http\Controllers\admin;

use App\DataTables\CutiDataTable;
use App\DataTables\CutiPersetujuanDataTable;
use App\DataTables\CutiSayaDataTable;
use App\Exports\cuti\CutiExport;
use App\Models\Cuti;
use App\Models\User;
use App\Models\JenisCuti;
use App\Http\Controllers\Controller;
use App\Mail\CutiMail;
use App\Models\Absensi;
use App\Models\FormasiTim;
use App\Models\KonfigurasiAbsensi;
use App\Models\KonfigurasiCuti;
use App\Models\Pulau;
use App\Models\Seksi;
use App\Models\StatusCuti;
use App\Services\ImageUploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class CutiController extends Controller
{
    // ADMIN
    public function index(CutiDataTable $dataTable, Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'jenis_cuti_id' => 'nullable|exists:jenis_cuti,id',
            'status_cuti_id' => 'nullable|exists:status_cuti,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $user_id = $request->user_id ?? null;
        $pulau_id = $request->pulau_id ?? null;
        $seksi_id = $request->seksi_id ?? null;
        $jenis_cuti_id = $request->jenis_cuti_id ?? null;
        $status_cuti_id = $request->status_cuti_id ?? null;

        $periode = null;

        if (($request->start_date != null) && ($request->end_date != null)) {
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);

            // Pastikan tahun sama
            if ($start->year !== $end->year) {
                return back()->withError('Tanggal awal dan akhir harus dalam tahun yang sama.');
            }

            $periode = $start->year;
            $start_date = $start->toDateString();
            $end_date = $end->toDateString();
        } else {
            // Default: tahun berjalan
            $periode = Carbon::now()->year;
            $start_date = Carbon::createFromDate($periode, 1, 1)->toDateString();
            $end_date = Carbon::createFromDate($periode, 12, 31)->toDateString();
        }


        $user = User::where('user_type_id', 4) //Hanya PJLP
                ->orderBy('name', 'ASC')
                ->whereNot('jabatan_id', 1) //Bukan admin
                ->get();

        $pulau = Pulau::orderBy('name', 'ASC')->get();
        $seksi  = Seksi::orderBy('name', 'ASC')->get();
        $jenis_cuti = JenisCuti::all();
        $status_cuti = StatusCuti::all();

        return $dataTable->with([
            'seksi_id' => $seksi_id,
            'user_id' => $user_id,
            'pulau_id' => $pulau_id,
            'jenis_cuti_id' => $jenis_cuti_id,
            'status_cuti_id' => $status_cuti_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'periode' => $periode,
        ])->render('page.admin.cuti.index', compact([
            'seksi',
            'user',
            'pulau',
            'jenis_cuti',
            'status_cuti',
            'seksi_id',
            'user_id',
            'pulau_id',
            'jenis_cuti_id',
            'status_cuti_id',
            'start_date',
            'end_date',
        ]));
    }

    public function approval_cuti(CutiPersetujuanDataTable $dataTable, Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('kanit')) {
            return redirect()->route('kanit-cuti-approval.index');
        }

        if ($user->hasRole('kasi')) {
            return redirect()->route('kasi-cuti-approval.index');
        }

        return $dataTable->render('page.admin.cuti.approval');
    }

    public function cuti_approve(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cuti,id',
            'no_surat' => 'required|string|max:255|unique:cuti,nomor_surat',
        ]);

        $cuti = Cuti::findOrFail($request->id);

        $tahun = Carbon::parse($cuti->tanggal_awal)->year; // pakai tanggal_awal

        $konfigurasi_cuti = KonfigurasiCuti::where('user_id', $cuti->user_id)
            ->where('periode', $tahun)
            ->where('jenis_cuti_id', 1) // cuti tahunan
            ->firstOrFail();

        // Hindari approve ulang
        if ($cuti->status_cuti_id === 2) {
            return back()->withError('Cuti ini sudah pernah disetujui.');
        }

        $status_absensi_id = 5; //Ijin sakit

        // Validasi sisa cuti
        if ($cuti->jenis_cuti_id === 1) {
            if ($konfigurasi_cuti->jumlah_akhir < $cuti->jumlah) {
                return back()->withError(
                    'Sisa cuti tidak mencukupi. <br> Hanya tersisa ' . $konfigurasi_cuti->jumlah_akhir . ' hari.'
                );
            }

            $konfigurasi_cuti->decrement('jumlah_akhir', $cuti->jumlah);
            $status_absensi_id = 4; //Cuti tahunan
        }

        // Update status cuti
        $cuti->update([
            'status_cuti_id'   => 2,
            'nomor_surat'         => $request->no_surat,
            'diketahui_at'     => Carbon::now(),
            'disetujui_at'     => Carbon::now(),
        ]);

        // Generate absensi otomatis
        $konfigurasi_absensi = KonfigurasiAbsensi::where('jenis_absensi_id', 1)->first();
        for ($date = Carbon::parse($cuti->tanggal_awal); $date->lte(Carbon::parse($cuti->tanggal_akhir)); $date->addDay()) {

            // Hindari absensi double
            $exists = Absensi::where('user_id', $cuti->user_id)
                ->whereDate('tanggal', $date->toDateString())
                ->exists();

            if (!$exists) {
                Absensi::create([
                    'user_id'          => $cuti->user_id,
                    'jenis_absensi_id' => 1,
                    'tanggal'          => $date->copy(),
                    'jam_masuk'        => $konfigurasi_absensi->jam_masuk,
                    'status_masuk'     => 'Datang tepat waktu',
                    'telat_masuk'      => 0,
                    'jam_pulang'       => $konfigurasi_absensi->jam_pulang,
                    'status_pulang'    => 'Pulang tepat waktu',
                    'telat_pulang'     => 0,
                    'status_absensi_id'=> $status_absensi_id,
                ]);
            }
        }

        return back()->withNotify("Pengajuan cuti <strong>{$cuti->user->name}</strong> telah disetujui dan berhasil disimpan.");
    }

    public function cuti_reject(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cuti,id'
        ]);

        $cuti = Cuti::findOrFail($request->id);

        $cuti->update([
            'status_cuti_id' => 3, //Ditolak
        ]);

        return back()->withNotify("Pengajuan cuti <strong>{$cuti->user->name}</strong> berhasil ditolak.");
    }

    public function export_excel(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'seksi_id' => 'nullable|exists:seksi,id',
            'disetujui_oleh_id' => 'nullable|exists:users,id',
            'tim_id' => 'nullable|exists:tim,id',
            'status_cuti_id' => 'nullable|exists:status_cuti,id',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date|required_with:start_date',
        ]);

        $user_id = $request->user_id ?? null;
        $pulau_id = $request->pulau_id ?? null;
        $seksi_id = $request->seksi_id ?? null;
        $disetujui_oleh_id = $request->disetujui_oleh_id ?? null;
        $tim_id = $request->tim_id ?? null;
        $status_cuti_id = $request->status_cuti_id ?? null;
        $start_date = $request->start_date ?? null;
        $end_date = $request->end_date ?? $start_date;

        $waktu = Carbon::now()->format('Ymd');

        return Excel::download(new CutiExport($user_id, $pulau_id, $seksi_id, $disetujui_oleh_id, $tim_id, $status_cuti_id, $start_date, $end_date), $waktu . '_data cuti.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function export_pdf ($uuid) {
        $cuti = Cuti::where('uuid', $uuid)->first();
        if(!$cuti) {
            return back()->withError('Data cuti tidak ditemukan');
        }

        $tanggal = ($cuti->tanggal_awal == $cuti->tanggal_akhir) ? Carbon::parse($cuti->tanggal_awal)->isoFormat('D MMMM Y') : Carbon::parse($cuti->tanggal_awal)->isoFormat('D MMMM Y') . ' s/d ' . Carbon::parse($cuti->tanggal_akhir)->isoFormat('D MMMM Y');
        $tahun = Carbon::parse($cuti->tanggal_awal)->isoFormat('Y');
        $tanggal_approve = 'Jakarta, ' . Carbon::parse($cuti->disetujui_at)->isoFormat('D MMMM Y');
        $pdf = Pdf::loadView('page.admin.cuti.pdf', [
            'cuti' => $cuti,
            'tanggal' => $tanggal,
            'tahun' => $tahun,
            'tanggal_approve' => $tanggal_approve,
        ]);
        return $pdf->stream(Carbon::now()->format('Ymd_') . 'Surat ' . $cuti->jenis_cuti->name . '_' . $cuti->user->name . '.pdf');
    }






    // KANIT
    public function kanit_index(CutiDataTable $dataTable, Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'jenis_cuti_id' => 'nullable|exists:jenis_cuti,id',
            'status_cuti_id' => 'nullable|exists:status_cuti,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $user_id = $request->user_id ?? null;
        $pulau_id = $request->pulau_id ?? null;
        $jenis_cuti_id = $request->jenis_cuti_id ?? null;
        $status_cuti_id = $request->status_cuti_id ?? null;

        $periode = null;

        if (($request->start_date != null) && ($request->end_date != null)) {
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);

            // Pastikan tahun sama
            if ($start->year !== $end->year) {
                return back()->withError('Tanggal awal dan akhir harus dalam tahun yang sama.');
            }

            $periode = $start->year;
            $start_date = $start->toDateString();
            $end_date = $end->toDateString();
        } else {
            // Default: tahun berjalan
            $periode = Carbon::now()->year;
            $start_date = Carbon::createFromDate($periode, 1, 1)->toDateString();
            $end_date = Carbon::createFromDate($periode, 12, 31)->toDateString();
        }


        $user = User::where('user_type_id', 4) //Hanya PJLP
                ->orderBy('name', 'ASC')
                ->whereNot('jabatan_id', 1) //Bukan admin
                ->get();

        $pulau = Pulau::orderBy('name', 'ASC')->get();
        $seksi  = Seksi::orderBy('name', 'ASC')->get();
        $jenis_cuti = JenisCuti::all();
        $status_cuti = StatusCuti::all();

        return $dataTable->with([
            'user_id' => $user_id,
            'pulau_id' => $pulau_id,
            'jenis_cuti_id' => $jenis_cuti_id,
            'status_cuti_id' => $status_cuti_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'periode' => $periode,
        ])->render('page.users.sigma.kanit.cuti.index', compact([
            'seksi',
            'user',
            'pulau',
            'jenis_cuti',
            'status_cuti',
            'user_id',
            'pulau_id',
            'jenis_cuti_id',
            'status_cuti_id',
            'start_date',
            'end_date',
        ]));
    }

    public function kanit_approval(CutiPersetujuanDataTable $dataTable, Request $request)
    {
        return $dataTable->render('page.users.sigma.kanit.cuti.approval');
    }







    // KASI
    public function kasi_index(CutiDataTable $dataTable, Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'jenis_cuti_id' => 'nullable|exists:jenis_cuti,id',
            'status_cuti_id' => 'nullable|exists:status_cuti,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $user_id = $request->user_id ?? null;
        $pulau_id = $request->pulau_id ?? null;
        $jenis_cuti_id = $request->jenis_cuti_id ?? null;
        $status_cuti_id = $request->status_cuti_id ?? null;

        $seksi_id = Auth::user()->seksi_id;

        $periode = null;

        if (($request->start_date != null) && ($request->end_date != null)) {
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);

            // Pastikan tahun sama
            if ($start->year !== $end->year) {
                return back()->withError('Tanggal awal dan akhir harus dalam tahun yang sama.');
            }

            $periode = $start->year;
            $start_date = $start->toDateString();
            $end_date = $end->toDateString();
        } else {
            // Default: tahun berjalan
            $periode = Carbon::now()->year;
            $start_date = Carbon::createFromDate($periode, 1, 1)->toDateString();
            $end_date = Carbon::createFromDate($periode, 12, 31)->toDateString();
        }


        $user = User::where('user_type_id', 4) //Hanya PJLP
                ->whereRelation('formasi_tim.tim', 'seksi_id', '=', $seksi_id)
                ->orderBy('name', 'ASC')
                ->whereNot('jabatan_id', 1) //Bukan admin
                ->get();

        $pulau = Pulau::orderBy('name', 'ASC')->get();
        $seksi  = Seksi::orderBy('name', 'ASC')->get();
        $jenis_cuti = JenisCuti::all();
        $status_cuti = StatusCuti::all();

        return $dataTable->with([
            'seksi_id' => $seksi_id,
            'user_id' => $user_id,
            'pulau_id' => $pulau_id,
            'jenis_cuti_id' => $jenis_cuti_id,
            'status_cuti_id' => $status_cuti_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'periode' => $periode,
        ])->render('page.users.sigma.kasi.cuti.index', compact([
            'seksi',
            'user',
            'pulau',
            'jenis_cuti',
            'status_cuti',
            'seksi_id',
            'user_id',
            'pulau_id',
            'jenis_cuti_id',
            'status_cuti_id',
            'start_date',
            'end_date',
        ]));
    }

    public function kasi_approval(CutiPersetujuanDataTable $dataTable, Request $request)
    {
        $seksi_id = Auth::user()->seksi_id;
        return $dataTable->with([
            'seksi_id' => $seksi_id,
        ])->render('page.users.sigma.kasi.cuti.approval');
    }







    // PJLP
    public function pjlp_index(CutiSayaDataTable $dataTable, Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $periode = null;

        if (($request->start_date != null) && ($request->end_date != null)) {
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);

            // Pastikan tahun sama
            if ($start->year !== $end->year) {
                return back()->withError('Tanggal awal dan akhir harus dalam tahun yang sama.');
            }

            $periode = $start->year;
            $start_date = $start->toDateString();
            $end_date = $end->toDateString();
        } else {
            // Default: tahun berjalan
            $periode = Carbon::now()->year;
            $start_date = Carbon::createFromDate($periode, 1, 1)->toDateString();
            $end_date = Carbon::createFromDate($periode, 12, 31)->toDateString();
        }

        $user_id = Auth::user()->id;

        $konfigurasi_cuti = KonfigurasiCuti::where('periode', $periode)
                    ->where('jenis_cuti_id', 1) //Cuti tahunan
                    ->where('user_id', $user_id)
                    ->first();

        $jumlah_cuti = Cuti::whereYear('tanggal_awal', $periode)
                ->where('jenis_cuti_id', $user_id) //Khusus cuti tahunan
                ->where('user_id', $user_id)
                ->where('status_cuti_id', 2) //Status diterima
                ->sum('jumlah');

        $jatah_cuti = optional($konfigurasi_cuti)->jumlah_awal ?? 0;

        $sisa_cuti = optional($konfigurasi_cuti)->jumlah_akhir ?? 0;

        return $dataTable->with([
            'user_id' => $user_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'periode' => $periode,
        ])->render('page.users.sigma.pjlp.cuti.index', compact([
            'sisa_cuti',
            'start_date',
            'end_date',
        ]));
    }

    public function pjlp_create()
    {
        $jenis_cuti = JenisCuti::all();
        $user_id = Auth::user()->id;
        $periode = date('Y');

        $konfigurasi_cuti = KonfigurasiCuti::where('periode', $periode)
                    ->where('jenis_cuti_id', 1) //Cuti tahunan
                    ->where('user_id', $user_id)
                    ->first();

        $jumlah_cuti = Cuti::whereYear('tanggal_awal', $periode)
                ->where('jenis_cuti_id', $user_id) //Khusus cuti tahunan
                ->where('user_id', $user_id)
                ->where('status_cuti_id', 2) //Status diterima
                ->sum('jumlah');

        $jatah_cuti = optional($konfigurasi_cuti)->jumlah_awal ?? 0;

        $sisa_cuti = optional($konfigurasi_cuti)->jumlah_akhir ?? 0;

        return view('page.users.sigma.pjlp.cuti.create', compact([
            'jenis_cuti',
            'sisa_cuti',
        ]));
    }

    public function pjlp_store(Request $request, ImageUploadService $imageService)
    {
        $request->validate([
            'jenis_cuti_id' => 'required|exists:jenis_cuti,id',
            'tanggal_awal' => 'required|date|after_or_equal:today',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'lampiran' => 'nullable|file|image|required_if:jenis_cuti_id,2',
            'catatan' => 'required|string|max:254'
        ], [
            'tanggal_awal.after_or_equal' => 'Tanggal mulai cuti tidak boleh sebelum hari ini (' . now()->format('d-m-Y') . ').',
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir tidak boleh kurang dari tanggal awal.',
            'lampiran.image' => 'Lampiran harus dalam format image.',
        ]);

        $user = Auth::user();
        $tahun = Carbon::now()->format('Y');

        $user_id = $user->id ?? null;
        $jenis_cuti_id = $request->jenis_cuti_id ?? null;
        $tanggal_awal = $request->tanggal_awal ?? null;
        $tanggal_akhir = $request->tanggal_akhir ?? null;
        $catatan = $request->catatan ?? null;
        $lampiran = $request->lampiran;
        $status_cuti_id = 1;

        $formasi_tim = FormasiTim::where('periode', $tahun)
                    ->where('user_id', $user_id)
                    ->first();

        if(!$formasi_tim) {
            return redirect()
                    ->back()
                    ->withError('Anda belum memiliki formasi tim di tahun ' . $tahun . ', silahkan hubungi admin.');
        }

        $seksi_id = $formasi_tim->tim->seksi_id;
        $unit_kerja_id = $formasi_tim->tim->seksi->unit_kerja_id;

        $kanit_id = User::where('jabatan_id', 2)
            ->where('unit_kerja_id', $unit_kerja_id)
            ->notBanned()
            ->latest('created_at')
            ->value('id');

        $kasi_id = User::where('jabatan_id', 3)
            ->where('unit_kerja_id', $unit_kerja_id)
            ->where('seksi_id', $seksi_id)
            ->notBanned()
            ->latest('created_at')
            ->value('id');

        $diketahui_oleh_id = $kasi_id ?? $kanit_id;
        $disetujui_oleh_id = $kanit_id;

        $tanggalAwal = Carbon::parse($request->tanggal_awal);
        $tanggalAkhir = Carbon::parse($request->tanggal_akhir);

        $jumlahHariCuti = 0;

        while ($tanggalAwal->lessThanOrEqualTo($tanggalAkhir)) {
            $jumlahHariCuti++;
            $tanggalAwal->addDay();
        }

        if($jenis_cuti_id == 1){
            $konfigurasi_cuti = KonfigurasiCuti::where('periode', $tahun)
                            ->where('jenis_cuti_id', 1)
                            ->where('user_id', $user_id)
                            ->first();

            if(!$konfigurasi_cuti) {
                return redirect()
                        ->back()
                        ->withError('Anda belum memiliki konfigurasi cuti di tahun ' . $tahun . ', silahkan hubungi admin.');
            }

            $jumlahSisaCuti = $konfigurasi_cuti->jumlah_akhir;

            if ($jumlahHariCuti > $jumlahSisaCuti){
                return redirect()
                        ->back()
                        ->withError('Di tahun ' . $tahun . ', Jumlah hari cuti yang anda ajukan (' . $jumlahHariCuti . ' hari) melebihi sisa cuti yang anda miliki (' . $jumlahSisaCuti .' hari).');
            }
        }

        $check_cuti = Cuti::where('user_id', $user_id)
                        ->whereYear('tanggal_awal', $tahun)
                        ->where('status_cuti_id', 1)
                        ->first();

        if($check_cuti) {
            return redirect()
                    ->back()
                    ->withError("Pengajuan cuti anda sebelumnya <strong>({$check_cuti->formatted_tanggal_awal})</strong> masih diproses, silahkan hubungi Atasan anda!");
        }

        $data = [
            'user_id' => $user_id,
            'jenis_cuti_id' => $jenis_cuti_id,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'jumlah' => $jumlahHariCuti,
            'diketahui_oleh_id' => $diketahui_oleh_id,
            'disetujui_oleh_id' => $disetujui_oleh_id,
            'catatan' => $catatan,
            'status_cuti_id' => $status_cuti_id,
        ];

        $cuti = Cuti::updateOrCreate($data, $data);

        $imagePath = $imageService->uploadImage(
            $request->file('lampiran'),
            'cuti/lampiran/',
            null,
            400,
            80,
        );

        $cuti->update([
            'lampiran' => $imagePath,
        ]);

        // KIRIM NOTIFIKASI EMAIL
        $nama = $user->name ?? '-';
        $jabatan = $user->jabatan->name ?? '-';
        $pulau = $user->formasi_tim->pulau->name ?? '-';
        $route = route('approval-cuti.index'); //Link untuk show approval via email
        $tanggal = Carbon::parse($tanggal_awal)->format('d-m-Y') . ' s/d ' . Carbon::parse($tanggal_akhir)->format('d-m-Y');
        $lampiran = $imagePath ? storage_path('app/public/' . $imagePath) : null;

        $message = null;
        if (filter_var(env('SEND_NOTIF_CUTI', false), FILTER_VALIDATE_BOOLEAN)) {
            $message = $this->send_email($nama, $jabatan, $pulau, $jumlahHariCuti, $tanggal, $catatan, $route, $lampiran);
        }

        return redirect()->route('pjlp-cuti.index')->withNotify("Data pengajuan cuti berhasil ditambahkan & {$message}.");
    }

    public function send_email($nama, $jabatan, $lokasi_pulau, $jumlah_hari, $tanggal, $alasan, $url, $lampiran)
    {
        $email_tujuan = env('EMAIL_NOTIFICATION');

        if(!$email_tujuan) {
            return "Email tujuan untuk notifikasi belum ditambahkan, silahkan hubungi admin.";
        }

        $mailData = [
            'nama' => $nama,
            'jabatan' => $jabatan,
            'lokasi_pulau' => $lokasi_pulau,
            'jumlah_hari' => $jumlah_hari,
            'tanggal' => $tanggal,
            'alasan' => $alasan,
            'url' => $url,
        ];

        $emailMethod = env('SEND_EMAIL_METHOD', 'send'); // default send

        if ($emailMethod === 'queue') {
            Mail::to($email_tujuan)->queue(new CutiMail($mailData, $lampiran));
        } else {
            Mail::to($email_tujuan)->send(new CutiMail($mailData, $lampiran));
        }

        return "Email notifikasi berhasil dikirim ke Atasan anda";
    }

    public function revoke(string $uuid)
    {
        DB::transaction(function () use ($uuid, &$cuti, &$jumlahAkhir) {

            $cuti = Cuti::with('user')
                ->where('uuid', $uuid)
                ->firstOrFail();

            $tahun = Carbon::parse($cuti->tanggal_awal)->year;

            $konfigurasiCuti = KonfigurasiCuti::where([
                    'periode'        => $tahun,
                    'user_id'        => $cuti->user_id,
                    'jenis_cuti_id'  => $cuti->jenis_cuti_id,
                ])->lockForUpdate()->first();

            if (!$konfigurasiCuti) {
                throw new \Exception(
                    "Data konfigurasi cuti {$cuti->user->name} tahun {$tahun} tidak ditemukan"
                );
            }

            // Hitung & update sisa cuti (atomic)
            $jumlahAkhir = $konfigurasiCuti->jumlah_akhir + $cuti->jumlah;

            $konfigurasiCuti->update([
                'jumlah_akhir' => $jumlahAkhir,
            ]);

            // Hapus file jika ada
            if (!empty($cuti->lampiran)) {
                Storage::delete($cuti->lampiran);
            }

            // Hapus data absensi yang sudah auto isi karena cuti
            Absensi::withTrashed()
                ->where('user_id', $cuti->user_id)
                ->whereBetween('tanggal', [
                    $cuti->tanggal_awal,
                    $cuti->tanggal_akhir
                ])
                ->forceDelete();

            // Hard delete
            $cuti->forceDelete();
        });

        $tahun = Carbon::parse($cuti->tanggal_awal)->year;

        return redirect()
            ->route('cuti.index')
            ->withNotify(
                "Data cuti <b>{$cuti->user->name}</b> berhasil dibatalkan dan sisa cuti menjadi <b>{$jumlahAkhir} hari</b> di tahun <b>{$tahun}</b>!"
            );
    }

    public function pjlp_destroy($uuid) {
        $cuti = Cuti::where('uuid', $uuid)->first();
        if(!$cuti) {
            return back()->withError('Data cuti tidak ditemukan');
        }

        if($cuti->lampiran != null)
        {
            Storage::delete($cuti->lampiran);
        }

        $cuti->forceDelete();

        return redirect()->route('pjlp-cuti.index')->withNotify('Data cuti berhasil dihapus secara permanen!');
    }
}
