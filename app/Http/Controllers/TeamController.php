<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Http\Datatables\TeamDatatable;
use App\Http\Requests\TeamRequest;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Events\TeamMemberAdded;
use App\Models\Invitation;
use App\Events\UserInvited;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Team::query();
        $datatables = TeamDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('teams.index', $datatables->html());
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(TeamRequest $request)
    {
        $team = Team::create(array_merge($request->all(),array('user_id'=>Auth::id())));
        $team->members()->attach(Auth::id(),['access'=>'owner','title'=>'Manager']);

        return $request->input('submit') == 'reload'
            ? redirect()->route('teams.create')
            : redirect()->to('/my-account#teams/');
    }

    public function addMember(Request $request)
    {
        $team = Team::find($request->get('team_id'));
        $user = User::where('email', $request->get('email'))->first();

        if ($request->get('user_id'))
            $team->members()->updateExistingPivot($request->get('user_id'), ['title'=>$request->get('title'),'rate'=>$request->get('rate'),'rate_frequency'=>$request->get('rate_frequency')]);
        elseif ($team && $user && !$team->members->contains($user))
        {
            $team->members()->attach($user, ['access'=>'member','title'=>$request->get('title'),'rate'=>$request->get('rate'),'rate_frequency'=>$request->get('rate_frequency')]);
            event(new TeamMemberAdded(Auth::user(), $team, $user, 'You have been added to '.$team->name));
        }
        elseif ($team)
        {
            $invitation = Invitation::where('email', '=', $request->get('email'))->first();

            if (!$invitation)
            {
                $invitation = new Invitation(['email'=>$request->get('email'),'user_id'=>Auth::id(),'team_id'=>$team->id]);
                $invitation->generateInvitationToken();
                $invitation->save();
            }

            event(new UserInvited($invitation, $team));
        }

        return redirect()->back();
    }

    public function removeMember(Team $team, User $user)
    {
        if ($team && $user)
            $team->members()->detach($user);

        return redirect()->back();
    }

    public function show(Team $team)
    {
        return view('teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        return view('teams.edit', compact('team'));
    }

    public function update(TeamRequest $request, Team $team)
    {
        $team->update($request->all());

        return $request->input('submit') == 'reload'
            ? redirect()->route('teams.edit', $team->id)
            : redirect()->route('teams.index');
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(Team $team)
    {
        $team->delete();

        return redirect()->route('teams.index');
    }
}
