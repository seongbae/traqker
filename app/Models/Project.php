<?php

namespace App\Models;

use App\Models\Client;
use App\Scopes\CompletedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Seongbae\Canvas\Traits\FillsColumns;
use Seongbae\Canvas\Traits\SerializesDates;
use App\Models\User;
use App\Models\Team;
use App\Models\Section;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\ArchiveScope;

use App\Models\Task;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Project extends Model implements Searchable
{
    use FillsColumns, SerializesDates, SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'archived',
        'settings'
    ];

    protected $attributes = [
        'settings' => '{
            "visibility": "public"
        }'
    ];

    public function members()
    {
        return $this->belongsToMany(User::class);
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

    public function completedTasks()
    {
        return $this->hasMany(Task::class)->withoutGlobalScope(CompletedScope::class)->where('status','complete')->orderBy('completed_on','desc');
    }

    public function deletedTasks()
    {
        return $this->hasMany(Task::class)->onlyTrashed()->orderBy('deleted_at','desc');
    }

    public function noSectionTasks()
    {
        return $this->hasMany(Task::class)->where('section_id', null)->orderBy('order','asc')->orderBy('created_at', 'desc');
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order','asc');
    }

    public function contains(User $user)
    {
        foreach($this->members as $member)
            if ($member->id === $user->id)
                return true;

        return false;
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('projects.show', $this);

        return new SearchResult(
            $this,
            $this->name,
            $url,
            $this->description,
            $this->created_at
        );
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    protected static function boot()
	{
	    parent::boot();

	    static::creating(function ($query) {
	        $query->user_id = auth()->id();
	    });

        static::updating(function ($query) {
            if($query->isDirty('archived')){
                foreach($query->tasks as $task)
                {
                    $task->archived = $query->archived;
                    $task->save();
                }
            }
        });

        static::deleting(function ($query) {
            foreach($query->tasks as $task)
                $task->delete();
        });

        static::addGlobalScope(new ArchiveScope);
	}

}
