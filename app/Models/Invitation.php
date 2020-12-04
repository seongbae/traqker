<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\User;
use App\Events\InviteCreated;

class Invitation extends Model
{
    protected $fillable = [
        'email', 'invitation_token', 'registered_at','to_user_id','from_user_id','project_id','team_id','access','title'
    ];

    public function generateInvitationToken() {
        $this->invitation_token = substr(md5(rand(0, 9) . $this->email . time()), 0, 32);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * @return string
     */
    public function getLink() {
        return urldecode(route('register') . '?invitation_token=' . $this->invitation_token);
    }

    protected static function boot()
    {
        parent::boot();

//        static::addGlobalScope(new AcceptedScope);
//        static::addGlobalScope(new DeclinedScope);

        static::created(function ($invitation) {
            event(new InviteCreated($invitation));
        });

    }
}
