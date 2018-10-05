<?php

namespace Muserpol\Http\Controllers;

use Muserpol\RetirementFundCertification;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\ProcedureModality;
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
    public function printReception($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $degree = $affiliate->degree;
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $modality = $retirement_fund->procedure_modality->name;
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
        $title = ($retirement_fund->procedure_modality->id == 1 || $retirement_fund->procedure_modality->id == 2 ) ? "REQUISITOS DEL PAGO GLOBAL DE APORTES – " . mb_strtoupper($modality)  : "REQUISITOS DEL BENEFICIO FONDO DE RETIRO – " . mb_strtoupper($modality);

        // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
        $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', WorkflowState::where('role_id', Util::getRol()->id)->whereIn('sequence_number', [0, 1])->first()->id)->first();
        $code = $retirement_fund->code;
        $area = $next_area_code->wf_state->first_shortened;
        $user = $next_area_code->user;
        $date = Util::getDateFormat($next_area_code->date);
        $number = $next_area_code->code;

        $submitted_documents = RetFunSubmittedDocument::leftJoin('procedure_requirements', 'procedure_requirements.id', '=', 'ret_fun_submitted_documents.procedure_requirement_id')->where('retirement_fund_id', $retirement_fund->id)->orderBy('procedure_requirements.number', 'asc')->get();

        /*
            !!todo
            add support utf-8
        */
        $bar_code = \DNS2D::getBarcodePNG(($retirement_fund->getBasicInfoCode()['code']."\n\n". $retirement_fund->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
        $applicant = RetFunBeneficiary::where('type', 'S')->where('retirement_fund_id', $retirement_fund->id)->first();
        $pdftitle = "RECEPCIÓN - " . $title;
        $namepdf = Util::getPDFName($pdftitle, $applicant);
        $footerHtml = view()->make('ret_fun.print.footer', ['bar_code'=>$bar_code])->render();

        $data = [
            'code'=> $code,
            'area'=> $area,
            'user'=> $user,
            'date'=> $date,
            'number'=> $number,

            'bar_code'=> $bar_code,
            'title'=> $title,
            'institution'=> $institution,
            'direction'=> $direction,
            'unit'=> $unit,
            'modality'=> $modality,
            'applicant'=> $applicant,
            'affiliate'=> $affiliate,
            'degree'=> $degree,
            'submitted_documents'=> $submitted_documents,
            'retirement_fund'=> $retirement_fund,
        ];
        $pages = [];
        for ($i = 1; $i <= 2; $i++) {
            $pages[] = \View::make('ret_fun.print.reception', $data)->render();
        }
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
                //    ->setOption('margin-top', '20mm')
                   ->setOption('margin-bottom', '15mm')
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
        $retirement_fund = RetirementFund::where('affiliate_id', $affiliate->id)->get()->last();

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
        $footerHtml = view()->make('ret_fun.print.footer', ['bar_code'=>$this->generateBarCode($retirement_fund)])->render();
        $data = [
            'code' => $code,
            'area' => $area,
            'user' => $user,
            'date' => $date,
            'number' => $number,

            'cite'=>$cite,
            'subtitle'=>$subtitle,
            'title'=>$title,
            'retirement_fund'=>$retirement_fund,
            'affiliate'=>$affiliate,
            'affiliate_folders'=>$affiliate_folders,
            'applicant'=>$applicant,
            'unit1'=>'archivo y gestión documental<br> beneficios económicos',
        ];
        $pages = [];
        for ($i = 1; $i <= 2; $i++) {
            $pages[] = \View::make('ret_fun.print.file_certification',$data)->render();
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
        $submitted_documents = RetFunSubmittedDocument::
                                select(
                                    'ret_fun_submitted_documents.id',
                                    'ret_fun_submitted_documents.retirement_fund_id',
                                    'ret_fun_submitted_documents.procedure_requirement_id',
                                    'ret_fun_submitted_documents.is_valid',
                                    'ret_fun_submitted_documents.reception_date')
                                ->where('ret_fun_submitted_documents.retirement_fund_id', $id)
                                ->leftJoin('procedure_requirements','ret_fun_submitted_documents.procedure_requirement_id','=','procedure_requirements.id')
                                ->orderBy('procedure_requirements.number', 'ASC')->get();

        $affiliate = $retirement_fund->affiliate;
        $footerHtml = view()->make('ret_fun.print.footer', ['bar_code'=>$this->generateBarCode($retirement_fund)])->render();
        $cite = $number;//RetFunIncrement::getIncrement(Session::get('rol_id'), $retirement_fund->id);
        $subtitle = $cite;
        $pdftitle = "Revision Legal";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);

        $data = [
            'code' => $code,
            'area' => $area,
            'user' => $user,
            'date' => $date,
            'number' => $number,

            'subtitle'=>$subtitle,
            'title'=>$title,
            'retirement_fund'=>$retirement_fund,
            'affiliate'=>$affiliate,
            'submitted_documents'=>$submitted_documents,
        ];

        $pages = [];
        for ($i = 1; $i <= 2; $i++) {
            $pages[] = \View::make('ret_fun.print.legal_certification',$data)->render();
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
            return \PDF::loadView('ret_fun.print.beneficiaries_qualification', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
        }
        return $data;
    }
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
            return \PDF::loadView('ret_fun.print.qualification_average_salary_quotable', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("SalarioPromedioCotizable.pdf");
        }
        return $data;
    }
    public function printDataQualification($id, $only_print = true)
    {
        $retirement_fund = RetirementFund::find($id);


        // $title = 'INFORMACIÓN TÉCNICA';
        $affiliate = $retirement_fund->affiliate;
        $applicant = $retirement_fund->ret_fun_beneficiaries()->where('type', 'S')->with('kinship')->first();
        $beneficiaries = $retirement_fund->ret_fun_beneficiaries()->orderByDesc('type')->orderBy('id')->get();
        $pdftitle = "Calificación - INFORMACIÓN TÉCNICA";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);
        if($affiliate->globalPayRetFun()){

            $title = 'CALIFICACIÓN DE PAGO GLOBAL POR '. $retirement_fund->procedure_modality->name;
        }else{
            $title = 'CALIFICACIÓN FONDO DE RETIRO POLICIAL SOLIDARIO';
        }


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
        $discounts = $retirement_fund->discount_types()->where('amount','>',0)->get();

        $has_availability = sizeOf($affiliate->getContributionsWithType(10)) > 0;

        /*  discount combinations*/
        $array_discounts = array();
        $array = DiscountType::all()->pluck('id');
        $results = array(array());
        foreach ($array as $element) {
            foreach ($results as $combination) {
                array_push($results, array_merge(array($element), $combination));
            }
        }
        foreach ($results as $value) {
            $sw = true;
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

        $array_discounts_combi = [];
        foreach ($array_discounts as $value) {
            $temp = 'Fondo de Retiro';
            if($affiliate->globalPayRetFun()){
                $temp = 'Pago Global por '.$retirement_fund->procedure_modality->name;
            }
            array_push($array_discounts_combi, array('name' => ($temp.' ' . ($value['name'] ? ' - ' . $value['name'] : '')), 'amount' => ($retirement_fund->subtotal_ret_fun - $value['amount'])));
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
        if ($affiliate->globalPayRetFun()){
            $total_aporte = $retirement_fund->average_quotable;
            $yield = $total_aporte + (($total_aporte * $current_procedure->annual_yield) / 100);
            $administrative_expenses = (($yield * $current_procedure->administrative_expenses) / 100);
            $less_administrative_expenses = $yield - $administrative_expenses;
            $temp =[
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
        ];
        $data = array_merge($data, $temp);

        if ($only_print) {
            return \PDF::loadView('ret_fun.print.qualification_step_data', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
        }
        return $data;
    }
    public function printDataQualificationAvailability($id, $only_print = true)
    {
        $retirement_fund = RetirementFund::find($id);

        $current_procedure = Util::getRetFunCurrentProcedure();
        $title = "RECONOCIMIENTO DE APORTES EN DISPONIBILIDAD";
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
        
        foreach (ContributionType::orderBy('id')->where('id','=',10)->get() as $c) {
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

        $has_availability = sizeOf($affiliate->getContributionsWithType(10)) > 0;
        $availability = ContributionType::find(10);

        /*  discount combinations*/
        $array_discounts = array();
        $array = DiscountType::all()->pluck('id');
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
                array_push($array_discounts_availability, array('name' => ('Fondo de Retiro + '. ($availability->display_name ?? 'error') .' ' . ($value['name'] ? ' - ' . $value['name'] : '')), 'amount' => ($retirement_fund->subtotal_ret_fun + $retirement_fund->total_availability - $value['amount'])));
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
        if($only_print){
            return \PDF::loadView('ret_fun.print.qualification_data_availability', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
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
        $array = DiscountType::all()->pluck('id');
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
                array_push($array_discounts_availability, array('name' => ('Fondo de Retiro + reconocimiento de aportes en disponibilidad ' . ($value['name'] ? ' - ' . $value['name'] : '')), 'amount' => ($retirement_fund->subtotal_ret_fun + $retirement_fund->total_availability - $value['amount'])));
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
            return \PDF::loadView('ret_fun.print.qualification_data_ret_fun_availability', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
        }
        return $data;
    }

    public function printAllQualification($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate =$retirement_fund->affiliate;

        if ($affiliate->hasAvailability()) {
            if ($retirement_fund->total_availability > 0) {
                $pages[] =\View::make('ret_fun.print.qualification_data_availability', self::printDataQualificationAvailability($id, false))->render();
            }
            // if ($retirement_fund->total > 0) {
            //     $pages[] =\View::make('ret_fun.print.qualification_data_ret_fun_availability', self::printDataQualificationRetFunAvailability($id, false))->render();
            // }
        }
        if ($retirement_fund->total_ret_fun > 0) {
            $pages[] =\View::make('ret_fun.print.qualification_step_data', self::printDataQualification($id, false))->render();
        }
        $pages[] =\View::make('ret_fun.print.beneficiaries_qualification', self::printBeneficiariesQualification($id, false))->render();
        if ($affiliate->hasAvailability()) {
            if ($retirement_fund->total_availability > 0) {
                $pages[] =\View::make('ret_fun.print.qualification_data_availability', self::printDataQualificationAvailability($id, false))->render();
            }
            // if ($retirement_fund->total > 0) {
            //     $pages[] =\View::make('ret_fun.print.qualification_data_ret_fun_availability', self::printDataQualificationRetFunAvailability($id, false))->render();
            // }
        }
        if ($retirement_fund->total_ret_fun > 0) {
            $pages[] =\View::make('ret_fun.print.qualification_step_data', self::printDataQualification($id, false))->render();
        }
        $pages[] =\View::make('ret_fun.print.beneficiaries_qualification', self::printBeneficiariesQualification($id, false))->render();

        if (!$affiliate->selectedContributions() > 0 && ! $affiliate->globalPayRetFun()  && $retirement_fund->procedure_modality->procedure_type->id == 2 ){
            $pages[] =\View::make('ret_fun.print.qualification_average_salary_quotable', self::printQualificationAverageSalaryQuotable($id, false))->render();
        }
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf
            ->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '15mm')
            // ->setOption('footer-html', $footerHtml)
            // ->setOption('footer-right', 'Pagina [page] de [toPage]')
            ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
            // ->setOption('user-style-sheet', 'css/app1.css')
            ->stream("namepdf");
    }
    public function printRetFunCommitmentLetter($id)
    {
        $affiliate = Affiliate::find($id);
        $commitment = ContributionCommitment::where('affiliate_id', $affiliate->id)->first();
        $date = Util::getDateFormat(date('Y-m-d'));
        $user = Auth::user();//agregar cuando haya roles
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
            'area'=>$area,
            'date'=>$date,
            'user'=>$user,
            'title'=>$title,
            'affiliate'=>$affiliate,
            'glosa'=>$glosa,
            'city'=>$city,
            'glosa_pago'=>$glosa_pago,
            'commitment'=>$commitment,
            

        ];
        return \PDF::loadView(
            'ret_fun.print.ret_fun_commitment_letter', $data
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
        $username = Auth::user()->username;//agregar cuando haya roles
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
        $username = Auth::user()->username;//agregar cuando haya roles
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
            ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
            ->stream("$namepdf");
    }

    private function generateBarCode($retirement_fund){
        $bar_code = \DNS2D::getBarcodePNG((
                        $retirement_fund->getBasicInfoCode()['code']."\n\n".$retirement_fund->getBasicInfoCode()['hash']), 
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
        $valid_contributions = ContributionType::select('id')->where('operator','+')->pluck('id');
        $quantity = Util::getRetFunCurrentProcedure()->contributions_number;        
        $contributions_sixty = Contribution::where('affiliate_id', $affiliate->id)  
                                ->whereIn('contribution_type_id',$valid_contributions)
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
        $subtitle ="Cuenta Individual";


        // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
        $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 22)->first();
        $code = $retirement_fund->code;
        $area = $next_area_code->wf_state->first_shortened;
        $user = $next_area_code->user;
        $date = Util::getDateFormat($next_area_code->date);
        $number = $next_area_code->code;

        $degree = Degree::find($affiliate->degree_id);
        $exp = City::find($affiliate->city_identity_card_id);
        $exp = ($exp==Null)? "-": $exp->first_shortened;
        $dateac = Carbon::now()->format('d/m/Y');
        $place = City::find(Auth::user()->city_id);
        $num=0;
        $pdftitle = "Cuentas Individuales";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);

        $subtitle = $next_area_code->code;

        $data = [
            'code' => $code,
            'area' => $area,
            'user' => $user,
            'date' => $date,
            'number' => $number,

            'num'=>$num,
            'subtitle'=>$subtitle,
            'place'=>$place,
            'retirement_fund'=>$retirement_fund,
            'reimbursements'=>$reimbursements,
            'dateac'=>$dateac,
            'exp'=>$exp,
            'degree'=>$degree,
            'contributions'=>$contributions_sixty,
            'affiliate'=>$affiliate,
            'title'=>$title,
            'institution'=>$institution,
            'direction'=>$direction,
            'unit'=>$unit,
        ];
        return \PDF::loadView('contribution.print.certification_contribution', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    }
    public function printCertificationAvailability($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $disponibilidad = ContributionType::where('name','=','Disponibilidad')->first();
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
        $subtitle ="Cuenta Individual";

        // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
        $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 22)->first();
        $code = $retirement_fund->code;
        $area = $next_area_code->wf_state->first_shortened;
        $user = $next_area_code->user;
        $date = Util::getDateFormat($next_area_code->date);
        $number = $next_area_code->code;

        $degree = Degree::find($affiliate->degree_id);
        $exp = City::find($affiliate->city_identity_card_id);
        $exp = ($exp==Null)? "-": $exp->first_shortened;
        $dateac = Carbon::now()->format('d/m/Y');
        $place = City::find(Auth::user()->city_id);
        $num=0;             
        $pdftitle = "Cuentas Individuales";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);

        $subtitle = $next_area_code->code;

        //total de los aportes
        $aporte=$retirement_fund->subtotal_availability;
        $data = [
            'code' => $code,
            'area' => $area,
            'user' => $user,
            'date' => $date,
            'number' => $number,

            'num'=>$num,
            'disponibilidad'=>$disponibilidad,
            'aporte'=>$aporte,
            'subtitle'=>$subtitle,
            'place'=>$place,
            'retirement_fund'=>$retirement_fund,
            'reimbursements'=>$reimbursements,
            'dateac'=>$dateac,
            'exp'=>$exp,
            'degree'=>$degree,
            'contributions'=>$contributions,
            'affiliate'=>$affiliate,
            'title'=>$title,
            'institution'=>$institution,
            'direction'=>$direction,
            'unit'=>$unit,
        ];

        return \PDF::loadView('contribution.print.certification_availability', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    }
    public function printCertificationItem0($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $item_cero_ids = [2,3];
        $contributions =  $affiliate->contributions()->whereIn('contribution_type_id', $item_cero_ids)->get();
        $month_years = $contributions->pluck('month_year');
        $months = implode(",", array_map(function ($item) {
            return "'".$item."'";
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
            where affiliate_id = ".$affiliate->id."
            and deleted_at is null
            and contribution_type_id in (2,3)
            and month_year in (".$months.")
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
            where affiliate_id = ".$affiliate->id. "
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
        $exp = ($exp==Null)? "-": $exp->first_shortened;
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
            'subtitle'=>$subtitle,
            'place'=>$place,
            'retirement_fund'=>$retirement_fund,
            'dateac'=>$dateac,
            'exp'=>$exp,
            'degree'=>$degree,
            'contributions'=>$contributions,
            'affiliate'=>$affiliate,
            'title'=>$title,
            'institution'=>$institution,
            'direction'=>$direction,
            'unit'=>$unit,
        ];
        return \PDF::loadView('contribution.print.certification_item0', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    } 

    public function printCertificationSecurity($id){
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $security_contributions = ContributionType::where('name','=','Período de Batallón de Seguridad Física Con Aporte')->first();
        $security_no_contributions = ContributionType::where('name','=','Período de Batallón de Seguridad Física Sin Aporte')->first();

        $contributions = Contribution::where('affiliate_id', $affiliate->id)
                        ->where(function ($query) use ($security_contributions,$security_no_contributions){
                            $query->where('contribution_type_id',$security_contributions->id)
                            ->orWhere('contribution_type_id',$security_no_contributions->id);
                        })
                        ->orderBy('month_year')                        
                        ->get();
        $contributions_number = Contribution::where('affiliate_id', $affiliate->id)->where('contribution_type_id',$security_contributions->id)->count();
        $contributions_total = Contribution::where('affiliate_id', $affiliate->id)->where('contribution_type_id',$security_contributions->id)->sum('total');
        $reimbursements = Reimbursement::where('affiliate_id', $affiliate->id)
                        ->orderBy('month_year')
                        ->get();
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
        $title = "BATALLÓN DE SEGURIDAD FÍSICA PRIVADA";
        $subtitle ="Cuenta Individual";

        // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
        $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 22)->first();
        $code = $retirement_fund->code;
        $area = $next_area_code->wf_state->first_shortened;
        $user = $next_area_code->user;
        $date = Util::getDateFormat($next_area_code->date);
        $number = $next_area_code->code;

        $degree = Degree::find($affiliate->degree_id);
        $exp = City::find($affiliate->city_identity_card_id);
        $exp = ($exp==Null)? "-": $exp->first_shortened;
        $dateac = Carbon::now()->format('d/m/Y');
        $place = City::find(Auth::user()->city_id); 
        $num=0;       
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

            'num'=>$num,
            'subtitle'=>$subtitle,
            'place'=>$place,
            'retirement_fund'=>$retirement_fund,
            // 'total'=>$total,
            'reimbursements'=>$reimbursements,
            'dateac'=>$dateac,
            'exp'=>$exp,
            'degree'=>$degree,
            'contributions'=>$contributions,
            'affiliate'=>$affiliate,
            'title'=>$title,
            'institution'=>$institution,
            'direction'=>$direction,
            'unit'=>$unit,
            'contributions_number'=>$contributions_number,
            'security_contributions'=>$security_contributions,
        ];
        return \PDF::loadView('contribution.print.security_certification',$data)
                ->setOption('encoding', 'utf-8')
                ->setOption('margin-bottom', '15mm')
                ->setOption('footer-right', 'Pagina [page] de [toPage]')
                ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL')
                ->stream("$namepdf");
    }

    public function printCertificationContributions($id){

        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $certification_contribution = ContributionType::where('name','=','Período Certificación Con Aporte')->first();
        $certification_no_contribution = ContributionType::where('name','=','Período Certificación Sin Aporte')->first();

        $contributions = Contribution::where('affiliate_id', $affiliate->id)
                        ->where(function ($query) use ($certification_contribution,$certification_no_contribution){
                            $query->where('contribution_type_id',$certification_contribution->id)
                            ->orWhere('contribution_type_id',$certification_no_contribution->id)
                            ->orWhere('contribution_type_id',9);
                        })
                        ->orderBy('month_year')                        
                        ->get();
        // 9 id periodo no  trabajado
        $contributions_number = Contribution::where('affiliate_id', $affiliate->id)->whereIn('contribution_type_id',[$certification_contribution->id,9])->count();
        $contributions_total = Contribution::where('affiliate_id', $affiliate->id)->whereIn('contribution_type_id', [$certification_contribution->id, 9])->sum('total');
        $reimbursements = Reimbursement::where('affiliate_id', $affiliate->id)
                        ->orderBy('month_year')
                        ->get();
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
        $title = "CERTIFICACIÓN";
        $subtitle ="Cuenta Individual";

        // $next_area_code = Util::getNextAreaCode($retirement_fund->id);
        $next_area_code = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 22)->first();
        $code = $retirement_fund->code;
        $area = $next_area_code->wf_state->first_shortened;
        $user = $next_area_code->user;
        $date = Util::getDateFormat($next_area_code->date);
        $number = $next_area_code->code;

        $degree = Degree::find($affiliate->degree_id);
        $exp = City::find($affiliate->city_identity_card_id);
        $exp = ($exp==Null)? "-": $exp->first_shortened;
        $dateac = Carbon::now()->format('d/m/Y');
        $place = City::find(Auth::user()->city_id); 
        $num=0;       
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

            'num'=>$num,
            'subtitle'=>$subtitle,
            'place'=>$place,
            'retirement_fund'=>$retirement_fund,
            'total'=>$total,
            'reimbursements'=>$reimbursements,
            'dateac'=>$dateac,
            'exp'=>$exp,
            'degree'=>$degree,
            'contributions'=>$contributions,
            'affiliate'=>$affiliate,
            'title'=>$title,
            'institution'=>$institution,
            'direction'=>$direction,
            'unit'=>$unit,
            'certification_contribution'=>$certification_contribution,
            'contributions_number'=>$contributions_number,
        ];
        return \PDF::loadView('contribution.print.contributions_certification', $data)
                ->setOption('encoding', 'utf-8')
                ->setOption('margin-bottom', '15mm')
                ->setOption('footer-right', 'Pagina [page] de [toPage]')
                ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL')
                ->stream("$namepdf");
    }
    public function printLegalDictum($id) {
        $retirement_fund = RetirementFund::find($id);

        $applicant = RetFunBeneficiary::where('type', 'S')->where('retirement_fund_id', $retirement_fund->id)->first();
        $beneficiaries = RetFunBeneficiary::where('retirement_fund_id',$retirement_fund->id)->orderByDesc('type')->orderBy('id')->get();        
        /** PERSON DATA */
        $person = "";
        $affiliate = Affiliate::find($retirement_fund->affiliate_id);                        
        $ret_fun_beneficiary = RetFunLegalGuardianBeneficiary::where('ret_fun_beneficiary_id',$applicant->id)->first();


        if(isset($ret_fun_beneficiary->id)) {
            $legal_guardian = RetFunLegalGuardian::where('id',$ret_fun_beneficiary->ret_fun_legal_guardian_id)->first();
            $person .= ($legal_guardian->gender=='M'?"El señor ":"La señora ").Util::fullName($legal_guardian)." con C.I. N° ".$legal_guardian->identity_card." ".$legal_guardian->city_identity_card->first_shortened.". a través de Testimonio Notarial N° ".$legal_guardian->number_authority." de fecha ".Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority))." sobre poder especial, bastante y suficiente emitido por ".$legal_guardian->notary_of_public_faith." a cargo del Notario ".$legal_guardian->notary." en representación ".($affiliate->gender=='M'?"del señor ":"de la señora ");
        } else {
            $person .= ($affiliate->gender=='M'?"El señor ":"La señora ");
        }
        $person .= $affiliate->fullNameWithDegree() ." con C.I. N° ". $affiliate->ciWithExt() .", como TITULAR ".($retirement_fund->procedure_modality_id == 4?"FALLECIDO ":" ")."del beneficio del Fondo de Retiro Policial Solidario en su modalidad de <strong class='uppercase'>". $retirement_fund->procedure_modality->name ."</strong>,";
        if($retirement_fund->procedure_modality_id != 4) {
            $person .= " presenta la documentación para la otorgación del beneficio en fecha ". Util::getStringDate($retirement_fund->reception_date) .", a lo cual considera lo siguiente:";
        } else {
            $person .=  ($applicant->gender=='M'?' el Sr. ':' la Sra. ').Util::fullName($applicant)." con C.I. N° ". $applicant->identity_card." ".$applicant->city_identity_card->first_shortened.". solicita el beneficio a favor suyo en calidad de ".$applicant->kinship->name;
            $quantity = $beneficiaries->count();
            if($quantity > 1)
            {
                $person .= " y de los Señores ";
                foreach($beneficiaries as $beneficiary)
                {
                    if($beneficiary->id != $applicant->id) {
                        $person .= Util::fullName($beneficiary)." con C.I. N° ". $beneficiary->identity_card." ".$beneficiary->city_identity_card->first_shortened.".".((--$quantity)==2?" y ":(($quantity==1)?'':', '))." ";
                    }
                }
            }
        }
        /** END PERSON DATA */

        /** LAW DATA */
        $art = [
            '3' => 'a)',
            '4' => 'b)',
            '5' => 'c)',
            '6' => 'c.1)',
            '7' => 'd)',
        ];

        $law = "Conforme normativa, el trámite N° ".$retirement_fund->code." de la Regional ".ucwords($retirement_fund->city_start->name)." es ingresado por Ventanilla
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
        $file = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$file_id)->first();
        $body_file .= "Que, mediante Certificación N° ". $file->code .", de Archivo de la Dirección de Beneficios Económicos de fecha ". Util::getStringDate($file->date) .", se establece que el trámite signado con el N° ". $retirement_fund->code." ";
        $discount = $retirement_fund->discount_types();
        $finance = $discount->where('discount_type_id','1')->first();
        if(isset($finance->id) && $finance->pivot->amount > 0)        
            $body_file .= "si tiene expediente del referido titular por concepto de anticipo en el monto de <b>".Util::formatMoneyWithLiteral($finance->pivot->amount)."</b> conforme Resolución de la Comisión de Presentaciones N°".($finance->pivot->note_code??'Sin codigo')." de fecha ".Util::getStringDate(($finance->pivot->note_code_date??'')).".";
        else 
        {
            $folder = AffiliateFolder::where('affiliate_id',$affiliate->id)->get();
            if($folder->count() > 0) {
                $body_file .= "si ";    
            } else {
                $body_file .= "no ";
            }
            $body_file .= "tiene expediente del referido titular.";
        }
        ///---ENDIFLE--////

        /////----FINANCE----///        
        $discount = $retirement_fund->discount_types();
        $finance = $discount->where('discount_type_id','1')->first();
        $body_finance = "";
        $body_finance = "Que, mediante nota de respuesta ".($finance->pivot->code ?? 'sin cite')." de la Dirección de Asuntos Administrativos de fecha ". Util::getStringDate(($finance->pivot->date??'')).", refiere que ".($affiliate->gender=='M'?"el":"la")." titular del beneficio ";
        if(isset($finance->id) && $finance->amount > 0){
            $body_finance .= "si cuenta con registro de pagos o anticipos por concepto de Fondo de Retiro Policial en el monto de " .Util::formatMoneyWithLiteral(($finance->pivot->amount??0)).".";
        } else {            
            $body_finance .= "no cuenta con registro de pagos o anticipos por concepto de Fondo de Retiro Policial, sin embargo se recomienda compatibilizar los listados adjuntos con las carpetas del archivo de la Unidad de Fondo de Retiro para no incurrir en algún error o pago doble de este beneficio.";
        }                          
        /////----END FINANCE---////

        ////-----LEGAL REVIEW ----////      
        $body_legal_review   = "";
        $legal_review_id = 21;
        $legal_review = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$legal_review_id)->first();
        $body_legal_review .= "Que, mediante Certificación N° ".$legal_review->code." del Área Legal de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha ".Util::getStringDate($legal_review->date).", fue verificada y validada la documentación presentada por ".($retirement_fund->procedure_modality_id == 4?"los beneficiarios":($affiliate->gender=="M"?"el titular":"la titular")) ." del trámite signado con el N° ".$retirement_fund->code.".";
        /////-----END LEGAL REVIEW----///
        
        ///------ INDIVIDUAL ACCCOUTNS ------////    
        $body_accounts = "";           
        $accounts_id = 22;
        $accounts = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$accounts_id)->first();
        $availability_code = 10;
        $availability_number_contributions = Contribution::where('affiliate_id',$affiliate->id)->where('contribution_type_id',$availability_code)->count();

        $end_contributions = [
            '3'  =>  'destino a disponibilidad de la letra (reserva activa) '.($affiliate->gender=='M'?'del':'de la').' titular.',
            '4'  =>  'del fallecimiento del Titular.',
            '5'  =>  'de su retiro.',
            '6'  =>  'de su retiro.',
            '7'  =>  'de su retiro.',
        ];
        $body_accounts = "Que, mediante Certificación de Aportes N° ".$accounts->code." del Área de Cuentas Individuales de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha ". Util::getStringDate($accounts->date) .", se verificó los últimos "."60"." aportes antes ".$end_contributions[$retirement_fund->procedure_modality_id];

        if($affiliate->hasAvailability()) {
            $body_accounts .=" Mediante Certificación de Aportes en Disponibilidad N° ".$accounts->code." del Área de Cuentas Individuales de la Unidad de Otorgación de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha ". Util::getStringDate($accounts->date) .", durante la permanencia en la reserva activa se verificó ". $availability_number_contributions ." aportes en disponibilidad antes ".$end_contributions[$retirement_fund->procedure_modality_id];
        }
        ////------- INDIVIDUAL ACCOUTNS ------////

        //----- QUALIFICATION -----////      
        $body_qualification = "";
        $qualification_id = 23;
        $qualification = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$qualification_id)->first();
        $months  = $affiliate->getTotalQuotes();        
        $body_qualification .=  "Que, mediante Calificación de Fondo de Retiro Policial Solidario N° ".$qualification->code." del Área de Calificación de la Unidad de Otorgación de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha ". Util::getStringDate($qualification->date) .", se realizó el cálculo por el periodo de<strong> ". Util::formatMonthYearLiteral($months)."</strong>, determinando el beneficio de <strong>Fondo de Retiro Policial Solidario por ".mb_strtoupper($retirement_fund->procedure_modality->name)."&nbsp;&nbsp;</strong>de<strong> ". Util::formatMoneyWithLiteral($retirement_fund->subtotal_ret_fun) ."</strong>".Util::getDiscountCombinations($retirement_fund->id);
        if($affiliate->hasAvailability()){
            $availability = Util::sumTotalContributions($affiliate->getDatesAvailability());
            $body_qualification .= " Por concepto de reconocimiento de aportes laborales durante el periodo de disponibilidad de ".Util::formatMonthYearLiteral($availability) .', el cual no es considerado en la calificación del beneficio del Fondo de Retiro Policial Solidario, de acuerdo a los parámetros establecidos por el Estudio Matemático Actuarial 2016 - 2020, se determina el monto de <strong>'.Util::formatMoneyWithLiteral($retirement_fund->total_availability).'</strong>; haciendo un total de <strong>'.Util::formatMoneyWithLiteral($retirement_fund->total).'</strong>.';
        }        
      
        ////----- DUE -----////
        $discounts = $retirement_fund->discount_types();
        $discount = $discounts->where('discount_type_id','2')->first();
        $body_due = "";
        $body_due .= "Que, mediante nota ".($discount->pivot->code??'Sin nota'). " de la Dirección de Estrategias Sociales e Inversiones de fecha ".Util::getStringDate(($discount->pivot->date??'')). ", 
                    refiere que ".($affiliate->gender == 'M'?'el':'la')." titular ";
        $discounts = $retirement_fund->discount_types();
        $discount_counter = $discounts->where('discount_type_id','>','1')->where('amount','>','0')->count();
        if($discount_counter == 0) {
            $body_due .="no cuenta con deuda en curso de pago a MUSERPOL ni por concepto de garantía de préstamo.";
        } else {                        
                $and = "";
                $discounts = $retirement_fund->discount_types();
                $discount = $discounts->where('discount_type_id','2')->first();
                if(isset($discount->id) && $discount->pivot->amount >0) {                    
                    $body_due .="si cuenta con deuda en curso de pago a MUSERPOL";
                    $and = " y ";
                }                
                
                $discounts = $retirement_fund->discount_types();
                $discount = $discounts->where('discount_type_id','3')->first();
                if(isset($discount->id) && $discount->pivot->amount >0) {                    
                    if($and=="") {
                        $body_due .="si cuenta con deuda en curso de pago a MUSERPOL";
                    }
                     $body_due .= $and." por concepto de garantía de préstamo";
                }
                $body_due .= ", supra detallado.";            
        }        
        

        

        // $discounts = $retirement_fund->discount_types();
        // $discount = $discounts->where('discount_type_id','3')->first();
        // $loans = InfoLoan::where('affiliate_id',$affiliate->id)->get();
        
        // $num_loans = $loans->count();        
        // if($num_loans > 0){            
        //     $body_due .= " y por concepto de garantes adeuda a ".($num_loans==1?"el señor":"los señores ");
        //     $i=0;
        //     foreach($loans as $loan){
        //         $i++;
        //         if($i!=1)
        //         {
        //             if($num_loans-$i==0)
        //                 $body_due .= " y ";
        //             else
        //                 $body_due .= ", ";
        //         }
        //         $body_due.= $loan->affiliate_guarantor->fullName()." con C.I. N° ".$loan->affiliate_guarantor->identity_card." en la suma de ".Util::formatMoneyWithLiteral($loan->amount);
        //     }
        //     $body_due .= " en conformidad al contrato de préstamo Nro. ".($discount->pivot->note_code??'sin nro').".";
        // }        
        // else {
        //     $body_due .= " ni por concepto de garantes.";
        // }
        
        ///-----END DUE----///

        ///------ PAYMENT ------////
        $payment = "";
        $discounts = $retirement_fund->discount_types(); //DiscountType::where('retirement_fund_id',$retirement_fund->id)->orderBy('discount_type_id','ASC')->get();                
        $loans = InfoLoan::where('affiliate_id',$affiliate->id)->get();
        $payment = "Por consiguiente, habiendo sido remitido el presente tramite al Área Legal Unidad de
        Otorgación del Fondo de Retiro Policial Solidario, autorizado por Jefatura de la referida unidad, conforme a los Art. 2, 3, 5, 10, 26, 27, 28, 31 Ter.,
        32, 36, 37, 38, 40, 41, 42, 44, 45, 48, 49, 50, 70, 71, 72, 73, 74 y la Disposición Transitoria
        Segunda, del Reglamento de Fondo de Retiro Policial Solidario, aprobado mediante
        Resolución de Directorio N° 31/2017 en fecha 24 de agosto de 2017 y modificado mediante
        Resolución de Directorio N° 36/2017 en fecha 20 de septiembre de 2017. Se DICTAMINA en
        merito a la documentación de respaldo contenida en el presente, ";
        $payment = "Por consiguiente, habiendo sido remitido el presente tramite al Área Legal Unidad de Otorgación del Fondo de Retiro Policial Solidario, 
        Cuota y Auxilio Mortuorio, autorizado por Jefatura de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, conforme a 
        los Arts. 2, 3, 5, 10, 26, 27, 29, 31 Ter., 32, 33, 34, 35, 36, 37, 38, 41, 42, 44, 45, 48, 49, 50, 70, 71, 72, 73, 74, Disposición Transitoria Segunda del 
        Reglamento de Fondo de Retiro Policial Solidario, aprobado mediante Resolución de Directorio N° 31/2017 en fecha 24 de agosto de 2017 y modificado mediante 
        Resoluciones de Directorio Nros. 36/2017 y 51/2017 de fechas 20 de septiembre de 2017 y 29 de diciembre de 2017 respectivamente; y la Disposición Transitoria 
        Segunda del Reglamento de Cuota Mortuoria y Auxilio Mortuorio, aprobado mediante Resolución de Directorio N° 43/2017 en fecha 08 de noviembre de 2017 y modificado 
        mediante Resolución de Directorio N° 51/2017 de fecha 29 de diciembre de 2017. Se <b>DICTAMINA</b> en merito a la documentación de respaldo contenida en el presente, ";
                
        $flagy = 0;
        $discounts = $retirement_fund->discount_types();
        $discounts_number = $discounts->where('amount','>','0')->count();
        if($discounts_number > 0) {
            $payment .= "proceder a realizar el descuento ";
            $discounts = $retirement_fund->discount_types();
            $discount = $discounts->where('discount_type_id','1')->first();        
            if(isset($discount->id) && $discount->pivot->amount > 0) {
                $flagy++;
                $payment.="de <b>".Util::formatMoneyWithLiteral($discount->pivot->amount)."</b> por concepto de anticipo de Fondo de Retiro Policial de conformidad a la Resoluci&oacute;n de la Comisión de Presentaciones Nro. ".$discount->pivot->note_code." de fecha ".Util::getStringDate($discount->pivot->date);
            }
            
            $discounts = $retirement_fund->discount_types();
            $discount = $discounts->where('discount_type_id','2')->first();                           
            $discount_footer = false;
            if(isset($discount->id) && $discount->pivot->amount > 0) {                                
                $payment .= $this->getFlagy($discounts_number,$flagy);
                $flagy++;
                $discount_footer = true;
                $payment.="de <b>".Util::formatMoneyWithLiteral($discount->pivot->amount)."</b> por concepto de saldo de deuda con la MUSERPOL"; 
                //de conformidad al contrato de préstamo Nro. ".$discount->pivot->note_code." y nota ".$discount->pivot->code." de fecha ".Util::getStringDate($discount->pivot->date);
            }
            //
            $discounts = $retirement_fund->discount_types();
            $discount = $discounts->where('discount_type_id','3')->first();
            
            $loans = InfoLoan::where('affiliate_id',$affiliate->id)->get();

            if(isset($discount->id) && $discount->pivot->amount > 0) {                 
                $payment .= $this->getFlagy($discounts_number,$flagy,"la suma ");                
                $payment.="total de <b>".Util::formatMoneyWithLiteral(($discount->pivot->amount??0))."</b> por concepto de garantía de préstamo, a favor ";
                $discount_footer = true;
                $num_loans = $loans->count();
                if($num_loans==1)
                    $payment .= " del señor ";
                else
                    $payment .= " de los señores ";
                $i=0;
                foreach($loans as $loan){
                    $i++;
                    if($i!=1)
                    {
                        if($num_loans-$i==0)
                            $payment .= " y ";
                        else
                            $payment .= ", ";
                    }
                    $payment.= $loan->affiliate_guarantor->fullName()." con C.I. N° ".$loan->affiliate_guarantor->identity_card;                
                    $payment.= " en la suma de <b>".Util::formatMoneyWithLiteral($loan->amount)."</b>";
                }
                //$payment .= " en conformidad al contrato de préstamo Nro. ".($discount->pivot->note_code??'sin nro')." y la nota ".($discount->pivot->code??'sin nota')." de fecha ". Util::getStringDate($discount->pivot->date) ." de la Dirección de Estrategias Sociales e Inversiones";
            }
            if($discount_footer) {
                $payment .= " en conformidad al contrato de préstamo Nro. ".($discount->pivot->note_code??'sin nro')." y la nota ".($discount->pivot->code??'sin nota')." de fecha ". Util::getStringDate($discount->pivot->date) ." de la Dirección de Estrategias Sociales e Inversiones";

            }
            $payment .= ". Reconocer los derechos y se otorgue el beneficio del Fondo de Retiro Policial Solidario por <strong class='uppercase'>".($retirement_fund->procedure_modality->name)."</strong> a favor de:<br><br>";
        } else {
            $payment .= "reconocer los derechos y se otorgue el beneficio del Fondo de Retiro Policial Solidario por <strong class='uppercase'>".$retirement_fund->procedure_modality->name."</strong> a favor de: <br><br>";
        }
        
        if($retirement_fund->procedure_modality_id == 4) {
            $beneficiaries = RetFunBeneficiary::where('retirement_fund_id',$retirement_fund->id)->orderBy('kinship_id')->get();
            foreach($beneficiaries as $beneficiary){                                
                $birth_date = Carbon::createFromFormat('d/m/Y', $beneficiary->birth_date);  
                if(date('Y') -$birth_date->format('Y') > 18) {
                $payment .=$beneficiary->gender=='M'?'Sr. ':'Sra. ';
                } else {
                    $payment .='Menor ';
                }
                $payment .= $beneficiary->fullName()." con C.I. N° ".$beneficiary->identity_card." ".$beneficiary->city_identity_card->first_shortened;
                $beneficiary_advisor = RetFunAdvisorBeneficiary::where('ret_fun_beneficiary_id',$beneficiary->id)->first();
                if(isset($beneficiary_advisor->id))
                {
                    $advisor = RetFunAdvisor::where('id',$beneficiary_advisor->ret_fun_advisor_id)->first();
                    $payment .= ", a través de su tutor".($advisor->gender=='F'?'a':'')." natural ".($advisor->gender=='M'?'Sr.':'Sra.')." ".Util::fullName($advisor)." con C.I. N°".$advisor->identity_card." ".$advisor->city_identity_card->first_shortened.".";
                }
                $beneficiary_legal_guardian = RetFunLegalGuardianBeneficiary::where('ret_fun_beneficiary_id',$beneficiary->id)->first();
                if(isset($beneficiary_legal_guardian->id)) {
                    $legal_guardian = RetFunLegalGuardian::where('id',$beneficiary_legal_guardian->ret_fun_legal_guardian_id)->first();
                    $payment .= " por si o representada legamente por ".($legal_guardian->gender=='M'?"el Sr.":"la Sra. ")." ".Util::fullName($legal_guardian)." con C.I. N° ".$legal_guardian->identity_card." ".($legal_guardian->city_identity_card->first_shortened??"sin extencion").". 
                    conforme establece la Escritura Pública sobre Testimonio de Poder especial, amplio y suficiente N° ".$legal_guardian->number_authority." de ".Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority))." emitido por ".$legal_guardian->notary.".";

                }
                $payment .= ', en el monto de <strong>'.Util::formatMoneyWithLiteral($beneficiary->amount_total).'</strong> '.'en calidad de '.$beneficiary->kinship->name.".<br><br>";
            }
        } else {
            $payment .= $affiliate->degree->shortened." ".$affiliate->fullName()." con C.I. N° ".$affiliate->identity_card." ".$affiliate->city_identity_card->first_shortened."., el monto de &nbsp;<strong>".Util::formatMoneyWithLiteral($retirement_fund->total).".</strong>";
        } 

        ///------EN  PAYMENT ------///
        // $number = Util::getNextAreaCode($retirement_fund->id);
        $number= RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 25)->first();


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
            'payment'   =>  $payment,
            'art'   =>  $art,
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
        ->setOption('margin-top',40)
        ->setOption('margin-bottom',15)
        ->stream("dictamenLegal.pdf");
    }

    public function printHeadshipReview($ret_fun_id){
        $retirement_fund =  RetirementFund::find($ret_fun_id);
        $affiliate = Affiliate::find($retirement_fund->affiliate_id);
        
        $applicant = RetFunBeneficiary::where('type', 'S')->where('retirement_fund_id', $retirement_fund->id)->first();                        
        $ret_fun_beneficiary = RetFunLegalGuardianBeneficiary::where('ret_fun_beneficiary_id',$applicant->id)->first();

        $head = "<p>Señora Directora:</p><p class='text-justify'>En atención a solicitud de fecha ".Util::getStringDate($retirement_fund->reception_date).", del beneficio de Fondo de Retiro Policial, ";
        if(isset($ret_fun_beneficiary->id)) {
            $legal_guardian = RetFunLegalGuardian::where('id',$ret_fun_beneficiary->ret_fun_legal_guardian_id)->first();
            //$head .= "Mediante Escritura Pública sobre Testimonio de Poder especial, amplio y suficiente N°".  $legal_guardian->number_authority ." de fecha ".$legal_guardian->date." emitido por ". $legal_guardian->notary." otorgado al Sr. ".Util::fullName($legal_guardian)." con C.I. N° ".$legal_guardian->identity_card." ".$legal_guardian->city_identity_card->first_shortened." representa legalmente al ";
            $head .= ($legal_guardian->gender=='M'?"el señor ":"la señora ").Util::fullName($legal_guardian)." con C.I. N° ".$legal_guardian->identity_card." ".$legal_guardian->city_identity_card->first_shortened.". a través de Testimonio Notarial N° ".$legal_guardian->number_authority." de fecha ".Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority))." sobre poder especial, bastante y suficiente emitido por ".$legal_guardian->notary_of_public_faith." a cargo del Notario ".$legal_guardian->notary." en representación ".($affiliate->gender=='M'?"del señor ":"de la señora ");
        } else {            
            $head .= ($affiliate->gender=='M'?"El señor ":"La señora ");
        }
        $head .= $affiliate->fullNameWithDegree() ." con C.I. N° ". $affiliate->ciWithExt() .", como TITULAR del beneficio del Fondo de Retiro Policial Solidario en su modalidad de <strong class='uppercase'>". ucwords($retirement_fund->procedure_modality->name) ."</strong> y en cumplimiento al numeral 8 del artículo 45 del Reglamento de Fondo de Retiro Policial Solidario, elevo el presente informe de revisión: ";        

        $past = "Conforme al Decreto Supremo N°1446 de 19 de diciembre de 2012, modificado por el Decreto Supremo N° 3231 de 28 de junio de 2017, referente al beneficio de Fondo de Retiro Policial en el artículo 2, (MODIFICACIONES) establece:
            <br><br>
            I. Se modifica el inciso c) del artículo 3 del Decreto Supremo N° 1446, de 19 de diciembre de 2012, con el siguiente texto: <b>“Otorgar el beneficio variable del Fondo de Retiro Policial Solidario, en el marco del principio de solidaridad”</b>
            <br><br>
            IV. Se modifica el inciso a) del Parágrafo I del artículo 14 del Decreto Supremo N° 1446, del 19 de diciembre de 2012, con el siguiente texto: <b>“a) Fondo de Retiro Policial Solidario”</b>, 
            <br><br>
            V. Se modifica el parágrafo III del artículo 14 del Decreto Supremo N° 1446, del 19 de diciembre de 2012, con el siguiente texto: <b><em>“III. Los beneficios señalados en el presente artículo se rigen por los principios de equidad y solidaridad, debiendo ser otorgados a todos los afiliados, aportantes de la Policía Boliviana en sus diferentes sectores y niveles sin ninguna distinción”</em></b>
            <br><br>
            VI. Se modifica el artículo 15 del Decreto Supremo N° 1446, del 19 de diciembre de 2012, con el siguiente texto: <i><b>“Articulo 15.- (FONDO DE RETIRO POLICIAL SOLIDARIO). Es el beneficio que brinda protección a los miembros del servicio activo y a sus derechohabientes, mediante el reconocimiento del pago único, con motivo y oportunidad del retiro definitivo de la actividad remunerada dependiente de la Policía Boliviana, el cual será administrado por la MUSERPOL; a ser otorgado en el marco del principio de solidaridad, cuando el retiro se produzca por: </b></i>
            <br><br>
            <b><i>a) Jubilación; b) Fallecimiento del titular; c) Retiro forzoso; d) Retiro voluntario.” </i></b>
            <p class='text-justify m-l-35'>
            Asimismo, como dispone en su Disposición Transitoria Única. – <b><em>“I. Los tramites ingresados y pendientes hasta la gestión 2015, serán determinados bajo los parámetros establecidos por el Estudio Matemático Actuarial 2016-2020 y pagados con los saldos acumulados hasta la fecha de publicación del presente Decreto Supremo. II. Para realizar el pago referido en el Parágrafo anterior, el Directorio aprobara el Reglamento respectivo y el Estudio Técnico Financiero en un plazo no mayor a sesenta (60) días calendario, a partir de la publicación del presente Decreto Supremo”</em></b> y conforme a la aprobación por el Honorable Directorio de la MUSERPOL, del Estudio Matemático Actuarial 2016-2020, mediante Resolución de Directorio N° 26/2017 de fecha 11 de agosto de 2017 y Reglamentación de Fondo de Retiro Policial Solidario con Resolución de Directorio N° 31/2017 de 24 de agosto de 2017, modificado mediante Resolución de Directorio Nº 36/2017 de 20 de septiembre de 2017, adicionando la DISPOSICIÓN TRANSITORIA SEGUNDA (incluida mediante Resolución de Directorio Nº 36/2017 de 20 de septiembre de 2017), refiere: <em>“Corresponderá la devolución de aportes realizados con prima de 1.85% durante la permanencia en la reserva activa, más el 5% anual de rendimiento, toda vez que estos aportes no forman parte de los parámetros de calificación establecidos en el Estudio Matemático Actuarial 2016 – 2020 considerado por el Decreto Supremo Nº 3231 de 28 de junio de 2017”</em> y mediante nota con cite: DIRECTORIO/MUSERPOL/247/2017 de fecha 31 de octubre de 2017 concluye: <em>“…sin embargo, para no dejar a interpretaciones el Directorio recomienda a la Dirección General Ejecutiva aplicar para el cálculo de los rendimientos a los aportes no calificados en el Beneficio de Fondo de Retiro la opción uno (1) de su propuesta, que señala: <b>la fecha del último aporte efectivizado en el periodo de la disponibilidad.”</b></em> y modificado mediante Resolución de Directorio Nº 51/2017 de 29 de diciembre de 2017 en la que se incorporan dos artículos referidos a la <b>“EXCEPCIÓN EN EL TRÁMITE DE FONDO DE RETIRO POR ENFERMEDADES TERMINALES” y a “FONDO DE RETIRO POR REINCORPORACIÓN”,</b> además de una disposición transitoria referida al pago de cuotas parte de trámites que cuenten con Resolución de la Comisión de Beneficios a partir de la puesta en vigencia de la MUSERPOL hasta la emisión de la Resolución de Directorio N° 50/2015.</p>";
        $past_footer = "En cumplimiento a la normativa Técnica – Legal vigente y aprobada por el Honorable Directorio de la MUSERPOL, se procedió a realizar el procesamiento del trámite para la otorgación del beneficio de Fondo de Retiro Policial Solidario al solicitante.";


        $process = "Conforme a Dictamen Legal de la Unidad de Fondo de Retiro Policial, Cuota y Auxilio Mortuorio de la Dirección de Beneficios Económicos, mediante <b>Cite: ";
        
        $legal_dictum_id = 25;
        $legal_dictum = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$legal_dictum_id)->first();
        
        $process .= "DBE/UFRPSCAM/AL-DL N°".$legal_dictum->code."</b> de fecha <b>".Util::getStringDate($legal_dictum->date)."</b> y resultado del procesamiento según normativa Técnica – Legal, en cumplimiento al punto 8 del artículo 45 Procesamiento del Reglamento del beneficio de Fondo de Retiro Policial Solidario. 
        <br><br>
        Por tanto, el expediente que cursa en esta Jefatura, cuenta con los actuados requeridos.
        <br><br>";
        $reception_id = 19;
        $reception = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$reception_id)->first();
        $process .= "Conforme normativa, el trámite N°".$retirement_fund->code." de la Regional ".ucwords(strtolower($retirement_fund->city_start->name))." 
        ingresado por la Ventanilla de Atención al Afiliado de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, verificados los requisitos mediante 
        solicitud N° ".$retirement_fund->code." de Ventanilla, adjuntado documentación según lo señalado en el Art. 39 del Reglamento de Fondo de Retiro Policial Solidario de la gestión 2017 y conforme al Art. 45, se detalla la documentación como resultado de la aplicación de la base técnica-legal del Estudio Matemático Actuarial 2016-2020 y Reglamento de la gestión 2017, generada y adjuntada al expediente por los funcionarios de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, según correspondan las funciones, detallando lo siguiente: ";


        $body = "";        

        
        ///---FILE---///
        $body_file = "";    
        $file_id = 20;
        $file = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$file_id)->first();
        $body_file .= "Que, mediante Certificación N° ". $file->code .", de Archivo de la Dirección de Beneficios Económicos de fecha ". Util::getStringDate($file->date) .", se establece que el trámite signado con el N° ". $retirement_fund->code." ";
        $discount = $retirement_fund->discount_types();
        $finance = $discount->where('discount_type_id','1')->first();
        if(isset($finance->id) && $finance->pivot->amount > 0)        
            $body_file .= "si tiene expediente del referido titular por concepto de anticipo en el monto de <b>".Util::formatMoneyWithLiteral($finance->pivot->amount)."</b> conforme Resolución de la Comisión de Presentaciones N°".($finance->pivot->note_code??'Sin codigo')." de fecha ".Util::getStringDate(($finance->pivot->note_code_date??'')).".";
        else 
        {
            $folder = AffiliateFolder::where('affiliate_id',$affiliate->id)->get();
            if($folder->count() > 0) {
                $body_file .= "si ";    
            } else {
                $body_file .= "no ";
            }
            $body_file .= "tiene expediente del referido titular.";
        }           
        ///---ENDIFLE--////

        /////----FINANCE----///        
        $discount = $retirement_fund->discount_types();
        $finance = $discount->where('discount_type_id','1')->first();
        $body_finance = "";
        $body_finance = "Que, mediante nota de respuesta ".($finance->pivot->code ?? 'sin cite')." de la Dirección de Asuntos Administrativos de fecha ". Util::getStringDate(($finance->pivot->date??'')).", refiere que ".($affiliate->gender=='M'?"el":"la")." titular del beneficio ";
        if(isset($finance->id) && $finance->amount > 0){
            $body_finance .= "si cuenta con registro de pagos o anticipos por concepto de Fondo de Retiro Policial en el monto de " .Util::formatMoneyWithLiteral(($finance->pivot->amount??0)).".";
        } else {            
            $body_finance .= "no cuenta con registro de pagos o anticipos por concepto de Fondo de Retiro Policial, sin embargo se recomienda compatibilizar los listados adjuntos con las carpetas del archivo de la Unidad de Fondo de Retiro para no incurrir en algún error o pago doble de este beneficio.";
        }                           
        /////----END FINANCE---////

        ////-----LEGAL REVIEW ----////      
        $body_legal_review   = "";
        $legal_review_id = 21;
        $legal_review = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$legal_review_id)->first();
        $body_legal_review .= "Que, mediante Certificación N° ".$legal_review->code." del Área Legal de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha ".Util::getStringDate($legal_review->date).", fue verificada y validada la documentación presentada por ".($retirement_fund->procedure_modality_id == 4?"los beneficiarios":"el titular") ." del trámite signado con el N° ".$retirement_fund->code.".";
        /////-----END LEGAL REVIEW----///
        
        ///------ INDIVIDUAL ACCCOUTNS ------////    
        $body_accounts = "";           
        $accounts_id = 22;
        $accounts = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$accounts_id)->first();
        $availability_code = 10;
        $availability_number_contributions = Contribution::where('affiliate_id',$affiliate->id)->where('contribution_type_id',$availability_code)->count();

        $end_contributions = [
            '3'  =>  'destino a disponibilidad de la letra (reserva activa) del titular.',
            '4'  =>  'fallecimiento.',
            '5'  =>  'retiro.',
            '6'  =>  'retiro.',
            '7'  =>  'retiro.',
        ];
        $body_accounts = "Que, mediante Certificación de Aportes N° ".$accounts->code." del Área de Cuentas Individuales de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha ". Util::getStringDate($accounts->date) .", se verificó los últimos "."60"." aportes antes de su ".$end_contributions[$retirement_fund->procedure_modality_id];

        if($affiliate->hasAvailability()) {
            $body_accounts .=" Mediante Certificación de Aportes en Disponibilidad N° ".$accounts->code." del Área de Cuentas Individuales de la Unidad de Otorgación de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha ". Util::getStringDate($accounts->date) .", durante la permanencia en la reserva activa se verificó ". $availability_number_contributions ." aportes en disponibilidad.";
        }
        ////------- INDIVIDUAL ACCOUTNS ------////

        //----- QUALIFICATION -----////      
        $body_qualification = "";
        $qualification_id = 23;
        $qualification = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$qualification_id)->first();
        $months  = $affiliate->getTotalQuotes();        
        $body_qualification .=  "Que, mediante Calificación de Fondo de Retiro Policial Solidario N° ".$qualification->code." del Área de Calificación de la Unidad de Otorgación de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha ". Util::getStringDate($qualification->date) .", se realizó el cálculo por el periodo de<strong> ". Util::formatMonthYearLiteral($months)."</strong>, determinando el beneficio de <strong>Fondo de Retiro Policial Solidario por ".mb_strtoupper($retirement_fund->procedure_modality->name)."&nbsp;&nbsp;</strong>de<strong> ". Util::formatMoneyWithLiteral($retirement_fund->subtotal_ret_fun) ."</strong>".Util::getDiscountCombinations($retirement_fund->id);
        if($affiliate->hasAvailability()){
            $availability = Util::sumTotalContributions($affiliate->getDatesAvailability());
            $body_qualification .= " Por concepto de reconocimiento de aportes laborales durante el periodo de disponibilidad de ".Util::formatMonthYearLiteral($availability) .', el cual no es considerado en la calificación del beneficio del Fondo de Retiro Policial Solidario, de acuerdo a los parámetros establecidos por el Estudio Matemático Actuarial 2016 - 2020, se determina el monto de <strong>'.Util::formatMoneyWithLiteral($retirement_fund->total_availability).'</strong>; haciendo un total de <strong>'.Util::formatMoneyWithLiteral($retirement_fund->total).'</strong>.';
        }
      
        ////----- DUE -----////
        $discounts = $retirement_fund->discount_types();
        $discount = $discounts->where('discount_type_id','2')->first();
        $body_due = "";
        $body_due .= "Que, mediante nota ".($discount->pivot->code??'Sin nota'). " de la Dirección de Estrategias Sociales e Inversiones de fecha ".Util::getStringDate(($discount->pivot->date??'')). ", 
                    refiere que ".($affiliate->gender == 'M'?'el':'la')." titular ";

        $discounts = $retirement_fund->discount_types();
        $discount_counter = $discounts->where('discount_type_id','>','1')->where('amount','>','0')->count();
        
        if($discount_counter == 0) {
            $body_due .="no cuenta con deuda en curso de pago a MUSERPOL ni por concepto de garantía de préstamo.";
        } else {            
            // if($discount_counter == 1) {                                                        
                $and = "";
                $discounts = $retirement_fund->discount_types();
                $discount = $discounts->where('discount_type_id','2')->first();
                if(isset($discount->id) && $discount->pivot->amount >0) {                    
                    $body_due .="si cuenta con deuda en curso de pago a MUSERPOL";
                    $and = " y ";
                }                
                
                $discounts = $retirement_fund->discount_types();
                $discount = $discounts->where('discount_type_id','3')->first();
                if(isset($discount->id) && $discount->pivot->amount >0) {                    
                    if($and=="") {
                        $body_due .="si cuenta con deuda en curso de pago a MUSERPOL";
                    }
                    $body_due .= $and." por concepto de garantía de préstamo";
                }
                $body_due .= ", supra detallado.";
        }
        
        

        

        // $discounts = $retirement_fund->discount_types();
        // $discount = $discounts->where('discount_type_id','3')->first();
        // $loans = InfoLoan::where('affiliate_id',$affiliate->id)->get();
        
        // $num_loans = $loans->count();        
        // if($num_loans > 0){            
        //     $body_due .= " y por concepto de garantes adeuda a ".($num_loans==1?"el señor":"los señores ");
        //     $i=0;
        //     foreach($loans as $loan){
        //         $i++;
        //         if($i!=1)
        //         {
        //             if($num_loans-$i==0)
        //                 $body_due .= " y ";
        //             else
        //                 $body_due .= ", ";
        //         }
        //         $body_due.= $loan->affiliate_guarantor->fullName()." con C.I. N° ".$loan->affiliate_guarantor->identity_card." en la suma de ".Util::formatMoneyWithLiteral($loan->amount);
        //     }
        //     $body_due .= " en conformidad al contrato de préstamo Nro. ".($discount->pivot->note_code??'sin nro').".";
        // }        
        // else {
        //     $body_due .= " ni por concepto de garantes.";
        // }
        
        ///-----END DUE----///


        $conclusion   = "";
        $headship_review_id = 25;
        $headship_review = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$headship_review_id)->first();

        $conclusion = "Se realizó la revisión de la documentación citada anteriormente, verificando su correcta emisión y contenido, en base al Dictamen Legal con 
        Cite: <strong> DBE/UFRPSCAM/AL-DL N°".$headship_review->code."</strong> de fecha <strong>".Util::getStringDate($headship_review->date)."</strong> se establece que";
        if($affiliate->gender == 'F'){
            $conclusion .= " la Señora ";
        }
        else {
            $conclusion .= " el Señor ";
        }
        $conclusion .= "<strong>".$affiliate->fullNameWithDegree ()."</strong> con <strong>C.I. N° ".$affiliate->identity_card." ".$affiliate->city_identity_card->first_shortened.".</strong> cumple con los 
        requisitos de acuerdo a Reglamento y se le reconocen los derechos para otorgar el beneficio de <strong>Fondo de Retiro Policial Solidario</strong> por <strong> ".$retirement_fund->procedure_modality->name."</strong> por el periodo de&nbsp;<strong>". Util::formatMonthYearLiteral($months) ."</strong>, determinando el monto de <strong>".Util::formatMoneyWithLiteral($retirement_fund->subtotal_ret_fun)."</strong>, ";
        // descontando la deuda por concepto de garantes de <strong>Bs14.327,85 (CATORCE MIL TRESCIENTOS VEINTE SIETE 85/100 BOLIVIANOS)</strong>, a solicitud de la Dirección de Estrategias Sociales e Inversiones, reconocer el <strong>Fondo de Retiro Policial Solidario</strong> por <strong>Bs27.811,63 (VEINTISIETE MIL OCHOCIENTOS ONCE 63/100 BOLIVIANOS)</strong>, a favor de los derechohabientes según el siguiente detalle:";
        $flagy = 0;
        $discounts = $retirement_fund->discount_types();
        $discounts_number = $discounts->where('amount','>','0')->count();
        if($discounts_number > 0) {
            $conclusion .= "proceder a realizar el descuento ";
            $discounts = $retirement_fund->discount_types();
            $discount = $discounts->where('discount_type_id','1')->first();        
            if(isset($discount->id) && $discount->pivot->amount > 0) {
                $flagy++;                
                $conclusion .="de <b>".Util::formatMoneyWithLiteral($discount->pivot->amount)."</b> por concepto de anticipo de Fondo de Retiro Policial de conformidad a la Resoluci&oacute;n de la Comisión de Presentaciones Nro. ".$discount->pivot->note_code." de fecha ".Util::getStringDate($discount->pivot->date);            
            }
            
            $discounts = $retirement_fund->discount_types();
            $discount = $discounts->where('discount_type_id','2')->first();                           
            $discount_footer = false;
            if(isset($discount->id) && $discount->pivot->amount > 0) {                                
                $conclusion .= $this->getFlagy($discounts_number,$flagy);
                $flagy++;
                $discount_footer = true;
                $conclusion.="de <b>".Util::formatMoneyWithLiteral($discount->pivot->amount)."</b> por concepto de saldo de deuda con la MUSERPOL"; 
                //de conformidad al contrato de préstamo Nro. ".$discount->pivot->note_code." y nota ".$discount->pivot->code." de fecha ".Util::getStringDate($discount->pivot->date);
            }
            //
            $discounts = $retirement_fund->discount_types();
            $discount = $discounts->where('discount_type_id','3')->first();
            
            $loans = InfoLoan::where('affiliate_id',$affiliate->id)->get();

            if(isset($discount->id) && $discount->pivot->amount > 0) {                 
                $conclusion .= $this->getFlagy($discounts_number,$flagy,"la suma ");                
                $conclusion.="total de <b>".Util::formatMoneyWithLiteral(($discount->pivot->amount??0))."</b> por concepto de garantía de préstamo, a favor ";
                $discount_footer = true;
                $num_loans = $loans->count();
                if($num_loans==1)
                    $conclusion .= " del señor ";
                else
                    $conclusion .= " de los señores ";
                $i=0;
                foreach($loans as $loan){
                    $i++;
                    if($i!=1)
                    {
                        if($num_loans-$i==0)
                            $conclusion .= " y ";
                        else
                            $conclusion .= ", ";
                    }
                    $conclusion.= $loan->affiliate_guarantor->fullName()." con C.I. N° ".$loan->affiliate_guarantor->identity_card;                
                    $conclusion.= " en la suma de <b>".Util::formatMoneyWithLiteral($loan->amount)."</b>";
                }
                //$payment .= " en conformidad al contrato de préstamo Nro. ".($discount->pivot->note_code??'sin nro')." y la nota ".($discount->pivot->code??'sin nota')." de fecha ". Util::getStringDate($discount->pivot->date) ." de la Dirección de Estrategias Sociales e Inversiones";
            }
            if($discount_footer) {
                $conclusion .= " en conformidad al contrato de préstamo Nro. ".($discount->pivot->note_code??'sin nro')." y la nota ".($discount->pivot->code??'sin nota')." de fecha ". Util::getStringDate($discount->pivot->date) ." de la Dirección de Estrategias Sociales e Inversiones";

            }
            $conclusion .= ". Reconocer los derechos y se otorgue el beneficio del Fondo de Retiro Policial Solidario por <strong class='uppercase'>".ucwords($retirement_fund->procedure_modality->name)."</strong> a favor de:<br><br>";
        } else {
            $conclusion .= "reconocer los derechos y se otorgue el beneficio del Fondo de Retiro Policial Solidario por <strong class='uppercase'>".$retirement_fund->procedure_modality->name."</strong> a favor de: <br><br>";
        }


        

        $beneficiaries = RetFunBeneficiary::where('retirement_fund_id',$retirement_fund->id)->orderByDesc('type')->orderBy('id')->get();

        $payments = [];
        if($retirement_fund->procedure_modality_id == 4) {
            $conclusion .= "Según información contenida en el Certificado de Descendencia presentado por la solicitante, mantener en reserva la cuota parte de: ";
        }
        foreach($beneficiaries as $beneficiary){
            $legal_guardian  = RetFunLegalGuardianbeneficiary::where('ret_fun_beneficiary_id',$beneficiary->id)->first();
            $payment = "";
            if(!isset($legal_guardian)){
                if($beneficiary->geneder == "F")
                {
                    $payment .= "Sra. ";
                }
                else{
                    $payment .= "Sr. ";
                }
            }
            else{
                $payment .= "Menor ";
            }
            //if($beneficiary->state)
            //$payment .= "Según información contenida en el Certificado de Descendencia presentado por la solicitante, mantener en reserva la cuota parte de: ";
            $payment .= Util::fullName($beneficiary). " con CI. N° ".$beneficiary->identity_card." ".$beneficiary->city_identity_card->first_shortened."., en el monto de <strong>".Util::formatMoneyWithLiteral($beneficiary->amount_total)."</strong>, en calidad de ".$beneficiary->kinship->name."." ;

            array_push($payments,$payment);
        }
        $end_conclusion = "Elevo el presente informe a su persona para su conocimiento y consideración.";
        $from = 'Lic. '.Util::fullName(Auth::user()).'
        <br><b>JEFE DE UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA Y AUXILIO MORTUORIO “MUSERPOL”</b>';
        $to = 'Lic. DAEN. Gabriela J. Bustillos Landaeta<br><b>
        DIRECTORA DE BENEFICIOS ECONÓMICOS “MUSERPOL"</b>';
        //return $conclusion;


        $bar_code = \DNS2D::getBarcodePNG(($retirement_fund->getBasicInfoCode()['code'] . "\n\n" . $retirement_fund->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
        $footerHtml = view()->make('ret_fun.print.headship_footer', ['bar_code' => $bar_code])->render();
        $headerHtml = view()->make('ret_fun.print.legal_header')->render();

            // $number = Util::getNextAreaCode($retirement_fund->id);
            $number = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 24)->first();

            $user = User::find($number->user_id);

            $data = [
                'ret_fun' => $retirement_fund,
                //'beneficiaries'    =>  $beneficiaries,
                'correlative'  =>  $number,
                'affiliate' =>  $affiliate,
                'actual_city'  =>  Auth::user()->city->name,
                'actual_date'  =>  Util::getStringDate($number->date),    
                'head'    =>  $head,
                'past'   =>  $past,
                'past_footer'   =>  $past_footer,
                'process'   =>  $process,
                'body_file'  =>  $body_file,
                'body_accounts'  =>  $body_accounts,
                'body_finance'  =>  $body_finance,
                'body_legal_review'  =>  $body_legal_review,
                'body_qualification'  =>  $body_qualification,
                'body_due'  =>  $body_due,
                'conclusion'   =>  $conclusion,
                'payments'  =>  $payments,
                'end_conclusion'    =>  $end_conclusion,
                'from'  =>  $from,
                'to'    =>  $to,
                'user'  =>  $user
            ];
            
            return \PDF::loadView('ret_fun.print.headship_review', $data)
            ->setOption('encoding', 'utf-8')
            ->setOption('header-html', $headerHtml)
            ->setOption('footer-html', $footerHtml)
            ->setOption('margin-top', 40)
            ->setOption('margin-bottom', 15)
            ->stream("jefaturaRevision.pdf");
    }
    public function printLegalResolution($ret_fun_id){

        $retirement_fund =  RetirementFund::find($ret_fun_id);        
        $affiliate = Affiliate::find($retirement_fund->affiliate_id);
        
        $art = [
            3  =>  28,
            4 =>  29,
            5 =>  30,
            6 =>  30,
            7 =>  31
        ];

        $law = 'Que, el Decreto Supremo N° 1446 de 19 de diciembre de 2012, Artículo 2 de la CREACIÓN Y
        NATURALEZA JURÍDICA, Parágrafo I establece: <i>“Se crea la Mutual de Servicios al Policía –
        MUSERPOL, como institución pública descentralizada, de duración indefinida y patrimonio
        propio, con autonomía de gestión administrativa, financiera, legal y técnica, bajo tuición del
        Ministerio de Gobierno.”</i> El Artículo 5 del ÁMBITO DE APLICACIÓN, Parágrafos I y II, refiere: <i>“I.
        El presente Decreto Supremo es aplicable a todas y todos los afiliados activos y pasivos de la
        Policía Boliviana, así como a sus beneficiarios de acuerdo a reglamento. II. Para los afiliados
        activos y pasivos de la Policía Boliviana que hayan sido dados de baja, de forma voluntaria o
        forzosa, los beneficios establecidos en el presente Decreto Supremo estarán sujetos a
        reglamentación interna”</i>.<br><br>
        Que, el Decreto Supremo N° 2829, de 06 de julio de 2016, modificatorio al Decreto Supremo Nº
        1446 de 19 de diciembre de 2012, en el Artículo 2 de las MODIFICACIONES, Parágrafo III
        señala: <i>“Se modifica el Parágrafo II del Artículo 14 del Decreto supremo Nº 1446, de 19 de
        diciembre de 2012, con el siguiente texto: II. El aporte y pago de los beneficios establecidos en
        los incisos a) (Fondo de Retiro) y b) del Parágrafo precedente, serán objeto de un estudio
        técnico financiero y estudio actuarial que asegure su sostenibilidad, en el marco del principio de
        solidaridad”</i>.<br><br>
        Que, el Decreto Supremo N°3231, de 28 de junio de 2017, modificatoria al Decreto Supremo Nº
        1446 de 19 de diciembre de 2012, en el Artículo 2 de las MODIFICACIONES, Parágrafos I, III,
        IV, V y VI señala: <i>“I. Se modifica el inciso c) del Artículo 3 del Decreto Supremo N°1446, de 19
        de diciembre de 2012, con el siguiente texto: “c) Otorgar el beneficio variable del Fondo de
        Retiro Policial Solidario, en el marco del principio de Solidaridad; III. Se modifica el inciso a) del
        Parágrafo I del Artículo 12 del Decreto Supremo N°1446, de 19 de diciembre de 2012, con el
        siguiente texto: a) Los aportes de los afiliados del sector activo de la Policía Boliviana
        transferidos por el Comando General de acuerdo a estudio actuarial aprobado; IV. Se modifica
        el inciso a) del Parágrafo I del Artículo 14 del Decreto Supremo N°1446, de 19 de diciembre de
        2012, con el siguiente texto: “a) Fondo de Retiro Policial Solidario; V. Se modifica el Parágrafo
        III del Artículo 14 del Decreto Supremo N°1446, de 19 de diciembre de 2012, con el siguiente
        texto: “III. Los beneficios señalados en el presente Artículo se rigen por los principios de
        equidad y solidaridad, debiendo ser otorgados a todos los afiliados, aportantes de la Policía
        Boliviana en sus diferentes sectores y niveles sin ninguna distinción; VI. Se modifica el Artículo
        15 del Decreto Supremo N°1446, de 19 de diciembre de 2012, con el siguiente texto:
        ARTICULO 15 (FONDO DE RETIRO POLICIAL SOLIDARIO). Es el beneficio que brinda
        protección a los miembros del servicio activo y sus derechohabientes, mediante el
        reconocimiento de un pago único, con motivo y oportunidad del retiro definitivo de la actividad
        remunerada dependiente de la Policía Boliviana, el cual será administrado por la MUSERPOL; a
        ser otorgado en el marco del principio de solidaridad, cuando el retiro se produzca por: a)
        Jubilación, b) Fallecimiento del titular, c) Retiro forzoso, d) Retiro voluntario”</i>.<br><br>
        Que, el Estudio Matemático Actuarial 2016 – 2020, aprobado mediante Resolución de Directorio
        Nº 26/2017, de 11 de agosto de 2017, determina la modalidad y parámetros de calificación para
        la otorgación del beneficio de Fondo de Retiro Policial Solidario.
        <br><br>
        Que, el Reglamento de Fondo de Retiro Policial Solidario, aprobado mediante Resolución de
        Directorio Nº 31/2017 de 24 de agosto de 2017 y modificado mediante Resolución de Directorio
        Nº 36/2017 de 20 de septiembre de 2017, Artículos 2,3,5,7,8,10,12,13,15,26,27,'.$art[$retirement_fund->procedure_modality_id].',37,41,44,45 y 55 reconocen el derecho de la otorgación del pago de Fondo de Retiro Policial Solidario.
        <br><br>
        Que, el Reglamento de Fondo de Retiro Policial Solidario, Artículo 15 del RECONOCIMIENTO
        DE LOS APORTES, señala: “La MUSERPOL reconoce la densidad de aportes efectuados a
        partir de mayo de 1976, al Ex Fondo Complementario de Seguridad Social de la Policía
        Nacional y a la extinta Mutual de Seguros del Policía MUSEPOL”.
        <br><br>';
        
        if($retirement_fund->procedure_modality_id == 1 || $retirement_fund->procedure_modality_id == 2) {
            $law .= "Que dicho Reglamento, en su Artículo 20 de la PROCEDENCIA del pago global, Parágrafo I señala: 
            <i>“El pago global de aportes procederá, cuando el afiliado no haya cumplido con 60 cotizaciones (5 años) para acceder al pago del Fondo de Retiro Policial Solidario, antes 
            de su desvinculación laboral con la Policía Boliviana, siendo las causales reconocidas para acceder a este pago el fallecimiento o retiro forzoso por invalidez permanente.”</i><br><br>";
        }
        $discounts = $retirement_fund->discount_types();
        $discount = $discounts->where('discount_type_id','>','1')->where('amount','>','0')->count();
        
        if($discount>0) {
            $law.='Que dicho reglamento, en su Artículo 45 del PROCESAMIENTO,
            punto 6 refiere: “Con la liquidación, el trámite será remitido a Jefatura para la verificación de
            actuados y puesta en conocimiento a la Dirección de Estrategias Sociales e Inversiones”
            Artículo 73 de la Retención por Garantes, refiere: <i>“Para dar curso a la solicitud de recuperación
            de deuda efectuada por la Dirección de Estrategias Sociales e Inversiones, ésta deberá contar
            con respaldo documental que el titular tiene conocimiento que se efectuará un descuento a
            favor de su garante con cargo a su beneficio de Fondo de Retiro Policial”</i>.
            <br><br>';
        }
        $discounts = $retirement_fund->discount_types();
        $discount = $discounts->where('discount_type_id','1')->first();
        if(isset($discount->id) && $discount->pivot->amount >0) {
            $law.='Que, el Reglamento de Fondo de Retiro Policial Solidario, Artículo 52 de los Anticipos de Fondo
            de Retiro, Parágrafo II, refiere: <i>“II El saldo pendiente de pago por anticipo, que hubiese sido
            solicitado antes de la disolución de la Ex MUSEPOL, será calificado y cancelado de acuerdo a
            los parámetros establecidos en la Reglamentación vigente a esa fecha”. “III. El saldo pendiente
            de pago por anticipo, que hubiese sido solicitado posterior a la disolución de la Ex MUSEPOL,
            será calificado y cancelado de acuerdo a los parámetros establecidos en el Estudio Matemático
            Actuarial 2016 – 2020 y el presente Reglamento”</i>.
            <br><br>';
        }
        $law.='Que dicho Reglamento, en su Artículo 55 de la DEFINICIÓN Y CONFORMACIÓN, Parágrafo I refiere: 
        “I. La Comisión de Beneficios Económicos es la instancia técnica legal que realiza el procedimiento 
        administrativo para la otorgación del beneficio de Fondo de Retiro Policial Solidario. Es designada 
        mediante Resolución Administrativa de la Dirección General Ejecutiva de la MUSERPOL”. Por consiguiente, 
        la Resolución Administrativa Nº 014/2018 de 8 de mayo de 2018, conforma la Comisión de Beneficios Económicos, 
        en cumplimiento al Reglamento.
        <br><br>';

        if($affiliate->hasAvailability()) {
            $law .='Que dicho Reglamento, en su DISPOSICIÓN TRANSITORIA SEGUNDA (Incluida mediante Resolución de Directorio Nº 36/2017 de 20 de septiembre de 2017 y modificada 
            mediante Resolución de Directorio Nº 51/2017 de 29 de diciembre de 2017), refiere: “ Corresponderá el reconocimiento de aportes laborales realizados con la prima de 
            1.85% durante la permanencia en la reserva activa, más el 5% de rendimiento, toda vez que estos aportes no forman parte de los parámetros de 
            calificación establecidos en el Estudio Matemático Actuarial 2016 – 2020 considerado por el Decreto Supremo Nº 3231 de 28 de junio de 2017”. <br><br>
            Que, el Reglamento de Cuota Mortuoria y Auxilio Mortuorio, aprobado mediante Resolución de Directorio Nº 43/2017 de 8 de noviembre de 2017 y modificado mediante Resolución de Directorio Nº 51/2017 de 29 de diciembre de 2017, en su DISPOSICIÓN TRANSITORIA SEGUNDA (Incluida mediante Resolución de Directorio Nº 51/2017 de 29 de diciembre de 2017), refiere: “Generada la desvinculación de la Policía Boliviana, se reconocerá al titular el aporte laboral efectivizado en el destino de la disponibilidad de las letras en función al aporte laboral efectuado (prima de aportación) más rendimiento de 5%, siempre y cuando no se haya suscitado el fallecimiento y el tiempo de aporte en éste destino no haya formado parte de la calificación del beneficio de Fondo de Retiro Policial”.';
        }        
        
        // $due = 'Que, mediante Resolución de la Comisión de Prestaciones Nº de fecha , se otorgó en calidad
        // de ANTICIPO del 50% el monto de Bs() a favor del Sr. SOF. 1ro. MARIO BAUTISTA
        // MANCILLA con C.I. 2215955 LP .';

        $discount = $retirement_fund->discount_types();        
         $finance = $discount->where('discount_type_id','1')->first();        
        $body_finance = "";
        if(isset($finance) && $finance->pivot->amount > 0) {            
            $body_finance = "<br>Que, mediante <strong>Resolución de la Comisión de Prestaciones N°".$finance->pivot->note_code ."</strong> de fecha ". Util::getStringDate($finance->pivot->note_code_date).",";
            
            if(isset($finance->id) && $finance->pivot->amount > 0){
                $body_finance .= " se otorgó en calidad de ANTICIPO el monto de <b>".Util::formatMoneyWithLiteral($finance->pivot->amount)."</b>, con cargo a liquidación final, a favor del&nbsp;<b>".$affiliate->degree->shortened." ".$affiliate->fullName()."</b> con C.I. N° <b>".$affiliate->identity_card." ".$affiliate->city_identity_card->first_shortened."</b>.<br>";
            }
            else{
                $body_finance .= " no se evidencia pagos o anticipos por concepto de Fondo de Retiro Policial.<br>";
            }
        }

        $applicant = RetFunBeneficiary::where('type', 'S')->where('retirement_fund_id', $retirement_fund->id)->first();        
        $ret_fun_beneficiary = RetFunLegalGuardianBeneficiary::where('ret_fun_beneficiary_id',$applicant->id)->first();
        $reception = 'Que, en fecha <b>'.Util::getStringDate($retirement_fund->reception_date).'</b>, ';
        if(isset($ret_fun_beneficiary->id)) {            
            $legal_guardian = RetFunLegalGuardian::where('id',$ret_fun_beneficiary->ret_fun_legal_guardian_id)->first();
            $reception .= ($legal_guardian->gender=="M"?" el Sr. ":"la Sra. ").Util::fullName($legal_guardian)." con C.I. N° ".$legal_guardian->identity_card." ".$legal_guardian->city_identity_card->first_shortened.",".($legal_guardian->gender=="M"?" Apoderado ":" Apoderada ")."Legal ";
            $reception.= ($affiliate->gender=='M'?'del':'de la').' <b>'.$affiliate->fullNameWithDegree().'</b> con C.I.<b>'.$affiliate->identity_card.' '.$affiliate->city_identity_card->first_shortened.'</b> y en favor '.($affiliate->gender=="M"?"del mismo":"de la misma");
        } else {

            $reception.= ($affiliate->gender=='M'?'el':'la').' <b>'.$affiliate->fullNameWithDegree().'</b> con C.I.<b>'.$affiliate->identity_card.' '.$affiliate->city_identity_card->first_shortened.'</b>';
        }


        $reception.= ', solicita el pago de Fondo de Retiro Policial Solidario, adjuntando documentación solicitada
        por la Unidad; por consiguiente, habiéndose cumplido con los requisitos de orden establecido
        en el Reglamento de Fondo de Retiro Policial Solidario, se dio curso al trámite.<br>';

        //----- QUALIFICATION -----////      
        $body_qualification = "";
        $qualification_id = 23;
        $qualification = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$qualification_id)->first();
        $months  = $affiliate->getTotalQuotes();        
        $body_qualification .= "Que, mediante Calificación Fondo de Retiro Policial Solidario <b>N° ".$qualification->code."</b> de fecha <strong>".Util::getStringDate($qualification->date)."</strong>, de la Encargada de Calificación, realizó el cálculo de otorgación, correspondiente ".($affiliate->gender=='M'?'al':'a la')." <b>"
        .$affiliate->fullNameWithDegree()."</b> con C.I. <b>".$affiliate->identity_card.' '.$affiliate->city_identity_card->first_shortened."</b>, determina el monto de <b>". Util::formatMoneyWithLiteral($retirement_fund->subtotal_ret_fun)."</b>";
        if($affiliate->hasAvailability()) {
            $body_qualification .=", de la misma forma realizó el cálculo por el reconocimiento de aportes laborales durante el periodo de disponibilidad, por no ser considerados en la calificación del beneficio de Fondo de Retiro Policial Solidario, 
            de acuerdo a los parámetros establecidos por el Estudio Matemático Actuarial 2016 – 2020; correspondiéndole el monto de <b>".Util::formatMoneyWithLiteral($retirement_fund->total_availability)."</b>, haciendo un monto total de<strong> ".Util::formatMoneyWithLiteral($retirement_fund->total_availability+$retirement_fund->subtotal_ret_fun)."</strong>";
        }

        $discounts = $retirement_fund->discount_types();
        $discount = $discounts->where('discount_type_id','1')->first();        
        if(isset($discount->id) && $discount->pivot->amount > 0){                                        
            $body_qualification.=" Descontando el monto del anticipo, reconocer el pago de <b>".Util::formatMoneyWithLiteral($retirement_fund->total_availavility+$retirement_fund->total_ret_fun-$discount->pivot->amount)."</b>";
        }        
        $body_qualification.= ".";
        //$body_qualification .= Util::getDiscountCombinations($retirement_fund->id);
        //".Util::getDiscountCombinations($retirement_fund->id);     
        ///----- END QUALIFICATION ----////

        $legal_dictum_id = 25;
        $legal_dictum = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$legal_dictum_id)->first();
        $body_legal_dictum = 'Que, habiéndose verificado el procesamiento establecido en el Reglamento de Fondo de Retiro
        Policial Solidario, se procedió con la emisión de DICTAMEN LEGAL <strong>'.$legal_dictum->code.'</strong> de '.Util::getStringDate($legal_dictum->date).', para la otorgación del beneficio de Fondo de Retiro Policial Solidario por '.$retirement_fund->procedure_modality->name.'.<br>';

        
        $flagy = 0;
        $discounts = $retirement_fund->discount_types();
        $discounts_number = $discounts->where('amount','>','0')->count();

        if($discounts_number > 0) {
            $discounts = $retirement_fund->discount_types();
            $discount = $discounts->where('discount_type_id','2')->first();                           
            $header_discount = false;
            if(isset($discount->id) && $discount->pivot->amount > 0) {
                
                $body_legal_dictum .= $this->getFlagy($discounts_number,$flagy);
                $flagy++;
                $body_legal_dictum .= "Que, la Dirección de Estrategias Sociales e Inversiones, emite Nota de Respuesta con Cite ".$discount->pivot->code." de fecha ".Util::getStringDate($discount->pivot->date).", refiriendo que ".($affiliate->gender=="M"?' el <strong>Sr. ':' la <strong>Sra. ').($affiliate->fullNameWithDegree())."</strong> con C.I. <strong>".$affiliate->identity_card." ".$affiliate->city_identity_card->first_shortened."</strong>, tiene una deuda pendiente con la MUSERPOL, por el monto ";
                $body_legal_dictum.="de <b>".Util::formatMoneyWithLiteral($discount->pivot->amount)."</b>";
                $header_discount = true;
            }
            //
            $discounts = $retirement_fund->discount_types();
            $discount = $discounts->where('discount_type_id','3')->first();                        

            if(isset($discount->id) && $discount->pivot->amount > 0) { 
                $loans = InfoLoan::where('affiliate_id',$affiliate->id)->get();
                $body_legal_dictum .= $this->getFlagy($discounts_number,$flagy);
                $flagy++;
                $flagy++;

                if(!$header_discount) {
                    $body_legal_dictum .= "Que, la Dirección de Estrategias Sociales e Inversiones, emite Nota de Respuesta con Cite ".$discount->pivot->code." de fecha ".Util::getStringDate($discount->pivot->date).", refiriendo que ".($affiliate->gender=="M"?' el <strong>Sr. ':' la <strong>Sra. ').($affiliate->fullNameWithDegree())."</strong> con C.I. <strong>".$affiliate->identity_card." ".$affiliate->city_identity_card->first_shortened."</strong>, tiene una deuda pendiente ";
                } else {
                    $body_legal_dictum .= "";
                }
            
                $num_loans = $loans->count();
                if($num_loans==1)
                    $body_legal_dictum .= " con el (la) Garante: ";
                else
                    $body_legal_dictum .= " con los Garantes: ";
                $i=0;                
                foreach($loans as $loan){
                    $i++;
                    if($i!=1)
                    {
                        if($num_loans-$i==0)
                            $body_legal_dictum .= " y ";
                        else
                            $body_legal_dictum .= ", ";
                    }
                    $body_legal_dictum.= $loan->affiliate_guarantor->fullName()." con C.I. N° ".$loan->affiliate_guarantor->identity_card;                
                    $body_legal_dictum.= " en la suma de <b>".Util::formatMoneyWithLiteral($loan->amount)."</b>";
                }                                
                $body_legal_dictum.=".";
            }            
        }
        
        $then = 'La Comisión de Beneficios Económicos de la Mutual de Servicios al Policía “MUSERPOL” en
        uso de sus facultades y en observancia al Reglamento de Fondo de Retiro Policial Solidario:';

        $cardinal = ['PRIMERA','SEGUNDA','TERCERA','CUARTA','QUINTA'];
        $cardinal_index = 0;

        $discounts = $retirement_fund->discount_types();
        $discount = $discounts->where('discount_type_id','1')->first();    
        $body_resolution = "";    
        if(isset($discount->id) && $discount->pivot->amount > 0){            
            $body_resolution .= "<b>".$cardinal[$cardinal_index++].".-</b> Ratifica el Anticipo otorgado mediante <strong>Resolución de la Comisión de Prestaciones Nº ".$discount->pivot->note_code."</strong> de fecha ".Util::getStringDate($discount->pivot->note_code_date).", por un monto de <b>".Util::formatMoneyWithLiteral($discount->pivot->amount)."</b> con cargo de liquidación final, a favor del<b>&nbsp; ".$affiliate->fullNameWithDegree()."</b> con C.I. <b>".$affiliate->identity_card." ".$affiliate->city_identity_card->first_shortened.".</b><br><br>";
        }
        $months  = $affiliate->getTotalQuotes();
        $qualification_id = 23;
        $qualification = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->where('wf_state_id',$qualification_id)->first();
        $body_resolution .= "<b>".$cardinal[$cardinal_index++].".-</b> Reconocer el beneficio de Fondo de Retiro Policial Solidario por ".ucwords($retirement_fund->procedure_modality->name).", por el periodo de&nbsp;<b>".Util::formatMonthYearLiteral($months).        
        "</b> de acuerdo a Calificación de Fondo de Retiro Policial Solidario, de fecha&nbsp; <strong>".Util::getStringDate($qualification->date)."</strong>, el monto de <strong>".Util::formatMoneyWithLiteral($retirement_fund->subtotal_ret_fun)."</strong>";        
        
        if($affiliate->hasAvailability()) {            
            $availability = Util::sumTotalContributions($affiliate->getDatesAvailability());
            $body_resolution .=" y el reconocimiento de aportes laborales en disponibilidad de&nbsp; <strong>".Util::formatMonthYearLiteral($availability)."</strong> por el monto de&nbsp; <strong>".Util::formatMoneyWithLiteral($retirement_fund->total_availability)."</strong>. Reconociendo el monto TOTAL de <strong>".Util::formatMoneyWithLiteral($retirement_fund->total_availability+$retirement_fund->subtotal_ret_fun)."</strong>.";
        }
        $discounts = $retirement_fund->discount_types();
        $discount = $discounts->where('discount_type_id','1')->first();        
        if(isset($discount->id) && $discount->pivot->amount > 0){                                        
            $body_resolution.=" Descontando el monto del anticipo, reconocer el pago del beneficio de Fondo de Retiro Policial Solidario, por un TOTAL de <strong>".Util::formatMoneyWithLiteral($retirement_fund->total_availavility+$retirement_fund->total_ret_fun-$discount->pivot->amount)."</strong>, a favor ".($affiliate->gender=='M'?'del ':'de la ').$affiliate->degree->shortened." ".$affiliate->fullName()." con C.I. N° ".$affiliate->identity_card." ".$affiliate->city_identity_card->first_shortened.".";
        } 
        $body_resolution.= "<br><br><br>";
        //$body_resolution .= ", reconocer el pago del beneficio de Fondo de Retiro Policial Solidario, por un TOTAL de &nbsp;<b>".Util::formatMoneyWithLiteral($retirement_fund->total)."</b> a favor del <b>".$affiliate->fullNameWithDegree()."</b> con C.I. <b>".$affiliate->identity_card.' '.$affiliate->city_identity_card->first_shortened."</b>.";        

        $discounts = $retirement_fund->discount_types();
        //$discount_sum = $discounts->where('discount_type_id','>','1')->where('retirement_fund_id',$ret_fun_id)->sum('amount');
        $discount_sum = $discounts->where('discount_type_id','>','1')->sum('amount');        
        //return $discount_sum;
        $header_discount = false;
        if($discount_sum > 0){
            $discounts = $retirement_fund->discount_types();
            $discount = $discounts->where('discount_type_id','2')->first();
            if(isset($discount->id) && $discount->pivot->amount > 0) {
                $body_resolution .= "<b>".$cardinal[$cardinal_index++].".-</b> A solicitud de la Dirección de Estrategias Sociales e Inversiones, retener por pago de deuda el monto de <strong>".Util::formatMoneyWithLiteral($discount->pivot->amount)."</strong> a favor de la MUSERPOL";
                $header_discount = true;
            }
                //return $body_resolution;
                    

            $discounts = $retirement_fund->discount_types();
            $discount = $discounts->where('discount_type_id','3')->first();
            if(isset($discount->id) && $discount->pivot->amount >0) {
                
                $loans = InfoLoan::where('affiliate_id',$affiliate->id)->get();            
                if(!$header_discount) {                
                    $body_resolution .= "<b>".$cardinal[$cardinal_index++].".-</b> A solicitud de la Dirección de Estrategias Sociales e Inversiones, retener para pago ";// de los garantes: el monto de <b>".Util::formatMoneyWithLiteral(($discount->pivot->amount??0))."</b> por concepto de garantía de préstamo a favor de";// los señores. ".$discount->code." y nota ".$discount->note_code." de fecha ".$discount->date;               
                } else {                
                    $body_resolution .= "; retener para pago ";
                }
                $num_loans = $loans->count();
                if($num_loans==1)
                    $body_resolution .= "del (de la) Garante: ";
                else
                    $body_resolution .= "de los Garantes: ";
                $i=0;
                foreach($loans as $loan){
                    $i++;
                    if($i!=1)
                    {
                        if($num_loans-$i==0)
                            $body_resolution .= " y ";
                        else
                            $body_resolution .= ", ";
                    }
                    $body_resolution.= $loan->affiliate_guarantor->fullName()." con C.I. N° ".$loan->affiliate_guarantor->identity_card;                
                    $body_resolution.= " en la suma de <b>".Util::formatMoneyWithLiteral($loan->amount)."</b>";
                }
                //$body_resolution .= ".<br><br>";//;" en conformidad al contrato de préstamo Nro. ".($discount->pivot->code??'sin nro')." y la nota ".($discount->pivot->note_code??'sin nota')." de fecha ". Util::getStringDate($retirement_fund->reception_date) .".<br><br>";
            }
            $body_resolution .= ".<br><br>";
        }        

        $body_resolution .= "<b>".$cardinal[$cardinal_index++].".-</b> El monto TOTAL a pagar de&nbsp; <strong>".Util::formatMoneyWithLiteral($retirement_fund->total)."</strong>, a favor ".($affiliate->gender=='M'?'del beneficiario: ':'de la beneficiaria: ')."<br><br>";        

        if($retirement_fund->procedure_modality_id == 4) {
            $body_resolution .= "<br><br>";
            $beneficiaries = RetFunBeneficiary::where('retirement_fund_id',$retirement_fund->id)->get();
            foreach($beneficiaries as $beneficiary){
                $birth_date = Carbon::createFromFormat('d/m/Y', $beneficiary->birth_date);                
                if(date('Y') -$birth_date->format('Y') > 18) {
                $body_resolution .=$beneficiary->gender=='M'?'Sr.':'Sra. ';
                } else {
                    $body_resolution .='Menor ';
                }
                $body_resolution .= $beneficiary->fullName()." con C.I. N° ".$beneficiary->identity_card." ".$beneficiary->city_identity_card->first_shortened.', en el monto de '.Util::formatMoneyWithLiteral($beneficiary->amount_total).' '.'en calidad de '.$beneficiary->kinship->name.".<br><br>"; 
            }
        } else {
            $body_resolution .= "<li class='text-justify'>".($affiliate->gender=='M'?'Sr. ':'Sra. ').$affiliate->degree->shortened." ".$affiliate->fullName()." con C.I. N° ".$affiliate->identity_card." ".$affiliate->city_identity_card->first_shortened.", en calidad de Titular.</li><b><br><br>";
        } 

        $body_resolution .= "<b>REGISTRESE, NOTIFIQUESE Y ARCHIVESE.</b><br><br><br><br><br>
        ";

        // $number = Util::getNextAreaCode($retirement_fund->id);
        $number = RetFunCorrelative::where('retirement_fund_id', $retirement_fund->id)->where('wf_state_id', 26)->first();

        $user = User::find($number->user_id);
        $body_resolution .= "<div class='text-xs italic'>cc. Arch.<br>CONTABILIDAD<br>COMISIÓN</div>";        

        $users_commission=User::where('is_commission', true)->get();
        $data = [
            'retirement_fund'   =>  $retirement_fund,
            'law'  =>  $law,
            'correlative'   =>  $number,
            'ret_fun' => $retirement_fund,                        
            'affiliate' =>  $affiliate,
            'actual_city'  =>  Auth::user()->city->name,
            'actual_date'  =>  Util::getStringDate($number->date), 
            'body_finance'  =>  $body_finance,
            'reception' =>  $reception,
            'body_qualification'    =>  $body_qualification,
            'then'  =>  $then,
            'user'  =>  $user,
            'body_resolution'   =>  $body_resolution,
            'users_commission'  =>  $users_commission,
            'body_legal_dictum' =>  $body_legal_dictum,
        ];
        $bar_code = \DNS2D::getBarcodePNG(($retirement_fund->getBasicInfoCode()['code'] . "\n\n" . $retirement_fund->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
        $headerHtml = view()->make('ret_fun.print.legal_header')->render();
        $footerHtml = view()->make('ret_fun.print.resolution_footer', ['bar_code' => $bar_code])->render();
        return \PDF::loadView('ret_fun.print.legal_resolution', $data)
            ->setOption('encoding', 'utf-8')
            ->setOption('footer-html', $footerHtml)
            ->setOption('header-html', $headerHtml)
            ->setOption('margin-top', 40)
            ->setOption('margin-bottom', 30)
            ->stream("jefaturaRevision.pdf");
    }
    private function getFlagy($num, $pos, $text = "")
    {
        if($pos == 0) {
            return;
        }

        if ($num == ($pos+1))
            return " y ".$text;

        return ", ";                
    }

}
