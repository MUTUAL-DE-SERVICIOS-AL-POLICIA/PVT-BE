<?php

namespace Muserpol\Http\Controllers;

use Muserpol\RetirementFundCertification;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\Kinship;
use Muserpol\Models\City;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\RetirementFund\RetFunSubmittedDocument;
use Muserpol\Models\RetirementFund\RetFunBeneficiary;

use Muserpol\Models\RetirementFund\RetFunAdvisor;
use Muserpol\Models\RetirementFund\RetFunIncrement;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Session;
use Auth;
use DB;
use Validator;
use Muserpol\Models\Address;
use Muserpol\Models\Spouse;
use Muserpol\Models\RetirementFund\RetFunLegalGuardian;
use Muserpol\Models\RetirementFund\RetFunAdvisorBeneficiary;
use Muserpol\Models\RetirementFund\RetFunLegalGuardianBeneficiary;
use Muserpol\Models\AffiliateFolder;
use DateTime;
use Muserpol\User;
use Carbon\Carbon;
use Muserpol\Helpers\Util;
use Muserpol\Models\Voucher;
use Muserpol\Models\VoucherType;
use Muserpol\Models\Contribution\ContributionCommitment;
use Muserpol\Models\Contribution\Contribution;
use Muserpol\Models\Contribution\Reimbursement;
use Muserpol\Models\Degree;
use Muserpol\Models\Contribution\ContributionType;
use Muserpol\Models\RetirementFund\RetFunCorrelative;
use Muserpol\Models\InfoLoan;
use Muserpol\Models\DiscountType;
use Muserpol\Models\Role;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\Testimony;
use Muserpol\Helpers\ID;

class RetirementFundCertificationController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \Muserpol\RetirementFundCertification  $retirementFundCertification
   * @return \Illuminate\Http\Response
   */
  public function show(RetirementFundCertification $retirementFundCertification)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \Muserpol\RetirementFundCertification  $retirementFundCertification
   * @return \Illuminate\Http\Response
   */
  public function edit(RetirementFundCertification $retirementFundCertification)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Muserpol\RetirementFundCertification  $retirementFundCertification
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, RetirementFundCertification $retirementFundCertification)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \Muserpol\RetirementFundCertification  $retirementFundCertification
   * @return \Illuminate\Http\Response
   */
  public function destroy(RetirementFundCertification $retirementFundCertification)
  {
    //
  }
  public function get_module_retirement_fund($id)
  {
      $module_id= RetirementFund::find($id)->procedure_modality->procedure_type->module->id;
      $file_name =$module_id.'/'.RetirementFund::find($id)->uuid;
      return $file_name;
  }
  public function printReception($id)
  {
    $retirement_fund = RetirementFund::find($id);
    $affiliate = $retirement_fund->affiliate;
    $degree = $affiliate->degree;
    $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
    $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
    $modality = $retirement_fund->procedure_modality->name;
    $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
    $dev_pay = ProcedureType::whereName("Devolución de Aportes")->first();//ojo cambiar 
    $article_header = $retirement_fund->procedure_modality->procedure_type_id === $dev_pay->id ?" PARA LA ":" PARA EL ";
    $article_by = $retirement_fund->procedure_modality->id==62 ? ' AL ': ' POR ';
    $title = "REQUISITOS".$article_header. mb_strtoupper($retirement_fund->procedure_modality->procedure_type->name) . $article_by . mb_strtoupper($modality);
    $legend_ret_fun = $retirement_fund->procedure_modality->procedure_type_id === 2?'De evidenciarse descuentos en periodo de disponibilidad a la(s) Letra(s) se procederá a su devolución en consideración a la Disposición Transitoria Cuarta del Reglamento de Fondo de Retiro Policial Solidario.':'';

    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', WorkflowState::where('role_id', Util::getRol()->id)->whereIn('sequence_number', [0, 1])->first()->id)->first();
    $code = $retirement_fund->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;

    $submitted_documents = RetFunSubmittedDocument::leftJoin('procedure_requirements', 'procedure_requirements.id', '=', 'ret_fun_submitted_documents.procedure_requirement_id')->where('retirement_fund_id', $retirement_fund->id)->orderBy('procedure_requirements.number', 'asc')->get();

    if($retirement_fund->procedure_modality->procedure_type_id==21)
      $article = 'PARA LA';
    elseif($article = $retirement_fund->procedure_modality->procedure_type_id==1)
      $article = 'PARA EL';
    else
      $article = 'DE';
    /*
            !!todo
            add support utf-8
        */
    $bar_code = \DNS2D::getBarcodePNG($this->get_module_retirement_fund($retirement_fund->id), "QRCODE");
    $applicant = RetFunBeneficiary::where('type', 'S')->where('retirement_fund_id', $retirement_fund->id)->first();
    $pdftitle = "RECEPCIÓN - " . $title;
    $namepdf = Util::getPDFName($pdftitle, $applicant);
    $footerHtml = view()->make('ret_fun.print.footer_qr', ['bar_code' => $bar_code])->render();

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
      'retirement_fund' => $retirement_fund,
      'legend_ret_fun'=> $legend_ret_fun,
      'article'=> $article,
    ];
    $pages = [];
    $number_pages = Util::isRegionalRole() ? 3 : 2;
    for ($i = 1; $i <= $number_pages; $i++) {
      $pages[] = \View::make('ret_fun.print.reception', $data)->render();
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
  public function printFile($id)
  {
    $affiliate = Affiliate::find($id);
    $retirement_fund = RetirementFund::where('affiliate_id', $affiliate->id)->where('ret_fun_state_id', '!=', '3')->get()->last();

    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 20)->first();
    $code = $retirement_fund->code;

    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;

    // $title = "CERTIFICACIÓN DE ARCHIVO – " . strtoupper($retirement_fund->procedure_modality->name ?? 'ERROR');
    $title = "CERTIFICACIÓN DE ARCHIVO";
    $affiliate_folders = AffiliateFolder::where('affiliate_id', $affiliate->id)->get();
    $applicant = RetFunBeneficiary::where('type', 'S')->where('retirement_fund_id', $retirement_fund->id)->first();

    /**
     * !!TODO
     *!!revisar
     */
    $cite = $number; // RetFunIncrement::getIncrement(Session::get('rol_id'), $retirement_fund->id);

    $subtitle = $cite;
    $pdftitle = "Certificación de Archivo";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    //aqui
    $bar_code = \DNS2D::getBarcodePNG($retirement_fund->getBasicInfoCode()['code'], "QRCODE");
    $footerHtml = view()->make('ret_fun.print.footer', ['bar_code' => $bar_code])->render();
    //$footerHtml = view()->make('ret_fun.print.footer', ['bar_code' => $this->generateBarCode($retirement_fund)])->render();
    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,

      'cite' => $cite,
      'subtitle' => $subtitle,
      'title' => $title,
      'retirement_fund' => $retirement_fund,
      'affiliate' => $affiliate,
      'affiliate_folders' => $affiliate_folders,
      'applicant' => $applicant,
      'unit1' => 'archivo y gestión documental<br> beneficios económicos',
    ];
    $pages = [];
    for ($i = 1; $i <= 2; $i++) {
      $pages[] = \View::make('ret_fun.print.file_certification', $data)->render();
    }
    $pdf = \App::make('snappy.pdf.wrapper');
    $pdf->loadHTML($pages);
    return $pdf->setOption('encoding', 'utf-8')
      ->setOption('margin-bottom', '15mm')
      ->setOption('footer-html', $footerHtml)
      ->stream("$namepdf");
  }
  public function printLegalReview($id)
  {
    $retirement_fund = RetirementFund::find($id);

    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 21)->first();
    $code = $retirement_fund->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;

    $title = "CERTIFICACI&Oacute;N DE DOCUMENTACI&Oacute;N PRESENTADA Y REVISADA";
    $submitted_documents = RetFunSubmittedDocument::select(
      'ret_fun_submitted_documents.id',
      'ret_fun_submitted_documents.retirement_fund_id',
      'ret_fun_submitted_documents.procedure_requirement_id',
      'ret_fun_submitted_documents.is_valid'
    )
      ->where('ret_fun_submitted_documents.retirement_fund_id', $id)
      ->leftJoin('procedure_requirements', 'ret_fun_submitted_documents.procedure_requirement_id', '=', 'procedure_requirements.id')
      ->orderBy('procedure_requirements.number', 'ASC')->get();

    $affiliate = $retirement_fund->affiliate;
    $bar_code = \DNS2D::getBarcodePNG($this->get_module_retirement_fund($retirement_fund->id), "QRCODE");
    // $footerHtml = view()->make('ret_fun.print.footer', ['bar_code' => $this->generateBarCode($retirement_fund)])->render();
    $footerHtml = view()->make('ret_fun.print.footer', ['bar_code' => $bar_code])->render();
    $cite = $number; //RetFunIncrement::getIncrement(Session::get('rol_id'), $retirement_fund->id);
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
      'retirement_fund' => $retirement_fund,
      'affiliate' => $affiliate,
      'submitted_documents' => $submitted_documents,
    ];

    $pages = [];
    for ($i = 1; $i <= 2; $i++) {
      $pages[] = \View::make('ret_fun.print.legal_certification', $data)->render();
    }
    $pdf = \App::make('snappy.pdf.wrapper');
    $pdf->loadHTML($pages);
    return $pdf->setOption('encoding', 'utf-8')
      ->setOption('margin-bottom', '15mm')
      ->setOption('footer-html', $footerHtml)
      ->stream("$namepdf");
  }
  public function printBeneficiariesQualification($id, $only_print = true)
  {
    $retirement_fund = RetirementFund::find($id);

    $title = 'INFORMACIÓN GENERAL';

    $affiliate = $retirement_fund->affiliate;
    $applicant = $retirement_fund->ret_fun_beneficiaries()->where('type', 'S')->with('kinship')->first();
    $beneficiaries = $retirement_fund->ret_fun_beneficiaries()->orderByDesc('type')->orderBy('id')->get();

    $pdftitle = "Calificación - INFORMACIÓN GENERAL";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);

    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 23)->first();
    $code = $retirement_fund->code;
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
      'retirement_fund' => $retirement_fund,
    ];
    if ($only_print) {
      return \PDF::loadView('ret_fun.print.beneficiaries_qualification', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
    }
    return $data;
  }
    //---**IMPRIMIR FORMULARIO DE SALARIO PROMEDIO COTIZABLE CALIFICACIÓN**--//
  public function printQualificationAverageSalaryQuotable($id, $only_print = true)
  {
    $retirement_fund = RetirementFund::find($id);
    $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;
    $affiliate = $retirement_fund->affiliate;

    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 23)->first();
    $code = $retirement_fund->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;

    $subtitle = $number;

    $title = "SALARIO PROMEDIO COTIZABLE";
    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,

      'title' => $title,
      'subtitle' => $subtitle,
      'retirement_fund' => $retirement_fund,
      'affiliate' => $affiliate,
      'number_contributions' => $number_contributions,
    ];
    $data = array_merge($data, $affiliate->getTotalAverageSalaryQuotable());
    if ($only_print) {
      return \PDF::loadView('ret_fun.print.qualification_average_salary_quotable', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("SalarioPromedioCotizable.pdf");
    }
    return $data;
  }
  //---**IMPRIMIR FORMULARIO DE CALIFICACIÓN**--//
  public function printDataQualification($id, $only_print = true)
  {
    $retirement_fund = RetirementFund::find($id);


    // $title = 'INFORMACIÓN TÉCNICA';
    $affiliate = $retirement_fund->affiliate;
    $applicant = $retirement_fund->ret_fun_beneficiaries()->where('type', 'S')->with('kinship')->first();
    $beneficiaries = $retirement_fund->ret_fun_beneficiaries()->orderByDesc('type')->orderBy('id')->get();
    $pdftitle = "Calificación - INFORMACIÓN TÉCNICA";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    if ($retirement_fund->procedure_modality->procedure_type_id == 1) {//PGA

      $title = 'CALIFICACIÓN DE PAGO GLOBAL POR ' . $retirement_fund->procedure_modality->name;
    }elseif ($retirement_fund->procedure_modality->procedure_type_id == 21){//DA
      $title = 'DEVOLUCIÓN DE APORTES - ' . $retirement_fund->procedure_modality->name;
    }else {//FRPS
      $title = 'CALIFICACIÓN FONDO DE RETIRO POLICIAL SOLIDARIO';
    }
    $name_procedure_type =$retirement_fund->procedure_modality->procedure_type->name;


    $group_dates = [];
    $total_dates = Util::sumTotalContributions($affiliate->getDatesGlobal());
    $dates = array(
      'dates' => $affiliate->getDatesGlobal(),
      'name' => "Años de servicio según certificación del Comando General de la Policía",
      'operator' => '**',
      'description' => "Años de servicio según certificación del Comando General de la Policía",
      'years' => $affiliate->service_years,
      'months' => $affiliate->service_months,
    );
    $group_dates[] = $dates;
    $dates = array(
      'id' => 0,
      'dates' => $affiliate->getDatesGlobal(),
      'name' => "Alta y Baja de la Policía Nacional Boliviana",
      'operator' => '**',
      'description' => "Fechas de Alta y Baja de la Policía Nacional Boliviana",
      'years' => intval($total_dates / 12),
      'months' => $total_dates % 12,
    );
    $group_dates[] = $dates;
    foreach (ContributionType::orderBy('id')->get() as $c) {
      // if($c->id != 1){
      $contributionsWithType = $affiliate->getContributionsWithType($c->id);
      if (sizeOf($contributionsWithType) > 0) {
        $sub_total_dates = Util::sumTotalContributions($contributionsWithType);
        $dates = array(
          'id' => $c->id,
          'dates' => $affiliate->getContributionsWithType($c->id),
          'name' => $c->name,
          'operator' => $c->operator,
          'description' => $c->description,
          'years' => intval($sub_total_dates / 12),
          'months' => $sub_total_dates % 12,
        );
        if ($c->operator == '-') {
          eval('$total_dates = ' . $total_dates . $c->operator . $sub_total_dates . ';');
        }
        $group_dates[] = $dates;
      }
      // }
    }

    $contributions = array(
      'contribution_types' => $group_dates,
      'years' => intval($total_dates / 12),
      'months' => $total_dates % 12
    );
    $total_quotes = $affiliate->getTotalQuotes();
    $discounts = $retirement_fund->discount_types()->where('amount', '>', 0)->get();

    $has_availability = sizeOf($affiliate->getContributionsWithType(12)) > 0;

    /*  discount combinations*/
    $array_discounts = array();
    $array = DiscountType::where('module_id', 3)->get()->pluck('id');
    $results = array(array());
    foreach ($array as $element) {
      foreach ($results as $combination) {
        array_push($results, array_merge(array($element), $combination));
      }
    }
    foreach ($results as $value) {
      $sw = true;
      if (count($discounts) > 0) {
        foreach ($value as $id) {
        //siempre tendra id
        // if (!$retirement_fund->discount_types()->find($id)) {
          if (!($retirement_fund->discount_types()->find($id)->pivot->amount > 0)) {
            $sw = false;
          }
        }

        if ($sw) {
          $temp_total_discount = 0;
          foreach ($value as $id) {
            $temp_total_discount = $temp_total_discount + $retirement_fund->discount_types()->find($id)->pivot->amount;
          }
          $name = join(' - ', DiscountType::whereIn('id', $value)->orderBy('id', 'asc')->get()->pluck('name')->toArray());
          array_push($array_discounts, array('name' => $name, 'amount' => $temp_total_discount));
        }
      }
    }
    if ($retirement_fund->procedure_modality->procedure_type_id == 1) {//PGA
      $title = 'CALIFICACIÓN DE PAGO GLOBAL POR ' . $retirement_fund->procedure_modality->name;
    }elseif ($retirement_fund->procedure_modality->procedure_type_id == 21){//DA
      $title = 'DEVOLUCIÓN DE APORTES - ' . $retirement_fund->procedure_modality->name;
    }else {//FRPS
      $title = 'CALIFICACIÓN FONDO DE RETIRO POLICIAL SOLIDARIO';
    }

    $array_discounts_combi = [];
    foreach ($array_discounts as $value) {
      $temp = 'Fondo de Retiro';
      if ($retirement_fund->procedure_modality->procedure_type_id == 1) {
        $temp = 'Pago Global por ' . $retirement_fund->procedure_modality->name;
      }elseif($retirement_fund->procedure_modality->procedure_type_id == 21){
        $temp = 'Devolución de aportes por ' . $retirement_fund->procedure_modality->name;
      }
      array_push($array_discounts_combi, array('name' => ($temp . ' ' . ($value['name'] ? ' - ' . $value['name'] : '')), 'amount' => ($retirement_fund->subtotal_ret_fun - $value['amount'])));
    }

    /*  / discount combinations*/

    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 23)->first();
    $code = $retirement_fund->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;

    $subtitle = $number;

    $current_procedure = Util::getRetFunCurrentProcedure();
    $temp = [];
    if ($retirement_fund->procedure_modality->procedure_type_id == 1) {//PGA
      $total_aporte = $retirement_fund->average_quotable;
      $yield = $total_aporte + (($total_aporte * $current_procedure->annual_yield) / 100);
      //$yield = Util::compoundInterest($affiliate->getContributionsPlus(), $affiliate);
      $administrative_expenses = 0;
      $less_administrative_expenses = $yield;
      $temp = [
        'yield' => $yield,
        'administrative_expenses' => $administrative_expenses,
        'less_administrative_expenses' => $less_administrative_expenses,
      ];
    }
    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,

      'title' => $title,
      'subtitle' => $subtitle,
      'contributions' => $contributions,
      'total_quotes' => $total_quotes,
      'discounts' => $discounts,
      'array_discounts_combi' => $array_discounts_combi,
      'has_availability' => $has_availability,
      'affiliate' => $affiliate,
      'applicant' => $applicant,
      'beneficiaries' => $beneficiaries,
      'retirement_fund' => $retirement_fund,
      'name_procedure_type'=>$name_procedure_type,
      'num'=>0,
      'contributionsPlus' => $affiliate->getTotalAverageSalaryQuotable()['contributions'],//aqui se manda a imprimir
      'total_retirement_fund'=>$affiliate->getTotalAverageSalaryQuotable()['total_retirement_fund']
    ];
    $data = array_merge($data, $temp);

    if ($only_print) {
      return \PDF::loadView('ret_fun.print.qualification_step_data', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
    }
    return $data;
  }
  //---**IMPRIMIR FORMULARIO DE CALIFICACIÓN DISPONIBILIDAD**--//
  public function printDataQualificationAvailability($id, $only_print = true)
  {
    $retirement_fund = RetirementFund::find($id);

    $current_procedure = Util::getRetFunCurrentProcedure();
    $title = "DEVOLUCIÓN DE APORTES EN DISPONIBILIDAD";
    $affiliate = $retirement_fund->affiliate;
    $applicant = $retirement_fund->ret_fun_beneficiaries()->where('type', 'S')->with('kinship')->first();
    $beneficiaries = $retirement_fund->ret_fun_beneficiaries()->orderByDesc('type')->orderBy('id')->get();
    $pdftitle = "Calificacion";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    $group_dates = [];
    $total_dates = Util::sumTotalContributions($affiliate->getDatesGlobal());
    $dates = array(
      'id' => 0,
      'dates' => $affiliate->getDatesGlobal(),
      'name' => "perii",
      'operator' => '**',
      'description' => "dsds",
      'years' => intval($total_dates / 12),
      'months' => $total_dates % 12,
    );

    foreach (ContributionType::orderBy('id')->where('id', '=', 12)->orWhere('id','=',13)->get() as $c) {
      // if($c->id != 1){
      $contributionsWithType = $affiliate->getContributionsWithType($c->id);
      if (sizeOf($contributionsWithType) > 0) {
        $sub_total_dates = Util::sumTotalContributions($contributionsWithType);
        $dates = array(
          'id' => $c->id,
          'dates' => $affiliate->getContributionsWithType($c->id),
          'name' => $c->name,
          'operator' => $c->operator,
          'description' => $c->description,
          'years' => intval($sub_total_dates / 12),
          'months' => $sub_total_dates % 12,
        );
        if ($c->operator == '-') {
          eval('$total_dates = ' . $total_dates . $c->operator . $sub_total_dates . ';');
        }
        $group_dates[] = $dates;
      }
      // }
    }
    $contributions = array(
      'contribution_types' => $group_dates,
      'years' => intval($total_dates / 12),
      'months' => $total_dates % 12
    );
    $total_quotes = $affiliate->getTotalQuotes();
    $discounts = $retirement_fund->discount_types()->where('amount', '>', 0)->get();
    $has_availability = sizeOf($affiliate->getContributionsWithType(12)) > 0;
    $availability = ContributionType::find(12);

    /*  discount combinations*/
    $array_discounts = array();
    $array = DiscountType::where('module_id', 3)->get()->pluck('id');
    $results = array(array());
    foreach ($array as $element) {
      foreach ($results as $combination) {
        array_push($results, array_merge(array($element), $combination));
      }
    }
    foreach ($results as $value) {
      $sw = true;
      foreach ($value as $id) {
        // if (!$retirement_fund->discount_types()->find($id)) {
        if (!($retirement_fund->discount_types()->find($id)->pivot->amount > 0)) {
          $sw = false;
        }
      }
      if ($sw) {
        $temp_total_discount = 0;
        foreach ($value as $id) {
          $temp_total_discount = $temp_total_discount + $retirement_fund->discount_types()->find($id)->pivot->amount;
        }
        $name = join(' - ', DiscountType::whereIn('id', $value)->orderBy('id', 'asc')->get()->pluck('name')->toArray());
        array_push($array_discounts, array('name' => $name, 'amount' => $temp_total_discount));
      }
    }
    if ($affiliate->hasAvailability()) {
      $array_discounts_availability = [];
       foreach ($array_discounts as $value) {
        array_push($array_discounts_availability, array('name' => ('Fondo de Retiro ' . ($value['name'] ? ' - ' . $value['name'] : '')), 'amount' => ($retirement_fund->subtotal_ret_fun - $value['amount'])));
      }
     }
    /*  discount combinations*/

    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 23)->first();
    $code = $retirement_fund->code;
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
      'contributions' => $contributions,
      'total_quotes' => $total_quotes,
      'discounts' => $discounts,
      'has_availability' => $has_availability,
      'availability' => $availability,

      'array_discounts_availability' => $array_discounts_availability,

      'affiliate' => $affiliate,
      'applicant' => $applicant,
      'beneficiaries' => $beneficiaries,
      'retirement_fund' => $retirement_fund,
      'current_procedure' => $current_procedure,
    ];
    if ($only_print) {
      return \PDF::loadView('ret_fun.print.qualification_data_availability', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
    }
    return $data;
  }
  public function printDataQualificationRetFunAvailability($id, $only_print = true)
  {
    $retirement_fund = RetirementFund::find($id);

    $title = "fondo de retiro y disponibilidad ";
    $affiliate = $retirement_fund->affiliate;
    $applicant = $retirement_fund->ret_fun_beneficiaries()->where('type', 'S')->with('kinship')->first();
    $beneficiaries = $retirement_fund->ret_fun_beneficiaries()->orderByDesc('type')->orderBy('id')->get();
    $pdftitle = "Calificacion";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);

    /*  discount combinations*/
    $array_discounts = array();
    $array = DiscountType::where('module_id', 3)->get()->pluck('id');
    $results = array(array());
    foreach ($array as $element) {
      foreach ($results as $combination) {
        array_push($results, array_merge(array($element), $combination));
      }
    }
    foreach ($results as $value) {
      $sw = true;
      foreach ($value as $id) {
        // if (!$retirement_fund->discount_types()->find($id)) {
        if (!($retirement_fund->discount_types()->find($id)->pivot->amount > 0)) {
          $sw = false;
        }
      }
      if ($sw) {
        $temp_total_discount = 0;
        foreach ($value as $id) {
          $temp_total_discount = $temp_total_discount + $retirement_fund->discount_types()->find($id)->pivot->amount;
        }
        $name = join(' - ', DiscountType::whereIn('id', $value)->orderBy('id', 'asc')->get()->pluck('name')->toArray());
        array_push($array_discounts, array('name' => $name, 'amount' => $temp_total_discount));
      }
    }
     if ($affiliate->hasAvailability()) {
      $array_discounts_availability = [];
       foreach ($array_discounts as $value) {
         array_push($array_discounts_availability, array('name' => ('Fondo de Retiro ' . ($value['name'] ? ' - ' . $value['name'] : '')), 'amount' => ($retirement_fund->subtotal_ret_fun - $value['amount'])));
      }
   }
    /*  discount combinations*/

    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 23)->first();
    $code = $retirement_fund->code;
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
      'array_discounts_availability' => $array_discounts_availability,

      'affiliate' => $affiliate,
      'applicant' => $applicant,
      'beneficiaries' => $beneficiaries,
      'retirement_fund' => $retirement_fund,
    ];
    if ($only_print) {
      return \PDF::loadView('ret_fun.print.qualification_data_ret_fun_availability', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
    }
    return $data;
  }

