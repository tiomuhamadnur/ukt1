<?php

namespace App\DataTables;

use App\Models\Cuti;
use App\Models\KonfigurasiCuti;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KonfigurasiCutiDataTable extends DataTable
{
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
                $editRoute = route('konfigurasi-cuti.update', $item->uuid);
                $editButton = "
                    <button class='btn btn-outline-primary' data-toggle='modal'
                            data-target='#editModal'
                            data-url='{$editRoute}'
                            data-periode='{$item->periode}'
                            data-jenis_cuti_id='{$item->jenis_cuti_id}'
                            data-user_id='{$item->user_id}'
                            data-jumlah_awal='{$item->jumlah_awal}'
                            >
                        <i class='fa fa-edit'></i>
                    </button>";

                $deleteRoute = route('konfigurasi-cuti.destroy', $item->uuid);
                $deleteButton = "
                    <a href='javascript:void(0);' class='btn btn-outline-danger' data-toggle='modal'
                    data-target='#deleteModal' data-url='{$deleteRoute}'>
                        <i class='fa fa-trash'></i>
                    </a>";

                return $editButton . ' ' . $deleteButton;
            })
            ->addColumn('jumlah_awal', function ($item) {
                return $item->jumlah_awal . ' hari';
            })
            ->addColumn('jumlah_akhir', function ($item) {
                $cuti_terpakai = Cuti::whereYear('tanggal_awal', $item->periode)
                                ->where('user_id', $item->user_id)
                                ->where('jenis_cuti_id', $item->user->id) //Khusus cuti tahunan
                                ->sum('jumlah');
                $jumlah_akhir = $item->jumlah_awal - $cuti_terpakai;
                return $jumlah_akhir . ' hari';
            })
            ->rawColumns(['aksi']);
    }

    public function query(KonfigurasiCuti $model): QueryBuilder
    {
        $query = $model->select('konfigurasi_cuti.*')->with([
            'jenis_cuti',
            'user',
        ])->newQuery();

        if ($this->periode != null) {
            $query->where('periode', $this->periode);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('konfigurasicuti-table')
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
            Column::make('periode')->title('Periode'),
            Column::make('user.name')->title('Personel')->addClass('fw-bolder'),
            Column::make('jenis_cuti.name')->title('Jenis Cuti'),
            Column::make('jumlah_awal')->title('Jumlah Awal')->sortable(false),
            Column::make('jumlah_akhir')->title('Jumlah Sisa')->sortable(false),
            Column::computed('aksi')->addClass('text-center text-nowrap')->sortable(false),
        ];
    }

    protected function filename(): string
    {
        return 'KonfigurasiCuti_' . date('YmdHis');
    }
}
