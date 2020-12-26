<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GanttResource extends JsonResource
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
            'id' => strval($this->id),
            'name' => $this->name,
            'start' => $this->start_on == null ? $this->due_on : $this->start_on,
            'end' => $this->due_on,
            'progress' => $this->status == 'complete' ? 100 : $this->progress,
            'custom_class'=> 'gantt-bar',
            'description'=>$this->description,
            'dependencies'=> strval(implode(',', $this->dependencies->pluck('dependency_id')->toArray()))
        ];

//        id: 'Task 1',
//        name: 'Redesign website',
//        start: '2016-12-28',
//        end: '2016-12-31',
//        progress: 20,
//        dependencies: 'Task 2, Task 3',
//        custom_class: 'bar-milestone' // optional
    }
}
