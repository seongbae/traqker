<?php

namespace App\Http\Datatables;

use Seongbae\Canvas\Http\Datatable;
use Yajra\DataTables\Html\Column;

class TeamDatatable extends Datatable
{
    protected function columns()
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    protected function orderBy()
    {
        return ['name', 'asc'];
    }

    protected function actions($team)
    {
        return view('teams.actions', compact('team'));
    }
}
