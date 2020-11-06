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

        if ($request->ajax())
            return response()->json(['section'=>$section], 201);


        return redirect()->route('projects.show', ['project'=>$section->project]);
    }

    public function edit(Section $section)
    {
        return view('sections.edit', compact('section'));
    }

    public function update(SectionRequest $request, Section $section)
    {
        $section->update($request->all());

        if ($request->ajax())
            return $request->json([], 200);

        return redirect()->route('projects.show', ['project'=>$section->project]);
    }

    public function updateOrders(Request $request)
    {
        if ($request->orders)
        {
            $order = 1;
            $placeholders = implode(',',array_fill(0, count($request->orders), '?'));
            $sections = Section::whereIn('id', $request->orders)->orderByRaw("field(id,{$placeholders})", $request->orders)->get();

            foreach($sections as $section)
            {
                $section->order = $order;
                $section->save();
                $order++;
            }
        }

        return $request->json([], 200);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('projects.show', ['project'=>$section->project_id]);
    }

}
