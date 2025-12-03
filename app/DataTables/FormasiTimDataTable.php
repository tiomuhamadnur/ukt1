<?php

namespace App\DataTables;

use App\Models\FormasiTim;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class FormasiTimDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('aksi', function ($item) {
                $editRoute = route('formasi-tim.update', $item->uuid);
                $editButton = "
                    <button class='btn btn-outline-primary' data-toggle='modal'
                            data-target='#editModal'
                            data-url='{$editRoute}'
                            data-periode='{$item->periode}'
                            data-tim_id='{$item->tim_id}'
                            data-pulau_id='{$item->pulau_id}'
                            data-koordinator_id='{$item->koordinator_id}'
                            data-user_id='{$item->user_id}'
                            >
                        <i class='fa fa-edit'></i>
                    </button>";

                $deleteRoute = route('formasi-tim.destroy', $item->uuid);
                $deleteButton = "
                    <a href='javascript:void(0);' class='btn btn-outline-danger' data-toggle='modal'
                    data-target='#deleteModal' data-url='{$deleteRoute}'>
                        <i class='fa fa-trash'></i>
                    </a>";

                return $editButton . ' ' . $deleteButton;
            })
            ->rawColumns(['aksi']);
    }

    public function query(FormasiTim $model): QueryBuilder
    {
        $query = $model->select('formasi_tim.*')->with([
            'tim',
            'pulau',
            'user',
            'koordinator',
        ])->newQuery();

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('formasitim-table')
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
            Column::make('tim.name')->title('Tim'),
            Column::make('pulau.name')->title('Pulau'),
            Column::make('koordinator.name')->title('Koordinator'),
            Column::make('user.name')->title('Personel'),
            Column::computed('aksi')->addClass('text-center text-nowrap')->sortable(false),
        ];
    }

    protected function filename(): string
    {
        return 'FormasiTim_' . date('YmdHis');
    }
}
