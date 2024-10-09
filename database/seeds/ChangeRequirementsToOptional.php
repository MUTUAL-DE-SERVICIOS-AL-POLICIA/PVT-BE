<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureRequirement;

class ChangeRequirementsToOptional extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // *********** Cambios de la nota MUSERPOL/DBE/UFRPSCAM/NI/Nro 1615/2024 ***********
        // *********** HRI/13328-2024 ***********
        // Cambiar requisitos a opcionales
        // Procedure_modality_id
        // Cuota Mortuoria
        $riesgoComunId = 9;
        $cumplimientoDeFuncionesId = 8;
        // Auxilio Mortuorio
        $titularFallecidoId = 13;

        $reqToChange = ProcedureRequirement::whereIn('procedure_modality_id', [$riesgoComunId, $cumplimientoDeFuncionesId, $titularFallecidoId])->where('number', 5)->get();

        foreach ($reqToChange as $req) {
            $req->number = 0;
            $req->save();
        }

        $reqRiesgoComun = ProcedureRequirement::where('procedure_modality_id', $riesgoComunId)->where('number','>',0)->orderBy('number')->get();
        $reqCumplimientoFunciones = ProcedureRequirement::where('procedure_modality_id', $cumplimientoDeFuncionesId)->where('number','>',0)->orderBy('number')->get();
        $reqTitularFallecido = ProcedureRequirement::where('procedure_modality_id', $titularFallecidoId)->where('number','>',0)->orderBy('number')->get();

        $this->organizeNumbers($reqRiesgoComun);
        $this->organizeNumbers($reqCumplimientoFunciones);
        $this->organizeNumbers($reqTitularFallecido);
    }

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
