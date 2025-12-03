<?php

namespace App\DataTables;

use App\Models\Seksi;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SeksiDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('aksi', function ($item) {
                $editRoute = route('seksi.update', $item->uuid);
                $editButton = "
                    <button class='btn btn-outline-primary' data-toggle='modal'
                            data-target='#editModal'
                            data-url='{$editRoute}'
                            data-name='{$item->name}'
                            data-code='{$item->code}'
                            data-unit_kerja_id='{$item->unit_kerja_id}'
                            >
                        <i class='fa fa-edit'></i>
                    </button>";

                $deleteRoute = route('seksi.destroy', $item->uuid);
                $deleteButton = "
                    <a href='javascript:void(0);' class='btn btn-outline-danger' data-toggle='modal'
                    data-target='#deleteModal' data-url='{$deleteRoute}'>
                        <i class='fa fa-trash'></i>
                    </a>";

                return $editButton . ' ' . $deleteButton;
            })
            ->rawColumns(['aksi']);
    }

    public function query(Seksi $model): QueryBuilder
    {
        $query = $model->select('seksi.*')->with([
            'unit_kerja',
        ])->newQuery();

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('seksi-table')
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
            Column::make('name')->title('Nama Seksi'),
            Column::make('code')->title('Kode Seksi'),
            Column::make('unit_kerja.name')->title('Unit Kerja'),
            Column::computed('aksi')->addClass('text-center text-nowrap')->sortable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Seksi_' . date('YmdHis');
    }
}
