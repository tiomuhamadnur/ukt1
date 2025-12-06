<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    protected $user_type_id;

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
                $editRoute = route('user.update', $item->uuid);
                $editButton = "
                    <button class='btn btn-outline-primary' title='Edit User' data-toggle='modal'
                            data-target='#editModal'
                            data-url='{$editRoute}'
                            data-name='{$item->name}'
                            data-email='{$item->email}'
                            data-nik='{$item->nik}'
                            data-nip='{$item->nip}'
                            data-no_hp='{$item->no_hp}'
                            data-tempat_lahir='{$item->tempat_lahir}'
                            data-tanggal_lahir='{$item->tanggal_lahir}'
                            data-alamat='{$item->alamat}'
                            data-bio='{$item->bio}'
                            data-is_plt='{$item->is_plt}'
                            data-user_type_id='{$item->user_type_id}'
                            data-gender_id='{$item->gender_id}'
                            data-jabatan_id='{$item->jabatan_id}'
                            data-pulau_id='{$item->pulau_id}'
                            >
                        <i class='fa fa-edit'></i>
                    </button>";

                $banRoute = route('user.destroy', $item->uuid);
                $color = $item->isBanned() ? 'btn-success' : 'btn-outline-danger';
                $title = $item->isBanned() ? 'Aktifkan User ini' : 'Ban User ini';
                $icon  = $item->isBanned() ? 'fa-user-plus' : 'fa-user-times';
                $banButton = "
                    <a href='javascript:void(0);' title='{$title}' class='btn {$color}' data-toggle='modal'
                    data-target='#banModal' data-url='{$banRoute}'>
                        <i class='fa {$icon}'></i>
                    </a>";

                $changePasswordRoute = route('user.show', $item->uuid);
                $changePasswordButton = "
                    <a href='javascript:void(0);' title='Reset Password' class='btn btn-outline-info' data-toggle='modal'
                    data-target='#resetPasswordModal' data-url='{$changePasswordRoute}'>
                        <i class='fa fa-key'></i>
                    </a>";

                return $editButton . ' ' . $changePasswordButton . ' ' . $banButton;
            })
            ->addColumn('status', function ($item) {
                if ($item->isBanned()) {
                    return "<span class='badge badge-danger'>BANNED</span>";
                }
                return "<span class='badge badge-primary'>ACTIVE</span>";
            })
            ->rawColumns(['status', 'aksi']);
    }

    public function query(User $model): QueryBuilder
    {
        $query = $model->select('users.*')->with([
            'user_type',
            'gender',
            'jabatan',
            'pulau',
        ])->newQuery();

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('user-table')
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
            Column::make('name')->title('Nama')->addClass('font-weight-bold')->sortable(true),
            Column::make('email')->title('Email')->sortable(false),
            Column::make('jabatan.name')->title('Jabatan')->sortable(false),
            Column::make('gender.name')->title('Gender')->sortable(false),
            Column::make('pulau.name')->title('Pulau')->sortable(false),
            Column::computed('status')->title('Status')
                ->addClass('text-center')
                ->sortable(false),
            Column::computed('aksi')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center text-nowrap')
                ->title('Aksi'),
        ];
    }

    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
