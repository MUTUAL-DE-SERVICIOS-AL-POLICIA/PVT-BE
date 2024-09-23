<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureDocument;
use Muserpol\Models\ProcedureRequirement;

class UpdateRequirementsQuotaAidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // *********** Cambios de la respuesta a nota MUSERPOL/DAA/US/NI/Nro 0385/2024 ***********
        // *********** HRI/03238-2024 ***********
        // Procedure_modality_id
        // Cuota Mortuoria
        $riesgoComunId = 9;
        $cumplimientoDeFuncionesId = 8;
        // Auxilio Mortuorio
        $titularFallecidoId = 13;
        $conyugueFallecidaId = 14;
        $viudaFallecidaId = 15;

        // Cambios para Auxilio Mortuorio - Fallecimiento Titular
        $reqToChange = ProcedureRequirement::where('procedure_modality_id', $titularFallecidoId)->where('procedure_document_id',30)->first();
        $reqToChange->procedure_document_id = 33;
        $reqToChange->save();

        // Cambios para Auxilio Mortuorio - Fallecimiento Conyuge
        $reqToAdd = new ProcedureRequirement();
        $reqToAdd->procedure_modality_id = $conyugueFallecidaId;
        $reqToAdd->procedure_document_id = 30;
        $reqToAdd->number = 5;
        $reqToAdd->save();

        // Cambios para Auxilio Mortuorio - Fallecimiento Viuda
        $newDoc = new ProcedureDocument();
        $newDoc->name = "Certificado original y actualizado de descendencia del (la) Viuda fallecida, emitido por el SERECI";
        $newDoc->save();

        $reqToAddFV = new ProcedureRequirement();
        $reqToAddFV->procedure_modality_id = $viudaFallecidaId;
        $reqToAddFV->procedure_document_id = $newDoc->id;
        $reqToAddFV->number = 9;
        $reqToAddFV->save();

        // Cambios para Cuota Mortuoria - Fallecimiento Cumplimiento de Funciones
        $reqToChangeCF = ProcedureRequirement::where('procedure_modality_id', $cumplimientoDeFuncionesId)->where('procedure_document_id',15)->first();
        $reqToChangeCF->number = 0;
        $reqToChangeCF->save();

        $reqToChangeCF2 = ProcedureRequirement::where('procedure_modality_id', $cumplimientoDeFuncionesId)->where('procedure_document_id',29)->first();
        $reqToChangeCF2->number = 0;
        $reqToChangeCF2->save();

        $CumplimientoFuncionesList = ProcedureRequirement::where('procedure_modality_id', $cumplimientoDeFuncionesId)->where('number','>',0)->orderBy('number')->get();
        $this->organizeNumbers($CumplimientoFuncionesList);

        // Cambios para Cuota Mortuoria - Fallecimiento Riesgo Comun

        $reqToChangeRC = ProcedureRequirement::where('procedure_modality_id', $riesgoComunId)->where('procedure_document_id',15)->first();
        $reqToChangeRC->number = 0;
        $reqToChangeRC->save();

        $reqToChangeRC2 = ProcedureRequirement::where('procedure_modality_id', $riesgoComunId)->where('procedure_document_id',29)->first();
        $reqToChangeRC2->number = 0;
        $reqToChangeRC2->save();
        $RiesgoComunList = ProcedureRequirement::where('procedure_modality_id', $riesgoComunId)->where('number','>',0)->orderBy('number')->get();
        
        $this->organizeNumbers($RiesgoComunList);
        


    }
    // Función que elimina saltos en la numeración de los procedure_requirements
    public function organizeNumbers($list){
        $correlativeNumber = 1;
        $actualNumber = 1;
        foreach ($list as $req) {
            if($actualNumber != $req->number){
                $correlativeNumber++;
                $actualNumber = $req->number;
            }
            if($actualNumber > $correlativeNumber){
                $req->number = $correlativeNumber;
                $req->save();
            }
        }
    }
}
