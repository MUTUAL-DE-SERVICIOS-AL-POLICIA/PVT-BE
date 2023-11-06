<?php

namespace Muserpol\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Helpers\Util;
use Log;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Helpers\ID;
use Muserpol\Models\Affiliate;
use DB;
use Illuminate\Support\Facades\Auth;
use Muserpol\Models\EconomicComplement\EcoComReviewProcedure;

class EcoComCertificationController extends Controller
{
    public static function get_module_eco_com($eco_com_id)
    {
      $eco_com = EconomicComplement::find($eco_com_id);
      $module_id= 2;//modulo complemento economico
      $file_name =$module_id.'/'.$eco_com->uuid;//aqui
      return $file_name;
    }
    public function printReception($id)
    {
        $eco_com = EconomicComplement::with(['affiliate', 'eco_com_beneficiary', 'eco_com_procedure', 'eco_com_modality'])->find($id);
        $affiliate = $eco_com->affiliate;
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $eco_com_legal_guardian = $eco_com->eco_com_legal_guardian;
        $submitted_document_ids = $eco_com->submitted_documents->pluck('procedure_requirement_id');
        $eco_com_submitted_documents = ProcedureRequirement::whereIn('id', $submitted_document_ids)->get();
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL BENEFICIO DEL COMPLEMENTO ECONÓMICO";
        $title = "SOLICITUD DE PAGO DEL BENEFICIO DE COMPLEMENTO ECONÓMICO";
        $size = 400;
        $size_down = 200;
        $subtitle = $eco_com->eco_com_procedure->getTextName() . " " . mb_strtoupper(optional(optional($eco_com->eco_com_modality)->procedure_modality)->name);
        $text = "";
        $habitual = false;
        if($eco_com->eco_com_reception_type_id == ID::ecoCom()->habitual || $eco_com->eco_com_reception_type_id == ID::ecoCom()->rehabilitacion)
        {
            $text = "La presente solicitud es generada bajo mi consentimiento a través de la Plataforma Virtual de Tramites – PVT, sin necesidad de firma expresa, para efectos de orden legal.";
            $habitual = true;
        }
        elseif($eco_com->eco_com_reception_type_id == ID::ecoCom()->inclusion)
            $text = "Firmo al pie del presente en señal de conformidad, debiendo considerarse mi consentimiento para las posteriores solicitudes de pago semestral a realizarse de manera presencial o virtual (Plataforma Virtual de Trámites o Aplicación Móvil – MUSERPOL PVT), sin necesidad de firma expresa en la Solicitud de Pago del Beneficio del Complemento Económico.";
        if($eco_com->eco_com_modality->procedure_modality->name != 'Vejez')
            $size = 780;
        if ($eco_com->eco_com_legal_guardian != null) {
            $size = 700;
            $size_down = 80;
        }
        $code = $eco_com->code;
        //$area = $eco_com->wf_state->first_shortened;
        //$user = $eco_com->user;
        $area = $eco_com->wf_records->sortBy('id')->first()->wf_state->first_shortened;
        $user = $eco_com->wf_records->sortBy('id')->first()->user;

        $date = Util::getDateFormat($eco_com->reception_date);
        $number = $code;


        //$bar_code = \DNS2D::getBarcodePNG($eco_com->encode(), "QRCODE");
        $bar_code = \DNS2D::getBarcodePNG($this->get_module_eco_com($eco_com->id), "QRCODE");
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
            'text' => $text,
            'habitual' => $habitual,
            'size' => $size,
            'size_down' => $size_down,
            'eco_com' => $eco_com,
            'affiliate' => $affiliate,
            'eco_com_beneficiary' => $eco_com_beneficiary,
            'eco_com_legal_guardian' => $eco_com_legal_guardian,
            'eco_com_submitted_documents' => $eco_com_submitted_documents,
        ];
        $pages = [];
        //$number_pages = Util::isRegionalRole() ? 3 : 2;
        $number_pages = $eco_com->eco_com_reception_type_id == ID::ecoCom()->inclusion ? 2 : 1;
        for ($i = 1; $i <= $number_pages; $i++) {
            $pages[] = \View::make('eco_com.print.reception', $data)->render();
        }

