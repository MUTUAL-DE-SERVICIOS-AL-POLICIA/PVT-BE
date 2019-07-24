<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\DocumentType;

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
DB::table('document_type')->insert([
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
['name' => 'Compromiso de devolución de pagos en defecto en original.','issued'=>'Autoridad Competente'],
['name' => 'Compromiso de pago','issued'=>'Autoridad Competente'],
['name' => 'Contrato de declaración de pensión','issued'=>'SERECI'],
['name' => 'Contrato en copia simple de la AFP o Entidad Aseguradora.','issued'=>'SISTEMA INTEGRAL DE PENSIONES'],
['name' => 'Cédula de Identidad del (la) cónyuge.','issued'=>'SEGIP'],
['name' => 'Cédula de Identidad del (la) titular.','issued'=>'SEGIP'],
['name' => 'Cédula de Identidad del (la) viudo(a).','issued'=>'SEGIP'],
['name' => 'Cédula de Identidad del (los) progenitor(es).','issued'=>'SEGIP'],
['name' => 'Cédula de Identidad del huerfano absoluto.','issued'=>'SEGIP'],
['name' => 'Cédula de Identidad vigente del (la) legatario(a).','issued'=>'SEGIP'],
['name' => 'Cédula de Identidad vigente del (la) titular.','issued'=>'SEGIP'],
['name' => 'Cédula de Identidad vigente del (los) derechohabiente(s).','issued'=>'SEGIP'],
['name' => 'Declaración jurada de no haber contraido nuevas nupcias en original.','issued'=>'Autoridad Competente'],
['name' => 'Declaración jurada notarial poniendo en conocimiento el fallecimiento del (los) derechohabiente (s) del titular','issued'=>'Autoridad Competente'],
['name' => 'Declaración jurada notarial poniendo en conocimiento el motivo de baja de la institución policial del (la) titular','issued'=>'Autoridad Competente'],
['name' => 'Declaración jurada notarial poniendo en conocimiento la descendencia del (la) titular actualizado.','issued'=>'Autoridad Competente'],
['name' => 'Declaratoria de Herederos del (la) hermano (a) ante el fallecimiento del (la) titular','issued'=>'Autoridad Competente'],
['name' => 'Declaratoria de Herederos del (la) progenitor (a) ante el fallecimiento del (la) titular','issued'=>'Autoridad Competente'],
['name' => 'Declaratoria de herederos','issued'=>'Autoridad Competente'],
['name' => 'Desglose de renta o pensión en caso de existir dos o mas huérfanos absolutos','issued'=>'Autoridad Competente'],
['name' => 'Dictamen con la calificación de al menos 70% de pérdida de capacidad laboral','issued'=>'entidad encargada de calificar'],
['name' => 'Documentación de acreditación de condición de estudios','issued'=>'Autoridad Competenter'],
['name' => 'Documentación de acreditación de estado de discapacidad','issued'=>'Autoridad Competente'],
['name' => 'Documento privado de Cesión de Derechos de cuotas parte incluyendo el correspondiente Reconocimiento de Firmas y Rubricas','issued'=>'Autoridad Competente'],
['name' => 'Formulario de verificación de requisitos con carácter de Declaración Jurada y solicitud a ser otorgado por la MUSERPOL a momento de inicio del trámite','issued'=>'Autoridad Competente'],
['name' => 'Informe determinando la recepción del trámite porque él (la) titular adolece de una enfermedad grave y/o terminal','issued'=>'Trabajo Social MUSERPOL'],
['name' => 'Informe determinando la recepción del trámite porque él (la) titular o sus familiares de primer grado están pasando una situación de extrema necesidad','issued'=>'Trabajo Social MUSERPOL'],
['name' => 'Informe estableciendo la reincorporación del (la) titular en original','issued'=>'Comando General de la Policía Boliviana'],
['name' => 'Informe rectificando el grado del (la) titular','issued'=>'Comando General de la Policía Boliviana.'],
['name' => 'Informe rectificando los datos personales del (la) Titular','issued'=>'Comando General de la Policía Boliviana.'],
['name' => 'Memorándum de agradecimiento de servicios','issued'=>'Comando General de la Policía Boliviana.'],
['name' => 'Memorándum de baja definitiva a solicitud voluntaria','issued'=>'Comando General de la Policía Boliviana.'],
['name' => 'Memorándum de designación.','issued'=>'Comando General de la Policía Boliviana.'],
['name' => 'Memorándum de destino a disponibilidad a la<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\DocumentType;
 letra "A" en copia legalizada.','issued'=>'Autoridad Competente'],
['name' => 'Memorándum de destino a disponibilidad a la letra "C" en copia legalizada.','issued'=>'Autoridad Competente'],
['name' => 'Memorándum de destino a disponibilidad a las letras "C" y "A"','issued'=>'Autoridad Competente'],
['name' => 'Memorándum de reincorporación','issued'=>'Comando General de la Policía Boliviana'],
['name' => 'Memorándum de suspensión de funciones y haberes.','issued'=>'Autoridad Competente'],
['name' => 'Memorándum de destino a disponibilidad a las letras "C"','issued'=>'Autoridad Competente'],
['name' => 'Nota dirigida al Director General Ejecutivo de la MUSERPOL, solicitando el registro en el sistema para efectivizar aportes directos','issued'=>'Autoridad Competente'],
['name' => 'Poder conferido por el (la) titular ','issued'=>'autoridad competente'],
['name' => 'Poder conferido por el (los) derechohabiente(s) en copia legalizada','issued'=>'autoridad competente'],
['name' => 'Poder del extranjero visado por cancillería y transcrito en escritura pública','issued'=>'autoridad competente'],
['name' => 'Renuncia de Herencia en copia legalizada','issued'=>'autoridad competente.'],
['name' => 'Resolución de baja definitiva a solicitud voluntaria','issued'=>'Comando General de la Policía Boliviana'],
['name' => 'Resolución de baja definitiva','issued'=>'Comando General de la Policía Boliviana.'],
['name' => 'Resolución de designación','issued'=>'Comando General de la Policía Boliviana.'],
['name' => 'Resolución de otorgación de renta de vejez o invalidez ','issued'=>'autoridad competente'],
['name' => 'Resolución de ratificación de datos personales del (la) titular','issued'=>'SERECI'],
['name' => 'Resolución de reconocimiento de matrimonio de hecho','issued'=>'Autoridad Competente'],
['name' => 'Resolución de suspensión de funciones y haberes','issued'=>'Autoridad Competente'],
['name' => 'Resolución del SENASIR de renta de vejez en copia simple.','issued'=>'SENASIR'],
['name' => 'Resolución sobre adhesión del alfanúmero a la Cédula de Identidad del (la) titular.','issued'=>'SEGIP'],
['name' => 'Resolución sobre adhesión del alfanúmero a la Cédula de Identidad del (los) derechohabiente (s).','issued'=>'SEGIP'],
['name' => 'Resolución sobre asignación de nuevo número de Cédula de Identidad del (la) titula.','issued'=>'SEGIP'],
['name' => 'Resolución sobre asignación de nuevo número de Cédula de Identidad del (los) derechohabiente(s).','issued'=>'SEGIP'],
['name' => 'Testamento abierto dentro del cual se halle inscrito el legado con carga para la otorgación del beneficio','issued'=>'Autoridad Competente'],
['name' => 'Testamento que señale expresamente la otorgación del beneficiO.','issued'=>'Autoridad Competent'],
['name' => 'Testimonio de Declaratoria de Tutoría','issued'=>'Autoridad Competente'],
['name' => 'Testimonio de apertura de sobre lacrado dejado por el (la) titular.','issued'=>'autoridad competente'],

]);
}
}