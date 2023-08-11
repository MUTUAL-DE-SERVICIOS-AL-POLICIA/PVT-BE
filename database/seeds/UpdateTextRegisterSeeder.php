<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureDocument;

class UpdateTextRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedure_document = ProcedureDocument::find(18);
        $procedure_document->name = 'Certificado de Haberes en original, considerando los últimos sesenta (60) meses antes del fallecimiento del Titular, emitido por el Comando General de la Policía Boliviana.';
        if($procedure_document->isDirty()) $procedure_document->save();

        $procedure_document = ProcedureDocument::find(355);
        $procedure_document->name = 'Informe determinando la recepción del trámite porque los derechohabientes del titular adolecen de una enfermedad grave y/o terminal, emitido por el Área de Trabajo Social de la MUSERPOL.';
        if($procedure_document->isDirty()) $procedure_document->save();
    }
}
