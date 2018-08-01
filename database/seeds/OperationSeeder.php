<?php

use Illuminate\Database\Seeder;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('operations')->insert([
            ['module_id' => '3', 'name' => 'Affiliate', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'Contribution', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'Address', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'AffiliateFolder', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'AffiliateState', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'BaseWage', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'Breakdown', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'Category', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'City', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],           
            ['module_id' => '3', 'name' => 'Degree', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'Hierarchy', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'Kinship', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'Module', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'PensionEntity', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'ProcedureDocument', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],['module_id' => '3', 'name' => 'City', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'ProcedureModality', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'ProcedureRequirement', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'ProcedureType', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'Role', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'RoleUser', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'ScannedDocument', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'Spouse', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'Unit', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'Voucher', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'VoucherType', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
           
            ['module_id' => '3', 'name' => 'ContributionRate', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'ContributionType', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'DirectContribution', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'IpcRate', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'Reimbursement', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],

            ['module_id' => '3', 'name' => 'RetFunAdvisor', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'RetFunAdvisorBeneficiary', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'RetFunApplicant', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'RetFunBeneficiary', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'RetFunIncrement', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'RetFunLegalGuardian', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'RetFunLegalGuardianBeneficiary', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'RetFunProcedure', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'RetFunSubmittedDocument', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'RetirementFund', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['module_id' => '3', 'name' => 'ContributionCommitment', 'created_at' => '2018/04/26', 'updated_at' =>'2018/04/26'],
            ['module_id' => '3', 'name' => 'AidCommitment', 'created_at' => '2018/04/26', 'updated_at' =>'2018/04/26'],
        ]);
    }
}
