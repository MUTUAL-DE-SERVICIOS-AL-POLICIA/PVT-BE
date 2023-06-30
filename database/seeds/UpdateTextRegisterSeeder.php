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
        $procedure_documnet->name = 'Certificado de Haberes en original, considerando los últimos sesenta (60) meses antes del fallecimiento del Titular, emitido por el Comando General de la Policía Boliviana.';
        $procedure_document->save();
    }
}
