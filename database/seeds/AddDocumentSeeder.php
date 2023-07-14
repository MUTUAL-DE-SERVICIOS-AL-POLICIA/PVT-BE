<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureDocument;
use Muserpol\Models\ProcedureRequirement;

class AddDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documents_for_quota_aid = [
            ['name' => 'Certificado de defunción del o los derechohabientes fallecidos en original y actualizado, emitido por el SERECI.'],
            ['name' => 'Resolución de ascenso póstumo del (la) Titular en original, emitido por el Comando General de la Policía Boliviana.'],
            ['name' => 'Resolución de ascenso póstumo del (la) Titular en copia legalizada, emitido por el Comando General de la Policía Boliviana.']
        ];

        foreach($documents_for_quota_aid as $document) {
            $model = ProcedureDocument::firstOrCreate($document);
            // Cuota Mortuoria
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 8,// Fallecimiento del (la) titular en cumplimiento de funciones
                'procedure_document_id' => $model->id,
                'number'                => 0
            ]);
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 9, // Fallecimiento del (la) titular por riesgo común
                'procedure_document_id' => $model->id,
                'number'                => 0
            ]);
        }

        ProcedureDocument::where('name','Poder Notarial conferido por el (la) o (los) y (las) derechohabientes de la Viuda (o) del Titular en original, emitido por Autoridad Competente.')
                        ->update(['name' => 'Poder Notarial conferido por el (la) o (los) y (las) derechohabientes de la o del Viuda (o) del Titular en original, emitido por Autoridad Competente.']);
        ProcedureDocument::where('name', 'Poder Notarial conferido por el (la) o (los) y (las) derechohabientes de la o la Viuda (o)  del Titular en copia legalizada, emitido por Autoridad Competente.')
                        ->update(['name' => 'Poder Notarial conferido por el (la) o (los) y (las) derechohabientes de la o del Viuda (o) del o la Titular en copia legalizada, emitido por Autoridad Competente.']);
    }
}
