<?php

use Illuminate\Database\Seeder;

class AditionalDocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('procedure_documents')->insert([
            ['name' => 'Resolución sobre la adhesión del alfanúmero a la Cedula de Identidad del titular emitido por el SEGIP (original).'],
            ['name' => 'Resolución sobre la adhesión del alfanúmero a la Cedula de Identidad del titular emitido por el SEGIP (fotocopia legalizada).'],
            ['name' => 'Resolución sobre la asignación de un nuevo número de Cedula de Identidad del titular emitido por el SEGIP (original).'],
            ['name' => 'Resolución sobre la asignación de un nuevo número de Cedula de Identidad del titular emitido por el SEGIP (fotocopia legalizada).'],
            ['name' => 'Certificado estableciendo la existencia o no de la Cedula de Identidad del titular emitido por el SEGIP o la Tarjeta Prontuario del titular (original y actualizado).'],
            ['name' => 'Certificado estableciendo la existencia o no de la Tarjeta Prontuario del titular emitido por el SEGIP (original y actualizado).'],
            ['name' => 'Poder del extranjero visado por cancillería y transcrito en Escritura Pública emitido por Notaria de Fe Publica (original y actualizado).'],
            ['name' => 'Certificado sobre reposición de partida de nacimiento del titular emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre reposición de partida de matrimonio del titular emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre la inexistencia de partida de nacimiento del titular emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre la inexistencia de partida de matrimonio del titular emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado de Bautismo del titular emitido por entidad Eclesiástica correspondiente (original y actualizado).'],
            ['name' => 'Certificado de Matrimonio Religioso del titular emitido por entidad Eclesiástica correspondiente (original y actualizado).'],
            ['name' => 'Certificado sobre la cancelación de partida de nacimiento del titular emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre la cancelación de partida de matrimonio del titular emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre la corrección de partida de nacimiento del titular emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre la corrección de partida de matrimonio del titular emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificación sobre la verificación de partidas de matrimonio existentes del titular fallecido emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado de óbito sobre el registro de defunción en o los cementerios del o los derechohabientes emitido por el Municipio de Provincia o la Autoridad Originaria Campesina (original y actualizado).'],
            ['name' => 'Certificado de óbito sobre el registro de defunción en o los cementerios del o los progenitores del titular fallecido emitido por el Municipio de Provincia o la Autoridad Originaria Campesina (original y actualizado).'],
            ['name' => 'Certificado de óbito sobre el registro de defunción en o los cementerios del titular fallecido emitido por el Municipio de Provincia o la Autoridad Originaria Campesina (original y actualizado).'],
            ['name' => 'Certificado de estado civil del o los derechohabientes antes de su fallecimiento emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado de descendencia del o los derechohabientes antes de su fallecimiento emitido por el SERECI (original y actualizado).'],
            ['name' => 'Declaración jurada voluntaria, poniendo en conocimiento la existencia o no del o los derechohabientes del titular fallecido emitida ante Notario de Fe Publica (original y actualizado).'],
            ['name' => 'Declaración jurada voluntaria, poniendo en conocimiento el fallecimiento del o los derechohabientes del titular fallecido emitida ante Notario de Fe Publica (original y  actualizado).'],
            ['name' => 'Sobre lacrado dejado por el titular fallecido, mismo que debe estar aperturado por el o los derechohabientes ante autoridad competente para tal fin.'],
            ['name' => 'Certificado de la existencia o no de Cedula de Identidad del titular fallecido emitido por el SEGIP (original y actualizado).'],
            ['name' => 'Certificado de la existencia o no de la Tarjeta Prontuario del titular fallecido emitido por el SEGIP (original y actualizado).'],
            ['name' => 'Resolución sobre la adhesión del alfanúmero a la cedula de identidad del titular fallecido emitido por le SEGIP (original).'],
            ['name' => 'Resolución sobre la adhesión del alfanúmero a la cedula de identidad del titular fallecido emitido por le SEGIP (fotocopia legalizada).'],
            ['name' => 'Resolución sobre la adhesión del alfanúmero a la cedula de identidad del o los derechohabientes emitido por el SEGIP (original).'],
            ['name' => 'Resolución sobre la adhesión del alfanúmero a la cedula de identidad del o los derechohabientes emitido por el SEGIP (fotocopia legalizada).'],
            ['name' => 'Certificado de defunción de los progenitores del titular fallecido emitido por el SERECI (original y actualizado).'],
            ['name' => 'Poder del extranjero visado por cancillería y transcrito en escritura pública emitido por Notaria de Fe Publica (original).'],
            ['name' => 'Poder del extranjero visado por cancillería y transcrito en escritura pública emitido por Notaria de Fe Publica (copia legalizada).'],
            ['name' => 'Poder conferido a uno de los derechohabientes para realizar la declaratoria de herederos o Aceptación de Herencia emitido por Notaria de Fe Publica (original).'],
            ['name' => 'Poder conferido a uno de los derechohabientes para realizar la declaratoria de herederos o Aceptación de Herencia emitido por Notaria de Fe Publica (copia legalizada).'],
            ['name' => 'Documento privado de Cesión de Derechos de cuotas partes del beneficio de Fondo de Retiro Policial Solidario por Fallecimiento, incluyendo el correspondiente Reconocimiento de Firmas y Rubricas, todo debidamente notariado (original).'],
            ['name' => 'Documento privado de Cesión de Derechos de cuotas partes del beneficio de Fondo de Retiro Policial Solidario por Fallecimiento, incluyendo el correspondiente Reconocimiento de Firmas y Rubricas, todo debidamente notariado (fotocopia legalizada).'],
            ['name' => 'Escritura Pública de Renuncia de Herencia realizado ante Notario de Fe Publica (original ).'],
            ['name' => 'Escritura Pública de Renuncia de Herencia realizado ante Notario de Fe Publica (fotocopia legalizada).'],
            ['name' => 'Testimonio de Renuncia de Herencia realizado ante autoridad jurisdiccional competente (original ).'],
            ['name' => 'Testimonio de Renuncia de Herencia realizado ante autoridad jurisdiccional competente (fotocopia legalizada).'],
            ['name' => 'Certificado sobre reposición de partida de defunción del titular fallecido emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre cancelación de partida de defunción del titular fallecido emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre rectificación de partida de defunción del titular fallecido emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre reposición de partida de nacimiento del o los derechohabientes emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre cancelación de partida de nacimiento del o los derechohabientes emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre rectificación de partida de nacimiento del o los derechohabientes emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre reposición de partida de matrimonio del o los derechohabientes emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre cancelación de partida de matrimonio del o los derechohabientes emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre rectificación de partida de matrimonio del o los derechohabientes emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre reposición de partida de defunción del o los derechohabientes emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre cancelación de partida de defunción del o los derechohabientes emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre rectificación de partida de defunción del o los derechohabientes emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre reposición de partida de matrimonio del titular fallecido emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre cancelación de partida de matrimonio del titular fallecido emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre rectificación de partida de matrimonio del titular fallecido emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre la inexistencia de partida de nacimiento del titular fallecido emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre la inexistencia de partida de matrimonio del titular fallecido emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre la inexistencia de partida de defunción del titular fallecido emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre la inexistencia de partida de nacimiento de los derechohabientes emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre la inexistencia de partida de matrimonio de los derechohabientes emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado sobre la inexistencia de partida de defunción de los derechohabientes emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado de Bautismo del titular fallecido emitido por entidad Eclesiástica correspondiente (original y actualizado).'],
            ['name' => 'Certificado de Matrimonio Religioso del titular fallecido emitido por entidad Eclesiástica correspondiente (original y actualizado).'],
            ['name' => 'Certificado de Bautismo del o los derechohabientes emitido por entidad Eclesiástica correspondiente (original y actualizado).'],
            ['name' => 'Certificado de Matrimonio Religioso del o los derechohabientes emitido por entidad Eclesiástica correspondiente (original y actualizado).'],
          ]);
    }
}
