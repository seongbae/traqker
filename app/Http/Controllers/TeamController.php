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
            : redirect()->route('teams.show',['team'=>$team]);
    }

    public function addMember(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $team = Team::find($request->get('team_id'));
        $user = User::where('email', $request->get('email'))->first();

        if ($request->get('user_id'))
            $team->members()->updateExistingPivot($request->get('user_id'), ['title'=>$request->get('title'), 'access'=>$request->get('access')]);
        elseif ($team && $user && !$team->members->contains($user))
        {
            $team->members()->attach($user, ['access'=>$request->get('access'),'title'=>$request->get('title')]);
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
        $this->authorize('update', $team);

        if ($team->firstAvailableManagerExcept($user) == null)
            return redirect()->back();

        if ($team && $user)
        {
            foreach($team->projects as $project)
            {
                foreach($project->tasks as $task)
                {
                    if ($task->assigned_to === $user->id)
                    {
                        $task->assigned_to = null;
                        $task->save();
                    }

                    if ($task->user_id === $user->id)
                    {
                        $task->user_id = $team->firstAvailableManager()->id;
                        $task->save();
                    }
                }

                $project->members()->detach($user);
            }


            $team->members()->detach($user);
        }

        return redirect()->back();
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);

        return view('teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $this->authorize('update', $team);

        return view('teams.edit', compact('team'));
    }

    public function update(TeamRequest $request, Team $team)
    {
        $this->authorize('update', $team);

        $team->update($request->all());

        return $request->input('submit') == 'reload'
            ? redirect()->route('teams.edit', $team->id)
            : redirect()->route('teams.show', ['team'=>$team]);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);

        $team->delete();

        return redirect()->route('teams.index');
    }
}
