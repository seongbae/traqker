<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Seongbae\Canvas\Traits\FillsColumns;
use Seongbae\Canvas\Traits\SerializesDates;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Camroncade\Timezone\Facades\Timezone;

class Hour extends Model
{
    use FillsColumns, SerializesDates;

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id')->withDefault(['name' => null]);
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id')->withDefault(['name' => null]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
	{
	    parent::boot();

	    static::creating(function ($query) {
	        $query->user_id = auth()->id();
	    });
	}

    public function getCreatedAtAttribute($input)
    {
        return Timezone::convertFromUTC($input, auth()->user()->timezone, 'Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($input)
    {
        return Timezone::convertFromUTC($input, auth()->user()->timezone, 'Y-m-d H:i:s');
    }
}
