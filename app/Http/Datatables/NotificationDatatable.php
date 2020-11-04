<?php

namespace App\Http\Datatables;

use Seongbae\Canvas\Http\Datatable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\DataTableAbstract;
use Carbon\Carbon;
use Helper;

class NotificationDatatable extends Datatable
{
    protected function jsonMethods(DataTableAbstract &$datatables)
    {
        $datatables
        ->addColumn('msg', function ($query) {
            $msg = "";

            if (array_key_exists('image', $query->data))
                $msg = $msg."<img src='".$query->data['image']."' class='img-circle profile-small mr-2'>";

            $msg = $msg.Helper::limitText($query->data['notif_msg'],200);

            return $msg;
        })
        ->editColumn('time', function ($query) {
            return $query->created_at ? with(new Carbon($query->created_at))->diffForHumans() : '';
        })
        ->addColumn('actions', function($query) {
                if (array_key_exists('link', $query->data))
                    return '<a href="'.$query->data['link'].'" class="text-secondary"><i class="far fa-eye "></i></a>';
                else
                    return '';
            })
        //->parameters()
        ->escapeColumns([]);
    }

    protected function columns()
    {
        return [
            Column::make('msg')->title('Notification'),
            Column::make('time')->title('Date')->width(200),
            Column::make('created_at')->hidden(true)
        ];
    }

    protected function orderBy()
    {
        return ['created_at', 'desc'];
    }

    // protected function actions($notification)
    // {
    //     //return "<a href='".$notification->data['link']."'>View</a>";
    // }
}
