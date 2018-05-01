<?php

namespace Muserpol\Observers;
use Muserpol\Models\Affiliate;
use Log;
use Carbon\Carbon;
class AffiliateObserver
{
    public function created(Affiliate $affiliate){
        Log::info('affiliado creado');
        Log::info($affiliate);
    }
    public function updating(Affiliate $affiliate){
        // Log::info('antes de actualizar');
        // $a = Affiliate::find($affiliate->id);
        // Log::info($a);
        Log::info('updating');
        Log::info(Carbon::now());
    }
   
}