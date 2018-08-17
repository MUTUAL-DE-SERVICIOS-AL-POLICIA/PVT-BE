<?php

use Illuminate\Database\Seeder;
use Muserpol\Operation;

class OperationQuotaAidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['module_id' => 4, 'name' => 'Affiliate', ],
            ['module_id' => 4, 'name' => 'Contribution', ],
            ['module_id' => 4, 'name' => 'Address', ],
            ['module_id' => 4, 'name' => 'AffiliateFolder', ],
            ['module_id' => 4, 'name' => 'AffiliateState', ],
            ['module_id' => 4, 'name' => 'BaseWage', ],
            ['module_id' => 4, 'name' => 'Breakdown', ],
            ['module_id' => 4, 'name' => 'Category', ],
            ['module_id' => 4, 'name' => 'City', ],
            ['module_id' => 4, 'name' => 'Degree', ],
            ['module_id' => 4, 'name' => 'Hierarchy', ],
            ['module_id' => 4, 'name' => 'Kinship', ],
            ['module_id' => 4, 'name' => 'Module', ],
            ['module_id' => 4, 'name' => 'PensionEntity', ],
            ['module_id' => 4, 'name' => 'ProcedureDocument', ],
            ['module_id' => 4, 'name' => 'ProcedureModality', ],
            ['module_id' => 4, 'name' => 'ProcedureRequirement', ],
            ['module_id' => 4, 'name' => 'ProcedureType', ],
            ['module_id' => 4, 'name' => 'Role', ],
            ['module_id' => 4, 'name' => 'RoleUser', ],
            ['module_id' => 4, 'name' => 'ScannedDocument', ],
            ['module_id' => 4, 'name' => 'Spouse', ],
            ['module_id' => 4, 'name' => 'Unit', ],
            ['module_id' => 4, 'name' => 'Voucher', ],
            ['module_id' => 4, 'name' => 'VoucherType', ],

            ['module_id' => 4, 'name' => 'ContributionRate', ],
            ['module_id' => 4, 'name' => 'ContributionType', ],
            ['module_id' => 4, 'name' => 'DirectContribution', ],
            ['module_id' => 4, 'name' => 'IpcRate', ],
            ['module_id' => 4, 'name' => 'Reimbursement', ],

            ['module_id' => 4, 'name' => 'AddressQuotaAidBeneficiary', ],
            ['module_id' => 4, 'name' => 'QuotaAidAdvisor', ],
            ['module_id' => 4, 'name' => 'QuotaAidAdvisorBeneficiary', ],
            ['module_id' => 4, 'name' => 'QuotaAidBeneficiary', ],
            ['module_id' => 4, 'name' => 'QuotaAidBeneficiaryLegalGuardian', ],
            ['module_id' => 4, 'name' => 'QuotaAidLegalGuardian', ],
            ['module_id' => 4, 'name' => 'QuotaAidMortuary', ],
            ['module_id' => 4, 'name' => 'QuotaAidObservation', ],
            ['module_id' => 4, 'name' => 'QuotaAidProcedure', ],
            ['module_id' => 4, 'name' => 'QuotaAidSubmittedDocument', ],
        ];
        foreach ($statuses as $status) {
            Operation::create($status);
        }
    }
}
