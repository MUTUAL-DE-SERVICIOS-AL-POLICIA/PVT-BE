<?php

namespace Muserpol\Http\Controllers;

use Muserpol\QuotaAidCertification;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\City;
use Session;
use Auth;
use Validator;
use Muserpol\Models\Address;
use DateTime;
use Muserpol\User;
use Carbon\Carbon;
use Muserpol\Helpers\Util;
use Muserpol\Models\Voucher;
use Muserpol\Models\VoucherType;
use Muserpol\Models\Spouse;
use Muserpol\Models\Contribution\AidContribution;
use Muserpol\Models\Contribution\DirectContribution;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Models\QuotaAidMortuary\QuotaAidSubmittedDocument;
use Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary;
use Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisorBeneficiary;
use Muserpol\Models\QuotaAidMortuary\QuotaAidLegalGuardian;
use Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiaryLegalGuardian;
use Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisor;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\QuotaAidMortuary\QuotaAidCorrelative;
use Muserpol\Models\AffiliateFolder;
use Muserpol\Models\Contribution\Contribution;
use Muserpol\Models\Contribution\Reimbursement;
use Muserpol\Models\Contribution\AidReimbursement;
use Muserpol\Models\Degree;
use Muserpol\Models\Testimony;
use Muserpol\Models\Contribution\ContributionTypeQuotaAid;
use Illuminate\Support\Str;

