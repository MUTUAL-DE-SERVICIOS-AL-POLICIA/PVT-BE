<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\ProcedureModality;
use Muserpol\Operation;

class DirectContributionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['module_id' => 11, 'name' => 'Pago de Aportes Activos', 'second_name' => null],
            ['module_id' => 11, 'name' => 'Pago de Aportes Pasivos', 'second_name' => null],
        ];
        foreach ($statuses as $status) {
            ProcedureType::create($status);
        }
        $statuses = [
            ['procedure_type_id' => 6, 'name' => 'Agregado Policial', 'shortened' => ''],
            ['procedure_type_id' => 6, 'name' => 'Baja Temporal', 'shortened' => ''],
            ['procedure_type_id' => 6, 'name' => 'Comisión', 'shortened' => ''],
            ['procedure_type_id' => 7, 'name' => 'Titular', 'shortened' => ''],
            ['procedure_type_id' => 7, 'name' => 'Cónyuge', 'shortened' => ''],
        ];
        foreach ($statuses as $status) {
            ProcedureModality::create($status);
        }
        $statuses = [
            ['module_id' => 11, 'name' => 'Affiliate'],
            ['module_id' => 11, 'name' => 'ContributionProcess'],
            ['module_id' => 11, 'name' => 'ContributionProcessSubmittedDocument'],
            ['module_id' => 11, 'name' => 'Spouse'],
            ['module_id' => 11, 'name' => 'Contribution'],
            ['module_id' => 11, 'name' => 'AidContribution'],
            ['module_id' => 11, 'name' => 'Reimbursement'],
            ['module_id' => 11, 'name' => 'DirectContribution'],
            ['module_id' => 11, 'name' => 'Voucher'],
        ];
        foreach ($statuses as $status) {
            Operation::create($status);
        }
    }
}
