<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Helpers\Util;
use Log;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Helpers\ID;
use Muserpol\Models\Affiliate;
use DB;
class EcoComCertificationController extends Controller
{
    public function printReception($id)
    {
        $eco_com = EconomicComplement::with(['affiliate', 'eco_com_beneficiary', 'eco_com_procedure', 'eco_com_modality'])->find($id);
        $affiliate = $eco_com->affiliate;
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $eco_com_legal_guardian = $eco_com->eco_com_legal_guardian;
        $eco_com_submitted_documents = $eco_com->submitted_documents->pluck('procedure_requirement');
        if ($eco_com->eco_com_reception_type_id == ID::ecoCom()->habitual) {
            $eco_com_submitted_documents = ProcedureRequirement::where('id', 127)->get();
        }
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO";
        $title = "RECEPCIÓN DEL BENEFICIO DE COMPLEMENTO ECONÓMICO – " . mb_strtoupper(optional(optional($eco_com->eco_com_modality)->procedure_modality)->name);
        $code = $eco_com->code;
        $area = $eco_com->wf_state->first_shortened;
        $user = $eco_com->user;
        $date = Util::getDateFormat($eco_com->reception_date);
        $number = $code;


        $bar_code = \DNS2D::getBarcodePNG($eco_com->encode(), "QRCODE");
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code, 'user' => $user])->render();

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
        $number_pages = Util::isRegionalRole() ? 3 : 2;
        for ($i = 1; $i <= $number_pages; $i++) {
            $pages[] = \View::make('eco_com.print.reception', $data)->render();
        }

        // ddjj
        if ($eco_com->eco_com_reception_type_id == ID::ecoCom()->inclusion) {
            $number_pages = Util::isRegionalRole() ? 3 : 2;
            for ($i = 1; $i <= $number_pages; $i++) {
                $pages[] = \View::make('eco_com.print.sworn_declaration', self::printSwornDeclaration($id))->render();
            }
        }
        // other ddjj
        if ($eco_com->isWidowhood()) {
            $number_pages = Util::isRegionalRole() ? 3 : 2;
            for ($i = 1; $i <= $number_pages; $i++) {
                $pages[] = \View::make('eco_com.print.sworn_declaration_beneficiary', self::printSwornDeclarationBeneficiary($id))->render();
            }
        }
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '23mm')
            ->setOption('footer-html', $footerHtml)
            ->stream("Reception " . $eco_com->id . '.pdf');
    }
    public function printSwornDeclaration($id, $only_data = true)
    {
        $eco_com = EconomicComplement::with(['affiliate', 'eco_com_beneficiary', 'eco_com_procedure', 'eco_com_modality'])->find($id);
        if ($eco_com->eco_com_reception_type_id == ID::ecoCom()->habitual) {
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
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code, 'user' => $user])->render();

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
        if ($only_data) {
            return $data;
        }
        $pages = [];
        $number_pages = Util::isRegionalRole() ? 3 : 2;
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
    public function printSwornDeclarationBeneficiary($id, $only_data = true)
    {
        $eco_com = EconomicComplement::with(['affiliate', 'eco_com_beneficiary', 'eco_com_procedure', 'eco_com_modality'])->find($id);
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
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code, 'user' => $user])->render();

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
        if ($only_data) {
            return $data;
        }
        $pages = [];
        $number_pages = Util::isRegionalRole() ? 3 : 2;
        for ($i = 1; $i <= $number_pages; $i++) {
            $pages[] = \View::make('eco_com.print.sworn_declaration_beneficiary', $data)->render();
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
        $subtitle = $eco_com_procedure->semester . " SEMESTRE " . $eco_com_procedure->getYear();
        $code = $eco_com->code;
        $area = $eco_com->wf_state->first_shortened;
        $user = $eco_com->user;
        // $date = Util::getDateFormat($eco_com->reception_date);
        $date = Util::getDateFormat(now());
        $number = $code;

        $bar_code = \DNS2D::getBarcodePNG($eco_com->encode(), "QRCODE");
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code, 'user' => $user])->render();

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
        $number_pages = Util::isRegionalRole() ? 3 : 2;
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
    public function certificationAllEcoComs($affiliate_id)
    {
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO";
        $title = "CERTIFICACIÓN";
        $affiliate = Affiliate::find($affiliate_id);
        $eco_com = $affiliate->economic_complements()->orderBy(DB::raw("regexp_replace(split_part(code, '/',3),'\D','','g')::integer"))->orderBy(DB::raw("split_part(code, '/',2)"))->orderBy(DB::raw("split_part(code, '/',1)::integer"))->get()->last();
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $type = $eco_com->getType();
        $type_modality = $eco_com->getTypeModality();
        $user = auth()->user();
        $area = Util::getRol()->wf_states->first()->first_shortened;
        $date = Util::getTextDate();
        $eco_coms = EconomicComplement::leftJoin('eco_com_procedures', 'economic_complements.eco_com_procedure_id', '=', 'eco_com_procedures.id')
        ->leftJoin('eco_com_applicants', 'economic_complements.id', '=', 'eco_com_applicants.economic_complement_id')
        ->where('eco_com_applicants.identity_card', $eco_com_beneficiary->identity_card)
        ->where('affiliate_id', $affiliate_id)
        ->select('economic_complements.*')
        ->orderBY('eco_com_procedures.year')
        ->orderBY('eco_com_procedures.semester')
        ->get();
        $data = [
            'direction' => $direction,
            'institution' => $institution,
            'unit' => $unit,
            'title' => $title,
            'area' => $area,
            'date' => $date,

            'eco_com_beneficiary' => $eco_com_beneficiary,
            'type' => $type,
            'type_modality' => $type_modality,
            'affiliate' => $affiliate,
            'eco_coms' => $eco_coms,
            'user' => $user
        ];
        $pages = [];
        $pages[] = \View::make('eco_com.print.certification_all_eco_coms', $data)->render();

        $pages[] = \View::make('eco_com.print.certification_all_eco_coms_second', array_merge($data, ['title' => 'CONFORMIDAD DE ENTREGA']))->render();
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '23mm')
            // ->setOption('footer-html', $footerHtml)
            ->stream("certificacion.pdf");
    }
}
