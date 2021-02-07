<?php

namespace App\Http\Controllers;

use App\Events\MessageReceived;
use App\Http\Resources\MessageResource;
use App\Http\Resources\TaskResource;
use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MessagesController extends Controller
{
    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        // All threads, ignore deleted/archived participants
        // $threads = Thread::getAllLatest()->get();

        // All threads that user is participating in
        // $threads = Thread::forUser(Auth::id())->latest('updated_at')->get();

        // All threads that user is participating in, with new messages
        $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();

        if($request->is('api/*') || $request->ajax()) {
            return MessageResource::collection($threads);
        }
        else {
            return view('messenger.index', compact('threads'));
        }


    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('messages');
        }

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();

        // don't show the current user in list
        $userId = Auth::id();
        $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();

        $thread->markAsRead($userId);

        return view('messenger.show', compact('thread', 'users'));
    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        return view('messenger.create', compact('users'));
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'subject'=>'required',
            'message'=>'required',
            'recipients'=>'required'
        ]);

        $input = $request->all();

        $thread = Thread::create([
            'subject' => $input['subject'],
        ]);

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $input['message'],
        ]);

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon,
        ]);

        // Recipients
        if ($request->has('recipients')) {
            $thread->addParticipant($input['recipients']);
        }

        event(new MessageReceived(Auth::user(), $thread, $request->message));

        if($request->is('api/*') || $request->ajax()) {
            return 'hello';
        }
        else {
            return redirect()->route('messages');
        }
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update($id, Request $request)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('messages');
        }

        $thread->activateAllParticipants();

        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $request->message
        ]);

        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients
        if ($request->has('recipients')) {
            $thread->addParticipant($request->recipients);
        }

        event(new MessageReceived(Auth::user(), $thread, $request->message));

        if($request->is('api/*') || $request->ajax()) {
            return 'hello';
        }
        else {
            return redirect()->route('messages.show', $id);
        }

    }
}
