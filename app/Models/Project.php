<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Seongbae\Canvas\Traits\FillsColumns;
use Seongbae\Canvas\Traits\SerializesDates;
use App\Models\User;
use App\Models\Team;
use App\Models\Section;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\ArchiveScope;

use App\Models\Task;

class Project extends Model
{
    use FillsColumns, SerializesDates, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'archived'
    ];

    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('rate', 'rate_frequency');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, "team_projects");
    }

    public function client()
    {
        return $this->belongsTo(Client::class)->withDefault(['name' => null]);
    }

    public function parent()
    {
        return $this->belongsTo(Project::class, 'parent_id');
    }

    public function quicklink()
    {
        return $this->hasOne(Quicklink::class, 'model_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('order','asc');
    }

    public function noSectionTasks()
    {
        return $this->hasMany(Task::class)->where('section_id', null)->orderBy('order','asc');
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order','asc');
    }

    protected static function boot()
	{
	    parent::boot();

	    static::creating(function ($query) {
	        $query->user_id = auth()->id();
	    });

        static::addGlobalScope(new ArchiveScope);
	}

}
