<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Group;
use App\Achievement;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements Searchable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','last_login_at','last_login_ip',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['role','photo'];

    protected $guard_name = 'web';

    public function getRoleAttribute()
    {
        return $this->roles->pluck('name');
    }

    public function getPhotoAttribute()
    {
        if ($this->photo_url != null)
            return $this->photo_url;
        else
            return asset('canvas/img/default-avatar.png');
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('admin.users.show', $this->id);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }
}
