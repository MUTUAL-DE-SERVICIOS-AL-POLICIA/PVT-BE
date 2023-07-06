<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Contribution\DirectContribution;
use Muserpol\Models\Affiliate;
use Muserpol\Helpers\Util;

class DirectContributionCertificationController extends Controller
{
    public function printCommitmentLetter($direct_contribution_id)
    {
        $direct_contribution = DirectContribution::find($direct_contribution_id);

        $affiliate = $direct_contribution->affiliate;
        $user = $direct_contribution->user;
        $city = $direct_contribution->city->name;
        $date = $date = Util::getDateFormat($direct_contribution->date);
        if ($direct_contribution->procedure_modality->procedure_type_id == 6) {
            $applicant = $affiliate;
        } else {
            $applicant = $direct_contribution->procedure_modality_id == 2 ? $affiliate->spouse : $affiliate;
        }
        $title = 'COMPROMISO DE PAGO - ' . ($direct_contribution->isActiveSector() ? 'APORTE VOLUNTARIO '. ($direct_contribution->procedure_modality_id == 19 ? 'SUSPENDIDOS TEMPORALMENTE DE FUNCIONES POR PROCESOS DISCIPLINARIOS' : 'COMISIÓN DE SERVICIO ÍTEM "0" O AGREGADOS POLICIALES EN EL EXTERIOR DEL PAÍS')  : 'APORTE PARA SOLICITANTES PARA EL PAGO DE APORTE DIRECTO '. ($direct_contribution->procedure_modality_id == 21 ? 'DEL SECTOR PASIVO CORRESPONDIENTE AL SISTEMA INTEGRAL DE PENSIONES': 'DE LAS (OS) VIUDAS (OS) DEL  SECTOR PASIVO CORRESPONDIENTE AL SISTEMA INTEGRAL DE PENSIONES'));
        $state = null;
        $glosa_pago = null;
        switch ($direct_contribution->procedure_modality_id) {
            case 18:
                $state = 'Agregado Policial en el exterior del país.';
                $glosa_pago = "de mi total ganado mensual (sin descuentos)";
                break;
            case 19:
                $state = 'Suspendido temporalmente de funciones por procesos disciplinarios, figurando en planilla de haberes con ítem "0".';
                $glosa_pago = "de mi última boleta de pago efectivamente percibida";
                break;
            case 20:
                $state = 'Comisión de Servicio Ítem "0".';
                $glosa_pago = "de mi total ganado mensual (sin descuentos)";
                break;
            case 21:
                $state = 'Recibo una prestación en curso de pago del Sistema Integral de Pensiones.';
                break;
            case 22:
                $state = 'Soy beneficiaria(o) (derechohabiente) de una prestación en curso de pago del Sistema Integral de Pensiones (SIP).';
                break;
        }

        $head = 'En aplicación del Artículo 18 del Reglamento de '.($direct_contribution->isActiveSector() ? 'Fondo de Retiro Policial Solidario ': 'Cuota Mortuoria y Auxilio Mortuorio').' y a solicitud expresa voluntaria, se suscribe el presente compromiso de pago, al tenor de lo siguiente:';

        $one = 'Yo, <strong class="uppercase">'.$applicant->fullName().'</strong>, con C.I. N° <strong class="uppercase">'.$applicant->identity_card.'</strong> ';
        if ($direct_contribution->isActiveSector()) {
            $one.= 'como funcionario de público de la Policía Boliviana, DECLARO encontrarme en la siguiente situación de:';
        }else{
            $applicant_state = ($direct_contribution->procedure_modality_id == 21 ? 'como miembro del servicio pasivo de la Policía Boliviana' : 'en mi calidad de viuda (o)');
            $one.=$applicant_state. ' DECLARO que:';
        }
        $one.='<ul><li>';
        $one.=$state;
        $one.='</li></ul>';
        $one .= 'Mediante '.($direct_contribution->isActiveSector() ? '(resolución o memorándum)' : '(Declaración de Pensión/Contrato N°)'). ' '.$direct_contribution->document_number.', de fecha '.Util::getStringDate($direct_contribution->document_date).', motivo por el cual y para continuar aportando de manera regular al beneficio de '.($direct_contribution->isActiveSector() ? 'Fondo de Retiro Policial Solidario' : 'Auxilio Mortuorio').', expreso mi voluntad de realizar los aportes de forma'.($direct_contribution->isActiveSector() ? '
        voluntaria,' : '').' directa, continua y mensual previa liquidación en oficina central u oficinas regionales, misma que debe hacerse efectiva en el área de Tesorería de la MUSERPOL, o a través de depósito bancario en las cuentas fiscales de la Institución del Banco Unión, el mismo día de la liquidación.';
        if ($direct_contribution->isActiveSector()){
            $one.=' Asimismo, a la conclusión de la situación laboral en la que me encuentro, deberé presentar el Memorándum de Repliegue o la Resolución de Restitución de Funciones y/o Derechos Institucionales. Al mismo tiempo declaro que tomé conocimiento de los artículos del reglamento referidos al aporte voluntario (Artículos 12, 13, 16, 17, 18 y 19) y me apego a la modalidad de aportación, hasta la conclusión de la situación antes declarada.';
        }else{
            $one.=' De igual forma declaro que tomé conocimiento de los artículos del reglamento referidos al aporte (Artículos 12, 13, 14, 16, 17, 18 y 19).';
        }


        $three = '';
        if ($direct_contribution->isActiveSector()){
            $three.='Como funcionario público del servicio activo de la Policía Boliviana, expreso mi conformidad de aportar con el 5,86% '.$glosa_pago.', para el Fondo de Retiro Policial Solidario y Cuota Mortuoria, determinado en el Estudio Matemático Actuarial 2016 –  2020.';
        }else{
            $three.= ucfirst($applicant_state).' expreso mi conformidad de aportar con el 2,03 % sobre la totalidad de mi renta o pensión mensual para el beneficio de Auxilio Mortuorio, según lo determinado en el Estudio Matemático Actuarial 2016 –  2020.';
        }
        $area = 'Aportes';
        $code = $direct_contribution->code;
        $data = [

            'title' => $title,
            'area' => $area,
            'code' => $code,
            'direct_contribution' => $direct_contribution,
            'applicant' => $applicant,
            'user' => $user,
            'city' => $city,
            'date' => $date,
            'head' => $head,
            'glosa' => $state,
            'glosa_pago' => $glosa_pago,
            'one' => $one,
            'three' => $three,
        ];

        return \PDF::loadView('direct_contributions.print.commitment_letter', $data)
            ->setOption('copies', 2)
            ->setOption('encoding', 'utf-8')
            // ->setOption('footer-right', 'Pagina [page] de [toPage]')
            ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL')
            ->stream("carta de compromiso.pdf");
    }
}
