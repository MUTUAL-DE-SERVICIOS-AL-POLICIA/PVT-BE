<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureDocument;
use Muserpol\Models\ProcedureRequirement;

class AddAditionalDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documents_for_quota_aid = [
            ['name' => 'Poder Notarial conferido por el (la) o (los) y (las) derechohabientes del Titular en original, emitido por Autoridad Competente.'],
            ['name' => 'Poder Notarial conferido por el (la) o (los) y (las) derechohabientes del Titular en copia legalizada, emitido por Autoridad Competente.'],
        ];
        foreach($documents_for_quota_aid as $document) {
            $model = ProcedureDocument::firstOrCreate($document);
            // Cuota Mortuoria
            ProcedureRequirement::create([
                'procedure_modality_id' => 8, // Fallecimiento del (la) titular en cumplimiento de funciones
                'procedure_document_id' => $model->id,
                'number'                => 0
            ]);
            ProcedureRequirement::create([
                'procedure_modality_id' => 9, // Fallecimiento del (la) titular por riesgo común
                'procedure_document_id' => $model->id,
                'number'                => 0
            ]);
            // Auxilio Morturio
            ProcedureRequirement::create([
                'procedure_modality_id' => 13, // Fallecimiento del (la) titular
                'procedure_document_id' => $model->id,
                'number'                => 0
            ]);
            ProcedureRequirement::create([
                'procedure_modality_id' => 15, // Fallecimiento del (la) viudo(a)
                'procedure_document_id' => $model->id,
                'number'                => 0
            ]);
        }

        $new_documents_for_quota_aid = [
            ['name' => 'Resolución sobre la modificación en el número de la Cédula de Identidad del Titular en copia legalizada, emitido por el SEGIP.'],
            ['name' => 'Resolución sobre la modificación en el número de la Cédula de Identidad del Titular en original, emitido por el SEGIP.']
        ];
        foreach($new_documents_for_quota_aid as $new_document) {
            $model = ProcedureDocument::firstOrCreate($new_document);
            // Auxilio Mortuorio
            ProcedureRequirement::create([
                'procedure_modality_id' => 15, // Fallecimiento del (la) viudo(a)
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
        }












        // $procedure_documents = [
        //     ['name' => 'Certificado de estado civil del o los derechohabientes fallecidos, habiendo cumplido la mayoría de edad en original y actualizado, emitido por el SERECI.'],
        //     ['name' => 'Certificado de matrimonio original y actualizado del o los derechohabientes fallecidos, emitido por el SERECI.'],
        //     ['name' => 'Certificado de descendencia del o los derechohabientes fallecidos, habiendo cumplido la mayoría de edad en original y actualizado, emitido por el SERECI.'],
        //     ['name' => 'Copia simple del Certificado de Nacimiento del Titular, emitido por el SERECI.'],
        //     ['name' => 'Certificado de defunción de la cónyuge del Titular en original y actualizado, emitido por el SERECI.'],
        //     ['name' => 'Copia simple de la Cédula de Identidad de la Cónyuge del Titular.'],
        //     ['name' => 'Informe incluyendo el grado del (la) Titular en original, emitido por el Comando General de la Policía Boliviana.'],
        //     ['name' => 'Memorándum de Baja Definitiva en original, emitida por el Comando General de la Policía Boliviana.'],
        //     ['name' => 'Memorándum de Baja Definitiva en copia legalizada, emitida por el Comando General de la Policía Boliviana.'],
        //     ['name' => 'Memorándum de Baja Definitiva en original, emitida por el Comando Departamental de la Policía Boliviana.'],
        //     ['name' => 'Memorándum de Baja Definitiva en copia legalizada, emitida por el Comando Departamental de la Policía Boliviana.'],
        //     ['name' => 'Resolución sobre la modificación en el número de la Cédula de Identidad del Titular en copia legalizada, emitido por el SEGIP.'],
        //     ['name' => 'Resolución sobre la modificación en el número de la Cédula de Identidad del Titular en original, emitido por el SEGIP.'],
        // ];

        // foreach($procedure_documents as $procedure_document) {
        //     ProcedureDocument::firstOrCreate($procedure_document);
        // }
    }
}
