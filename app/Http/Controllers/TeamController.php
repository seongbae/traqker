<?php

namespace App\Http\Controllers;

use App\Events\InviteCreated;
use App\Models\Team;
use App\Http\Datatables\TeamDatatable;
use App\Http\Requests\TeamRequest;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Events\InviteAccepted;
use App\Models\Invitation;
use App\Events\UserInvited;
use Log;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Auth::user()->teams;

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
        $team = Team::create(array_merge($request->all(),array('user_id'=>Auth::id(), 'slug'=>$this->createSlug($request->name))));
        $team->members()->attach(Auth::id(),['access'=>'owner','title'=>'Manager']);

        return $request->input('submit') == 'reload'
            ? redirect()->route('teams.create')
            : redirect()->route('teams.show',['team'=>$team]);
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);

        $page = "_teams";

        return view('teams.show', compact('team','page'));
    }

    public function getSettings(Team $team)
    {
        $this->authorize('update', $team);

        $page = "_settings";

        return view('teams.settings', compact('team','page'));
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

        return redirect()->back();
    }

    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);

        $team->delete();

        return redirect()->route('teams.index');
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
            $invitation = Invitation::where('to_user_id', $user->id)->where('team_id', $team->id)->whereNull('accepted_at')->whereNull('declined_at')->first();

            if (!$invitation)
            {
                $invitation = new Invitation(
                    [
                        'email'=>$request->get('email'),
                        'from_user_id'=>Auth::id(),
                        'to_user_id'=>$user->id,
                        'team_id'=>$team->id,
                        'access'=>$request->get('access'),
                        'title'=>$request->get('title')
                    ]);
                $invitation->generateInvitationToken();
                $invitation->save();
            }
        }
        elseif ($team)
        {
            $invitation = Invitation::where('email', '=', $request->get('email'))->whereNull('accepted_at')->whereNull('declined_at')->first();

            if (!$invitation)
            {
                $invitation = new Invitation(
                    [
                        'email'=>$request->get('email'),
                        'from_user_id'=>Auth::id(),
                        'team_id'=>$team->id,
                        'access'=>$request->get('access'),
                        'title'=>$request->get('title')
                    ]);
                $invitation->generateInvitationToken();
                $invitation->save();
            }
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
                        $task->user_id = $team->firstAvailableManagerExcept()->id;
                        $task->save();
                    }
                }

                $project->members()->detach($user);
            }


            $team->members()->detach($user);
        }

        return redirect()->back();
    }

    public function createSlug($title, $id = 0)
    {
        $slug = str_slug($title);
        $allSlugs = $this->getRelatedSlugs($slug, $id);
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        $i = 1;
        $is_contain = true;
        do {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                $is_contain = false;
                return $newSlug;
            }
            $i++;
        } while ($is_contain);
    }

    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Team::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }
}
