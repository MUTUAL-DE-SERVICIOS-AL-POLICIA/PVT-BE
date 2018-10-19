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
use Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisorBeneficiary;
use Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiaryLegalGuardian;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\QuotaAidMortuary\QuotaAidCorrelative;
use Muserpol\Models\AffiliateFolder;
use Muserpol\Models\Contribution\Contribution;
use Muserpol\Models\Contribution\Reimbursement;
use Muserpol\Models\Contribution\AidReimbursement;
use Muserpol\Models\Degree;
use Muserpol\Models\Testimony;
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
        // ->where('wf_state_id', 23)
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
            return \PDF::loadView('quota_aid.print.beneficiaries_qualification', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
        }
        return $data;
    }
    public function printQualificationData($id, $only_print = true)
    {
        $quota_aid = QuotaAidMortuary::find($id);
        $affiliate = $quota_aid->affiliate;
        $beneficiaries = $quota_aid->quota_aid_beneficiaries()->orderByDesc('type')->get();

        $next_area_code = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)
        // ->where('wf_state_id', 37)
            ->first();
        $code = $quota_aid->code;
        $area = $next_area_code->wf_state->first_shortened;
        $user = $next_area_code->user;
        $date = Util::getDateFormat($next_area_code->date);
        $number = $next_area_code->code;

        $dates = $affiliate->getContributionsWithTypeQuotaAid();
        if (sizeof($dates) > 1) {
            return "error";
        }
        $start_date = $dates[0]->start;
        $end_date = $dates[0]->end;

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
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];

        if ($only_print) {
            return \PDF::loadView('quota_aid.print.qualification_data', $data)
                ->setOption('encoding', 'utf-8')
                        // ->setOption('footer-right', 'Pagina [page] de [toPage]')
                ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
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
            ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
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
        $footerHtml = view()->make('quota_aid.print.footer', ['bar_code' => $this->generateBarCode($quota_aid)])->render();
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
        $footerHtml = view()->make('quota_aid.print.footer', ['bar_code' => $this->generateBarCode($quota_aid)])->render();
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
        

        if($quota_aid->procedure_modality_id == 15 || $quota_aid->procedure_modality_id == 14) {
            $spouse = Spouse::where('affiliate_id',$affiliate->id)->first();            
            $end_date = Carbon::createFromFormat('Y-m-d', $spouse->date_death);
            $start_date = Carbon::createFromFormat('Y-m-d', $spouse->date_death);            
        } else {
            $end_date = Carbon::createFromFormat('d/m/Y', $affiliate->date_death);
            $start_date = Carbon::createFromFormat('d/m/Y', $affiliate->date_death);    
        }        
        $end_date->subMonth();                
        $start_date->subMonths(12); // change by procedure cotizations            
        $spouse = null;
        $valid_contributions = null;
        if($quota_aid->procedure_modality->procedure_type_id == 3) {
            Util::completQuotaContributions($affiliate->id,$start_date->copy(),$end_date->copy());
            $contributions = Contribution::where('affiliate_id',$affiliate->id)->where('month_year','>=',$start_date->format('Y-m')."-01")->whereDate('month_year','<=',$end_date->format('Y-m')."-01")->orderByDesc('month_year')->get();
            $valid_contributions = $contributions;
            $reimbursements = Reimbursement::where('affiliate_id',$affiliate->id)->where('month_year','>=',$start_date->format('Y-m')."-01")->whereDate('month_year','<=',$end_date->format('Y-m')."-01")->orderByDesc('month_year')->get();
        }   
        //return $start_date->format('Y-m');     
        
        if($quota_aid->procedure_modality->procedure_type_id == 4) {            
            $aid_commitment = AidCommitment::where('affiliate_id',$affiliate->id)->where('state','ALTA')->first();
            
            if(!isset($aid_commitment->id)) {
                Session::flash('message','No se encontró compromiso de pago');
                return redirect('affiliate/'.$affiliate->id);   
            }
            $valid_contributions = AidContribution::where('affiliate_id',$affiliate->id)
                                        ->whereDate('month_year','>=',$start_date->format('Y-m')."-01")
                                        ->whereDate('month_year','<=',$end_date->format('Y-m')."-01")
                                        ->whereDate('month_year','>=',$aid_commitment->start_contribution_date)
                                        ->orderByDesc('month_year')->pluck('id','month_year');                       

                //return $valid_contributions;
            Util::completAidContributions($affiliate->id,$start_date->copy(),$end_date->copy());
            $contributions = AidContribution::where('affiliate_id',$affiliate->id)
                                                ->whereDate('month_year','>=',$start_date->format('Y-m')."-01")
                                                ->whereDate('month_year','<=',$end_date->format('Y-m')."-01")
                                                //->whereDate('month_year','>=',$aid_commitment->date_commitment)
                                                ->orderByDesc('month_year')->get();
            $reimbursements = AidReimbursement::where('affiliate_id',$affiliate->id)->where('month_year','>=',$start_date->format('Y-m')."-01")->whereDate('month_year','<=',$end_date->format('Y-m')."-01")->orderByDesc('month_year')->get();
            if($quota_aid->procedure_modality_id == 14 || $quota_aid->procedure_modality_id == 15) {
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
            return \PDF::loadView('contribution.print.certification_quota_contribution', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
        }
        if ($quota_aid->procedure_modality->procedure_type_id == 4) {
            return \PDF::loadView('contribution.print.certification_aid_contribution', $data)->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
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



    public function printLegalDictum($id) {
        $quota_aid = QuotaAidMortuary::find($id);

        $applicant = QuotaAidBeneficiary::where('type', 'S')->where('quota_aid_mortuary_id', $quota_aid->id)->first();
        
        $beneficiaries = QuotaAidBeneficiary::where('quota_aid_mortuary_id',$quota_aid->id)->orderByDesc('type')->orderBy('id')->get();        
        /** PERSON DATA */
        $person = "";
        $affiliate = Affiliate::find($quota_aid->affiliate_id);                        
        $ret_fun_beneficiary = QuotaAidBeneficiaryLegalGuardian::where('quota_aid_beneficiary_id',$applicant->id)->first();
        

        if(isset($ret_fun_beneficiary->id)) {
            $legal_guardian = QuotaAidLegalGuardian::where('id',$ret_fun_beneficiary->ret_fun_legal_guardian_id)->first();
            $person .= ($legal_guardian->gender=='M'?"El señor ":"La señora ").Util::fullName($legal_guardian)." con C.I. N° ".$legal_guardian->identity_card." ".$legal_guardian->city_identity_card->first_shortened.". a través de Testimonio Notarial N° ".$legal_guardian->number_authority." de fecha ".Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority))." sobre poder especial, bastante y suficiente emitido por ".$legal_guardian->notary_of_public_faith." a cargo del Notario ".$legal_guardian->notary." en representación ".($affiliate->gender=='M'?"del señor ":"de la señora ");
        } else {
            $person .= ($affiliate->gender=='M'?"El señor ":"La señora ");
        }        
        $person .= $affiliate->fullNameWithDegree() ." con C.I. N° ". $affiliate->ciWithExt() .", como TITULAR FALLECIDO ".($quota_aid->procedure_modality_id == 4?"FALLECIDO ":" ")."del beneficio de ".$quota_aid->procedure_modality->procedure_type->second_name." en su modalidad de <strong class='uppercase'>". $quota_aid->procedure_modality->name ."</strong>,";
        if($quota_aid->procedure_modality_id == 4 || true) {
            //$person .= " presenta la documentación para la otorgación del beneficio en fecha ". Util::getStringDate($quota_aid->reception_date) .", a lo cual considera lo siguiente:";
       
            $person .=  ($applicant->gender=='M'?' el Sr. ':' la Sra. ').Util::fullName($applicant)." con C.I. N° ". $applicant->identity_card." ".$applicant->city_identity_card->first_shortened.". solicita el beneficio a favor suyo en calidad de ".$applicant->kinship->name; 
            $testimony_applicant = Testimony::find($applicant->testimonies()->first()->id);
            
           // foreach($testimonies_applicant as $testimony) {
                $beneficiaries = $testimony_applicant->ret_fun_beneficiaries;
                $quantity = $beneficiaries->count();                
                $start_message = false;
                if($quantity > 1) {
                    $person .= " y de los derechohabientes ";
                    $start_message = true;
                }
                foreach($beneficiaries as $beneficiary) {
                    if($beneficiary->id != $applicant->id) {
                        if(!$start_message) {
                            $person = $person .= " y ".($beneficiary->gender=="M"?"del":"de la")." derechohabiente ";
                        }
                        $person .= Util::fullName($beneficiary)." con C.I. N° ". $beneficiary->identity_card." ".($beneficiary->city_identity_card->first_shortened??"SIN CI")."."." en calidad de ".$beneficiary->kinship->name.((--$quantity)==2?" y ":(($quantity==1)?'':', '));
                    }
                }
                $quantity = $beneficiaries->count();
                if($quantity > 1) {
                    $person .=" como herederos legales acreditados mediante ".$testimony_applicant->document_type." Nº ".$testimony_applicant->number." de fecha ".Util::getStringDate($testimony_applicant->date)." sobre Declaratoria de Herederos, emitido por ".$testimony_applicant->court." de ".$testimony_applicant->place." a cargo de ".$testimony_applicant->notary."";
                } else {
                    $person .=" como ".($applicant->gender=="M"?"heredero legal acreditado":"heredera legal acreditada")." mediante ".$testimony_applicant->document_type." Nº ".$testimony_applicant->number." de fecha ".Util::getStringDate($testimony_applicant->date)." sobre Declaratoria de Herederos, emitido por ".$testimony_applicant->court." de la cuidad de ".$testimony_applicant->place." a cargo de ".$testimony_applicant->notary."";
                }
            //} 

            $testimonies_applicant = Testimony::where('affiliate_id',$affiliate->id)->where('id','!=',$applicant->testimonies()->first()->id)->get();
            foreach($testimonies_applicant as $testimony) {
                $beneficiaries = $testimony->ret_fun_beneficiaries;                
                $beneficiaries = $beneficiaries->where('state',true);
                $quantity = $beneficiaries->count();
                $start_message = false;
                if($quantity > 0) {
                    if($quantity > 1) {
                        $person .= "; asimismo solicitan el beneficio los derechohabientes ";
                        $start_message = true;
                    } 
                    $stored_quantity = $quantity;
                    foreach($beneficiaries as $beneficiary) {
                        //if($beneficiary->state)
                        if(!$start_message)
                        {
                            $person = $person .= " asimismo solicita el beneficio ".($beneficiary->gender=="M"?"el":"la")." derechohabiente ";
                        }
                        $person .= Util::fullName($beneficiary)." con C.I. N° ". $beneficiary->identity_card." ".($beneficiary->city_identity_card->first_shortened??"SIN CI").". en calidad de ".$beneficiary->kinship->name.((--$quantity)==1?" y ":(($quantity==0)?'':', '));
                    }
                    if($stored_quantity > 1) {
                        $person .=" como herederos legales acreditados mediante ".$testimony->document_type." Nº ".$testimony->number." de fecha ".Util::getStringDate($testimony->date)." sobre Declaratoria de Herederos, emitido por ".$testimony->court." de ".$testimony->place." a cargo de ".$testimony->notary."";
                    } else {
                        $person .=" como ".($applicant->gender=="M"?"heredero legal acreditado":"heredera legal acreditada")." mediante ".$testimony->document_type." Nº ".$testimony->number." de fecha ".Util::getStringDate($testimony->date)." sobre Declaratoria de Herederos, emitido por ".$testimony->court." de la cuidad de ".$testimony->place." a cargo de ".$testimony->notary."";
                    }                    
                }
            } 
            $person .=". Presentando";
        } else {
            $person .= " presenta";
        }
        $person .= " la documentación para la otorgación del beneficio en fecha ". Util::getStringDate($quota_aid->reception_date) .", a lo cual considera lo siguiente:";        
        //return $person;
        /** END PERSON DATA */

        /** LAW DATA */
        $art = [
            '8' => '43 a)',
            '9' => '43 b)',
            '13' => '44 a)',
            '14' => '44 b)',
            '15' => '44 c)',
        ];

        $law = "Conforme normativa, el trámite N° ".$quota_aid->code." de la Regional ".ucwords($quota_aid->city_start->name)." es ingresado por Ventanilla
        de Atención al Afiliado de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio; verificados los requisitos y la documentación presentada por la parte solicitante según lo señalado el Art. 43 inciso a) (Cuota Mortuoria al Fallecimiento del Titular en Cumplimiento de Funciones) del Reglamento de Cuota Mortuoria y Auxilio Mortuorio aprobado mediante Resolución de Directorio N° 43/2017 de fecha 08 de noviembre de 2017 y modificado mediante Resolución de Directorio N° 51/2017 de fecha 29 de diciembre de 2017, y conforme el Art. 48 de referido Reglamento (Procesamiento), de referido Reglamento, se detalla la documentación como resultado de la aplicación de la base técnica-legal del Estudio Matemático Actuarial 2016-2020, generada y adjuntada al expediente por los funcionarios de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, según correspondan las funciones, detallando lo siguiente:";
        /** END LAW DATA */

        $body = "";        

        ///---FILE---///
        $body_file = "";    
        $file_id = 34;
        $file = QuotaAidCorrelative::where('quota_aid_mortuary_id',$quota_aid->id)->where('wf_state_id',$file_id)->first();
        
        $body_file .= "Que, mediante Certificación N° ". $file->code .", de Archivo de la Dirección de Beneficios Económicos de fecha ". Util::getStringDate($file->date) .", se establece que el trámite signado con el N° ". $quota_aid->code." ";
        $discount = $quota_aid->discount_types();
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
        $discount = $quota_aid->discount_types();
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
        $legal_review_id = 35;
        $legal_review = QuotaAidCorrelative::where('quota_aid_mortuary_id',$quota_aid->id)->where('wf_state_id',$legal_review_id)->first();
        $body_legal_review .= "Que, mediante Certificación N° ".$legal_review->code." del Área Legal de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha ".Util::getStringDate($legal_review->date).", fue verificada y validada la documentación presentada por ".($quota_aid->procedure_modality_id == 4?"los beneficiarios":($affiliate->gender=="M"?"el titular":"la titular")) ." del trámite signado con el N° ".$quota_aid->code.".";
        /////-----END LEGAL REVIEW----///
        
        ///------ INDIVIDUAL ACCCOUTNS ------////    
        $body_accounts = "";           
        $accounts_id = 36;
        $accounts = QuotaAidCorrelative::where('quota_aid_mortuary_id',$quota_aid->id)->where('wf_state_id',$accounts_id)->first();
        $availability_code = 10;
        $availability_number_contributions = Contribution::where('affiliate_id',$affiliate->id)->where('contribution_type_id',$availability_code)->count();

        $end_contributions = [
            '3'  =>  'destino a disponibilidad de la letra (reserva activa) '.($affiliate->gender=='M'?'del':'de la').' titular.',
            '4'  =>  'del fallecimiento del Titular.',
            '5'  =>  'de su retiro.',
            '6'  =>  'de su retiro.',
            '7'  =>  'de su retiro.',
        ];
        $body_accounts = "Que, mediante Certificación de Aportes N° ".$accounts->code." del Área de Cuentas Individuales de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha ". Util::getStringDate($accounts->date) .", se verificó los últimos "."12"." aportes antes del fallecimiento del titular.";
        
        ////------- INDIVIDUAL ACCOUTNS ------////

        //----- QUALIFICATION -----////      
        $body_qualification = "";
        $qualification_id = 37;
        $qualification = QuotaAidCorrelative::where('quota_aid_mortuary_id',$quota_aid->id)->where('wf_state_id',$qualification_id)->first();
        $months  = $affiliate->getTotalQuotes();        
        $body_qualification .=  "Que, mediante Calificación de Fondo de Retiro Policial Solidario N° ".$qualification->code." del Área de Calificación de la Unidad de Otorgación de Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha ". Util::getStringDate($qualification->date) .", se realizó el cálculo por el periodo de<strong> ". Util::formatMonthYearLiteral($months)."</strong>, determinando el beneficio de <strong>Fondo de Retiro Policial Solidario por ".mb_strtoupper($quota_aid->procedure_modality->name)."&nbsp;&nbsp;</strong>de<strong> ". Util::formatMoneyWithLiteral($quota_aid->subtotal_ret_fun) ."</strong>".Util::getDiscountCombinations($quota_aid->id);
        $body_qualification .= ".";
        
        ////----- DUE -----////
        // $discounts = $quota_aid->discount_types();
        // $discount = $discounts->where('discount_type_id','2')->first();
        // $body_due = "";
        // $body_due .= "Que, mediante nota ".($discount->pivot->code??'Sin nota'). " de la Dirección de Estrategias Sociales e Inversiones de fecha ".Util::getStringDate(($discount->pivot->date??'')). ", 
        //             refiere que ".($affiliate->gender == 'M'?'el':'la')." titular ";
        
        // $discounts = $quota_aid->discount_types();
        // $discount_counter = $discounts->where('discount_type_id','>','1')->where('amount','>','0')->count();
        // if($discount_counter == 0) {
        //     $body_due .="no cuenta con deuda en curso de pago a MUSERPOL ni por concepto de garantía de préstamo.";
        // } else {                        
        //         $and = "";
        //         $discounts = $quota_aid->discount_types();
        //         $discount = $discounts->where('discount_type_id','2')->first();
        //         if(isset($discount->id) && $discount->pivot->amount >0) {                    
        //             $body_due .="si cuenta con deuda en curso de pago a MUSERPOL";
        //             $and = " y ";
        //         }                
                
        //         $discounts = $quota_aid->discount_types();
        //         $discount = $discounts->where('discount_type_id','3')->first();
        //         if(isset($discount->id) && $discount->pivot->amount >0) {                    
        //             if($and=="") {
        //                 $body_due .="si cuenta con deuda en curso de pago a MUSERPOL";
        //             }
        //              $body_due .= $and." por concepto de garantía de préstamo";
        //         }
        //         $body_due .= ", supra detallado.";            
        // }
        ///-----END DUE----///

        ///------ PAYMENT ------////
        $payment = "";
        $discounts = $quota_aid->discount_types(); //DiscountType::where('quota_aid_id',$quota_aid->id)->orderBy('discount_type_id','ASC')->get();                
        //$loans = InfoLoan::where('affiliate_id',$affiliate->id)->get();
        $payment = "Por consiguiente, habiendo sido remitido el presente trámite al Área Legal Unidad de Otorgación del Fondo de Retiro Policial Solidario, 
        Cuota y Auxilio Mortuorio, autorizado por Jefatura de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, 
        Cuota y Auxilio Mortuorio conforme a los ";
        if($quota_aid->procedure_modality_id == 8 || $quota_aid->procedure_modality_id == 9) {
            $payment .= "Art. 2, 3, 5, 6, 10, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 43, 45, 47, 48, 51 ";
        } else {
            $payment .= "Art. 2, 3, 5, 6, 10, 31, 32, 33, 36, 37, 38, 39, 40, 41, 42, 44, 45, 47, 48, 52 ";
        }        
        $payment .="y la Disposición Transitoria Única del Reglamento de Cuota Mortuoria y Auxilio Mortuorio aprobado mediante Resolución de Directorio N° 43/2017 en fecha 08 de noviembre de 2017 y 
        modificado mediante Resolución de Directorio N° 51/2017 de fecha 29 de diciembre de 2017. Se <strong>DICTAMINA</strong> en merito a la documentación de respaldo contenida en el presente reconocer 
        los derechos y se otorgue el beneficio de ".$quota_aid->procedure_modality->procedure_type->second_name." por <strong>".$quota_aid->procedure_modality->name."</strong> a favor ";
                        
        $flagy = 0;
        $discounts = $quota_aid->discount_types();
        $discounts_number = $discounts->where('amount','>','0')->count();
        
        $beneficiaries_count = QuotaAidBeneficiary::where('quota_aid_mortuary_id',$quota_aid->id)->count();            
        $payment .=" de ".($beneficiaries_count > 1?"los beneficiarios ":($applicant->gender?"el beneficiario ":"la beneficiaria ")).($affiliate->gender=='M'?"del ":"de la ").$affiliate->fullNameWithDegree()." con C.I. N° ".$affiliate->identity_card." ".($affiliate->city_identity_card->first_shortened??'Sin extencion')."., en el monto de <strong>".Util::formatMoneyWithLiteral($quota_aid->total)."</strong> de la siguiente manera: <br><br>";
        
        if(true) {
            $beneficiaries = QuotaAidBeneficiary::where('quota_aid_mortuary_id',$quota_aid->id)->orderBy('kinship_id')->orderByDesc('state')->get();
            foreach($beneficiaries as $beneficiary){
                if(!$beneficiary->state) {
                    $reserved_quantity = QuotaAidBeneficiary::where('quota_aid_mortuary_id',$quota_aid->id)->where('state',false)->count();
                    $certification = $beneficiary->testimonies()->first();
                    $payment .= "Mediante certificación ".$certification->document_type."-N° ".$certification->number." de ".Util::getStringDate($certification->date)." emitido en la cuidad de ".$certification->place.", se evidencia 
                    la descendencia del titular fallecido; por lo que, se mantiene en reserva".($reserved_quantity>1?" las Cuotas Partes ":" la Cuota Parte ")." salvando los derechos del beneficiario ".
                    ($affiliate->gender=="M"?"del ":"de la ").$affiliate->fullNameWithDegree()." con C.I. N° ".$affiliate->identity_card." ".($affiliate->city_identity_card->first_shortened??"SIN CI").
                    ". conforme establece el Art. 1094 del Código Civil, hasta que presenten la correspondiente Declaratoria de Herederos o Aceptación de Herencia y demás requisitos establecidos de conformidad con los Arts. 29, 34, 35 y 41 del Reglamento de Fondo de Retiro Policial Solidario, aprobado mediante Resolución de Directorio N° 31/2017 en fecha 24 de agosto de 2017 y modificado mediante Resoluciones de Directorio Nros. 36/2017 y 51/2017 de fechas 20 de septiembre de 2017 y 29 de diciembre de 2017 respectivamente, de la siguiente manera:<br><br>";
                }
                //return $beneficiary->birth_date;
                $birth_date = Carbon::createFromFormat('Y-m-d', $beneficiary->birth_date);
                if(date('Y') -$birth_date->format('Y') > 18) {
                $payment .=$beneficiary->gender=='M'?'Sr. ':'Sra. ';
                } else {
                    $payment .='Menor ';
                }
                $payment .= $beneficiary->fullName();
                if($beneficiary->identity_card)
                $payment .=" con C.I. N° ".$beneficiary->identity_card." ".($beneficiary->city_identity_card->first_shortened??"sin extencion");
                $beneficiary_advisor = QuotaAidAdvisorBeneficiary::where('quota_aid_beneficiary_id',$beneficiary->id)->first();
                if(isset($beneficiary_advisor->id))
                {
                    $advisor = QuotaAidAdvisor::where('id',$beneficiary_advisor->quota_aid_advisor_id)->first();
                    $payment .= ", a través de su tutor".($advisor->gender=='F'?'a':'')." natural ".($advisor->gender=='M'?'Sr.':'Sra.')." ".Util::fullName($advisor)." con C.I. N°".$advisor->identity_card." ".($advisor->city_identity_card->first_shortened??"Sin Extencion").".";
                }
                $beneficiary_legal_guardian = QuotaAidBeneficiaryLegalGuardian::where('quota_aid_beneficiary_id',$beneficiary->id)->first();
                if(isset($beneficiary_legal_guardian->id)) {
                    $legal_guardian = QuotaAidLegalGuardian::where('id',$beneficiary_legal_guardian->quota_aid_legal_guardian_id)->first();
                    $payment .= " por si o representada legamente por ".($legal_guardian->gender=='M'?"el Sr.":"la Sra. ")." ".Util::fullName($legal_guardian)." con C.I. N° ".$legal_guardian->identity_card." ".($legal_guardian->city_identity_card->first_shortened??"sin extencion").". 
                    conforme establece la Escritura Pública sobre Testimonio de Poder especial, amplio y suficiente N° ".$legal_guardian->number_authority." de ".Util::getStringDate(Util::parseBarDate($legal_guardian->date_authority))." emitido por ".$legal_guardian->notary.".";
                }
                $payment .= ', en el monto de<strong> '.Util::formatMoneyWithLiteral($beneficiary->paid_amount).'</strong> '.'en calidad de '.$beneficiary->kinship->name.".<br><br>";
            
            }
        } else {            
            $payment .= $affiliate->degree->shortened." ".$affiliate->fullName()." con C.I. N° ".$affiliate->identity_card." ".($affiliate->city_identity_card->first_shortened??"SIN CI")."., el monto de &nbsp;<strong>".Util::formatMoneyWithLiteral($quota_aid->total).".</strong>";
        } 
        
        ///------EN  PAYMENT ------///
        // $number = Util::getNextAreaCode($quota_aid->id);
        $number= QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', 39)->first();
        //return $number;

        $bar_code = \DNS2D::getBarcodePNG(($quota_aid->getBasicInfoCode()['code'] . "\n\n" . $quota_aid->getBasicInfoCode()['hash']), "PDF417", 100, 33, array(1, 1, 1));
        /*HEADER FOOTER*/
        $footerHtml = view()->make('ret_fun.print.legal_footer', ['bar_code' => $bar_code])->render();
        $headerHtml = view()->make('ret_fun.print.legal_header')->render();
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
            'body_due'  =>  "no body",
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
        ->setOption('margin-top',40)
        ->setOption('margin-bottom',15)
        ->stream("dictamenLegal.pdf");
    }
}
