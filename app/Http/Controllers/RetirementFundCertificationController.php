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
use Muserpol\Models\RetirementFund\RetFunAddressBeneficiary;
use Muserpol\Models\RetirementFund\RetFunAdvisor;
use Muserpol\Models\RetirementFund\RetFunIncrement;
use Session;
use Auth;
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
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;

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
    public function printReception($id){
        $retirement_fund = RetirementFund::find($id);
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $modality = $retirement_fund->procedure_modality->name;
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
        $title = "REQUISITOS DEL BENEFICIO FONDO DE RETIRO – ".strtoupper($modality);
        $number = $retirement_fund->code;
        $username = Auth::user()->username;//agregar cuando haya roles
        $date=Util::getStringDate($retirement_fund->reception_date);
        $applicant = RetFunBeneficiary::where('type','S')->where('retirement_fund_id',$retirement_fund->id)->first();
        $submitted_documents = RetFunSubmittedDocument::where('retirement_fund_id',$retirement_fund->id)->get();  
        //return view('ret_fun.print.reception', compact('title','usuario','fec_emi','name','ci','expedido'));

       // $pdf = view('print_global.reception', compact('title','usuario','fec_emi','name','ci','expedido'));       
    //    return view('ret_fun.print.reception',compact('title','institution', 'direction', 'unit','username','date','modality','applicant','submitted_documents','header','number'));
       return \PDF::loadView('ret_fun.print.reception',compact('title', 'institution', 'direction','unit','username','date','modality','applicant','submitted_documents','header','number'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream('recepcion.pdf');
    }
    public function printFile($id){
        $affiliate = Affiliate::find($id);
        $retirement_fund = RetirementFund::where('affiliate_id',$affiliate->id)->get()->last();        
        $number = $retirement_fund->code;
        $date=Util::getStringDate($retirement_fund->reception_date);
        $title = "CERTIFICACION DE ARCHIVO – ".strtoupper($retirement_fund->procedure_modality->name ?? 'ERROR');       
        $username = Auth::user()->username;//agregar cuando haya roles        
        $affiliate_folders = AffiliateFolder::where('affiliate_id',$affiliate->id)->get();
        $applicant = RetFunBeneficiary::where('type','S')->where('retirement_fund_id',$retirement_fund->id)->first();
        $cite = RetFunIncrement::getIncrement(Session::get('rol_id'), $retirement_fund->id);
        $subtitle = $cite;
        return \PDF::loadView('ret_fun.print.file_certification', compact('date','subtitle','username','cite','title','number','retirement_fund','affiliate','affiliate_folders','applicant'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream('recepcion.pdf');
        
    }
    public function printLegalReview($id){
        $retirement_fund = RetirementFund::find($id);
        $date=Util::getStringDate($retirement_fund->reception_date);
        //$title = "CERTIFICACION DE ARCHIVO – ".strtoupper($retirement_fund->procedure_modality->name);       
        $title = "CERTIFICACI&Oacute;N DE DOCUMENTACI&Oacute;N PRESENTADA Y REVISADA";
        $submitted_documents = RetFunSubmittedDocument::where('retirement_fund_id',$id)->orderBy('procedure_requirement_id','ASC')->get();
        $username = Auth::user()->username;//agregar cuando haya roles
        $date=Util::getStringDate($retirement_fund->reception_date);
        $affiliate = $retirement_fund->affiliate;
        $number = $retirement_fund->code;
//        $data = [
//            'retirement_fund'   =>  $retirement_fund,
//            'submitted_documents'   => $submitted_documents,            
//        ];
        $cite = RetFunIncrement::getIncrement(Session::get('rol_id'), $retirement_fund->id);
        $subtitle = $cite;
        return \PDF::loadView('ret_fun.print.legal_certification', compact('date','subtitle','username','title','number','retirement_fund','affiliate','submitted_documents'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream('recepcion.pdf');
    }
    public function printBeneficiariesQualification($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $date =  date('d/m/Y');
        $title = $retirement_fund->procedure_modality->procedure_type->module->name;
        $username = Auth::user()->username;//agregar cuando haya roles
        $affiliate = $retirement_fund->affiliate;
        $number = $retirement_fund->code;
        // return view('ret_fun.print.beneficiaries_qualification', compact('date','subtitle','username','title','number','retirement_fund','affiliate','submitted_documents'));
        return \PDF::loadView('ret_fun.print.beneficiaries_qualification', compact('date','subtitle','username','title','number','retirement_fund','affiliate','submitted_documents'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream('recepcion.pdf');

    }
    public function printCommitmentLetter($id)
    {
        $affiliate = Affiliate::find($id);
        $date =  Util::getStringDate(date('Y-m-d'));
        $username = Auth::user()->username;//agregar cuando haya roles
        $city = Auth::user()->city->name;
        if( $affiliate->affiliate_state->name == "Fallecido"){
            $title = "COMPROMISO DE PAGO - APORTE PARA SOLICITANTES PARA EL PAGO DE APORTE DIRECTO DE LAS (OS) VIUDAS (OS) DEL  SECTOR PASIVO CORRESPONDIENTE AL SISTEMA INTEGRAL DE PENSIONES";
            $spouses = Spouse::where('affiliate_id',$affiliate->id)->first();        
            $beneficiary="en mi calidad de viuda (o)";
            $glosa="Soy beneficiaria(o) (derechohabiente) con una prestación en curso de pago del Sistema Integral de Pensiones (SIP).";
            $bene=$spouses;
        }else{
            $title = "COMPROMISO DE PAGO - APORTE PARA SOLICITANTES PARA EL PAGO DE APORTE DIRECTO DEL SECTOR PASIVO CORRESPONDIENTE AL SISTEMA INTEGRAL DE PENSIONES";
            $beneficiary="como miembro del servicio pasivo de la Policía Boliviana";
            $glosa="Recibo una prestación en curso de pago del Sistema Integral de Pensiones.";
            $bene=$affiliate;
        }
        // return view('ret_fun.print.beneficiaries_qualification', compact('date','subtitle','username','title','number','retirement_fund','affiliate','submitted_documents'));
        return \PDF::loadView('ret_fun.print.commitment_letter', compact('date','subtitle','username','title','retirement_fund','affiliate','submitted_documents','beneficiary','glosa','bene','city'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream('commitment_letter.pdf');
    }
    
    private function getNextCode($actual){
        $year =  date('Y');
        if($actual == "")
            return "1/".$year;
        
        $data = explode('/', $actual);        
        if(!isset($data[1]))
            return "1/".$year;                
        return ($year!=$data[1]?"1":($data[0]+1))."/".$year;
    }
}
