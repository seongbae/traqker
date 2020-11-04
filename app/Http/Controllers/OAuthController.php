<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Datatables\ClientDatatable;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use Auth;

class OAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
          
    }

    
}
