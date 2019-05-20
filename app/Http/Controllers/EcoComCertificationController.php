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
        if($eco_com->eco_com_reception_type_id == 1){
            $eco_com_submitted_documents = ProcedureRequirement::where('id',127)->get();
        }
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO";
        $title = "RECEPCIÓN DEL BENEFICIO DE COMPLEMENTO ECONÓMICO – ". mb_strtoupper(optional(optional($eco_com->eco_com_modality)->procedure_modality)->name);
        $code = $eco_com->code;
        $area = $eco_com->wf_state->first_shortened;
        $user = $eco_com->user;
        $date = Util::getDateFormat($eco_com->reception_date);
        $number = $code;


        $bar_code = \DNS2D::getBarcodePNG($eco_com->encode(), "QRCODE");
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code, 'user'=> $user])->render();

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
            ->setOption('margin-bottom', '23mm')
            ->setOption('footer-html', $footerHtml)
            ->stream("Reception " . $eco_com->id . '.pdf');
    }
    public function printSwornDeclaration($id)
    {
        $eco_com = EconomicComplement::with(['affiliate', 'eco_com_beneficiary', 'eco_com_procedure', 'eco_com_modality'])->find($id);
        if($eco_com->eco_com_reception_type_id == 1){
            return 'error';
        }
        $affiliate = $eco_com->affiliate;
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO";
        $title = "FORMULARIO DE DECLARACIÓN JURADA VOLUNTARIA";
        $code = $eco_com->code;
        $area = $eco_com->wf_state->first_shortened;
        $user = $eco_com->user;
        $date = Util::getDateFormat($eco_com->reception_date);
        $number = $code;

        $bar_code = \DNS2D::getBarcodePNG($eco_com->encode(), "QRCODE");
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code, 'user'=> $user])->render();

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
            ->setOption('margin-bottom', '23mm')
            ->setOption('footer-html', $footerHtml)
            ->stream("Reception " . $eco_com->id . '.pdf');
    }
    public function printQualification($id)
    {
        $eco_com = EconomicComplement::with([
            'affiliate',
            'eco_com_beneficiary',
            'eco_com_procedure',
            'eco_com_modality',
            'discount_types',
            'observations',
        ])->find($id);

        $affiliate = $eco_com->affiliate;
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $eco_com_procedure = $eco_com->eco_com_procedure;
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO";
        $title = "HOJA DE CALCULO DEL COMPLEMENTO ECONÓMICO";
        $subtitle = $eco_com_procedure->semester ." SEMESTRE ". $eco_com_procedure->getYear();
        $code = $eco_com->code;
        $area = $eco_com->wf_state->first_shortened;
        $user = $eco_com->user;
        // $date = Util::getDateFormat($eco_com->reception_date);
        $date = Util::getDateFormat(now());
        $number = $code;

        $bar_code = \DNS2D::getBarcodePNG($eco_com->encode(), "QRCODE");
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code, 'user'=> $user])->render();

        $data = [
            'direction' => $direction,
            'institution' => $institution,
            'unit' => $unit,
            'title' => $title,
            'subtitle' => $subtitle,
            'code' => $code,
            'area' => $area,
            'user' => $user,
            'date' => $date,
            'number' => $number,

            'eco_com' => $eco_com,
            'eco_com_procedure' => $eco_com_procedure,
            'affiliate' => $affiliate,
            'eco_com_beneficiary' => $eco_com_beneficiary,
        ];
        $pages = [];
        $number_pages = Util::isRegionalRole() ?3 : 2;
        for ($i = 1; $i <= $number_pages; $i++) {
            $pages[] = \View::make('eco_com.print.qualification', $data)->render();
        }

        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '23mm')
            // ->setOption('footer-html', $footerHtml)
            ->stream("Reception " . $eco_com->id . '.pdf');
    }
}
