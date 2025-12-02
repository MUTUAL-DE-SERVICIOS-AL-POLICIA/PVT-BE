<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureDocument;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Helpers\ID;

class AddNewDocumentsCE21022025Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();
            // Cambio de descripción
            $doc269 = ProcedureDocument::find(269); // old: Boleta de renta o pensión de jubilación para la calificación, en fotocopia.
            $doc269->name = 'Boleta de renta o pensión de jubilación para la calificación, en fotocopia. (Titular)';
            $doc269->save();
    
            // Añadir codificación
            $doc440 = ProcedureDocument::find(440); // Certificado original y actualizado de descendencia del (la) Viuda fallecida, emitido por el SERECI
            $doc440->shortened = "CDV";
            $doc440->save();
            
            // Nuevos documentos
            $ids = ProcedureDocument::whereIn('id',[441,442,443,444,445,446])->get();
            if($ids->count() > 0) {
                $this->command->info("Ya existen documentos asignados a los id que corresponde añadir");
            } else {
                $newDocs = [
                    ['name' => 'Boleta de renta o pensión de jubilación para la calificación, en fotocopia. (viuda)', 'shortened' => 'BOL_JUBCV'], // id - 441
                    ['name' => 'Formulario de Registro de Beneficiario SIGEP. (viuda)', 'shortened' => 'FOR_SIGEPV'], // id - 442
                    ['name' => 'Compromiso de pago de aportes para el beneficio de Auxilio Mortuorio. (viuda)', 'shortened' => 'FOR_AUTV'], // id - 443
                    ['name' => 'Boleta de renta o pensión de jubilación para la calificación, en fotocopia. (huerfano)', 'shortened' => 'BOL_JUBCH'], // id - 444
                    ['name' => 'Boleta de renta o pensión de Jubilación para la verificación del cumplimiento del semestre, en fotocopia. (huerfano)', 'shortened' => 'BOL_JUBVH'], // id - 445
                    ['name' => 'Formulario de Registro de Beneficiario SIGEP. (huerfano)', 'shortened' => 'FOR_SIGEPH'], // id - 446
                ];
                $createdDoc = [];
                foreach ($newDocs as $doc) {
                    $createdDoc[] = ProcedureDocument::create($doc)->id; 
                }
            }
            
            // Modificar requisitos por modalidad
            $widowhood_modality_id = ID::ecoCom()->widowhood;
            $orphan_modality_id = ID::ecoCom()->orphanhood;

            // VIUDEDAD
            $reqToChange1 = 269; // Boleta de renta o pensión de jubilación para la calificación, en fotocopia. (Titular)
            $proc_req1 = ProcedureRequirement::where('procedure_modality_id',$widowhood_modality_id)->where('procedure_document_id',$reqToChange1)->first();
            $proc_req1->procedure_document_id = 441; // Boleta de renta o pensión de jubilación para la calificación, en fotocopia. (viuda)
            $proc_req1->save();

            $reqToChange2 = 272; // Formulario de Registro de Beneficiario SIGEP.
            $proc_req2 = ProcedureRequirement::where('procedure_modality_id',$widowhood_modality_id)->where('procedure_document_id',$reqToChange2)->first();
            $proc_req2->procedure_document_id = 442; // Formulario de Registro de Beneficiario SIGEP. (viuda)
            $proc_req2->save();

            $reqToChange3 = 320; // Compromiso de pago de aportes para el beneficio de Auxilio Mortuorio.
            $proc_req3 = ProcedureRequirement::where('procedure_modality_id',$widowhood_modality_id)->where('procedure_document_id',$reqToChange3)->first();
            $proc_req3->procedure_document_id = 443; // Compromiso de pago de aportes para el beneficio de Auxilio Mortuorio. (viuda)
            $proc_req3->save();

            // ORFANDAD
            $proc_req4 = ProcedureRequirement::where('procedure_modality_id',$orphan_modality_id)->where('procedure_document_id',$reqToChange1)->first();
            $proc_req4->procedure_document_id = 444; // Compromiso de pago de aportes para el beneficio de Auxilio Mortuorio. (huerfano)
            $proc_req4->save();

            $reqToChange4 = 237; // Boleta de renta o pensión de Jubilación para la verificación del cumplimiento del semestre, en fotocopia.
            $proc_req5 = ProcedureRequirement::where('procedure_modality_id',$orphan_modality_id)->where('procedure_document_id',$reqToChange4)->first();
            $proc_req5->procedure_document_id = 445; // Boleta de renta o pensión de Jubilación para la verificación del cumplimiento del semestre, en fotocopia. (huerfano)
            $proc_req5->save();

            $proc_req6 = ProcedureRequirement::where('procedure_modality_id',$orphan_modality_id)->where('procedure_document_id',$reqToChange2)->first();
            $proc_req6->procedure_document_id = 446; // Formulario de Registro de Beneficiario SIGEP. (huerfano)
            $proc_req6->save();

            DB::commit();
            $this->command->info("Descripción del documento 269 actualizado");
            $this->command->info("Código del documento 440 añadido");
            $this->command->info("Documentos " . implode(', ', $createdDoc) . " creados.");
            $this->command->info("Requisitos de viudedad cambiados: 269 -> 441, 272->442, 320->443");
            $this->command->info("Requisitos de orfandad cambiados: 269 -> 444, 237->445, 272->446");
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
