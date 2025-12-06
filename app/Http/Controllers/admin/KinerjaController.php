<?php

namespace App\Http\Controllers\admin;

use App\DataTables\KinerjaDataTable;
use App\Http\Controllers\Controller;
use App\Models\FormasiTim;
use App\Models\Kegiatan;
use App\Models\Kinerja;
use App\Models\KinerjaPhoto;
use App\Models\Pulau;
use App\Models\Seksi;
use App\Models\User;
use App\Services\ImageUploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KinerjaController extends Controller
{
    //ADMIN
    public function index(KinerjaDataTable $dataTable, Request $request)
    {
        $request->validate([
            'seksi_id' => 'nullable|exists:seksi,id',
            'user_id' => 'nullable|exists:users,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'kegiatan_id' => 'nullable|exists:kegiatan,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'tahun' => 'nullable|numeric',
        ]);

        $seksi_id = $request->seksi_id ?? null;
        $user_id = $request->user_id ?? null;
        $pulau_id = $request->pulau_id ?? null;
        $kegiatan_id = $request->kegiatan_id ?? null;

        $tahun = $request->tahun ?? date('Y');

        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date);
            $endDate   = Carbon::parse($request->end_date);

            // Validasi tahun harus sesuai dengan request tahun
            if ($startDate->year != $tahun || $endDate->year != $tahun) {
                return redirect()->back()->withErrors([
                    'date' => "Tanggal yang dipilih harus berada di tahun $tahun",
                ]);
            }

            // Validasi tahun start & end harus sama
            if ($startDate->year != $endDate->year) {
                return redirect()->back()->withErrors([
                    'date' => "Tanggal awal dan tanggal akhir harus berada di tahun yang sama",
                ]);
            }

            // Validasi start <= end
            if ($startDate->gt($endDate)) {
                return redirect()->back()->withErrors([
                    'date' => "Tanggal awal tidak boleh lebih besar dari tanggal akhir",
                ]);
            }
        }

        $start_date = $request->start_date ?? Carbon::createFromFormat('Y', $tahun)->startOfYear()->toDateString();
        $end_date = $request->end_date ?? Carbon::createFromFormat('Y', $tahun)->endOfYear()->toDateString();

        $user = User::where('user_type_id', 4) //Hanya PJLP
                ->orderBy('name', 'ASC')
                ->get();
        $seksi = Seksi::orderBy('name', 'ASC')->get();
        $pulau = Pulau::orderBy('name', 'ASC')->get();
        $kegiatan = Kegiatan::orderBy('seksi_id', 'asc')->get();
        $tahuns = Kinerja::selectRaw('YEAR(tanggal) as tahun')
                ->distinct()
                ->orderBy('tahun', 'asc')
                ->pluck('tahun');

        return $dataTable->with([
            'seksi_id' => $seksi_id,
            'user_id' => $user_id,
            'pulau_id' => $pulau_id,
            'kegiatan_id' => $kegiatan_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ])->render('page.admin.kinerja.index', compact([
            'user',
            'pulau',
            'seksi',
            'kegiatan',
            'seksi_id',
            'user_id',
            'pulau_id',
            'kegiatan_id',
            'start_date',
            'end_date',
            'tahuns',
            'tahun'
        ]));
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
    public function pjlp_index(KinerjaDataTable $dataTable, Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'pulau_id' => 'nullable|exists:pulau,id',
            'kegiatan_id' => 'nullable|exists:kegiatan,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $periode = date('Y');

        $start_date = $request->start_date ?? Carbon::createFromFormat('Y', $periode)->startOfYear()->toDateString();
        $end_date = $request->end_date ?? Carbon::createFromFormat('Y', $periode)->endOfYear()->toDateString();
        $user_id = Auth::user()->id;

        return $dataTable->with([
            'user_id' => $user_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ])->render('page.users.sigma.pjlp.kinerja.index', compact([
            'start_date',
            'end_date',
        ]));
    }

    public function pjlp_create()
    {
        $periode = date('Y');
        $user = Auth::user();
        $formasi_tim = FormasiTim::where('periode', $periode)
                    ->where('user_id', $user->id)
                    ->first();
        $kegiatan = Kegiatan::where('seksi_id', $formasi_tim->tim->seksi_id)
                    ->orderBy('name', 'asc')
                    ->get();

        return view('page.users.sigma.pjlp.kinerja.create', compact([
            'user',
            'formasi_tim',
            'kegiatan',
        ]));
    }

    public function pjlp_store(Request $request, ImageUploadService $imageService)
    {
        $rawData = $request->validate([
            'kegiatan_id' => 'nullable|exists:kegiatan,id',
            'tanggal' => 'required|date|before_or_equal:today',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
        ], [
            'tanggal.before_or_equal' => 'isian Tanggal tidak boleh di masa depan!',
        ]);

        $request->validate([
            'photo.*' => 'required|file|image',
            'photo' => 'max:3',
        ], [
            'photo.max' => 'Lampiran maksimal hanya 3 photo.'
        ]);

        $periode = date('Y');
        $user = Auth::user();
        $formasi_tim = FormasiTim::where('periode', $periode)
                    ->where('user_id', $user->id)
                    ->first();

        $extra = [
            'user_id'         => $user->id,
            'unit_kerja_id'   => $formasi_tim->tim->seksi->unit_kerja_id,
            'seksi_id'        => $formasi_tim->tim->seksi_id,
            'tim_id'          => $formasi_tim->tim_id,
            'formasi_tim_id'  => $formasi_tim->id,
            'pulau_id'           => $formasi_tim->pulau_id,
        ];

        $data = array_merge($rawData, $extra);

        $kinerja = Kinerja::updateOrCreate($data, $data);

        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $imagePath = $imageService->uploadImage(
                    $file,
                    'kinerja/kegiatan/',
                    null,
                    300,
                    60,
                );

                $datas = [
                    'kinerja_id' => $kinerja->id,
                    'photo' => $imagePath,
                ];

                KinerjaPhoto::updateOrCreate($datas, $datas);
            }
        }

        return redirect()->route('pjlp-kinerja.index')->withNotify('Data Laporan Kinerja berhasil ditambahkan!');
    }
}