        // ddjj
        /*if ($eco_com->eco_com_reception_type_id == ID::ecoCom()->inclusion) {
            $number_pages = 2;
            for ($i = 1; $i <= $number_pages; $i++) {
                $pages[] = \View::make('eco_com.print.sworn_declaration', self::printSwornDeclaration($id))->render();
            }
        }
        // other ddjj
        if ($eco_com->isWidowhood() && $submitted_document_ids->contains(1263)) {
            $number_pages = 2;
            for ($i = 1; $i <= $number_pages; $i++) {
                $pages[] = \View::make('eco_com.print.sworn_declaration_beneficiary', self::printSwornDeclarationBeneficiary($id))->render();
            }
        }*/
        
        //Compromiso de Pago Vejez
        if (!$eco_com->isWidowhood() && $eco_com->eco_com_reception_type_id == ID::ecoCom()->inclusion && $affiliate->pension_entity_id <> ID::pensionEntity()->senasir) {
            
            $number_pages = 2;
            for ($i = 1; $i <= $number_pages; $i++) {
                $pages[] = \View::make('eco_com.print.payment_commitment', self::printPaymentCommitment($id))->render();
            }
        }
        //Compromiso de Pago Viudedad
        if ($eco_com->isWidowhood() && $eco_com->eco_com_reception_type_id == ID::ecoCom()->inclusion && $affiliate->pension_entity_id <> ID::pensionEntity()->senasir) {
            $number_pages = 2;
            for ($i = 1; $i <= $number_pages; $i++) {
                $pages[] = \View::make('eco_com.print.payment_commitment_beneficiary', self::printPaymentCommitmentBeneficiary($id))->render();
            }
        }

        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '23mm')
            ->setOption('footer-html', $footerHtml)
            ->stream("Reception " . $eco_com->id . '.pdf');
    }
    public function printRevisionCertificate($id)
    {
        $eco_com = EconomicComplement::with(['affiliate', 'eco_com_beneficiary', 'eco_com_procedure', 'eco_com_modality'])->find($id);
        $has_certificate = false;
        $affiliate = $eco_com->affiliate;
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $eco_com_legal_guardian = $eco_com->eco_com_legal_guardian;
        $eco_com_review_procedures = EcoComReviewProcedure::where('economic_complement_id', $id)->orderBy('review_procedure_id','asc')->get();
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL BENEFICIO DEL COMPLEMENTO ECONÓMICO";
        $title = "BENEFICIO DEL COMPLEMENTO ECONÓMICO";
        $subtitle = $eco_com->eco_com_procedure->getTextName();
        $text = "CERTIFICACIÓN DE REVISIÓN";
        $user = Auth::user() ?? $eco_com->user;
        $size = 400;
        $size_down = 200;

        if($eco_com->eco_com_reception_type_id == ID::ecoCom()->inclusion || $eco_com->eco_com_reception_type_id == ID::ecoCom()->rehabilitacion)
        {
            $has_certificate = true;
        }

        $date = now();

        $data = [
            'direction' => $direction,
            'institution' => $institution,
            'unit' => $unit,
            'title' => $title,
            'subtitle' => $subtitle,
            'date' => $date,
            'text' => $text,
            'size' => $size,
            'size_down' => $size_down,
            'eco_com' => $eco_com,
            'user' => $user,
            'affiliate' => $affiliate,
            'has_certificate' => $has_certificate,
            'eco_com_beneficiary' => $eco_com_beneficiary,
            'eco_com_legal_guardian' => $eco_com_legal_guardian,
            'eco_com_review_procedures' => $eco_com_review_procedures
        ];
        
        $pages = [];
        $pages[] = \View::make('eco_com.print.revision_certificate', $data)->render();

        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '23mm')
            ->stream("Certificacion" . $eco_com->id . '.pdf');
    }

    public function printSwornDeclaration($id, $only_data = true)
    {
        $eco_com = EconomicComplement::with(['affiliate', 'eco_com_beneficiary', 'eco_com_procedure', 'eco_com_modality'])->find($id);
        if ($eco_com->eco_com_reception_type_id == ID::ecoCom()->habitual || $eco_com->eco_com_reception_type_id == ID::ecoCom()->rehabilitacion) {
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
    public function printPaymentCommitment($id, $only_data = true)
    {
        $eco_com = EconomicComplement::with(['affiliate', 'eco_com_beneficiary', 'eco_com_procedure', 'eco_com_modality'])->find($id);
        if ($eco_com->eco_com_reception_type_id == ID::ecoCom()->habitual || $eco_com->eco_com_reception_type_id == ID::ecoCom()->rehabilitacion) {
            return 'error';
        }
        $affiliate = $eco_com->affiliate;
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA Y AUXILIO MORTUORIO";
        $title = "COMPROMISO DE PAGO – APORTE PARA
        SOLICITANTES PARA DEL DESCUENTO DIRECTO DEL COMPLEMENTO ECONÓMICO DEL SECTOR PASIVO 
        CORRESPONDIENTE AL SISTEMA INTEGRAL DE PENSIONES";
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
            $pages[] = \View::make('eco_com.print.payment_commitment', $data)->render();
        }
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '23mm')
            ->setOption('footer-html', $footerHtml)
            ->stream("Reception " . $eco_com->id . '.pdf');
    }
    public function printPaymentCommitmentBeneficiary($id, $only_data = true)
    {
        $eco_com = EconomicComplement::with(['affiliate', 'eco_com_beneficiary', 'eco_com_procedure', 'eco_com_modality'])->find($id);
        if ($eco_com->eco_com_reception_type_id == ID::ecoCom()->habitual || $eco_com->eco_com_reception_type_id == ID::ecoCom()->rehabilitacion) {
            return 'error';
        }
        $affiliate = $eco_com->affiliate;
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA Y AUXILIO MORTUORIO";
        $title = "COMPROMISO DE PAGO – APORTE PARA
        SOLICITANTES PARA DEL DESCUENTO DIRECTO DEL COMPLEMENTO ECONÓMICO DEL SECTOR PASIVO 
        CORRESPONDIENTE AL SISTEMA INTEGRAL DE PENSIONES";
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
            $pages[] = \View::make('eco_com.print.payment_commitment_beneficiary', $data)->render();
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
        $date = Util::getTextDate(now());
        $with_padding = true;
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
            'with_padding' => $with_padding,
        ];
        $pages = [];
        $number_pages = Util::isRegionalRole() ? 3 : 1;
        for ($i = 1; $i <= $number_pages; $i++) {
            $pages[] = \View::make('eco_com.print.qualification', $data)->render();
        }

        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '23mm')
            ->setOption('margin-left', 0)
            ->setOption('margin-bottom', 0)
            // ->setOption('footer-html', $footerHtml)
            ->stream("Reception " . $eco_com->id . '.pdf');
    }
    public function certificationAllEcoComs($affiliate_id)
    {
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO";
        $title = "CERTIFICACIÓN DE PAGOS DE COMPLEMENTO ECONÓMICO";
        $affiliate = Affiliate::find($affiliate_id);
        $eco_com = $affiliate->economic_complements()->orderBy(DB::raw("regexp_replace(split_part(code, '/',3),'\D','','g')::integer"))->orderBy(DB::raw("split_part(code, '/',2)"))->orderBy(DB::raw("split_part(code, '/',1)::integer"))->get()->last();
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $type = $eco_com->getType();
        $type_modality = $eco_com->eco_com_modality->procedure_modality->name;
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
        $bar_code = \DNS2D::getBarcodePNG($affiliate->encode(), "QRCODE");
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code, 'user' => $user])->render();
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
            ->setOption('footer-html', $footerHtml)
            ->stream("certificacion.pdf");
    }
    public function saveCertificationNote(Request $request, $eco_com_id)
    {
        $eco_com = EconomicComplement::find($eco_com_id);
        // Session::put('size', $request->size);
        if ($request->note) {
            $eco_com->comment = $request->note;
            $eco_com->save();
        }
        return $eco_com;
    }
    public function printLagging($id)
    {
        $eco_com = EconomicComplement::with(['affiliate', 'eco_com_beneficiary', 'eco_com_procedure', 'eco_com_modality', 'workflow'])->find($id);
        $affiliate = $eco_com->affiliate;
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $eco_com_legal_guardian = $eco_com->eco_com_legal_guardian;
        if (!$eco_com->isLagging()) {
            return "error";
        }
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO";
        $title = "SOLICITUD DE PAGO REZAGADO DEL COMPLEMENTO ECONÓMICO – " . mb_strtoupper(optional(optional($eco_com->eco_com_modality)->procedure_modality)->name);
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
        $pages = [];
        $number_pages = Util::isRegionalRole() ? 3 : 2;
        for ($i = 1; $i <= $number_pages; $i++) {
            $pages[] = \View::make('eco_com.print.lagging', $data)->render();
        }
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '23mm')
            ->setOption('footer-html', $footerHtml)
            ->stream("Reception " . $eco_com->id . '.pdf');
    }
}
