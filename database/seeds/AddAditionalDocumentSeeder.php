<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureDocument;

class AddAditionalDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $current_sequence = DB::select("SELECT nextval(pg_get_serial_sequence('procedure_documents','id'))")[0]->nextval;
        DB::statement(DB::raw("ALTER SEQUENCE procedure_documents_id_seq RESTART WITH $current_sequence"));
        $procedure_documents = [
            ['name' => 'Certificado de estado civil del o los derechohabientes fallecidos, habiendo cumplido la mayoría de edad en original y actualizado, emitido por el SERECI.'],
            ['name' => 'Certificado de matrimonio original y actualizado del o los derechohabientes fallecidos, emitido por el SERECI.'],
            ['name' => 'Certificado de descendencia del o los derechohabientes fallecidos, habiendo cumplido la mayoría de edad en original y actualizado, emitido por el SERECI.'],
            ['name' => 'Copia simple del Certificado de Nacimiento del Titular, emitido por el SERECI.'],
            ['name' => 'Certificado de defunción de la cónyuge del Titular en original y actualizado, emitido por el SERECI.'],
            ['name' => 'Copia simple de la Cédula de Identidad de la Cónyuge del Titular.'],
            ['name' => 'Informe incluyendo el grado del (la) Titular en original, emitido por el Comando General de la Policía Boliviana.'],
            ['name' => 'Memorándum de Baja Definitiva en original, emitida por el Comando General de la Policía Boliviana.'],
            ['name' => 'Memorándum de Baja Definitiva en copia legalizada, emitida por el Comando General de la Policía Boliviana.'],
            ['name' => 'Memorándum de Baja Definitiva en original, emitida por el Comando Departamental de la Policía Boliviana.'],
            ['name' => 'Memorándum de Baja Definitiva en copia legalizada, emitida por el Comando Departamental de la Policía Boliviana.'],
            ['name' => 'Resolución sobre la modificación en el número de la Cédula de Identidad del Titular en copia legalizada, emitido por el SEGIP.'],
            ['name' => 'Resolución sobre la modificación en el número de la Cédula de Identidad del Titular en original, emitido por el SEGIP.'],
        ];

        foreach($procedure_documents as $procedure_document) {
            ProcedureDocument::firstOrCreate($procedure_document);
        }
    }
}
