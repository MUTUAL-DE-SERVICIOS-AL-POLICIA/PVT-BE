<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Contribution\ContributionType;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Muserpol\Models\RetirementFund\RetFunRefundType;
use Muserpol\Models\Hierarchy;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\ProcedureType;

class RetFunEma2026 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $procedure = RetFunProcedure::findOrFail(1);

            // Sincronizar jerarquías
            $hierarchiesSyncData = Hierarchy::orderBy('id')
                ->pluck('id')
                ->mapWithKeys(function ($id) {
                    return [$id => ['apply_contributions_limit' => false]];
                })
                ->toArray();

            $procedure->hierarchies()->sync($hierarchiesSyncData);

            // Procedimientos
            $PG_PROCEDURE = ProcedureType::RET_FUN_PG;
            $DA_PROCEDURE = ProcedureType::RET_FUN_DA;

            // Sincronizar modalidades
            $modalitiesSyncData = ProcedureModality::whereIn('procedure_type_id', [$PG_PROCEDURE, $DA_PROCEDURE])
                ->get()
                ->mapWithKeys(function ($modality) use ($PG_PROCEDURE) {
                    return [
                        $modality->id => [
                            'annual_percentage_yield' => $modality->procedure_type_id == $PG_PROCEDURE ? 5 : 0
                        ]
                    ];
                })
                ->toArray();

            $procedure->procedure_modalities()->sync($modalitiesSyncData);

            // Actualizar tipos de aportes
            $newContributionTypes = [
                ['name' => 'Periodo posterior a 30 años de servicios activo', 'operator' => '-', 'shortened' => '+30 años'],
                ['name' => 'Disponibilidad por Enfermedad con aporte', 'operator' => '+', 'shortened' => 'Disponibilidad Enfermedad Con Aporte'],
                ['name' => 'Disponibilidad por Enfermedad sin aporte', 'operator' => '-', 'shortened' => 'Disponibilidad Enfermedad Sin Aporte'],
            ];
            foreach ($newContributionTypes as $value) {
                ContributionType::create($value);
            }
            ContributionType::where('name', 'Disponibilidad')->delete();

            // Nuevas submodalidades
            $newModalities = [
                ['procedure_type_id' => $PG_PROCEDURE, 'name' => 'Jubilación', 'shortened' => 'PGA - JUB'],
                ['procedure_type_id' => $DA_PROCEDURE, 'name' => 'Retiro Forzoso', 'shortened' => 'DA - RF'],
                ['procedure_type_id' => $DA_PROCEDURE, 'name' => 'Retiro Voluntario', 'shortened' => 'DA - RV'],
            ];

            foreach ($newModalities as $value) {
                ProcedureModality::create($value);
            }

            // Nuevos tipos de reembolso
            $newRefundTypes = [
                ['annual_percentage_yield' => 5, 'contribution_type_id' => ContributionType::where('shortened', '+30 años')->first()->id],
                ['annual_percentage_yield' => 0, 'contribution_type_id' => 12],
            ];

            foreach ($newRefundTypes as $value) {
                RetFunRefundType::create($value);
            }
        });
    }
}
