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
        ->editColumn('name', function ($query) {
            return '<a href="'.route('tasks.show',['task'=>$query]).'">'.$query->name.'</a>';
        })
        ->editColumn('due_on', function ($query) {
            return $query->due_on ? with(new Carbon($query->due_on))->format('Y-m-d') : '';
        })
        ->editColumn('status', function ($query) {
            return '<span class="badge badge-'.$query->status_badge.'">'.$query->status.'</span>';
        })
        ->addColumn('project', function($query) {
            if ($query->project_id)
                return '<a href="'.route('projects.show',['project'=>$query->project_id]).'">'.$query->project->name.'</a>';
            else
                return '';
        })
        ->escapeColumns([]);
    }

    protected function htmlMethods(Builder &$html)
    {
        $html->stateSave(true)
            ->setTableId("tasks-table")
            ->serverSide(true)
            ->processing(true);

    }

    protected function columns()
    {
        return [
            Column::make('name'),
            Column::make('project')->title('Project'),
            Column::make('assigned.name')->title('Assignee')->width(150),
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