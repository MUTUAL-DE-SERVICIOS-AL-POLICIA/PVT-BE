<?php

namespace Muserpol\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Muserpol\Models\Contribution\Contribution;
use Muserpol\Models\Affiliate;
use Muserpol\Policies\ContributionPolicy;
use Muserpol\Policies\AffiliatePolicy;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Contribution::class => ContributionPolicy::class,
         Affiliate::class => AffiliatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
