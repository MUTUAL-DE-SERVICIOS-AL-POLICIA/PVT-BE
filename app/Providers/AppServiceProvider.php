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
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Observers\EconomicComplementObserver;
use Muserpol\Models\EconomicComplement\EcoComUpdatedPension;
use Muserpol\Observers\EcoComUpdatedPensionObserver;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Muserpol\Observers\EcoComBeneficiaryObserver;
use Muserpol\Models\EconomicComplement\EcoComLegalGuardian;
use Muserpol\Observers\EcoComLegalGuardianObserver;
use Muserpol\Models\Contribution\Contribution;
use Muserpol\Observers\ContributionObserver;
use Muserpol\Models\Contribution\Reimbursement;
use Muserpol\Models\EconomicComplement\EcoComFixedPension;
use Muserpol\Observers\EcoComFixedPensionObserver;
use Muserpol\Observers\ReimbursementObserver;

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
        //RetFunObservation::observe(RetirementFundObservationObserver::class);
        ContributionProcess::observe(ContributionProcessObserver::class);
        EconomicComplement::observe(EconomicComplementObserver::class);
        EcoComUpdatedPension::observe(EcoComUpdatedPensionObserver::class);
        EcoComBeneficiary::observe(EcoComBeneficiaryObserver::class);
        EcoComLegalGuardian::observe(EcoComLegalGuardianObserver::class);
        Contribution::observe(ContributionObserver::class);
        Reimbursement::observe(ReimbursementObserver::class);
        EcoComFixedPension::observe(EcoComFixedPensionObserver::class);

        Relation::morphMap([
            'retirement_funds' => 'Muserpol\Models\RetirementFund\RetirementFund',
            'quota_aid_mortuaries' => 'Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary',
            'contribution_processes' => 'Muserpol\Models\Contribution\ContributionProcess',
            'contributions' => 'Muserpol\Models\Contribution\Contribution',
            'aid_contributions' => 'Muserpol\Models\Contribution\AidContribution',
            'reimbursements' => 'Muserpol\Models\Contribution\Reimbursement',
            'aid_reimbursements' => 'Muserpol\Models\Contribution\AidReimbursement',
            'wf_states' => 'Muserpol\Models\Workflow\WorkflowState',
            'economic_complements' => 'Muserpol\Models\EconomicComplement\EconomicComplement',
            'eco_com_beneficiaries' => 'Muserpol\Models\EconomicComplement\EcoComBeneficiary',
            'ret_fun_beneficiaries' => 'Muserpol\Models\RetirementFund\RetFunBeneficiary',
            'quota_aid_beneficiaries' => 'Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary',
            'affiliates' => 'Muserpol\Models\Affiliate',
            'modules' => 'Muserpol\Models\Module',
        ]);

        // carbon settings
        Carbon::useMonthsOverflow(false);
        setlocale(LC_TIME, 'es_ES.utf8');
        Carbon::setLocale(config('app.locale'));
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
