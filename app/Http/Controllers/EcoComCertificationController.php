<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Helpers\Util;
use Log;
use Muserpol\Models\ProcedureRequirement;

class EcoComCertificationController extends Controller
{
    public function printReception($id)
    {
        $eco_com = EconomicComplement::with(['affiliate', 'eco_com_beneficiary', 'eco_com_procedure', 'eco_com_modality'])->find($id);
        $affiliate = $eco_com->affiliate;
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $eco_com_legal_guardian = $eco_com->eco_com_legal_guardian;
        $eco_com_submitted_documents = $eco_com->submitted_documents->pluck('procedure_requirement');
        if($eco_com->reception_type == 'Habitual'){
            $eco_com_submitted_documents = ProcedureRequirement::where('id',127)->get();
        }
        // $bar_code = \DNS2D::getBarcodePNG(($eco_com->getBasicInfoCode()['code'] . "\n\n" . $eco_com->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
        // $bar_code = \DNS2D::getBarcodePNG(($eco_com->getBasicInfoCode()['code'] . "\n\n" . $eco_com->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
        $bar_code = \DNS2D::getBarcodePNG(($eco_com->getBasicInfoCode()['code'] . "\n\n" . $eco_com->getBasicInfoCode()['hash']), "QRCODE");
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code])->render();
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO";
        $title = null;
        $code = $eco_com->code;
        $area = $eco_com->wf_state->first_shortened;
        $user = $eco_com->user;
        $date = Util::getDateFormat($eco_com->reception_date);
        $number = $code;
        $data = [
            'direction' => $direction,
            'institution' => $institution,
            'unit' => $unit,
            'title' => $title,
            'code' => $code,
            'area' => $area,
            'user' => $user,
            'date' => $date,
            'number' => $number,

            'eco_com' => $eco_com,
            'affiliate' => $affiliate,
            'eco_com_beneficiary' => $eco_com_beneficiary,
            'eco_com_legal_guardian' => $eco_com_legal_guardian,
            'eco_com_submitted_documents' => $eco_com_submitted_documents,
        ];
        $pages = [];
        $number_pages = Util::isRegionalRole() ?3 : 2;
        for ($i = 1; $i <= $number_pages; $i++) {
            $pages[] = \View::make('eco_com.print.reception', $data)->render();
        }
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '15mm')
            ->setOption('footer-html', $footerHtml)
            ->stream("Reception " . $eco_com->id . '.pdf');
    }
    public function printSwornDeclaration($id)
    {
        $eco_com = EconomicComplement::with(['affiliate', 'eco_com_beneficiary', 'eco_com_procedure', 'eco_com_modality'])->find($id);
        if($eco_com->reception_type == 'Habitual'){
            return 'error';
        }
        $affiliate = $eco_com->affiliate;
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        /*
        **!! TODO add support utf-8
        */
        $bar_code = \DNS2D::getBarcodePNG(($eco_com->getBasicInfoCode()['code'] . "\n\n" . $eco_com->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code])->render();
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO";
        $title = "FORMULARIO DE DECLARACIÓN JURADA VOLUNTARIA";
        $code = $eco_com->code;
        $area = $eco_com->wf_state->first_shortened;
        $user = $eco_com->user;
        $date = Util::getDateFormat($eco_com->reception_date);
        $number = $code;
        $data = [
            'direction' => $direction,
            'institution' => $institution,
            'unit' => $unit,
            'title' => $title,
            'code' => $code,
            'area' => $area,
            'user' => $user,
            'date' => $date,
            'number' => $number,

            'eco_com' => $eco_com,
            'affiliate' => $affiliate,
            'eco_com_beneficiary' => $eco_com_beneficiary,
        ];
        $pages = [];
        $number_pages = Util::isRegionalRole() ?3 : 2;
        for ($i = 1; $i <= $number_pages; $i++) {
            $pages[] = \View::make('eco_com.print.sworn_declaration', $data)->render();
        }
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '15mm')
            ->setOption('footer-html', $footerHtml)
            ->stream("Reception " . $eco_com->id . '.pdf');
    }
}
