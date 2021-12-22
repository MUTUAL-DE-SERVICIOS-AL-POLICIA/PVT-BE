<?php

namespace Muserpol\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Muserpol\Models\Contribution\Contribution;
use Muserpol\Models\Affiliate;
use Muserpol\Policies\ContributionPolicy;
use Muserpol\Policies\AffiliatePolicy;
use Muserpol\Policies\AffiliateFolderPolicy;
use Muserpol\Policies\AffiliateStatePolicy;
use Muserpol\Models\AffiliateFolder;
use Muserpol\Models\AffiliateState;
use Muserpol\Models\Address;
use Muserpol\Policies\AddressPolicy;
use Muserpol\Models\BaseWage;
use Muserpol\Policies\BaseWagePolicy;
use Muserpol\Models\Breakdown;
use Muserpol\Policies\BreakdownPolicy;
use Muserpol\Models\Category;
use Muserpol\Policies\CategoryPolicy;
use Muserpol\Models\City;
use Muserpol\Policies\CityPolicy;
use Muserpol\Models\Degree;
use Muserpol\Policies\DegreePolicy;
use Muserpol\Models\Hierarchy;
use Muserpol\Policies\HierarchyPolicy;
use Muserpol\Models\Kinship;
use Muserpol\Policies\KinshipPolicy;
use Muserpol\Models\Module;
use Muserpol\Policies\ModulePolicy;
use Muserpol\Models\PensionEntity;
use Muserpol\Policies\PensionEntityPolicy;
use Muserpol\Models\ProcedureDocument;
use Muserpol\Policies\ProcedureDocumentPolicy;
use Muserpol\Models\ProcedureModality;
use Muserpol\Policies\ProcedureModalityPolicy;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Policies\ProcedureRequirementPolicy;
use Muserpol\Models\ProcedureType;
use Muserpol\Policies\ProcedureTypePolicy;
use Muserpol\Models\Role;
use Muserpol\Policies\RolePolicy;
use Muserpol\Models\RoleUser;
use Muserpol\Policies\RoleUserPolicy;
use Muserpol\Models\Spouse;
use Muserpol\Policies\SpousePolicy;
use Muserpol\Models\Unit;
use Muserpol\Models\Note;
use Muserpol\Policies\UnitPolicy;
use Muserpol\Models\Voucher;
use Muserpol\Policies\VoucherPolicy;
use Muserpol\Models\VoucherType;
use Muserpol\Policies\VoucherTypePolicy;
use Muserpol\Models\Contribution\ContributionRate;
use Muserpol\Policies\ContributionRatePolicy;
use Muserpol\Models\Contribution\ContributionType;
use Muserpol\Policies\ContributionTypePolicy;
use Muserpol\Models\Contribution\DirectContribution;
use Muserpol\Policies\DirectContributionPolicy;
use Muserpol\Models\Contribution\IpcRate;
use Muserpol\Policies\IpcRatePolicy;
use Muserpol\Models\Contribution\Reimbursement;
use Muserpol\Policies\ReimbursementPolicy;
use Muserpol\Models\RetirementFund\RetFunAdvisor;
use Muserpol\Policies\RetFunAdvisorPolicy;
use Muserpol\Models\RetirementFund\RetFunAdvisorBeneficiary;
use Muserpol\Policies\RetFunAdvisorBeneficiaryPolicy;
use Muserpol\Models\RetirementFund\RetFunApplicant;
use Muserpol\Policies\RetFunApplicantPolicy;
use Muserpol\Models\RetirementFund\RetFunBeneficiary;
use Muserpol\Policies\RetFunBeneficiaryPolicy;
use Muserpol\Models\RetirementFund\RetFunIncrement;
use Muserpol\Policies\RetFunIncrementPolicy;
use Muserpol\Models\RetirementFund\RetFunLegalGuardian;
use Muserpol\Policies\RetFunLegalGuardianPolicy;
use Muserpol\Models\RetirementFund\RetFunLegalGuardianBeneficiary;
use Muserpol\Policies\RetFunLegalGuardianBeneficiaryPolicy;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Muserpol\Policies\RetFunProcedurePolicy;
use Muserpol\Models\RetirementFund\RetFunSubmittedDocument;
use Muserpol\Policies\RetFunSubmittedDocumentPolicy;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Policies\RetirementFundPolicy;
use Muserpol\Policies\NotePolicy;

