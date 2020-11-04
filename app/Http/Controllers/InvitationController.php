<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invitation;

class InvitationController extends Controller
{
    public function destroy(Invitation $invitation)
    {
        $invitation->delete();

        return redirect()->back();
    }
}
