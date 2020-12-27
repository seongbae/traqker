<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class SettingsController extends Controller
{
    public function saveUserSettings(User $user, Request $request)
    {
        $user->settings(["daily_reminder_email"=>$this->getCheckboxValue($request->daily_reminder_email)]);

        $user->settings(["browser_notification"=>$this->getCheckboxValue($request->browser_notification)]);

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
