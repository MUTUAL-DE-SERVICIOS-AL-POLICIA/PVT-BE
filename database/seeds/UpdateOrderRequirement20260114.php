<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureRequirement;

class UpdateOrderRequirement20260114 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cambios al numero de orden los documentos de Fondo de retiro
        // En Fecha 14-01-2026

        // Fondo
        $fondoRetiroInvalidezId = 101;
        // Pago global
        $pagoGlobalInvalidezPermanenteId = 2;

        $requerimientosFRInvalidez = [
            ['procedure_document_id' => 45, 'number' => 2],
            ['procedure_document_id' => 20, 'number' => 2],
            ['procedure_document_id' => 19, 'number' => 2],
            ['procedure_document_id' => 264, 'number' => 3],
            ['procedure_document_id' => 7, 'number' => 4],
            ['procedure_document_id' => 231, 'number' => 5],
            ['procedure_document_id' => 272, 'number' => 6]
        ];

        $requerimientosPGInvalidezPermanente = [
            ['procedure_document_id' => 45, 'number' => 2],
            ['procedure_document_id' => 20, 'number' => 2],
            ['procedure_document_id' => 19, 'number' => 2],
            ['procedure_document_id' => 264, 'number' => 3],
            ['procedure_document_id' => 7, 'number' => 4],
            ['procedure_document_id' => 231, 'number' => 5],
            ['procedure_document_id' => 272, 'number' => 6]
        ];

        $this->orderRequirements($fondoRetiroInvalidezId, $requerimientosFRInvalidez);
        $this->orderRequirements($pagoGlobalInvalidezPermanenteId, $requerimientosPGInvalidezPermanente);

    }
    
    public function orderRequirements($procedureId, $list)
    {
        foreach($list as $item) {
            $findItem = ProcedureRequirement::where('procedure_modality_id', $procedureId)->where('procedure_document_id', $item['procedure_document_id'])->first();

            if($findItem->number != $item['number']) {
                $findItem->number = $item['number'];
                $findItem->save();
            }
        }
    }
}
