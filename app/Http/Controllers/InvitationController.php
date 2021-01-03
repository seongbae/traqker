<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Events\InviteAccepted;
use App\Events\InviteDeclined;
use Seongbae\Discuss\Models\Channel;

class InvitationController extends Controller
{
    public function show(Invitation $invite)
    {
        return view('invites.show', compact('invite'));
    }

    public function update(Request $request, Invitation $invite)
    {
        if ($request->input('submit') == 'accept')
        {
            $invite->accepted_at = Carbon::now()->toDateTimeString();
            $invite->save();

            $invite->team->members()->syncWithoutDetaching($invite->to_user_id, ['access'=>$invite->access,'title'=>$invite->title]);

            $channel = Channel::where('slug',$invite->team->slug)->first();

            if ($channel)
                $channel->subscribe($invite->toUser);

            event(new InviteAccepted($invite));

            flash('Success.  Invite accepted.', 'alert-success');

            return redirect()->route('teams.show', $invite->team);
        }
        else
        {
            $invite->declined_at = Carbon::now()->toDateTimeString();
            $invite->save();

            event(new InviteDeclined($invite));
            flash('Success.  Invite has been declined.', 'alert-danger');

            return redirect()->to('/dashboard');
        }

        return redirect()->back();
    }

    public function destroy(Invitation $invitation)
    {
        $invitation->delete();

        return redirect()->back();
    }
}
