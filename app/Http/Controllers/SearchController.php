<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Http\Datatables\ClientDatatable;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use Auth;
use Spatie\Searchable\Search;
use Laravelista\Comments\Comment;

class SearchController extends Controller
{
	public function search(Request $request)
    {
        $searchResults = (new Search())
            ->registerModel(Task::class, 'name','description')
            ->registerModel(Project::class, 'name', 'description')
            ->perform($request->input('query'));

        return view('canvas::admin.search', compact('searchResults'));
    }
}
