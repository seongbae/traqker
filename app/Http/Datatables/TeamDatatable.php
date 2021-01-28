<?php

namespace App\Http\Datatables;

use Carbon\Carbon;
use Seongbae\Canvas\Http\Datatable;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Column;

class TeamDatatable extends Datatable
{
    protected function jsonMethods(DataTableAbstract &$datatables)
    {
        $datatables
            ->editColumn('name', function ($query) {
                return '<a href="'.route('teams.show',['team'=>$query]).'">'.$query->name.'</a>';
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
                        $membersHtml .= '<img src="'.$member->photo .'" alt="{'.$member->name .'" title="'.$member->name .'" class=" rounded-circle profile-small mr-1">';
                    }
                }

                return $membersHtml;
            })
            ->escapeColumns([]);
    }

    protected function columns()
    {
        return [
            Column::make('name'),
            Column::make('members')
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