use Muserpol\Models\Contribution\ContributionCommitment;
use Muserpol\Policies\ContributionCommitmentPolicy;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Policies\QuotaAidMortuaryPolicy;
use Muserpol\Policies\ContributionProcessPolicy;
use Muserpol\Policies\EconomicComplementPolicy;
use Muserpol\Policies\EcoComLegalGuardianPolicy;
use Muserpol\Policies\EcoComBeneficiaryPolicy;
use Muserpol\Policies\EcoComProcedurePolicy;
use Muserpol\Policies\ObservationTypePolicy;
use Muserpol\Policies\ComplementaryFactorPolicy;
use Muserpol\Models\Contribution\ContributionProcess;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EcoComLegalGuardian;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Muserpol\Models\ComplementaryFactor;
use Muserpol\Models\ObservationType;
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

         Address::class => AddressPolicy::class,
         AffiliateFolder::class => AffiliateFolderPolicy::class,
         AffiliateState::class => AffiliateStatePolicy::class,
         BaseWage::class => BaseWagePolicy::class,
         Breakdown::class => BreakdownPolicy::class,
         Category::class => CategoryPolicy::class,
         City::class => CityPolicy::class,
         Degree::class => DegreePolicy::class,
         Hierarchy::class => HierarchyPolicy::class,
         Kinship::class => KinshipPolicy::class,
         Module::class => ModulePolicy::class,
         PensionEntity::class => PensionEntityPolicy::class,
         ProcedureDocument::class => ProcedureDocumentPolicy::class,
         ProcedureModality::class => ProcedureModalityPolicy::class,
         ProcedureRequirement::class => ProcedureRequirementPolicy::class,
         ProcedureType::class => ProcedureTypePolicy::class,
         Role::class => RolePolicy::class,
         RoleUser::class => RoleUserPolicy::class,
         Spouse::class => SpousePolicy::class,
         Unit::class => UnitPolicy::class,
         Voucher::class => VoucherPolicy::class,
         VoucherType::class => VoucherTypePolicy::class,

         ContributionRate::class => ContributionRatePolicy::class,
         ContributionType::class => ContributionTypePolicy::class,
         DirectContribution::class => DirectContributionPolicy::class,
         IpcRate::class => IpcRatePolicy::class,
         Reimbursement::class => ReimbursementPolicy::class,

         RetFunAdvisorBeneficiary::class => RetFunAdvisorBeneficiaryPolicy::class,
         RetFunApplicant::class => RetFunApplicantPolicy::class,
         RetFunBeneficiary::class => RetFunBeneficiaryPolicy::class,
         RetFunIncrement::class => RetFunIncrementPolicy::class,
         RetFunLegalGuardian::class => RetFunLegalGuardianPolicy::class,
         RetFunLegalGuardianBeneficiary::class => RetFunLegalGuardianBeneficiaryPolicy::class,
         RetFunProcedure::class => RetFunProcedurePolicy::class,
         RetFunSubmittedDocument::class => RetFunSubmittedDocumentPolicy::class,
         RetirementFund::class => RetirementFundPolicy::class,
         ContributionCommitment::class => ContributionCommitmentPolicy::class,
         /** quota aid */
         QuotaAidMortuary::class => QuotaAidMortuaryPolicy::class,

         ContributionProcess::class => ContributionProcessPolicy::class,

         EconomicComplement::class => EconomicComplementPolicy::class,
         ComplementaryFactor::class => ComplementaryFactorPolicy::class,
         ObservationType::class => ObservationTypePolicy::class,
         EcoComProcedure::class => EcoComProcedurePolicy::class,
         EcoComLegalGuardian::class => EcoComLegalGuardianPolicy::class,
         EcoComBeneficiary::class => EcoComBeneficiaryPolicy::class,
         Note::class => NotePolicy::class,
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
