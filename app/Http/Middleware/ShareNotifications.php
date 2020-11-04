<?php

namespace App\Http\Middleware;

use Closure;
use Auth;


class ShareNotifications
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
        $user = Auth::user();


            \View::share('notifications', $user->unreadNotifications);

        return $next($request);
    }
}
