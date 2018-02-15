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
use Muserpol\Models\RetirementFund\AddressRetFunBeneficiary;
use Muserpol\Models\RetirementFund\RetFunAdvisor;
use Auth;
use Validator;
use Muserpol\Models\Address;
use Muserpol\Models\Spouse;
use Muserpol\Models\RetirementFund\RetFunLegalGuardian;
use Muserpol\Models\RetirementFund\RetFunAdvisorBeneficiary;
use Muserpol\Models\RetirementFund\RetFunBeneficiaryLegalGuardian;
use DateTime;
use Muserpol\User;
use Carbon\Carbon;

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
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
       $title = "REQUISITOS DEL BENEFICIO FONDO DE RETIRO – ".strtoupper($retirement_fund->procedure_modality->name);
       $number = "1";
       $username = Auth::user()->username."-Recepcion";
       $date=$this->getStringDate($retirement_fund->reception_date);//'6 de Febrero de 2018 - 10:10:48';       
       $applicant = RetFunBeneficiary::where('type','S')->where('retirement_fund_id',$retirement_fund->id)->first();
       $modality = $retirement_fund->procedure_modality->name;
       $submitted_documents = RetFunSubmittedDocument::where('retirement_fund_id',$retirement_fund->id)->get();  
        //return view('ret_fun.print.reception', compact('title','usuario','fec_emi','name','ci','expedido'));

       // $pdf = view('print_global.reception', compact('title','usuario','fec_emi','name','ci','expedido'));       
    //    return view('ret_fun.print.reception',compact('title','institution', 'direction', 'unit','username','date','applicant','submitted_documents','header','number'));
       return \PDF::loadView('ret_fun.print.reception',compact('title', 'institution', 'direction','unit','username','date','modality','applicant','submitted_documents','header','number'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream('recepcion.pdf');
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
    private function getStringDate($string = "1800/01/01"){        
        setlocale(LC_TIME, 'es_ES.utf8');        
        $date = DateTime::createFromFormat("Y-m-d", $string);
        if($date)
            return strftime("%d de %B de %Y",$date->getTimestamp());
        else 
            return "sin fecha";
        
    }
}
