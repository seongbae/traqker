<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactusSubmitted;
use Illuminate\Support\Facades\View;
use Camroncade\Timezone\Facades\Timezone;
use Auth;
use App\Models\Team;
use App\Http\Datatables\TeamDatatable;
use App\Http\Requests\TeamRequest;


class HomeController extends Controller
{
    public function index()
    {
        return view('canvas::frontend.home');
    }

    public function showHome()
    {
        return view('canvas::admin.home');
    }

    public function home()
    {
        $comments = Comment::all();

        // return view('canvas::frontend.home',compact('comments');
    }

    public function showAccount()
    {
        $user = Auth::user();

        $timezone_select = Timezone::selectForm(
            $user->timezone ? $user->timezone : 'US/Central',
            '',
            ['class' => 'form-control', 'name' => 'timezone']
        );

        $query = Team::query();
        $datatables = TeamDatatable::make($query);

        return view('canvas::admin.account', $datatables->html())
            ->with('user', $user)
            ->with('timezone_select', $timezone_select);
    }

    public function showContact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $emails = $this->addressToArray(option('notification_email'));

        Mail::to($emails)
            ->send(new ContactusSubmitted($request->get('name'), $request->get('email'), $request->get('phone'), $request->get('message')));

        flash('E-mail sent!', 'alert-success');

        return redirect()->back();
    }


    private function addressToArray($emails)
    {
        if( strpos($emails, ',') !== false )
            return explode(",",$emails);
        elseif( strpos($emails, ';') !== false )
            return explode(";",$emails);
        else
            return $emails;

    }


    public function markNotification(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return response()->noContent();
    }





}
