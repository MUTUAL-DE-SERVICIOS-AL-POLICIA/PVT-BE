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
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 8, // Fallecimiento del (la) titular en cumplimiento de funciones
                'procedure_document_id' => $model->id,
                'number'                => 0
            ]);
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 9, // Fallecimiento del (la) titular por riesgo común
                'procedure_document_id' => $model->id,
                'number'                => 0
            ]);
            // Auxilio Morturio
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 13, // Fallecimiento del (la) titular
                'procedure_document_id' => $model->id,
                'number'                => 0
            ]);
        }

        $new_documents_for_quota_aid = [
            ['name' => 'Poder Notarial conferido por el (la) o (los) y (las) derechohabientes de la Viuda (o) del Titular en original, emitido por Autoridad Competente.'],
            ['name' => 'Poder Notarial conferido por el (la) o (los) y (las) derechohabientes de la o la Viuda (o)  del Titular en copia legalizada, emitido por Autoridad Competente.'],
            ['name' => 'Resolución sobre la modificación en el número de la Cédula de Identidad del Titular en copia legalizada, emitido por el SEGIP.'],
            ['name' => 'Resolución sobre la modificación en el número de la Cédula de Identidad del Titular en original, emitido por el SEGIP.'],
        ];
        foreach($new_documents_for_quota_aid as $new_document) {
            $model = ProcedureDocument::firstOrCreate($new_document);
            // Auxilio Mortuorio
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 15, // Fallecimiento del (la) viudo(a)
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
        }
    }
}
