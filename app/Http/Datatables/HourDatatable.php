<?php

namespace App\Http\Datatables;

use Seongbae\Canvas\Http\Datatable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\DataTableAbstract;
use Carbon\Carbon;

class HourDatatable extends Datatable
{
    protected function jsonMethods(DataTableAbstract &$datatables)
    {
        $datatables
        ->editColumn('created_at', function ($query) {
            return $query->created_at ? with(new Carbon($query->created_at))->format('Y-m-d') : '';
        })
        ->editColumn('updated_at', function ($query) {
            return $query->updated_at ? with(new Carbon($query->updated_at))->format('Y-m-d') : '';;
        })
        ->editColumn('hours', function ($query) {
            return '<a href="'.route('hours.show',['hour'=>$query]).'">'.$query->hours.'</a>';
        })
        ->editColumn('description', function ($query) {
            return '<a href="'.route('hours.show',['hour'=>$query]).'">'.$query->description.'</a>';
        })
        ->escapeColumns([]);
    }

    protected function columns()
    {
        return [
            Column::make('hours'),
            Column::make('description'),
            Column::make('project.name')->title('Project'),
            Column::make('created_at')->title('Created')
        ];
    }

    protected function orderBy()
    {
        return ['created_at', 'desc'];
    }

    protected function actions($hour)
    {
        return view('hours.actions', compact('hour'));
    }
}
