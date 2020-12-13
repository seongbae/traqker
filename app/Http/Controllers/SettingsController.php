<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SettingsController extends Controller
{
    public function saveUserSettings(User $user, Request $request)
    {
        $user->settings(["daily_reminder_email"=>$this->getCheckboxValue($request->daily_reminder_email)]);

        return redirect()->back();
    }

    public function getCheckboxValue($field)
    {
        if ($field == null)
            return 0;
        else
            return 1;
    }
}
