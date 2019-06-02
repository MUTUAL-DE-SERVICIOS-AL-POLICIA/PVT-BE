<?php

use Illuminate\Database\Seeder;
use Muserpol\Operation;
use Muserpol\Models\DiscountType;
use Muserpol\Models\Workflow\WorkflowSequence;
use Muserpol\Models\EconomicComplement\EcoComLegalGuardianType;
use Muserpol\Models\EconomicComplement\EcoComModality;
use Muserpol\Models\EconomicComplement\EcoComRent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Muserpol\Action;
use Muserpol\Permission;
use Muserpol\Models\Role;
use Muserpol\Models\EconomicComplement\EcoComReceptionType;
use Muserpol\Models\RecordType;

class EconomicComplementRolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EconomicComplementSeeder::class);
        $statuses = [
            // ['module_id' => 2, 'name' => 'Affiliate'],
            ['module_id' => 2, 'name' => 'EconomicComplement'],
            ['module_id' => 2, 'name' => 'EcoComProcedure'],
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
            ['module_id' => 2, 'name' => 'Note'],
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
        $statuses = [
            ['workflow_id' => 1, 'wf_state_current_id' => 1, 'wf_state_next_id' => 8, 'action' => 'Aprobar'],
            ['workflow_id' => 1, 'wf_state_current_id' => 3, 'wf_state_next_id' => 8, 'action' => 'Aprobar'],
            ['workflow_id' => 2, 'wf_state_current_id' => 1, 'wf_state_next_id' => 8, 'action' => 'Aprobar'],
            ['workflow_id' => 2, 'wf_state_current_id' => 3, 'wf_state_next_id' => 8, 'action' => 'Aprobar'],
            ['workflow_id' => 3, 'wf_state_current_id' => 1, 'wf_state_next_id' => 8, 'action' => 'Aprobar'],
            ['workflow_id' => 3, 'wf_state_current_id' => 3, 'wf_state_next_id' => 8, 'action' => 'Aprobar'],
        ];
        foreach ($statuses as $status) {
            WorkflowSequence::create($status);
        }
        $statuses = [
            ['name' => 'Solicitante'],
            ['name' => 'Cobrador'],
            ['name' => 'Solicitante y Cobrador'],
        ];
        foreach ($statuses as $status) {
            EcoComLegalGuardianType::create($status);
        }

        $eco_com_modalities = EcoComModality::whereIn('id', [1,4,6,8])->get();
        foreach ($eco_com_modalities as $e) {
            $e->procedure_modality_id = 29;
            $e->save();
        }
        $eco_com_modalities = EcoComModality::whereIn('id', [2,5,7,9])->get();
        foreach ($eco_com_modalities as $e) {
            $e->procedure_modality_id = 30;
            $e->save();
        }
        $eco_com_modalities = EcoComModality::whereIn('id', [3,10,11,12])->get();
        foreach ($eco_com_modalities as $e) {
            $e->procedure_modality_id = 31;
            $e->save();
        }
        $eco_com_rents= EcoComRent::where('eco_com_type_id', 1)->get();
        foreach ($eco_com_rents as $e) {
            $e->procedure_modality_id = 29;
            $e->save();
        }
        $eco_com_rents= EcoComRent::where('eco_com_type_id', 2)->get();
        foreach ($eco_com_rents as $e) {
            $e->procedure_modality_id = 30;
            $e->save();
        }
        $eco_com_rents= EcoComRent::where('eco_com_type_id', 3)->get();
        foreach ($eco_com_rents as $e) {
            $e->procedure_modality_id = 31;
            $e->save();
        }
        if (Schema::hasColumn('eco_com_rents', 'eco_com_type_id')) {
            Schema::table('eco_com_rents', function (Blueprint $table) {
                $table->dropColumn('eco_com_type_id');
            });
        }
        $statuses = [
            ['name' => 'Habitual'],
            ['name' => 'Inclusión'],
        ];
        foreach ($statuses as $status) {
            EcoComReceptionType::create($status);
        }
        $statuses = [
            ['name' => 'Observaciones', 'description' => 'Datos de la Observación.'],
            ['name' => 'Amortizaciones', 'description' => 'Datos de la Amortización.'],
            ['name' => 'Beneficiario(s)', 'description' => 'Datos del o los Beneficiario(s).'],
            ['name' => 'Apoderado', 'description' => 'Datos del Apoderado Legal.'],
            ['name' => 'Notas', 'description' => 'Notas.'],
            ['name' => 'Direcciones', 'description' => 'direcciones.'],
            ['name' => 'Etiquetas', 'description' => 'etiquetas.'],
        ];
        foreach ($statuses as $status) {
            RecordType::create($status);
        }
        $this->call(EconomicComplementRequirementsSeeder::class);
        $this->call(EcoComPermissionSeeder::class);
        $this->call(TagAffiliateSeeder::class);
    }
}
