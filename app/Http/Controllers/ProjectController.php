<?php

namespace App\Http\Controllers;

use App\Events\AddedToProject;
use App\Http\Resources\GanttResource;
use App\Http\Resources\MemberResource;
use App\Models\Project;
use App\Http\Datatables\ProjectDatatable;
use App\Http\Datatables\AttachmentDatatable;
use App\Http\Requests\ProjectRequest;
use App\Scopes\ArchiveScope;
use App\Scopes\CompletedScope;
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
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Auth::user()->projects;
        $datatables = ProjectDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('projects.index', $datatables->html());
    }

    public function indexArchived(Request $request)
    {
        $query = Auth::user()->projects()->withoutGlobalScope(ArchiveScope::class);
        $datatables = ProjectDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('projects.index', $datatables->html());
    }

    public function create()
    {
        $projects = Project::where('user_id', Auth::id())->pluck('id','name')->toArray();
        $teams = Auth::user()->teamsWithManagerialAccess->pluck('id','name')->toArray();
        $teamIds = Team::where('user_id', Auth::id())->pluck('id')->toArray();
        $users = User::whereHas('teams', function($q) use($teamIds) {
                        $q->whereIn('teams.id', $teamIds);
                    })->pluck('id','name')->toArray();


        return view('projects.create')
                    ->with('projects', $projects)
                    ->with('users', $users)
                    ->with('teams', $teams)
                    ->with('data', null);
    }

    public function store(ProjectRequest $request)
    {
        $project = Project::create(array_merge($request->all(),['user_id'=>Auth::id(), 'slug'=>$this->createSlug($request->name)]));

        $project->members()->attach(Auth::id(),['access'=>'owner']);

        if ($request->team_id)
            $project->teams()->attach($request->team_id);

        return $request->input('submit') == 'reload'
            ? redirect()->route('projects.create')
            : redirect()->route('projects.show',['project'=>$project]);
    }

    public function show(Project $project, Request $request)
    {
        $page = "_list";

        $this->authorize('view', $project);

        $query = Attachment::where('attached_model', 'App\Models\Task')->whereIn('attached_model_id', $project->tasks->pluck('id'));
        $datatables = AttachmentDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('projects.show', $datatables->html(), compact('project','page'));
    }

    public function showBoard(Project $project)
    {
        $page = "_board";

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

        return view('projects.board', compact('project','boards', 'page'));
    }


    public function showCalendar(Project $project)
    {
        $page = "_calendar";

        return view('projects.calendar', compact('project', 'page'));
    }

    public function showGantt(Project $project)
    {
        $page = "_timeline";

        $tasks = GanttResource::collection($project->tasks()->withoutGlobalScope(CompletedScope::class)->orderBy('start_on')->where(function ($query) {
                $query->orWhereNotNull('start_on');
                $query->orWhereNotNull('due_on');
            })->get());

        return view('projects.gantt', compact('project', 'tasks', 'page'));
    }

    public function showFiles(Request $request, Project $project)
    {
        $query = Attachment::where('attached_model', 'App\Models\Task')->whereIn('attached_model_id', $project->tasks->pluck('id'));
        $datatables = AttachmentDatatable::make($query);
        $page = "_files";

        return $request->ajax()
            ? $datatables->json()
            : view('projects.files', $datatables->html(), compact('project','page'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $projects = Project::where('user_id', Auth::id())->where('id','!=',$project->id)->pluck('id','name')->toArray();
        $teams = Team::where('user_id', Auth::id())->pluck('id','name')->toArray();
        $teamIds = Team::where('user_id', Auth::id())->pluck('id')->toArray();
        $users = User::whereHas('teams', function($q) use($teamIds) {
                        $q->whereIn('teams.id', $teamIds);
                    })->pluck('id','name')->toArray();
        $data = $project->members;

        $availableUsers = [];
        foreach(Auth::user()->teams as $team)
        {
            if ($team->pivot->access == 'owner' || $team->pivot->access == 'manager')
            {
                foreach($team->members as $member)
                {
                    if (!in_array($member->id, $availableUsers))
                        $availableUsers[] = array('value'=>$member->id, 'text'=>$member->name);
                }
            }
        }

        $projectUsers = MemberResource::collection($project->members);

        return view('projects.edit', compact('project', 'projects', 'teams', 'users', 'data','availableUsers','projectUsers'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $project->update($request->all());

        if ($request->has('users'))
        {
            if ($request->users == null)
                $project->members()->detach();
            else
            {
                $changes = $project->members()->sync(explode(",", $request->users));

                if (count($changes['attached'])>0)
                    event(new AddedToProject(User::find($changes['attached']), $project));
            }
        }

        if ($request->team_id)
            $project->teams()->sync($request->team_id);

        return $request->input('submit') == 'reload'
            ? redirect()->route('projects.edit', $project)
            : redirect()->route('projects.show', $project);
    }

    public function archive(ProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $project->update($request->all());

        return $request->input('submit') == 'reload'
            ? redirect()->route('projects.edit', $project)
            : redirect()->route('projects.index');
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

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
        return Project::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }
}
