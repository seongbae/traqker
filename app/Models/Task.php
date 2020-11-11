<?php

namespace App\Models;

use App\Models\User;
use App\Traits\HasAttachments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
use App\Models\Section;
use Carbon\Carbon;


class Task extends Model implements Searchable
{
    use FillsColumns, SerializesDates, Commentable, UploadTrait, softDeletes, HasFactory, HasAttachments;

    protected $appends = ['status_badge'];


    protected $dates = [
    ];


    public function getDueOnDayEndAttribute()
    {
        return Carbon::parse($this->due_on)->endOfDay();
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to')->withDefault(['name' => null]);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
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
                return "info";
                break;
        }
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('tasks.show', $this->id);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }

    public function usersToNotify($user)
    {
        $users = array();

        if ($this->user_id && $this->user_id != $user->id)
            $users[] = $this->user_id;

        if ($this->assigned_to && $this->assigned_to != $user->id)
            $users[] = $this->assigned_to;

        return User::whereIn('id', $users)->get();
    }



    public function getCreatedAtAttribute($input)
    {
        return Timezone::convertFromUTC($input, auth()->user()->timezone, 'Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($input)
    {
        return Timezone::convertFromUTC($input, auth()->user()->timezone, 'Y-m-d H:i:s');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ArchiveScope);
    }
}