class QuotaAidCertificationController extends Controller
{
  public function saveCertificationNote(Request $request, $quota_aid_id)
  {
    $retirement_fund =  QuotaAidMortuary::find($quota_aid_id);
    Session::put('size', $request->size);
    if ($request->note) {
      $wf_state = WorkflowState::where('role_id', Util::getRol()->id)->first();
      Util::getNextAreaCodeQuotaAid($quota_aid_id);
      $ret_fun_correlative = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid_id)->where('wf_state_id', $wf_state->id)->first();
      $ret_fun_correlative->note = $request->note;
      $ret_fun_correlative->save();
    }
    return $retirement_fund;
  }
  public function getCorrelative($quota_aid_id, $wf_state_id)
  {
    $correlative = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid_id)->where('wf_state_id', $wf_state_id)->first();

    if ($correlative) {
      return $correlative;
    }
    return null;
  }
  public function printQuotaAidCommitmentLetter($id)
  {
    $affiliate = Affiliate::find($id);
    $date = Util::getStringDate(date('Y-m-d'));
    $username = Auth::user()->username; //agregar cuando haya roles
    $city = Auth::user()->city->name;
    // return $city;
    if ($affiliate->affiliate_state->name == "Fallecido") {
      $title = "COMPROMISO DE PAGO - APORTE PARA SOLICITANTES PARA EL PAGO DE APORTE DIRECTO DE LAS (OS) VIUDAS (OS) DEL  SECTOR PASIVO CORRESPONDIENTE AL SISTEMA INTEGRAL DE PENSIONES";
      $spouses = Spouse::where('affiliate_id', $affiliate->id)->first();
      $beneficiary = "en mi calidad de viuda (o)";
      $glosa = "Soy beneficiaria(o) (derechohabiente) con una prestación en curso de pago del Sistema Integral de Pensiones (SIP).";
      $bene = $spouses;
    } else {
      $title = "COMPROMISO DE PAGO - APORTE PARA SOLICITANTES PARA EL PAGO DE APORTE DIRECTO DEL SECTOR PASIVO CORRESPONDIENTE AL SISTEMA INTEGRAL DE PENSIONES";
      $beneficiary = "como miembro del servicio pasivo de la Policía Boliviana";
      $glosa = "Recibo una prestación en curso de pago del Sistema Integral de Pensiones.";
      $bene = $affiliate;
    }
    $pdftitle = "Carta de Compromiso de Auxilio Mortuorio";
    $namepdf = Util::getPDFName($pdftitle, $bene);
    // return view('ret_fun.print.beneficiaries_qualification', compact('date','subtitle','username','title','number','retirement_fund','affiliate','submitted_documents'));
    return \PDF::loadView('quota_aid.print.quota_aid_commitment_letter', compact('date', 'username', 'title', 'glosa', 'bene', 'city', 'beneficiary'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
  }

  public function printDirectContributionQuoteAid(Request $request)
  {
    $contributions  = json_decode($request->contributions);
    $total = $request->total;
    $total_literal = Util::convertir($total);
    $affiliate = Affiliate::find($request->affiliate_id);
    $date = Util::getStringDate(date('Y-m-d'));
    $username = Auth::user()->username; //agregar cuando haya roles
    $name_user_complet = Auth::user()->first_name . " " . Auth::user()->last_name;
    $commitment = AidCommitment::where('affiliate_id', $affiliate->id)->where('state', 'ALTA')->first();
    $title = "APORTE DIRECTO";
    if (isset($commitment->id)) {
      $title .= " - " . ($commitment->contributor == 'T' ? 'Titular' : 'Cónyuge');
    }

    $detail = "Pago de aporte directo";
    $beneficiary = $affiliate;
    $name_beneficiary_complet = Util::fullName($beneficiary);
    $pdftitle = "Comprobante";
    $namepdf = Util::getPDFName($pdftitle, $beneficiary);
    $util = new Util();
    $area = Util::getRol()->name;
    $user = Auth::user();
    $date = date('d/m/Y');
    $number = 1;
    return \PDF::loadView(
      'quota_aid.print.affiliate_aid_contribution',
      compact(
        'area',
        'user',
        'date',
        'date',
        'username',
        'title',
        'number',
        'beneficiary',
        'contributions',
        'total',
        'total_literal',
        'detail',
        'util',
        'name_user_complet',
        'name_beneficiary_complet'
      )
    )
      ->setPaper('letter')
      ->setOption('encoding', 'utf-8')
      ->setOption('footer-right', 'Pagina [page] de [toPage]')
      ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)
      ->stream("$namepdf");
  }

  public function printVoucherQuoteAid(Request $request, $affiliate_id, $voucher_id)
  {
    $affiliate = Affiliate::find($affiliate_id);
    $voucher = Voucher::find($voucher_id);
    $aid_contributions = [];
    $total_literal = Util::convertir($voucher->total);
    $payment_date = Util::getStringDate($voucher->payment_date);
    $date = Util::getStringDate(date('Y-m-d'));
    $title = "RECIBO";
    $subtitle = "AUXILIO MORTUORIO <br> (Expresado en Bolivianos)";
    $username = Auth::user()->username; //agregar cuando haya roles
    $name_user_complet = Auth::user()->first_name . " " . Auth::user()->last_name;
    $number = $voucher->code;
    $descripcion = VoucherType::where('id', $voucher->voucher_type_id)->first();
    $util = new Util();
    if ($affiliate->affiliate_state->name == "Fallecido") {
      $spouses = Spouse::where('affiliate_id', $affiliate->id)->first();
      $beneficiary = $spouses;
      $aid_contributions  = json_decode($request->aid_contributions);
    } else {
      $beneficiary = $affiliate;
      $aid_contributions  = json_decode($request->aid_contributions);
    }
    $pdftitle = "Comprobante";
    $namepdf = Util::getPDFName($pdftitle, $beneficiary);

    $area = WorkflowState::find(22)->first_shortened;
    $user = Auth::user();
    $date = date('d/m/Y');
    // return view('ret_fun.print.beneficiaries_qualification', compact('date','subtitle','username','title','number','retirement_fund','affiliate','submitted_documents'));
    return \PDF::loadView(
      'quota_aid.print.voucher_aid_contribution',
      compact(
        'date',
        'username',
        'title',
        'subtitle',
        'affiliate',
        'beneficiary',
        'util',
        'glosa',
        'aid_contributions',
        'number',
        'voucher',
        'descripcion',
        'payment_date',
        'total_literal',
        'name_user_complet',
        'area',
        'user',
        'date'
      )
    )
      ->setPaper('letter')
      ->setOption('encoding', 'utf-8')
      ->setOption('footer-right', 'Pagina [page] de [toPage]')
      ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)
      ->stream("$namepdf");
  }

  public static function get_module_quota_aid_mortuary($quota_aida)
  {
      $quota_aid = QuotaAidMortuary::find($quota_aida);
      $module_id= $quota_aid->procedure_modality->procedure_type->module->id;
      $file_name =$module_id.'/'.$quota_aid->uuid;
      return $file_name;
  }

  public function printReception($id)
  {
    $quota_aid = QuotaAidMortuary::find($id);
    $affiliate = $quota_aid->affiliate;
    $degree = $affiliate->degree;
    $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
    $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
    $modality = $quota_aid->procedure_modality->name;
    $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
    $separator = Str::contains($quota_aid->procedure_modality->name, 'riesgo común') ? ' AL ' : ($quota_aid->isAid() ? ' AL ' : ' POR ');
    $title = "REQUISITOS PARA EL " . $quota_aid->procedure_modality->procedure_type->name . $separator . mb_strtoupper($modality);

    // $next_area_code = Util::getNextAreaCode($quota_aid->id);
    $next_area_code = Util::getNextAreaCodeQuotaAid($quota_aid->id);
    $code = $quota_aid->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;
    $submitted_documents = QuotaAidSubmittedDocument::leftJoin('procedure_requirements', 'procedure_requirements.id', '=', 'quota_aid_submitted_documents.procedure_requirement_id')->where('quota_aid_mortuary_id', $quota_aid->id)->orderBy('procedure_requirements.number', 'asc')->get();

    /*
            !!todo
            add support utf-8
         */
    $bar_code = \DNS2D::getBarcodePNG($this->get_module_quota_aid_mortuary($quota_aid->id), "QRCODE");
    $applicant = QuotaAidBeneficiary::where('type', 'S')->where('quota_aid_mortuary_id', $quota_aid->id)->first();
    $pdftitle = "RECEPCIÓN - " . $title;
    $namepdf = Util::getPDFName($pdftitle, $applicant);
    $footerHtml = view()->make('quota_aid.print.footer_qr', ['bar_code' => $bar_code])->render();
    $spouse = null;
    if (($quota_aid->procedure_modality_id == 15 && $affiliate->pension_entity_id == 5) || $quota_aid->procedure_modality_id == 14) {//aqui
      $spouse = Spouse::where('affiliate_id', $affiliate->id)->first();
    }

    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,

      'bar_code' => $bar_code,
      'title' => $title,
      'institution' => $institution,
      'direction' => $direction,
      'unit' => $unit,
      'modality' => $modality,
      'applicant' => $applicant,
      'affiliate' => $affiliate,
      'degree' => $degree,
      'submitted_documents' => $submitted_documents,
      'quota_aid' => $quota_aid,
      'spouse'=>$spouse,
      'is_quota'=> $quota_aid->isQuota(),
      'separator' => $separator
    ];
    $pages = [];
    for ($i = 1; $i <= 2; $i++) {
      $pages[] = \View::make('quota_aid.print.reception', $data)->render();
    }
    $pdf = \App::make('snappy.pdf.wrapper');
    $pdf->loadHTML($pages);
    return $pdf->setOption('encoding', 'utf-8')
      //    ->setOption('margin-top', '20mm')
      ->setOption('margin-bottom', '30mm')
      //    ->setOption('margin-left', '25mm')
      //    ->setOption('margin-right', '15mm')
      //->setOption('footer-right', 'PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL')
      //    ->setOption('footer-right', 'Pagina [page] de [toPage]')
      ->setOption('footer-html', $footerHtml)
      ->stream("$namepdf");
  }

  public function printBeneficiariesQualification($id, $only_print = true)
  {
    $quota_aid = QuotaAidMortuary::find($id);

    $title = 'INFORMACIÓN GENERAL';

    $affiliate = $quota_aid->affiliate;
    $applicant = $quota_aid->quota_aid_beneficiaries()->where('type', 'S')->with('kinship')->first();
    $beneficiaries = $quota_aid->quota_aid_beneficiaries()->orderByDesc('type')->orderBy('id')->get();

    $pdftitle = "Calificación - INFORMACIÓN GENERAL";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);

    // $next_area_code = Util::getNextAreaCode($quota_aid->id);
    $next_area_code = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)
      ->where('wf_state_id', 37)
      ->first();
    $code = $quota_aid->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;

    $subtitle = $number;

    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,
      'title' => $title,
      'subtitle' => $subtitle,
      'affiliate' => $affiliate,
      'applicant' => $applicant,
      'beneficiaries' => $beneficiaries,
      'quota_aid' => $quota_aid,
    ];
    if ($only_print) {
      return \PDF::loadView('quota_aid.print.beneficiaries_qualification', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
    }
    return $data;
  }
  public function printQualificationData($id, $only_print = true)
  {
    $quota_aid = QuotaAidMortuary::find($id);
    $affiliate = $quota_aid->affiliate;
    $beneficiaries = $quota_aid->quota_aid_beneficiaries()->orderByDesc('type')->get();

    $next_area_code = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)
      ->where('wf_state_id', 37)
      ->first();
    $code = $quota_aid->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;
    $contribution_types= ContributionTypeQuotaAid::where('operator','+')->first();

    $dates = $affiliate->getContributionsWithTypeQuotaAid($id);//aqui
    $total_dates = $quota_aid->number_qualified_contributions;

    $contributions = array(
      'contribution_types' => $contribution_types->name,
      'years' => intval($total_dates / 12),
      'months' => $total_dates % 12
    );

    $title = 'CALIFICACIÓN ' . $quota_aid->procedure_modality->procedure_type->second_name;
    $subtitle = $number;
    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,
      'title' => $title,
      'subtitle' => $subtitle,
      'quota_aid' => $quota_aid,
      'affiliate' => $affiliate,
      'beneficiaries' => $beneficiaries,
      // 'start_date' => '2022-01-01',
      // 'end_date' => '2022-01-01',
      'dates' =>$dates,
      'contributions'=> $contributions,
    ];
    if ($only_print) {
      return \PDF::loadView('quota_aid.print.qualification_data', $data)
        ->setOption('encoding', 'utf-8')
        // ->setOption('footer-right', 'Pagina [page] de [toPage]')
        ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)
        ->stream("calificacion");
    }
    return $data;
  }
  public function printAllQualification($id)
  {
    $quota_aid = QuotaAidMortuary::find($id);
    $affiliate = $quota_aid->affiliate;
    $pages[] = \View::make('quota_aid.print.qualification_data', self::printQualificationData($id, false))->render();

    $pages[] = \View::make('quota_aid.print.beneficiaries_qualification', self::printBeneficiariesQualification($id, false))->render();

    $pdf = \App::make('snappy.pdf.wrapper');
    $pdf->loadHTML($pages);
    return $pdf
      ->setOption('encoding', 'utf-8')
      ->setOption('margin-bottom', '15mm')
      // ->setOption('footer-html', $footerHtml)
      // ->setOption('footer-right', 'Pagina [page] de [toPage]')
      ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)
      // ->setOption('user-style-sheet', 'css/app1.css')
      ->stream("namepdf");
  }



  public function printLegalReview($id)
  {
    $quota_aid = QuotaAidMortuary::find($id);
    $next_area_code = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', 35)->first();
    $code = $quota_aid->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;
    $title = "CERTIFICACI&Oacute;N DE DOCUMENTACI&Oacute;N PRESENTADA Y REVISADA";
    $submitted_documents = QuotaAidSubmittedDocument::select(
      'quota_aid_submitted_documents.id',
      'quota_aid_submitted_documents.quota_aid_mortuary_id',
      'quota_aid_submitted_documents.procedure_requirement_id',
      'quota_aid_submitted_documents.is_valid',
      'quota_aid_submitted_documents.reception_date'
    )
      ->where('quota_aid_submitted_documents.quota_aid_mortuary_id', $id)
      ->leftJoin('procedure_requirements', 'quota_aid_submitted_documents.procedure_requirement_id', '=', 'procedure_requirements.id')
      ->orderBy('procedure_requirements.number', 'ASC')->get();
    $affiliate = $quota_aid->affiliate;
    $bar_code = \DNS2D::getBarcodePNG($this->get_module_quota_aid_mortuary($quota_aid->id), "QRCODE");
    $footerHtml = view()->make('quota_aid.print.footer', ['bar_code' => $bar_code])->render();
    //$footerHtml = view()->make('quota_aid.print.footer', ['bar_code' => $this->generateBarCode($quota_aid)])->render();
    $cite = $number; //RetFunIncrement::getIncrement(Session::get('rol_id'), $quota_aid->id);
    $subtitle = $cite;
    $pdftitle = "Revision Legal";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,
      'subtitle' => $subtitle,
      'title' => $title,
      'quota_aid' => $quota_aid,
      'affiliate' => $affiliate,
      'submitted_documents' => $submitted_documents,
    ];
    $pages = [];
    for ($i = 1; $i <= 2; $i++) {
      $pages[] = \View::make('quota_aid.print.legal_certification', $data)->render();
    }
    $pdf = \App::make('snappy.pdf.wrapper');
    $pdf->loadHTML($pages);
    return $pdf->setOption('encoding', 'utf-8')
      ->setOption('margin-bottom', '15mm')
      ->setOption('footer-html', $footerHtml)
      ->stream("$namepdf");
  }
  public function printLiquidation($id)
  {
    $quota_aid = QuotaAidMortuary::find($id);
    $next_area_code = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', 35)->first();
    $code = $quota_aid->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;
    $title = "CERTIFICACI&Oacute;N DE LIQUIDACI&Oacute;N";
    $affiliate = $quota_aid->affiliate;
    $applicant = QuotaAidBeneficiary::where('type', 'S')->where('quota_aid_mortuary_id', $quota_aid->id)->first();
    $spouse = $affiliate->spouse()->first();
    $beneficiaries = $quota_aid->quota_aid_beneficiaries()->orderByDesc('type')->orderBy('id')->get();
    $bar_code = \DNS2D::getBarcodePNG($this->get_module_quota_aid_mortuary($quota_aid->id), "QRCODE");
    $footerHtml = view()->make('quota_aid.print.footer', ['bar_code' => $bar_code])->render();
    //$footerHtml = view()->make('quota_aid.print.footer', ['bar_code' => $this->generateBarCode($quota_aid)])->render();
    $cite = $number; //RetFunIncrement::getIncrement(Session::get('rol_id'), $quota_aid->id);
    $subtitle = $cite;
    $pdftitle = "Revision Legal";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,
      'subtitle' => $subtitle,
      'title' => $title,
      'quota_aid' => $quota_aid,
      'affiliate' => $affiliate,
      'applicant' => $applicant,
      'spouse' => $spouse,
      'beneficiaries' => $beneficiaries,
    ];
    $pages = [];
    for ($i = 1; $i <= 2; $i++) {
      $pages[] = \View::make('quota_aid.print.liquidation', $data)->render();
    }
    $pdf = \App::make('snappy.pdf.wrapper');
    $pdf->loadHTML($pages);
    return $pdf->setOption('encoding', 'utf-8')
      ->setOption('margin-bottom', '15mm')
      ->setOption('footer-html', $footerHtml)
      ->stream("$namepdf");
  }
  public function printFile($id)
  {

    $quota_aid = QuotaAidMortuary::where('id', $id)->where('code', 'NOT LIKE', '%A')->get()->last();
    $affiliate = Affiliate::find($quota_aid->affiliate_id);
    $role = Util::getRol();

    $next_area_code = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', 34)->first();
    $code = $quota_aid->code;
    $applicant = QuotaAidBeneficiary::where('type', 'S')->where('quota_aid_mortuary_id', $quota_aid->id)->first();
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;
    // $title = "CERTIFICACIÓN DE ARCHIVO – " . strtoupper($retirement_fund->procedure_modality->name ?? 'ERROR');
    $title = "CERTIFICACIÓN DE ARCHIVO";
    $affiliate_folders = AffiliateFolder::where('affiliate_id', $affiliate->id)->get();

    /**
     * !!TODO
     *!!revisar
     */
    $cite = $number; // RetFunIncrement::getIncrement(Session::get('rol_id'), $retirement_fund->id);
    $subtitle = $cite;
    $pdftitle = "Certificación de Archivo";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    // aqui
    $bar_code = \DNS2D::getBarcodePNG($this->get_module_quota_aid_mortuary($quota_aid->id), "QRCODE");
    $footerHtml = view()->make('quota_aid.print.footer', ['bar_code' => $bar_code])->render();
   // $footerHtml = view()->make('quota_aid.print.footer', ['bar_code' => $this->generateBarCode($quota_aid)])->render();
    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,
      'cite' => $cite,
      'subtitle' => $subtitle,
      'title' => $title,
      'quota_aid' => $quota_aid,
      'affiliate' => $affiliate,
      'affiliate_folders' => $affiliate_folders,
      'applicant' => $applicant,
      'unit1' => 'archivo y gestión documental<br> beneficios económicos',
    ];
    $pages = [];
    for ($i = 1; $i <= 2; $i++) {
      $pages[] = \View::make('quota_aid.print.file_certification', $data)->render();
    }
    $pdf = \App::make('snappy.pdf.wrapper');
    $pdf->loadHTML($pages);
    return $pdf->setOption('encoding', 'utf-8')
      ->setOption('margin-bottom', '15mm')
      ->setOption('footer-html', $footerHtml)
      ->stream("$namepdf");
  }
 public function printCertification($id)//cuentas individuales
  {
    $quota_aid = QuotaAidMortuary::find($id);
    $next_area_code = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', 36)->first();
    $code = $quota_aid->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    Carbon::useMonthsOverflow(false);
    $number = $next_area_code->code;
    $affiliate = Affiliate::find($quota_aid->affiliate_id);

    if (($quota_aid->procedure_modality_id == 15 && $affiliate->pension_entity_id == 5) || $quota_aid->procedure_modality_id == 14) {
      $spouse = Spouse::where('affiliate_id', $affiliate->id)->first();
      $end_date = Carbon::createFromFormat('Y-m-d', Util::parseBarDate($spouse->date_death));
      $start_date = Carbon::createFromFormat('Y-m-d', Util::parseBarDate($spouse->date_death));
    } else {
      $end_date = Carbon::createFromFormat('d/m/Y', $affiliate->date_death);
      $start_date = Carbon::createFromFormat('d/m/Y', $affiliate->date_death);
    }
    $end_date->subMonth();
    $start_date->subMonths(12); // change by procedure cotizations
    $spouse = null;
    $valid_contributions = null;
    if ($quota_aid->procedure_modality->procedure_type_id == 3) {
      Util::completQuotaContributions($affiliate->id, $start_date->copy(), $end_date->copy());
      $contributions = Contribution::where('affiliate_id', $affiliate->id)->where('month_year', '>=', $start_date->format('Y-m') . "-01")->whereDate('month_year', '<=', $end_date->format('Y-m') . "-01")->orderBy('month_year')->get();
      $valid_contributions = $contributions;
      $reimbursements = Reimbursement::where('affiliate_id', $affiliate->id)->where('month_year', '>=', $start_date->format('Y-m') . "-01")->whereDate('month_year', '<=', $end_date->format('Y-m') . "-01")->orderByDesc('month_year')->get();
    }
    //return $start_date->format('Y-m');

    if ($quota_aid->procedure_modality->procedure_type_id == 4) {
      $aid_commitment = DirectContribution::where('affiliate_id', $affiliate->id)->where('status', true)->first();

      if (!isset($aid_commitment->id) && $affiliate->pension_entity_id != 5) {
        Session::flash('message', 'No se encontró compromiso de pago');
        return redirect('affiliate/' . $affiliate->id);
      }
      $limit_period = "";
      if ($affiliate->pension_entity_id == 5) {
        $limit_period = $start_date->format('Y-m') . "-01";
      } else {
        $limit_period = $aid_commitment->start_contribution_date;
      }
      $valid_contributions = AidContribution::where('affiliate_id', $affiliate->id)
        ->whereDate('month_year', '>=', $start_date->format('Y-m') . "-01")
        ->whereDate('month_year', '<=', $end_date->format('Y-m') . "-01")
        ->whereDate('month_year', '>=', $limit_period)
        ->orderBy('month_year')->pluck('id', 'month_year');

      //return $valid_contributions;
      Util::completAidContributions($affiliate->id, $start_date->copy(), $end_date->copy());
      $contributions = AidContribution::where('affiliate_id', $affiliate->id)
        ->whereDate('month_year', '>=', $start_date->format('Y-m') . "-01")
        ->whereDate('month_year', '<=', $end_date->format('Y-m') . "-01")
        //->whereDate('month_year','>=',$aid_commitment->date_commitment)
        ->orderBy('month_year')->get();
      $reimbursements = AidReimbursement::where('affiliate_id', $affiliate->id)->where('month_year', '>=', $start_date->format('Y-m') . "-01")->whereDate('month_year', '<=', $end_date->format('Y-m') . "-01")->orderByDesc('month_year')->get();
      if ($quota_aid->procedure_modality_id == 14 || $quota_aid->procedure_modality_id == 15) {
        $spouse = $affiliate->spouse()->first();
      }
    }
    //return $contributions;
    $degree = Degree::find($affiliate->degree_id);
    $exp = City::find($affiliate->city_identity_card_id);
    $exp = ($exp == null) ? "-" : $exp->first_shortened;
    $dateac = Carbon::now()->format('d/m/Y');
    $place = City::find(Auth::user()->city_id);
    $num = 0;
    $pdftitle = "Cuentas Individuales";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    $subtitle = $next_area_code->code;
    $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
    $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
    $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
    $title = "CERTIFICACIÓN " . $quota_aid->procedure_modality->procedure_type->second_name;
    $contributions_number = 12;

    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,
      'contributions_number' => $contributions_number,
      'num' => $num,
      'subtitle' => $subtitle,
      'place' => $place,
      'quota_aid' => $quota_aid,
      'reimbursements' => $reimbursements,
      'dateac' => $dateac,
      'exp' => $exp,
      'degree' => $degree,
      'contributions' => $contributions,
      'affiliate' => $affiliate,
      'title' => $title,
      'spouse' => $spouse,
      'valid_contributions' => $valid_contributions,
      //'institution'=>$institution,
      //'direction'=>$direction,
      'unit' => $unit,
    ];
    if ($quota_aid->procedure_modality->procedure_type_id == 3) {
      return \PDF::loadView('contribution.print.certification_quota_contribution', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
    }
    if ($quota_aid->procedure_modality->procedure_type_id == 4) {
      return \PDF::loadView('contribution.print.certification_aid_contribution', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
    }
  }
  public function printCertification2($id)//cuentas individuales
  {
    $quota_aid = QuotaAidMortuary::find($id);
    $next_area_code = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', 36)->first();
    $code = $quota_aid->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    Carbon::useMonthsOverflow(false);
    $number = $next_area_code->code;
    $affiliate = Affiliate::find($quota_aid->affiliate_id);
    $spouse = null;
    $reimbursements = [];

    $min_limit = $quota_aid->affiliate->getIntervalQualificationQuotaAid($quota_aid->id)['start_min_limit'];
    $max_limit = $quota_aid->affiliate->getIntervalQualificationQuotaAid($quota_aid->id)['end_max_limit'];

    $contributions = $affiliate->getQuotaAidContributions2($quota_aid->id)['contributions_print'];
    $valid_contributions = $affiliate->getQuotaAidContributions2($quota_aid->id)['contributions_print'];
    if ($quota_aid->procedure_modality->procedure_type_id == 3) {
      $reimbursements = Reimbursement::where('affiliate_id', $affiliate->id)
      ->where('month_year','>=',$min_limit)
      ->where('month_year', '<',$max_limit)
      ->orderBy('month_year')->get();
    }

      if ($quota_aid->procedure_modality_id == 14 || $quota_aid->procedure_modality_id == 15) {
        $spouse = $affiliate->spouse()->first();
      }

    $degree = Degree::find($affiliate->degree_id);
    $exp = City::find($affiliate->city_identity_card_id);
    $exp = ($exp == null) ? "-" : $exp->first_shortened;
    $dateac = Carbon::now()->format('d/m/Y');
    $place = City::find(Auth::user()->city_id);
    $num = 0;
    $pdftitle = "Cuentas Individuales";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    $subtitle = $next_area_code->code;
    $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
    $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
    $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
    $title = "CERTIFICACIÓN " . $quota_aid->procedure_modality->procedure_type->second_name;
    $contributions_number = count($contributions);
   // dd($contributions_number);

    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,
      'contributions_number' => $contributions_number,
      'num' => $num,
      'subtitle' => $subtitle,
      'place' => $place,
      'quota_aid' => $quota_aid,
      'reimbursements' => $reimbursements,
      'dateac' => $dateac,
      'exp' => $exp,
      'degree' => $degree,
      'contributions' => $contributions,
      'affiliate' => $affiliate,
      'title' => $title,
      'spouse' => $spouse,
      'valid_contributions' => $valid_contributions,
      //'institution'=>$institution,
      //'direction'=>$direction,
      'unit' => $unit,
    ];
    if ($quota_aid->procedure_modality->procedure_type_id == 3) {
      return \PDF::loadView('contribution.print.certification_quota_contribution', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
    }
    if ($quota_aid->procedure_modality->procedure_type_id == 4) {
      return \PDF::loadView('contribution.print.certification_aid_contribution', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
    }
  }

  private function generateBarCode($quota_aid)
  {
    $bar_code = \DNS2D::getBarcodePNG(
      ($quota_aid->getBasicInfoCode()['code'] . "\n\n" . $quota_aid->getBasicInfoCode()['hash']),
      "PDF417",
      100,
      33,
      array(1, 1, 1)
    );
    return $bar_code;
  }



  public function printLegalDictum($id)
  {
    $quota_aid = QuotaAidMortuary::find($id);

    $applicant = QuotaAidBeneficiary::where('type', 'S')->where('quota_aid_mortuary_id', $quota_aid->id)->first();

    if (!$applicant) {
      return response('No existe solicitante', 404);
    }
    if (count($applicant->testimonies) == 0) {
      return response('No existen testimonios', 404);
    }

    $beneficiaries = QuotaAidBeneficiary::where('quota_aid_mortuary_id', $quota_aid->id)->orderByDesc('type')->orderBy('id')->get();
    /** PERSON DATA */
    $person = "";
    $affiliate = Affiliate::find($quota_aid->affiliate_id);
    $quota_aid_beneficiaries = QuotaAidBeneficiaryLegalGuardian::where('quota_aid_beneficiary_id', $applicant->id)->first();

    if ($quota_aid->procedure_modality_id == 15) {
      $person .= ($affiliate->spouse()->first()->gender == 'M' ? "El señor " : "La señora ");
    } else {
      $person .= ($affiliate->gender == 'M' ? "El señor " : "La señora ");
    }
    if ($quota_aid->procedure_modality_id == 15) {
      $person .= Util::fullName($affiliate->spouse()->first()) . " con C.I. N° " . $affiliate->spouse()->first()->identity_card;
    } else {
      $person .= $affiliate->fullNameWithDegree() . " con C.I. N° " . $affiliate->identity_card;
    }

    if ($quota_aid->procedure_modality_id == 15) {
      $person .= ", como TITULAR FALLECIDA ";
    } else {
      if ($affiliate->gender == "F") {
        $person .= ", como TITULAR " . ($quota_aid->procedure_modality_id != 14 ? "FALLECIDA " : " ");
      } else {
        $person .= ", como TITULAR " . ($quota_aid->procedure_modality_id != 14 ? "FALLECIDO " : " ");
      }
    }
    $person .=  "del beneficio de " . $quota_aid->procedure_modality->procedure_type->second_name . " en su modalidad de<strong class='uppercase'> &nbsp;" . $quota_aid->procedure_modality->name . "</strong>,";
    $with_art = false;
    if (isset($quota_aid_beneficiaries->id)) {
      $legal_guardian = QuotaAidLegalGuardian::where('id', $quota_aid_beneficiaries->quota_aid_legal_guardian_id)->first();
      $person .= ($legal_guardian->gender == 'M' ? " el señor " : ", la señora ") . Util::fullName($legal_guardian) . " con C.I. N° " . $legal_guardian->identity_card . ". a través de Testimonio Notarial N° " . $legal_guardian->number_authority . " de fecha " . Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority)) . " sobre poder especial, bastante y suficiente emitido por " . $legal_guardian->notary_of_public_faith . " a cargo del (la) Notario (a) " . $legal_guardian->notary . " en representación "; //.($affiliate->gender=='M'?"del señor ":"de la señora ");
      $with_art = true;
    } else {
      $person .= " ";
    }
    if ($quota_aid->procedure_modality_id != 14) {
      //$person .= " presenta la documentación para la otorgación del beneficio en fecha ". Util::getStringDate($quota_aid->reception_date) .", a lo cual considera lo siguiente:";

      if ($with_art) {
        $person .= ($applicant->gender == 'M' ? ' del Sr. ' : ' de la Sra. ');
        $with_art = false;
      } else {
        $person .= ($applicant->gender == 'M' ? ' el Sr. ' : ' la Sra. ');
      }

      $person .= Util::fullName($applicant) . " con C.I. N° " . $applicant->identity_card . " " . ". solicita el beneficio a favor suyo en calidad de " . $applicant->kinship->name;
      $testimony_applicant = Testimony::find($applicant->testimonies()->first()->id);


      // foreach($testimonies_applicant as $testimony) {
      $beneficiaries = $testimony_applicant->quota_aid_beneficiaries;

      $quantity = $beneficiaries->count();
      $start_message = false;
      if ($quantity > 2) {
        $person .= " y de los derechohabientes ";
        $start_message = true;
      }
      foreach ($beneficiaries as $beneficiary) {
        if ($beneficiary->id != $applicant->id) {
          if (!$start_message) {
            $person = $person .= " y " . ($beneficiary->gender == "M" ? "del" : "de la") . " derechohabiente ";
          }
          $person .= Util::fullName($beneficiary) . " con C.I. N° " . $beneficiary->identity_card . "." . " en calidad de " . $beneficiary->kinship->name . ((--$quantity) == 2 ? " y " : (($quantity == 1) ? '' : ', '));
        }
      }
      $quantity = $beneficiaries->count();
      if ($quantity > 1) {
        $person .= " como herederos legales acreditados mediante " . $testimony_applicant->document_type . " Nº " . $testimony_applicant->number . " de fecha " . Util::getStringDate($testimony_applicant->date) . " sobre Declaratoria de Herederos o Aceptaci&oacute;n de Herencia, emitido por " . $testimony_applicant->court . " de " . $testimony_applicant->place . " a cargo del (la) " . $testimony_applicant->notary . "";
      } else {
        $person .= " como " . ($applicant->gender == "M" ? "heredero legal acreditado" : "heredera legal acreditada") . " mediante " . $testimony_applicant->document_type . " Nº " . $testimony_applicant->number . " de fecha " . Util::getStringDate($testimony_applicant->date) . " sobre Declaratoria de Herederos o Aceptaci&oacute;n de Herencia, emitido por " . $testimony_applicant->court . " de la ciudad de " . $testimony_applicant->place . " a cargo del (la) " . $testimony_applicant->notary . "";
      }
      //}

      $testimonies_applicant = Testimony::where('affiliate_id', $affiliate->id)->where('id', '!=', $applicant->testimonies()->first()->id)->get();
      foreach ($testimonies_applicant as $testimony) {
        $beneficiaries = $testimony->quota_aid_beneficiaries;
        $beneficiaries = $beneficiaries->where('state', true);
        $quantity = $beneficiaries->count();
        $start_message = false;
        if ($quantity > 0) {
          if ($quantity > 1) {
            $person .= "; asimismo solicitan el beneficio los derechohabientes ";
            $start_message = true;
          }
          $stored_quantity = $quantity;
          foreach ($beneficiaries as $beneficiary) {
            //if($beneficiary->state)
            if (!$start_message) {
              $person = $person .= " asimismo solicita el beneficio " . ($beneficiary->gender == "M" ? "el" : "la") . " derechohabiente ";
            }
            $person .= Util::fullName($beneficiary) . " con C.I. N° " . $beneficiary->identity_card . ". en calidad de " . $beneficiary->kinship->name . ((--$quantity) == 1 ? " y " : (($quantity == 0) ? '' : ', '));
          }
          if ($stored_quantity > 1) {
            $person .= " como herederos legales acreditados mediante " . $testimony->document_type . " Nº " . $testimony->number . " de fecha " . Util::getStringDate($testimony->date) . " sobre Declaratoria de Herederos o Aceptaci&oacute;n de Herencia, emitido por " . $testimony->court . " de " . $testimony->place . " a cargo de " . $testimony->notary . "";
          } else {
            $person .= " como " . ($applicant->gender == "M" ? "heredero legal acreditado" : "heredera legal acreditada") . " mediante " . $testimony->document_type . " Nº " . $testimony->number . " de fecha " . Util::getStringDate($testimony->date) . " sobre Declaratoria de Herederos o Aceptaci&oacute;n de Herencia, emitido por " . $testimony->court . " de la ciudad de " . $testimony->place . " a cargo de " . $testimony->notary . "";
          }
        }
      }
      $person .= ". Presentando";
    } else {
      $person .= " presenta";
    }
    $person .= " la documentación para la otorgación del beneficio en fecha " . Util::getStringDate($quota_aid->reception_date) . ", a lo cual considera lo siguiente:";
    //return $person;
    /** END PERSON DATA */

    /** LAW DATA */

    $art = [
      '8' => '42 inciso a)',
      '9' => '42 inciso b)',
      '13' => '43 inciso a)',
      '14' => '43 inciso b)',
      '15' => '43 inciso c)',
    ];

    $law = "Conforme normativa, el trámite N° " . $quota_aid->code . " de la Regional " . ucwords(strtolower($quota_aid->city_start->name)) . " es ingresado por Ventanilla
        de Atención al Afiliado de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio; verificados los requisitos y la documentación presentada por la parte solicitante según lo señalado
        el Art. " . $art[$quota_aid->procedure_modality_id] . " (" . $quota_aid->procedure_modality->procedure_type->name . " al " . $quota_aid->procedure_modality->name . ") del Reglamento de Cuota Mortuoria y Auxilio Mortuorio aprobado mediante Resolución de Directorio N° 76/2019 de fecha 11 de diciembre de 2019 y conforme el Art. 48 de referido Reglamento (Procedimiento), se detalla la documentación como resultado de la aplicación de la base técnica-legal del Estudio Matemático Actuarial 2016-2020, generada y adjuntada al expediente por los funcionarios de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, según correspondan las funciones, detallando lo siguiente:";
    /** END LAW DATA */

    $body = "";

    ///---FILE---///
    $body_file = "";
    $file_id = 34;
    $file = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', $file_id)->first();

    $body_file .= "Que, mediante Certificación N° " . $file->code . ", de Archivo de la Dirección de Beneficios Económicos de fecha " . Util::getStringDate($file->date) . ", se establece que el trámite signado con el N° " . $quota_aid->code . " ";
    $discount = $quota_aid->discount_types();
    $finance = $discount->where('discount_type_id', '1')->first();
    if (isset($finance->id) && $finance->pivot->amount > 0)
      $body_file .= "si tiene expediente del referido titular por concepto de anticipo en el monto de <b>" . Util::formatMoneyWithLiteral($finance->pivot->amount) . "</b> conforme Resolución de la Comisión de Presentaciones N°" . ($finance->pivot->note_code ?? 'Sin codigo') . " de fecha " . Util::getStringDate(($finance->pivot->note_code_date ?? '')) . ".";
    else {
      $folder = AffiliateFolder::where('affiliate_id', $affiliate->id)->get();
      if ($folder->count() > 0) {
        $body_file .= "si ";
      } else {
        $body_file .= "no ";
      }
      $body_file .= "tiene expediente del ";
      switch ($quota_aid->procedure_modality_id) {
        case 14:
          $body_file .= "referido titular.";
          break;
        case 15:
          $body_file .= "titular fallecido.";
          break;
        default:
          $body_file .= "referido titular fallecido.";
      }
    }
    ///---ENDIFLE--////

    /////----FINANCE----///
    $discount = $quota_aid->discount_types();
    $finance = $discount->where('discount_type_id', '1')->first();
    $body_finance = "";
    $body_finance = "Que, mediante nota de respuesta " . ($finance->pivot->code ?? 'sin cite') . " de la Dirección de Asuntos Administrativos de fecha " . Util::getStringDate(($finance->pivot->date ?? '')) . ", refiere que " . ($affiliate->gender == 'M' ? "el" : "la") . " titular del beneficio ";
    if (isset($finance->id) && $finance->amount > 0) {
      $body_finance .= "si cuenta con registro de pagos o anticipos por concepto de Fondo de Retiro Policial en el monto de " . Util::formatMoneyWithLiteral(($finance->pivot->amount ?? 0)) . ".";
    } else {
      $body_finance .= "no cuenta con registro de pagos o anticipos por concepto de " . $quota_aid->procedure_modality->procedure_type->name . ", sin embargo se recomienda compatibilizar los listados adjuntos con las carpetas del archivo de la Unidad de Fondo de Retiro para no incurrir en algún error o pago doble de este beneficio.";
    }

    /////----END FINANCE---////

    ////-----LEGAL REVIEW ----////
    $body_legal_review   = "";
    $legal_review_id = 35;
    $legal_review = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', $legal_review_id)->first();
    $body_legal_review .= "Que, mediante Certificación N° " . $legal_review->code . " del Área Legal de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha " . Util::getStringDate($legal_review->date) . ", fue verificada y validada la documentación presentada por " . ($quota_aid->procedure_modality_id != 14 ? "los beneficiarios" : ($affiliate->gender == "M" ? "el titular" : "la titular")) . " del trámite signado con el N° " . $quota_aid->code . ".";
    /////-----END LEGAL REVIEW----///

    ///------ INDIVIDUAL ACCCOUTNS ------////
    $body_accounts = "";
    $accounts_id = 36;
    $accounts = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', $accounts_id)->first();
    $availability_code = 10;
    $availability_number_contributions = Contribution::where('affiliate_id', $affiliate->id)->where('contribution_type_id', $availability_code)->count();

    $end_contributions = [
      '3'  =>  'destino a disponibilidad de la letra (reserva activa) ' . ($affiliate->gender == 'M' ? 'del' : 'de la') . ' titular.',
      '4'  =>  'del fallecimiento del Titular.',
      '5'  =>  'de su retiro.',
      '6'  =>  'de su retiro.',
      '7'  =>  'de su retiro.',
    ];
    $body_accounts = "Que, mediante Certificación de Aportes N° " . $accounts->code . " del Área de Cuentas Individuales de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha " . Util::getStringDate($accounts->date) . ", se verificó los aportes requeridos según el reglamento vigente";

    switch ($quota_aid->procedure_modality_id) {
      case 14:
        $body_accounts .= " de la cónyuge.";
        break;
      case 15:
        $body_accounts .= " de la viuda.";
        break;
      default:
        $body_accounts .= " del titular.";
    }
    ////------- INDIVIDUAL ACCOUTNS ------////

    //----- QUALIFICATION -----////
    $body_qualification = "";
    $qualification_id = 37;
    $qualification = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', $qualification_id)->first();
    $months  = $affiliate->getTotalQuotes();
    $contributions = $affiliate->getContributionsWithTypeQuotaAid($id)[0];
    $start_contribution = $contributions->start;
    $end_contribution = $contributions->end;
    $body_qualification .=  "Que, mediante Calificación de " . $quota_aid->procedure_modality->procedure_type->second_name . " N° " . $qualification->code . " del Área de Calificación de la Unidad de Otorgación de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha " . Util::getStringDate($qualification->date) . ", se realizó el cálculo por el periodo de " . Util::getStringDate($start_contribution, true) . " a " . Util::getStringDate($end_contribution, true) . ", determinando el beneficio de <strong>" . mb_strtoupper($quota_aid->procedure_modality->procedure_type->second_name) . "</strong> por <strong>" . mb_strtoupper($quota_aid->procedure_modality->name) . "&nbsp;&nbsp;</strong>de<strong> " . Util::formatMoneyWithLiteral($quota_aid->total) . "</strong>";
    $body_qualification .= ".";

    ///------ PAYMENT ------////
    $payment = "";
    $discounts = $quota_aid->discount_types(); //DiscountType::where('quota_aid_id',$quota_aid->id)->orderBy('discount_type_id','ASC')->get();
    //$loans = InfoLoan::where('affiliate_id',$affiliate->id)->get();
    $payment = "Por consiguiente, habiendo sido remitido el presente trámite al Área Legal de la Unidad de Otorgación del Fondo de Retiro Policial Solidario,
        Cuota y Auxilio Mortuorio, autorizado por Jefatura de la referida Unidad, conforme a los Art. ";
    $art = [
      8 => '2, 3, 5, 6, 10, 20, 21, 22, 24, 25, 26, 27, 28, 29, 42, 47, 48 y 51 ',
      9 => '2, 3, 5, 6, 10, 20, 21, 23, 24, 25, 26, 27, 28, 29, 42, 47, 48 y 51 ',
      13 => '2, 3, 5, 6, 10, 31, 32, 33, 36, 37, 38, 39, 40, 41, 42, 43, 47, 48, 52 y Disposición Transitoria Cuarta',
      14 => '2, 3, 5, 6, 10, 31, 32, 34, 36, 37, 38, 39, 40, 41, 42, 43, 47, 48, 52 y Disposición Transitoria Cuarta',
      15 => '2, 3, 5, 6, 10, 31, 32, 35, 36, 37, 38, 39, 40, 41, 42, 43, 47, 48, 52 y Disposición Transitoria Cuarta'
    ];
    $payment .= $art[$quota_aid->procedure_modality_id] . " del Reglamento de Cuota Mortuoria y Auxilio Mortuorio aprobado mediante Resolución de Directorio N° 76/2019 en fecha 11 de diciembre de 2019.
     Se <strong>DICTAMINA</strong> en mérito a la documentación de respaldo contenida en el presente reconocer
        los derechos y se otorgue el beneficio de <strong>" . strtoupper($quota_aid->procedure_modality->procedure_type->second_name) . "</strong> por <strong class='uppercase'>" . $quota_aid->procedure_modality->name . "</strong> a favor ";

    $flagy = 0;
    $discounts = $quota_aid->discount_types();
    $discounts_number = $discounts->where('amount', '>', '0')->count();

    $beneficiaries_count = QuotaAidBeneficiary::where('quota_aid_mortuary_id', $quota_aid->id)->count();

    if ($quota_aid->procedure_modality_id != 14) {

      if ($quota_aid->procedure_modality_id == 15) {
        $payment .= " de " . ($beneficiaries_count > 1 ? "los beneficiarios " : ($applicant->gender ? "del Viudo " : "de la Viuda ")) . ($affiliate->spouse()->first()->gender == 'M' ? "del Sr. " : "de la Sra. ") . Util::fullName($affiliate->spouse()->first()) . " con C.I. N° " . $affiliate->spouse()->first()->identity_card . "., en el monto de <strong>" . Util::formatMoneyWithLiteral($quota_aid->total) . "</strong> de la siguiente manera: <br><br>";
      } else {
        $payment .= ($beneficiaries_count > 1 ? "los beneficiarios " : ($applicant->gender == 'M' ? "del beneficiario " : "de la beneficiaria ")) . ($affiliate->gender == 'M' ? "del " : "de la ") . $affiliate->fullNameWithDegree() . " con C.I. N° " . $affiliate->identity_card . "., en el monto de <strong>" . Util::formatMoneyWithLiteral($quota_aid->total) . "</strong> de la siguiente manera: <br><br>";
      }
    } else {
      $payment .= " de: ";
    }
    $reserved = false;

    if ($quota_aid->procedure_modality_id != 14) {
      $beneficiaries = QuotaAidBeneficiary::where('quota_aid_mortuary_id', $quota_aid->id)->orderBy('kinship_id')->orderByDesc('state')->get();
      foreach ($beneficiaries as $beneficiary) {
        if (!$beneficiary->state && !$reserved) {
          $reserved = true;
          $reserved_quantity = QuotaAidBeneficiary::where('quota_aid_mortuary_id', $quota_aid->id)->where('state', false)->count();
          $certification = $beneficiary->testimonies()->first();
          //return $certification;
          $spouse = $affiliate->spouse()->first();
          $payment .= "Mediante certificación " . $certification->document_type . "-N° " . $certification->number . " de " . Util::getStringDate($certification->date) . " emitido en la ciudad de " . $certification->place . ", se evidencia
                    la descendencia del titular fallecido; por lo que, se mantiene en reserva" . ($reserved_quantity > 1 ? " las Cuotas Partes  salvando los derechos de los beneficiarios " : " la Cuota Parte salvando los derechos del (de la) beneficiario (a) ");
          if ($quota_aid->procedure_modality_id == 15) {
            $payment .= ($spouse->gender == "M" ? "del Sr. " : "de la Sra. ") . Util::fullName($spouse) . " con C.I. N° " . $spouse->identity_card;
          } else {
            $payment .= ($affiliate->gender == "M" ? "del " : "de la ") . $affiliate->fullNameWithDegree() . " con C.I. N° " . $affiliate->identity_card;
          }
          $payment .= ". conforme establece el Art. 1094 del Código Civil, hasta que presenten la correspondiente Declaratoria de Herederos o Aceptación de Herencia y demás requisitos establecidos de conformidad con los Arts. " . $art[$quota_aid->procedure_modality_id] . " del Reglamento de Cuota Mortuoria y Auxilio Mortuorio, aprobado mediante Resolución de Directorio N° 76/2019 en fecha 11 de diciembre de 2019, de la siguiente manera:<br><br>";
        }
        //return $beneficiary;
        if (Util::isChild($beneficiary->birth_date)) {
          $payment .= 'Menor ';
        } else {
          $payment .= $beneficiary->gender == 'M' ? 'Sr. ' : 'Sra. ';
        }
        $payment .= $beneficiary->fullName();

        if ($beneficiary->identity_card) {
          $payment .= " con C.I. N° " . $beneficiary->identity_card;
        }
        $beneficiary_advisor = QuotaAidAdvisorBeneficiary::where('quota_aid_beneficiary_id', $beneficiary->id)->first();
        if (Util::isChild($beneficiary->birth_date) && !$beneficiary->state && !isset($beneficiary_advisor->id)) {
          $payment .= ", a través de tutor (a) natural, tutor (a) legal o hasta que cumpla la mayoría de edad";
        }
        if (isset($beneficiary_advisor->id)) {
          $advisor = QuotaAidAdvisor::where('id', $beneficiary_advisor->quota_aid_advisor_id)->first();
          $payment .= ", a través de su tutor" . ($advisor->gender == 'F' ? 'a' : '') . " natural " . ($advisor->gender == 'M' ? 'Sr.' : 'Sra.') . " " . Util::fullName($advisor) . " con C.I. N°" . $advisor->identity_card. ".";
        }
        $beneficiary_legal_guardian = QuotaAidBeneficiaryLegalGuardian::where('quota_aid_beneficiary_id', $beneficiary->id)->first();
        if (isset($beneficiary_legal_guardian->id)) {
          $legal_guardian = QuotaAidLegalGuardian::where('id', $beneficiary_legal_guardian->quota_aid_legal_guardian_id)->first();
          $payment .= " por si o representada legamente por " . ($legal_guardian->gender == 'M' ? "el Sr." : "la Sra. ") . " " . Util::fullName($legal_guardian) . " con C.I. N° " . $legal_guardian->identity_card.".
                    conforme establece la Escritura Pública sobre Testimonio de Poder especial, amplio y suficiente N° " . $legal_guardian->number_authority . " de " . Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority)) . " emitido por " . $legal_guardian->notary . ".";
        }
        $payment .= ', en el monto de<strong> ' . Util::formatMoneyWithLiteral($beneficiary->paid_amount) . '</strong> ' . 'en calidad de ' . $beneficiary->kinship->name . ".<br><br>";
      }
    } else {
      $payment .= "<br><br>" . $affiliate->degree->shortened . " " . $affiliate->fullName() . " con C.I. N° " . $affiliate->identity_card . "., el monto de &nbsp;<strong>" . Util::formatMoneyWithLiteral($quota_aid->total) . ".</strong>";
    }


    ///------EN  PAYMENT ------///
    // $number = Util::getNextAreaCode($quota_aid->id);
    $number = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', 40)->first();//usa el correlativo de la resolucion
    //return $number;

    $bar_code = \DNS2D::getBarcodePNG(($quota_aid->getBasicInfoCode()['code'] . "\n\n" . $quota_aid->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
    /*HEADER FOOTER*/
    $footerHtml = view()->make('quota_aid.print.legal_footer', ['bar_code' => $bar_code, 'quota_aid' => $quota_aid])->render();
    $headerHtml = view()->make('ret_fun.print.legal_header', ['quota_aid' => $quota_aid])->render();
    $user = User::find($number->user_id);
    $data = [
      'quota_aid' => $quota_aid,
      'beneficiaries'    =>  $beneficiaries,
      'correlative'  =>  $number,
      'actual_city'  =>  Auth::user()->city->name,
      'actual_date'  =>  Util::getStringDate($number->date),
      'user'  =>  $user,
      'person'    =>  $person,
      'law'   =>  $law,
      'body_file'  =>  $body_file,
      'body_accounts'  =>  $body_accounts,
      'body_finance'  =>  $body_finance,
      'body_legal_review'  =>  $body_legal_review,
      'body_qualification'  =>  $body_qualification,
      'payment'   =>  $payment,
      'art'   =>  $art,
    ];

    $pages = [];
    for ($i = 1; $i <= 3; $i++) {
      $pages[] = \View::make('quota_aid.print.legal_dictum', $data)->render();
    }
    $pdf = \App::make('snappy.pdf.wrapper');
    $pdf->loadHTML($pages);

    return $pdf->setOption('encoding', 'utf-8')
      ->setOption('footer-html', $footerHtml)
      ->setOption('header-html', $headerHtml)
      ->setOption('margin-top', 40)
      ->setOption('margin-bottom', 15)
      ->stream("dictamenLegal.pdf");
  }
  public function printHeadshipReview($quota_aid)
  {
    $quota_aid =  QuotaAidMortuary::find($quota_aid);
    $affiliate = Affiliate::find($quota_aid->affiliate_id);
    $spouse = Spouse::where('affiliate_id', $affiliate->id)->first();
    $documents = array();
    array_push($documents, 'LLENADO DE FORMULARIO CON CARÁCTER DE DECLARACIÓN JURADA');
    array_push($documents, 'CERTIFICACIÓN DE ARCHIVO Y REVISIÓN DE ANTECEDENTES');
    array_push($documents, 'CERTIFICACIÓN Y VALIDACIÓN DE DOCUMENTOS POR EL ÁREA LEGAL');
    array_push($documents, 'CERTIFICACIÓN DE APORTES EN EL SERVICIO ACTIVO');
    array_push($documents, 'CERTIFICACIÓN DE PAGOS ANTERIORES (DIRECCIÓN DE ASUNTOS ADMINISTRATIVOS)');
    // array_push($documents,'CERTIFICACIÓN DE DEUDA (DIRECCIÓN DE ESTRATEGIAS SOCIALES E INVERSIONES)');
    array_push($documents,($quota_aid->isAid()?'CALIFICACIÓN DE AUXILIO MORTUORIO':'CALIFICACIÓN DE CUOTA MORTUORIA'));//ojo
    //array_push($documents, 'DICTAMEN LEGAL');
    array_push($documents, ['SE VERIFICÓ LA CALIFICACIÓN Y DISTRIBUCIÓN DEL BENEFICIO']);

    $bar_code = \DNS2D::getBarcodePNG($this->get_module_quota_aid_mortuary($quota_aid->id), "QRCODE");
    //$bar_code = \DNS2D::getBarcodePNG(($quota_aid->getBasicInfoCode()['code'] . "\n\n" . $quota_aid->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
    $footerHtml = view()->make('ret_fun.print.footer', ['bar_code' => $bar_code])->render();

    $number = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', 38)->first();
    $user = User::find($number->user_id);

    $data = [
      'quota_aid' => $quota_aid,
      'documents' =>  $documents,
      'correlative'   =>  $number,
      'user'  =>  $user,
      'affiliate' =>  $affiliate,
      'spouse'  =>  $spouse,
      'title' =>  $quota_aid->procedure_modality->procedure_type->second_name,
      'area'  =>  $number->wf_state->first_shortened,
      'date'  =>   Util::getDateFormat($number->date),
      'code'  =>  $number->code
    ];
    return \PDF::loadView('quota_aid.print.headship_review', $data)
      ->setOption('encoding', 'utf-8')
      ->setOption('footer-html', $footerHtml)
      ->setOption('margin-bottom', 15)
      ->stream("jefaturaRevision.pdf");
  }

  public function printLegalResolution($quota_aid_id)
  {
    $quota_aid = QuotaAidMortuary::find($quota_aid_id);
    $applicant = QuotaAidBeneficiary::where('type', 'S')->where('quota_aid_mortuary_id', $quota_aid->id)->first();//solicitante

    if (!$applicant) {
      return response('No existe solicitante', 404);
    }
    if (count($applicant->testimonies) == 0) {
      return response('No existen testimonios', 404);
    }

    $beneficiaries = QuotaAidBeneficiary::where('quota_aid_mortuary_id', $quota_aid->id)->orderByDesc('type')->orderBy('id')->get();
    /** PERSON DATA */
    $affiliate = Affiliate::find($quota_aid->affiliate_id);
    $affiliate_full_name = '<b>' . $affiliate->fullNameWithDegree() . '</b> con C.I. N° <b>' . $affiliate->identity_card . '</b>';
    $quota_aid_beneficiaries = QuotaAidBeneficiaryLegalGuardian::where('quota_aid_beneficiary_id', $applicant->id)->first();
    $person = '';
    $person .= '<br>Que, en fecha ' . Util::getStringDate($quota_aid->reception_date) . ', ';
    $with_art = false;
    $spouse_full_name = '';
    if (isset($quota_aid_beneficiaries->id)) {
      $legal_guardian = QuotaAidLegalGuardian::where('id', $quota_aid_beneficiaries->quota_aid_legal_guardian_id)->first();
      $person .= ($legal_guardian->gender == 'M' ? " el Sr. " : " la Sra. ") . Util::fullName($legal_guardian) . " con C.I. N° " . $legal_guardian->identity_card . " a través de Testimonio Notarial N° " . $legal_guardian->number_authority . " de fecha " . Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority)) . " sobre poder especial, bastante y suficiente emitido por " . $legal_guardian->notary_of_public_faith . " a cargo del (la) Notario (a) " . $legal_guardian->notary . " en representación de ";
      $person .= ($applicant->gender == 'M' ? 'el Sr. ' : 'la Sra. '). Util::fullName($applicant) . " con C.I. N° " . $applicant->identity_card . ", en calidad de " . $applicant->kinship->name; //solicitante
      $with_art = true;
    } else {
        if ($quota_aid->procedure_modality_id != 14){
          $person .= ($applicant->gender == 'M' ? 'el Sr. ' : 'la Sra. '). Util::fullName($applicant) . " con C.I. N° " . $applicant->identity_card . ", en calidad de " . $applicant->kinship->name;//solicitante
        }else{
          $person .= $affiliate->gender == 'M' ? 'el Sr. ': 'la Sra. ';
        }
    }
    if ($quota_aid->procedure_modality_id != 14) {
      if($applicant->testimonies()->first()){
        $testimony_applicant = Testimony::find($applicant->testimonies()->first()->id);
        $beneficiaries = $testimony_applicant->quota_aid_beneficiaries;
        $quantity = $beneficiaries->count();
        $start_message = false;
       if ($quantity > 2) {
          $person .= '. Asimismo, los derechohabientes ';
          $start_message = true;
        }
        foreach ($beneficiaries as $beneficiary) {
          if ($beneficiary->id != $applicant->id) {
            if (!$start_message) {
              $person = $person .= ' y ' . ($beneficiary->gender == 'M' ? 'del' : 'de la') . ' derechohabiente ';
            }
            $person .= Util::fullName($beneficiary) . " con C.I. N° " . $beneficiary->identity_card . ", en calidad de " . $beneficiary->kinship->name . ((--$quantity) == 2 ? " y " : (($quantity == 1) ? '' : ', '));
          }
        }
        $quantity = $beneficiaries->count();
        if ($quantity > 1) {
          $person .= ' como herederos legales acreditados mediante ' .$testimony_applicant->document_type . ' Nº ' . $testimony_applicant->number .' de fecha '.Util::getStringDate($testimony_applicant->date).' sobre Declaratoria de Herederos o Aceptación de Herencia, emitido por ' . $testimony_applicant->court . ' de ' . $testimony_applicant->place . ' a cargo de ' . $testimony_applicant->notary;
        } else {
          $person .= ' como ' . ($applicant->gender == 'M' ? 'heredero legal acreditado' : 'heredera legal acreditada') . ' mediante ' . $testimony_applicant->document_type . ' Nº ' . $testimony_applicant->number . ' de fecha ' . Util::getStringDate($testimony_applicant->date) . ' sobre Declaratoria de Herederos o Aceptación de Herencia, emitido por ' . $testimony_applicant->court . ' de la ciudad de ' . $testimony_applicant->place . ' a cargo de ' . $testimony_applicant->notary;
        }
        $testimonies_applicant = Testimony::where('affiliate_id', $affiliate->id)->where('id', '!=', $applicant->testimonies()->first()->id)->get();
        ///recorre los testimonios
        foreach ($testimonies_applicant as $testimony) {
          $beneficiaries = $testimony->ret_fun_beneficiaries;
          $beneficiaries = $beneficiaries->where('state', true);
          $quantity = $beneficiaries->count();
          $start_message = false;
          if ($quantity > 0) {
            if ($quantity > 1) {
              $person .= '. Asimismo los derechohabientes ';
              $start_message = true;
            }
            $stored_quantity = $quantity;
            foreach ($beneficiaries as $beneficiary) {
              if (!$start_message) {
                $person = $person .= '. Asimismo solicita el beneficio ' . ($beneficiary->gender == 'M' ? 'el' : 'la') . ' derechohabiente ';
              }
              $person .= Util::fullName($beneficiary) . ' con C.I. N° ' . $beneficiary->identity_card. ', en calidad de ' . $beneficiary->kinship->name . ((--$quantity) == 1 ? ' y ' : (($quantity == 0) ? ' ' : ', '));
            }
            if ($stored_quantity > 1) {
              $person .= ' mediante ' . $testimony->document_type . ' Nº ' . $testimony->number . ' de fecha ' . Util::getStringDate($testimony->date).' sobre Declaratoria de Herederos o Aceptación de Herencia, emitido por '.$testimony->court.' de la ciudad de '.$testimony->place.' a cargo de '.$testimony->notary. '';
            } else {
              $person .= ' como ' . ($applicant->gender == 'M' ? 'heredero legal acreditado' : 'heredera legal acreditada') . ' mediante ' . $testimony->document_type . ' Nº ' . $testimony->number .' de fecha '.Util::getStringDate($testimony->date). ' sobre Declaratoria de Herederos o Aceptación de Herencia, emitido por ' . $testimony->court . ' de la ciudad de ' . $testimony->place . ' a cargo de ' . $testimony->notary;
            }
          }
        }
      }
      $presents = ", presentando";
    } else {
      $presents = " presenta";
    }
    if ($quota_aid->procedure_modality_id == 15) {
      $spouse_full_name.= ' <b>' . ($affiliate->spouse()->first()->gender == 'M' ? ' Sr. ' : 'Sra. ') . Util::fullName($affiliate->spouse()->first()) . '</b> con C.I. N° <b>' . $affiliate->spouse()->first()->identity_card . '</b>';
      $person .= ', solicita el pago del beneficio de ' . $quota_aid->procedure_modality->procedure_type->second_name . " en su modalidad de <b>" .mb_strtoupper($quota_aid->procedure_modality->name).'</b> ' . ($affiliate->spouse()->first()->gender == 'M' ? ' del Sr. fallecido: ' : 'de la Sra. fallecida: '). $spouse_full_name; 
    } elseif ($quota_aid->procedure_modality_id == 14) {
        $person .= $affiliate_full_name.', en calidad de ' . ($affiliate->gender == 'M' ? 'viudo de la fallecida: ' : 'viuda del fallecido: ');
        $spouse_full_name.= ' <b>' . ($affiliate->spouse()->first()->gender == 'M' ? ' Sr. ' : 'Sra. ') . Util::fullName($affiliate->spouse()->first()) . '</b> con C.I. N° <b>' . $affiliate->spouse()->first()->identity_card . '</b>';
        $person .= $spouse_full_name.', solicita el pago del beneficio de ' . $quota_aid->procedure_modality->procedure_type->second_name . " en su modalidad de <strong>" .mb_strtoupper($quota_aid->procedure_modality->name).'</strong>' ;
      } else {
        $person .=  ". Solicita(n) el pago del  beneficio de " .mb_strtoupper($quota_aid->procedure_modality->procedure_type->second_name) . " en su modalidad de " .mb_strtoupper($quota_aid->procedure_modality->name) . " del afiliado fallecido: ".$affiliate_full_name;
      }
    $person .= $presents.' la documentación en Ventanilla de Atención al Aﬁliado de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio en la Regional '.Util::ucw($quota_aid->city_start->name).'.';
    /** END PERSON DATA */
    $considering_one = 'Que, la Mutual de Servicios al Policía al ser una institución pública descentralizada,
    bajo tuición del Ministerio de Gobierno, regula su actividad y procedimiento bajo los principios
    generales descritos en el Art. 232 de la Constitución Política del Estado, Art. 4 de la Ley N° 2341 y
    Art. 3 del Decreto Supremo N° 27113, cuya competencia para conocer asuntos administrativos suscitados
    tanto por la institución, así como por los administrados, se sujetan a lo determinado por el Art. 5
    de la Ley de Procedimiento Administrativo.
    <br><br>';
    $considering_one .= 'Que, el Decreto Supremo N° 1446 de 19 de diciembre de 2012, Artículo 2 de la CREACIÓN Y
    NATURALEZA JURÍDICA, Parágrafo I establece: <i>“Se crea la Mutual de Servicios al Policía –
    MUSERPOL, como institución pública descentralizada, de duración indefinida y patrimonio
    propio, con autonomía de gestión administrativa, financiera, legal y técnica, bajo tuición del
    Ministerio de Gobierno.”</i> El Artículo 5 del ÁMBITO DE APLICACIÓN, Parágrafos I y II, refiere: <i>“I.
    El presente Decreto Supremo es aplicable a todas y todos los afiliados activos y pasivos de la
    Policía Boliviana, así como a sus beneficiarios de acuerdo a reglamento. II. Para los afiliados
    activos y pasivos de la Policía Boliviana que hayan sido dados de baja, de forma voluntaria o
    forzosa, los beneficios establecidos en el presente Decreto Supremo estarán sujetos a
    reglamentación interna”</i>.
    <br><br>';
    $considering_one.='Que, el Decreto Supremo N° 2829, de 06 de julio de 2016, modificatorio al Decreto Supremo Nº
    1446 de 19 de diciembre de 2012, establece otorgar el beneﬁcio de Cuota Mortuoria y Auxilio Mortuorio dejando sin efecto 
    el Seguro de Vida, asimismo, establece que el beneficio de Cuota Mortuoria y Auxilio Mortuorio
    serán objeto de un Estudio Técnico Financiero y Estudio Matemático Actuarial que asegure su sostenibilidad, en el marco del principio de 
    solidaridad. <br><br>';
    if($quota_aid->procedure_modality->procedure_type_id == 3) {
      $considering_one.='Que, el Decreto Supremo N°5007, de 23 de agosto de 2023 modificatorio al Decreto Supremo N°1446, de 19 de diciembre de 2012, 
      restituye el pago del beneficio de la Cuota Mortuoria por el Fallecimiento de la Cónyuge.
      <br><br>';
    }
    $considering_one.='Que, el Estudio Matemático Actuarial 2021 – 2025, aprobado mediante Resolución de Directorio Nº
    77/2021, de 21 de octubre de 2021, determina las cuantías para la otorgación del beneﬁcio de '.
    ($quota_aid->procedure_modality->procedure_type->second_name).'.';

    $considering_two = 'Que, el Reglamento de los Beneficios de Cuota Mortuoria y Auxilio Mortuorio, aprobado mediante Resolución de Directorio Nº 68/2023 
    de  19  de septiembre  de  2023,  en sus Artículos 1, 2, 6, 7, 10, 11, 15, 16, '; 
    if($quota_aid->procedure_modality->procedure_type_id == 3){
      $considering_two .= '17, 22, 23, 24, 25, 26, 28, 42, 46, 51, 52, 56';
    }else{
      $considering_two .= '33, 34, 35, 36, 38, 42, 47, 51, 52, 57';
    }
    $considering_two .= ' reconocen el derecho de la otorgación del Beneficio de '.($quota_aid->procedure_modality->procedure_type->second_name).'.
    <br><br>';
    // if ($quota_aid->procedure_modality->procedure_type_id == 4) {
    //   $considering_two.=' II. Auxilio Mortuorio a) Los aportes obligatorios a la Mutual de Servicios al Policía - MUSERPOL de los (las) Afiliados (as) 
    //   del sector pasivo de la Policía Boliviana, que son sujetos de descuento mediante planilla de rentas del Servicio Nacional del Sistema de Reparto 
    //   (SENASIR). b) Los aportes obligatorios de los (las) Afiliados (as) del sector pasivo de la Policía Boliviana, que cuentan con una Prestación de 
    //   Vejez en curso de pago del Sistema Integral de Pensiones (SIP) y los aportes de los (las) viudos (as), en caso de fallecimiento del (la) Titular. 
    //   c) En caso de necesidad, se podrá utilizar una alícuota del 55% de los rendimientos del portafolio de las inversiones proyectadas para un determinado 
    //   periodo, según lo establecido en el Estudio Técnico Financiero. ARTÍCULO 11. <b>(PRIMA DE FINANCIAMIENTO)</b>.- Es el porcentaje de aportación determinado 
    //   por el Estudio Matemático Actuarial 2021 - 2025 sobre el cual los Afiliados públicos policiales efectivizan sus aportes para la otorgación de los beneficios. 
    //   (…) V. El porcentaje de aporte obligatorio para el beneficio de Auxilio Mortuorio, de los (las) Afiliados (as) que son jubilados (as) del SENASIR o 
    //   que cuentan con una pensión de vejez (o por concurrencia de pensiones) en curso de pago del Sistema Integral de Pensiones (previo compromiso de pago 
    //   de aportes), determinado por el Estudio Matemático Actuarial 2021 - 2025, es del 2,03% sobre la totalidad de su renta o pensión mensual sin considerar 
    //   la Renta Dignidad. VI. La Tasa de aportación para el beneficio de Auxilio Mortuorio, por parte de los (las) viudos (as) que cuentan con una renta de viudedad 
    //   del SENASIR o prestación de vejez en curso de pago del Sistema Integral de Pensiones (previo compromiso de pago), es del 2,03% sobre la totalidad de su renta o 
    //   pensión mensual sin considerar la Renta Dignidad.”, </i>,';
    // }else{
    //   $considering_two.=' “<b>I. Cuota Mortuoria a)</b> Los aportes obligatorios de los (las) Afiliados (as) del sector activo, transferidos a la Mutual de Servicios al Policía 
    //   - MUSERPOL por el Comando General de la Policía Boliviana, información que deberá ser reportada por la Dirección de Beneficios Económicos y contrastada por la Dirección 
    //   de Asuntos Administrativos. b). Los aportes directos de los (las) Afiliados (as) del servicio activo de la Policía Boliviana que se encuentren en comisión de servicio 
    //   Ítem Cero (Ítem “0”) y aquellos Afiliados que se encuentren suspendidos o retirados temporalmente de sus funciones por procesos disciplinarios y/o penales siempre y 
    //   cuando figuren en planilla de haberes y/o lista de revista del Comando General de la Policía Boliviana. ARTÍCULO 11. <b>(PRIMA DE FINANCIAMIENTO)</b>.- Es el porcentaje de 
    //   aportación determinado por el Estudio Matemático Actuarial 2021 - 2025 sobre el cual los Afiliados públicos policiales efectivizan sus aportes para la otorgación de 
    //   los beneficios I. El porcentaje de aporte obligatorio de los servidores públicos policiales activos, determinado para el beneficio de Cuota Mortuoria por el Estudio 
    //   Matemático Actuarial 2021 - 2025, es del 1,09% sobre la totalidad del ingreso cotizable. II. El porcentaje de aporte directo para el beneficio de Cuota Mortuoria, 
    //   por parte de los (las) Afiliados (as) del sector activo de la Policía Boliviana, destinados en comisión de servicio Ítem Cero (Ítem "0"), previo compromiso de pago, 
    //   será del 1,09% sobre el ingreso cotizable correspondiente a un efectivo policial con el mismo grado y de la misma promoción de egreso de la Universidad Policial o 
    //   de la Facultad Técnica Policial más todos los bonos que hubiese registrado en su última boleta de pago antes de su destino (sin descuentos). III. El porcentaje de 
    //   aporte directo para el beneficio de Cuota Mortuoria, por parte de los (las) Afiliados (as) que se encuentren suspendidos (as) o retirados (as) temporalmente de sus 
    //   funciones por procesos disciplinarios y/o penales figurando en planilla de haberes con Ítem Cero (Ítem "0"), será del 1,09% sobre el salario cotizable detallado en 
    //   la última boleta emitida por el Comando General de la Policía Boliviana. IV. El porcentaje de aporte directo para el beneficio de Cuota Mortuoria, por parte de los 
    //   (las) Afiliados (as) del sector activo de la Policía Boliviana, destinados como agregados policiales en exterior del país, será del 1,09% sobre el salario cotizable 
    //   correspondiente a un efectivo policial con el mismo grado y de la misma promoción de egreso de la Universidad Policial o de la Facultad Técnica Policial, considerando 
    //   la totalidad de los bonos incluidos en su última boleta de pago (con las actualizaciones que correspondan, en caso de cualquier tipo de variación y sin descuentos), 
    //   tomando como referente el bono al cargo correspondiente a un Director Nacional (en caso de Jefes que no registren el monto de este bono con anterioridad en su boleta
    //    de pago)”.</i>,';
    // }
    // $considering_two.='son los parámetros que establece el rendimiento e inversiones para el beneficio de Auxilio Mortuorio, asimismo cuanto es el porcentaje de aportación
    // por parte del sector activo de sus ingresos cotizables mensuales.
    // <br><br>';;
    // if ($quota_aid->procedure_modality->procedure_type_id == 4) { // auxilio mortuorio
    //   $considering_two.='Que, los Artículos 15 y 16 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio refieren: <i>“ARTICULO 15° (APORTE MENSUAL).- 
    //   Es el efectuado a la Mutual de Servicios al Policía – MUSERPOL por: III. Los (las) afiliados (as) del sector pasivo o viudas (os) que cuentan con 
    //   una renta por jubilación en curso de pago, mediante descuentos mensuales por planilla de rentas del Servicio Nacional del Sistema de Reparto - 
    //   SENASIR. IV. Los (las) afiliados (as) del sector pasivo o viudas (os) que cuentan con una prestación de vejez en curso de pago del Sistema Integral 
    //   de Pensiones (SIP), mismo que deberán realizar sus aportes de manera directa a la Mutual de Servicios al Policía- MUSERPOL (en las modalidades de 
    //   APORTE DIRECTO y APORTE ANTICIPADO A TRAVES DE DESCUENTOS DEL COMPLEMENTO ECONOMICO SEMESTRAL), previa firma de compromiso de pago de aportes. 
    //   ARTÍCULO 16º. (OBLIGATORIEDAD).- III. Los (las) afiliados (as) del sector pasivo de la Policía Boliviana o los (las) viudos (as) que cuenten con 
    //   renta de jubilación del Servicio Nacional del Sistema de Reparto - SENASIR, realizarán aportes a la Mutual de Servicios al Policía- MUSERPOL, 
    //   mediante descuentos mensuales por planilla del rentas, hasta el momento de su deceso. IV. Los (las) afiliados (as) del sector pasivo de la Policía 
    //   Boliviana o los (las) viudos (as) que cuenten con pensión de  vejez  (o por concurrencia de pensiones)  del  Sistema  Integral  de  Pensiones, 
    //   realizarán aportes directos a la Mutual de Servicios al Policía- MUSERPOL, sobre el total de su pensión mensual, sin considerar la Renta Dignidad 
    //   hasta el momento de su deceso.”,</i> ';
    // }else{
    //   $considering_two.='Que, los Artículos 15, 16 y 17 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio refieren: “ARTICULO 15. <i>(APORTE MENSUAL).- Es el efectuado 
    //   a la Mutual de Servicios al Policía – MUSERPOL por: I. Los (las) afiliados (as) del sector activo a través del Comando General de la Policía Boliviana, mediante 
    //   descuentos mensuales de las planillas de haberes. II. Los (las) afiliados (as) del servicio activo de la Policía Boliviana, que no son objeto de descuentos mediante 
    //   planillas de haberes del Comando General de la Policía Boliviana, por estar destinados en comisión de servicio Ítem Cero (Ítem “0”), suspendidos o retirados temporalmente 
    //   de sus funciones por procesos disciplinarios y/o penales, figurando en planilla de haberes con Ítem Cero (Ítem “0” ), deberán realizar sus aportes directos a la Mutual 
    //   de Servicios al Policía - MUSERPOL, previa firma de compromiso de pago de aportes(…)</i> ARTÍCULO 16º. <i>(OBLIGATORIEDAD).- I. Los aportes efectuados a la Mutual de Servicios 
    //   al Policía - MUSERPOL, por los (las) afiliados (as) del sector activo de la Policía Boliviana, son de carácter obligatorio, desde su ingreso a la Institución Policial, 
    //   hasta el momento de su desvinculación definitiva acreditada mediante Memorándum de Agradecimiento o Resolución de baja definitiva (voluntaria o forzosa) emitidos por el 
    //   Comando General de la Policía Boliviana emitidos por el Comando General de la Policía Boliviana o Certificado de Defunción en caso del deceso del (la) Titular Policía. 
    //   II. Los (as) efectivos Policiales que se encuentren en comisión de servicio Ítem Cero (Ítem “0”) o que hubieran sido suspendidos (as) de sus funciones por procesos 
    //   disciplinarios y/o penales, figurando en planilla de haberes con Ítem Cero (Ítem “0”) y otros, deberán continuar aportando de manera directa a la Mutual de Servicios 
    //   al Policía - MUSERPOL, para poder acceder al beneficio de Cuota Mortuoria; a excepción de los que no figuran en listas de revista y planillas de haberes del Comando 
    //   General de la Policía Boliviana(…)</i>. ARTÍCULO 17º. <i>(RECONOCIMIENTO DE LOS APORTES).- La Mutual de Servicios al Policía - MUSERPOL reconoce la cantidad de aportes 
    //   efectuados a partir de mayo de 1976, al Ex Fondo Complementario de Seguridad Social de la Policía Nacional y a la Ex Mutual de Seguros del Policía – MUSEPOL”,</i>';
    // }
    // $considering_two.=' establece la forma y manera de cómo un afiliado de la Policía Boliviana realiza los aportes a la Mutual de Servicios al Policía MUSERPOL.
    // <br><br>';
    // if ($quota_aid->procedure_modality->procedure_type_id == 4) {
    //   $considering_two.= 'Que, los Artículos 32, 33, 34, 35 y 36 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio, refieren: ARTÍCULO 32º. <i>(DEFINICIÓN).- 
    //   Para efectos del presente Reglamento y conforme lo dispuesto por el inciso b) parágrafo I del Artículo 14 y el Artículo 16 del Decreto Supremo N° 1446 de 
    //   19 de diciembre de 2012; modificado por los parágrafos II, III y IV del Artículo 2 del Decreto Supremo N° 2829 de 6 de julio de 2016, se define el beneficio 
    //   de la siguiente manera: Auxilio Mortuorio.- Es el beneficio económico que se otorga a los (las) derechohabiente (s) de los miembros del sector pasivo de la 
    //   Policía Boliviana Afiliados a la Mutual de Servicios al Policía - MUSERPOL, destinados a los gastos emergentes del fallecimiento del (la) Titular, Cónyuge 
    //   o Viudo (a) que se hará efectivo con el pago de un monto único y por una sola vez. ARTÍCULO 33º. ((MODALIDADES DE AUXILIO MORTUORIO).- El beneficio de Auxilio 
    //   Mortuorio, será otorgado en las siguientes modalidades: a)Auxilio Mortuorio al Fallecimiento del (la) Titular. b) Auxilio Mortuorio al Fallecimiento del (la) 
    //   Cónyuge. c) Auxilio Mortuorio al Fallecimiento del (la) Viudo (a). ARTÍCULO 34º. (AUXILIO MORTUORIO AL FALLECIMIENTO DEL (LA) TITULAR).- Será otorgado a los 
    //   (las) derechohabientes debidamente acreditados del (la) Afiliado (a) del sector pasivo de la Policía Boliviana, que cumplan con lo determinado en el inciso a) 
    //   del Artículo 47, en función al último grado que ostentaba el (la) Titular al momento de su desvinculación definitiva de la Institución Policial. ARTÍCULO 35º. 
    //   (AUXILIO MORTUORIO AL FALLECIMIENTO DEL (LA) CÓNYUGE).- Será otorgado al Titular del sector pasivo de la Policía Boliviana, al fallecimiento de su cónyuge, que 
    //   cumplan con lo determinado en el inciso b) del Artículo 47 del presente Reglamento, en función al último grado que ostentaba  el Titular al momento de su 
    //   desvinculación definitiva de la Institución Policial. ARTÍCULO 36º. (AUXILIO MORTUORIO AL FALLECIMIENTO DEL (LA) VIUDO (A). Será otorgado a los (las) 
    //   derechohabientes del (la) Viudo (a), que cumplan con lo determinado en el inciso c) del Artículo 47 del presente Reglamento, en función al último grado que 
    //   ostentaba el (la) Titular miembro de la Policía Boliviana al momento de su desvinculación definitiva.”</i>, establece la definición del beneficio de Auxilio Mortuorio 
    //   y en que consiste las modalidades para la otorgación del mismo.
    //   <br><br>
    //   Que, el Artículo 38 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio refiere: <i>“(COTIZACIONES NECESARIAS PARA ACCEDER AL AUXILIO MORTUORIO).- I. Según 
    //   lo determinado por el Estudio Matemático Actuarial 2021 – 2025, para la otorgación del beneficio del Auxilio Mortuorio, el Titular deberá contar al menos un 
    //   (1) aporte anterior al fallecimiento o con la firma de Compromiso de Pago de aportes para dicho beneficio (en caso de encontrarse en trámite la pensión de vejez).
    //   II. En caso de no acreditarse cotizaciones continuas anteriores al deceso del (la) Titular, de su Cónyuge o Viuda o a la inexistencia del compromiso de pago de aportes, 
    //   no corresponderá la otorgación del beneficio”</i>, se verifica que el presente tramite cuenta con las cotizaciones necesarias para su procesamiento.
    //   <br><br>';
    // }else{
    //   $considering_two.='Que, los Artículos 22, 23, 24, 25 y 26 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio refieren: “ARTÍCULO 22. <i>(DEFINICIÓN).- Para efectos del presente Reglamento y conforme lo dispuesto por el Decreto Supremo N°5007 de 23 de agosto de 2023, 
    //   que modifica el parágrafo I del Artículo 16 del Decreto Supremo N°1446 de 19 de diciembre de 2012, se define el beneficio de la siguiente manera: Cuota Mortuoria.- Es el beneficio económico que se otorga a los (las) derechohabientes de los miembros del sector activo 
    //   de la Policía Boliviana, Afiliados (as) a la Mutual de Servicios al Policía - MUSERPOL, destinado a cubrir los gastos emergentes del fallecimiento del (la) Titular y de su Cónyuge, que se hará efectivo con el pago de un monto único y por una sola vez.</i> ARTÍCULO 23º. 
    //   <i>(MODALIDADES DE CUOTA MORTUORIA).- El beneficio de Cuota Mortuoria, será otorgado en las siguientes modalidades: a) Cuota Mortuoria al Fallecimiento del (la) Titular en Cumplimiento de Funciones. b) Cuota Mortuoria al Fallecimiento del (la) Titular por Riesgo Común. 
    //   c) Cuota Mortuoria al Fallecimiento del (la) Cónyuge.</i> ARTÍCULO 24º. <i>(CUOTA MORTUORIA AL FALLECIMIENTO DEL (LA) TITULAR EN CUMPLIMIENTO DE FUNCIONES).- I. Será otorgado a los (las) derechohabientes debidamente acreditados (as), del (la) Efectivo Policial que hubiese 
    //   fallecido en cumplimiento de sus funciones, que cumplan con lo determinado en el inciso a) del Artículo 46 del presente reglamento. II. La determinación del tipo de fallecimiento, deberá ser verificada a través del dictamen de calificación emitido por la Entidad 
    //   Encargada de Calificar o certificación emitida por la Dirección Nacional de Salud y Bienestar Social de la Policía Boliviana. III. En caso de no presentar el Dictamen de Calificación o Certificación emitida por la Dirección Nacional de Salud y Bienestar Social de 
    //   la Policía Boliviana, se determinará la modalidad como fallecimiento del (la) Titular por Riesgo Común.</i> ARTÍCULO 25º. <i>(CUOTA MORTUORIA AL FALLECIMIENTO DEL (LA) TITULAR POR RIESGO COMÚN).- Este beneficio, será otorgado a los (las) derechohabientes debidamente 
    //   acreditados (as), del (la) Efectivo Policial que hubiese fallecido por cualquier causa no vinculada al cumplimiento de sus funciones, que cumplan con lo determinado en el inciso b) del Artículo 46 del presente reglamento. ARTÍCULO 26º. (CUOTA MORTUORIA AL FALLECIMIENTO 
    //   DEL (LA) CÓNYUGE). - Este beneficio, será otorgado al Titular Policía del sector activo de la Policía Boliviana, al fallecimiento de su Cónyuge previo cumplimiento de lo determinado en el inciso c) del Artículo 46 del presente reglamento”,</i> establece la definición del 
    //   beneficio de Cuota Mortuoria y en que consiste las modalidades para la otorgación del mismo.
    //   <br><br>
    //   Que, el Artículo 28 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio refiere: <i>“(COTIZACIONES NECESARIAS PARA ACCEDER AL BENEFICIO).- I. Para acceder a la otorgación del beneficio de la Cuota Mortuoria, según las modalidades determinadas en los Artículos 24, 25 y 26 
    //   del presente Reglamento, es necesario verificar una cantidad de al menos doce (12) cotizaciones continuas anteriores al fallecimiento del (la) Efectivo Policial o de su Cónyuge. II. En caso de no acreditarse las doce (12) cotizaciones continuas anteriores al deceso del (la) 
    //   Efectivo Policial o de su Cónyuge, no corresponderá la otorgación del beneficio”,</i> se verifica que el presente tramite cuenta con las cotizaciones necesarias para su procesamiento.
    //   <br><br>';
    // }
    // $considering_two.='Que, el Artículo 42 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio refiere: <i>”(PRESENTACIÓN DE TRÁMITES).- I. Previo a la presentación de requisitos 
    // para el inicio de la solicitud de otorgación de los Beneficios de Cuota Mortuoria y Auxilio Mortuorio, el personal encargado de la recepción deberá verificar la existencia de 
    // aportes según lo determinado en los Artículos 28 y 38 del presente reglamento. II. La recepción de solicitudes de los beneficios de Cuota Mortuoria y Auxilio Mortuorio, se 
    // efectuará en Ventanilla de Atención al Afiliado de la ciudad de La Paz u Oficinas Regionales, verificándose la presentación de la documentación integra y legible de acuerdo a 
    // lo referido en los Artículos 46 y 47 del presente Reglamento, en función a la modalidad del beneficio; en caso de identificar algún documento faltante u observado no se procederá 
    // con la recepción. III. La documentación presentada en fotocopias simples en el proceso de trámite de los beneficios de Cuota Mortuoria y Auxilio Mortuorio, deberá ser verificada 
    // con los originales por el profesional que realice el registro. IV. Toda documentación adjunta, debe estar debidamente foliada y será incorporada en el folder del beneficio, el que 
    // será denominado posteriormente como “Expediente”. V. Cuando el (la) solicitante no firme el Formulario de Recepción con carácter de Declaración Jurada, deberá imprimir sus huellas 
    // digitales en presencia de un (1) testigo mayor de edad, quien firmará el documento en constancia de conformidad”</i>, establece los parámetros de presentación de los documentos para 
    // acceder a la otorgación del beneficio.
    // <br><br>';
    // if ($quota_aid->procedure_modality->procedure_type_id == 4) {
    //     $considering_two.='Que, el Artículo 47 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio refiere: “(REQUISITOS PARA SOLICITAR EL BENEFICIO DE AUXILIO MORTUORIO).-<i> I. Los trámites
    //     y solicitudes para el pago del beneficio de Auxilio Mortuorio, que ingresen a partir de la aprobación del presente Reglamento, deberań contener los siguientes documentos: ';
    //     if ($quota_aid->procedure_modality_id == 14) {
    //     $considering_two.= ' <b>b) Auxilio Mortuorio al Fallecimiento de la o del Cónyuge.</b> <b>1.</b> Comprobante de depósito o de transferencia por concepto de adquisición de folder y formularios en la cuenta fiscal de la MUSERPOL. <b>2.</b> Formulario de verificación de requisitos con carácter de Declaración Jurada y solicitud, a ser otorgado por la MUSERPOL, a momento de inicio del trámite. <b>3.</b> Fotocopia simple de la Cédula de Identidad del (la) Titular. <b>4.</b> Certificado original y actualizado de defunción del (la) Cónyuge. <b>5.</b> Fotocopia simple de la Cédula de Identidad del (la) Cónyuge. <b>6.</b> Certificado original y actualizado de matrimonio, o certificado original y actualizado de unión libre o de hecho emitido por el "SERECI", </i>.';
    //     }elseif ($quota_aid->procedure_modality_id == 15) {
    //          $considering_two.=' <b>c) Auxilio Mortuorio al Fallecimiento del (la) Viudo.</b> <b>1.</b> Comprobante de depósito o de transferencia por concepto de adquisición de folder y formularios en la cuenta fiscal de la MUSERPOL. <b>2.</b> Formulario de verificación de requisitos con carácter de Declaración Jurada y solicitud, a ser otorgado por la MUSERPOL, a momento de inicio del trámite. <b>3.</b> Fotocopia simple de la Cédula de Identidad del (la) Viudo (a). <b>4.</b> Certificado original y actualizado de defunción del (la) Viudo (a). <b>5.</b> Fotocopia simple y vigente de la Cédula de Identidad del (los) derechohabiente (s). <b>6.</b> Certificado original y actualizado de matrimonio o certificado original y actualizado de unión libre o de hecho emitido por el SERECI. <b>7.</b> Declaratoria de Herederos o Aceptación de Herencia, original o copia legalizada; en el caso de herederos por sucesión testamentaria presentar “Testamento” original o copia legalizada, dentro del cual señale expresamente la otorgación del beneficio”, </i>. ';
    //       }else{
    //          $considering_two.=' <b>a) Auxilio Mortuorio al Fallecimiento del (la) Titular.</b> <b>1.</b>  Comprobante de depósito o de transferencia por concepto de adquisición de folder y formularios en la cuenta fiscal de la MUSERPOL. <b>2.</b> Formulario de verificación de requisitos con carácter de Declaración Jurada y solicitud, a ser otorgado por la MUSERPOL a momento de inicio del trámite. <b>3.</b> Fotocopia simple de la Cédula de Identidad del (la) Titular. <b>4.</b> Certificado original y actualizado de defunción del (la) Titular. <b>5.</b> Fotocopia simple y vigente de la Cédula de Identidad de los derechohabientes. <b>6.</b> Certificado original y actualizado de matrimonio, o certificado original y actualizado de unión libre o de hecho emitido por el SERECI.  En caso que el efectivo policial no hubiese contraído nupcias, deberá adjuntar el certificado de inexistencia de partida matrimonial emitido por el SERECI en original. <b>7.</b> Declaratoria de Herederos o Aceptación de Herencia, original o copia legalizada;  en  el  caso  de  herederos  por  sucesión  testamentaria presentar “Testamento” original o copia legalizada, dentro del cual señale expresamente la otorgación del beneficio (…)”</i>.';
    //       }
          // $considering_two.=' Las solicitudes para el pago del beneficio de Auxilio Mortuorio, que ingresen a partir de la aprobación del presente Reglamento deberán contener los referidos documentos: por tanto, al verificarse la documentación adjunta a la solicitud presentada, se determina el cumplimiento del mismo.<br><br>';
    // }else{//cuota mortuoria
    //       $considering_two.='Que, el Artículo 46 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio refiere: <i>“(REQUISITOS PARA SOLICITAR EL BENEFICIO DE CUOTA MORTUORIA).- Las solicitudes para el pago del beneficio de Cuota Mortuoria, que ingresen a partir de la aprobación del presente Reglamento deberán contener los siguientes documentos:';
    //         if ($quota_aid->procedure_modality_id == 8) { //cumplimiento de susfunciones
    //           $considering_two.='<b> a) Cuota Mortuoria al Fallecimiento del (la) Titular en Cumplimiento de Funciones:</b> <b>1.</b> Comprobante de depósito o de transferencia por concepto de adquisición de folder y formularios en la cuenta fiscal de la MUSERPOL. <b>2.</b> Formulario de verificación de requisitos con carácter de Declaración Jurada y solicitud, a ser otorgado por la MUSERPOL, a momento de inicio del trámite. <b>3.</b> Fotocopia simple de la Cédula de Identidad del (la) Titular. <b>4.</b> Certificado original y actualizado de defunción. <b>5.</b> Fotocopia simple y vigente de la Cédula de Identidad de los derechohabientes. <b>6.</b> Certificado original y actualizado de matrimonio, o certificado original y actualizado de unión libre o de hecho emitido por el "SERECI" o Resolución original o copia legalizada de reconocimiento de matrimonio de hecho ante autoridad competente. En caso que el efectivo policial no hubiese contraído nupcias, deberá adjuntar el certificado de inexistencia de partida matrimonial emitido por el SERECI en original. <b>7.</b> Certificado original y actualizado de descendencia del (la) Titular fallecido (a), emitido por el SERECI. Este documento, al tener una validez de treinta (30) días, debe estar plenamente vigente a momento de la presentación y/o recepción de la documentación. <b>8.</b> Declaratoria de herederos o Aceptación de Herencia, original o copia legalizada; en el caso de herederos por sucesión Testamentaria presentar “Testamento” original o copia legalizada, dentro del cual señale expresamente la otorgación del beneficio. <b>9.</b> Certificado de Años de Servicio desglosado, en original o copia legalizada otorgado por el Comando General de la Policía Boliviana <b>10.</b> Dictamen de calificación original o copia legalizada emitido por la Entidad Encargada de Calificar o Certificación emitida por la Dirección Nacional de Salud y Bienestar Social de la Policía Boliviana”.';
    //         }else{ //riesgo comun
    //           $considering_two.='<b> b) Cuota Mortuoria al Fallecimiento del (la) Titular por Riesgo Común </b> <b>1.</b> Comprobante de depósito o de transferencia por concepto de adquisición de folder y formularios en la cuenta fiscal de la MUSERPOL. <b>2.</b> Formulario de verificación de requisitos con carácter de Declaración Jurada y solicitud, a ser otorgado por la MUSERPOL, a momento de inicio del trámite. <b>3.</b> Fotocopia simple de la Cédula de Identidad del (la) titular. <b>4.</b> Certificado original y actualizado de defunción del (la) Titular. <b>5.</b> Fotocopia simple y vigente de la Cédula de Identidad de los derechohabientes. <b>6.</b> Certificado original y actualizado de matrimonio, o certificado original y actualizado de unión libre o de hecho emitido por el "SERECI". En caso que los efectivos policiales no hubiese contraído nupcias, deberá adjuntar el certificado de inexistencia de partida matrimonial emitido por el SERECI en original. <b>7.</b> Certificado original y actualizado de descendencia del (la) titular fallecido (a), emitido por el SERECI. Este documento, al tener una validez de treinta (30) días, debe estar plenamente vigente a momento de la presentación y/o recepción de la documentación. <b>8.</b> Declaratoria de herederos o Aceptación de Herencia, original o copia legalizada; en el caso de herederos por sucesión testamentaria presentar “Testamento” original o copia legalizada, dentro del cual señale expresamente la otorgación del beneficio. <b>9.</b> Certificado de Años de Servicio desglosado, emitido por el Comando General de la Policía Boliviana”,';
    //         }
    //         // $considering_two.='</i> por tanto, al verificarse la documentación adjunta a la solicitud presentada, se determina el cumplimiento del mismo.<br><br>';
    // }

    // $considering_two.='</i> por tanto, al verificarse la documentación adjunta a la solicitud presentada, se determina el cumplimiento del mismo.<br><br>';

    if ($quota_aid->procedure_modality->procedure_type_id == 4) {
      $considering_two .= 'Que, el Artículo 32, el Reglamento de los Beneficios de Cuota Mortuoria y Auxilio  Mortuorio, reﬁeren:<i> "(DEFINICIÓN): Auxilio Mortuorio.- Es el beneﬁcio económico que se otorga a los (las) derechohabiente (s) de
      los miembros del sector pasivo de la Policía Boliviana aﬁliados a la Mutual de Servicios al Policía - MUSERPOL, destinados a los gastos emergentes del fallecimiento del (la) Titular, Cónyuge o Viudo (a) que se hará 
      efectivo con el pago de un monto único y por una sola vez"</i>.
      <br><br>';
    }else{
      $considering_two .= 'Que, el Artículo 22, del Reglamento de Cuota Mortuoria y Auxilio  Mortuorio, reﬁeren: (DEFINICIÓN): Cuota Mortuoria.- Es el beneﬁcio económico que se otorga a los (las)
      derechohabiente (s) de los miembros del sector activo de la Policía Boliviana, aﬁliados (as) a la Mutual de Servicios al Policía - MUSERPOL, destinados a los gastos emergentes del fallecimiento del (la) 
      Titular y de su cónyuge que se hará efectivo con el pago de un monto único y por una sola vez.
      <br><br>';
    }
    // $considering_two.='Que los Artículos 51 y 52 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio refieren: <i>“ARTICULO 51 (ROGACIÓN).- La actuación de la Mutual de Servicios al Policía – MUSERPOL 
    // se inicia a partir de la presentación de la solicitud formal por parte del Titular o derechohabiente (s) para acceder a los beneficios de Cuota Mortuoria y Auxilio Mortuorio. La Mutual de Servicios 
    // al Policía - MUSERPOL reconoce el derecho del (los) beneficiario (s) a partir de la fecha en la cual el (la) Afiliado (a) o derechohabiente (s) presenta (n) su solicitud formal por Ventanilla de Atención 
    // al Afiliado en la ciudad de La Paz u Oficinas Regionales, para el correspondiente procesamiento del trámite, cumpliendo con los requisitos de orden establecidos en el presente Reglamento. ARTÍCULO 52°. 
    // (PROCEDIMIENTO).- I. Para la otorgación de los beneficios de Cuota Mortuoria y Auxilio Mortuorio, el plazo de procesamiento será de quince (15) días hábiles, de acuerdo al siguiente procedimiento: 1. 
    // Admitido el trámite en Ventanilla de Atención al Afiliado, con todos los requisitos establecidos en el presente Reglamento, se derivará el expediente al Área de Archivo y Gestión Documental de Beneficios 
    // Económicos. 2. El Área de Archivo y Gestión Documental de Beneficios Económicos, emitirá la certificación de existencia de antecedentes o expediente del (la) Titular e iniciará la foliación de la documentación 
    // que debe ser continuada por cada una de las áreas intervinientes en el procedimiento. 3. Con la certificación de Archivo, el trámite será remitido al Área Legal, para verificar y validar la documentación presentada 
    // por el (la) solicitante, conforme lo establecido en el presente Reglamento. (…)”</i>, establece la prosecución del trámite después de la recepción de la carpeta disponiendo el procesamiento de acuerdo a las funciones 
    // de cada Área que conforman la Unidad de Fondo de Retiro Policial Solidario.
    // <br><br>';
    // if ($quota_aid->procedure_modality->procedure_type_id == 4) {
    //   $considering_two.='Que, el Artículo 57 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio refiere: <i>”(CUANTÍA DEL BENEFICIO DE AUXILIO MORTUORIO).- 
    //   Los beneficiarios que inicien trámite de Auxilio Mortuorio, según lo establecido en los artículos 34, 35, 36 y 37 del presente Reglamento, percibirán 
    //   un pago correspondiente a la cuantía determinada por el Estudio Matemático Actuarial 2021 – 2025, tomando en cuenta el grado que ostentaba el o la servidor 
    //   (a) público policial al momento de su desvinculación definitiva de la institución policial; la cuantía del beneficio será distribuido de acuerdo a lo establecido 
    //   en el Código Civil y normas conexas. (…)”</i>, por tanto a través de la Certificación de Calificación se determina el monto de pago a favor del titular o derechohabientes.
    //   <br><br>';
    // }else{
    //   $considering_two.='Que, el Artículo 56 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio refiere: <i>(CUANTÍA DEL BENEFICIO DE CUOTA MORTUORIA).- Los beneficiarios que inicien trámite de Cuota Mortuoria 
    //   según lo establecido en los artículos 24, 25 y 26 del presente Reglamento, percibirán un pago único correspondiente a la cuantía determinada por el Estudio Matemático Actuarial 2021 – 2025, tomando en cuenta 
    //   el grado que ostentaba el o la servidor público policial al momento del fallecimiento; la cuantía del beneficio será distribuido de acuerdo a lo establecido en el Código Civil y normas conexas. (…)”</i>, por tanto 
    //   a través de la Certificación de Calificación se determina el  monto de pago a favor del titular o derechohabientes. <br><br>';
    // }
    $considering_two.='Que, el Artículo 59 del Reglamento de los Beneficios de Cuota Mortuoria y Auxilio Mortuorio refiere:<i> “(DEFINICIÓN Y CONFORMACIÓN), Parágrafo I: “La Comisión de Beneficios Económicos, 
    es la instancia técnica y legal que mediante acto administrativo determina la otorgación de los beneficios de Cuota Mortuoria y Auxilio Mortuorio. Es designada mediante Resolución Administrativa
    de la Dirección General Ejecutiva de la Mutual de Servicios al Policía - MUSERPOL”</i>. Por consiguiente, la Resolución Administrativa Nº 002/2024 del 02 de enero de 2024, conforma la Comisión de 
    Beneficios Económicos, en cumplimiento al Reglamento.
    <br><br>
    Que el Artículo 60 el Reglamento de los Beneficios de Cuota Mortuoria y Auxilio Mortuorio refiere: <i>“(ATRIBUCIONES). La Comisión de Beneficios Económicos tiene las siguientes atribuciones: a. Determinar la otorgación de los beneficios. 
    b. Determinar la otorgación de montos dejados en cuota parte en reserva. c. Resolver los Recursos de Revocatoria. d. Otros, atribuibles dentro de su competencia. Para efectivizar lo establecido, emitirá 
    Resolución, Auto o informe según corresponda”,</i> es así que la comisión de beneficios económicos en consideración de todos los antecedentes y la documentación adjunta a la presentación del trámite y certificaciones 
    de las diferentes áreas de la Unidad de Otorgación de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, se emite la presente Resolución.';

    // ---RECEPTION--- //
    $body_reception = "";
    $reception_id = 33;
    $reception = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', $reception_id)->first();
    
    $body_reception = "Que, mediante Formulario de Recepción de ventanilla de atención al afiliado de la Unidad de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, se registra el trámite 
    N° " .$reception->code. " de fecha ". Util::getStringDate($reception->date) . ", debiéndose aplicar el reglamento vigente a la fecha de presentación de la solicitud formal, en virtud del principio 
    de rogación establecido en el Art. 51 el reglamento de los Beneficios de Cuota Mortuoria y Auxilio Mortuorio.";
    //---END RECEPTION--- //
    ///---FILE---///
    $body_file = "";
    $file_id = 34;
    $file = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', $file_id)->first();

    $body_file .= "Que, mediante Certificación N° " . $file->code . ", del Área de Archivo y Gestión Documental de la Dirección de Beneficios Económicos de fecha " . Util::getStringDate($file->date) . ",  se establece que el trámite signado con el N° " . $quota_aid->code . " ";
    $discount = $quota_aid->discount_types();
      $folder = AffiliateFolder::where('affiliate_id', $affiliate->id)->get();
      if ($folder->count() > 0) {
        $body_file .= "si ";
      } else {
        $body_file .= "no ";
      }
      $body_file .= "tiene expediente del referido titular. ";
    ///---ENDIFLE--////
    /////----FINANCE----///revisar
    $discount = $quota_aid->discount_types();
    $finance = $discount->where('discount_type_id', '1')->first();
    $body_finance = "";
    $body_finance = "Que, mediante nota de respuesta " . ($finance->pivot->code ?? 'sin cite') . " de la Dirección de Asuntos Administrativos de fecha " . Util::getStringDate(($finance->pivot->date ?? '')) . ", refiere que " . ($affiliate->gender == 'M' ? "el" : "la") . " titular del beneficio ";
    if (isset($finance->id) && $finance->amount > 0) {
      $body_finance .= "si cuenta con registro de pagos o anticipos por concepto de Fondo de Retiro Policial en el monto de " . Util::formatMoneyWithLiteral(($finance->pivot->amount ?? 0)) . ".";
    } else {
      $body_finance .= "no cuenta con registro de pagos o anticipos por concepto de " . $quota_aid->procedure_modality->procedure_type->name . ", por tanto se encuentra habilitado para la continuidad del trámite.";
    }
    /////----END FINANCE---////
    ////-----LEGAL REVIEW ----////
    $body_legal_review   = "";
    $legal_review_id = 35;
    $legal_review = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', $legal_review_id)->first();
    $body_legal_review .= "Que, mediante Certificación N° " . $legal_review->code . " del Área Legal de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha " . Util::getStringDate($legal_review->date) . ", fue verificada y validada la documentación presentada por " . ($quota_aid->procedure_modality_id != 14 ? "los beneficiarios" : ($affiliate->gender == "M" ? "el titular" : "la titular")) . " del trámite signado con el N° " . $quota_aid->code . ", cumpliendo con los requisitos conforme a normativa legal.";
    /////-----END LEGAL REVIEW----///
    
    ///------ INDIVIDUAL ACCCOUTNS ------////
    $body_accounts = "";
    $accounts_id = 36;
    $accounts = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', $accounts_id)->first();
    $body_accounts = "Que, mediante Certificación de Aportes N° " . $accounts->code . " del Área de Cuentas Individuales de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha " . Util::getStringDate($accounts->date) . ", se determinó los aportes correspondientes, de acuerdo a la información obtenida en la base de datos que cuenta la Institución.";
    ////-------END INDIVIDUAL ACCOUTNS ------////
    //----- QUALIFICATION -----////
    $body_qualification = "";
    $qualification_id = 37;
    $qualification = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', $qualification_id)->first();
    $months  = $affiliate->getTotalQuotes();
    $body_qualification .=  'Que, mediante Certificación ' . $qualification->code . " del Área de Calificación de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio,  de fecha " . Util::getStringDate($qualification->date) . ',  en aplicación del Estudio Matemático Actuarial 2021 – 2025 y el Reglamento de los Beneficios de Cuota Mortupria y Auxilio Mortuorio que establecen la cuantía al <b>'. $quota_aid->procedure_modality->name .'</b> ';
      if ($quota_aid->procedure_modality_id == 14 || $quota_aid->procedure_modality_id == 15 ) {
        $body_qualification.= $spouse_full_name;
      } else {
        $body_qualification.= $affiliate_full_name; //hacer
      }
      if($quota_aid->procedure_modality_id != 14 && $quota_aid->procedure_modality_id != 8 ){
        $body_qualification.=' considerando que el (la) afiliado (a) cuenta con '.$quota_aid->number_qualified_contributions.' aportes realizados para dicho beneficio en la escala de '.($quota_aid->quota_aid_procedure->months_min??0).' – '.(($quota_aid->quota_aid_procedure->months_max)==1200?'En adelante':($quota_aid->quota_aid_procedure->months_max??0));
      }
    $body_qualification.=", corresponde el monto de pago de <b> " . Util::formatMoneyWithLiteral($quota_aid->total) . "</b>.";
    //-----END QUALIFICATION -----////
    ////-----HEADSHIP ----////
    $body_headship  = "";
    $legal_review_id = 38;
    $headship_review = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', $legal_review_id)->first();
    $body_headship .= "Que, mediante Certificación de Revisión N° " . $headship_review->code . " de " . Util::getStringDate($headship_review->date) ." emitido por la Jefatura de la Unidad de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, se verifica el cumplimiento de todos los procedimientos conforme lo establecido en la normativa vigente.";
    ////-----END HEADSHIP----////
     
    $considering_three = $body_reception.'<br><br>'.$body_file.'<br><br>'.$body_finance.'<br><br>'.$body_legal_review.'<br><br>'.$body_accounts.'<br><br>'.$body_qualification.'<br><br>'.$body_headship;
    /////---------///
    $art = [
      '8' => '46 inciso <b>a)</b> Cuota Mortuoria al fallecimiento del (la) titular en Cumplimiento de Funciones',
      '9' => '46 inciso <b>b)</b> Cuota Mortuoria al fallecimiento del (la) titular por Riesgo Común',
      '13' => '47 inciso <b>a)</b> Auxilio Mortuorio al fallecimiento del (la) titular',
      '14' => '47 inciso <b>b)</b> Auxilio Mortuorio al fallecimiento de la o del Cónyuge',
      '15' => '47 inciso <b>c)</b> Auxilio Mortuorio al fallecimiento del (la) viudo (a)',
    ];
      switch ($quota_aid->procedure_modality_id) {
        case 14:
         $fallecido ='a favor del (los) beneficiario (s) '.($affiliate->spouse()->first()->gender == 'M' ? ' del ' : ' de la ').$spouse_full_name;///del / de
         break;
        case 15:
         $fallecido = 'a favor del (los) derechohabiente (s) '.($affiliate->spouse()->first()->gender == 'M' ? ' del ' : ' de la ').$spouse_full_name;
        break;
        default:
         $fallecido =' a los derechohabientes del titular Fallecido '.$affiliate_full_name;
      }
    /*$number = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', 33)->first();
       if ($number->note != '') {
        $considering_three.= $number->note . "<br><br>";
      }*/
    $then = 'Habiéndose verificado el cumplimiento de requisitos adjuntos a la carpeta según lo señalado en el Artículo '.$art[$quota_aid->procedure_modality_id].' el Reglamento de los Beneficios de Cuota Mortuoria y Auxilio Mortuorio aprobado mediante Resolución de Directorio N° 68/2023 en fecha 19 de septiembre de 2023 y efectivizado el procesamiento del trámite y conforme el Artículo 52  (Procedimiento) del referido Reglamento, corresponde dar curso al pago del beneficio
    <b>' .mb_strtoupper($quota_aid->procedure_modality->procedure_type->second_name).' - '.mb_strtoupper($quota_aid->procedure_modality->name). '</b>'.$fallecido.'.';
    
    ///----- END QUALIFICATION ----////*/
    $legal_dictum_id = 40;//Se usara el mismo cod de la resolucion
    $legal_dictum = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', $legal_dictum_id)->first();
    $number = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', 40)->first();
    $body_legal_dictum = '';
    if ($number->note != '') {
      $body_legal_dictum = $number->note . "<br>";
    }

    $then .= '<br><br>La Comisión de Beneficios Económicos de la Mutual de Servicios al Policía “MUSERPOL” en
        uso de sus facultades y en observancia el Reglamento de los Beneficios de Cuota Mortuoria y Auxilio Mortuorio:';

    $cardinal = ['PRIMERA', 'SEGUNDA', 'TERCERA', 'CUARTA', 'QUINTA'];
    $cardinal_index = 0;


    $body_resolution = "<b>" . $cardinal[$cardinal_index++] . ".-</b> Reconocer el beneficio de<strong class='uppercase'>&nbsp;" . strtoupper($quota_aid->procedure_modality->procedure_type->second_name) . "</strong> por <strong class='uppercase'>" . strtoupper($quota_aid->procedure_modality->name) . "</strong>, de
    acuerdo a Calificación de fecha&nbsp; <b>" . Util::getStringDate($qualification->date) . "</b>";
        if($quota_aid->procedure_modality_id != 14 && $quota_aid->procedure_modality_id != 8 ){
          $body_resolution.=' considerando que el afiliado cuenta con '.$quota_aid->number_qualified_contributions.' aportes realizados para dicho beneficio en la escala de '.($quota_aid->quota_aid_procedure->months_min??0).' – '.(($quota_aid->quota_aid_procedure->months_max)==1200?'En adelante':($quota_aid->quota_aid_procedure->months_max??0));
        }
    $body_resolution .=", determinando el monto de <b>" . Util::formatMoneyWithLiteral($quota_aid->total) . "</b>.";
    $body_resolution .= "<br><br><b>" . $cardinal[$cardinal_index++] . ".-</b> El monto de la CUANT&Iacute;A a pagar de&nbsp; <strong>" . Util::formatMoneyWithLiteral($quota_aid->total) . "</strong>, a favor ";


    $beneficiaries_count = QuotaAidBeneficiary::where('quota_aid_mortuary_id', $quota_aid->id)->count();

    if ($quota_aid->procedure_modality_id != 14) {

      if ($quota_aid->procedure_modality_id == 15) {
        logger($applicant->gender);
        $body_resolution .= ($beneficiaries_count > 1 ? "de los beneficiarios " : ($applicant->gender == 'M' ? "del beneficiario " : "de la beneficiaria ")) . ($affiliate->spouse()->first()->gender == 'M' ? "del Sr. " : "de la Sra. ") . Util::fullName($affiliate->spouse()->first()) . " con C.I. N° " . $affiliate->spouse()->first()->identity_card . "., en el siguiente tenor: <br><br>";
      } else {
        $body_resolution .= ($beneficiaries_count > 1 ? "de los beneficiarios " : ($applicant->gender == 'M' ? "del beneficiario " : "de la beneficiaria ")) . ($affiliate->gender == 'M' ? "del " : "de la ") . $affiliate->fullNameWithDegree() . " con C.I. N° " . $affiliate->identity_card . ", de la siguiente manera: <br><br>";
      }
    } else {
      $body_resolution .= ($applicant->gender == 'M' ? "del beneficiario de la <strong> &nbsp;Sra. " : "de la beneficiaria del <strong> &nbsp;Sr. ") . Util::fullName($affiliate->spouse()->first()) . "</strong> con C.I. N° <strong>" . $affiliate->spouse()->first()->identity_card . "</strong>, de la siguiente manera: <br><br>";
    }
    $reserved = false;
    if ($quota_aid->procedure_modality_id != 14) {
      $beneficiaries = QuotaAidBeneficiary::where('quota_aid_mortuary_id', $quota_aid->id)->orderBy('kinship_id')->orderByDesc('state')->get();
      foreach ($beneficiaries as $beneficiary) {
        if (!$beneficiary->state && !$reserved) {
          $reserved = true;
          $reserved_quantity = QuotaAidBeneficiary::where('quota_aid_mortuary_id', $quota_aid->id)->where('state', false)->count();
          $certification = $beneficiary->testimonies()->first();
          $body_resolution .= "Mantener en reserva la(s) Cuota(s) Parte(s) salvando derechos, hasta que presente(n) la correspondiente Declaratoria de Herederos o Aceptación de Herencia y demás requisitos establecidos del Reglamento de Cuota Mortuoria y Auxilio Mortuorio, de la siguiente manera:<br><br>";
        }
        //return $beneficiary;
        $body_resolution .= "<li class='text-justify'>";
        if (Util::isChild($beneficiary->birth_date)) {
          $body_resolution .= 'Menor ';
        } else {
          $body_resolution .= $beneficiary->gender == 'M' ? 'Sr. ' : 'Sra. ';
        }
        $body_resolution .= $beneficiary->fullName();
        if (Util::isChild($beneficiary->birth_date) && !$beneficiary->state) {
          $body_resolution .= ', a través de su'.($affiliate->gender == 'F' ? ' padre' : ' madre').', tutor (a) o hasta que cumpla la mayoría de edad';
        }
        if ($beneficiary->identity_card)
          $body_resolution .= " con C.I. N° " . $beneficiary->identity_card;
        $beneficiary_advisor = QuotaAidAdvisorBeneficiary::where('quota_aid_beneficiary_id', $beneficiary->id)->first();
        if (isset($beneficiary_advisor->id)) {
          $advisor = QuotaAidAdvisor::where('id', $beneficiary_advisor->quota_aid_advisor_id)->first();
          $body_resolution .= ', en el monto de<strong> ' . Util::formatMoneyWithLiteral($beneficiary->paid_amount) . '</strong> ' . ', en calidad de ' . $beneficiary->kinship->name.( $reserved?'':(' a través'. ($advisor->gender == 'M' ? ' del Sr.' : ' de la Sra.') . ' '  . Util::fullName($advisor) . ' con C.I. N°' . $advisor->identity_card . ($affiliate->gender == 'F' ? ' padre' : ' madre').' del menor') ).'.</li><br>';
        }else{
          $body_resolution .= ', en el monto de<strong> ' . Util::formatMoneyWithLiteral($beneficiary->paid_amount) . '</strong> ' . ', en calidad de ' . $beneficiary->kinship->name . ".</li><br>";}
         $beneficiary_legal_guardian = QuotaAidBeneficiaryLegalGuardian::where('quota_aid_beneficiary_id', $beneficiary->id)->first();
       /* if (false && isset($beneficiary_legal_guardian->id)) {
          $legal_guardian = QuotaAidLegalGuardian::where('id', $beneficiary_legal_guardian->quota_aid_legal_guardian_id)->first();
          $body_resolution .= " por si o representada legamente por " . ($legal_guardian->gender == 'M' ? "el Sr." : "la Sra. ") . " " . Util::fullName($legal_guardian) . " con C.I. N° " . $legal_guardian->identity_card . 
                    conforme establece la Escritura Pública sobre Testimonio de Poder especial, amplio y suficiente N° " . $legal_guardian->number_authority . " de " . Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority)) . " emitido por " . $legal_guardian->notary . ".";
        }*/

      }
    } else {
      $body_resolution .= "<li class='text-justify'>" . $affiliate->degree->shortened . " " . $affiliate->fullName() . " con C.I. N° " . $affiliate->identity_card . "., el monto de &nbsp;<strong>" . Util::formatMoneyWithLiteral($quota_aid->total) . ".</strong></li><br><br>";
    }

    $body_resolution .= "<b>REGISTRESE, NOTIFIQUESE Y ARCHIVESE.</b><br><br><br><br><br>";



    $user = User::find($number->user_id);
    $body_resolution .= "<div class='text-xs italic'>cc. Arch.<br>CONTABILIDAD<br>COMISIÓN</div>";

    $users_commission = User::where('is_commission', true)->get();
    $data = [
      'quota_aid'   =>  $quota_aid,
      'correlative'   =>  $number,
      'ret_fun' => $quota_aid,
      'affiliate' =>  $affiliate,
      'actual_city'  =>  Auth::user()->city->name ?? '',
      'actual_date'  =>  Util::getStringDate($number->date),
      'body_qualification'    =>  $body_qualification,
      'then'  =>  $then,
      'user'  =>  $user,
      'person'    =>  $person,
      'considering_one'   =>$considering_one,
      'considering_two'   =>$considering_two,
      'considering_three' =>$considering_three,
      'body_resolution'   =>  $body_resolution,
      'users_commission'  =>  $users_commission,
      'body_legal_dictum' =>  $body_legal_dictum,
    ];
   $bar_code = \DNS2D::getBarcodePNG($this->get_module_quota_aid_mortuary($quota_aid->id), "QRCODE");
   //$bar_code = \DNS2D::getBarcodePNG(($quota_aid->getBasicInfoCode()['code'] . "\n\n" . $quota_aid->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
   $headerHtml = view()->make('ret_fun.print.legal_header')->render();
   $footerHtml = view()->make('quota_aid.print.resolution_footer', ['quota_aid' => $quota_aid,  'bar_code' => $bar_code])->render();
    return \PDF::loadView('quota_aid.print.legal_resolution', $data)
      ->setOption('encoding', 'utf-8')
      ->setOption('footer-html', $footerHtml)
      ->setOption('header-html', $headerHtml)
      ->setOption('margin-top', 40)
      ->setOption('margin-bottom', 35)
      ->stream("jefaturaRevision.pdf");
  }
}
