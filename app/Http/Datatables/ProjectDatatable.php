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
        ->addColumn('members', function ($query) {
            $membersHtml = "";

            if ($query->members->count() > 0)
            {
                foreach($query->members as $member)
                {
                    $membersHtml .= '<img src="/storage/'.$member->photo .'" alt="{'.$member->name .'" title="'.$member->name .'" class="img-circle elevation-2 " style="width:24px;">';
;
                }
            }

            return $membersHtml;
        })
        ->escapeColumns([]);
    }

    protected function htmlMethods(Builder &$html)
    {
        $html->stateSave(true)
            ->searching(false)
            ->paging(false)
            ->setTableId("projects-table")
            ->serverSide(true)
            ->processing(true);

    }

    protected function columns()
    {
        return [
            Column::make('name'),
            Column::make('members')
            //Column::make('created_at')->title('Created')

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
