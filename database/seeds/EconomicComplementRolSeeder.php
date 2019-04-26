<?php

use Illuminate\Database\Seeder;
use Muserpol\Operation;

class EconomicComplementRolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['module_id' => 2, 'name' => 'Affiliate'],
            ['module_id' => 2, 'name' => 'EconomicComplement'],
            ['module_id' => 2, 'name' => 'EcoComBeneficiary'],
            ['module_id' => 2, 'name' => 'EcoComLegalGuardian'],
            ['module_id' => 2, 'name' => 'EcoComModality'],
            ['module_id' => 2, 'name' => 'EcoComProcedure'],
            ['module_id' => 2, 'name' => 'EcoComRent'],
            ['module_id' => 2, 'name' => 'EcoComState'],
            ['module_id' => 2, 'name' => 'EcoComStateType'],
            ['module_id' => 2, 'name' => 'EcoComSubmittedDocument'],
            ['module_id' => 2, 'name' => 'EcoComType'],
            ['module_id' => 2, 'name' => 'Address'],
            ['module_id' => 2, 'name' => 'AffiliateState'],
            ['module_id' => 2, 'name' => 'BaseWage'],
            ['module_id' => 2, 'name' => 'Breakdown'],
            ['module_id' => 2, 'name' => 'Category'],
            ['module_id' => 2, 'name' => 'Degree'],
            ['module_id' => 2, 'name' => 'Hierarchy'],
            ['module_id' => 2, 'name' => 'Kinship'],
            ['module_id' => 2, 'name' => 'PensionEntity'],
            ['module_id' => 2, 'name' => 'ProcedureDocument'],
            ['module_id' => 2, 'name' => 'City'],
            ['module_id' => 2, 'name' => 'ScannedDocument'],
            ['module_id' => 2, 'name' => 'Spouse'],
            ['module_id' => 2, 'name' => 'Unit'],
            ['module_id' => 2, 'name' => 'IpcRate'],
            ['module_id' => 2, 'name' => 'ComplementaryFactor'],
        ];
        foreach ($statuses as $status) {
            Operation::create($status);
        }
    }
}
