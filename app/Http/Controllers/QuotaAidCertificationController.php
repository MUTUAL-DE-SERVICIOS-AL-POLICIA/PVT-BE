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
use Muserpol\Models\Contribution\AidCommitment;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Models\QuotaAidMortuary\QuotaAidSubmittedDocument;
use Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\QuotaAidCorrelative;
use Muserpol\Models\AffiliateFolder;
use Muserpol\Models\Contribution\Contribution;
use Muserpol\Models\Contribution\Reimbursement;
use Muserpol\Models\Contribution\AidReimbursement;
use Muserpol\Models\Degree;
class QuotaAidCertificationController extends Controller
{
    public function saveCertificationNote(Request $request, $quota_aid_id)
    {
        $retirement_fund =  QuotaAidMortuary::find($quota_aid_id);
        if ($request->note) {
            $wf_state = WorkflowState::where('role_id', Util::getRol()->id)->first();
            Util::getNextAreaCodeQuotaAid($quota_aid_id);
            $ret_fun_correlative = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid_id)->where('wf_state_id', $wf_state->id)->first();
            $ret_fun_correlative->note = $request->note;
            $ret_fun_correlative->save();
            Log::info('note saved');
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
        $username = Auth::user()->username;//agregar cuando haya roles
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
        return \PDF::loadView('quota_aid.print.quota_aid_commitment_letter', compact('date', 'username', 'title', 'glosa', 'bene', 'city', 'beneficiary'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    }

    public function printDirectContributionQuoteAid(Request $request)
    {
        $contributions  = json_decode($request->contributions);     
        $total = $request->total;
        $total_literal = Util::convertir($total);
        $affiliate = Affiliate::find($request->affiliate_id);                                
        $date = Util::getStringDate(date('Y-m-d'));        
        $username = Auth::user()->username;//agregar cuando haya roles
        $name_user_complet = Auth::user()->first_name." ".Auth::user()->last_name;        
        $commitment = AidCommitment::where('affiliate_id',$affiliate->id)->where('state','ALTA')->first();
        $title = "APORTE DIRECTO";        
        if(isset($commitment->id)) {
            $title .= " - ".($commitment->contributor=='T'?'Titular':'Cónyuge');
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
                ))
                ->setPaper('letter')
                ->setOption('encoding', 'utf-8')
                ->setOption('footer-right', 'Pagina [page] de [toPage]')
                ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')                
                ->stream("$namepdf");
    }  

    public function printVoucherQuoteAid(Request $request, $affiliate_id,$voucher_id)
    {
        $affiliate = Affiliate::find($affiliate_id);
        $voucher = Voucher::find($voucher_id);
        $aid_contributions=[];
        $total_literal = Util::convertir($voucher->total);
        $payment_date = Util::getStringDate($voucher->payment_date);
        $date = Util::getStringDate(date('Y-m-d'));
        $title = "RECIBO";
        $subtitle = "AUXILIO MORTUORIO <br> (Expresado en Bolivianos)";
        $username = Auth::user()->username;//agregar cuando haya roles
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
        return \PDF::loadView('quota_aid.print.voucher_aid_contribution', 
                compact('date', 
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
                        'date'))
                ->setPaper('letter')
                ->setOption('encoding', 'utf-8')
                ->setOption('footer-right', 'Pagina [page] de [toPage]')
                ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
                ->stream("$namepdf");
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
        $title = "REQUISITOS DEL BENEFICIO DE ". $quota_aid->procedure_modality->procedure_type->name . ' - '. mb_strtoupper($modality);

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
        $bar_code = \DNS2D::getBarcodePNG(($quota_aid->getBasicInfoCode()['code'] . "\n\n" . $quota_aid->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
        $applicant = QuotaAidBeneficiary::where('type', 'S')->where('quota_aid_mortuary_id', $quota_aid->id)->first();
        $pdftitle = "RECEPCIÓN - " . $title;
        $namepdf = Util::getPDFName($pdftitle, $applicant);
        $footerHtml = view()->make('quota_aid.print.footer', ['bar_code' => $bar_code])->render();

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
        ];
        $pages = [];
        for ($i = 1; $i <= 2; $i++) {
            $pages[] = \View::make('quota_aid.print.reception', $data)->render();
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

    public function printLegalReview($id){

        $quota_aid = QuotaAidMortuary::find($id);        
        $next_area_code = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', 35)->first();
        $code = $quota_aid->code;        
        $area = $next_area_code->wf_state->first_shortened;
        $user = $next_area_code->user;
        $date = Util::getDateFormat($next_area_code->date);
        $number = $next_area_code->code;

        $title = "CERTIFICACI&Oacute;N DE DOCUMENTACI&Oacute;N PRESENTADA Y REVISADA";
        $submitted_documents = QuotaAidSubmittedDocument::
                                select(
                                    'quota_aid_submitted_documents.id',
                                    'quota_aid_submitted_documents.quota_aid_mortuary_id',
                                    'quota_aid_submitted_documents.procedure_requirement_id',
                                    'quota_aid_submitted_documents.is_valid',
                                    'quota_aid_submitted_documents.reception_date')
                                ->where('quota_aid_submitted_documents.quota_aid_mortuary_id', $id)
                                ->leftJoin('procedure_requirements','quota_aid_submitted_documents.procedure_requirement_id','=','procedure_requirements.id')
                                ->orderBy('procedure_requirements.number', 'ASC')->get();

        $affiliate = $quota_aid->affiliate;
        $footerHtml = view()->make('quota_aid.print.footer', ['bar_code'=>$this->generateBarCode($quota_aid)])->render();
        $cite = $number;//RetFunIncrement::getIncrement(Session::get('rol_id'), $quota_aid->id);
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
            'quota_aid'=>$quota_aid,
            'affiliate'=>$affiliate,
            'submitted_documents'=>$submitted_documents,
        ];

        $pages = [];
        for ($i = 1; $i <= 2; $i++) {
            $pages[] = \View::make('quota_aid.print.legal_certification',$data)->render();
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
        $affiliate = Affiliate::find($id);
        $role = Util::getRol();
                
        $quota_aid = QuotaAidMortuary::where('affiliate_id', $affiliate->id)->get()->last();        
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
        $footerHtml = view()->make('quota_aid.print.footer', ['bar_code'=>$this->generateBarCode($quota_aid)])->render();
        $data = [
            'code' => $code,
            'area' => $area,
            'user' => $user,
            'date' => $date,
            'number' => $number,

            'cite'=>$cite,
            'subtitle'=>$subtitle,
            'title'=>$title,
            'quota_aid'=>$quota_aid,
            'affiliate'=>$affiliate,
            'affiliate_folders'=>$affiliate_folders,
            'applicant'=>$applicant,
            'unit1'=>'archivo y gestión documental<br> beneficios económicos',
        ];
        $pages = [];
        for ($i = 1; $i <= 2; $i++) {
            $pages[] = \View::make('quota_aid.print.file_certification',$data)->render();
        }
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '15mm')
            ->setOption('footer-html', $footerHtml)
            ->stream("$namepdf");

    }
    public function printCertification($id)
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

        $end_date = Carbon::createFromFormat('d/m/Y', $affiliate->date_death);
        $end_date->subMonth();
        $start_date = Carbon::createFromFormat('d/m/Y', $affiliate->date_death);
        $start_date->subMonths(12); // change by procedure cotizations            
        
        $spouse = null;
        if($quota_aid->procedure_modality->procedure_type_id == 3) {
            Util::completQuotaContributions($affiliate->id,$start_date->copy(),$end_date->copy());
            $contributions = Contribution::where('affiliate_id',$affiliate->id)->where('month_year','>=',$start_date->format('Y-m')."-01")->whereDate('month_year','<=',$end_date->format('Y-m')."-01")->orderByDesc('month_year')->get();
            $reimbursements = Reimbursement::where('affiliate_id',$affiliate->id)->where('month_year','>=',$start_date->format('Y-m')."-01")->whereDate('month_year','<=',$end_date->format('Y-m')."-01")->orderByDesc('month_year')->get();
        }   
        //return $start_date->format('Y-m');     
        
        if($quota_aid->procedure_modality->procedure_type_id == 4) {            
            Util::completAidContributions($affiliate->id,$start_date->copy(),$end_date->copy());
            $contributions = AidContribution::where('affiliate_id',$affiliate->id)->where('month_year','>=',$start_date->format('Y-m')."-01")->whereDate('month_year','<=',$end_date->format('Y-m')."-01")->orderByDesc('month_year')->get();
            $reimbursements = AidReimbursement::where('affiliate_id',$affiliate->id)->where('month_year','>=',$start_date->format('Y-m')."-01")->whereDate('month_year','<=',$end_date->format('Y-m')."-01")->orderByDesc('month_year')->get();
            if($quota_aid->procedure_modality_id == 14 || $quota_aid->procedure_modality_id == 15) {
                $spouse = $affiliate->spouse()->first();
            }
        }
        //return $contributions;
        $degree = Degree::find($affiliate->degree_id);
        $exp = City::find($affiliate->city_identity_card_id);
        $exp = ($exp==Null)? "-": $exp->first_shortened;
        $dateac = Carbon::now()->format('d/m/Y');
        $place = City::find(Auth::user()->city_id);
        $num=0;
        $pdftitle = "Cuentas Individuales";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);

        $subtitle = $next_area_code->code;
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
        $title = "CERTIFICACIÓN ".$quota_aid->procedure_modality->procedure_type->second_name;
        $contributions_number = 12;
        $data = [
            'code' => $code,
            'area' => $area,
            'user' => $user,
            'date' => $date,
            'number' => $number,
            'contributions_number' => $contributions_number,
            'num'=>$num,
            'subtitle'=>$subtitle,
            'place'=>$place,
            'quota_aid'=>$quota_aid,
            'reimbursements'=>$reimbursements,
            'dateac'=>$dateac,
            'exp'=>$exp,
            'degree'=>$degree,
            'contributions'=>$contributions,
            'affiliate'=>$affiliate,
            'title'=>$title,
            'spouse' => $spouse,
            //'institution'=>$institution,
            //'direction'=>$direction,
            'unit'=>$unit,
        ];
        if($quota_aid->procedure_modality->procedure_type_id == 3) {
            return \PDF::loadView('contribution.print.certification_quota_contribution', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
        }
        if($quota_aid->procedure_modality->procedure_type_id == 4) {
            return \PDF::loadView('contribution.print.certification_aid_contribution', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
        }
    }

    private function generateBarCode($quota_aid){
        $bar_code = \DNS2D::getBarcodePNG((
                        $quota_aid->getBasicInfoCode()['code']."\n\n".$quota_aid->getBasicInfoCode()['hash']), 
                        "PDF417", 
                        100, 
                        33, 
                        array(1, 1, 1)
                    );
        return $bar_code;
    }
}
