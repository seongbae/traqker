<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Datatables\TaskDatatable;
use App\Http\Requests\SectionRequest;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Section;
use Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Hour;
use Illuminate\Support\Facades\Mail;
use App\Scopes\ArchiveScope;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Project $project)
    {
        return view('sections.create')->with('project', $project);
    }

    public function store(SectionRequest $request)
    {
        $section = Section::create(array_merge($request->all(), ['user_id'=>Auth::id()]));

        return redirect()->route('projects.show', ['project'=>$section->project]);
    }

    public function edit(Section $section)
    {
        return view('sections.edit', compact('section'));
    }

    public function update(SectionRequest $request, Section $section)
    {
        $section->update($request->all());

        return redirect()->route('projects.show', ['project'=>$section->project]);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('projects.show', ['project'=>$section->project_id]);
    }

}
