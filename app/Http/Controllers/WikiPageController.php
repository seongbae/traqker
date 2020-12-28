<?php

namespace App\Http\Controllers;

use App\Models\WikiPage;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Project;
use Log;
use GrahamCampbell\Markdown\Facades\Markdown;


class WikiPageController extends Controller
{
    protected $page = '_wiki';

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type, $slug)
    {
        if ($type == 'teams')
        {
            $wikiableType = Team::class;
            $wikiable = Team::where('slug', $slug)->first();
        }
        elseif ($type == 'projects')
        {
            $wikiableType = Project::class;
            $wikiable = Project::where('slug', $slug)->first();
        }

        $this->authorize('viewAny', [ WikiPage::class, $wikiable]);

        $initialPage = WikiPage::where('wikiable_type', $wikiableType)->where('wikiable_id', $wikiable->id)->where('initial_page', 1)->first();

        if ($initialPage)
            return view('wikipages.show', compact('type'))->with('wikipage', $initialPage)->with('page', $this->page);
        else
            return view('wikipages.create')->with('type', $type)->with('slug',$slug)->with('page', $this->page)->with('initial_page', 1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type, $slug)
    {
        if ($type == 'teams')
        {
            $wikiableType = Team::class;
            $wikiable = Team::where('slug', $slug)->first();
        }
        elseif ($type == 'projects')
        {
            $wikiableType = Project::class;
            $wikiable = Project::where('slug', $slug)->first();
        }

        $this->authorize('create', [WikiPage::class, $wikiable]);

        return view('wikipages.create',compact('type','slug'))->with('page',$this->page)->with('initial_page', 0);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $type, $slug)
    {
        if ($type == 'teams')
        {
            $wikiableType = Team::class;
            $wikiable = Team::where('slug', $slug)->first();
        }
        elseif ($type == 'projects')
        {
            $wikiableType = Project::class;
            $wikiable = Project::where('slug', $slug)->first();
        }

        $this->authorize('create', [WikiPage::class, $wikiable]);

        $wikipage = WikiPage::create(array_merge($request->all(), ['wikiable_id'=>$wikiable->id, 'wikiable_type'=>$wikiableType]));

        return $request->input('submit') == 'reload'
            ? redirect()->route('wikipages.edit')
            : redirect()->route('wikipages.show', ['type'=>$type,'slug'=>$slug,'wikiPage'=>$wikipage]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WikiPage  $wikiPage
     * @return \Illuminate\Http\Response
     */
    public function show($type, $slug, WikiPage $wikiPage)
    {
        $this->authorize('view', $wikiPage);

        return view('wikipages.show',compact('type', 'slug'))->with('wikipage', $wikiPage)->with('page', $this->page);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WikiPage  $wikiPage
     * @return \Illuminate\Http\Response
     */
    public function edit($type, $slug, WikiPage $wikiPage)
    {
        $this->authorize('update', $wikiPage);

        return view('wikipages.edit', compact('wikiPage','type','slug'))->with('page', $this->page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WikiPage  $wikiPage
     * @return \Illuminate\Http\Response
     */
    public function update($type, $slug, WikiPage $wikiPage, Request $request)
    {
        $this->authorize('update', $wikiPage);

        $wikiPage->update($request->all());

        return $request->input('submit') == 'reload'
            ? redirect()->back()
            : redirect()->route('wikipages.show',['type'=>$type,'slug'=>$slug,'wikiPage'=>$wikiPage]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WikiPage  $wikiPage
     * @return \Illuminate\Http\Response
     */
    public function destroy($type, $slug, WikiPage $wikiPage)
    {
        $this->authorize('delete', $wikiPage);

        $wikiPage->delete();

        return redirect()->route('wikipages.index',['type'=>$type,'slug'=>$slug]);
    }
}
