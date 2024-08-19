<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureDocument;
use Muserpol\Models\ProcedureRequirement;

class UpdateProcedureDocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->editarDocumento(3,"Fotocopia de la cédula de identidad del (la) titular");
        $this->editarDocumento(7,"Certificado de haberes en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(9,"Certificado de defunción del titular en original, emitido por el SERECI.");
        $this->editarDocumento(11,"Fotocopia de la cédula de identidad de los derechohabientes");
        $this->editarDocumento(12,"Certificado de matrimonio en original y actualizado, emitido por el SERECI.");
        $this->editarDocumento(13,"Certificado de unión libre o de hecho en original y actualizado, emitido por el SERECI.");
        $this->editarDocumento(15,"Certificado de descendencia del titular fallecido en original y actualizado, emitido por el SERECI.");
        $this->editarDocumento(16,"Declaratoria de herederos en original o copia legalizada.");
        $this->editarDocumento(17,"Testamento en original o copia legalizada.");
        $this->editarDocumento(19,"Resolución de baja definitiva en original o copia legalizada, emitida por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(20,"Memorándum de baja definitiva en original o copia legalizada dirigido al titular, emitida por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(25,"Aceptación de herencia en original o copia legalizada.");
        $this->editarDocumento(26,"Dictamen en original o copia legalizada, emitido por la entidad encargada de calificar.");
        $this->editarDocumento(27,"Certificación de salud en original emitida por la Dirección Nacional de Salud y Bienestar Social de la Policía Boliviana.");
        $this->editarDocumento(29,"Certificado de defunción del (la) cónyuge en original, emitido por el SERECI.");
        $this->editarDocumento(30,"Fotocopia de la cédula de identidad del (la) cónyuge");
        $this->editarDocumento(32,"Certificado de defunción del viudo(a) en original, emitido por el SERECI.");
        $this->editarDocumento(33,"Fotocopia de la cédula de identidad del viudo(a)");
        $this->editarDocumento(44,"Certificado de estado civil en original y actualizado, emitido por el SERECI.");
        $this->editarDocumento(45,"Certificación de Baja Definitiva a solicitud voluntaria en original o copia legalizada, emitida por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(67,"Certificado de óbito sobre el registro de defunción en original y actualizado, emitido por el Municipio o la Autoridad Indígena Originaria Campesina.");
        $this->editarDocumento(71,"Declaración voluntaria notarial poniendo en conocimiento el fallecimiento del o (los) derechohabiente (s), emitida por Autoridad Competente.");
        $this->editarDocumento(101,"Resolución de modificación de número de cédula de identidad del (la) titular en original o copia legalizada, emitido por el SEGIP.");
        $this->editarDocumento(115,"Certificado de verificación de partidas matrimoniales del (la) titular en original, emitido por el SERECI.");
        $this->editarDocumento(121,"Declaración voluntaria notarial poniendo en conocimiento el último estado civil del (la) titular en original y actualizado, emitida por Autoridad Competente.");
        $this->editarDocumento(130,"Certificado de defunción del (los) progenitor(es) en original , emitido por el SERECI.");
        $this->editarDocumento(131,"Poder notarial protocolizado en original o copia legalizada, emitido por Autoridad Competente.");
        $this->editarDocumento(135,"Testimonio notarial de cesión de derechos en original o copia legalizada, emitida por Autoridad Competente.");
        $this->editarDocumento(137,"Testimonio notarial de renuncia de herencia en original o copia legalizada, emitida por Autoridad Competente.");
        $this->editarDocumento(166,"Poder notarial en original o copia legalizada, emitido por Autoridad Competente.");
        $this->editarDocumento(179,"Informe de trabajo social de la MUSERPOL.");
        $this->editarDocumento(186,"Documento de aclaración de datos personales en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(187,"Documento de aclaración de grado en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(189,"Certificado de trabajo de reincorporación en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(193,"Contrato de la Gestora Pública, AFP o Entidad Aseguradora en original o copia legalizada.");
        $this->editarDocumento(206,"Memorándum de destino a Disponibilidad a la Letra \"C\" en original o copia legalizada.");
        $this->editarDocumento(208,"Memorándum de destino a Disponibilidad a la Letra \"A\" en original o copia legalizada.");
        $this->editarDocumento(210,"Apostilla en original o copia legalizada, emitido por Autoridad Competente.");
        $this->editarDocumento(218,"Memorándum de reincorporación en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(226,"Comprobante de depósito bancario por adquisición de fólder y formulario de solicitud de pago.");
        $this->editarDocumento(227,"Fotocopia de la cédula de identidad del (los) progenitor(es).");
        $this->editarDocumento(228,"Fotocopia de la cédula de identidad del huérfano absoluto.");
        $this->editarDocumento(229,"Memorándum de agradecimiento por servicios en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(231,"Certificado de calificación de años de servicio en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(237,"Boleta de renta o pensión de Jubilación para la verificación del cumplimiento del semestre, en fotocopia.");
        $this->editarDocumento(238,"Testimonio de declaratoria de tutoría en original o copia legalizada.");
        $this->editarDocumento(239,"Documentación de acreditación de condición de estudios en original o copia legalizada.");
        $this->editarDocumento(240,"Certificado de trabajo en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(234,"Resolución del SENASIR de renta de vejez o invalidez, en original o copia legalizada.");
        $this->editarDocumento(237,"Boleta de renta o pensión de Jubilación para la verificación del cumplimiento del semestre, en fotocopia.");
        $this->editarDocumento(238,"Testimonio de declaratoria de tutoría en original o copia legalizada.");
        $this->editarDocumento(239,"Documentación de acreditación de condición de estudios en original o copia legalizada.");
        $this->editarDocumento(240,"Certificado de trabajo en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(248,"Certificado de compensación de cotizaciones en original o copia legalizada, emitido por la Gestora Pública, AFP o Entidad Aseguradora.");
        $this->editarDocumento(249,"Boleta de pensión de invalidez, en fotocopia.");
        $this->editarDocumento(260,"Certificación de los componentes suspendidos de la pensión en original o copia legalizada, emitido por la Gestora Pública, AFP o Entidad Aseguradora.");
        $this->editarDocumento(263,"Documentación de desglose de renta o pensión en original o copia legalizada.");
        $this->editarDocumento(264,"Documentación de acreditación de estado de discapacidad en original o copia legalizada.");
        $this->editarDocumento(268,"Certificado de la existencia de Cedula de Identidad del (los) progenitor(es) en original y actualizado emitido por el SEGIP.");
        $this->editarDocumento(269,"Boleta de renta o pensión de jubilación para la calificación, en fotocopia.");
        $this->editarDocumento(270,"Resolución del SENASIR de renta de viudedad en original o copia legalizada.");
        $this->editarDocumento(272,"Formulario de Registro de Beneficiario SIGEP.");
        //$this->editDocument(284,"Certificación de aportes para el beneficio del Auxilio Mortuorio.");
        $this->editarDocumento(320,"Compromiso de pago de aportes para el beneficio de Auxilio Mortuorio.");
        $this->editarDocumento(324,"Certificado de destino a la disponibilidad en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(325,"Certificado de no ingreso a la disponibilidad en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(334,"Certificado de ingreso a la Disponibilidad (Letra “C”) en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(335,"Certificado de ingreso a la Disponibilidad (Letra “A”) en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(337,"Certificado de no ingreso a Disponibilidad (Letra “C”) en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(338,"Certificado de no ingreso a Disponibilidad (Letra “A”) en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");
        $this->editarDocumento(341,"Fotocopia de la cédula de identidad del apoderado.");
        $this->editarDocumento(342,"Certificado de Calificación de años de servicio en original o copia legalizada, emitido por el Batallón de Seguridad Física.");
        $this->editarDocumento(343,"Certificado de haberes y aportes en original o copia legalizada, emitido por el Batallón de Seguridad Física.");
        $this->editarDocumento(346,"Certificación de datos en original o copia legalizada, emitido por el SEGIP.");
        $this->editarDocumento(378,"Certificado de estado civil del o los derechohabientes fallecidos habiendo cumplido la mayoría de edad en original y actualizado, emitido por el SERECI.");
        $this->editarDocumento(379,"Certificado de matrimonio de los derechohabientes fallecidos en original y actualizado, emitido por el SERECI.");
        $this->editarDocumento(380,"Certificado de descendencia de los derechohabientes fallecidos en original y actualizado, emitido por el SERECI.");
        $this->editarDocumento(393,"Resolución de modificación de número de cédula de identidad del (la) titular en original o copia legalizada, emitido por el SEGIP.");
        $this->editarDocumento(407,"Certificado de defunción de los derechohabientes en original, emitido por el SERECI.");
        $this->editarDocumento(408,"Resolución de ascenso póstumo en original o copia legalizada, emitido por el Comando General de la Policía Boliviana.");

        $nuevosDocumentos = [
            "Certificación de datos del progenitor en original o copia legalizada, emitido por el SEGIP.",
            "Factura o proforma emitida por la funeraria en original.",
            "Ultima boleta de pago de haberes en el servicio activo en original o copia legalizada, emitida por el Comando General de la Policía Boliviana",
            "Certificado de años de servicio en fotocopia, emitido por el Batallón de Seguridad Física.",
            "Documento de la masa hereditaria en fotocopia, emitido por el Ente Gestor.",
            "Certificado de Deceso en cumplimiento del deber, emitido por el Comando General de la Policía Boliviana.",
            "Certificado de nacimiento del huerfano absoluto en original y actualizado, emitido por el SERECI.",
            "Certificación de otorgación de renta del SENASIR, en original o copia legalizada.",
            "Certificación de aportes para el beneficio del Auxilio Mortuorio."
        ];
        foreach ($nuevosDocumentos as $doc) {
            $temp = ProcedureDocument::where('name', $doc)->first();
            if($temp == null) {
                $procedureDoc = new ProcedureDocument();
                $procedureDoc->name = $doc;
                $procedureDoc->save();
            }
        }

        $certificaciónDeDatos = ProcedureDocument::where('name',"Certificación de datos del progenitor en original o copia legalizada, emitido por el SEGIP.")->first();
        $factura = ProcedureDocument::where('name',"Factura o proforma emitida por la funeraria en original.")->first();
        $boletaDePago = ProcedureDocument::where('name',"Ultima boleta de pago de haberes en el servicio activo en original o copia legalizada, emitida por el Comando General de la Policía Boliviana")->first();
        $añosDeServicio = ProcedureDocument::where('name',"Certificado de años de servicio en fotocopia, emitido por el Batallón de Seguridad Física.")->first();
        $masaHereditaria = ProcedureDocument::where('name',"Documento de la masa hereditaria en fotocopia, emitido por el Ente Gestor.")->first();
        $decesoCumplimientoDeber = ProcedureDocument::where('name',"Certificado de Deceso en cumplimiento del deber, emitido por el Comando General de la Policía Boliviana.")->first();
        $nacimientoHuerfanoAbsoluto = ProcedureDocument::where('name',"Certificado de nacimiento del huerfano absoluto en original y actualizado, emitido por el SERECI.")->first();
        $rentaSenasir = ProcedureDocument::where('name',"Certificación de otorgación de renta del SENASIR, en original o copia legalizada.")->first();
        $aportesAM = ProcedureDocument::where('name',"Certificación de aportes para el beneficio del Auxilio Mortuorio.")->first();

        // Añadir relación documentos con modalidad

        $now = Carbon::now();
        // Fondo
        $fondoRetiroJubilacionId = 3;
        $fondoRetiroFallecimientoId = 4;
        $retiroVoluntarioId = 7;
        $retiroForzosoId = 5;
        // Cuota Mortuoria
        $riesgoComunId = 9;
        $cumplimientoDeFuncionesId = 8;
        // Auxilio Mortuorio
        $titularFallecidoId = 13;
        $conyugueFallecidaId = 14;
        $viudaFallecidaId = 15;
        // Complemento
        $ceVejezId = 29;
        $ceViudedadId = 30;
        $ceOrfandadId = 31;



        $requerimientosFondoRetiroJubilacion = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 7, 'number' => 2],
            ['procedure_document_id' => 206, 'number' => 3],
            ['procedure_document_id' => 334, 'number' => 3],
            ['procedure_document_id' => 208, 'number' => 4],
            ['procedure_document_id' => 335, 'number' => 4],
            ['procedure_document_id' => 229, 'number' => 5],
            ['procedure_document_id' => 240, 'number' => 5],
            ['procedure_document_id' => 231, 'number' => 6],
            ['procedure_document_id' => 272, 'number' => 7],
            ['procedure_document_id' => 101, 'number' => 0],
            ['procedure_document_id' => 131, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 179, 'number' => 0],
            ['procedure_document_id' => 186, 'number' => 0],
            ['procedure_document_id' => 187, 'number' => 0],
            ['procedure_document_id' => 189, 'number' => 0],
            ['procedure_document_id' => 210, 'number' => 0],
            ['procedure_document_id' => 325, 'number' => 0],
            ['procedure_document_id' => 218, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
            ['procedure_document_id' => 342, 'number' => 0],
            ['procedure_document_id' => 343, 'number' => 0],
            ['procedure_document_id' => 324, 'number' => 0],
        ];

        foreach ($requerimientosFondoRetiroJubilacion as &$req) {
            $req['procedure_modality_id'] = $fondoRetiroJubilacionId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }

        $requerimientosFondoRetiroFallecimiento = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 7, 'number' => 2],
            ['procedure_document_id' => 9, 'number' => 3],
            ['procedure_document_id' => 11, 'number' => 4],
            ['procedure_document_id' => 12, 'number' => 5],
            ['procedure_document_id' => 44, 'number' => 5],
            ['procedure_document_id' => 115, 'number' => 5],
            ['procedure_document_id' => 13, 'number' => 5],
            ['procedure_document_id' => 15, 'number' => 6],
            ['procedure_document_id' => 33, 'number' => 7],
            ['procedure_document_id' => 25, 'number' => 8],
            ['procedure_document_id' => 16, 'number' => 8],
            ['procedure_document_id' => 17, 'number' => 8],
            ['procedure_document_id' => 206, 'number' => 9],
            ['procedure_document_id' => 334, 'number' => 9],
            ['procedure_document_id' => 208, 'number' => 10],
            ['procedure_document_id' => 335, 'number' => 10],
            ['procedure_document_id' => 231, 'number' => 11],
            ['procedure_document_id' => 272, 'number' => 12],
            ['procedure_document_id' => 324, 'number' => 0],
            ['procedure_document_id' => 240, 'number' => 0],
            ['procedure_document_id' => 29, 'number' => 0],
            ['procedure_document_id' => 32, 'number' => 0],
            ['procedure_document_id' => 67, 'number' => 0],
            ['procedure_document_id' => 71, 'number' => 0],
            ['procedure_document_id' => 101, 'number' => 0],
            ['procedure_document_id' => 121, 'number' => 0],
            ['procedure_document_id' => 131, 'number' => 0],
            ['procedure_document_id' => 137, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 179, 'number' => 0],
            ['procedure_document_id' => 186, 'number' => 0],
            ['procedure_document_id' => 187, 'number' => 0],
            ['procedure_document_id' => 189, 'number' => 0],
            ['procedure_document_id' => 210, 'number' => 0],
            ['procedure_document_id' => 238, 'number' => 0],
            ['procedure_document_id' => 135, 'number' => 0],
            ['procedure_document_id' => 325, 'number' => 0],
            ['procedure_document_id' => 218, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
            ['procedure_document_id' => 342, 'number' => 0],
            ['procedure_document_id' => 343, 'number' => 0],
            ['procedure_document_id' => 378, 'number' => 0],
            ['procedure_document_id' => 379, 'number' => 0],
            ['procedure_document_id' => 380, 'number' => 0],
            ['procedure_document_id' => 408, 'number' => 0],
            ['procedure_document_id' => 407, 'number' => 0],
        ];

        foreach ($requerimientosFondoRetiroFallecimiento as &$req) {
            $req['procedure_modality_id'] = $fondoRetiroFallecimientoId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }

        $requerimientosRetiroVoluntario = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 7, 'number' => 2],
            ['procedure_document_id' => 19, 'number' => 3],
            ['procedure_document_id' => 45, 'number' => 3],
            ['procedure_document_id' => 20, 'number' => 4],
            ['procedure_document_id' => 240, 'number' => 4],
            ['procedure_document_id' => 206, 'number' => 5],
            ['procedure_document_id' => 334, 'number' => 5],
            ['procedure_document_id' => 208, 'number' => 6],
            ['procedure_document_id' => 335, 'number' => 6],
            ['procedure_document_id' => 231, 'number' => 7],
            ['procedure_document_id' => 272, 'number' => 8],
            ['procedure_document_id' => 325, 'number' => 9],
            ['procedure_document_id' => 324, 'number' => 0],
            ['procedure_document_id' => 101, 'number' => 0],
            ['procedure_document_id' => 131, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 179, 'number' => 0],
            ['procedure_document_id' => 186, 'number' => 0],
            ['procedure_document_id' => 187, 'number' => 0],
            ['procedure_document_id' => 189, 'number' => 0],
            ['procedure_document_id' => 210, 'number' => 0],
            ['procedure_document_id' => 218, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
            ['procedure_document_id' => 342, 'number' => 0],
            ['procedure_document_id' => 343, 'number' => 0],
        ];
        foreach ($requerimientosRetiroVoluntario as &$req) {
            $req['procedure_modality_id'] = $retiroVoluntarioId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }

        $requerimientosRetiroForzoso = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 7, 'number' => 2],
            ['procedure_document_id' => 19, 'number' => 3],
            ['procedure_document_id' => 20, 'number' => 4],
            ['procedure_document_id' => 240, 'number' => 4],
            ['procedure_document_id' => 206, 'number' => 5],
            ['procedure_document_id' => 334, 'number' => 5],
            ['procedure_document_id' => 208, 'number' => 6],
            ['procedure_document_id' => 335, 'number' => 6],
            ['procedure_document_id' => 231, 'number' => 7],
            ['procedure_document_id' => 272, 'number' => 8],
            ['procedure_document_id' => 325, 'number' => 9],
            ['procedure_document_id' => 324, 'number' => 0],
            ['procedure_document_id' => 101, 'number' => 0],
            ['procedure_document_id' => 131, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 179, 'number' => 0],
            ['procedure_document_id' => 186, 'number' => 0],
            ['procedure_document_id' => 187, 'number' => 0],
            ['procedure_document_id' => 189, 'number' => 0],
            ['procedure_document_id' => 210, 'number' => 0],
            ['procedure_document_id' => 218, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
            ['procedure_document_id' => 342, 'number' => 0],
            ['procedure_document_id' => 343, 'number' => 0],
        ];
        foreach ($requerimientosRetiroForzoso as &$req) {
            $req['procedure_modality_id'] = $retiroForzosoId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }

        $requerimientosRiesgoComun = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 9, 'number' => 2],
            ['procedure_document_id' => 12, 'number' => 3],
            ['procedure_document_id' => 44, 'number' => 3],
            ['procedure_document_id' => 115, 'number' => 3],
            ['procedure_document_id' => 13, 'number' => 3],
            ['procedure_document_id' => 15, 'number' => 4],
            ['procedure_document_id' => 29, 'number' => 5],
            ['procedure_document_id' => 33, 'number' => 6],
            ['procedure_document_id' => 25, 'number' => 7],
            ['procedure_document_id' => 16, 'number' => 7],
            ['procedure_document_id' => 17, 'number' => 7],
            ['procedure_document_id' => 231, 'number' => 8],
            ['procedure_document_id' => 272, 'number' => 9],
            ['procedure_document_id' => 11, 'number' => 10],
            ['procedure_document_id' => 67, 'number' => 0],
            ['procedure_document_id' => 71, 'number' => 0],
            ['procedure_document_id' => 101, 'number' => 0],
            ['procedure_document_id' => 121, 'number' => 0],
            ['procedure_document_id' => 131, 'number' => 0],
            ['procedure_document_id' => 137, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 186, 'number' => 0],
            ['procedure_document_id' => 187, 'number' => 0],
            ['procedure_document_id' => 210, 'number' => 0],
            ['procedure_document_id' => 227, 'number' => 0],
            ['procedure_document_id' => 238, 'number' => 0],
            ['procedure_document_id' => 135, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
            ['procedure_document_id' => 342, 'number' => 0],
            ['procedure_document_id' => 378, 'number' => 0],
            ['procedure_document_id' => 379, 'number' => 0],
            ['procedure_document_id' => 380, 'number' => 0],
            ['procedure_document_id' => 407, 'number' => 0],
        ];
        foreach ($requerimientosRiesgoComun as &$req) {
            $req['procedure_modality_id'] = $riesgoComunId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }

        $requerimientosCumplimientoDeFunciones = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 9, 'number' => 2],
            ['procedure_document_id' => 12, 'number' => 3],
            ['procedure_document_id' => 44, 'number' => 3],
            ['procedure_document_id' => 115, 'number' => 3],
            ['procedure_document_id' => 13, 'number' => 3],
            ['procedure_document_id' => 15, 'number' => 4],
            ['procedure_document_id' => 25, 'number' => 5],
            ['procedure_document_id' => 16, 'number' => 5],
            ['procedure_document_id' => 17, 'number' => 5],
            ['procedure_document_id' => 26, 'number' => 6],
            ['procedure_document_id' => 29, 'number' => 7],
            ['procedure_document_id' => 33, 'number' => 8],
            ['procedure_document_id' => 11, 'number' => 9],
            ['procedure_document_id' => 231, 'number' => 10],
            ['procedure_document_id' => 272, 'number' => 11],
            ['procedure_document_id' => 67, 'number' => 0],
            ['procedure_document_id' => 71, 'number' => 0],
            ['procedure_document_id' => 101, 'number' => 0],
            ['procedure_document_id' => 121, 'number' => 0],
            ['procedure_document_id' => 131, 'number' => 0],
            ['procedure_document_id' => 137, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 186, 'number' => 0],
            ['procedure_document_id' => 187, 'number' => 0],
            ['procedure_document_id' => 210, 'number' => 0],
            ['procedure_document_id' => 227, 'number' => 0],
            ['procedure_document_id' => 238, 'number' => 0],
            ['procedure_document_id' => 135, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
            ['procedure_document_id' => 342, 'number' => 0],
            ['procedure_document_id' => 378, 'number' => 0],
            ['procedure_document_id' => 379, 'number' => 0],
            ['procedure_document_id' => 380, 'number' => 0],
            ['procedure_document_id' => 407, 'number' => 0],
        ];
        foreach ($requerimientosCumplimientoDeFunciones as &$req) {
            $req['procedure_modality_id'] = $cumplimientoDeFuncionesId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }

        $requerimientosTitularFallecido = [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 346, 'number' => 1],
            ['procedure_document_id' => 9, 'number' => 2],
            ['procedure_document_id' => 11, 'number' => 3],
            ['procedure_document_id' => 12, 'number' => 4],
            ['procedure_document_id' => 44, 'number' => 4],
            ['procedure_document_id' => 115, 'number' => 4],
            ['procedure_document_id' => 13, 'number' => 4],
            ['procedure_document_id' => 25, 'number' => 5],
            ['procedure_document_id' => 16, 'number' => 5],
            ['procedure_document_id' => 17, 'number' => 5],
            ['procedure_document_id' => 30, 'number' => 6],
            ['procedure_document_id' => 272, 'number' => 7],
            ['procedure_document_id' => 15, 'number' => 0],
            ['procedure_document_id' => 71, 'number' => 0],
            ['procedure_document_id' => 121, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 210, 'number' => 0],
            ['procedure_document_id' => 238, 'number' => 0],
            ['procedure_document_id' => 135, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
            ['procedure_document_id' => 378, 'number' => 0],
            ['procedure_document_id' => 380, 'number' => 0],
            ['procedure_document_id' => 407, 'number' => 0],
            
        ];
        foreach ($requerimientosTitularFallecido as &$req) {
            $req['procedure_modality_id'] = $titularFallecidoId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }

        $requerimientosConyugueFallecida= [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 12, 'number' => 2],
            ['procedure_document_id' => 13, 'number' => 2],
            ['procedure_document_id' => 29, 'number' => 3],
            ['procedure_document_id' => 272, 'number' => 4],
            ['procedure_document_id' => 131, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 210, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
            ['procedure_document_id' => 380, 'number' => 0],
            ['procedure_document_id' => 407, 'number' => 0],
                        
        ];
        foreach ($requerimientosConyugueFallecida as &$req) {
            $req['procedure_modality_id'] = $conyugueFallecidaId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }

        $requerimientosViudaFallecida= [
            ['procedure_document_id' => 3, 'number' => 1],
            ['procedure_document_id' => 11, 'number' => 2],
            ['procedure_document_id' => 12, 'number' => 3],
            ['procedure_document_id' => 44, 'number' => 3],
            ['procedure_document_id' => 115, 'number' => 3],
            ['procedure_document_id' => 13, 'number' => 3],
            ['procedure_document_id' => 25, 'number' => 4],
            ['procedure_document_id' => 16, 'number' => 4],
            ['procedure_document_id' => 17, 'number' => 4],
            ['procedure_document_id' => 32, 'number' => 5],
            ['procedure_document_id' => 33, 'number' => 6],
            ['procedure_document_id' => 272, 'number' => 8],
            ['procedure_document_id' => 15, 'number' => 0],
            ['procedure_document_id' => 71, 'number' => 0],
            ['procedure_document_id' => 131, 'number' => 0],
            ['procedure_document_id' => 137, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 210, 'number' => 0],
            ['procedure_document_id' => 135, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
            ['procedure_document_id' => 378, 'number' => 0],
            ['procedure_document_id' => 380, 'number' => 0],
            ['procedure_document_id' => 407, 'number' => 0],
            
                        
        ];
        foreach ($requerimientosViudaFallecida as &$req) {
            $req['procedure_modality_id'] = $viudaFallecidaId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }

        $requerimientosCeTitular= [
            ['procedure_document_id' => 226, 'number' => 1],
            ['procedure_document_id' => 3, 'number' => 2],
            ['procedure_document_id' => 229, 'number' => 3],
            ['procedure_document_id' => 231, 'number' => 4],
            ['procedure_document_id' => 234, 'number' => 5],
            ['procedure_document_id' => 193, 'number' => 5],
            ['procedure_document_id' => 248, 'number' => 5],
            ['procedure_document_id' => $rentaSenasir->id, 'number' => 5],
            ['procedure_document_id' => 269, 'number' => 6],
            ['procedure_document_id' => $aportesAM->id, 'number' => 7],
            ['procedure_document_id' => 320, 'number' => 7],
            ['procedure_document_id' => 272, 'number' => 0],
            ['procedure_document_id' => 249, 'number' => 0],
            ['procedure_document_id' => 7, 'number' => 0],
            ['procedure_document_id' => $boletaDePago->id, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 131, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
            ['procedure_document_id' => 101, 'number' => 0],
            ['procedure_document_id' => 260, 'number' => 0],
            ['procedure_document_id' => 186, 'number' => 0],
            ['procedure_document_id' => 187, 'number' => 0],
            ['procedure_document_id' => $añosDeServicio->id, 'number' => 0],
            
                        
        ];
        foreach ($requerimientosCeTitular as &$req) {
            $req['procedure_modality_id'] = $ceVejezId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }

        $requerimientosCeViuda= [
            ['procedure_document_id' => 226, 'number' => 1],
            ['procedure_document_id' => 3, 'number' => 2],
            ['procedure_document_id' => 346, 'number' => 2],
            ['procedure_document_id' => 33, 'number' => 3],
            ['procedure_document_id' => 229, 'number' => 4],
            ['procedure_document_id' => 240, 'number' => 4],
            ['procedure_document_id' => 231, 'number' => 5],
            ['procedure_document_id' => 269, 'number' => 6],
            ['procedure_document_id' => 237, 'number' => 7],
            ['procedure_document_id' => 12, 'number' => 8],
            ['procedure_document_id' => 13, 'number' => 8],
            ['procedure_document_id' => $aportesAM->id, 'number' => 9],
            ['procedure_document_id' => 320, 'number' => 9],
            ['procedure_document_id' => 272, 'number' => 0],
            ['procedure_document_id' => 193, 'number' => 0],
            ['procedure_document_id' => 270, 'number' => 0],
            ['procedure_document_id' => $rentaSenasir->id, 'number' => 0],
            ['procedure_document_id' => 248, 'number' => 0],
            ['procedure_document_id' => $masaHereditaria->id, 'number' => 0],
            ['procedure_document_id' => 249, 'number' => 0],
            ['procedure_document_id' => 7, 'number' => 0],
            ['procedure_document_id' => $boletaDePago->id, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 131, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
            ['procedure_document_id' => 101, 'number' => 0],
            ['procedure_document_id' => 260, 'number' => 0],
            ['procedure_document_id' => 186, 'number' => 0],
            ['procedure_document_id' => 187, 'number' => 0],
            ['procedure_document_id' => $decesoCumplimientoDeber->id, 'number' => 0],
            ['procedure_document_id' => $añosDeServicio->id, 'number' => 0],
        ];
        foreach ($requerimientosCeViuda as &$req) {
            $req['procedure_modality_id'] = $ceViudedadId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }

        $requerimientosCeHuerfano= [
            ['procedure_document_id' => 226, 'number' => 1],
            ['procedure_document_id' => 227, 'number' => 2],
            ['procedure_document_id' => $certificaciónDeDatos->id, 'number' => 2],
            ['procedure_document_id' => 3, 'number' => 3],
            ['procedure_document_id' => 228, 'number' => 4],
            ['procedure_document_id' => 130, 'number' => 5],
            ['procedure_document_id' => 239, 'number' => 6],
            ['procedure_document_id' => 264, 'number' => 6],
            ['procedure_document_id' => 229, 'number' => 7],
            ['procedure_document_id' => 240, 'number' => 7],
            ['procedure_document_id' => 231, 'number' => 8],
            ['procedure_document_id' => 269, 'number' => 9],
            ['procedure_document_id' => 237, 'number' => 10],
            ['procedure_document_id' => 238, 'number' => 11],
            ['procedure_document_id' => $nacimientoHuerfanoAbsoluto->id, 'number' => 12],
            ['procedure_document_id' => 272, 'number' => 0],
            ['procedure_document_id' => 166, 'number' => 0],
            ['procedure_document_id' => 131, 'number' => 0],
            ['procedure_document_id' => 341, 'number' => 0],
            ['procedure_document_id' => 193, 'number' => 0],
            ['procedure_document_id' => 234, 'number' => 0],
            ['procedure_document_id' => $rentaSenasir->id, 'number' => 0],
            ['procedure_document_id' => 248, 'number' => 0],
            ['procedure_document_id' => 186, 'number' => 0],
            ['procedure_document_id' => 187, 'number' => 0],
            ['procedure_document_id' => 249, 'number' => 0],
            ['procedure_document_id' => 260, 'number' => 0],
            ['procedure_document_id' => 263, 'number' => 0],
            ['procedure_document_id' => 346, 'number' => 0],
            ['procedure_document_id' => 7, 'number' => 0],
            ['procedure_document_id' => $boletaDePago->id, 'number' => 0],
            ['procedure_document_id' => 101, 'number' => 0],
            ['procedure_document_id' => $masaHereditaria->id, 'number' => 0],
            ['procedure_document_id' => $decesoCumplimientoDeber->id, 'number' => 0],
            ['procedure_document_id' => $añosDeServicio->id, 'number' => 0],
            
        ];
        foreach ($requerimientosCeHuerfano as &$req) {
            $req['procedure_modality_id'] = $ceOrfandadId;
            $req['created_at'] = $now;
            $req['updated_at'] = $now;
        }

        // fondo
        $this->editarModalidad($requerimientosFondoRetiroJubilacion, $fondoRetiroJubilacionId);
        $this->editarModalidad($requerimientosFondoRetiroFallecimiento, $fondoRetiroFallecimientoId);
        $this->editarModalidad($requerimientosRetiroVoluntario, $retiroVoluntarioId);
        $this->editarModalidad($requerimientosRetiroForzoso, $retiroForzosoId);
        // Cuota
        $this->editarModalidad($requerimientosRiesgoComun, $riesgoComunId);
        $this->editarModalidad($requerimientosCumplimientoDeFunciones, $cumplimientoDeFuncionesId);
        // Auxilio
        $this->editarModalidad($requerimientosTitularFallecido, $titularFallecidoId);
        $this->editarModalidad($requerimientosConyugueFallecida, $conyugueFallecidaId);
        $this->editarModalidad($requerimientosViudaFallecida, $viudaFallecidaId);
        // Complemento
        $this->editarModalidad($requerimientosCeTitular, $ceVejezId);
        $this->editarModalidad($requerimientosCeViuda, $ceViudedadId);
        $this->editarModalidad($requerimientosCeHuerfano, $ceOrfandadId);
    }

    public function editarDocumento($id, $name){
        $procedure_document = ProcedureDocument::find($id);
        $procedure_document->name = $name;
        if($procedure_document->isDirty()) $procedure_document->save();
    }

    public function editarModalidad($list, $modality_id){
        ProcedureRequirement::where('procedure_modality_id',$modality_id)->delete();

        foreach ($list as $l)  {
            $req = ProcedureRequirement::withTrashed()->where('procedure_document_id',$l['procedure_document_id'])
                ->where('procedure_modality_id', $l['procedure_modality_id'])->first();
                
            if($req != null){
                $req->restore();
                if ($req->number != $l['number']) {
                    $req->number = $l['number'];
                    $req->save();
                }
            } else {
                ProcedureRequirement::insert($l);
            }
        }
    }

}

