<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageThreadResource extends JsonResource
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
            'messages'=>MessageResource::collection($this->messages()->orderBy('created_at','asc')->take(20)->get())
        ];
    }
}
