<?php

namespace Muserpol\Http\Middleware;

use Closure;
// use Session;
use Log;

class SessionMiddleware
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
        if(session()->exists('rol_id'))
        {
            return $next($request);
        }
        else{
            return redirect('changerol');
        }
    }
}
