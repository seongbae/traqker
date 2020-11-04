<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Datatables\ClientDatatable;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use Auth;
use App\Models\Project;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Hour;
use Illuminate\Support\Facades\Input;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $userHours = array();
        $projects = Project::where('user_id', Auth::id())->get();

        $dateRange = strip_tags($request->get('reportrange'));

        $dates = array();

        if ($dateRange)
        {
            $dates = explode(' - ', $dateRange);
            $startDate = Carbon::parse($dates[0]);
            $endDate = Carbon::parse($dates[1].' 11:59:59');

            $userHours = array();

            foreach(Auth::user()->myTeams as $team)
            {
                foreach($team->members as $user)
                {

                    if ($user->id != Auth::id())
                    {
                        $totalHours = 0;
                        $totalPay = 0;

                        $tasksComplete = $user->tasks()->where('status','complete')->where('paid',0)->whereBetween('completed_on', [$startDate, $endDate])->get();

                        foreach($tasksComplete as $taskComplete)
                        {
                            $totalHours += $taskComplete->total_hours;
                            $totalPay += $taskComplete->total_hours * $user->getRate($taskComplete);
                        }

                        if ($totalHours>0)
                            $userHours[$user->id] = array('user'=>$user, 'tasks'=>$tasksComplete, 'total_hours'=>$totalHours, 'total_pay'=>$totalPay);
                    }

                }
            }
        }
        else
        {
            $start = Carbon::now()->subDays(30)->format('m/d/Y');
            $end = Carbon::now()->format('m/d/Y');
            $dates[0] = $start;
            $dates[1] = $end;
        }

        return view('reports.index',compact('userHours','projects'))->with('start', $dates[0])->with('end', $dates[1]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(ClientRequest $request)
    {
        Client::create(array_merge($request->all(), ['user_id'=>Auth::id()]));

        return $request->input('submit') == 'reload'
            ? redirect()->route('clients.create')
            : redirect()->route('clients.index');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->all());

        return $request->input('submit') == 'reload'
            ? redirect()->route('clients.edit', $client->id)
            : redirect()->route('clients.index');
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index');
    }
}
