<?php

namespace App\Http\Datatables;

use Seongbae\Canvas\Http\Datatable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\DataTableAbstract;
use Carbon\Carbon;
use Auth;

class TaskDatatable extends Datatable
{
    protected function jsonMethods(DataTableAbstract &$datatables)
    {
        $datatables
        ->addColumn('check', function ($query) {
            return '<div class="custom-control custom-checkbox">
                          <input class="custom-control-input mark-complete" type="checkbox" id="task_'.$query->id.'" value="complete">
                          <label for="task_'.$query->id.'" class="custom-control-label"></label>
                        </div>';
        })
        ->addColumn('name_link', function ($query) {
            return '<a href="'.route('tasks.show',['task'=>$query]).'" data-id="'.$query->id.'">'.$query->name.'</a>';
        })
        ->editColumn('due_on', function ($query) {
            return $query->due_on ? with(new Carbon($query->due_on))->format('Y-m-d') : '';
        })
        ->editColumn('status', function ($query) {
            return '<span class="badge badge-'.$query->status_badge.'">'.$query->status.'</span>';
        })
        ->addColumn('project', function($query) {
            if ($query->project_id)
                return '<a href="'.route('projects.show',['project'=>$query->project]).'">'.$query->project->name.'</a>';
            else
                return '';
        })
        ->addColumn('assignees', function ($query) {
            $membersHtml = "";

            if ($query->users->count() > 0)
            {
                foreach($query->users as $member)
                {
                    $membersHtml .= '<img src="'.$member->photo .'" alt="{'.$member->name .'" title="'.$member->name .'" class="rounded-circle profile-small mr-1">';
                }
            }

            return $membersHtml;
        })
        ->escapeColumns([]);
    }

    protected function htmlMethods(Builder &$html)
    {
        $html->stateSave(false)
            ->setTableId("tasks-table")
            ->language(['processing'=>'<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>'])
            ->serverSide(true)
            ->pageLength(25)
            ->processing(true);
    }

    protected function columns()
    {
        return [
            Column::make('name_link'), //->data('name'),
            Column::make('assignees'),
            Column::make('project')->title('Project'),
            Column::make('due_on')->title('Due')->width(150),
            Column::make('created_at')->hidden()
        ];
    }

    protected function orderBy()
    {
        return ['created_at', 'desc'];
    }

    protected function actions($task)
    {
        if ($task && $task->user_id == Auth::id())
            return view('tasks.actions', compact('task'));
        else
            return "";
    }
}
