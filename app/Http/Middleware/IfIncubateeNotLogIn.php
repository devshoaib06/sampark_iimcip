<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;

class IfIncubateeNotLogIn
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
        $ck = Session::get('is_iimcip_logged_in');
        $session_uid = Session::get('iimcip_user_id');
        $validUserTypeID = 2;
        
        if($ck == "yes" && Auth::check() && Auth::user()->id == $session_uid && Auth::user()->user_type == $validUserTypeID)
        {
            return redirect()->route('incubatee.user.account');
        }
        
        $response = $next($request);
        return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
        ->header('Pragma','no-cache')
        ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
    }
}
