<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Team;
use Log;
use Request;


class GetTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Request::segment(1) == 'teams')
        {
            $team = Team::where('slug', Request::segment(2))->first();

            \View::share('team', $team);
        }

        return $next($request);
    }
}
