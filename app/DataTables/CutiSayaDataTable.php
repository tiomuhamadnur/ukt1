<?php

namespace App\DataTables;

use App\Models\Cuti;
use App\Models\CutiSaya;
use App\Models\KonfigurasiCuti;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CutiSayaDataTable extends DataTable
{
    protected $user_id;
    protected $start_date;
    protected $end_date;
    protected $periode;

    public function with(array|string $key, mixed $value = null): static
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->{$k} = $v;
            }
        } else {
            $this->{$key} = $value;
        }

        return $this;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('aksi', function ($item) {
                $printURL = route('pjlp-cuti.pdf', $item->uuid);
                $printButton = "<a href='javascript:;' class='btn btn-outline-warning'
                                    title='Print' title='Download PDF' data-toggle='modal'
                                    data-target='#modalDownloadPDF'
                                    data-href='{$printURL}'>
                                    <i class='fa fa-print'></i>
                                </a>";

                $lampiranURL = $item->lampiran != null ? asset('storage/' . $item->lampiran) : 'https://img.freepik.com/premium-vector/default-image-icon-vector-missing-picture-page-website-design-mobile-app-no-photo-available_87543-11093.jpg';

                if ($item->status_cuti_id == 1) {
                    $statusCuti = "<h5>*Pengajuan Cuti masih Dalam Proses persetujuan</h5>";
                } elseif ($item->status_cuti_id == 3) {
                    $statusCuti = "<h5>*Pengajuan Cuti <span style='color: red'>Ditolak</span></h5>";
                } else {
                    $statusCuti = "<h5>*Pengajuan Cuti Sudah <span style='color: green'>Disetujui</span> pada Tanggal {$item->updated_at}</h5>";
                }

                // Encode biar aman ditaruh di atribut HTML
                $statusAttr = htmlspecialchars($statusCuti, ENT_QUOTES, 'UTF-8');

                $lampiranButton = "
                    <a href='javascript:;' class='btn btn-outline-primary'
                        title='Lihat lampiran' data-toggle='modal'
                        data-target='#modalDetailPengajuan'
                        data-lampiran='{$lampiranURL}'
                        data-nama='{$item->user->name}'
                        data-jenis_cuti='{$item->jenis_cuti->name} ({$item->jumlah} hari)'
                        data-koordinator='{$item->diketahui_oleh->name}'
                        data-periode='{$item->tanggal_awal} s/d {$item->tanggal_akhir}'
                        data-tim='Pulau {$item->user->pulau->name})'
                        data-catatan='{$item->catatan}'
                        data-status=\"{$statusAttr}\">
                        <i class='fa fa-eye'></i>
                    </a>
                ";

                $deleteRoute = route('pjlp-cuti.destroy', $item->uuid);
                $deleteButton = "
                    <a href='javascript:void(0);' class='btn btn-outline-danger' data-toggle='modal'
                    data-target='#deleteModal' data-url='{$deleteRoute}'>
                        <i class='fa fa-trash'></i>
                    </a>";

                if ($item->status_cuti_id == 2) {
                    return $printButton . ' ' . $lampiranButton;
                }

                return $lampiranButton . ' ' . $deleteButton;
            })
            ->addColumn('jumlah_hari', function ($item) {
                return $item->jumlah . ' hari';
            })
            // ->addColumn('sisa_cuti', function ($item) {
            //     $periode = date('Y');
            //     $user_id = Auth::user()->id;
            //     $jatah = KonfigurasiCuti::where('periode', $periode)
            //                 ->where('user_id', $user_id)
            //                 ->where('jenis_cuti_id', 1) //Cuti tahunan
            //                 ->first();
            //     // Hitung total cuti yang sudah diambil di this->periode itu, SEBELUM cuti ini
            //     $totalDiambil = Cuti::where('user_id', $item->user_id)
            //                 ->whereYear('tanggal_awal', $this->periode)
            //                 ->where('tanggal_awal', '<', $item->tanggal_awal)
            //                 ->sum('jumlah');

            //     return $jatah - $totalDiambil - $item->jumlah . ' hari';
            // })
            ->addColumn('disetujui', function ($item) {
                return $item->status_cuti_id == 2
                    ? ($item->disetujui_oleh->name ?? '-')
                    : '-';
            })
            ->addColumn('status', function ($item) {
                $class = match ($item->status_cuti_id) {
                    1 => 'btn-warning',
                    3  => 'btn-secondary',
                    default    => 'btn-primary',
                };

                return '<span class="btn ' . $class . '">' . e($item->status_cuti->name) . '</span>';
            })
            ->rawColumns(['jumlah_hari', 'disetujui', 'status', 'aksi']);
    }

    public function query(Cuti $model): QueryBuilder
    {
        $query = $model->select('cuti.*')->with([
            'user.jabatan',
            'user.pulau',
            'jenis_cuti',
            // 'user.konfigurasi_cuti',
            'diketahui_oleh',
            'disetujui_oleh',
        ])->newQuery();

        // Filter
        if ($this->user_id != null) {
            $query->where('user_id', $this->user_id);
        }

        if ($this->start_date != null && $this->end_date != null) {
            $clean_start_date = explode('?', $this->start_date)[0];
            $clean_end_date = explode('?', $this->end_date)[0];

            $start = Carbon::parse($clean_start_date)->startOfDay()->format('Y-m-d H:i:s');
            $end = Carbon::parse($clean_end_date)->endOfDay()->format('Y-m-d H:i:s');

            $query->whereBetween('tanggal_awal', [$start, $end]);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('cutisaya-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->pageLength(50)
                    ->lengthMenu([10, 50, 100, 250, 500, 1000])
                    ->orderBy([0, 'asc'])
                    ->selectStyleSingle()
                    ->buttons([
                        [
                            'extend' => 'excel',
                            'text' => 'Export to Excel',
                            'attr' => [
                                'id' => 'datatable-excel',
                                'style' => 'display: none;',
                            ],
                        ],
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('user.name')->title('Nama')->addClass('font-weight-bold text-nowrap')->sortable(true),
            Column::make('user.jabatan.name')->title('Jabatan')->sortable(false),
            Column::make('user.pulau.name')->title('Pulau')->sortable(false),
            Column::make('tanggal_awal')->title('Tanggal Awal')->sortable(true),
            Column::make('tanggal_akhir')->title('Tanggal Akhir')->sortable(true),
            Column::make('jenis_cuti.name')->title('Jenis Izin')->sortable(false),
            Column::computed('jumlah_hari')->title('Jumlah Hari')->sortable(false),
            // Column::computed('sisa_cuti')->title('Sisa Cuti')->sortable(false),
            Column::make('diketahui_oleh.name')->title('Diketahui')->sortable(false),
            Column::computed('disetujui')->title('Disetujui')->sortable(false),
            Column::computed('status')->title('Status')->sortable(false),
            Column::computed('aksi')
                    ->exportable(false)
                    ->printable(false)
                    ->width(60)
                    ->addClass('text-center text-nowrap'),
        ];
    }

    protected function filename(): string
    {
        return 'CutiSaya_' . date('YmdHis');
    }
}
