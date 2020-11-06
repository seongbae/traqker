<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactusSubmitted;
use Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        if (Auth::check())
            return redirect('/dashboard');

        return view('welcome');
    }
}
