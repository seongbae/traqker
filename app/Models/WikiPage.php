<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikiPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'created_user_id',
        'updated_user_id',
        'initial_page',
        'wikiable_id',
        'wikiable_type',
        'change_description'
    ];

    public function wikiable()
    {
        return $this->morphTo();
    }

    public function lastUpdatedBy()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function revisions()
    {
        return $this->hasMany(WikiPageHistory::class, 'wiki_page_id');
    }

    public function getSimpleTypeAttribute()
    {
        if ($this->wikiable_type == Team::class)
            return 'teams';
        elseif ($this->wikiable_type == Project::class)
            return 'projects';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->created_user_id = auth()->id();
            $query->updated_user_id = auth()->id();

        });

        static::created(function ($query) {
            WikiPageHistory::create(
                [
                    'wiki_page_id'=>$query->id,
                    'content'=>$query->content,
                    'user_id'=>auth()->id(),
                    'change_description'=>'Initial page'
                ]
            );
        });

        static::updating(function ($query) {
            $query->updated_user_id = auth()->id();
            if ($query->content != $query->getOriginal('content')) {
                WikiPageHistory::create(
                    [
                        'wiki_page_id'=>$query->id,
                        'content'=>$query->getOriginal('content'),
                        'user_id'=>auth()->id(),
                        'change_description'=>$query->change_description
                    ]
                );
            }
        });
    }
}
