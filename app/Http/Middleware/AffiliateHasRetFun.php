<?php

namespace Muserpol\Http\Middleware;

use Closure;
use Log;
class AffiliateHasRetFun
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
        if($request->affiliate->retirement_funds->count() > 0 ){
            return redirect()->route('affiliate.show', $request->affiliate->id);
        }
        return $next($request);
    }
}
