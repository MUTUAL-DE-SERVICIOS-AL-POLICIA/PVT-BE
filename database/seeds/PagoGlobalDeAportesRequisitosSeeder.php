<?php

use Illuminate\Database\Seeder;

class PagoGlobalDeAportesRequisitosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('procedure_documents')->insert([
        	['name' => 'Solicitud escrita dirigida a la Máxima Autoridad Ejecutiva de la MUSERPOL.'],
            ['name' => 'Calificación de años de servicio (C.A.S.) emitido por el Ministerio de Economía y Finanzas Públicas (original).'],
            ['name' => 'Certificado de haberes otorgado por el Comando General de la Policía Boliviana, hasta la fecha de fallecimiento(original).'],
            ['name' => 'Certificado de haberes otorgado por el Comando general de la Policía Boliviana (original).'],
            ['name' => 'Fotocopia simple de la cédula de identidad del titular.'],
            ['name' => 'Certificado de años de servicio desglosado, otorgado por el Comando General de la Policía Boliviana, hasta la fecha de fallecimiento (original).'],
        ]);

        DB::table('procedure_requirements')->insert([
        	['procedure_modality_id' => '1', 'procedure_document_id' => '36', 'number' => '1'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '40', 'number' => '2'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '9', 'number' => '3'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '10', 'number' => '4'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '11', 'number' => '5'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '12', 'number' => '6'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '13', 'number' => '6'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '14', 'number' => '6'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '15', 'number' => '7'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '16', 'number' => '8'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '17', 'number' => '8'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '37', 'number' => '9'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '41', 'number' => '10'],
        	['procedure_modality_id' => '1', 'procedure_document_id' => '38', 'number' => '11'],

        	['procedure_modality_id' => '2', 'procedure_document_id' => '36', 'number' => '1'],
        	['procedure_modality_id' => '2', 'procedure_document_id' => '40', 'number' => '2'],
        	['procedure_modality_id' => '2', 'procedure_document_id' => '22', 'number' => '3'],
        	['procedure_modality_id' => '2', 'procedure_document_id' => '37', 'number' => '4'],
        	['procedure_modality_id' => '2', 'procedure_document_id' => '8', 'number' => '5'],
        	['procedure_modality_id' => '2', 'procedure_document_id' => '39', 'number' => '6'],
        	['procedure_modality_id' => '2', 'procedure_document_id' => '19', 'number' => '7'],
        	['procedure_modality_id' => '2', 'procedure_document_id' => '20', 'number' => '7']
        ]);
    }
}
