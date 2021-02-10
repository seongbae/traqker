<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class ThreadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'users'=>UserResource::collection($this->users),
            'thread_image'=>$this->users->where('id','<>',Auth::id())->first() != null ? $this->users->where('id','<>',Auth::id())->first()->photo : $this->users->first()->photo,
            'participant_names'=>implode(",", $this->users->where('id','<>',Auth::id())->pluck('name')->toArray()),
            'last_message'=>new MessageResource($this->messages()->latest()->first())
        ];
    }
}
