<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Seongbae\Canvas\Traits\FillsColumns;
use Seongbae\Canvas\Traits\SerializesDates;
use Illuminate\Support\Facades\Log;
use Seongbae\Discuss\Models\Channel;

class Team extends Model
{
    use FillsColumns, SerializesDates;

    protected $attributes = [
        'settings' => '{
            "color_scheme": "default"
        }'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function pendingInvitations()
    {
        return $this->hasMany(Invitation::class)->whereNull('accepted_at')->whereNull('declined_at');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('title', 'access');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'team_projects');
    }

    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    public function firstAvailableManagerExcept($user=null)
    {
        foreach($this->members as $member)
        {
            if ($user && $member->id === $user->id)
                continue;

            if ($member->pivot->access == 'owner' || $member->pivot->access == 'manager')
                return $member;

        }

        return null;
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    public function wikipages()
    {
        return $this->morphMany(WikiPage::class, 'wikiable');
    }

    public function contains(User $user)
    {
        foreach($this->members as $member)
            if ($member->id === $user->id)
                return true;

        return false;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $channel = Channel::create(['name'=>$query->name, 'slug'=>$query->slug]);
            $query->channel_id = $channel->id;
        });

        static::deleting(function ($query) {
            foreach($query->projects as $project)
                $project->delete();

            $channel = Channel::where('slug', $query->slug);
            $channel->delete();
        });

    }

}
