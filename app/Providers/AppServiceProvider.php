<?php

namespace Muserpol\Providers;

use Illuminate\Support\ServiceProvider;
use Muserpol\Observers\AffiliateObserver;
use Muserpol\Models\Affiliate;
use Muserpol\Observers\RetirementFundObserver;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Observers\RetirementFundObservationObserver;
use Muserpol\Models\RetirementFund\RetFunObservation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Affiliate::observe(AffiliateObserver::class);
        RetirementFund::observe(RetirementFundObserver::class);
        RetFunObservation::observe(RetirementFundObservationObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__ . '/../Http/Helpers/Navigation.php';
    }
}
