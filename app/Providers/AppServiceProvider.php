<?php

namespace Muserpol\Providers;

use Illuminate\Support\ServiceProvider;
use Muserpol\Observers\AffiliateObserver;
use Muserpol\Models\Affiliate;
use Muserpol\Observers\RetirementFundObserver;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Observers\RetirementFundObservationObserver;
use Muserpol\Models\RetirementFund\RetFunObservation;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Observers\QuotaAidMortuaryObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Muserpol\Models\Contribution\ContributionProcess;
use Muserpol\Observers\ContributionProcessObserver;
use Carbon\Carbon;
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
        QuotaAidMortuary::observe(QuotaAidMortuaryObserver::class);
        RetFunObservation::observe(RetirementFundObservationObserver::class);
        ContributionProcess::observe(ContributionProcessObserver::class);
        Relation::morphMap([
            'retirement_funds' => 'Muserpol\Models\RetirementFund\RetirementFund',
            'quota_aid_mortuaries' => 'Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary',
            'contribution_processes' => 'Muserpol\Models\Contribution\ContributionProcess',
            'contributions' => 'Muserpol\Models\Contribution\Contribution',
            'aid_contributions' => 'Muserpol\Models\Contribution\AidContribution',
            'wf_states' => 'Muserpol\Models\Workflow\WorkflowState',
        ]);

        // carbon settings
        Carbon::useMonthsOverflow(false);
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
