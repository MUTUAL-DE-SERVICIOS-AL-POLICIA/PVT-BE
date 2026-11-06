<?php

namespace Muserpol\Providers;

use Muserpol\Observers\SpouseObserver;
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
use Muserpol\Models\Spouse;
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
        // Reemplazo compatible de Carbon::formatLocalized(), que usa strftime()
        // (deprecado en PHP 8.1) y desaparece en Carbon 3. Mantiene la misma
        // salida: en es el mes abreviado de Carbon lleva punto ("oct.") y el de
        // strftime no, asi que se ajusta segun el formato pedido.
        \Carbon\Carbon::macro('formatLocalizedEs', function (string $format) {
            /** @var \Carbon\Carbon $this */
            $map = [
                'B' => 'F', 'b' => 'M', 'Y' => 'Y', 'd' => 'd',
                'm' => 'm', 'H' => 'H', 'M' => 'i', 'S' => 's',
            ];
            // Se recorre el formato: los codigos %X pasan a date() y todo el
            // texto literal se escapa, porque letras como "d" o "e" (de "de")
            // serian interpretadas por date().
            $php = '';
            $len = strlen($format);
            for ($i = 0; $i < $len; $i++) {
                if ($format[$i] === '%' && $i + 1 < $len) {
                    $code = $format[++$i];
                    $php .= $map[$code] ?? $code;
                } else {
                    $php .= '\\' . $format[$i];
                }
            }
            $out = $this->locale(config('app.locale', 'es'))->translatedFormat($php);

            if (strpos($format, '%b.') !== false) {
                $out = str_replace('..', '.', $out);
            } elseif (strpos($format, '%b') !== false) {
                $out = preg_replace('/(\p{L}+)\.(\s|\/|$)/u', '$1$2', $out);
            }

            return $out;
        });

        Affiliate::observe(AffiliateObserver::class);
        Spouse::observe(SpouseObserver::class);
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
            'spouses' => 'Muserpol\Models\Spouse',
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
