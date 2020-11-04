<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Project;

class Section extends Model
{
    protected $fillable = ['name', 'project_id', 'user_id','order'];

    public $timestamps = false;

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('order','asc');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
