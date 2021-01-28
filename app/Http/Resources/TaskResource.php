<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'name' => $this->name,
            'status' => $this->status,
            'priority' => $this->priority,
            'project_id' => $this->project_id,
            'start_on' => $this->start_on,
            'due_on' => $this->due_on,
            'completed_on' => $this->completed_on,
            'estimate_hour' => $this->estimate_hour,
            'progress' => $this->progress,
            'description' => $this->description,
            'assignee_names'=> implode(",", $this->assignees->pluck('name')->toArray()),
            'assignees'=> UserResource::collection($this->assignees),
            'project'=> new ProjectResource($this->project),
            'comments'=> CommentResource::collection($this->comments)
        ];
    }
}
