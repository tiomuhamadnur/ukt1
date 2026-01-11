<?php

namespace App\DataTables;

use App\Models\KonfigurasiAbsensi;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KonfigurasiAbsensiDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('aksi', function ($item) {
                $editRoute = route('konfigurasi-absensi.update', $item->uuid);
                $tim_ids = $item->tims->pluck('tim_id')->toArray();
                $timsIdsJson = htmlspecialchars(json_encode($tim_ids), ENT_QUOTES, 'UTF-8');
                $editButton = "
                    <button class='btn btn-outline-primary' data-toggle='modal'
                            data-target='#editModal'
                            data-url='{$editRoute}'
                            data-jenis_absensi_id='{$item->jenis_absensi_id}'
                            data-jam_masuk='{$item->jam_masuk}'
                            data-jam_pulang='{$item->jam_pulang}'
                            data-mulai_absen_masuk='{$item->mulai_absen_masuk}'
                            data-selesai_absen_masuk='{$item->selesai_absen_masuk}'
                            data-mulai_absen_pulang='{$item->mulai_absen_pulang}'
                            data-selesai_absen_pulang='{$item->selesai_absen_pulang}'
                            data-toleransi_masuk='{$item->toleransi_masuk}'
                            data-toleransi_pulang='{$item->toleransi_pulang}'
                            data-tim_ids='{$timsIdsJson}'
                            >
                        <i class='fa fa-edit'></i>
                    </button>";

                $deleteRoute = route('konfigurasi-absensi.destroy', $item->uuid);
                $deleteButton = "
                    <a href='javascript:void(0);' class='btn btn-outline-danger' data-toggle='modal'
                    data-target='#deleteModal' data-url='{$deleteRoute}'>
                        <i class='fa fa-trash'></i>
                    </a>";

                return $editButton . ' ' . $deleteButton;
            })
            ->addColumn('jam_kerja', function ($item) {
                return $item->jam_masuk . ' - ' . $item->jam_pulang;
            })
            ->addColumn('range_absen_masuk', function ($item) {
                return $item->mulai_absen_masuk . ' - ' . $item->selesai_absen_masuk;
            })
            ->addColumn('range_absen_pulang', function ($item) {
                return $item->mulai_absen_pulang . ' - ' . $item->selesai_absen_pulang;
            })
            ->addColumn('toleransi_masuk', function ($item) {
                return $item->toleransi_masuk . ' Menit';
            })
            ->addColumn('toleransi_pulang', function ($item) {
                return $item->toleransi_pulang . ' Menit';
            })
            ->addColumn('tim', function ($item) {
                if ($item->tims->isEmpty()) {
                    return "<em class='text-muted'>Tidak ada</em>";
                }

                $html = "<ul style='padding-left:18px; margin:0; list-style-type: disc !important;'>";

                foreach ($item->tims as $item) {
                    $html .= "<li>{$item->tim->name}</li>";
                }

                $html .= "</ul>";

                return $html;
            })
            ->rawColumns(['tim', 'aksi']);
    }

    public function query(KonfigurasiAbsensi $model): QueryBuilder
    {
        $query = $model->select('konfigurasi_absensi.*')->with([
            'jenis_absensi',
        ])->newQuery();

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('konfigurasiabsensi-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->pageLength(50)
                    ->lengthMenu([10, 50, 100, 250, 500, 1000])
                    ->orderBy([8, 'asc'])
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
            Column::make('jenis_absensi.name')->title('Jenis Absensi')->addClass('text-nowrap')->sortable(false),
            Column::make('jam_kerja')->title('Jam Kerja')->addClass('text-nowrap')->sortable(false),
            Column::make('range_absen_masuk')->title('Waktu Absen Masuk')->addClass('text-nowrap')->sortable(false),
            Column::make('range_absen_pulang')->title('Waktu Absen Pulang')->addClass('text-nowrap')->sortable(false),
            Column::make('toleransi_masuk')->title('Toleransi Masuk')->addClass('text-nowrap')->sortable(false),
            Column::make('toleransi_pulang')->title('Toleransi Pulang')->addClass('text-nowrap')->sortable(false),
            Column::computed('tim')->title('Rumpun')->addClass('text-nowrap')->sortable(false),
            Column::computed('aksi')->addClass('text-center text-nowrap')->sortable(false),
        ];
    }

    protected function filename(): string
    {
        return 'KonfigurasiAbsensi_' . date('YmdHis');
    }
}
