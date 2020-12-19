<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Team;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getUserAvailability($id)
    {
        $availability = Availability::where('user_id', $id)->get();

        return response()->json(['data'=>$availability]);
    }

    public function getTeamAvailability(Request $request, Team $team)
    {
        $page = '_availability';
        $availability = array();

        foreach($team->members as $member)
        {
            $availability[] = array('name'=>$member->name, 'availability'=>$member->getAvailability(Auth::user()->timezone));
        }

        if ($request->ajax())
            return response()->json(['data'=>$availability]);
        else
            return view('teams.availability', compact('page','team'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $availability = Availability::create([
                'dayofweek'=>Carbon::parse($request->get('start'))->dayOfWeek,
                'start'=>Carbon::parse($request->get('start'))->toTimeString(),
                'end'=>Carbon::parse($request->get('end'))->toTimeString(),
                'name'=>$request->get('name'),
                'user_id'=>Auth::id()
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function show(Availability $availability)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function edit(Availability $availability)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Availability $availability)
    {
        $availability->dayofweek = Carbon::parse($request->get('start'))->dayOfWeek;
        $availability->start = Carbon::parse($request->get('start'))->toTimeString();
        $availability->end = Carbon::parse($request->get('end'))->toTimeString();
        $availability->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Availability  $availability
     * @return \Illuminate\Http\Response
     */
    public function destroy(Availability $availability)
    {
        if ($availability && Auth::id()==$availability->user_id)
            $availability->delete();

    }
}
