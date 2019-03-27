<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EcoComProcess;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Illuminate\Support\Facades\Auth;
use Muserpol\Helpers\Util;
use Muserpol\Models\City;
use Muserpol\Models\Spouse;
use Muserpol\Models\ProcedureRequirement;

class EconomicComplementController extends Controller
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
    public function create($eco_com_process_id, $eco_com_procedure_id)
    {
        $eco_com_process = EcoComProcess::with(['procedure_modality'])->find($eco_com_process_id);
        $has_economic_complement = $eco_com_process->hasEconomicComplementWithProcedure($eco_com_procedure_id);
        if($has_economic_complement){
            return redirect()->action('EconomicComplementController@show', ['id' => $eco_com_process->economic_complements()->where('eco_com_procedure_id',$eco_com_procedure_id)->first()->id]);
        }
        $affiliate = $eco_com_process->affiliate()->with(['pension_entity'])->first();
        $cities = City::all();
        $eco_com_beneficiary = $eco_com_process->eco_com_beneficiary;
        $eco_com_beneficiary->phone_number = explode(',', $eco_com_beneficiary->phone_number);
        $eco_com_beneficiary->cell_phone_number = explode(',', $eco_com_beneficiary->cell_phone_number);
        if (!sizeOf($eco_com_beneficiary->address) > 0) {
            $eco_com_beneficiary->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
        }
        $requirements = ProcedureRequirement::select('procedure_requirements.id', 'procedure_documents.name as document', 'number', 'procedure_modality_id as modality_id')
        ->leftJoin('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
        ->orderBy('procedure_requirements.procedure_modality_id', 'ASC')
        ->orderBy('procedure_requirements.number', 'ASC')
        ->get();
        $user = Auth::user();
        $economic_complement = new EconomicComplement();

        $data = [
            'eco_com_process' => $eco_com_process,
            'affiliate' => $affiliate,
            'cities' => $cities,
            'eco_com_beneficiary' => $eco_com_beneficiary,
            'requirements' => $requirements,
            'user' => $user,
            'economic_complement' => $economic_complement,
        ];

        return view('eco_com.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $eco_com_process = EcoComProcess::find($eco_com_process_id);
        $has_economic_complement = $eco_com_process->hasEconomicComplementWithProcedure($eco_com_procedure_id);
        if($has_economic_complement){
            return redirect()->action('EconomicComplementController@show', ['id' => $eco_com_process->economic_complements()->where('eco_com_procedure_id',$eco_com_procedure_id)->first()->id]);
        }
        return view('eco_com.create');

        $affiliate = $eco_com_process->affiliate;
        $economic_complement = new EconomicComplement();
        $economic_complement->user_id = Auth::user()->id;
        $economic_complement->eco_com_process_id = $eco_com_process_id;
        $economic_complement->eco_com_state_id = 1;
        $economic_complement->procedure_state_id = 1;
        $economic_complement->eco_com_procedure_id = $eco_com_procedure_id;
        $economic_complement->workflow_id = 1;
        $economic_complement->wf_state_current_id = 1;
        $economic_complement->city_id = 1;
        $economic_complement->degree_id = $affiliate->degree->id;
        $economic_complement->category_id = $affiliate->category->id;
        $economic_complement->base_wage_id = 2;
        $economic_complement->complementary_factor_id = 2;
        $economic_complement->code = Util::getLastCodeEconomicComplement($eco_com_procedure_id);
        $economic_complement->reception_date = now();
        $economic_complement->inbox_state = true;
        $economic_complement->save();
        dd($economic_complement);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $economic_complement = EconomicComplement::findOrFail($id);
        $data = [
            'economic_complement' => $economic_complement
        ];
        return view('eco_com.show',$data);
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
}
