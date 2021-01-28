<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'created_at_readable' => (new Carbon($this->created_at))->diffForHumans(),
            'name' => $this->commenter->name,
            'image_url' => asset($this->commenter->photo),
            'body' => $this->comment
        ];
    }
}
