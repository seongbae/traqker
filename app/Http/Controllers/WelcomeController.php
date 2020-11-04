<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactusSubmitted;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
}
