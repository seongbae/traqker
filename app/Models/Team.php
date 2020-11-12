<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Seongbae\Canvas\Traits\FillsColumns;
use Seongbae\Canvas\Traits\SerializesDates;
use Illuminate\Support\Facades\Log;

class Team extends Model
{
    use FillsColumns, SerializesDates;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('title', 'access');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'team_projects');
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

    public function contains(User $user)
    {
        foreach($this->members as $member)
            if ($member->id === $user->id)
                return true;

        return false;
    }

}
