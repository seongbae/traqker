<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Datatables\ProjectDatatable;
use App\Http\Datatables\AttachmentDatatable;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;
use App\Models\Client;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\MemberAdded;
use App\Models\Team;
use Illuminate\Support\Facades\Log;
use App\Models\Task;
use App\Models\Section;
use App\Models\Attachment;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Project::with('client')->where('user_id', Auth::id());
        $datatables = ProjectDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('projects.index', $datatables->html());
    }

    public function create()
    {
        $clients = Client::where('user_id', Auth::id())->pluck('id','name')->toArray();
        $teams = Team::where('user_id', Auth::id())->pluck('id','name')->toArray();
        $teamIds = Team::where('user_id', Auth::id())->pluck('id')->toArray();
        $users = User::whereHas('teams', function($q) use($teamIds) {
                        $q->whereIn('teams.id', $teamIds);
                    })->pluck('id','name')->toArray();

        return view('projects.create')
                    ->with('clients', $clients)
                    ->with('users', $users)
                    ->with('teams', $teams)
                    ->with('data', null);
    }

    public function store(ProjectRequest $request)
    {
        $project = Project::create(array_merge($request->all(),['user_id'=>Auth::id()]));
        $project->members()->attach(Auth::id(),['access'=>'owner']);


        return $request->input('submit') == 'reload'
            ? redirect()->route('projects.create')
            : redirect()->route('projects.index');
    }

    public function show(Project $project, Request $request)
    {
        $query = Attachment::where('attached_model', 'App\Models\Task')->whereIn('attached_model_id', $project->tasks->pluck('id'));
        $datatables = AttachmentDatatable::make($query);

        $boards = [];

        $sectionIds = array_map('strval', $project->sections->pluck('id')->toArray());

        foreach ($project->sections()->orderBy('order','asc')->get() as $section)
        {
            $tasks = [];
            foreach($section->tasks as $task)
            {
                $tasks[] = [
                    'id' => $task->id,
                    'title' => $task->name,
                    'class'=>["traqker-kanban-item"]
                ];
            }

            $boards[] = [
                'id' => strval($section->id),
                'title' => $section->name,
                'dragTo'=>$sectionIds,
                'item'=>$tasks

            ];
        }


        return $request->ajax()
            ? $datatables->json()
            : view('projects.show', $datatables->html())->with('project',$project)->with('boards',$boards);
    }

    public function showCalendar(Project $project)
    {
        return view('projects.calendar', compact('project'));
    }

    public function edit(Project $project)
    {
        $clients = Client::where('user_id', Auth::id())->pluck('id','name')->toArray();
        $teams = Team::where('user_id', Auth::id())->pluck('id','name')->toArray();
        $teamIds = Team::where('user_id', Auth::id())->pluck('id')->toArray();
        $users = User::whereHas('teams', function($q) use($teamIds) {
                        $q->whereIn('teams.id', $teamIds);
                    })->pluck('id','name')->toArray();
        $data = $project->members;
        $memberDeleteLink = '/project/'.$project->id.'/user/';
        $additionalFields = array();

        return view('projects.edit', compact('project', 'clients', 'teams', 'users', 'data','memberDeleteLink','additionalFields'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->all());

        if($request->get('project_user'))
        {
            if (!$project->members->contains($request->get('project_user')))
            {
                $project->members()->attach($request->get('project_user'),['rate'=>$request->get('rate')]);
                Mail::to(User::find($request->get('project_user')))->send(new MemberAdded($project));
            }
        }

        return $request->input('submit') == 'reload'
            ? redirect()->route('projects.edit', $project->id)
            : redirect()->route('projects.index');
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index');
    }

    public function removeMember($projectId, $userId)
    {
        $project = Project::find($projectId);

        if ($project->user_id != $userId)
            $project->members()->detach($userId);

        return redirect()->back();
    }

    public function repositionTasks(Request $request)
    {
        $item = $request->item;
        $data = $request->data;

        list($needleType, $needleId) = explode('-', $item);

        $itemSectionId = null;
        $repositionType = null;
        $counter = 1;

        if ($needleType == 'task')
        {
            foreach($data as $item)
            {
                list($searchType, $searchId) = explode('-', $item);

                if ($searchType == 'section')
                {
                    $itemSectionId = $searchId;
                    continue;
                }

                if ($searchType == 'task' && $searchId == $needleId)
                {
                    $task = Task::find($searchId);

                    if ($task->section_id != $itemSectionId)
                    {
                        $task->section_id = $itemSectionId;
                        $repositionType = "SECTION_CHANGE";
                        $task->order = $counter;
                        $task->save();
                        break;
                    }
                    elseif ($itemSectionId == null) {
                        // item is reordered without any section
                        $repositionType = "NO_SECTION";
                        break;
                    } else {
                        // item is reordered in the same section
                        $repositionType = "REORDER_WITHIN_SECTION";
                        break;
                    }
                }
                $counter++;
            }

            // Update order:
            if ($repositionType == "SECTION_CHANGE" || $repositionType == "REORDER_WITHIN_SECTION")
            {
                $counter = 1;
                $inTheSection = false;

                foreach($data as $item)
                {
                    list($searchType, $searchId) = explode('-', $item);

                    if ($item == "section-".$itemSectionId) {
                        $inTheSection = true;
                        continue;
                    }

                    if ($inTheSection)
                    {
                        $task = Task::find($searchId);
                        $task->order = $counter;
                        $task->save();
                        $counter++;
                    }
                }
            }
            elseif ($repositionType == "NO_SECTION")
            {
                $counter = 1;
                foreach($data as $item)
                {
                    list($searchType, $searchId) = explode('-', $item);

                    if ($item == "section-".$itemSectionId) {
                        break;
                    }

                    $task = Task::find($searchId);
                    $task->order = $counter;
                    $task->save();
                    $counter++;
                }
            }
        }
        elseif ($needleType == 'section')
        {
            $counter = 1;
            foreach($data as $item) {
                list($searchType, $searchId) = explode('-', $item);

                if ($searchType == 'section') {
                    $section = Section::find($searchId);
                    $section->order = $counter;
                    $section->save();
                }
                $counter++;
            }
        }
        return redirect()->back();
    }
}
