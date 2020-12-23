<?php

namespace App\Models;

use App\Notifications\NewTeamMemberNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Seongbae\Discuss\Traits\HasThreads;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Group;
use App\Achievement;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Laravel\Cashier\Billable;
use App\Models\Project;
use App\Models\Client;
use App\Models\Task;
use Laravelista\Comments\Commenter;
use Laravelista\Comments\Comment;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Hour;
use App\Models\Team;
use Auth;
use App\Scopes\ArchiveScope;
use App\Models\Invitation;
use App\Notifications\InviteAcceptedNotification;

class User extends Authenticatable implements Searchable
{
    use Notifiable, HasRoles, LogsActivity, Billable, Commenter, HasFactory, HasApiTokens, HasThreads;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','last_login_at','last_login_ip','timezone','paypal_payer_id','paypal_email','paypal_phone','paypal_client_id','paypal_secret'
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
        'settings' => 'array'
    ];

    protected $appends = ['role','photo'];

    protected $attributes = [
        'settings' => '{
            "daily_reminder_email": 1
        }'
    ];


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
            return '../canvas/img/default-avatar.png';
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function paymentSent()
    {
        return $this->hasMany(Payment::class, 'payer_id')->orderBy('created_at','desc');
    }

    public function paymentReceived()
    {
        return $this->hasMany(Payment::class, 'payee_id')->orderBy('created_at','desc');
    }

    public function getTransactionsAttribute()
    {
        return $this->paymentSent->merge($this->paymentReceived);
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

    public function projects()
    {
        return $this->belongsToMany(Project::class)->with('members');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('title','access');
    }

    public function teamsWithManagerialAccess()
    {
        return $this->belongsToMany(Team::class)->withPivot('title','access')->whereIn('access',['owner','manager']);
    }

    public function myTeams()
    {
        return $this->hasMany(Team::class);
    }

    public function teamMembers()
    {
        return $this->hasManyThrough(User::class, Team::class);
    }

    public function myProjects()
    {
        return $this->hasMany(Project::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function hours()
    {
        return $this->hasMany(Hour::class);
    }

//    public function assignedTasks()
//    {
//        return $this->hasMany(Task::class, 'assigned_to');
//    }

//    public function myTasks()
//    {
//        return $this->hasMany(Task::class, 'user_id');
//    }

//    public function tasks()
//    {
//        return $this->assignedTasks->merge($this->myTasks);
//    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class)->with('project')->with('users');
    }

    public function activeTasks()
    {
        return $this->tasks()->where('status','!=','complete')->orderBy('created_at', 'desc');
    }

    public function upcomingTasks()
    {
        return $this->tasks()->where('status','!=','complete')->whereNotNull('due_on')->where('due_on', '>=',Carbon::now())->orderBy('due_on');
    }

    public function deletedTasks()
    {
        return $this->tasks()->withTrashed()->whereNotNull('deleted_at');
    }

    public function archivedTasks()
    {
        return $this->tasks()->withoutGlobalScope(new ArchiveScope)->where('archived', true)->orderBy('completed_on','desc');
    }

    public function pastDueTasks()
    {
        return $this->tasks()->where('status','!=','complete')->where('due_on', '<',Carbon::now()->subDays(1))->orderBy('due_on');
    }

    public function tasksDueToday()
    {
        return $this->tasks()->where('due_on', date('Y-m-d'))->orderBy('created_at','asc');
    }

    public function tasksDueInWeek()
    {
        $begin = Carbon::today()->addDay(1);
        $end = Carbon::today()->addDay(7);

        return $this->tasks()->whereBetween('due_on', [$begin, $end])->orderBy('due_on','asc');
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function getRate($task)
    {
        $rate = 0;

        if ($task->project_id)
        {
            foreach($this->projects as $project)
            {
                if ($project->id == $task->project_id)
                {
                    if ($project->pivot->rate)
                    {
                        $rate = $project->pivot->rate;
                        break;
                    }
                }
            }

            if ($rate == 0 && $task->project->team_id)
            {
                foreach($this->teams as $team)
                {
                    if ($team->id == $task->project->team_id)
                    {
                        $rate = $team->pivot->rate;
                        break;
                    }
                }
            }
        }

        return $rate;
    }

    public function isAdmin()
    {
        return $this->hasRole(['Administrator']);
    }

    public function relatedComments($count=10)
    {
        return Comment::whereIn('commentable_id', $this->tasks()->pluck('id')->toArray())->orderBy('created_at','desc')->paginate($count);
    }

    public function getAvailability($timezone)
    {
        $availabilities = collect();

        foreach($this->availabilities as $av)
        {
            $av->start = Carbon::createFromFormat('Y-m-d H:i:s', '2020-05-04 '.$av->start, $this->timezone)->setTimezone($timezone)->format('H:i:00');
            $av->end = Carbon::createFromFormat('Y-m-d H:i:s', '2020-05-04 '.$av->end, $this->timezone)->setTimezone($timezone)->format('H:i:00');
            $availabilities->push($av);
        }

        return $availabilities;
    }

    public function quicklinks()
    {
        return $this->hasMany(Quicklink::class);
    }

    public function unpaidCompletedTasks($projects)
    {
        return $this->hasMany(Task::class, 'assigned_to')
            ->where('status','complete')
            ->whereIn('project_id', $projects->pluck('id')->toArray())
            ->whereNull('payment_id')
            ->orderBy('due_on');
    }

    public function unpaidCompletedTaskHours($projects)
    {
        $tasks = $this->unpaidCompletedTasks($projects)->get();

        $total = 0;

        foreach($tasks as $task)
            foreach($task->hours as $hour)
                $total += $hour->hours;

        return $total;
    }

    public function unpaidCompletedTaskAmount($projects)
    {
        $tasks = $this->unpaidCompletedTasks($projects)->get();

        $total = 0;
        $rate = 0;

        foreach($tasks as $task)
        {
            foreach ($task->project->members as $member)
            {
                if ($member->id == $this->id)
                    $rate = $member->pivot->rate;
            }

            foreach($task->hours as $hour)
            {
                $total += $hour->hours * $rate;
            }

        }

        return $total;
    }

    public function hasPayoutMethods()
    {
        if ($this->paypal_email || $this->stripe_connected_id)
            return true;
        else
            return false;
    }

    public function getPayoutMethod()
    {
        if ($this->paypal_email)
            return 'Paypal';
        else
            return '';
    }

    public function hasPaymentMethods()
    {
        if ($this->paypal_client_id && $this->paypal_secret)
            return true;
        else
            return false;
    }

    public function canReceivePaymentFrom($user)
    {
        if ($this->hasPayoutMethods() && $user->hasPaymentMethods())
            return true;
        else
            return false;
    }

    public function canManageTeam($team)
    {
        foreach($team->members as $member)
            if ($member->id == $this->id && in_array($member->pivot->access, ["owner","manager"]))
                return true;

        return false;
    }

    public function getTotalEarned()
    {
        return $this->paymentReceived()->where('payee_id',Auth::id())->sum('amount');
    }

    public function getTotalTimeSpent()
    {
        return $this->hours()->sum('hours');
    }

    public function getOnTimeCompletionRate()
    {
        $dueonTask = 0;
        $completedOnTimeTask = 0;

        foreach($this->tasks() as $task)
        {
            if ($task->due_on && $task->completed_on)
            {
                if (Carbon::parse($task->completed_on)->lessThanOrEqualTo(Carbon::parse($task->due_on)))
                {
                    $completedOnTimeTask++;
                    $dueonTask++;
                }
                else
                {
                    $dueonTask++;
                }

            }
        }

        if ($dueonTask == 0 || $completedOnTimeTask==0)
            return 100;
        else
            return number_format($completedOnTimeTask / $dueonTask*100,0);
    }

    public function getEfficiencyRate()
    {
        $totalEstimate = $this->tasks()->where('status','complete')->where('paid',1)->sum('estimate_hour');
        $completedAndPaidTasks = $this->tasks()->where('status','complete')->where('paid',1)->pluck('id')->toArray();
        $totalTimeSpent = $this->hours()->whereIn('task_id',$completedAndPaidTasks)->sum('hours');

        if ($totalEstimate == 0 || count($completedAndPaidTasks) == 0 || $totalTimeSpent == 0)
            return 100;

        if ($totalTimeSpent <= $totalEstimate)
            return 100;

        $totalVariance = 0;

        foreach($this->tasks as $task)
        {
            if ($task->estimate_hour && $task->total_hours > 0)
            { //  1/2=0.5
                $variance = abs($task->estimate_hour - $task->total_hours) / $task->estimate_hour;

                if ($variance == 0)
                    $totalVariance = $totalVariance - 1;
                else
                    $totalVariance = $totalVariance + $variance;
            }

        }

        if ($totalVariance<0)
            $totalVariance=0;

        return 100-$totalVariance;

    }

    /**
     * Retrieve a setting with a given name or fall back to the default.
     *
     */
    public function setting(string $name, $default = null)
    {
        if (array_key_exists($name, $this->settings == null ? [] : $this->settings)) {
            return $this->settings[$name];
        }
        return $default;
    }

    /**
     * Update one or more settings and then optionally save the model.
     *
     */
    public function settings(array $revisions, bool $save = true) : self
    {
        $this->settings = array_merge($this->settings == null ? [] : $this->settings, $revisions);

        if ($save) {
            $this->save();
        }

        return $this;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function($user) {
            if (option('default_role'))
                $user->assignRole(option('default_role'));

        });
    }

    public function isStripeCustomer() {
        $stripeCustomer = $this->createOrGetStripeCustomer();

        if ($stripeCustomer)
            return true;
        else
            return false;
    }
}
