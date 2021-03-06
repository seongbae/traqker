<?php

namespace App\Http\Controllers;

use Seongbae\Discuss\Models\Thread;
use Illuminate\Http\Request;
use Seongbae\Discuss\Models\Channel;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Team;

class ThreadsControllerBackup extends Controller
{
    /**
     * ThreadsController constructor.
     */
    public function __construct()
    {
        if (config('discuss.view_mode') == 'public')
            $this->middleware('auth', ['only' => ['create', 'store', 'edit','update', 'delete']]);
        else
            $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $page = '_discuss';
        $subscribed = false;
        $channel = null;
        $team = null;

        if ($slug)
        {
            $threads = Thread::whereHas('channel', function($q) use($slug) {
                $q->where('channels.slug', $slug);
            })->latest()->paginate(config('discuss.page_count'));

            $channel = Channel::where('slug', $slug)->first();

            if (Auth::check() && Auth::user()->channelSubscriptions->contains($channel))
                $subscribed = true;

            $team = Team::where('slug', $channel->slug)->first();
        }
        else
        {
            $user = Auth::user();
            $threads = Thread::where('user_id',$user->id)->latest()->paginate(config('discuss.page_count'));
        }

        return view('discuss::threads.index', compact('threads', 'channel','subscribed','page','team'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $channels = Channel::all();

        return view('discuss::threads.create')
                ->with('channels', $channels);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'=>'required',
            'body'=>'required',
            'channel_id'=>'required|exists:channels,id'
        ]);

        $user = Auth::user();

        $thread = $user->threads()->create([
            'user_id' => auth()->id(),
            'title' => request('title'),
            'slug' => $this->slugify(request('title')),
            'channel_id' => request('channel_id'),
            'body'  => request('body')
        ]);

        $thread->updateSubscription($user);

        return redirect()->route('discuss.index',['slug'=>$thread->channel->slug])->with('success','Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel, Thread $thread)
    {
        $thread->view_count += 1;
        $thread->save();

        $subscribed = 0;
        if (Auth::check() && Auth::user()->threadSubscriptions->contains($thread))
            $subscribed = 1;

        $team = Team::where('slug', $channel->slug)->first();
        $page = '_discuss';

        return view('discuss::threads.show', compact(['thread', 'subscribed','team','page']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update($channel, Thread $thread)
    {
        $this->validate(request(), [
            'title'=>'required',
            'body'=>'required',
            'channel_id'=>'required|exists:channels,id'
        ]);

        $thread->update(request(['title','body','channel_id']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $thread->delete();

        return redirect()->route('discuss.index', ['slug'=>$channel->slug])->with('success','Successfully deleted');
    }

    private function slugify($string){
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));

        $thread = Thread::where('slug', $slug)->first();

        if ($thread)
            $slug = $slug.'-'.$this->generate_string(4);

        return $slug;
    }


    private function generate_string($strength = 4) {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $input_length = strlen($permitted_chars);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }
}
