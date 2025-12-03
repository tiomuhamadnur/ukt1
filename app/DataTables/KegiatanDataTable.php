<?php

namespace App\DataTables;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KegiatanDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('aksi', function ($item) {
                $editRoute = route('kegiatan.update', $item->uuid);
                $editButton = "
                    <button class='btn btn-outline-primary' data-toggle='modal'
                            data-target='#editModal'
                            data-url='{$editRoute}'
                            data-name='{$item->name}'
                            data-code='{$item->code}'
                            data-seksi_id='{$item->seksi_id}'
                            >
                        <i class='fa fa-edit'></i>
                    </button>";

                $deleteRoute = route('kegiatan.destroy', $item->uuid);
                $deleteButton = "
                    <a href='javascript:void(0);' class='btn btn-outline-danger' data-toggle='modal'
                    data-target='#deleteModal' data-url='{$deleteRoute}'>
                        <i class='fa fa-trash'></i>
                    </a>";

                return $editButton . ' ' . $deleteButton;
            })
            ->rawColumns(['aksi']);
    }

    public function query(Kegiatan $model): QueryBuilder
    {
        $query = $model->select('kegiatan.*')->with([
            'seksi',
        ])->newQuery();

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('kegiatan-table')
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
            Column::make('name')->title('Nama Kegiatan'),
            Column::make('code')->title('Kode Kegiatan'),
            Column::make('seksi.name')->title('Seksi'),
            Column::computed('aksi')->addClass('text-center text-nowrap')->sortable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Kegiatan_' . date('YmdHis');
    }
}
