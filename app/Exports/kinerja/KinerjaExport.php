<?php

namespace App\Exports\kinerja;

use App\Models\Kinerja;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KinerjaExport implements FromView, ShouldAutoSize
{
    public function __construct(
        public ?int $seksi_id = null,
        public ?int $user_id = null,
        public ?int $pulau_id = null,
        public ?int $kegiatan_id = null,
        public ?string $start_date = null,
        public ?string $end_date = null,
    ) {}

    public function view(): View
    {
        $kinerja = Kinerja::query()
            ->when($this->seksi_id, fn($q) =>
                $q->where('seksi_id', $this->seksi_id)
            )
            ->when($this->user_id, fn($q) =>
                $q->where('user_id', $this->user_id)
            )
            ->when($this->pulau_id, fn($q) =>
                $q->where('pulau_id', $this->pulau_id)
            )
            ->when($this->kegiatan_id, fn($q) =>
                $q->where('kegiatan_id', $this->kegiatan_id)
            )
            ->when($this->start_date && $this->end_date, fn($q) =>
                $q->whereBetween('tanggal', [$this->start_date, $this->end_date])
            )
            ->orderBy('tanggal',  'asc')
            ->get();

        return view('page.admin.kinerja.excel', [
            'kinerja' => $kinerja,
        ]);
    }
}
