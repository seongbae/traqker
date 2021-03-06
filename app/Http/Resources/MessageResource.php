<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class MessageResource extends JsonResource
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
            'user_id'=>$this->user_id,
            'body'=>$this->body,
            'from_me'=>Auth::id()==$this->user_id ? 'true' : 'false',
            'created_at'=>$this->created_at
        ];
    }
}
