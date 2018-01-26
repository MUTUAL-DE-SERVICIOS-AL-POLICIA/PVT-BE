<?php

use Illuminate\Database\Seeder;

class ProcedureTablesSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('procedure_types')->insert([
            ['module_id' => '3', 'name' => 'Pago Global de Aportes'],
            ['module_id' => '3', 'name' => 'Pago de Fondo de Retiro'],
        ]);

        DB::table('procedure_modalities')->insert([
            ['id' => '1', 'procedure_type_id' => '1', 'name' => 'Fallecimiento'],
            ['id' => '2', 'procedure_type_id' => '1', 'name' => 'Retiro forzoso(Invalidez Permanente)'],
            ['id' => '3', 'procedure_type_id' => '2', 'name' => 'Jubilación'],
            ['id' => '4', 'procedure_type_id' => '2', 'name' => 'Fallecimiento'],
            ['id' => '5', 'procedure_type_id' => '2', 'name' => 'Retiro forzoso'],
            ['id' => '6', 'procedure_type_id' => '2', 'name' => 'Retiro forzoso(Invalidez Permanente)'],
            ['id' => '7', 'procedure_typs_id' => '2', 'name' => 'Retiro Voluntario'],
        ]);
        DB::table('procedure_documents')->insert([
            ['id' => '1', 'name' => 'Comprobante de depósito bancario de Bs.- 25 por concepto de adquisición de folder y formularios en la cuenta fiscal de la MUSEROL.'],
            ['id' => '2', 'name' => 'Formualrio de verificación de requisitos con carácter de Declaración Jurada y solicitud a ser otorgado por la MUSERPOL a momento de inicio de trámite.'],
            ['id' => '3', 'name' => 'Fotocopia simple de cédula de identidad del tituar, vigente a la fecha de solicitud.'],
            ['id' => '4', 'name' => 'Certificado de nacimiento del titular (original y actualizado).'],
            ['id' => '5', 'name' => 'Memorándum de agradecimiento de servicios emitido por el Comando general de la Policía Boliviana(original).'],
            ['id' => '6', 'name' => 'Memorándum de destino a disponibilidad a las letras "C" y "A" (reserva activa) según corresponda (original).'],
            ['id' => '7', 'name' => 'Certificado de haberes otorgado por el Comando general de la Policía Boliviana, de los últimos 60 meses efectivamente percibidos antes de su destino a disponibilidad de las letras(reserva activa), (original).'],
            ['id' => '8', 'name' => 'Certificado de años de servicio desglosado, otorgado por el Comando General de la Policía Boliviana (original).'],
            ['id' => '9', 'name' => 'Certificado de defunción (original y actualizado).'],
            ['id' => '10', 'name' => 'Certificado de nacimiento de lso derechohabientes (original y actualizado).'],
            ['id' => '11', 'name' => 'Fotocopia simple de la cédula de identidad de los derechohabientes(vigente).'],
            ['id' => '12', 'name' => 'Certificado de Matrimonio(original y actualizado).'],
            ['id' => '13', 'name' => 'Certificado de unión libre o de hecho emitido por el "SERECI" (original y actualizado).'],
            ['id' => '14', 'name' => 'Resolución de reconocimiento de matrimonio de hecho ante autoridad competente (orignal o copia legalizada).'],
            ['id' => '15', 'name' => 'Certificado de descendencia de titutar fallecido emitido por el SERECI (original y actualizado).'],
            ['id' => '16', 'name' => 'Declaratoria de herederos (original o copia legalizada),'],
            ['id' => '17', 'name' => 'Testamento que señale expresamente la otorgación del beneficio (original o copia legalizada).'],
            ['id' => '18', 'name' => 'Certificado de haberes otorgado por el Comando General de la Policía Boliviana, de los últimos 60 meses efectivamente percibidos, previo a suscitarse a fallecimiento del titular (original).'],
            ['id' => '19', 'name' => 'Resolución de baja definitiva emitida por el Comando General de la Policía Boliviana (original).'],
            ['id' => '20', 'name' => 'Memorándum de baja definitiva emitida por el Comando General de la Policía Boliviana (original).'],
            ['id' => '21', 'name' => 'Certificado de haberes otorgado por el comando general de la Policía Boliviana de los últimos 60 mese efectivamente percibidos, antes de su desvinculación laboral con la Institucivn Policial (original).'],
            ['id' => '22', 'name' => 'Dictamen con calificación de al menos 70% de pérdida de capacidad laboral, emitida por la Entidad Encargada de Calificar (copia legalizada).'],
            ['id' => '23', 'name' => 'Certificado de trabajo emitido por el Comando General de la Policía Boliviana, que espeficique la fecha de ingreso a la institución Plicial, fecha de destino a disponibilidad, fecha de agradecimiento de servicio o fecha de baja.'],
            ['id' => '24', 'name' => 'Certificado de haberes otorgado por el Comando General de la Policia Boliviana, de los 12 meses, previo a suscitarse el fallecimiento'],
            ['id' => '25', 'name' => 'Aceptación de Herencia original'],
            ['id' => '26', 'name' => 'Aceptación de Herencia copia legalizada'],
            ['id' => '27', 'name' => 'Dictamen emitido por la Entidad Encargada de Calificar'],
            ['id' => '28', 'name' => 'Certificación emitida por la Dirección Nacional de Salud y Bienestar Social de la Policia Boliviana original'],
            ['id' => '29', 'name' => 'Certificación emitida por la Dirección Nacional de Salud y Bienestar Social de la Policia Boliviana copia legalizada'],
            ['id' => '30', 'name' => 'Certificado de nacimiento del cónyugue original y actualizado'],
            ['id' => '31', 'name' => 'Certificado de defunción de la o el cónyugue original y actualizado'],
            ['id' => '32', 'name' => 'Fotocopia simple de la célula de identidad del cónyugue'],
            ['id' => '33', 'name' => 'Certificado de nacimiento del o de la viud@ original y actualizado'],
            ['id' => '34', 'name' => 'Certificado de defunción del o de la viud@ original y actualizado'],
            ['id' => '35', 'name' => 'Fotocopia simple de la célula de identidad del o de la viud@'],
        ]);
        DB::table('procedure_requirements')->insert([
            ['procedure_modality_id' => '3', 'procedure_document_id' => '1', 'number' => '1'],
            ['procedure_modality_id' => '3', 'procedure_document_id' => '2', 'number' => '2'],
            ['procedure_modality_id' => '3', 'procedure_document_id' => '3', 'number' => '3'],
            ['procedure_modality_id' => '3', 'procedure_document_id' => '4', 'number' => '4'],
            ['procedure_modality_id' => '3', 'procedure_document_id' => '5', 'number' => '5'],
            ['procedure_modality_id' => '3', 'procedure_document_id' => '23', 'number' => '5'],
            ['procedure_modality_id' => '3', 'procedure_document_id' => '6', 'number' => '6'],
            ['procedure_modality_id' => '3', 'procedure_document_id' => '23', 'number' => '6'],
            ['procedure_modality_id' => '3', 'procedure_document_id' => '7', 'number' => '7'],
            ['procedure_modality_id' => '3', 'procedure_document_id' => '8', 'number' => '8'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '1', 'number' => '1'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '2', 'number' => '2'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '3', 'number' => '3'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '4', 'number' => '4'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '9', 'number' => '5'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '6', 'number' => '6'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '23', 'number' => '6'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '7', 'number' => '7'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '11', 'number' => '8'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '12', 'number' => '9'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '13', 'number' => '9'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '14', 'number' => '9'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '15', 'number' => '10'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '16', 'number' => '11'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '17', 'number' => '11'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '7', 'number' => '12'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '18', 'number' => '12'],
            ['procedure_modality_id' => '4', 'procedure_document_id' => '8', 'number' => '13'],
            ['procedure_modality_id' => '5', 'procedure_document_id' => '1', 'number' => '1'],
            ['procedure_modality_id' => '5', 'procedure_document_id' => '2', 'number' => '2'],
            ['procedure_modality_id' => '5', 'procedure_document_id' => '3', 'number' => '3'],
            ['procedure_modality_id' => '5', 'procedure_document_id' => '4', 'number' => '4'],
            ['procedure_modality_id' => '5', 'procedure_document_id' => '19', 'number' => '5'],
            ['procedure_modality_id' => '5', 'procedure_document_id' => '20', 'number' => '5'],
            ['procedure_modality_id' => '5', 'procedure_document_id' => '7', 'number' => '6'],
            ['procedure_modality_id' => '5', 'procedure_document_id' => '21', 'number' => '6'],
            ['procedure_modality_id' => '5', 'procedure_document_id' => '8', 'number' => '7'],
            ['procedure_modality_id' => '6', 'procedure_document_id' => '1', 'number' => '1'],
            ['procedure_modality_id' => '6', 'procedure_document_id' => '2', 'number' => '2'],
            ['procedure_modality_id' => '6', 'procedure_document_id' => '3', 'number' => '3'],
            ['procedure_modality_id' => '6', 'procedure_document_id' => '4', 'number' => '4'],
            ['procedure_modality_id' => '6', 'procedure_document_id' => '22', 'number' => '5'],
            ['procedure_modality_id' => '6', 'procedure_document_id' => '19', 'number' => '6'],
            ['procedure_modality_id' => '6', 'procedure_document_id' => '20', 'number' => '6'],
            ['procedure_modality_id' => '6', 'procedure_document_id' => '21', 'number' => '7'],
            ['procedure_modality_id' => '6', 'procedure_document_id' => '7', 'number' => '7'],
            ['procedure_modality_id' => '6', 'procedure_document_id' => '8', 'number' => '8'],
            ['procedure_modality_id' => '7', 'procedure_document_id' => '1', 'number' => '1'],
            ['procedure_modality_id' => '7', 'procedure_document_id' => '2', 'number' => '2'],
            ['procedure_modality_id' => '7', 'procedure_document_id' => '3', 'number' => '3'],
            ['procedure_modality_id' => '7', 'procedure_document_id' => '4', 'number' => '4'],
            ['procedure_modality_id' => '7', 'procedure_document_id' => '19', 'number' => '5'],
            ['procedure_modality_id' => '7', 'procedure_document_id' => '20', 'number' => '5'],
            ['procedure_modality_id' => '7', 'procedure_document_id' => '21', 'number' => '6'],
            ['procedure_modality_id' => '7', 'procedure_document_id' => '7', 'number' => '6'],
            ['procedure_modality_id' => '7', 'procedure_document_id' => '8', 'number' => '7'],
        ]);
    }

}
