<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureRequirement;

class UpdateOrderRequirement08102024 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cambios al numero de orden los documentos de Fondo de retiro
        // En Fecha 08 Oct 2024

        // Fondo
        $fondoRetiroJubilacionId = 3;
        $fondoRetiroFallecimientoId = 4;
        $retiroVoluntarioId = 7;
        $retiroForzosoId = 5;

        $requerimientosFRJubilacion = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 7, 'number' => 4],
            ['procedure_document_id' => 206, 'number' => 0],
            ['procedure_document_id' => 334, 'number' => 0],
            ['procedure_document_id' => 208, 'number' => 3],
            ['procedure_document_id' => 335, 'number' => 3],
            ['procedure_document_id' => 229, 'number' => 2],
            ['procedure_document_id' => 240, 'number' => 2],
            ['procedure_document_id' => 231, 'number' => 5],
            ['procedure_document_id' => 272, 'number' => 0],
        ];

        $requerimientosFRFallecimiento = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 7, 'number' => 7],
            ['procedure_document_id' => 9, 'number' => 2],
            ['procedure_document_id' => 11, 'number' => 3],
            ['procedure_document_id' => 12, 'number' => 4],
            ['procedure_document_id' => 44, 'number' => 4],
            ['procedure_document_id' => 115, 'number' => 4],
            ['procedure_document_id' => 13, 'number' => 4],
            ['procedure_document_id' => 15, 'number' => 5],
            ['procedure_document_id' => 33, 'number' => 3],
            ['procedure_document_id' => 25, 'number' => 6],
            ['procedure_document_id' => 16, 'number' => 6],
            ['procedure_document_id' => 17, 'number' => 6],
            ['procedure_document_id' => 206, 'number' => 0],
            ['procedure_document_id' => 334, 'number' => 0],
            ['procedure_document_id' => 208, 'number' => 0],
            ['procedure_document_id' => 335, 'number' => 0],
            ['procedure_document_id' => 231, 'number' => 8],
            ['procedure_document_id' => 272, 'number' => 0],

        ];

        $requerimientosFRRetiroVoluntario = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 7, 'number' => 3],
            ['procedure_document_id' => 19, 'number' => 2],
            ['procedure_document_id' => 45, 'number' => 2],
            ['procedure_document_id' => 20, 'number' => 2],
            ['procedure_document_id' => 240, 'number' => 2],
            ['procedure_document_id' => 206, 'number' => 0],
            ['procedure_document_id' => 334, 'number' => 0],
            ['procedure_document_id' => 208, 'number' => 0],
            ['procedure_document_id' => 335, 'number' => 0],
            ['procedure_document_id' => 231, 'number' => 4],
            ['procedure_document_id' => 272, 'number' => 0],
            ['procedure_document_id' => 325, 'number' => 0],
        ];

        $requerimientosFRRetiroForzoso = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 7, 'number' => 3],
            ['procedure_document_id' => 19, 'number' => 2],
            ['procedure_document_id' => 20, 'number' => 2],
            ['procedure_document_id' => 240, 'number' => 2],
            ['procedure_document_id' => 206, 'number' => 0],
            ['procedure_document_id' => 334, 'number' => 0],
            ['procedure_document_id' => 208, 'number' => 0],
            ['procedure_document_id' => 335, 'number' => 0],
            ['procedure_document_id' => 231, 'number' => 4],
            ['procedure_document_id' => 272, 'number' => 0],
            ['procedure_document_id' => 325, 'number' => 0],
        ];

        $this->orderRequirements($fondoRetiroJubilacionId, $requerimientosFRJubilacion);
        $this->orderRequirements($fondoRetiroFallecimientoId, $requerimientosFRFallecimiento);
        $this->orderRequirements($retiroVoluntarioId, $requerimientosFRRetiroVoluntario);
        $this->orderRequirements($retiroForzosoId, $requerimientosFRRetiroForzoso);
    }

    public function orderRequirements($procedureId, $list)
    {
        foreach ($list as $item) {
            $findItem = ProcedureRequirement::where('procedure_modality_id', $procedureId)->where('procedure_document_id', $item['procedure_document_id'])->first();

            if ($findItem->number != $item['number']) {
                $findItem->number = $item['number'];
                $findItem->save();
            }
        }
    }
}
