<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\ProcedureDocument;
use Muserpol\Models\ProcedureRequirement;


class ProcedureDevPaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedure_types = [
            ['module_id' => '3', 'name' => 'Devolución de Aportes','second_name' => 'Devolución de Aportes']
        ];
        foreach ($procedure_types as $procedure_type) {
            ProcedureType::firstOrCreate($procedure_type);
        }

        $procedure_modalities = [
            ['procedure_type_id' => '21', 'name' => 'Titular', 'shortened' => 'DA - TIT', 'is_valid' => true],
            ['procedure_type_id' => '21', 'name' => 'Fallecimiento', 'shortened' => 'DA - FALL', 'is_valid' => true],
        ];
        foreach ($procedure_modalities as $procedure_modality) {
            ProcedureModality::firstOrCreate($procedure_modality);
        }

        $procedure_documents = [
            ['name' => 'Comprobante de depósito o de transferencia por concepto de adquisición de fólder y formularios en la cuenta fiscal de la MUSERPOL.'],
            ['name' => 'Formulario de verificación de requisitos con carácter de Declaración Jurada y solicitud a ser otorgado por la MUSERPOL al momento de iniciar el trámite.'],
            ['name' => 'Fotocopia simple de la Cédula de Identidad del (la) titular, vigente a la fecha de solicitud.'],
            ['name' => 'Certificado de años de servicio desglosado en original, otorgado por el Comando General de la Policía Boliviana.'],
            ['name' => 'Certificado de años de servicio desglosado en copia legalizada, otorgado por el Comando General de la Policía Boliviana.'],
            ['name' => 'Boletas de pago en original.'],

            ['name' => 'Comprobante de depósito o de transferencia por concepto de adquisición de fólder y formularios en la cuenta fiscal de la MUSERPOL.'],
            ['name' => 'Formulario de verificación de requisitos con carácter de Declaración Jurada y solicitud a ser otorgado por la MUSERPOL al momento de iniciar el trámite.'],
            ['name' => 'Fotocopia simple de la Cédula de Identidad del (la) titular, vigente a la fecha de solicitud.'],
            ['name' => 'Certificado de defunción del titular en original y actualizado.'],
            ['name' => 'Fotocopia simple y vigente de la Cédula de Identidad de los derechohabientes, vigente a la fecha de solicitud.'],
            ['name' => 'Aceptación de Herencia en original emitida por la autoridad competente.'],
            ['name' => 'Aceptación de Herencia en copia legalizada emitida por la autoridad competente.'],
            ['name' => 'Declaratoria de Herederos en original emitida por la autoridad competente.'],
            ['name' => 'Declaratoria de Herederos en copia legalizada emitida por la autoridad competente.'],
            ['name' => 'Certificado de años de servicio desglosado, en original otorgado por el Comando General de la Policía Boliviana.'],
            ['name' => 'Certificado de años de servicio desglosado, en copia legalizada otorgado por el Comando General de la Policía Boliviana.'],
            ['name' => 'Boletas de pago en original.'],
        ];
        foreach ($procedure_documents as $procedure_document) {
            ProcedureDocument::firstOrCreate($procedure_document);
        }
        
        $procedure_requirements = [
            ['procedure_modality_id' => '62', 'procedure_document_id' => '326', 'number' => '1'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '327', 'number' => '2'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '328', 'number' => '3'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '329', 'number' => '4'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '330', 'number' => '4'],
            ['procedure_modality_id' => '62', 'procedure_document_id' => '331', 'number' => '4'],

            ['procedure_modality_id' => '63', 'procedure_document_id' => '332', 'number' => '1'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '333', 'number' => '2'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '334', 'number' => '3'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '335', 'number' => '4'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '336', 'number' => '5'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '337', 'number' => '6'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '338', 'number' => '6'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '339', 'number' => '6'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '340', 'number' => '6'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '341', 'number' => '7'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '342', 'number' => '7'],
            ['procedure_modality_id' => '63', 'procedure_document_id' => '343', 'number' => '7'],
        ];
        foreach ($procedure_requirements as $procedure_requirement) {
            ProcedureRequirement::firstOrCreate($procedure_requirement);
        }
    }
}
