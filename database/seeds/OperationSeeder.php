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
            //['id' => '1', 'module_id' => '3', 'name' => 'Affiliate', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            //['id' => '2', 'module_id' => '3', 'name' => 'Contribution', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '3', 'module_id' => '3', 'name' => 'Address', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '4', 'module_id' => '3', 'name' => 'AffiliateFolder', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '5', 'module_id' => '3', 'name' => 'AffiliateState', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '6', 'module_id' => '3', 'name' => 'BaseWage', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '7', 'module_id' => '3', 'name' => 'Breakdown', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '8', 'module_id' => '3', 'name' => 'Category', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '9', 'module_id' => '3', 'name' => 'City', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],           
            ['id' => '10', 'module_id' => '3', 'name' => 'Degree', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '11', 'module_id' => '3', 'name' => 'Hierarchy', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '12', 'module_id' => '3', 'name' => 'Kinship', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '13', 'module_id' => '3', 'name' => 'Module', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '14', 'module_id' => '3', 'name' => 'PensionEntity', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '15', 'module_id' => '3', 'name' => 'ProcedureDocument', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],['id' => '9', 'module_id' => '3', 'name' => 'City', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '16', 'module_id' => '3', 'name' => 'ProcedureModality', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '17', 'module_id' => '3', 'name' => 'ProcedureRequirement', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '18', 'module_id' => '3', 'name' => 'ProcedureType', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '19', 'module_id' => '3', 'name' => 'Role', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '20', 'module_id' => '3', 'name' => 'RoleUser', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '21', 'module_id' => '3', 'name' => 'ScannedDocument', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '22', 'module_id' => '3', 'name' => 'Spouse', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '23', 'module_id' => '3', 'name' => 'Unit', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '24', 'module_id' => '3', 'name' => 'Voucher', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '25', 'module_id' => '3', 'name' => 'VoucherType', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
           
            ['id' => '26', 'module_id' => '3', 'name' => 'ContributionRate', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '27', 'module_id' => '3', 'name' => 'ContributionType', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '28', 'module_id' => '3', 'name' => 'DirectContribution', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '29', 'module_id' => '3', 'name' => 'IpcRate', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '30', 'module_id' => '3', 'name' => 'Reimbursement', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            
            ['id' => '31', 'module_id' => '3', 'name' => 'RetFunAddressBeneficiary', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '32', 'module_id' => '3', 'name' => 'RetFunAdvisor', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '33', 'module_id' => '3', 'name' => 'RetFunAdvisorBeneficiary', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '34', 'module_id' => '3', 'name' => 'RetFunApplicant', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '35', 'module_id' => '3', 'name' => 'RetFunBeneficiary', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '36', 'module_id' => '3', 'name' => 'RetFunIncrement', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '37', 'module_id' => '3', 'name' => 'RetFunLegalGuardian', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '38', 'module_id' => '3', 'name' => 'RetFunLegalGuardianBeneficiary', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '39', 'module_id' => '3', 'name' => 'RetFunProcedure', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '40', 'module_id' => '3', 'name' => 'RetFunSubmittedDocument', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['id' => '41', 'module_id' => '3', 'name' => 'RetirementFund', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
        ]);
    }
}
