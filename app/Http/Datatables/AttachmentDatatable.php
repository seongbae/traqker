<?php

namespace App\Http\Datatables;

use Seongbae\Canvas\Http\Datatable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\DataTableAbstract;
use Carbon\Carbon;
use Auth;

class AttachmentDatatable extends Datatable
{
    protected function jsonMethods(DataTableAbstract &$datatables)
    {
        $datatables
        ->editColumn('label', function ($query) {
            return '<a href="/download/'.$query->id.'">'.$query->label.'</a>';
        })
        ->editColumn('size', function ($query) {
            return $query->size.'mb';
        })
        ->editColumn('created_at_short', function ($query) {
            return $query->created_at->format('Y-m-d');
        })
//        ->editColumn('due_on', function ($query) {
//            return $query->due_on ? with(new Carbon($query->due_on))->format('Y-m-d') : '';
//        })
//        ->editColumn('status', function ($query) {
//            return '<span class="badge badge-'.$query->status_badge.'">'.$query->status.'</span>';
//        })
        ->addColumn('download', function($query) {
            return '<a href="/download/'.$query->id.'"><i class="fas fa-download"></i></a>';
        })
        ->escapeColumns([]);
    }

    protected function htmlMethods(Builder &$html)
    {
        $html->setTableId("attachments-table")
            ->autoWidth(false)
            ->serverSide(true)
            ->processing(true);

    }

    protected function columns()
    {
        return [
            Column::make('label'),
            Column::make('size')->searchable(false),
            Column::make('created_at_short')->title('Date')->searchable(false),
            Column::make('download')->searchable(false),
            Column::make('created_at')->searchable(false)->hidden()
        ];
    }

    protected function orderBy()
    {
        return ['created_at', 'desc'];
    }

    protected function actions($task)
    {
//        if ($task && $task->user_id == Auth::id())
//            return view('tasks.actions', compact('task'));
//        else
//            return "";
    }
}
