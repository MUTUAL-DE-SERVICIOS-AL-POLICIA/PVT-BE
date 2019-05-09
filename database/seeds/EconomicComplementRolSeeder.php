<?php

use Illuminate\Database\Seeder;
use Muserpol\Operation;
use Muserpol\Models\DiscountType;

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
            ['module_id' => 6, 'name' => 'EconomicComplement'],
            ['module_id' => 6, 'name' => 'Affiliate'],
            ['module_id' => 6, 'name' => 'ObservationType'],
            ['module_id' => 9, 'name' => 'EconomicComplement'],
            ['module_id' => 9, 'name' => 'Affiliate'],
            ['module_id' => 9, 'name' => 'ObservationType'],
        ];
        foreach ($statuses as $status) {
            Operation::create($status);
        }
        $statuses = [
            ['module_id' => 2, 'name' => 'Amortización por Cuentas por Cobrar', 'shortened' => 'Cuentas por Cobrar'],
            ['module_id' => 2, 'name' => 'Amortización por Prestamos en Mora', 'shortened' => 'Prestamos en Mora'],
            ['module_id' => 2, 'name' => 'Amortización por Reposición de Fondos', 'shortened' => 'Reposición de Fondos'],
            ['module_id' => 2, 'name' => 'Amortización por Pago a Futuro', 'shortened' => 'Pago a Futuro'],
        ];
        foreach ($statuses as $status) {
            DiscountType::create($status);
        }
    }
}
