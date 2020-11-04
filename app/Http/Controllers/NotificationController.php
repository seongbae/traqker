<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\Notification;
use App\Http\Datatables\NotificationDatatable;
use App\Http\Requests\PayoutRequest;
use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->notifications;
        $datatables = NotificationDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('notifications.index', $datatables->html());
    }
}
