<?php

namespace App\Http\Controllers\admin;

use App\DataTables\AbsensiDataTable;
use App\DataTables\AbsensiSayaDataTable;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JenisAbsensi;
use App\Models\KonfigurasiAbsensi;
use App\Models\Pulau;
use App\Models\Seksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;

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








    public function kanit_index()
    {
        return view('page.admin.arsip.absensi.index');
    }

    public function kasi_index()
    {
        return view('page.admin.arsip.absensi.index');
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
        ]));
    }

    public function pjlp_create()
    {
        $user = Auth::user();
        $tanggal = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $jenis_absensi = JenisAbsensi::findOrFail(1); //Jenis absensi biasa

        return view('page.users.sigma.pjlp.absensi.create', compact([
            'user',
            'tanggal',
            'jenis_absensi',
        ]));
    }

    public function pjlp_store(Request $request)
    {
        $request->validate([
            'photo' => 'required',
            'catatan' => 'nullable|string|max:255',
        ]);

        $img = $request->photo;
        $catatan = $request->catatan;
        $latitude = $request->latitude ?? 'xxx';
        $longitude = $request->longitude ?? 'xxx';

        $now = Carbon::now();
        $tanggal = Carbon::parse($now)->format('Y-m-d');
        $waktu = Carbon::parse($now);
        $konfigurasi_absensi = KonfigurasiAbsensi::where('jenis_absensi_id', 1)->first();

        if(!$konfigurasi_absensi) {
            return back()->withError('Konfigurasi Absensi belum diatur, silahkan hubungi admin.');
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
                $status_absensi = 'Pulang Cepat';
            } else {
                $telat = 0;
                $status_absensi = 'Pulang tepat waktu';
            }
        }
        else {
            return back()->withError('Anda harus melakukan absensi, pada rentang Waktu yang telah ditentukan!');
        }

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

        $absen = Absensi::find($absensi->id);

        if ($mode == 'pulang') {
            $absen->update([
                'photo_pulang' => $file,
            ]);

            $path = public_path('storage/'. $absen->photo_pulang);
        } else { // masuk
            $absen->update([
                'photo_masuk' => $file,
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

        $destinationPath = public_path('storage/'. $folderPath);
        $image->save($destinationPath.$imageName);

        return redirect()->route('pjlp-absensi.index')->withNotify("Data absensi berhasil disimpan!");
    }
}
