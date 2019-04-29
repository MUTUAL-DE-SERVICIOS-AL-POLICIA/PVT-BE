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
            ['module_id' => 2, 'name' => 'EcoComRent'],
            ['module_id' => 2, 'name' => 'EcoComSubmittedDocument'],
            ['module_id' => 2, 'name' => 'Address'],
            ['module_id' => 2, 'name' => 'ScannedDocument'],
            ['module_id' => 2, 'name' => 'Spouse'],
            ['module_id' => 2, 'name' => 'ComplementaryFactor'],
            ['module_id' => 2, 'name' => 'ObservationType'],
        ];
        foreach ($statuses as $status) {
            Operation::create($status);
        }
    }
}
