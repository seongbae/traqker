<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quicklink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order', 'linkable_id', 'linkable_type'
    ];

    public function getPathAttribute()
    {
        return substr(parse_url($this->url, PHP_URL_PATH), 1);
    }

    public function getUrlAttribute()
    {
        if ($this->linkable_type == Project::class)
            return route('projects.show', ['project'=>$this->linkable]);
        elseif ($this->linkable_type == Team::class)
            return route('teams.show', ['team'=>$this->linkable]);
    }

    public function getFaIconAttribute()
    {
        if ($this->linkable_type == Project::class)
            return 'project-diagram';
        elseif ($this->linkable_type == Team::class)
            return 'users';
    }

    public function linkable()
    {
        return $this->morphTo();
    }
}
