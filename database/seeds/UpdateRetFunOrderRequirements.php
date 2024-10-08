<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureDocument;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\ProcedureRequirement;

class UpdateRetFunOrderRequirements extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cambios al numero de orden los documentos de Fondo de retiro, cuota y auxilio
        // En Fecha 30 Sep 2024

        $requerimientosCMRiesgoComun= [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 9, 'number' => 2],
            ['procedure_document_id' => 33, 'number' => 3],
            ['procedure_document_id' => 11, 'number' => 3],
            ['procedure_document_id' => 12, 'number' => 4],
            ['procedure_document_id' => 44, 'number' => 4],
            ['procedure_document_id' => 115, 'number' => 4],
            ['procedure_document_id' => 13, 'number' => 4],
            ['procedure_document_id' => 25, 'number' => 5],
            ['procedure_document_id' => 16, 'number' => 5],
            ['procedure_document_id' => 17, 'number' => 5],
            ['procedure_document_id' => 231, 'number' => 6],
            ['procedure_document_id' => 272, 'number' => 7],
        ];

        $requerimientosCMCumplimientoFunciones= [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 9, 'number' => 2],
            ['procedure_document_id' => 33, 'number' => 3],
            ['procedure_document_id' => 11, 'number' => 3],
            ['procedure_document_id' => 12, 'number' => 4],
            ['procedure_document_id' => 44, 'number' => 4],
            ['procedure_document_id' => 115, 'number' => 4],
            ['procedure_document_id' => 13, 'number' => 4],
            ['procedure_document_id' => 25, 'number' => 5],
            ['procedure_document_id' => 16, 'number' => 5],
            ['procedure_document_id' => 17, 'number' => 5],
            ['procedure_document_id' => 231, 'number' => 6],
            ['procedure_document_id' => 26, 'number' => 7],
            ['procedure_document_id' => 272, 'number' => 8],
        ];

        $requerimientosCMFallecimientoConyugue= [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 30, 'number' => 2],
            ['procedure_document_id' => 29, 'number' => 3],
            ['procedure_document_id' => 12, 'number' => 4],
            ['procedure_document_id' => 44, 'number' => 4],
            ['procedure_document_id' => 115, 'number' => 4],
            ['procedure_document_id' => 13, 'number' => 4],
            ['procedure_document_id' => 7, 'number' => 5],
            ['procedure_document_id' => 272, 'number' => 6],
        ];

        $requerimientosAMTitularFallecido = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 9, 'number' => 2],
            ['procedure_document_id' => 11, 'number' => 3],
            ['procedure_document_id' => 33, 'number' => 3],
            ['procedure_document_id' => 12, 'number' => 4],
            ['procedure_document_id' => 44, 'number' => 4],
            ['procedure_document_id' => 115, 'number' => 4],
            ['procedure_document_id' => 13, 'number' => 4],
            ['procedure_document_id' => 25, 'number' => 5],
            ['procedure_document_id' => 16, 'number' => 5],
            ['procedure_document_id' => 17, 'number' => 5],
            ['procedure_document_id' => 272, 'number' => 6],
        ];

        $requerimientosAMConyugueFallecida = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 30, 'number' => 2],
            ['procedure_document_id' => 29, 'number' => 3],
            ['procedure_document_id' => 12, 'number' => 4],
            ['procedure_document_id' => 13, 'number' => 4],
            ['procedure_document_id' => 272, 'number' => 5],
        ];

        $reqId = ProcedureDocument::where('name', 'Certificado original y actualizado de descendencia del (la) Viuda fallecida, emitido por el SERECI')->first()->id;

        $requerimientosAMViudaFallecida = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 33, 'number' => 2],
            ['procedure_document_id' => 32, 'number' => 3],
            ['procedure_document_id' => 11, 'number' => 4],
            ['procedure_document_id' => 12, 'number' => 5],
            ['procedure_document_id' => 44, 'number' => 5],
            ['procedure_document_id' => 115, 'number' => 5],
            ['procedure_document_id' => 13, 'number' => 5],
            ['procedure_document_id' => $reqId, 'number' => 6],
            ['procedure_document_id' => 25, 'number' => 7],
            ['procedure_document_id' => 16, 'number' => 7],
            ['procedure_document_id' => 17, 'number' => 7],
            ['procedure_document_id' => 272, 'number' => 8],
        ];

        // Procedure_modality_id
        // Cuota Mortuoria
        $riesgoComunId = 9;
        $cumplimientoDeFuncionesId = 8;
        $conyugueFallecidaCMId = ProcedureModality::where('shortened', 'CM - FC')->first()->id;
        // Auxilio Mortuorio
        $titularFallecidoId = 13;
        $conyugueFallecidaId = 14;
        $viudaFallecidaId = 15;

        $this->orderRequirements($riesgoComunId, $requerimientosCMRiesgoComun);
        $this->orderRequirements($cumplimientoDeFuncionesId, $requerimientosCMCumplimientoFunciones);
        $this->orderRequirements($conyugueFallecidaCMId, $requerimientosCMFallecimientoConyugue);
        $this->orderRequirements($titularFallecidoId, $requerimientosAMTitularFallecido);
        $this->orderRequirements($conyugueFallecidaId, $requerimientosAMConyugueFallecida);
        $this->orderRequirements($viudaFallecidaId, $requerimientosAMViudaFallecida);

    }

    public function orderRequirements($procedureId,$list){
        foreach ($list as $item) {
            $findItem = ProcedureRequirement::where('procedure_modality_id', $procedureId)->where('procedure_document_id',$item['procedure_document_id'])->first();

            //logger($procedureId . "  :  " . $findItem->number . "  ->  " . $item['number']);
            if ($findItem->number != $item['number']) {
                $findItem->number = $item['number'];
                $findItem->save();  
            }
        }
    }
}
