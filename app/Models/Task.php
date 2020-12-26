<?php

namespace App\Models;

use App\Events\TaskAssigned;
use App\Events\TaskComplete;
use App\Models\User;
use App\Traits\HasAttachments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use PayPal\Api\Notification;
use Seongbae\Canvas\Traits\FillsColumns;
use Seongbae\Canvas\Traits\SerializesDates;
use App\Models\Project;
use App\Models\Hour;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Laravelista\Comments\Commentable;
use Camroncade\Timezone\Facades\Timezone;
use Seongbae\Canvas\Traits\UploadTrait;
use Illuminate\Support\Str;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\ArchiveScope;
use App\Scopes\CompletedScope;
use App\Models\Section;
use Carbon\Carbon;
use Auth;
use Log;


class Task extends Model implements Searchable
{
    use FillsColumns, SerializesDates, Commentable, UploadTrait, softDeletes, HasFactory, HasAttachments;

    protected $appends = ['status_badge'];

    protected $with = ['tasks'];

    protected $dates = [
    ];


    public function getDueOnDayEndAttribute()
    {
        return Carbon::parse($this->due_on)->endOfDay();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies','task_id', 'dependency_id');
    }

    public function getAssigneesNameAttribute()
    {
        return $this->assignees->implode('name', ", ");
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id')->withDefault(['name' => null]);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function hours()
    {
    	return $this->hasMany(Hour::class, 'task_id');
    }

    public function attachments()
    {
        //$related, $name, $type = null, $id = null, $localKey = null)
        return $this->morphMany('App\Models\Attachment', 'attachable', 'attached_model', 'attached_model_id');
        //return $this->hasMany(Attachment::class, 'attached_model_id')->where('attached_model', Task::class);
    }

    public function getTotalHoursAttribute()
    {
        $total = 0;

        foreach($this->hours as $hour)
            $total += $hour->hours;

        return $total;
    }

    public function getStatusBadgeAttribute()
    {
        switch($this->status) {
            case "created":
                return "info";
                break;
            case "active":
                return "primary";
                break;
            case "complete":
                return "success";
                break;
            default:
                break;
        }
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('tasks.show', $this);

        return new SearchResult(
            $this,
            $this->name,
            $url,
            $this->description,
            $this->created_at
        );
    }

    public function getAllRelatedUsersExcept($user)
    {
        $users = array();

        if ($this->user_id != $user->id)
            $users[] = $this->user_id;

        if (count($this->assignees) > 0)
        {


            foreach($this->assignees as $relatedUser)
            {
                if (!in_array($relatedUser, $users) && $relatedUser->id != $user->id)
                    $users[] = $relatedUser->id;
            }
        }

        return User::whereIn('id', $users)->get();
    }

//    public function getCreatedAtAttribute($input)
//    {
//        return Timezone::convertFromUTC($input, auth()->user()->timezone, 'Y-m-d H:i:s');
//    }
//
//    public function getUpdatedAtAttribute($input)
//    {
//        return Timezone::convertFromUTC($input, auth()->user()->timezone, 'Y-m-d H:i:s');
//    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ArchiveScope);
        static::addGlobalScope(new CompletedScope);

        static::creating(function ($task) {
            $task->progress = 0;
        });

        static::created(function ($task) {
            if (count($task->users)>0)
                event(new TaskAssigned($task->users, $task));

            if ($task->project_id)
                $task->project->touch();
        });

       static::updated(function ($task) {
            if($task->isDirty('status')){
                // status has changed
                if ($task->status == 'complete') {
                    event(new TaskComplete(Auth::user(), $task));
                }
            }

            if ($task->project_id)
                $task->project->touch();
        });
    }
}
