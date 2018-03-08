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
            // Log::info(session()->get('rol_id'));
            return $next($request);
        }
        else{
            // Log::info("habiliatar en caso de error en cyk");
            return redirect('changerol');
        }
    }
}
