<?php

namespace App\Http\Controllers;

use App\Models\Quicklink;
use Illuminate\Http\Request;
use Auth;

class QuicklinkController extends Controller
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
        $quicklink = Quicklink::create(array_merge($request->all(),['user_id'=>Auth::id()]));

       return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quicklink  $quicklink
     * @return \Illuminate\Http\Response
     */
    public function show(Quicklink $quicklink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quicklink  $quicklink
     * @return \Illuminate\Http\Response
     */
    public function edit(Quicklink $quicklink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quicklink  $quicklink
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quicklink $quicklink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quicklink  $quicklink
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quicklink $quicklink)
    {
        $quicklink->delete();

        return redirect()->back();
    }
}
