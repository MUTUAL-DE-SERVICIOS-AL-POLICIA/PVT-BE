<?php

namespace Muserpol\Http\Middleware;

use Closure;
use Log;
use Muserpol\Models\RetirementFund\RetirementFund;
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
        $ret_fun = RetirementFund::where('affiliate_id',$request->affiliate->id)->where('code','LIKE','%A')->get();        
        $cant = $ret_fun->count();
        if($request->affiliate->retirement_funds->count()-$cant > 1){
            return redirect()->route('affiliate.show', $request->affiliate->id);
        }
        return $next($request);
    }
}
