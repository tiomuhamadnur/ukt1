<?php

namespace App\Exports\cuti;

use App\Models\Cuti;
use App\Models\FormasiTim;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CutiExport implements FromView, ShouldAutoSize
{
    public $user_id;
    public $pulau_id;
    public $seksi_id;
    public $disetujui_oleh_id;
    public $tim_id;
    public $status_cuti_id;
    public $start_date;
    public $end_date;

    public function __construct(?int $user_id = null, ?int $pulau_id = null, ?int $seksi_id = null, ?int $disetujui_oleh_id = null, ?int $tim_id, ?int $status_cuti_id = null, ?string $start_date = null, ?string $end_date = null)
    {
        $this->user_id = $user_id;
        $this->pulau_id = $pulau_id;
        $this->seksi_id = $seksi_id;
        $this->disetujui_oleh_id = $disetujui_oleh_id;
        $this->tim_id = $tim_id;
        $this->status_cuti_id = $status_cuti_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {
        $cuti = Cuti::query();

        // Filter by user_id
        $cuti->when($this->user_id, function ($query) {
            return $query->where('user_id', $this->pulau_id);
        });

        // Filter by pulau_id
        $cuti->when($this->pulau_id, function ($query) {
            return $query->whereRelation('user.formasi_tim', 'pulau_id', '=', $this->pulau_id);
        });

        // Filter by seksi_id
        $cuti->when($this->seksi_id, function ($query) {
            return $query->whereRelation('user.formasi_tim.tim', 'seksi_id', '=', $this->seksi_id);
        });

        // Filter by koordinator_id
        // $cuti->when($this->koordinator_id, function ($query) {
        //     $periode = Carbon::now()->format('Y');
        //     $anggota_id = FormasiTim::where('koordinator_id', $this->koordinator_id)->where('periode', $periode)->pluck('anggota_id');
        //     $koordinator_id = FormasiTim::where('koordinator_id', $this->koordinator_id)->where('periode', $periode)->pluck('koordinator_id');
        //     $users_id = $anggota_id->merge($koordinator_id);
        //     return $query->whereIn('user_id', $users_id);
        // });

        // Filter by tim_id
        $cuti->when($this->tim_id, function ($query) {
            return $query->whereRelation('user.formasi_tim', 'tim_id', '=', $this->tim_id);
        });

        // Filter by status_cuti_id
        $cuti->when($this->status_cuti_id, function ($query) {
            return $query->where('status_cuti_id', $this->status_cuti_id);
        });

        // Filter by tanggal
        if ($this->start_date != null and $this->end_date != null) {
            $cuti->when($this->start_date, function ($query) {
                return $query->whereDate('tanggal_awal', '>=', $this->start_date);
            });
            $cuti->when($this->end_date, function ($query) {
                return $query->whereDate('tanggal_akhir', '<=', $this->end_date);
            });
        }

        return view('page.admin.cuti.excel', [
            'cuti' => $cuti->orderBy('tanggal_awal', 'ASC')->get(),
        ]);
    }
}
