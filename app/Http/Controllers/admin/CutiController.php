<?php

namespace App\Http\Controllers\admin;

use App\DataTables\CutiDataTable;
use App\DataTables\CutiPersetujuanDataTable;
use App\DataTables\CutiSayaDataTable;
use App\Models\Cuti;
use App\Models\User;
use App\Models\JenisCuti;
use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\Storage;

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

        $jumlah = $jatah_cuti - $jumlah_cuti;

        return $dataTable->with([
            'user_id' => $user_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'periode' => $periode,
        ])->render('page.users.sigma.pjlp.cuti.index', compact([
            'jumlah',
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

        $jumlah = $jatah_cuti - $jumlah_cuti;

        return view('page.users.sigma.pjlp.cuti.create', compact([
            'jenis_cuti',
            'jumlah',
        ]));
    }

    public function pjlp_store(Request $request, ImageUploadService $imageService)
    {
        $request->validate([
            'jenis_cuti_id' => 'required|exists:jenis_cuti,id',
            'tanggal_awal' => 'required|date|after_or_equal:today',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'lampiran' => 'nullable|file|image',
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
                    ->route('dashboard.index')
                    ->withError('Anda belum memiliki formasi tim di tahun ' . $tahun . ', silahkan hubungi admin.');
        }

        $disetujui_oleh_id = $user_id;
        $diketahui_oleh_id = $user_id;
        $jabatan_id = $user->jabatan_id;

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
                        ->route('dashboard.index')
                        ->withError('Anda belum memiliki konfigurasi cuti di tahun ' . $tahun . ', silahkan hubungi admin.');
            }

            $jumlahSisaCuti = $konfigurasi_cuti->jumlah_akhir;

            if ($jumlahHariCuti > $jumlahSisaCuti){
                return redirect()
                        ->route('dashboard.index')
                        ->withError('Di tahun ' . $tahun . ', Jumlah hari cuti yang anda ajukan (' . $jumlahHariCuti . ' hari) melebihi sisa cuti yang anda miliki (' . $jumlahSisaCuti .' hari).');
            }
        }

        $check_cuti = Cuti::where('user_id', $user_id)
                        ->whereYear('tanggal_awal', $tahun)
                        ->where('status_cuti_id', 1)
                        ->first();

        if($check_cuti) {
            return redirect()
                    ->route('dashboard.index')
                    ->withError('Pengajuan cuti anda sebelumnya <strong>(' . $check_cuti->tanggal_awal ?? 'N/A' . ')</strong> masih diproses, silahkan hubungi Koordinator anda!');
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

        return redirect()->route('pjlp-cuti.index')->withNotify('Data pengajuan cuti berhasil ditambahkan.');
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

    public function pjlp_pdf ($uuid) {
        $cuti = Cuti::where('uuid', $uuid)->first();
        if(!$cuti) {
            return back()->withError('Data cuti tidak ditemukan');
        }
    }
}