//--**METODO PRINCIPAL DEL FORMULARIO DE CALIFICACIÓN PAGINAS**--//
  public function printAllQualification($id)
  {
    $retirement_fund = RetirementFund::find($id);
    $affiliate = $retirement_fund->affiliate;

    if ($affiliate->hasAvailability()) {
      if ($retirement_fund->total_availability > 0) {
        $pages[] = \View::make('ret_fun.print.qualification_data_availability', self::printDataQualificationAvailability($id, false))->render();
      }
      // if ($retirement_fund->total > 0) {
      //     $pages[] =\View::make('ret_fun.print.qualification_data_ret_fun_availability', self::printDataQualificationRetFunAvailability($id, false))->render();
      // }
    }

    $pages[] = \View::make('ret_fun.print.qualification_step_data', self::printDataQualification($id, false))->render();

    $pages[] = \View::make('ret_fun.print.beneficiaries_qualification', self::printBeneficiariesQualification($id, false))->render();
    if ($affiliate->hasAvailability()) {
      if ($retirement_fund->total_availability > 0) {
        $pages[] = \View::make('ret_fun.print.qualification_data_availability', self::printDataQualificationAvailability($id, false))->render();
      }
      // if ($retirement_fund->total > 0) {
      //     $pages[] =\View::make('ret_fun.print.qualification_data_ret_fun_availability', self::printDataQualificationRetFunAvailability($id, false))->render();
      // }
    }

    $pages[] = \View::make('ret_fun.print.qualification_step_data', self::printDataQualification($id, false))->render();

    $pages[] = \View::make('ret_fun.print.beneficiaries_qualification', self::printBeneficiariesQualification($id, false))->render();

    if (!$affiliate->selectedContributions() > 0 && $retirement_fund->procedure_modality->procedure_type->id == 2) {
      $pages[] = \View::make('ret_fun.print.qualification_average_salary_quotable', self::printQualificationAverageSalaryQuotable($id, false))->render();
    }
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
  public function printRetFunCommitmentLetter($id)
  {
    $affiliate = Affiliate::find($id);
    $commitment = ContributionCommitment::where('affiliate_id', $affiliate->id)->first();
    $date = Util::getDateFormat(date('Y-m-d'));
    $user = Auth::user(); //agregar cuando haya roles
    $city = Auth::user()->city->name;
    $glosa = "No corresponde";
    if ($affiliate->affiliate_state->name == "Baja Temporal") {
      $title = "COMPROMISO DE PAGO - APORTE VOLUNTARIO SUSPENDIDOS TEMPORALMENTE DE FUNCIONES POR PROCESOS DISCIPLINARIOS";
      $glosa = 'Suspendido temporalmente de funciones por procesos disciplinarios, figurando en planilla de haberes con ítem "0".';
      $glosa_pago = "de mi última boleta de pago efectivamente percibida";
    } else {
      $title = 'COMPROMISO DE PAGO - APORTE VOLUNTARIO COMISIÓN DE SERVICIO ÍTEM "0" O AGREGADOS POLICIALES EN EL EXTERIOR DEL PAÍS';
      $glosa_pago = "de mi total ganado mensual (sin descuentos)";
      if ($affiliate->affiliate_state->name == "Comisión") {
        $glosa = 'Comisión de Servicio Ítem "0".';
      } else {
        if ($affiliate->affiliate_state->name == "Agregado Policial") {
          $glosa = "Agregado Policial en el exterior del país.";
        }
      }
    }
    $pdftitle = "Carta de Compromiso de Fondo de Retiro";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    $user = Auth::user();
    $date = date('d/m/Y');
    $area = WorkflowState::find(22)->first_shortened;

    // return view('ret_fun.print.beneficiaries_qualification', compact('date','subtitle','username','title','number','retirement_fund','affiliate','submitted_documents'));
    $data = [
      'area' => $area,
      'date' => $date,
      'user' => $user,
      'title' => $title,
      'affiliate' => $affiliate,
      'glosa' => $glosa,
      'city' => $city,
      'glosa_pago' => $glosa_pago,
      'commitment' => $commitment,


    ];
    return \PDF::loadView(
      'ret_fun.print.ret_fun_commitment_letter',
      $data
    )
      ->setOption('encoding', 'utf-8')
      ->setOption('footer-right', 'Pagina [page] de [toPage]')
      ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL')
      ->stream("$namepdf");
  }

  public function printVoucher(Request $request, $affiliate_id, $voucher_id)
  {
    $affiliate = Affiliate::find($affiliate_id);
    $voucher = Voucher::find($voucher_id);
    $contributions = [];
    $total_literal = Util::convertir($voucher->total);
    $payment_date = Util::getStringDate($voucher->payment_date);
    $date = Util::getStringDate(date('Y-m-d'));
    $title = "RECIBO";
    $subtitle = "FONDO DE RETIRO Y CUOTA MORTUORIA <br> (Expresado en Bolivianos)";
    $username = Auth::user()->username; //agregar cuando haya roles
    $name_user_complet = Auth::user()->first_name . " " . Auth::user()->last_name;
    $number = $voucher->code;
    $descripcion = VoucherType::where('id', $voucher->voucher_type_id)->first();
    $beneficiary = $affiliate;
    $contributions = json_decode($request->contributions);
    $pdftitle = "Comprobante";
    $namepdf = Util::getPDFName($pdftitle, $beneficiary);
    $util = new Util();

    $area = WorkflowState::find(22)->first_shortened;
    $user = Auth::user();
    $date = date('d/m/Y');


    // return view('ret_fun.print.beneficiaries_qualification', compact('date','subtitle','username','title','number','retirement_fund','affiliate','submitted_documents'));
    return \PDF::loadView(
      'ret_fun.print.voucher_contribution',
      compact(
        'date',
        'username',
        'title',
        'subtitle',
        'affiliate',
        'submitted_documents',
        'beneficiary',
        'contributions',
        'number',
        'voucher',
        'util',
        'descripcion',
        'payment_date',
        'total_literal',
        'name_user_complet',
        'area',
        'user',
        'date'
      )
    )
      ->setOption('encoding', 'utf-8')
      ->setOption('footer-right', 'Pagina [page] de [toPage]')
      ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL')
      ->stream("$namepdf");
  }

  public function printDirectContributionQuote(Request $request)
  {
    $contributions = json_decode($request->contributions);
    $total = $request->total;
    $total_literal = Util::convertir($total);
    $affiliate = Affiliate::find($request->affiliate_id);
    $date = Util::getStringDate(date('Y-m-d'));
    $title = "PAGO DE APORTE DIRECTO";
    $subtitle = "(Expresado en Bolivianos)";
    $username = Auth::user()->username; //agregar cuando haya roles
    $name_user_complet = Auth::user()->first_name . " " . Auth::user()->last_name;
    $detail = "Pago de aporte directo";
    $beneficiary = $affiliate;
    $name_beneficiary_complet = Util::fullName($beneficiary);
    $pdftitle = "Comprobante";
    $namepdf = Util::getPDFName($pdftitle, $beneficiary);
    $util = new Util();
    $area = WorkflowState::find(22)->first_shortened;
    $user = Auth::user();
    $date = date('d/m/Y');
    $number = 1;

    $data = [
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,
      'date'  =>  $date,
      'username'  =>  $username,
      'title' =>  $title,
      'subtitle' =>  $subtitle,
      'beneficiary'   =>  $beneficiary,
      'contributions' =>  $contributions,
      'total' =>  $total,
      'total_literal' =>  $total_literal,
      'detail'    =>  $detail,
      'util'  =>  $util,
      'name_user_complet' =>  $name_user_complet,
      'name_beneficiary_complet'  =>  $name_beneficiary_complet,
    ];


    return \PDF::loadView(
      'ret_fun.print.affiliate_contribution',
      $data
    )
      ->setOption('encoding', 'utf-8')
      ->setOption('footer-right', 'Pagina [page] de [toPage]')
      ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)
      ->stream("$namepdf");
  }

  private function generateBarCode($retirement_fund)
  {
    $bar_code = \DNS2D::getBarcodePNG(($retirement_fund->getBasicInfoCode()['code'] . "\n\n" . $retirement_fund->getBasicInfoCode()['hash']),
      "PDF417",
      100,
      33,
      array(1, 1, 1)
    );
    return $bar_code;
  }

  public function printCertification($id)
  {
    // 60 aportes
    $retirement_fund = RetirementFund::find($id);
    $affiliate = $retirement_fund->affiliate;
    $valid_contributions = ContributionType::select('id')->where('operator', '+')->pluck('id');
    $quantity = Util::getRetFunCurrentProcedure()->contributions_number;
    $contributions_sixty = Contribution::where('affiliate_id', $affiliate->id)
      ->whereIn('contribution_type_id', $valid_contributions)
      ->orderByDesc('month_year')
      ->take($quantity)
      ->get();
    $reimbursements = Reimbursement::where('affiliate_id', $affiliate->id)
      ->orderBy('month_year')
      ->get();
    $contributions_sixty = $contributions_sixty->reverse();
    $reimbursements = $reimbursements->reverse();
    $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
    $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
    $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
    $title = "CERTIFICACIÓN DE APORTES";
    $subtitle = "Cuenta Individual";


    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 22)->first();
    $code = $retirement_fund->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;

    $degree = Degree::find($affiliate->degree_id);
    $exp = City::find($affiliate->city_identity_card_id);
    $exp = ($exp == Null) ? "-" : $exp->first_shortened;
    $dateac = Carbon::now()->format('d/m/Y');
    $place = City::find(Auth::user()->city_id);
    $num = 0;
    $pdftitle = "Cuentas Individuales";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);

    $subtitle = $next_area_code->code;

    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,

      'num' => $num,
      'subtitle' => $subtitle,
      'place' => $place,
      'retirement_fund' => $retirement_fund,
      'reimbursements' => $reimbursements,
      'dateac' => $dateac,
      'exp' => $exp,
      'degree' => $degree,
      'contributions' => $contributions_sixty,
      'affiliate' => $affiliate,
      'title' => $title,
      'institution' => $institution,
      'direction' => $direction,
      'unit' => $unit,
    ];
    return \PDF::loadView('contribution.print.certification_contribution', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
  }
  public function printCertificationAvailability($id)
  {
    $retirement_fund = RetirementFund::find($id);
    $affiliate = $retirement_fund->affiliate;
    $disponibilidad = ContributionType::where('name', '=', 'Disponibilidad')->first();
    $contributions = Contribution::where('affiliate_id', $affiliate->id)
      ->orderBy('month_year')
      ->get();
    $reimbursements = Reimbursement::where('affiliate_id', $affiliate->id)
      ->orderBy('month_year')
      ->get();
    $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
    $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
    $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
    $title = "CERTIFICACIÓN DE APORTES EN DISPONIBILIDAD";
    $subtitle = "Cuenta Individual";

    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 22)->first();
    $code = $retirement_fund->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;

    $degree = Degree::find($affiliate->degree_id);
    $exp = City::find($affiliate->city_identity_card_id);
    $exp = ($exp == Null) ? "-" : $exp->first_shortened;
    $dateac = Carbon::now()->format('d/m/Y');
    $place = City::find(Auth::user()->city_id);
    $num = 0;
    $pdftitle = "Cuentas Individuales";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);

    $subtitle = $next_area_code->code;

    //total de los aportes
    $aporte = $retirement_fund->subtotal_availability;
    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,

      'num' => $num,
      'disponibilidad' => $disponibilidad,
      'aporte' => $aporte,
      'subtitle' => $subtitle,
      'place' => $place,
      'retirement_fund' => $retirement_fund,
      'reimbursements' => $reimbursements,
      'dateac' => $dateac,
      'exp' => $exp,
      'degree' => $degree,
      'contributions' => $contributions,
      'affiliate' => $affiliate,
      'title' => $title,
      'institution' => $institution,
      'direction' => $direction,
      'unit' => $unit,
    ];

    return \PDF::loadView('contribution.print.certification_availability', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
  }
  public function printCertificationItem0($id)
  {
    $retirement_fund = RetirementFund::find($id);
    $affiliate = $retirement_fund->affiliate;
    $item_cero_ids = [2, 3];
    $contributions =  $affiliate->contributions()->whereIn('contribution_type_id', $item_cero_ids)->get();
    $month_years = $contributions->pluck('month_year');
    $months = implode(",", array_map(function ($item) {
      return "'" . $item . "'";
    }, $month_years->toArray()));

    $contributions = DB::select("
        select * from
        (
            select
                contributions.id,
                contributions.affiliate_id,
                contributions.month_year,
                contributions.base_wage,
                contributions.quotable,
                contributions.subtotal,
                contributions.retirement_fund,
                contributions.mortuary_quota,
                contributions.interest,
                contributions.total
            from contributions
            where affiliate_id = " . $affiliate->id . "
            and deleted_at is null
            and contribution_type_id in (2,3)
            and month_year in (" . $months . ")
            UNION
            select
                reimbursements.id,
                reimbursements.affiliate_id,
                reimbursements.month_year,
                reimbursements.base_wage,
                reimbursements.quotable,
                reimbursements.subtotal,
                reimbursements.retirement_fund,
                reimbursements.mortuary_quota,
                reimbursements.interest,
                reimbursements.total
            from reimbursements
            where affiliate_id = " . $affiliate->id . "
            and month_year in (" . $months . ")
            and deleted_at is null
        ) as contributions_reimbursements
            ORDER BY month_year DESC");

    $contributions = array_reverse($contributions);

    $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
    $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
    $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
    $title = "CERTIFICACIÓN DE CUENTAS INDIVIDUALES ITEM 0";
    $subtitle = "Cuenta Individual";

    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 22)->first();
    $code = $retirement_fund->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;

    $degree = Degree::find($affiliate->degree_id);
    $exp = City::find($affiliate->city_identity_card_id);
    $exp = ($exp == Null) ? "-" : $exp->first_shortened;
    $dateac = Carbon::now()->format('d/m/Y');
    $place = City::find(Auth::user()->city_id);
    $pdftitle = "Cuentas Individuales";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    $item0_type = 2;

    $subtitle = $next_area_code->code;

    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,
      'subtitle' => $subtitle,
      'place' => $place,
      'retirement_fund' => $retirement_fund,
      'dateac' => $dateac,
      'exp' => $exp,
      'degree' => $degree,
      'contributions' => $contributions,
      'affiliate' => $affiliate,
      'title' => $title,
      'institution' => $institution,
      'direction' => $direction,
      'unit' => $unit,
    ];
    return \PDF::loadView('contribution.print.certification_item0', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)->stream("$namepdf");
  }

  public function printCertificationAvailabilityNew($id)
  {
    $retirement_fund = RetirementFund::find($id);
    $affiliate = $retirement_fund->affiliate;
    $item_cero_ids = [12, 13];
    $contributions =  $affiliate->contributions()->whereIn('contribution_type_id', $item_cero_ids)->orderBy('month_year')->get();
    $month_years = $contributions->pluck('month_year');
    $months = implode(",", array_map(function ($item) {
      return "'" . $item . "'";
    }, $month_years->toArray()));

    $reimbursements = Reimbursement::where('affiliate_id', $affiliate->id)
      ->orderBy('month_year')
      ->get();

    $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
    $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
    $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
    $title = "CERTIFICACIÓN DE CUENTAS INDIVIDUALES DISPONIBILIDAD";
    $subtitle = "Cuenta Individual";

    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 22)->first();
    $code = $retirement_fund->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;

    $degree = Degree::find($affiliate->degree_id);
    $exp = City::find($affiliate->city_identity_card_id);
    $exp = ($exp == Null) ? "-" : $exp->first_shortened;
    $dateac = Carbon::now()->format('d/m/Y');
    $place = City::find(Auth::user()->city_id);
    $pdftitle = "Cuentas Individuales";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    $num=0;

    $subtitle = $next_area_code->code;

    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,
      'subtitle' => $subtitle,
      'place' => $place,
      'retirement_fund' => $retirement_fund,
      'reimbursements' => $reimbursements,
      'dateac' => $dateac,
      'exp' => $exp,
      'degree' => $degree,
      'contributions' => $contributions,
      'affiliate' => $affiliate,
      'title' => $title,
      'institution' => $institution,
      'direction' => $direction,
      'unit' => $unit,
      'num' => $num,
    ];
    return \PDF::loadView('contribution.print.certification_availabilityNew', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL')->stream("$namepdf");
  }

  public function printCertificationSecurity($id)
  {
    $retirement_fund = RetirementFund::find($id);
    $affiliate = $retirement_fund->affiliate;
    $security_contributions = ContributionType::where('name', '=', 'Período de Batallón de Seguridad Física Con Aporte')->first();
    $security_no_contributions = ContributionType::where('name', '=', 'Período de Batallón de Seguridad Física Sin Aporte')->first();

    $contributions = Contribution::where('affiliate_id', $affiliate->id)
      ->where(function ($query) use ($security_contributions, $security_no_contributions) {
        $query->where('contribution_type_id', $security_contributions->id)
          ->orWhere('contribution_type_id', $security_no_contributions->id);
      })
      ->orderBy('month_year')
      ->get();
    $contributions_number = Contribution::where('affiliate_id', $affiliate->id)->where('contribution_type_id', $security_contributions->id)->count();
    $contributions_total = Contribution::where('affiliate_id', $affiliate->id)->where('contribution_type_id', $security_contributions->id)->sum('total');
    $reimbursements = Reimbursement::where('affiliate_id', $affiliate->id)
      ->orderBy('month_year')
      ->get();
    $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
    $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
    $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
    $title = "BATALLÓN DE SEGURIDAD FÍSICA PRIVADA";
    $subtitle = "Cuenta Individual";

    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 22)->first();
    $code = $retirement_fund->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;

    $degree = Degree::find($affiliate->degree_id);
    $exp = City::find($affiliate->city_identity_card_id);
    $exp = ($exp == Null) ? "-" : $exp->first_shortened;
    $dateac = Carbon::now()->format('d/m/Y');
    $place = City::find(Auth::user()->city_id);
    $num = 0;
    $pdftitle = "Cuentas Individuales";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    // $total = Util::formatMoney($contributions_total);

    $subtitle = $next_area_code->code;

    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,

      'num' => $num,
      'subtitle' => $subtitle,
      'place' => $place,
      'retirement_fund' => $retirement_fund,
      // 'total'=>$total,
      'reimbursements' => $reimbursements,
      'dateac' => $dateac,
      'exp' => $exp,
      'degree' => $degree,
      'contributions' => $contributions,
      'affiliate' => $affiliate,
      'title' => $title,
      'institution' => $institution,
      'direction' => $direction,
      'unit' => $unit,
      'contributions_number' => $contributions_number,
      'security_contributions' => $security_contributions,
    ];
    return \PDF::loadView('contribution.print.security_certification', $data)
      ->setOption('encoding', 'utf-8')
      ->setOption('margin-bottom', '15mm')
      ->setOption('footer-right', 'Pagina [page] de [toPage]')
      ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL')
      ->stream("$namepdf");
  }

  public function printCertificationContributions($id)
  {

    $retirement_fund = RetirementFund::find($id);
    $affiliate = $retirement_fund->affiliate;
    $certification_contribution = ContributionType::where('name', '=', 'Período Certificación Con Aporte')->first();
    $certification_no_contribution = ContributionType::where('name', '=', 'Período Certificación Sin Aporte')->first();

    $contributions = Contribution::where('affiliate_id', $affiliate->id)
      ->where(function ($query) use ($certification_contribution, $certification_no_contribution) {
        $query->where('contribution_type_id', $certification_contribution->id)
          ->orWhere('contribution_type_id', $certification_no_contribution->id)
          ->orWhere('contribution_type_id', 9)
          ->orWhere('contribution_type_id', 14)
          ->orWhere('contribution_type_id', 6);
      })
      ->orderBy('month_year')
      ->get();
    // 9 id periodo no  trabajado
    $contributions_number = Contribution::where('affiliate_id', $affiliate->id)->whereIn('contribution_type_id', [$certification_contribution->id, 9])->count();
    $contributions_total = Contribution::where('affiliate_id', $affiliate->id)->whereIn('contribution_type_id', [$certification_contribution->id, 9])->sum('total');
    $reimbursements = Reimbursement::where('affiliate_id', $affiliate->id)
      ->orderBy('month_year')
      ->get();
    $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
    $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
    $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
    $title = "CERTIFICACIÓN DE APORTES";
    $subtitle = "Cuenta Individual";

    // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
    $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 22)->first();
    $code = $retirement_fund->code;
    $area = $next_area_code->wf_state->first_shortened;
    $user = $next_area_code->user;
    $date = Util::getDateFormat($next_area_code->date);
    $number = $next_area_code->code;

    $degree = Degree::find($affiliate->degree_id);
    $exp = City::find($affiliate->city_identity_card_id);
    $exp = ($exp == Null) ? "-" : $exp->first_shortened;
    $dateac = Carbon::now()->format('d/m/Y');
    $place = City::find(Auth::user()->city_id);
    $num = 0;
    $pdftitle = "Cuentas Individuales";
    $namepdf = Util::getPDFName($pdftitle, $affiliate);
    $total = Util::formatMoney($contributions_total);

    $subtitle = $next_area_code->code;

    $data = [
      'code' => $code,
      'area' => $area,
      'user' => $user,
      'date' => $date,
      'number' => $number,

      'num' => $num,
      'subtitle' => $subtitle,
      'place' => $place,
      'retirement_fund' => $retirement_fund,
      'total' => $total,
      'reimbursements' => $reimbursements,
      'dateac' => $dateac,
      'exp' => $exp,
      'degree' => $degree,
      'contributions' => $contributions,
      'affiliate' => $affiliate,
      'title' => $title,
      'institution' => $institution,
      'direction' => $direction,
      'unit' => $unit,
      'certification_contribution' => $certification_contribution,
      'contributions_number' => $contributions_number,
    ];
    return \PDF::loadView('contribution.print.contributions_certification', $data)
      ->setOption('encoding', 'utf-8')
      ->setOption('margin-bottom', '15mm')
      ->setOption('footer-right', 'Pagina [page] de [toPage]')
      ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL')
      ->stream("$namepdf");
  }
  public function printLegalDictum($id)
  {
    $retirement_fund = RetirementFund::find($id);

    $applicant = RetFunBeneficiary::where('type', 'S')->where('retirement_fund_id', $retirement_fund->id)->first();
    $beneficiaries = RetFunBeneficiary::where('retirement_fund_id', $retirement_fund->id)->orderByDesc('type')->orderBy('id')->get();
    /** PERSON DATA */
    $person = "";
    $affiliate = Affiliate::find($retirement_fund->affiliate_id);
    $ret_fun_beneficiary = RetFunLegalGuardianBeneficiary::where('ret_fun_beneficiary_id', $applicant->id)->first();


    if (isset($ret_fun_beneficiary->id)) {
      $legal_guardian = RetFunLegalGuardian::where('id', $ret_fun_beneficiary->ret_fun_legal_guardian_id)->first();
      $person .= ($legal_guardian->gender == 'M' ? "El Sr. " : "La Sra. ") . Util::fullName($legal_guardian) . " con C.I. N° " . $legal_guardian->identity_card . ". a través de Testimonio Notarial N° " . $legal_guardian->number_authority . " de fecha " . Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority)) . " sobre poder especial, bastante y suficiente emitido por " . $legal_guardian->notary_of_public_faith . " a cargo del Notario " . $legal_guardian->notary . " en representación " . ($affiliate->gender == 'M' ? "del señor " : "de la señora ");
    } else {
      $person .= ($affiliate->gender == 'M' ? "El Sr. " : "La Sra. ");
    }
    $person .= $affiliate->fullNameWithDegree() . " con C.I. N° " . $affiliate->identity_card . ", como TITULAR " . ($retirement_fund->procedure_modality_id == 4 ? "FALLECIDO " : " ") . "del " . $retirement_fund->procedure_modality->procedure_type->name . " en su modalidad de <strong class='uppercase'>" . $retirement_fund->procedure_modality->name . "</strong>,";
    if ($retirement_fund->procedure_modality_id == 4) {
      //$person .= " presenta la documentación para la otorgación del beneficio en fecha ". Util::getStringDate($retirement_fund->reception_date) .", a lo cual considera lo siguiente:";

      $person .= ($applicant->gender == 'M' ? ' el Sr. ' : ' la Sra. ') . Util::fullName($applicant) . " con C.I. N° " . $applicant->identity_card . ". solicita el beneficio a favor suyo en calidad de " . $applicant->kinship->name;
      $testimony_applicant = Testimony::find($applicant->testimonies()->first()->id);

      // foreach($testimonies_applicant as $testimony) {
      $beneficiaries = $testimony_applicant->ret_fun_beneficiaries;
      $quantity = $beneficiaries->count();
      $start_message = false;
      if ($quantity > 1) {
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
        $person .= " como herederos legales acreditados mediante " . $testimony_applicant->document_type . " Nº " . $testimony_applicant->number . " de fecha " . Util::getStringDate($testimony_applicant->date) . " sobre Declaratoria de Herederos, emitido por " . $testimony_applicant->court . " de " . $testimony_applicant->place . " a cargo de " . $testimony_applicant->notary . "";
      } else {
        $person .= " como " . ($applicant->gender == "M" ? "heredero legal acreditado" : "heredera legal acreditada") . " mediante " . $testimony_applicant->document_type . " Nº " . $testimony_applicant->number . " de fecha " . Util::getStringDate($testimony_applicant->date) . " sobre Declaratoria de Herederos, emitido por " . $testimony_applicant->court . " de la ciudad de " . $testimony_applicant->place . " a cargo de " . $testimony_applicant->notary . "";
      }
      //}

      $testimonies_applicant = Testimony::where('affiliate_id', $affiliate->id)->where('id', '!=', $applicant->testimonies()->first()->id)->get();
      foreach ($testimonies_applicant as $testimony) {
        $beneficiaries = $testimony->ret_fun_beneficiaries;
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
            $person .= " como herederos legales acreditados mediante " . $testimony->document_type . " Nº " . $testimony->number . " de fecha " . Util::getStringDate($testimony->date) . " sobre Declaratoria de Herederos, emitido por " . $testimony->court . " de " . $testimony->place . " a cargo de " . $testimony->notary . "";
          } else {
            $person .= " como " . ($applicant->gender == "M" ? "heredero legal acreditado" : "heredera legal acreditada") . " mediante " . $testimony->document_type . " Nº " . $testimony->number . " de fecha " . Util::getStringDate($testimony->date) . " sobre Declaratoria de Herederos, emitido por " . $testimony->court . " de la ciudad de " . $testimony->place . " a cargo de " . $testimony->notary . "";
          }
        }
      }
      $person .= ". Presentando";
    } else {
      $person .= " presenta";
    }
    $person .= " la documentación para la otorgación del beneficio en fecha " . Util::getStringDate($retirement_fund->reception_date) . ", a lo cual considera lo siguiente:";
    //return $person;
    /** END PERSON DATA */

    /** LAW DATA */

    $law = "Conforme normativa, el trámite N° " . $retirement_fund->code . " de la Regional " . ucwords($retirement_fund->city_start->name) . " es ingresado por Ventanilla
        de Atención al Afiliado de la Unidad de Otorgación del Fondo de Retiro Policial, Cuota y Auxilio
        Mortuorio; verificados los requisitos y la documentación presentada por la parte solicitante
        según lo señalado el Art. 41 inciso a) del Reglamento de Fondo de Retiro Policial Solidario
        aprobado mediante Resolución de Directorio N° 31/2017 en fecha 24 de agosto de 2017 y
        modificado mediante Resolución de Directorio N° 36/2017 en fecha 20 de septiembre de 2017,
        y conforme el Art. 45 de referido Reglamento, se detalla la documentación como resultado de
        la aplicación de la base técnica-legal del Estudio Matemático Actuarial 2016-2020, generada y
        adjuntada al expediente por los funcionarios de la Unidad de Otorgación del Fondo de Retiro
        Policial, Cuota y Auxilio Mortuorio, según correspondan las funciones, detallando lo siguiente:";
    /** END LAW DATA */

    $body = "";

    ///---FILE---///
    $body_file = "";
    $file_id = 20;
    $file = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', $file_id)->first();
    $body_file .= "Que, mediante Certificación N° " . $file->code . ", de Archivo de la Dirección de Beneficios Económicos de fecha " . Util::getStringDate($file->date) . ", se establece que el trámite signado con el N° " . $retirement_fund->code . " ";
    $discount = $retirement_fund->discount_types();
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
      $body_file .= "tiene expediente del referido titular.";
    }
    ///---ENDIFLE--////

    /////----FINANCE----///
    $discount = $retirement_fund->discount_types();
    $finance = $discount->where('discount_type_id', '1')->first();
    $body_finance = "";
    $body_finance = "Que, mediante nota de respuesta " . ($finance->pivot->code ?? 'sin cite') . " de la Dirección de Asuntos Administrativos de fecha " . Util::getStringDate(($finance->pivot->date ?? '')) . ", refiere que " . ($affiliate->gender == 'M' ? "el" : "la") . " titular del beneficio ";
    if (isset($finance->id) && $finance->amount > 0) {
      $body_finance .= "si cuenta con registro de pagos o anticipos por concepto de Fondo de Retiro Policial en el monto de " . Util::formatMoneyWithLiteral(($finance->pivot->amount ?? 0)) . ".";
    } else {
      $body_finance .= "no cuenta con registro de pagos o anticipos por concepto de Fondo de Retiro Policial, sin embargo se recomienda compatibilizar los listados adjuntos con las carpetas del archivo de la Unidad de Fondo de Retiro para no incurrir en algún error o pago doble de este beneficio.";
    }
    /////----END FINANCE---////

    ////-----LEGAL REVIEW ----////
    $body_legal_review   = "";
    $legal_review_id = 21;
    $legal_review = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', $legal_review_id)->first();
    $body_legal_review .= "Que, mediante Certificación N° " . $legal_review->code . " del Área Legal de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha " . Util::getStringDate($legal_review->date) . ", fue verificada y validada la documentación presentada por " . ($retirement_fund->procedure_modality_id == 4 ? "los beneficiarios" : ($affiliate->gender == "M" ? "el titular" : "la titular")) . " del trámite signado con el N° " . $retirement_fund->code . ".";
    /////-----END LEGAL REVIEW----///

    ///------ INDIVIDUAL ACCCOUTNS ------////
    $body_accounts = "";
    $accounts_id = 22;
    $accounts = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', $accounts_id)->first();
    $availability_code = 10;
    $availability_number_contributions = Contribution::where('affiliate_id', $affiliate->id)->where('contribution_type_id', $availability_code)->count();

    $end_contributions = [
      '1'  => 'del fallecimiento del Titular.',
      '2'  =>  'de su retiro.',
      '3'  =>  'de la letra ' . ($affiliate->gender == 'M' ? 'del' : 'de la') . ' titular.',
      '4'  =>  'el fallecimiento del Titular.',
      '5'  =>  'de su retiro.',
      '6'  =>  'de su retiro.',
      '7'  =>  'de su retiro.',
      '24'  =>  'de su jubilación.'
    ];

    if ($retirement_fund->procedure_modality->procedure_type_id == 1) {
      $body_accounts = "Que, mediante Certificación de Aportes N° " . $accounts->code . " del Área de Cuentas Individuales de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha " . Util::getStringDate($accounts->date) . ", se verificó los " . sizeof($affiliate->getContributionsPlus()) . " aportes antes " . $end_contributions[$retirement_fund->procedure_modality_id];
    } else {
      $body_accounts = "Que, mediante Certificación de Aportes N° " . $accounts->code . " del Área de Cuentas Individuales de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha " . Util::getStringDate($accounts->date) . ", se verificó los últimos " . "60" . " aportes antes " . $end_contributions[$retirement_fund->procedure_modality_id];
    }

    if ($affiliate->hasAvailability()) {
      $body_accounts .= " Mediante Certificación de Aportes en Disponibilidad N° " . $accounts->code . " del Área de Cuentas Individuales de la Unidad de Otorgación de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha " . Util::getStringDate($accounts->date) . ", durante la permanencia en la reserva activa se verificó " . $availability_number_contributions . " aportes en disponibilidad."; // antes ".$end_contributions[$retirement_fund->procedure_modality_id];
    }
    ////------- INDIVIDUAL ACCOUTNS ------////

    //----- QUALIFICATION -----////
    $body_qualification = "";
    $qualification_id = 23;
    $qualification = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', $qualification_id)->first();
    $months  = $affiliate->getTotalQuotes();
    $body_qualification .=  "Que, mediante Calificación del ". $retirement_fund->procedure_modality->procedure_type->name ." N° " . $qualification->code . " del Área de Calificación de la Unidad de Otorgación de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha " . Util::getStringDate($qualification->date) . ", se realizó el cálculo por el periodo de<strong> " . Util::formatMonthYearLiteral($months) . "</strong>, determinando el <strong>". $retirement_fund->procedure_modality->procedure_type->name ." por " . mb_strtoupper($retirement_fund->procedure_modality->name) . "&nbsp;&nbsp;</strong>de<strong> " . Util::formatMoneyWithLiteral($retirement_fund->subtotal_ret_fun) . "</strong>" . Util::getDiscountCombinations($retirement_fund->id);
    if ($affiliate->hasAvailability()) {
      $availability = Util::sumTotalContributions($affiliate->getDatesAvailability());
      $body_qualification .= " Por concepto de reconocimiento de aportes laborales durante el periodo de disponibilidad de " . Util::formatMonthYearLiteral($availability) . ', el cual no es considerado en la calificación del ' . $retirement_fund->procedure_modality->procedure_type->name . ', de acuerdo a los parámetros establecidos por el Estudio Matemático Actuarial 2016 - 2020, se determina el monto de <strong>' . Util::formatMoneyWithLiteral($retirement_fund->total_availability) . '</strong>; haciendo un total de <strong>' . Util::formatMoneyWithLiteral($retirement_fund->total) . '</strong>';
    }
    $body_qualification .= ".";
    ////----- DUE -----////
    $discounts = $retirement_fund->discount_types();
    $discount = $discounts->where('discount_type_id', '2')->first();
    $body_due = "";
    $body_due .= "Que, mediante nota " . ($discount->pivot->code ?? 'Sin nota') . " de la Dirección de Estrategias Sociales e Inversiones de fecha " . Util::getStringDate(($discount->pivot->date ?? '')) . ",
                    refiere que " . ($affiliate->gender == 'M' ? 'el' : 'la') . " titular ";
    $discounts = $retirement_fund->discount_types();
    $discount_counter = $discounts->where('discount_type_id', '>', '1')->where('amount', '>', '0')->count();
    if ($discount_counter == 0) {
      $body_due .= "no cuenta con deuda en curso de pago a MUSERPOL ni por concepto de garantía de préstamo.";
    } else {
      $and = "";
      $discounts = $retirement_fund->discount_types();
      $discount = $discounts->where('discount_type_id', '2')->first();
      if (isset($discount->id) && $discount->pivot->amount > 0) {
        $body_due .= "si cuenta con deuda en curso de pago a MUSERPOL";
        $and = " y ";
      }

      $discounts = $retirement_fund->discount_types();
      $discount = $discounts->where('discount_type_id', '3')->first();
      if (isset($discount->id) && $discount->pivot->amount > 0) {
        if ($and == "") {
          $body_due .= "si cuenta con deuda en curso de pago a MUSERPOL";
        }
        $body_due .= $and . " por concepto de garantía de préstamo";
      }
      $body_due .= ", supra detallado.";
    }
    ///-----END DUE----///

    ///------ PAYMENT ------////
    $payment = "";

    switch($retirement_fund->procedure_modality->procedure_type->id) {
      case 1:
        $request_article = '2, 3, 5, 10, 20, 21 inciso ';
        switch($retirement_fund->procedure_modality_id) {
          case 1:
            $request_article .= 'a)';
            break;
          case 2:
            $request_article .= 'b)';
            break;
          case 24:
            $request_article .= 'c)';
            break;
        }
        $request_article .= ', 22, 24, 31 Ter., 40, 42, 42 Bis., 42 Ter., 44, 45, 48, 49 y 50';
        break;
      case 2:
        $request_article = '2, 3, 5, 10, 15, 26, 27, ';
        switch($retirement_fund->procedure_modality_id) {
          case 3:
            $request_article .= '28';
            break;
          case 4:
            $request_article .= '29';
            break;
          case 5:
          case 6:
            $request_article .= '30';
            break;
          case 7:
            $request_article .= '31';
            break;
        }
        $request_article .= ', 31 Bis., 31 Ter., 32, ';
        if ($retirement_fund->procedure_modality_id == 4) {
          $request_article .= '33, 34, 35, ';
        }
        $request_article .= '36, 37, 38, 40, 41 inciso ';
        switch($retirement_fund->procedure_modality_id) {
          case 3:
            $request_article .= 'a)';
            break;
          case 4:
            $request_article .= 'b)';
            break;
          case 5:
          case 6:
            $request_article .= 'c)';
            break;
          case 7:
            $request_article .= 'd)';
            break;
        }
        $request_article .= ', 42, 42 Bis., 42 Ter., 43, 44, 45, 48, 49, 50, 52, 70, 71, 72, 73, 74 y Disposición Transitoria Segunda';
        break;
    }


    $discounts = $retirement_fund->discount_types(); //DiscountType::where('retirement_fund_id',$retirement_fund->id)->orderBy('discount_type_id','ASC')->get();
    $loans = InfoLoan::where('affiliate_id', $affiliate->id)->get();
    $payment = "Por consiguiente, habiendo sido remitido el presente trámite al Área Legal de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, autorizado por Jefatura de la referida Unidad, conforme a los Arts. " . $request_article . " del Reglamento de Fondo de Retiro Policial Solidario";

    if ($retirement_fund->procedure_modality->procedure_type->id == 2) {
      $payment .= ", aprobado mediante Resolución de Directorio N° 31/2017 en fecha 24 de agosto de 2017 y modificado mediante Resoluciones de Directorio Nros. 36/2017 de 20 de septiembre de 2017, 51/2017 de 29 de diciembre de 2017 y 05/2019 de 20 de febrero de 2019; y la Disposición Transitoria Segunda del Reglamento de Cuota Mortuoria y Auxilio Mortuorio, aprobado mediante Resolución de Directorio N° 76/2019 de fecha 11 de diciembre de 2019";
    }

    $payment .= ". Se <b>DICTAMINA</b> en mérito a la documentación de respaldo contenida en el presente, ";

    $flagy = 0;
    $discounts = $retirement_fund->discount_types();
    $discounts_number = $discounts->where('amount', '>', '0')->count();
    if ($discounts_number > 0) {
      $payment .= "proceder a realizar el descuento ";
      $discounts = $retirement_fund->discount_types();
      $discount = $discounts->where('discount_type_id', '1')->first();
      if (isset($discount->id) && $discount->pivot->amount > 0) {
        $flagy++;
        $payment .= "de <b>" . Util::formatMoneyWithLiteral($discount->pivot->amount) . "</b> por concepto de anticipo de Fondo de Retiro Policial de conformidad a la Resoluci&oacute;n de la Comisión de Presentaciones Nro. " . $discount->pivot->note_code . " de fecha " . Util::getStringDate($discount->pivot->date);
      }

      $discounts = $retirement_fund->discount_types();
      $discount = $discounts->where('discount_type_id', '2')->first();
      $discount_footer = false;
      if (isset($discount->id) && $discount->pivot->amount > 0) {
        $payment .= $this->getFlagy($discounts_number, $flagy);
        $flagy++;
        $discount_footer = true;
        $payment .= "de <b>" . Util::formatMoneyWithLiteral($discount->pivot->amount) . "</b> por concepto de saldo de deuda con la MUSERPOL";
        //de conformidad al contrato de préstamo Nro. ".$discount->pivot->note_code." y nota ".$discount->pivot->code." de fecha ".Util::getStringDate($discount->pivot->date);
      }
      //
      $discounts = $retirement_fund->discount_types();
      $discount = $discounts->where('discount_type_id', '3')->first();

      $loans = InfoLoan::where('affiliate_id', $affiliate->id)->get();

      if (isset($discount->id) && $discount->pivot->amount > 0) {
        $payment .= $this->getFlagy($discounts_number, $flagy, "la suma ");
        $payment .= "total de <b>" . Util::formatMoneyWithLiteral(($discount->pivot->amount ?? 0)) . "</b> por concepto de garantía de préstamo, a favor ";
        $discount_footer = true;
        $num_loans = $loans->count();
        if ($num_loans == 1)
          $payment .= " del señor ";
        else
          $payment .= " de los señores ";
        $i = 0;
        foreach ($loans as $loan) {
          $i++;
          if ($i != 1) {
            if ($num_loans - $i == 0)
              $payment .= " y ";
            else
              $payment .= ", ";
          }
          $payment .= $loan->affiliate_guarantor->fullName() . " con C.I. N° " . $loan->affiliate_guarantor->identity_card;
          $payment .= " en la suma de <b>" . Util::formatMoneyWithLiteral($loan->amount) . "</b>";
        }
        //$payment .= " en conformidad al contrato de préstamo Nro. ".($discount->pivot->note_code??'sin nro')." y la nota ".($discount->pivot->code??'sin nota')." de fecha ". Util::getStringDate($discount->pivot->date) ." de la Dirección de Estrategias Sociales e Inversiones";
      }
      if ($discount_footer) {
        $payment .= " en conformidad al contrato de préstamo Nro. " . ($discount->pivot->note_code ?? 'sin nro') . " y la nota " . ($discount->pivot->code ?? 'sin nota') . " de fecha " . Util::getStringDate($discount->pivot->date) . " de la Dirección de Estrategias Sociales e Inversiones";
      }
      $payment .= ". Reconocer los derechos y se otorgue el " . $retirement_fund->procedure_modality->procedure_type->name . " por <strong class='uppercase'>" . ($retirement_fund->procedure_modality->name) . "</strong> a favor ";
    } else {
      $payment .= "reconocer los derechos y se otorgue el " . $retirement_fund->procedure_modality->procedure_type->name . " por <strong class='uppercase'>" . $retirement_fund->procedure_modality->name . "</strong> a favor ";
    }
    if ($retirement_fund->procedure_modality_id == 4 || $retirement_fund->procedure_modality_id == 1) {
      $beneficiaries_count = RetFunBeneficiary::where('retirement_fund_id', $retirement_fund->id)->count();
      $payment .= " de " . ($beneficiaries_count > 1 ? "los beneficiarios " : ($applicant->gender ? "el beneficiario " : "la beneficiaria ")) . ($affiliate->gender == 'M' ? "del " : "de la ") . $affiliate->fullNameWithDegree() . " con C.I. N° " . $affiliate->identity_card . "., en el monto de <strong>" . Util::formatMoneyWithLiteral($retirement_fund->total) . "</strong> de la siguiente manera: <br><br>";
    } else {
      $payment .= " de:<br><br>";
    }
    $reserved = false;
    if ($retirement_fund->procedure_modality_id == 4 || $retirement_fund->procedure_modality_id == 1) {
      $beneficiaries = RetFunBeneficiary::where('retirement_fund_id', $retirement_fund->id)->orderBy('kinship_id')->orderByDesc('state')->get();
      foreach ($beneficiaries as $beneficiary) {
        if (!$beneficiary->state && !$reserved) {
          $reserved = true;
          $reserved_quantity = RetFunBeneficiary::where('retirement_fund_id', $retirement_fund->id)->where('state', false)->count();
          $certification = $beneficiary->testimonies()->first(); //PRINT CUOTA PARTE DICTAMEN LEGAL
          $payment .= "Mediante certificación " . $certification->document_type . "-N° " . $certification->number . " de " . Util::getStringDate($certification->date) . " emitido en la ciudad de " . $certification->place . ", se evidencia
                    la descendencia del titular fallecido; por lo que, se mantiene en reserva" . ($reserved_quantity > 1 ? " las Cuotas Partes " : " la Cuota Parte ") . " salvando los derechos del beneficiario " . ($affiliate->gender == "M" ? "del " : "de la ") . $affiliate->fullNameWithDegree() . " con C.I. N° " . $affiliate->identity_card .
            ". conforme establece el Art. 1094 del Código Civil, hasta que presenten la correspondiente Declaratoria de Herederos o Aceptación de Herencia y demás requisitos establecidos de conformidad con los Arts. 29, 34, 35 y 41 del Reglamento de Fondo de Retiro Policial Solidario, aprobado mediante Resolución de Directorio N° 31/2017 en fecha 24 de agosto de 2017 y modificado mediante Resoluciones de Directorio Nros. 36/2017 de 20 de septiembre de 2017, 51/2017 de 29 de diciembre de 2017 y 05/2019 de 20 de frebrero de 2019, de la siguiente manera:<br><br>";
        }
        if (Util::isChild($beneficiary->birth_date)) {
          $payment .= 'Menor ';
        } else {
          $payment .= $beneficiary->gender == 'M' ? 'Sr. ' : 'Sra. ';
        }
        $payment .= $beneficiary->fullName();
        if ($beneficiary->identity_card)
          $payment .= " con C.I. N° " . $beneficiary->identity_card;
        $beneficiary_advisor = RetFunAdvisorBeneficiary::where('ret_fun_beneficiary_id', $beneficiary->id)->first();
        if (isset($beneficiary_advisor->id)) {
          $advisor = RetFunAdvisor::where('id', $beneficiary_advisor->ret_fun_advisor_id)->first();
          $payment .= ", a través de su tutor" . ($advisor->gender == 'F' ? 'a' : '') . " natural " . ($advisor->gender == 'M' ? 'Sr.' : 'Sra.') . " " . Util::fullName($advisor) . " con C.I. N°" . $advisor->identity_card . ".";
        }
        $beneficiary_legal_guardian = RetFunLegalGuardianBeneficiary::where('ret_fun_beneficiary_id', $beneficiary->id)->first();
        if (isset($beneficiary_legal_guardian->id)) {
          $legal_guardian = RetFunLegalGuardian::where('id', $beneficiary_legal_guardian->ret_fun_legal_guardian_id)->first();
          $payment .= " por si o representada legamente por " . ($legal_guardian->gender == 'M' ? "el Sr." : "la Sra. ") . " " . Util::fullName($legal_guardian) . " con C.I. N° " . $legal_guardian->identity_card . ".
                    conforme establece la Escritura Pública sobre Testimonio de Poder especial, amplio y suficiente N° " . $legal_guardian->number_authority . " de " . Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority)) . " emitido por " . $legal_guardian->notary . ".";
        }
        $payment .= ', en el monto de<strong> ' . Util::formatMoneyWithLiteral($beneficiary->amount_total) . '</strong> ' . 'en calidad de ' . $beneficiary->kinship->name . ".<br><br>";
      }
    } else {
      $payment .= $affiliate->degree->shortened . " " . $affiliate->fullName() . " con C.I. N° " . $affiliate->identity_card . "., el monto de &nbsp;<strong>" . Util::formatMoneyWithLiteral($retirement_fund->total) . ".</strong>";
    }

    ///------EN  PAYMENT ------///
    // $number = Util::getNextAreaCode($retirement_fund->id);
    $number = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 26)->first();//Llevara el mismo codigo que el de la Resolución


    $bar_code = \DNS2D::getBarcodePNG(($retirement_fund->getBasicInfoCode()['code'] . "\n\n" . $retirement_fund->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
    /*HEADER FOOTER*/
    $footerHtml = view()->make('ret_fun.print.legal_footer', ['bar_code' => $bar_code])->render();
    $headerHtml = view()->make('ret_fun.print.legal_header')->render();
    $user = Auth::user();
    $data = [
      'ret_fun' => $retirement_fund,
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
      'body_due'  =>  $body_due,
      'payment'   =>  $payment
    ];

    $pages = [];
    for ($i = 1; $i <= 3; $i++) {
      $pages[] = \View::make('ret_fun.print.legal_dictum', $data)->render();
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

  public function printHeadshipReview($ret_fun_id)
  {
    $retirement_fund =  RetirementFund::find($ret_fun_id);
    $affiliate = Affiliate::find($retirement_fund->affiliate_id);
    //$correlatives = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->get();
    //$wf_states = WorkflowState::where('sequence_number','!=',0)->where('role_id','<','28')->where('module_id',3)->orderBy('sequence_number')->get();
    $documents = array();
    array_push($documents, 'LLENADO DE FORMULARIO CON CARÁCTER DE DECLARACIÓN JURADA');
    array_push($documents, 'CERTIFICACIÓN DE ARCHIVO Y REVISIÓN DE ANTECEDENTES');
    array_push($documents, 'CERTIFICACIÓN Y VALIDACIÓN DE DOCUMENTOS POR EL ÁREA LEGAL');
    $valid_contributions = ContributionType::select('id')->where('operator', '+')->pluck('id');
    $quantity = Util::getRetFunCurrentProcedure()->contributions_number;
    $contributions_count = Contribution::where('affiliate_id', $affiliate->id)
      ->whereIn('contribution_type_id', $valid_contributions)
      ->count();
    if ($contributions_count >= $quantity) {
      array_push($documents, 'CERTIFICACIÓN DE APORTES EN EL SERVICIO ACTIVO');
    }
    $item_cero_ids = [2, 3];
    $item0 =  Contribution::where('affiliate_id', $affiliate->id)
      ->whereIn('contribution_type_id', $item_cero_ids)
      ->count();
    if ($item0 > 0) {
      array_push($documents, 'CERTIFICACIÓN DE APORTES ITEM "0"');
    }
    $valid_contributions = ContributionType::where('name', '=', 'Disponibilidad Con Aporte')->select('id')->pluck('id');
    $availability = Contribution::where('affiliate_id', $affiliate->id)
      ->whereIn('contribution_type_id', $valid_contributions)
      ->count();
    if ($availability > 0) {
      array_push($documents, 'CERTIFICACIÓN DE APORTES EN DISPONIBILIDAD');
    }
    $security_contributions = ContributionType::where('name', '=', 'Período de Batallón de Seguridad Física Con Aporte')->first();
    $security_no_contributions = ContributionType::where('name', '=', 'Período de Batallón de Seguridad Física Sin Aporte')->first();

    $contributions = Contribution::where('affiliate_id', $affiliate->id)
      ->where(function ($query) use ($security_contributions, $security_no_contributions) {
        $query->where('contribution_type_id', $security_contributions->id)
          ->orWhere('contribution_type_id', $security_no_contributions->id);
      })
      ->count();
    if ($contributions > 0) {
      array_push($documents, 'CERTIFICACIÓN DE BATALLÓN DE SEGUIRIDAD FÍSICA');
    }
    $certification_contribution = ContributionType::where('name', '=', 'Período Certificación Con Aporte')->first();
    $certification_no_contribution = ContributionType::where('name', '=', 'Período Certificación Sin Aporte')->first();
    $no_work_period = 9;
    $contributions = Contribution::where('affiliate_id', $affiliate->id)
      ->whereIn('contribution_type_id', [$certification_contribution->id, $certification_no_contribution->id, $no_work_period])
      ->count();
    if ($contributions > 0) {
      array_push($documents, 'CERTIFICACIÓN DE PERIODOS SIN APORTES');
    }
    $tag = $retirement_fund->tags->where('name', 'Trabajo Social')->count();
    array_push($documents, 'CERTIFICACIÓN DE PAGOS ANTERIORES (DIRECCIÓN DE ASUNTOS ADMINISTRATIVOS)');
    array_push($documents, 'CERTIFICACIÓN DE DEUDA (DIRECCIÓN DE ESTRATEGIAS SOCIALES E INVERSIONES)');
    array_push($documents, 'CALIFICACIÓN DE FONDO DE RETIRO');
    if ($retirement_fund->total_availability != null) {
      if($retirement_fund->total_availability > 0){
         array_push($documents, 'DEVOLUCIÓN DE DESCUENTOS EN DISPONIBILIDAD');
     }
    }
    if ($tag > 0) {
      array_push($documents, 'INFORME DE TRABAJO SOCIAL (PRIORIZACIÓN DE TRÁMITES)');
    }
    //array_push($documents, 'DICTAMEN LEGAL');

    $bar_code = \DNS2D::getBarcodePNG($retirement_fund->getBasicInfoCode()['code'], "QRCODE");
    // $bar_code = \DNS2D::getBarcodePNG(($retirement_fund->getBasicInfoCode()['code'] . "\n\n" . $retirement_fund->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
    $footerHtml = view()->make('ret_fun.print.footer', ['bar_code' => $bar_code])->render();


    // $number = Util::getNextAreaCode($retirement_fund->id);
    $number = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 24)->first();
    $legal_dictum_number = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 24)->first();//era 25 se obtendra del misma area
    $number->note = $legal_dictum_number->note;
    $number->save();
    $user = User::find($number->user_id);

    $data = [
      'retirement_fund' => $retirement_fund,
      'documents' =>  $documents,
      'correlative'   =>  $number,
      'user'  =>  $user,
      'affiliate' =>  $affiliate,
      'title' =>  $retirement_fund->procedure_modality->procedure_type->second_name,
      'area'  =>  $number->wf_state->first_shortened,
      'date'  =>   Util::getDateFormat($number->date),
      'code'  =>  $retirement_fund->code
    ];

    return \PDF::loadView('ret_fun.print.headship_review', $data)
      ->setOption('encoding', 'utf-8')
      // ->setOption('header-html', $headerHtml)
      ->setOption('footer-html', $footerHtml)
      ->setOption('margin-bottom', 15)
      ->stream("jefaturaRevision.pdf");
  }
  public function printLegalResolution($ret_fun_id)
  {

    $retirement_fund =  RetirementFund::find($ret_fun_id);
    $affiliate = Affiliate::find($retirement_fund->affiliate_id);
    $regional_city = Util::ucw(City::find($retirement_fund->city_end_id)->name);
    $applicant = RetFunBeneficiary::where('type', 'S')->where('retirement_fund_id', $retirement_fund->id)->first();
    $ret_fun_beneficiary = RetFunLegalGuardianBeneficiary::where('ret_fun_beneficiary_id', $applicant->id)->first();

    $affiliate_name = ' <b> '.$affiliate->fullNameWithDegree().'</b> con C.I. <b>N°'.$affiliate->identity_card.'</b>';
    $legal_guardian_viewed='';
    //testimonio de derechohabiente
    $person ='';
    if ($retirement_fund->procedure_modality_id == ID::retFun()->fallecimiento_id || $retirement_fund->procedure_modality_id == ID::retFunGlobalPay()->fallecimiento_id || $retirement_fund->procedure_modality_id == ID::retFunDevPay()->fallecimiento_id) {
      if($applicant->testimonies()->first()){
      $testimony_applicant = Testimony::find($applicant->testimonies()->first()->id);
      $beneficiaries = $testimony_applicant->ret_fun_beneficiaries;
      $quantity = $beneficiaries->count();
      $start_message = false;
     if ($quantity > 2) {
        $person .= ' y de los derechohabientes ';
        $start_message = true;
      }
      foreach ($beneficiaries as $beneficiary) {
        if ($beneficiary->id != $applicant->id) {
          if (!$start_message) {
            $person = $person .= ' y ' . ($beneficiary->gender == 'M' ? 'del' : 'de la') . ' derechohabiente ';
          }
          $person .= Util::fullName($beneficiary).' con C.I. N° '.$beneficiary->identity_card.' en calidad de '.$beneficiary->kinship->name . ((--$quantity) == 2 ? " y " : (($quantity == 1) ? '' : ', '));
        }
      }
      $quantity = $beneficiaries->count();
      if ($quantity > 1) {
        $person .= ' como herederos legales acreditados mediante ' .$testimony_applicant->document_type . ' Nº ' . $testimony_applicant->number .' de fecha '.Util::getStringDate($testimony_applicant->date).' sobre Declaratoria de Herederos, emitido por ' . $testimony_applicant->court . ' de ' . $testimony_applicant->place . ' a cargo de ' . $testimony_applicant->notary;
      } else {
        $person .= ' como ' . ($applicant->gender == 'M' ? 'heredero legal acreditado' : 'heredera legal acreditada') . ' mediante ' . $testimony_applicant->document_type . ' Nº ' . $testimony_applicant->number . ' de fecha ' . Util::getStringDate($testimony_applicant->date) . ' sobre Declaratoria de Herederos, emitido por ' . $testimony_applicant->court . ' de la ciudad de ' . $testimony_applicant->place . ' a cargo de ' . $testimony_applicant->notary;
      }

      $testimonies_applicant = Testimony::where('affiliate_id', $affiliate->id)->where('id', '!=', $applicant->testimonies()->first()->id)->get();
  
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
            $person .= Util::fullName($beneficiary) . ' con C.I. N° ' . $beneficiary->identity_card. ' en calidad de ' . $beneficiary->kinship->name . ((--$quantity) == 1 ? ' y ' : (($quantity == 0) ? ' ' : ', '));
          }
          if ($stored_quantity > 1) {
            $person .= ' mediante ' . $testimony->document_type . ' Nº ' . $testimony->number . ' de fecha ' . Util::getStringDate($testimony->date).' sobre Declaratoria de Herederos o Aceptación de Herencia, emitido por '.$testimony->court.' de la ciudad de '.$testimony->place.' a cargo de '.$testimony->notary. '';
          } else {
            $person .= ' como ' . ($applicant->gender == 'M' ? 'heredero legal acreditado' : 'heredera legal acreditada') . ' mediante ' . $testimony->document_type . ' Nº ' . $testimony->number .' de fecha '.Util::getStringDate($testimony->date). ' sobre Declaratoria de Herederos o Aceptación de Herencia, emitido por ' . $testimony->court . ' de la ciudad de ' . $testimony->place . ' a cargo de ' . $testimony->notary;
          }
        }
      }
    $person .='. Solicita(n) ';
  }
  }
  
    $viewed = 'Que, en fecha '.Util::getStringDate($retirement_fund->reception_date).', ';
    ///para verificar si tiene un Apoderado
    if (isset($ret_fun_beneficiary->id)) {
        $legal_guardian = RetFunLegalGuardian::where('id', $ret_fun_beneficiary->ret_fun_legal_guardian_id)->first();
        $legal_guardian_viewed .= ($legal_guardian->gender == 'M' ? ' el Sr. ' : 'la Sra. ') . Util::fullName($legal_guardian) . ' con C.I. N° ' .$legal_guardian->identity_card. ', a través de Testimonio Notarial N° ' .$legal_guardian->number_authority. ' de fecha ' .Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority)). ' sobre poder especial, bastante y suficiente emitido por Notaria de Fe Pública N° ' . $legal_guardian->notary_of_public_faith . ' a cargo del Notario ' . $legal_guardian->notary . ' en representación del (la) Sr. (a). ';
    }
    //Para Generar el Visto
    $modality_procedure = $retirement_fund->procedure_modality->procedure_type->name.', en su modalidad <b>'.mb_strtoupper($retirement_fund->procedure_modality->name).'</b>';

    if($retirement_fund->procedure_modality_id == ID::retFun()->fallecimiento_id || $retirement_fund->procedure_modality_id == ID::retFunGlobalPay()->fallecimiento_id||$retirement_fund->procedure_modality_id == ID::retFunDevPay()->fallecimiento_id){
      $viewed.= ($legal_guardian_viewed?$legal_guardian_viewed:($applicant->gender == 'M' ? 'el Sr. ' : 'la Sra. ')). Util::fullName($applicant) . ' con C.I. N° ' . $applicant->identity_card. ' en calidad de ' . $applicant->kinship->name.$person; //solicitante e hijos
      $viewed.=' el '.$modality_procedure.' del titular  '.$affiliate_name;
    }else{
      $viewed.= ($legal_guardian_viewed?$legal_guardian_viewed:($affiliate->gender == 'M' ? 'el Sr. ' : 'la Sra. ')).$affiliate_name.', como Titular del Trámite solicita, ';
      if($retirement_fund->procedure_modality->procedure_type_id == 21 && ($retirement_fund->procedure_modality_id == ID::retFunDevPay()->titular_id)){
        $viewed.='la '.$retirement_fund->procedure_modality->procedure_type->name;
      }else{
        $viewed.='el '.$modality_procedure;
      }
    }
    $viewed.=', presentando documentación en Ventanilla de Atención al Aﬁliado de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio en la Regional '.$regional_city.'.<br><br>';

    $considering_one = 'Que, la Mutual de Servicios al Policía al ser una institución pública descentralizada,
    bajo tuición del Ministerio de Gobierno, regula su actividad y procedimiento bajo los principios
    generales descritos en el Art. 232 de la Constitución Política del Estado, Art. 4 de la Ley 2341 y
    Art. 3 del Decreto Supremo N° 27113, cuya competencia para conocer asuntos administrativos suscitados
    tanto por la institución, así como por los <i>administrados, se sujetan a lo determinado por el Art. 5
    de la Ley de Procedimiento Administrativo</i>.
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
    reglamentación interna”</i>.<br><br>';
    if($retirement_fund->procedure_modality->procedure_type_id != 21){
    $considering_one.='Que, el Decreto Supremo N° 2829, de 06 de julio de 2016, modificatorio al Decreto Supremo Nº
    1446 de 19 de diciembre de 2012, establece que el Fondo de Retiro será objeto de un estudio
    técnico financiero y estudio actuarial que asegure su sostenibilidad, en el marco del principio de
    solidaridad.<br><br>
    Que, el Decreto Supremo N°3231, de 28 de junio de 2017, modificatoria al Decreto Supremo Nº
    1446 de 19 de diciembre de 2012, estableciendo que el FONDO DE RETIRO POLICIAL SOLIDARIO: <i>"Es el beneficio que brinda
    protección a los miembros del servicio activo y sus derechohabientes, mediante el
    reconocimiento de un pago único, con motivo y oportunidad del retiro definitivo de la actividad
    remunerada dependiente de la Policía Boliviana, el cual será administrado por la MUSERPOL; a
    ser otorgado en el marco del principio de solidaridad, cuando el retiro se produzca por: a)
    Jubilación, b) Fallecimiento del titular, c) Retiro forzoso, d) Retiro voluntario”</i>.
    <br><br>';}
    $considering_one.='Que, el Estudio Matemático Actuarial 2021 – 2025, aprobado mediante Resolución de Directorio Nº
    77/2021, de 21 de octubre de 2021, determina la modalidad y parámetros de calificación para la
    otorgación del beneficio de Fondo de Retiro Policial Solidario.
    <br><br>';

    $considering_two = 'Que, el Reglamento de Fondo de Retiro Policial Solidario, aprobado mediante
    Resolución de Directorio Nº 97/2021 de 01 de diciembre de 2021, en sus Artículos 1, 2, 4, 6, 7, 10, 11, 15, 16, 17, 36, 40,
    44, 47, 48, 52, reconocen el derecho a la otorgación del beneficio de Fondo de Retiro Policial Solidario.
    <br><br>';
    // if($retirement_fund->procedure_modality->procedure_type_id != 21){
    // $considering_two.='Que, los Artículos 10 y 11 del reglamento de Fondo de Retiro Policial Solidario refieren:<i>“ARTÍCULO 10. (FINANCIAMIENTO).- I.
    // El pago del beneficio de Fondo de Retiro Policial Solidario, está financiado por los aportes  obligatorios  de  los  miembros del servicio activo,
    // transferidos a la Mutual de Servicios al Policía – MUSERPOL por el Comando General de la Policía Boliviana, información que deberá ser reportada
    // por la Dirección de Beneficios Económicos y contrastada por la Dirección de Asuntos Administrativos.  II. Una alícuota de hasta el 55% de los
    // rendimientos de las inversiones y/o utilidades generadas por el portafolio de inversiones administradas por la Dirección de Estrategias Sociales e
    // Inversiones de la Mutual de Servicios al Policía - MUSERPOL. III. Los aportes directos de los (las) afiliados (as) del servicio activo de la Policía
    // Boliviana que se encuentren en comisión de servicio Ítem Cero (Ítem “0”) y aquellos afiliados (as) que se encuentren suspendidos (as) o retirados (as)
    // temporalmente de sus funciones por procesos disciplinarios y/o penales figurando en planilla de haberes con Ítem Cero (Ítem “0”), y otros; siempre y
    // cuando figuren en planilla de haberes y/o lista de revista del Comando General de la Policía Boliviana. ARTÍCULO 11. (PRIMA DE FINANCIAMIENTO). -
    // La prima de financiamiento es el porcentaje de aportación determinado por el Estudio Matemático Actuarial sobre el cuál los (las) afiliados (as) del
    // sector activo efectivizan su aporte para la otorgación del beneficio. I. El porcentaje de aporte obligatorio determinado por el Estudio Matemático
    // Actuarial 2021 — 2025, para el Fondo de Retiro Policial Solidario de los (las) afiliados (as) del sector activo de la Policía Boliviana, es del 4,77 %
    // sobre la totalidad de sus ingresos cotizables mensuales sin ningún tipo de descuentos (…)”</i>, son los parámetros que establece el rendimiento e inversiones
    // para el beneficio de Fondo de Retiro Policial Solidario, asimismo cuanto es el porcentaje de aportación por parte del sector activo de sus ingresos cotizables mensuales.<br><br>';
    // }
    if($retirement_fund->procedure_modality->procedure_type_id == 1){ // Pago Global de aportes
    $considering_two.='Que, el Artículo 22 del reglamento de Fondo de Retiro Policial Solidario refiere: ARTÍCULO 22. (PAGO GLOBAL DE APORTES).- I. El pago global de aportes procederá,
    cuando el (la) afiliado (a) no cumpla con sesenta (60) cotizaciones (5 años) para acceder al pago del beneficio de Fondo de Retiro Policial Solidario, antes de su desvinculación
    laboral con la Policía Boliviana. II. El Pago Global de aportes a él (la) titular o derechohabientes, será determinado en base al monto total de los aportes realizados a la
    institución hasta el momento de su desvinculación laboral con la Policía Boliviana, más el 5% de rendimiento actuarial. III. No corresponderá el pago global de aportes en casos de Retiro Forzoso o Retiro Voluntario.
    <br><br>
    Que, el Artículo 23 del reglamento de Fondo de Retiro Policial Solidario refiere:<i> ARTÍCULO 23 (REQUISITOS PARA EL PAGO GLOBAL DE APORTES).- Los requisitos a ser presentados por el (la) solicitante
    de acuerdo a las causales establecidas en el Artículo anterior son: a) Pago global de aportes por fallecimiento 1. Comprobante de depósito o de transferencia por concepto de adquisición de folder y
    formularios en la cuenta fiscal de la MUSERPOL. 2. Formulario de verificación de requisitos con carácter de Declaración Jurada y solicitud, a ser otorgado por la MUSERPOL a momento de inicio del
    trámite. 3. Fotocopia simple de la Cédula de Identidad del titular, vigente a la fecha de solicitud. 4. Certificado original y actualizado de defunción del titular.  5. Fotocopia simple y vigente
    de la Cédula de Identidad de los derechohabientes. 6. Certificado original y actualizado de matrimonio, o certificado original y actualizado de unión libre o de hecho emitido por el Servicio de
    Registro Cívico - SERECI o Resolución original o copia legalizada de reconocimiento de matrimonio de hecho ante autoridad competente. En caso de que el afiliado policial no hubiese contraído nupcias,
    deberán adjuntar el certificado de inexistencia de partida matrimonial emitido por el SERECI en original. 7. Certificado original y actualizado de descendencia del titular fallecido emitido por el
    SERECI. 8. Declaratoria de Herederos o Aceptación de Herencia original o copia legalizada, en caso de Herederos por sucesión testamentaria presentar "Testamento" original o copia legalizada, dentro
    del cual señale expresamente la otorgación del beneficio. 9. Certificado de años de servicio desglosado original o copia legalizada, otorgado por el Comando General de la Policía Boliviana hasta
    la fecha de fallecimiento. 10. Certificado original de haberes otorgado por el Comando General de la Policía Boliviana, hasta la fecha de fallecimiento (…)”</i>, por tanto, al verificarse la documentación
    adjunta a la solicitud presentada, se determina el cumplimiento del mismo.<br><br>';
    }
    // if($retirement_fund->procedure_modality->procedure_type_id == 21){ //devolucion de Aportes
    // $considering_two.='Que, el Artículo 27 del reglamento de Fondo de Retiro Policial Solidario refiere:<i>“(DEVOLUCIÓN DE APORTES). – I. La Dirección de Asuntos Administrativos de la Mutual de Servicios al
    // Policía – MUSERPOL, efectivizará la devolución de aportes sin ningún tipo de rendimientos ni mantenimiento de valor a aquellos ex afiliados (as) que no accedan al pago del Fondo de Retiro ni al Pago
    // Global de Aportes, que se hubiesen desvinculado de la institución policial dentro de los diez (10) años anteriores a la presentación de su solicitud formal, previo informe de Jefatura y certificación
    // emitida por el Área de Cuentas Individuales de la Unidad de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio.<b> II. Corresponderá la devolución de los aportes realizados sin ningún tipo de
    // rendimientos ni mantenimiento de valor a aquellos afiliados (as) que no accedan al beneficio de Fondo de Retiro ni al Pago Global de Aportes por haber transcurrido más de diez (10) años de su desvinculación
    // de la institución policial</b>, sin que se hubiese generado la solicitud formal de pago con anterioridad. III. De presentarse una solicitud de pago del Fondo de Retiro Policial Solidario por afiliados o
    // derechohabientes que no pudieron acceder al mismo por prescripción, se acogerán a lo determinado por la Disposición Transitoria Séptima del presente reglamento. IV. Para la devolución de aportes,
    // los (las) afiliados (as) deberán presentar los siguientes documentos:</i> ';
    //   if($retirement_fund->procedure_modality_id == ID::retFunDevPay()->titular_id){//sub modalidad Titular
    //     $considering_two.='<b>a) Devolución de aportes a titulares 1.</b> Comprobante de depósito o de transferencia por concepto de adquisición de folder y formularios en la cuenta fiscal de la MUSERPOL. <b>2.</b>
    //     Formulario de verificación de requisitos con carácter de Declaración Jurada y solicitud, a ser otorgado por la MUSERPOL a momento de inicio del trámite. <b>3.</b> Fotocopia simple de la Cédula de Identidad
    //     del titular, vigente a la fecha de solicitud. <b>4.</b> Certificado de años de servicio desglosado, en original o copia legalizada otorgado por el Comando General de la Policía Boliviana. <b>5.</b> En caso de no
    //     contar con el certificado de años de servicio desglosado o a requerimiento del área pertinente, se deberán adjuntar el original de las boletas de pago correspondientes”.<br><br>';
    //   }
    //   if($retirement_fund->procedure_modality_id == ID::retFunDevPay()->fallecimiento_id){//sub modalidad Fallecimiento
    //     $considering_two.='<b>b) Devolución de aportes en caso de fallecimiento 1.</b> Comprobante de depósito o de transferencia por concepto de adquisición de folder y formularios en la cuenta fiscal de la
    //     MUSERPOL. 2. Formulario de verificación de requisitos con carácter de Declaración Jurada y solicitud, a ser otorgado por la MUSERPOL a momento de inicio del trámite. 3. Fotocopia simple de la
    //     Cédula de Identidad del titular, vigente a la fecha de solicitud. 4. Certificado de defunción original del titular. 5. Certificado de años de servicio desglosado, en original o copia legalizada
    //     otorgado por el Comando General de la Policía Boliviana. 6. Declaratoria de Herederos o Aceptación de Herencia original o copia legalizada. 7. Fotocopia simple de la Cédula de Identidad de los
    //     derechohabientes, vigente a la fecha de solicitud. 8. En caso de no contar con el certificado de años de servicio desglosado o a requerimiento del área pertinente, se deberán adjuntar el
    //     original de las boletas de pago correspondientes”.<br><br>';
    //   }
    // }
    // if($retirement_fund->procedure_modality->procedure_type_id != 21){
    $considering_two.='Que, los Artículos 28 y 29 del Reglamento de Fondo de Retiro Policial Solidario refieren:<i>“ARTÍCULO 28. (NATURALEZA JURIDICA DEL BENEFICIO).- El Fondo de Retiro Policial Solidario
    se constituye en un beneficio económico que no se encuentra comprendido dentro del Código de Seguridad Social o dentro de los alcances de la seguridad social de largo plazo (Servicio Nacional
    del Sistema de Reparto – SENASIR ni del Sistema Integral de Pensiones – SIP) en razón a su fuente de financiamiento (…). ARTÍCULO 29. (MODALIDADES DEL FONDO DE RETIRO POLICIAL SOLIDARIO).- El Beneficio
    de Fondo de Retiro Policial Solidario, se otorgará en los siguientes casos: a) Fondo de Retiro Policial Solidario por Jubilación/Invalidez. b) Fondo de Retiro Policial Solidario por Fallecimiento. c) Fondo
    de Retiro Policial Solidario por Retiro Forzoso. d) Fondo de Retiro Policial Solidario por Retiro Voluntario”</i>, establece la definición del Beneficio de Fondo de Retiro y en que consiste la otorgación del
    mismo, señalando que es un beneficio económico y no así un beneficio social.
    <br><br>';
    // if($retirement_fund->procedure_modality->procedure_type_id == 2){
    //   $considering_two.='Que, el Artículo 44 del reglamento de Fondo de Retiro Policial Solidario refiere:<i>“(REQUISITOS PARA SOLICITUDES NUEVAS). - I. Las solicitudes nuevas para el pago del beneficio de Fondo de Retiro Policial Solidario, que
    // ingresen a partir de la aprobación del presente Reglamento deberán contener los siguientes documentos: <b>a.) Requisitos generales</b> 1. Comprobante de depósito o de transferencia por concepto de adquisición de folder y formularios
    // en la cuenta fiscal de la MUSERPOL. 2. Formulario de verificación de requisitos con carácter de Declaración Jurada y solicitud, a ser otorgado por la MUSERPOL a momento de inicio del trámite. 3. Fotocopia simple de la Cédula
    // de Identidad del titular, vigente a la fecha de solicitud. 4. Memorándum original o copia legalizada de Agradecimiento de Servicios emitido por el Comando General de la Policía Boliviana, dirigido a nombre del titular.  5.
    // Memorándum original o copia legalizada de destino a disponibilidad a las letras "C" y "A" (reserva activa) según corresponda. Este documento debe ser el del último lugar de destino de trabajo transcrito a nombre del (la) titular o en su caso,
    // adjuntar la Certificación de ingreso o no ingreso a la Disponibilidad en original, emitido por el Comando General de la Policía Boliviana. 6. Certificado original de haberes otorgado por el Comando General de la Policía Boliviana, considerando
    // los últimos sesenta (60) meses, antes de su ingreso a la disponibilidad (reserva activa). 7. Certificado de años de servicio desglosado, en original o copia legalizada otorgado por el Comando General de la Policía Boliviana</i>';
      // if($retirement_fund->procedure_modality_id == ID::retFun()->fallecimiento_id){//sub modalidad Fallecimiento
      //   $considering_two.='. <i><b>b). Requisitos Específicos(...), <u>b.2) Fondo de Retiro Policial Solidario por Fallecimiento:</u></b> 1. Certificado original y actualizado de defunción del titular. 2. Fotocopia simple y vigente de la Cédula de
      //   Identidad de los derechohabientes. 3. Certificado original y actualizado de Matrimonio o certificado original y actualizado de unión libre o de hecho emitido por el "SERECI" o Resolución original o copia legalizada de reconocimiento
      //   de matrimonio de hecho ante autoridad competente. En el caso de que el afiliado policial no hubiese contraído nupcias, deberán adjuntar el certificado de inexistencia de partida matrimonial emitido por el SERECI en original. 4. Certificado
      //   original y actualizado de descendencia del titular fallecido, emitido por el SERECI. Este documento, al tener una validez de treinta (30) días, debe estar plenamente vigente a momento de la presentación y/o recepción de la documentación. 5.
      //   Declaratoria de Herederos o Aceptación de Herencia, original o copia legalizada; en el caso de herederos por sucesión testamentaria presentar “Testamento” original o copia legalizada, dentro del cual señale expresamente la otorgación del
      //   beneficio. 6. En caso de suscitarse el fallecimiento en el periodo de disponibilidad (reserva activa), el certificado de haberes deberá contemplar todos los periodos hasta el último aporte efectivizado</i>';
      // }
      // if($retirement_fund->procedure_modality_id == ID::retFun()->retiro_forzoso_id){//sub modalidad retiro forzoso
      //   $considering_two.='. <i><b>b). Requisitos Específicos(...),  <u>b.3) Fondo de Retiro Policial Solidario por Retiro Forzoso:</u></b> 1. Resolución y/o Memorándum de baja definitiva emitida por el Comando General de la Policía Boliviana, dirigido a
      //   nombre del titular, en original o copia legalizada. 2. En caso de efectivizarse la baja mientras el afiliado se encuentre en el destino de disponibilidad de las letras (reserva activa), el certificado de haberes deberá contemplar todos los
      //   periodos hasta el último aporte efectivizado.3. Certificado original de ingreso o no ingreso a Disponibilidad emitido por el Comando General de la Policía Boliviana</i>';
      // }
      // if($retirement_fund->procedure_modality_id == ID::retFun()->retiro_voluntario_id){//sub modalidad voluntario
      //   $considering_two.='. <i><b>b). Requisitos Específicos, <u>b.4) Fondo de Retiro por Retiro Voluntario:</u></b> 1. Resolución y/o Memorándum original o copia legalizada de baja definitiva a solicitud voluntaria, emitida por el Comando General de la Policía
      //   Boliviana, dirigido a nombre del titular. 2. En caso que la baja se efectivice mientras el afiliado se encuentre en el destino de disponibilidad de las letras (reserva activa), el certificado de haberes deberá contemplar todos los periodos
      //   hasta el último aporte efectivizado. 3. Certificado original de ingreso o no ingreso a Disponibilidad emitido por el Comando General de la Policía Boliviana. 4. En caso de haber prestado servicios en el Batallón de Seguridad Física
      //   (Ex Privada, en periodos anteriores a Mayo/2007), deberá presentar adicionalmente documentación requerida por la Mutual de Servicios al Policía – MUSERPOL</i>';
      // }
      // $considering_two.='<i>(...)”</i>, por tanto, al verificarse la documentación adjunta a la solicitud presentada, se determina el cumplimiento del mismo.<br><br>';
    //  }
    // }
    $considering_two.= 'Que, el Artículo 61 del Reglamento del Beneficio de Fondo de Retiro Policial Solidario refiere:<i>“(DEFINICIÓN Y CONFORMACIÓN), Parágrafo I refiere: La Comisión de Beneficios Económicos, es la instancia técnica y legal que mediante acto administrativo determina la
    otorgación del beneficio de Fondo de Retiro Policial Solidario. Es designada mediante Resolución Administrativa de la Dirección General Ejecutiva de la Mutual de Servicios al Policía - MUSERPOL.”</i>. Por consiguiente, la Resolución Administrativa
    N° 002/2024 del 02 de enero de 2024, conforma la Comisión de Beneficios Económicos, en cumplimiento al Reglamento.
    <br><br>
    Que, el Artículo 62 del Reglamento de Fondo de Retiro Policial Solidario refiere:<i>“(ATRIBUCIONES).- La Comisión de Beneﬁcios Económicos tiene las siguientes atribuciones: 1. Conocer y resolver los casos pendientes de acuerdo a lo establecido en el parágrafo I de la Disposición 
    Transitoria Única del Decreto Supremo No. 3231 de fecha 28 de junio de 2017:  a) Montos dejados en cuota parte en reserva. b) Recursos de Reclamación. c) Carpetas en curso de Trámite. d) Casos especiales determinados por la Comisión”.</i> Es así que la comisión de beneﬁcios económicos 
    en consideración de todos los antecedentes y la documentación adjunta a la presentación del trámite y certiﬁcaciones de las diferentes áreas de la Unidad de Otorgación de Fondo de Retiro Policial Solidario, se emite la presente Resolución.<br>';
    
    $total_discounts = $retirement_fund->discount_types();
    $discount_loan = $total_discounts->where('discount_type_id', '2')->first();
    $total_discounts = $retirement_fund->discount_types();
    $discount_guarantee = $total_discounts->where('discount_type_id', '3')->first();
    $total_discounts = $retirement_fund->discount_types();
    $discounts_counter = $total_discounts->where('discount_type_id', '>', '1')->where('amount', '>', '0')->count();  

    if($discounts_counter > 0){
      $considering_two.= '<br>Que, el Reglamento de Fondo de Retiro Policial Solidario, aprobado mediante Resolución de Directorio Nº 97/2021 de 01 de diciembre de 2021, en sus Artículos 89, 90, 91, 92, 93 y 94 disponen el pago de deuda con la MUSERPOL y la retención de importes y descuentos por 
      garantía conforme a solicitud de la Dirección de Estrategias Sociales e Inversiones.
      <br>';
    }
    // if($retirement_fund->procedure_modality->procedure_type_id == 21){
    // $considering_two.='Que, la DISPOSICIÓN TRANSITORIA SÉPTIMA, refiere: “Se dará curso a la devolución de aportes a aquellos afiliados (as) que presenten una nueva solicitud de devolución, siempre y cuando con anterioridad no se hubiese generado la emisión
    // de una Resolución de Prescripción y esta no esté debidamente ejecutoriada”.
    // <br>';
    // }
    $number = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 26)->first();
    $considering_three = '';
    if ($number->note != '') {
      $considering_three.= $number->note . '<br>';
    }
    
    $affiliate_folders = AffiliateFolder::where('affiliate_id', $affiliate->id)->get()->count();

    $wf_states = WorkflowState::where('sequence_number','!=',0)->where('module_id',3)->orderBy('sequence_number')->get();
    $discounts = $retirement_fund->discount_types();
    $finance = $discounts->where('discount_type_id', '1')->first();
      foreach($wf_states as $wf_state){
        $certification_date = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id',$wf_state->id)->first();

        if(isset($certification_date)){
        switch($certification_date->wf_state_id) {
          case 19:
            $considering_three.= 'Que, mediante Formulario de Recepción de ventanilla de atención al afiliado de la Unidad de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, se registra el trámite N° ' .$certification_date->code. ' de fecha ' 
            .Util::getStringDate($certification_date->date).', debiéndose aplicar el reglamento vigente a la fecha de presentación de la solicitud formal, en virtud del principio de rogación establecido en el Art. 47 del Reglamento de Fondo de Retiro Policial Solidario.<br><br>';
          break;
          case 20: //Area de Archivo
            //Certificación de Archivo
            $considering_three .= 'Que, mediante Certificación N° '.$certification_date->code.', de Archivo de la Dirección de Beneficios Económicos de fecha '. Util::getStringDate($certification_date->date).',
            se establece que el trámite signado con el N° '.$retirement_fund->code.($affiliate_folders == 0?' no':' si').' tiene expediente del referido titular.<br><br>';
            //Certificación de Asuntos administrativos
            $administrative_certification = 'Que, mediante nota de respuesta '.($finance->pivot->code ?? 'sin cite').' de la Dirección de Asuntos Administrativos de fecha '. Util::getStringDate(($finance->pivot->date ?? '')) .',
              refiere que '.($affiliate->gender == 'M' ? 'el' : 'la').' titular del beneficio ';
              if (isset($finance->id) &&  $finance->pivot->amount > 0) {
                $administrative_certification .= 'si cuenta con registro de pagos o anticipos por concepto de Fondo de Retiro Policial.<br><br>';
              } else {
                $administrative_certification .= 'no cuenta con registro de pagos o anticipos por concepto de Fondo
                de Retiro Policial, por tanto se encuentra habilitado para la continuidad del trámite.<br><br>';
              }
            $considering_three.= $administrative_certification;
          break;
          case 21: //area de Revisión Legal
            $legacy_area_cetification = 'Que, mediante Certificación N° '.$certification_date->code.' del Área Legal de la Unidad de Otorgación del Fondo de Retiro Policial Solidario,
            Cuota y Auxilio Mortuorio, de fecha '. Util::getStringDate($certification_date->date).', fue verificada y validada la documentación presentada por el
            titular del trámite signado con el N° '.$retirement_fund->code.', cumpliendo con los requisitos conforme a normativa legal.<br><br>';
            $considering_three.= $legacy_area_cetification;
          break;
          case 22: //área de cuentas individuales
            $months  = $affiliate->getTotalQuotes();
           $individual_accounts = 'Que, mediante Certificación de Aportes N° '.$certification_date->code.' del Área de Cuentas Individuales de la Unidad
            de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha '. Util::getStringDate($certification_date->date).',
            se determina los últimos '.($retirement_fund->procedure_modality->procedure_type_id==2?'60':'').' aportes anteriores a su destino de la disponibilidad o desvinculación
            definitiva de la institución policial, de acuerdo a la información obtenida en la base de datos de la institución y contrastada con la documentación adjunta en la carpeta administrativa.<br><br>';
            $considering_three.= $individual_accounts;
            //falta para la disponivilidad con aportes
            if($retirement_fund->procedure_modality->procedure_type_id == 2){// Solo para Fondo de retiro
              if($affiliate->hasAvailabilityTime()){//Tiene clasificador con Disponibilidad con y sin portes
                 $considering_three.='Que, mediante Certificación de Aportes N° '.$certification_date->code.' del Área de Cuentas Individuales de la Unidad
                 de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha '. Util::getStringDate($certification_date->date).', se determina su permanencia en el destino de disponibilidad por el periodo de <b>'.Util::formatMonthYearLiteral($affiliate->getDatesTotalAvailability()).'</b>, en los cuales,';
                 if ($affiliate->hasAvailability()) {
                   $availability = Util::sumTotalContributions($affiliate->getDatesAvailability());
                   $considering_three.='se evidencia descuento para el Beneficio de Fondo de Retiro Policial Solidario por '.Util::formatMonthYearLiteral($availability).', motivo por el cual en consideración a la Disposición Transitoria Cuarta del Reglamento del mencionado beneficio, el afiliado podrá solicitar la devolución del mismo.
                   <br><br>
                   Que, habiéndose procedido a la solicitud formal para la devolución de descuentos efectivizados durante la permanencia en el destino de la disponibilidad de las letras al Titular '.($affiliate->gender == 'M' ? ' Sr. ' : ' Sra. ').$affiliate_name.', corresponde que la misma sea atendida en concordancia con lo determinado en el Reglamento de Fondo de Retiro Policial Solidario aprobado y vigente.<br><br>';
                 }else{
                 $considering_three.='no se evidencia descuentos para el beneficio de Fondo de Retiro Policial Solidario.<br><br>';
                 }
              }
          }
          break;
          case 23://área de calificación
            $affiliate->hasAvailability();
            $months  = $affiliate->getTotalQuotes();
            $qualification = ' Que, mediante Certificación '.$certification_date->code.' del Área de Calificación de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha '.Util::getStringDate($certification_date->date).', en aplicación del Estudio Matemático Actuarial 2021 – 2025 y del
            Reglamento de Fondo de Retiro Policial Solidario que establecen los parámetros de calificación,
            se reconoce un total de <b>'.Util::formatMonthYearLiteral($months).'</b>';
            if($retirement_fund->procedure_modality->procedure_type_id==2){
              $qualification.='. Asimismo, se determinó un salario promedio cotizable de <b>'. Util::formatMoneyWithLiteral($retirement_fund->average_quotable).'</b> correspondiente a los 60
              periodos anteriores a su destino de la disponibilidad o desvinculación definitiva de la
              institución policial, determinando el beneficio de Fondo de Retiro Policial Solidario por '.mb_strtoupper($retirement_fund->procedure_modality->name).' ';
            }
            $qualification.=' por el monto de <b> '. Util::formatMoneyWithLiteral($retirement_fund->subtotal_ret_fun).'.</b><br><br>';
            $considering_three.= $qualification;
            //// INICIO PÁRRAFO DESCUENTOS DE PRESTAMOS Y GARANTIAS/////
            $discounts = $retirement_fund->discount_types();
            $discount_loan = $discounts->where('discount_type_id', '2')->first();
            $discounts = $retirement_fund->discount_types();
            $discount_guarantee = $discounts->where('discount_type_id', '3')->first();
            $discounts = $retirement_fund->discount_types();
            $discount_counter = $discounts->where('discount_type_id', '>', '1')->where('amount', '>', '0')->count();
            $loan_guarantee = '';
            if ($discount_counter == 0) {
             // if ($retirement_fund->procedure_modality->procedure_type_id != 21) {
              $loan_guarantee .= 'Que, mediante nota '.($discount_loan->pivot->code ?? 'Sin nota').' de la Dirección de Estrategias Sociales e Inversiones de fecha '.Util::getStringDate(($discount_loan->pivot->date ?? '')).', refiere que '.($affiliate->gender == 'M' ? 'el' : 'la').' titular ';
              $loan_guarantee .= 'no cuenta con deuda en curso de pago a MUSERPOL ni por concepto de garantía de préstamo';
              $considering_three.= $loan_guarantee.'.<br><br>';
              //}
            } else{
            $flagy = 0;
            if ($discount_counter > 0) {
              $header_discount = false;
              $header_garantee = false;
              if (isset($discount_loan->id) && $discount_loan->pivot->amount > 0) {
                //descuento de prestamo
                $loan_guarantee .= $this->getFlagy($discount_counter, $flagy);
                $flagy++;
                $loan_guarantee .= 'Que, mediante nota ' . $discount_loan->pivot->code.' de la Dirección de Estrategias Sociales e Inversiones de fecha '.Util::getStringDate($discount_loan->pivot->date).', refiriendo que '.($affiliate->gender == 'M' ? ' el <b>Sr. ' : ' la <b>Sra. ').'</b>'.$affiliate_name.', tiene una deuda pendiente con la MUSERPOL de conformidad al contrato de préstamo Nro. '.($discount_loan->pivot->note_code).', por el monto ';
                $loan_guarantee .= 'de <b>'.Util::formatMoneyWithLiteral($discount_loan->pivot->amount).'</b>';
                $header_discount = true;
              }
              if (isset($discount_guarantee->id) && $discount_guarantee->pivot->amount > 0) {
                $loans = InfoLoan::where('affiliate_id', $affiliate->id)->get();
                $loan_guarantee .= $this->getFlagy($discount_counter, $flagy);

                if (!$header_discount) { //no tiene descuento de prestamo pero se de garantia
                  $loan_guarantee .= 'Que, mediante nota '.$discount_guarantee->pivot->code.' de la Dirección de Estrategias Sociales e Inversiones de fecha '.Util::getStringDate($discount_guarantee->pivot->date).', refiriendo que '.($affiliate->gender == 'M' ? ' el <b>Sr. ' : ' la <b>Sra. ').'</b>'.$affiliate_name.', tiene retención por concepto de garantía,';
                  $header_garantee = true;
                } else {
                  $loan_guarantee .= '';
                }

                $num_loans = $loans->count();
                $header = false;
                if ($num_loans > 1) {
                  if($header_garantee){
                    $loan_guarantee .= ' a favor de ';
                  }else{
                  $loan_guarantee .= ' la suma total de  <b>'.Util::formatMoneyWithLiteral($discount_guarantee->pivot->amount).'</b> por concepto de garantía de préstamo, a favor de ';
                  }
                  $header = true;
                }
                $i = 0;
                foreach ($loans as $loan) {
                  if (!$header) {
                    $loan_guarantee .= ' a favor de ';
                  }
                  $i++;
                  if ($i != 1) {
                    if ($num_loans - $i == 0)
                      $loan_guarantee .= ' y ';
                    else
                      $loan_guarantee .= ', ';
                  }
                  $loan_guarantee .= ($loan->affiliate_guarantor->gender == 'M' ? 'Sr. ' : 'Sra. ').$loan->affiliate_guarantor->fullName().' con C.I. N° '.$loan->affiliate_guarantor->identity_card;
                  $loan_guarantee .= ' en la suma de <b>' . Util::formatMoneyWithLiteral($loan->amount) . '</b>';
                }
              }
            }
            $considering_three.= $loan_guarantee.'.<br><br>';
            }
            //// FIN PÁRRAFO DESCUENTOS DE PRESTAMOS Y GARANTIAS/////
          break;
          case 24://área de jefatura
            $body_qualification = 'Que, mediante Certificación de Revisión Nº '.$certification_date->code.' de '.Util::getStringDate($certification_date->date).', emitido por la Jefatura de la Unidad de
            Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, se verifica el cumplimiento de todos los procedimientos
            requeridos para la correcta determinación del beneficio de Fondo de Retiro Policial Solidario.';
            $considering_three.= $body_qualification;
          break;
        }
      }
    }
     ///inicio del inciso//
    switch($retirement_fund->procedure_modality->procedure_type->id) {
      case 1:
        $subsection = ' 23 inciso '; //pago Globla de aportes
        switch($retirement_fund->procedure_modality_id) {
          case 1:
            $subsection .= '<b>a)</b>'; //fallecimiento
            break;
          case 2:
            $subsection .= '<b>b)</b>';
            break;
          case 24:
            $subsection .= '<b>c)</b>';
            break;
        }
        break;
      case 2:
        $subsection = ' 44 inciso ';//Fondo de Retiro Policia Solidario
        switch($retirement_fund->procedure_modality_id) {
          case 3://Jubilación
            $subsection .= '<b>a)</b>';
            break;
          case 4://Fallecimiento
            $subsection .= '<b>b.2)</b>';
            break;
          case 5://Retiro forzoso
            $subsection .= '<b>b.3)</b>';
            break;
          case 7://Retiro voluntario
            $subsection .= '<b>b.4)</b>';
            break;
        }
        break;
      case 21:
          $subsection = ' 27 inciso '; //Devolución de Aportes
          switch($retirement_fund->procedure_modality_id) {
            case 62:
              $subsection .= '<b>a)</b>';
              break;
            case 63:
              $subsection .= '<b>b)</b>';
              break;
          }
        break;
    }

    ///fin del inciso//
    $then = 'Habiéndose verificado el cumplimiento de requisitos adjuntas a la carpeta del según lo señalado el Art.';
    $directory_resolution = 'del Reglamento de Fondo de Retiro Policial Solidario aprobado mediante Resolución de Directorio N° 97/2021 en fecha 01 de diciembre de 2021 y efectivizado el procesamiento del trámite y conforme el Art. 48  (Procedimiento) del referido Reglamento, corresponde dar curso al ';
    if($retirement_fund->procedure_modality->procedure_type_id==21){
      if($retirement_fund->procedure_modality_id == ID::retFunDevPay()->titular_id){
        $then.=$subsection.' ('.$retirement_fund->procedure_modality->procedure_type->name.' a '.$retirement_fund->procedure_modality->name.') '.$directory_resolution .' pago de '.$retirement_fund->procedure_modality->procedure_type->name.($affiliate->gender == 'M' ? ' al Sr. ' : 'a la Sra. ').$affiliate_name.'.';
      }else{
        $then.=$subsection.' ('.$retirement_fund->procedure_modality->procedure_type->name.' por '.$retirement_fund->procedure_modality->name.') '.$directory_resolution .' pago de '.$retirement_fund->procedure_modality->procedure_type->name.'del titular'.$retirement_fund->procedure_modality->name.($affiliate->gender == 'M' ? ' Sr. ' : ' Sra. ').$affiliate_name.' a favor de sus derechohabientes.';
      }
    }else{
      $then.=$subsection.' ('.$retirement_fund->procedure_modality->procedure_type->name.' por '.$retirement_fund->procedure_modality->name.') '.$directory_resolution .$retirement_fund->procedure_modality->procedure_type->name.' en su modalidad '.$retirement_fund->procedure_modality->name.($affiliate->gender == 'M' ? ' al Sr. ' : 'a la Sra. ').$affiliate_name.'.';
    }


    // $due = 'Que, mediante Resolución de la Comisión de Prestaciones Nº de fecha , se otorgó en calidad
    // de ANTICIPO del 50% el monto de Bs() a favor del Sr. SOF. 1ro. MARIO BAUTISTA
    // MANCILLA con C.I. 2215955 LP .';

    $discount = $retirement_fund->discount_types();
    $finance = $discount->where('discount_type_id', '1')->first();
    $body_finance = '';
    if (isset($finance) && $finance->pivot->amount > 0) {
      $body_finance = '<br>Que, mediante <strong>Resolución de la Comisión de Prestaciones N°' . $finance->pivot->note_code . '</strong> de fecha ' . Util::getStringDate($finance->pivot->note_code_date) . ',';

      if (isset($finance->id) && $finance->pivot->amount > 0) {
        $body_finance .= ' se otorgó en calidad de ANTICIPO el monto de <b>' . Util::formatMoneyWithLiteral($finance->pivot->amount) . '</b>, con cargo a liquidación final, a favor del&nbsp;<b>' . $affiliate->degree->shortened . ' ' . $affiliate->fullName() . '</b> con C.I. N° <b>' . $affiliate->identity_card. '</b>.<br>';
      } else {
        $body_finance .= ' no se evidencia pagos o anticipos por concepto de Fondo de Retiro Policial.<br>';
      }
    }

    $then .= '<br><br>La Comisión de Beneficios Económicos de la Mutual de Servicios al Policía “MUSERPOL” en
        uso de sus facultades y en observancia al Reglamento de Fondo de Retiro Policial Solidario:';

    $cardinal = ['PRIMERA', 'SEGUNDA', 'TERCERA', 'CUARTA', 'QUINTA'];
    $cardinal_index = 0;

    $discounts = $retirement_fund->discount_types();
    $discount = $discounts->where('discount_type_id', '1')->first();
    $body_resolution = '';
    if (isset($discount->id) && $discount->pivot->amount > 0) {
      $body_resolution .= "<b>" . $cardinal[$cardinal_index++] . '.-</b> Ratificar el Anticipo otorgado mediante <strong>' . $discount->pivot->note_code . '</strong> de fecha ' . Util::getStringDate($discount->pivot->note_code_date) . ', por un monto de <b>' . Util::formatMoneyWithLiteral($discount->pivot->amount) . '</b> con cargo de liquidación final, a favor del<b>&nbsp; '. $affiliate_name . '.</b><br><br>';
    }
    $months  = $affiliate->getTotalQuotes();
    $qualification_id = 23;
    $qualification = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', $qualification_id)->first();
     $body_resolution .= '<b>' . $cardinal[$cardinal_index++] . '.-</b> Reconocer '.($retirement_fund->procedure_modality->procedure_type_id==21?'la ':'el ').$retirement_fund->procedure_modality->procedure_type->name.($retirement_fund->procedure_modality_id == ID::retFunDevPay()->titular_id?' al ':' por '). $retirement_fund->procedure_modality->name . ', por el periodo de&nbsp;<b>' . Util::formatMonthYearLiteral($months).
     '</b> de acuerdo a Calificación de la Unidad de Fondo de Retiro Policial Solidario, de fecha&nbsp; <b>' . Util::getStringDate($qualification->date) . '</b>, el monto de <strong>' . Util::formatMoneyWithLiteral($retirement_fund->subtotal_ret_fun) . '</strong>';


   $discounts = $retirement_fund->discount_types();
    $discount = $discounts->where('discount_type_id', '1')->first();
    if (isset($discount->id) && $discount->pivot->amount > 0) {
      $body_resolution .= ". Descontando el monto del anticipo <strong>" . Util::formatMoneyWithLiteral($discount->pivot->amount) . "</strong>.";
      /*$body_resolution .= ". Descontando el monto del anticipo, reconocer el pago del beneficio de Fondo de Retiro Policial Solidario, por un TOTAL de <strong>" . Util::formatMoneyWithLiteral($retirement_fund->total_availability + $retirement_fund->subtotal_ret_fun - $discount->pivot->amount) . "</strong>";
      if ($retirement_fund->procedure_modality_id == 4 || $retirement_fund->procedure_modality_id == 1) {
        $body_resolution .= ".";
      } else {
        $body_resolution .= ", a favor " . ($affiliate->gender == 'M' ? 'del ' : 'de la ') . $affiliate->degree->shortened . " " . $affiliate->fullName() . " con C.I. N° " . $affiliate->identity_card . ".";
      }*/
    } else {
      $body_resolution .= ".";
    }
    $body_resolution .= "<br><br>";
    
    $discounts = $retirement_fund->discount_types();
    //$discount_sum = $discounts->where('discount_type_id','>','1')->where('retirement_fund_id',$ret_fun_id)->sum('amount');
    $discount_sum = $discounts->where('discount_type_id', '>', '1')->sum('amount');
    //return $discount_sum;
    $header_discount = false;
    $header_garantee = false;
    if ($discount_sum > 0) {
      $discounts = $retirement_fund->discount_types();
      $discount = $discounts->where('discount_type_id', '2')->first();
      if (isset($discount->id) && $discount->pivot->amount > 0) {
        $body_resolution .= '<b>' . $cardinal[$cardinal_index++] . '.-</b> A solicitud de la Dirección de Estrategias Sociales e Inversiones, retener por pago de deuda con la MUSERPOL, el monto de <b>' . Util::formatMoneyWithLiteral($discount->pivot->amount) . "</b>";
        $header_discount = true;
      }
      //return $body_resolution;


      $discounts = $retirement_fund->discount_types();
      $discount = $discounts->where('discount_type_id', '3')->first();
      if (isset($discount->id) && $discount->pivot->amount > 0) {

        $loans = InfoLoan::where('affiliate_id', $affiliate->id)->get();
        if (!$header_discount) {
          $body_resolution .= '<b>' . $cardinal[$cardinal_index++] . '.-</b> A solicitud de la Dirección de Estrategias Sociales e Inversiones, retener para pago de Garantia de prestamo '; // de los garantes
          $header_garantee=true;
        } else {
          $body_resolution .= " y ";
        }
        $num_loans = $loans->count();
        $header = false;
        if ($num_loans > 1) {
          $body_resolution .= 'la suma total de  <b>'.Util::formatMoneyWithLiteral($discount_guarantee->pivot->amount).'</b> ';
          if($header_garantee){
            $body_resolution .= ' a favor de : ';
          }else{
            $body_resolution .= ' por concepto de garantía de préstamo, a favor de : ';
          }
          $header = true;
        }
        $i = 0;
        foreach ($loans as $loan) {
          $i++;
          if (!$header) {
            if($header_discount){
              $body_resolution .= ' por concepto de garantía de préstamo, a favor de : ';
            }else{
            $body_resolution .= ' a favor de ';
            }
          }
          if ($i != 1) {
            if ($num_loans - $i == 0)
              $body_resolution .= ' y ';
            else
              $body_resolution .= ', ';
          }
          $body_resolution .= ($loan->affiliate_guarantor->gender == 'M' ? 'Sr. ' : 'Sra. ').$loan->affiliate_guarantor->fullName().' con C.I. N° '.$loan->affiliate_guarantor->identity_card;
          $body_resolution .= ' en la suma de <b> ' . Util::formatMoneyWithLiteral($loan->amount) . '</b>';
        }
        //$body_resolution .= ".<br><br>";//;" en conformidad al contrato de préstamo Nro. ".($discount->pivot->code??'sin nro')." y la nota ".($discount->pivot->note_code??'sin nota')." de fecha ". Util::getStringDate($retirement_fund->reception_date) .".<br><br>";
      }
      $body_resolution .= '.<br><br>';
    }

    $body_resolution .= '<b>' . $cardinal[$cardinal_index++] . '.-</b> El monto TOTAL a pagar de&nbsp; <b>' . Util::formatMoneyWithLiteral($retirement_fund->total) . '</b>'.($retirement_fund->procedure_modality_id != 62?', a favor ':'.');
    $reserved = false;
    if ($retirement_fund->procedure_modality_id == 4 || $retirement_fund->procedure_modality_id == 1 || $retirement_fund->procedure_modality_id == 63) {

      $beneficiaries = RetFunBeneficiary::where('retirement_fund_id', $retirement_fund->id)->orderBy('kinship_id')->orderByDesc('state')->get();
      if ($beneficiaries->count() > 1) {
        $body_resolution .= 'de los beneficiarios';
      } else {
        $body_resolution .= ($applicant->gender == 'M' ? 'del beneficiario ' : 'de la beneficiaria ');
      }
      $body_resolution .= ($affiliate->gender == 'M' ? ' del Sr. ' : ' de la Sra. ') . $affiliate_name . "., en el siguiente manera: <br><br>";
      foreach ($beneficiaries as $beneficiary) {
        if (!$beneficiary->state && !$reserved) {
          $reserved = true;
          $reserved_quantity = RetFunBeneficiary::where('retirement_fund_id', $retirement_fund->id)->where('state', false)->count();
          $certification = $beneficiary->testimonies()->first();
          $body_resolution .= '
          Mantener en reserva la (s) Cuota (s) salvando derechos, hasta que presente (n) la correspondiente Declaratoria de Herederos o Aceptación de Herencia y demás requisitos establecidos del Reglamento de Fondo de Retiro Policial Solidario, de la siguiente manera:<br><br>';
        }
        $body_resolution .= "<li class='text-justify'>";
        if (Util::isChild($beneficiary->birth_date)) {
          $body_resolution .= 'Menor ';
        } else {
          $body_resolution .= $beneficiary->gender == 'M' ? 'Sr. ' : 'Sra. ';
        }
        $body_resolution .= $beneficiary->fullName();
        if ($beneficiary->identity_card){
          $body_resolution .= ' con C.I. N° ' . $beneficiary->identity_card;
        }
        $beneficiary_advisor = RetFunAdvisorBeneficiary::where('ret_fun_beneficiary_id', $beneficiary->id)->first();
        $beneficiary_legal_guardian = RetFunLegalGuardianBeneficiary::where('ret_fun_beneficiary_id', $beneficiary->id)->first();
        if(!$beneficiary->state && (Util::isChild($beneficiary->birth_date))){
          $body_resolution.=', a través de su '.($affiliate->gender == 'F' ? ' padre' : ' madre').', tutor (a) o hasta que cumpla la mayoría de edad';
        }
        $body_resolution .= ', en el monto de <strong>' . Util::formatMoneyWithLiteral($beneficiary->amount_total) . '</strong> ';
          if (isset($beneficiary_advisor->id) && $beneficiary->state) {
            $advisor = RetFunAdvisor::where('id', $beneficiary_advisor->ret_fun_advisor_id)->first();
            $body_resolution.='en calidad de '.$beneficiary->kinship->name.' a través de '.($advisor->gender == 'M' ? 'el Sr. ' : 'la Sra. ').Util::fullName($advisor) . ' con C.I. N°' . $advisor->identity_card .($advisor->gender == 'F' ? ' madre' : ' padre').' del menor.</li><br>';
          }else{
            $body_resolution.='en calidad de '.$beneficiary->kinship->name . '.</li><br>';
          }
         /*if (isset($beneficiary_legal_guardian->id)) {
          $legal_guardian = RetFunLegalGuardian::where('id', $beneficiary_legal_guardian->ret_fun_legal_guardian_id)->first();
          $body_resolution .= " por si o representada legamente por " . ($legal_guardian->gender == 'M' ? "el Sr." : "la Sra. ") . " " . Util::fullName($legal_guardian) . " con C.I. N° " . $legal_guardian->identity_card . ".
                    conforme establece la Escritura Pública sobre Testimonio de Poder especial, amplio y suficiente N° " . $legal_guardian->number_authority . " de " . Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority)) . " emitido por " . $legal_guardian->notary . ".";
        }*/
      }
    } else {
      $body_resolution .= ($retirement_fund->procedure_modality_id != 62?(($affiliate->gender == 'M' ? 'del beneficiario: ' : 'de la beneficiaria: ')):'') . "<br><br>";
      //$payment .= $affiliate->degree->shortened." ".$affiliate->fullName()." con C.I. N° ".$affiliate->identity_card."., el monto de &nbsp;<strong>".Util::formatMoneyWithLiteral($retirement_fund->total).".</strong>";
      $body_resolution .= "<li class='text-justify'>" . ($affiliate->gender == 'M' ? 'Sr. ' : 'Sra. ') . $affiliate->degree->shortened . " " . $affiliate->fullName() . " con C.I. N° " . $affiliate->identity_card . ", en calidad de Titular.</li><br><br>";
    }
    //Disponibilidad
    if ($affiliate->hasAvailability()) {
      $reserved_availability = false;
      $beneficiaries = RetFunBeneficiary::where('retirement_fund_id', $retirement_fund->id)->orderBy('kinship_id')->orderByDesc('state')->get();
      $availability = Util::sumTotalContributions($affiliate->getDatesAvailability());

      $body_resolution .= '<b>' . $cardinal[$cardinal_index++] . '.-</b> Proceder a la devolución de <b>'.Util::formatMoneyWithLiteral($retirement_fund->total_availability).'</b> correspondiente a los descuentos realizados al Titular '.($affiliate->gender == 'M' ? ' Sr. ' : ' Sra. ').$affiliate_name.', para el Fondo de Retiro Policial Solidario durante su permanencia en el destino de disponibilidad de las letras por el tiempo de '. Util::formatMonthYearLiteral($availability) .'.<br><br>';
      /////Para Beneficiarios 
      if ($retirement_fund->procedure_modality_id == 4 || $retirement_fund->procedure_modality_id == 1 || $retirement_fund->procedure_modality_id == 63) {
          foreach ($beneficiaries as $beneficiary) {
            if (!$beneficiary->state && !$reserved_availability) {
              $reserved_availability = true;
              $reserved_quantity = RetFunBeneficiary::where('retirement_fund_id', $retirement_fund->id)->where('state', false)->count();
              $certification = $beneficiary->testimonies()->first();
              $body_resolution .= "Mantener en reserva la (s) Cuota (s) salvando los derechos, hasta que presente (n) la correspondiente Declaratoria de Herederos o Aceptación de Herencia y demás requisitos establecidos del Reglamento de Fondo de Retiro Policial Solidario, de la siguiente manera:<br><br>";
            }
            $body_resolution .= "<li class='text-justify'>";
            if (Util::isChild($beneficiary->birth_date)) {
              $body_resolution .= 'Menor ';
            }else{
              $body_resolution .= $beneficiary->gender == 'M' ? 'Sr. ' : 'Sra. ';
            }
            $body_resolution .= $beneficiary->fullName();
            if ($beneficiary->identity_card){
              $body_resolution .= " con C.I. N° " . $beneficiary->identity_card;
            }
            $beneficiary_advisor = RetFunAdvisorBeneficiary::where('ret_fun_beneficiary_id', $beneficiary->id)->first();
            $beneficiary_legal_guardian = RetFunLegalGuardianBeneficiary::where('ret_fun_beneficiary_id', $beneficiary->id)->first();
            if(!$beneficiary->state && (Util::isChild($beneficiary->birth_date))){
              $body_resolution.=', a través de su '.($affiliate->gender == 'F' ? ' padre' : ' madre').', tutor (a) o hasta que cumpla la mayoría de edad';
            }
            $body_resolution .= ', en el monto de <strong>' . Util::formatMoneyWithLiteral($beneficiary->amount_total) . '</strong> ';
              if (isset($beneficiary_advisor->id) && $beneficiary->state) {
                $advisor = RetFunAdvisor::where('id', $beneficiary_advisor->ret_fun_advisor_id)->first();
                $body_resolution.='en calidad de '.$beneficiary->kinship->name.' a través de '.($advisor->gender == 'M' ? 'el Sr. ' : 'la Sra. ').Util::fullName($advisor) . ' con C.I. N°' . $advisor->identity_card .' '.($advisor->gender == 'F' ? ' madre' : ' padre').' del menor.</li><br><br>';
           }else{
                $body_resolution.='en calidad de '.$beneficiary->kinship->name . '.</li><br>';
           }
              /*$beneficiary_legal_guardian = RetFunLegalGuardianBeneficiary::where('ret_fun_beneficiary_id', $beneficiary->id)->first();
             if (isset($beneficiary_legal_guardian->id)) {
              $legal_guardian = RetFunLegalGuardian::where('id', $beneficiary_legal_guardian->ret_fun_legal_guardian_id)->first();
              $body_resolution .= " por si o representada legamente por " . ($legal_guardian->gender == 'M' ? "el Sr." : "la Sra. ") . " " . Util::fullName($legal_guardian) . " con C.I. N° " . $legal_guardian->identity_card . ".
                        conforme establece la Escritura Pública sobre Testimonio de Poder especial, amplio y suficiente N° " . $legal_guardian->number_authority . " de " . Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority)) . " emitido por " . $legal_guardian->notary . ".";
            }*/
           // $body_resolution .= ', en el monto de <strong>' . Util::formatMoneyWithLiteral($beneficiary->amount_availability) . '</strong> ' . 'en calidad de ' . $beneficiary->kinship->name . ".</li><br><br>";
          }
      }

    }


    $body_resolution .= '<b>REGISTRESE, NOTIFIQUESE Y ARCHIVESE.</b><br><br><br><br><br>';

    // $number = Util::getNextAreaCode($retirement_fund->id);
    $number = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 26)->first();

    $user = User::find($number->user_id);
    $body_resolution .= "<div class='text-xs italic'>cc. Arch.<br>CONTABILIDAD<br>COMISIÓN</div>";

    $users_commission = User::where('is_commission', true)->get();
    $data = [
      'retirement_fund'   =>  $retirement_fund,
      'correlative'   =>  $number,
      'ret_fun' => $retirement_fund,
      'affiliate' =>  $affiliate,
      'actual_city'  =>  Auth::user()->city->name,
      'actual_date'  =>  Util::getStringDate($number->date),
      'then'  =>  $then,
      'user'  =>  $user,
      'body_resolution'   =>  $body_resolution,
      'users_commission'  =>  $users_commission,
      'viewed' => $viewed,
      'considering_one' => $considering_one,
      'considering_two' => $considering_two,
      'considering_three' => $considering_three,
    ];
    $bar_code = \DNS2D::getBarcodePNG($this->get_module_retirement_fund($retirement_fund->id), "QRCODE");
    //$bar_code = \DNS2D::getBarcodePNG(($retirement_fund->getBasicInfoCode()['code'] . "\n\n" . $retirement_fund->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
    $headerHtml = view()->make('ret_fun.print.legal_header')->render();
    $footerHtml = view()->make('ret_fun.print.resolution_footer', ['bar_code' => $bar_code])->render();
    return \PDF::loadView('ret_fun.print.legal_resolution', $data)
      ->setOption('encoding', 'utf-8')
      ->setOption('footer-html', $footerHtml)
      ->setOption('header-html', $headerHtml)
      ->setOption('margin-top', 40)
      ->setOption('margin-bottom', 35)
      ->stream("jefaturaRevision.pdf");
  }
  private function getFlagy($num, $pos, $text = "")
  {
    if ($pos == 0) {
      return;
    }

    if ($num == ($pos + 1))
      return " y " . $text;

    return ", ";
  }
}
