<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Task;

class AttachmentController extends Controller
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
        Log::info('Attachment:store');

        if($request->hasFile('file')) {
            $files = $request->file('file');
            Log::info(json_encode($request->all()));
            $model = Task::find($request->task_id);
            $file = $model->addFile($files);
//            foreach ($files as $file) {
//                $model->addFile($file);
//            }

            return response()->json([
                'id' => $file->id,
                'label'=>$file->label
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function show(Attachment $attachment)
    {
        //
    }

    public function download(Attachment $attachment)
    {
        return Storage::disk('local')->download('public/files/'.$attachment->filename);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function edit(Attachment $attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attachment $attachment)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();

        if (\Illuminate\Support\Facades\Request::ajax())
            return response()->json(['success'], 200);

        return redirect()->back();
    }
}
