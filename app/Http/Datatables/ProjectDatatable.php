<?php

namespace App\Http\Datatables;

use Seongbae\Canvas\Http\Datatable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\DataTableAbstract;
use Carbon\Carbon;

class ProjectDatatable extends Datatable
{
    protected function jsonMethods(DataTableAbstract &$datatables)
    {
        $datatables
        ->editColumn('name', function ($query) {
            return '<a href="'.route('projects.show',['project'=>$query]).'">'.$query->name.'</a>';
        })
        ->editColumn('created_at', function ($query) {
            return $query->created_at ? with(new Carbon($query->created_at))->format('Y-m-d') : '';
        })
        ->escapeColumns([]);
    }

    protected function htmlMethods(Builder &$html)
    {
        $html->stateSave(true)
            ->setTableId("projects-table")
            ->serverSide(true)
            ->processing(true);

    }

    protected function columns()
    {
        return [
            Column::make('name'),
            Column::make('client.name')->title('Client'),
            Column::make('created_at')->title('Created')

        ];
    }

    protected function orderBy()
    {
        return ['name', 'asc'];
    }

    protected function actions($project)
    {
        return view('projects.actions', compact('project'));
    }
}
