<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\ProcedureDocument;

class AditionalDocumentRequirementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedure_documents = [
            ['name' => 'Memorándum de Agradecimiento por servicios en original, emitido por el Comando General de la Policía Boliviana.'],
            ['name' => 'Memorándum de Agradecimiento por servicios en copia legalizada, emitido por el Comando General de la Policía Boliviana.'],
            ['name' => 'Certificado de estado civil del o los derechohabientes fallecidos, habiendo cumplido la mayoría de edad en original y actualizado, emitido por el SERECI.'],
            ['name' => 'Certificado de matrimonio original y actualizado del o los derechohabientes fallecidos, emitido por el SERECI.'], //hay
            ['name' => 'Certificado de descendencia del o los derechohabientes fallecidos, habiendo cumplido la mayoría de edad en original y actualizado, emitido por el SERECI.'],
            ['name' => 'Copia simple del Certificado de Nacimiento del Titular, emitido por el SERECI.'],
            ['name' => 'Certificado de defunción de la cónyuge del Titular en original y actualizado, emitido por el SERECI.'], // Hay
            ['name' => 'Copia simple de la Cédula de Identidad de la Cónyuge del Titular.'], // hay
            ['name' => 'Informe incluyendo el grado del (la) Titular en original, emitido por el Comando General de la Policía Boliviana.'],
        ];

        foreach($procedure_documents as $procedure_document) {
            $model = ProcedureDocument::firstOrCreate($procedure_document);
            // Pago global de aportes
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 1, // Fallecimiento
                'procedure_document_id'  => $model->id,
                'number' => 0
            ]);
            // Pago del Beneficio Fondo de Retiro Policíal
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 4, // Fallecimiento
                'procedure_document_id'  => $model->id,
                'number' => 0
            ]);
            // Devolución de Aportes
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 63, // Fallecimiento
                'procedure_document_id'  => $model->id,
                'number' => 0
            ]);
        }

        $procedure_documents = [
            ['name' => 'Resolución de Baja Definitiva en original, emitida por el Comando General de la Policía Boliviana.'],
            ['name' => 'Resolución de Baja Definitiva en copia legalizada, emitida por el Comando General de la Policía Boliviana.'],
            ['name' => 'Memorándum de Baja Definitiva en original, emitida por el Comando General de la Policía Boliviana.'],
            ['name' => 'Memorándum de Baja Definitiva en copia legalizada, emitida por el Comando General de la Policía Boliviana.'],
            ['name' => 'Memorándum de Baja Definitiva en original, emitida por el Comando Departamental de la Policía Boliviana.'],
            ['name' => 'Memorándum de Baja Definitiva en copia legalizada, emitida por el Comando Departamental de la Policía Boliviana.'],
            ['name' => 'Certificación de Baja Definitiva en original, emitida por el Comando General de la Policía Boliviana.'],
        ];
        foreach($procedure_documents as $procedure_document) {
            $model = ProcedureDocument::firstOrCreate($procedure_document);
            // Devolución de aportes
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 62, // Titular
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 63, // Fallecimiento
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
        }

        $procedure_documents = [
            ['name' => 'Informe determinando la priorización al proceso del trámite porque el o la Titular adolece de una enfermedad grave y/o terminal, emitido por el Área de Trabajo Social de la MUSERPOL.'],
            ['name' => 'Informe determinando la priorización al proceso del trámite porque el o la Titular o sus familiares de primer grado están pasando una situación de extrema necesidad, emitido por el Área de Trabajo Social de la MUSERPOL.'],
        ];

        foreach($procedure_documents as $procedure_document) {
            $model = ProcedureDocument::firstOrCreate($procedure_document);
            // Pago Global de Aportes
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 24, // Jubilación Debido a Reincorporación
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 2,  // Invalidéz Permanente
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
            // Beneficio de Pago de Fondo de Retiro Policial Solidario
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 3,  // Jubilación
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
        }

        $procedure_documents = [
            ['name' => 'Informe determinando la priorización al proceso del trámite porque los derechohabientes del titular adolecen de una enfermedad grave y/o terminal, emitido por el Área de Trabajo Social de la MUSERPOL.'],
            ['name' => 'Informe determinando la priorización al proceso del trámite trámite porque los derechohabientes del titular están pasando una situación de extrema necesidad, emitido por el Área de Trabajo Social de la MUSERPOL.'],
            ['name' => 'Certificado de defunción del o los derechohabientes fallecidos en original y actualizado, emitido por el SERECI.'],
            ['name' => 'Resolución de ascenso póstumo del (la) Titular en original, emitido por el Comando General de la Policía Boliviana.'],
            ['name' => 'Resolución de ascenso póstumo del (la) titular en copia legalizada, emitido por el Comando General de la Policía Boliviana.'],
            ['name' => 'Copia simple del Certificado de Nacimiento del Titular, emitido por el SERECI.'],
        ];

        foreach($procedure_documents as $procedure_document) {
            $model = ProcedureDocument::firstOrCreate($procedure_document);
            // Pago Global de Aportes
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 1,  // Fallecimiento
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
            // Beneficio de Pago de Fondo de Retiro Policial Solidario
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 4,  // Fallecimiento
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
        }

        $procedure_documents = [
            ['name' => 'Certificado de defunción del (los) progenitor(es) del Titular en original, emitido por el SERECI.'],
            ['name' => 'Certificado de descendencia vigente del (los) progenitor (es) en original, emitido por el SERECI.'],

        ];
        foreach($procedure_documents as $procedure_document) {
            $model = ProcedureDocument::firstOrCreate($procedure_document);
            // Pago Global de Aportes
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 1,  // Fallecimiento
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
        }

        $procedure_documents = [
            ['name' => 'Copia simple de la Cédula de Identidad de la Cónyuge del o la Titular.'],
            ['name' => 'Certificado de estado civil del o los derechohabientes fallecidos habiendo cumplido la mayoría de edad en original y actualizado, emitido por el SERECI.'],
            ['name' => 'Certificado de matrimonio original y actualizado del o los derechohabientes fallecidos, emitido por el SERECI.'],
            ['name' => 'Certificado de descendencia del o los derechohabientes fallecidos habiendo cumplido la mayoría de edad en original y actualizado, emitido por el SERECI.'],
            ['name' => 'Certificado de defunción de la o del Cónyuge del Titular en original y actualizado, emitido por el SERECI.'],
            ['name' => 'Certificado de descendencia vigente del (los) progenitor (es) en original, emitido por el SERECI.'],
        ];
        foreach($procedure_documents as $procedure_document) {
            $model = ProcedureDocument::firstOrCreate($procedure_document);
            // Beneficio de Pago de Fondo de Retiro Policial Solidario
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 4,  // Fallecimiento
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
        }

        $procedure_documents = [
            ['name' => 'Certificado de defunción del (los) progenitor(es) del Titular en original, emitido por el SERECI.'],
        ];
        foreach($procedure_documents as $procedure_document) {
            $model = ProcedureDocument::firstOrCreate($procedure_document);
            // Beneficio de Pago de Fondo de Retiro Policial Solidario
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 3,  // Jubilación
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
        }

        $procedure_documents = [
            ['name' => 'Informe determinando la priorización al proceso del trámite porque el o la Titular adolece de una enfermedad grave y/o terminal, emitido por el Área de Trabajo Social de la MUSERPOL.'],
            ['name' => 'Informe determinando la priorización al proceso del trámite porque el o la Titular o sus familiares de primer grado están pasando una situación de extrema necesidad, emitido por el Área de Trabajo Social de la MUSERPOL.'],
        ];
        foreach($procedure_documents as $procedure_document) {
            $model = ProcedureDocument::firstOrCreate($procedure_document);
            // Beneficio de Pago de Fondo de Retiro Policial Solidario
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 5, // Retiro forzoso
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 7, // Retiro voluntario
                'procedure_document_id' => $model->id,
                'number' => 0
            ]);
        }
        $procedure_documents = [
            ['name' => 'Calificación de Años de Servicio desglosado en copia legalizada y actualizado, emitido por el Comando General de la Policía Boliviana.'],
            ['name' => 'Calificación de Años de Servicio desglosado en original y actualizado, emitido por el Comando General de la Policía Boliviana.'],
        ];
        foreach($procedure_documents as $procedure_document) {
            $model = ProcedureDocument::firstOrCreate($procedure_document);
            // Devolución de aportes
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 62, // Titular
                'procedure_document_id' => $model->id,
                'number' => 4
            ]);
            ProcedureRequirement::firstOrCreate([
                'procedure_modality_id' => 63, // Fallecimiento
                'procedure_document_id' => $model->id,
                'number' => 7
            ]);
        }
    }
}
