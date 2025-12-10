<?php

namespace App\Exports\absensi;

use App\Models\Absensi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbsensiExport implements FromView, ShouldAutoSize
{
    public function __construct(
        public ?int $seksi_id = null,
        public ?int $user_id = null,
        public ?int $pulau_id = null,
        public ?string $start_date = null,
        public ?string $end_date = null,
    ) {}

    public function view(): View
    {
        $absensi = Absensi::query()
            ->when($this->seksi_id, fn($q) =>
                $q->whereRelation('user.formasi_tim.tim', 'seksi_id', $this->seksi_id)
            )
            ->when($this->user_id, fn($q) =>
                $q->where('user_id', $this->user_id)
            )
            ->when($this->pulau_id, fn($q) =>
                $q->whereRelation('user.formasi_tim', 'pulau_id', $this->pulau_id)
            )
            ->when($this->start_date && $this->end_date, fn($q) =>
                $q->whereBetween('tanggal', [$this->start_date, $this->end_date])
            )
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_masuk', 'asc')
            ->orderBy('jam_pulang', 'asc')
            ->get();

        return view('page.admin.absensi.excel', compact(['absensi']));
    }
}
