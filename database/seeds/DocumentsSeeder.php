<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Document;

class DocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $documents =[
            ['name' => 'Aceptación de Herencia del (la) hermano(a) ante el fallecimiento del (la) titular.','issued'=>'autoridad competente'],
            ['name' => 'Aceptación de Herencia del (la) progenitor(a) ante el fallecimiento del (la) titular.','issued'=>'autoridad competente'],
            ['name' => 'Aceptación de Herencia.','issued'=>'autoridad competente'],
            ['name' => 'Apostilla','issued'=>'autoridad competente'],
            ['name' => 'Boleta de pago.','issued'=>'autoridad competente'],
            ['name' => 'Boleta de prestación de invalidez','issued'=>'autoridad competente'],
            ['name' => 'Boleta de prestación de jubilación para habilitación del semestre','issued'=>'autoridad competente'],
            ['name' => 'Certificación de baja definitiva a solicitud voluntaria','issued'=>'autoridad competente'],
            ['name' => 'Certificación de baja definitiva','issued'=>'autoridad competente'],
            ['name' => 'Certificación de salud.','issued'=>'Dirección Nacional de Salud y Bienestar Social de la Policía Boliviana'],
            ['name' => 'Certificación que indique el importe del (los) componentes suspendidos en la pensión de jubilación','issued'=>' AFP o Entidad Aseguradora'],
            ['name' => 'Certificación rectificando el grado del (la) titular.','issued'=>'Comando General de la Policía Boliviana'],
            ['name' => 'Certificación rectificando los datos personales del (la) Titular.','issued'=>'Comando General de la Policía Boliviana'],
            ['name' => 'Certificación sobre la verificación de partidas de matrimonio existentes del (la) titular.','issued'=>'SERECI'],
            ['name' => 'Certificado de Bautismo del o los derechohabientes.','issued'=>'Entidad Eclesiástica correspondiente'],
            ['name' => 'Certificado de Bautismo del titular fallecido.','issued'=>'Eclesiástica correspondiente'],
            ['name' => 'Certificado de Matrimonio Religioso del (la) titular.','issued'=>'entidad Eclesiástica correspondiente'],
            ['name' => 'Certificado de Matrimonio Religioso del o los derechohabientes.','issued'=>'entidad Eclesiástica correspondiente'],
            ['name' => 'Certificado de años de servicio desglosado.','issued'=>'Comando General de la Policía Boliviana'],
            ['name' => 'Certificado de compensación de cotizaciones. ','issued'=>'AFP o Entidad Aseguradora'],
            ['name' => 'Certificado de defunción del (la) cónyuge en original y actualizado.','issued'=>'autoridad competente'],
            ['name' => 'Certificado de defunción del (la) legatario(a) en original y actualizado.','issued'=>'autoridad competente'],
            ['name' => 'Certificado de defunción del (la) viudo(a) en original y actualizado.','issued'=>'autoridad competente'],
            ['name' => 'Certificado de defunción del (los) progenitor(es).','issued'=>'SERECI'],
            ['name' => 'Certificado de descendencia del o los derechohabientes antes de su fallecimiento.','issued'=>'SERECI'],
            ['name' => 'Certificado de descendencia del titular fallecido','issued'=>'SERECI'],
            ['name' => 'Certificado de estado civil del o los derechohabientes antes de su fallecimiento','issued'=>'SERECI'],
            ['name' => 'Certificado de estado civil','issued'=>'SERECI'],
            ['name' => 'Certificado de haberes considerando los últimos 60 meses percibidos y antes de su desvinculación laboral','issued'=>'Comando General de la Policía Boliviana'],
            ['name' => 'Certificado de nacimiento del (la) cónyuge','issued'=>'autoridad competente'],
            ['name' => 'Certificado de no ingreso a disponibilidad de las letras “C” y “A”','issued'=>'Comando General de la Policía Boliviana'],
            ['name' => 'Certificado de rentista o jubilado','issued'=>'autoridad competente'],
            ['name' => 'Certificado de trabajo','issued'=>'Comando General de la Policía Boliviana'],
            ['name' => 'Certificado de trabajo estableciendo la reincorporacion del (la) titular','issued'=>'Comando General de la Policía Boliviana'],
            ['name' => 'Certificado de unión libre o de hecho','issued'=>'SERECI'],
            ['name' => 'Certificado de óbito sobre el registro de defunción en o los cementerios del (la) titular fallecido(a).','issued'=>'Municipio de Provincia o la Autoridad Originaria Campesina'],
            ['name' => 'Certificado de óbito sobre el registro de defunción en o los cementerios del (los) derechohabiente(s).','issued'=>'Municipio o la Autoridad Originaria Campesina'],
            ['name' => 'Certificado de óbito sobre el registro de defunción en o los cementerios del (los) progenitor (es) del titular del fallecido.','issued'=>'Municipio de Provincia o la Autoridad Originaria Campesina'],
            ['name' => 'Certificado sobre cancelación de partida de defunción del (la) titular','issued'=>'SERECI'],
            ['name' => 'Certificado sobre cancelación de partida de defunción del (los) derechohabiente(s)','issued'=>'SERECI'],
            ['name' => 'Certificado sobre cancelación de partida de matrimonio del (la) titular.','issued'=>'SERECI'],
            ['name' => 'Certificado sobre cancelación de partida de matrimonio del (los) derechohabiente(s) SERECI.','issued'=>'SERECI'],
            ['name' => 'Certificado sobre cancelación de partida de nacimiento del (la) titular','issued'=>'SERECI'],
            ['name' => 'Certificado sobre la corrección de partida de matrimonio del (la) titular.','issued'=>'SERECI'],
            ['name' => 'Certificado sobre la corrección de partida de nacimiento del (la) titular','issued'=>'SERECI'],
            ['name' => 'Certificado sobre la inexistencia de partida de defunción del (la) titular','issued'=>'SERECI'],
            ['name' => 'Certificado sobre la inexistencia de partida de defunción del (los) derechohabiente(s) SERECI.','issued'=>'SERECI'],
            ['name' => 'Certificado sobre la inexistencia de partida de matrimonio del (la) titular','issued'=>'SERECI'],
            ['name' => 'Certificado sobre la inexistencia de partida de matrimonio del (los) derechohabiente(s)','issued'=>'SERECI'],
            ['name' => 'Certificado sobre la inexistencia de partida de nacimiento del (la) titular','issued'=>'SERECI'],
            ['name' => 'Certificado sobre la inexistencia de partida de nacimiento del (los) derechohabiente(s)','issued'=>'SERECI'],
            ['name' => 'Certificado sobre rectificación de partida de defunción del (la) titular','issued'=>'SERECI'],
            ['name' => 'Certificado sobre rectificación de partida de matrimonio del (la) titular','issued'=>'SERECI'],
            ['name' => 'Certificado sobre rectificación de partida de matrimonio del (los) derechohabiente(s)','issued'=>'SERECI'],
            ['name' => 'Certificado sobre rectificación de partida de nacimiento del (la) titular','issued'=>'SERECI'],
            ['name' => 'Certificado sobre rectificación de partida de nacimiento del (los) derechohabiente(s)','issued'=>'SERECI'],
            ['name' => 'Certificado sobre reposición de partida de defunción del (la) titular fallecido','issued'=>'SERECI'],
            ['name' => 'Certificado sobre reposición de partida de defunción del (los) derechohabiente(s)','issued'=>'SERECI'],
            ['name' => 'Certificado sobre reposición de partida de matrimonio del (la) titular','issued'=>'SERECI'],
            ['name' => 'Certificado sobre reposición de partida de matrimonio del (los) derechohabiente(s)','issued'=>'SERECI'],
            ['name' => 'Certificado sobre reposición de partida de nacimiento del (la)','issued'=>'SERECI'],
            ['name' => 'Certificado sobre reposición de partida de nacimiento del (los) derechohabiente(s)','issued'=>'SERECI'],
            ['name' => 'Comprobante de depósito bancario de Bs.- 15,00 por concepto de adquisición de folder y formularios, en la cuenta fiscal de la MUSERPOL.','issued'=>'MUSERPOL'],
            ['name' => 'Comprobante de depósito bancario de Bs.- 25,00 por concepto de adquisición de folder y formularios, en la cuenta fiscal de la MUSERPOL.','issued'=>'MUSERPOL'],
            ['name' => 'Compromiso de devolución de pagos en defecto en original.','issued'=>''],
            ['name' => 'Compromiso de pago','issued'=>''],
            ['name' => 'Contrato de declaración de pensión','issued'=>'SERECI'],
            ['name' => 'Contrato en copia simple de la AFP o Entidad Aseguradora.','issued'=>'SISTEMA INTEGRAL DE PENSIONES'],
            ['name' => 'Cédula de Identidad del (la) cónyuge en copia simple.','issued'=>''],
            ['name' => 'Cédula de Identidad del (la) titular en copia simple.','issued'=>''],
            ['name' => 'Cédula de Identidad del (la) viudo(a) en copia simple.','issued'=>''],
            ['name' => 'Cédula de Identidad del (los) progenitor(es) en copia simple.','issued'=>''],
            ['name' => 'Cédula de Identidad del huerfano absoluto en copia simple.','issued'=>''],
            ['name' => 'Cédula de Identidad vigente del (la) legatario(a) copia simple.','issued'=>''],
            ['name' => 'Cédula de Identidad vigente del (la) titular copia simple.'=>''],
            ['name' => 'Cédula de Identidad vigente del (los) derechohabiente(s) en copia simple.','issued'=>''],
            ['name' => 'Declaración jurada de no haber contraido nuevas nupcias en original.','issued'=>''],
            ['name' => 'Compromiso de pago','issued'=>'SERECI'],
            ['name' => 'Compromiso de pago','issued'=>'SERECI'],
            ['name' => 'Compromiso de pago','issued'=>'SERECI'],



        ];
        
        Document::insert($documents);
    }
}
