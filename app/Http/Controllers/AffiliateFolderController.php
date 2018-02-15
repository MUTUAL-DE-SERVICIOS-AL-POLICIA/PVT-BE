<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\AffiliateFolder;

class AffiliateFolderController extends Controller
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
        $folder = new AffiliateFolder;
        $folder->affiliate_id = $request->affiliate_id;
        $folder->procedure_modality_id = $request->procedure_modality_id;
        $folder->code_file = $request->code_file;
        $folder->folder_number = $request->folder_number;
        $folder->save();
        return back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function printCertification($id){                      
//       $retirement_fund = RetirementFund::find($id);
//       $header = "MUTIAL DE SERVICIOS AL POLIC&Iacute;A \"MUSERPOL\" DIRECCI&Oacute;N DE BENEFICIOS ECON&Oacute;MICOS UNIDAD DE OTORGACI&Oacute;N DE FONDO DE RETIRO POLICIAL, ".strtoupper($retirement_fund->procedure_modality->name);
//       $title = "REQUISITOS DEL BENEFICIO DE ".strtoupper($retirement_fund->procedure_modality->name)." – CUMPLIMIENTO DE SUS FUNCIONES N°";
//       $number = "1";     
//       $username = Auth::user()->username."-Recepcion";      
//       $date=$this->getStringDate($retirement_fund->reception_date);//'6 de Febrero de 2018 - 10:10:48';       
//       $applicant = RetFunBeneficiary::where('type','S')->where('retirement_fund_id',$retirement_fund->id)->first();
//       $submitted_documents = RetFunSubmittedDocument::where('retirement_fund_id',$retirement_fund->id)->get();  
//        //return view('ret_fun.print.reception', compact('title','usuario','fec_emi','name','ci','expedido'));
//
//       // $pdf = view('print_global.reception', compact('title','usuario','fec_emi','name','ci','expedido'));       
       return \PDF::loadView('ret_fun.print.reception',compact('title','username','date','applicant','submitted_documents','header','number'))->stream('recepcion.pdf');
    }
    
}
