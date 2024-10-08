<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\ProcedureRequirement;

class AddSpouseDeceasedQuotaMortuaryModalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Se añade la modalidad
        $modality = ProcedureModality::where('shortened','CM - FC')->first();
        if($modality == null){
            $modality = new ProcedureModality();
            $modality->procedure_type_id = 3; // Pago del Beneficio Cuota Mortuoria
            $modality->name = "Fallecimiento del (la) Cónyuge";
            $modality->shortened = "CM - FC";
            $modality->is_valid = true;
            $modality->save();
        }

        // Se añaden los requisitos
        $now = Carbon::now();
        $spouseDeceasedId = $modality->id;
        $requirementsSpouseDeceased = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 7, 'number' => 2],
            ['procedure_document_id' => 12, 'number' => 3],
            ['procedure_document_id' => 44, 'number' => 3],
            ['procedure_document_id' => 115, 'number' => 3],
            ['procedure_document_id' => 13, 'number' => 3],
            ['procedure_document_id' => 29, 'number' => 4],
            ['procedure_document_id' => 30, 'number' => 5],
            ['procedure_document_id' => 272, 'number' => 6],
            ['procedure_document_id' => 101, 'number' => 0],
            ['procedure_document_id' => 131, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 210, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
        ];
        foreach ($requirementsSpouseDeceased as &$req) {
            $req['procedure_modality_id'] = $spouseDeceasedId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }
        ProcedureRequirement::insert($requirementsSpouseDeceased);

        // Añadir montos para cálculo de cuantia en quota_aid_procedure
        DB::table('quota_aid_procedures')->insert([
            /*cuota mortuoria fallecimiento conyugue*/
            ['hierarchy_id' => '1', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => $modality->id,'amount' => '14000', 'year'=>'2024-07-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => $modality->id,'amount' => '14000', 'year'=>'2024-07-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => $modality->id,'amount' => '14000', 'year'=>'2024-07-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => $modality->id,'amount' => '14000', 'year'=>'2024-07-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => $modality->id,'amount' => '14000', 'year'=>'2024-07-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
        ]);
        
    }
}
