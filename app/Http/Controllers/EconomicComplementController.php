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
use Muserpol\Models\EconomicComplement\EcoComLegalGuardian;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Address;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\ProcedureState;
use Muserpol\Models\PensionEntity;
use Muserpol\User;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\EconomicComplement\EcoComProcessSubmittedDocument;
use Muserpol\Models\Degree;
use Muserpol\Models\Category;
use Log;
use Muserpol\Models\AffiliateState;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Muserpol\Models\EconomicComplement\EcoComType;

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
    public function create($affiliate_id, $eco_com_procedure_id)
    {
        $affiliate = Affiliate::with(['pension_entity'])->find($affiliate_id);
        $has_economic_complement = $affiliate->hasEconomicComplementWithProcedure($eco_com_procedure_id);
        if($has_economic_complement){
            return redirect()->action('EconomicComplementController@show', ['id' => $affiliate->economic_complements()->where('eco_com_procedure_id',$eco_com_procedure_id)->first()->id]);
        }
        $cities = City::all();
        $eco_com_beneficiary = new EcoComBeneficiary();
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
        $last_eco_com = $affiliate->economic_complements()->orderByDesc('id')->get()->first();
        if($last_eco_com){
            $last_eco_com->procedure_modality_id = $last_eco_com->eco_com_modality->eco_com_type_id;
        }
        $modalities = EcoComType::all();
        $pension_entities = PensionEntity::all();
        $data = [
            // 'eco_com_process' => $eco_com_process,
            'affiliate' => $affiliate,
            'cities' => $cities,
            'eco_com_beneficiary' => $eco_com_beneficiary,
            'requirements' => $requirements,
            'user' => $user,
            'last_eco_com' => $last_eco_com,
            'eco_com_procedure_id' => $eco_com_procedure_id,
            'modalities' => $modalities,
            'pension_entities' => $pension_entities,
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
        if(!EcoComProcedure::find($request->eco_com_procedure_id)){
            abort(500,"ERROR");
        }
        $eco_com_process = EcoComProcess::find($request->eco_com_process_id);
        $has_economic_complement = $eco_com_process->hasEconomicComplementWithProcedure($request->eco_com_procedure_id);
        if($has_economic_complement){
            return redirect()->action('EconomicComplementController@show', ['id' => $eco_com_process->economic_complements()->where('eco_com_procedure_id',$request->eco_com_procedure_id)->first()->id]);
        }

        $affiliate = $eco_com_process->affiliate;
        $economic_complement = new EconomicComplement();
        $economic_complement->user_id = Auth::user()->id;
        $economic_complement->eco_com_process_id = $request->eco_com_process_id;
        $economic_complement->eco_com_state_id = 1;
        /**
         * !! TODO eco com modality id
         */
        $economic_complement->eco_com_modality_id = 1;
        $economic_complement->procedure_state_id = 1;
        $economic_complement->eco_com_procedure_id = $request->eco_com_procedure_id;
        $economic_complement->workflow_id = 1;
        $economic_complement->wf_state_current_id = 1;
        $economic_complement->city_id = $request->city_id;
        $economic_complement->degree_id = $affiliate->degree->id;
        $economic_complement->category_id = $affiliate->category->id;
        $economic_complement->base_wage_id = 2;
        $economic_complement->complementary_factor_id = 2;
        $economic_complement->code = Util::getLastCodeEconomicComplement($request->eco_com_procedure_id);
        $economic_complement->reception_date = now();
        $economic_complement->inbox_state = true;
        $economic_complement->inbox_state = true;
        if($affiliate->pension_entity_id == 5){
            $economic_complement->sub_total_rent = Util::parseMoney($request->sub_total_rent);
            $economic_complement->reimbursement = Util::parseMoney($request->reimbursement);
            $economic_complement->dignity_pension = Util::parseMoney($request->dignity_pension);
            $economic_complement->aps_total_disability = Util::parseMoney($request->aps_total_disability);
            $economic_complement->aps_total_fsa= null;
            $economic_complement->aps_total_cc= null;
            $economic_complement->aps_total_fs= null;

        }else{
            $economic_complement->aps_total_fsa = Util::parseMoney($request->aps_total_fsa);
            $economic_complement->aps_total_cc = Util::parseMoney($request->aps_total_cc);
            $economic_complement->aps_total_fs = Util::parseMoney($request->aps_total_fs);
            $economic_complement->aps_total_disability = Util::parseMoney($request->aps_total_disability);
            $economic_complement->sub_total_rent = null;
            $economic_complement->reimbursement = null;
            $economic_complement->dignity_pension = null;
        }
        $economic_complement->save();
        if($request->has_legal_guardian == 'on'){
            $legal_guardian = new EcoComLegalGuardian();
            $legal_guardian->economic_complement_id = $economic_complement->id;
            $legal_guardian->city_identity_card_id = $request->legal_guardian_city_identity_card;
            $legal_guardian->identity_card = $request->legal_guardian_identity_card;
            $legal_guardian->last_name = $request->legal_guardian_last_name;
            $legal_guardian->mothers_last_name = $request->legal_guardian_mothers_last_name;
            $legal_guardian->first_name = $request->legal_guardian_first_name;
            $legal_guardian->second_name = $request->legal_guardian_second_name;
            $legal_guardian->surname_husband = $request->legal_guardian_surname_husband;
            $legal_guardian->phone_number = $request->legal_guardian_phone_number;
            $legal_guardian->cell_phone_number = $request->legal_guardian_cell_phone_number;
            $legal_guardian->save();
        }

        $eco_com_beneficiary = $eco_com_process->eco_com_beneficiary;
        $eco_com_beneficiary->phone_number = trim(implode(",", $request->beneficiary_phone_number));
        $eco_com_beneficiary->cell_phone_number = trim(implode(",", $request->beneficiary_cell_phone_number));
        /**
         * Update or create address
         */
        if (sizeOf($eco_com_beneficiary->address) > 0) {
            $address_id = $eco_com_beneficiary->address()->first()->id;
            $address = Address::find($address_id);
            if ($request->beneficiary_zone || $request->beneficiary_street || $request->beneficiary_number_address) {
                $address->city_address_id = $request->beneficiary_city_address_id ?? 1;
                $address->zone = $request->beneficiary_zone;
                $address->street = $request->beneficiary_street;
                $address->number_address = $request->beneficiary_number_address;
                $address->save();
                if ($eco_com_process->procedure_modality_id == 24) {
                    $update_affiliate = Affiliate::find($eco_com_process->affiliate_id);
                    if ($update_affiliate->address->contains($address->id)) { } else {
                        $update_affiliate->address()->save($address);
                    }
                }
            } else {
                if ($eco_com_process->procedure_modality_id == 24) {
                    $update_affiliate = Affiliate::find($eco_com_process->affiliate_id);
                    $update_affiliate->address()->detach($address->id);
                }
                $eco_com_beneficiary->address()->detach($address->id);
                $address->delete();
            }
        } else {
            if ($request->beneficiary_city_address_id) {
                $address = new Address();
                $address->city_address_id = $request->beneficiary_city_address_id ?? 1;
                $address->zone = $request->beneficiary_zone;
                $address->street = $request->beneficiary_street;
                $address->number_address = $request->beneficiary_number_address;
                $address->save();
                $eco_com_beneficiary->address()->save($address);
                if ($eco_com_process->procedure_modality_id == 24) {
                    $update_affiliate = Affiliate::find($eco_com_process->affiliate_id);
                    $update_affiliate->address()->save($address);
                }
            }
        }
        $eco_com_beneficiary->save();
        return redirect()->action('EconomicComplementController@show', ['id' => $economic_complement->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $economic_complement = EconomicComplement::with(['wf_state:id,name', 'workflow:id,name', 'eco_com_modality:id,name'])->findOrFail($id);

        $eco_com_process = $economic_complement->eco_com_process()->with(['procedure_modality:id,name'])->first();
        $affiliate = $eco_com_process->affiliate;
        $degrees = Degree::all();
        $categories = Category::all();

        $states = ProcedureState::all();
        $pension_entities = PensionEntity::all();

        /*
        * for affiliate info
        */
        $cities = City::get();
        $cities_pluck = City::all()->pluck('first_shortened', 'id');
        $birth_cities = City::all()->pluck('name', 'id');
        $is_editable = false;
        $affiliate->phone_number = explode(',', $affiliate->phone_number);
        $affiliate->cell_phone_number = explode(',', $affiliate->cell_phone_number);
        if (!sizeOf($affiliate->address) > 0) {
            $affiliate->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
        }
        //police info
        $affiliate_states = AffiliateState::all()->pluck('name', 'id');
       
        /**
         ** for beneficiary info
         */
        $eco_com_beneficiary = $eco_com_process->eco_com_beneficiary;
        $eco_com_beneficiary->phone_number = explode(',', $eco_com_beneficiary->phone_number);
        $eco_com_beneficiary->cell_phone_number = explode(',', $eco_com_beneficiary->cell_phone_number);
        if (!sizeOf($eco_com_beneficiary->address) > 0) {
            $eco_com_beneficiary->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
        }

        $user = User::find(Auth::user()->id);
        $procedure_types = ProcedureType::where('module_id', 2)->get();
        $procedure_requirements = ProcedureRequirement::select('procedure_requirements.id', 'procedure_documents.name as document', 'number', 'procedure_modality_id as modality_id')
            ->leftJoin('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
            ->orderBy('procedure_requirements.procedure_modality_id', 'ASC')
            ->orderBy('procedure_requirements.number', 'ASC')
            ->get();
        $procedure_modalities = ProcedureModality::where('procedure_type_id', '<=', '8')->select('id', 'name', 'procedure_type_id')->get();
        // $observation_types = ObservationType::where('module_id',3)->get();
        $submitted = EcoComProcessSubmittedDocument::select('eco_com_process_submitted_documents.id', 'procedure_requirements.number', 'eco_com_process_submitted_documents.procedure_requirement_id', 'eco_com_process_submitted_documents.comment', 'eco_com_process_submitted_documents.is_valid')
            ->leftJoin('procedure_requirements', 'eco_com_process_submitted_documents.procedure_requirement_id', '=', 'procedure_requirements.id')
            ->orderby('procedure_requirements.number', 'ASC')
            ->where('eco_com_process_submitted_documents.eco_com_process_id', $id);

        $data = [
            'economic_complement' => $economic_complement,
            'eco_com_process' => $eco_com_process,
            'affiliate' => $affiliate,
            'states' => $states,
            'pension_entities' => $pension_entities,
            'cities' => $cities,
            'cities_pluck' => $cities_pluck,
            'birth_cities' => $birth_cities,
            'is_editable' => $is_editable,
            'eco_com_beneficiary' => $eco_com_beneficiary,

            'degrees' => $degrees,
            'categories' => $categories,
            'affiliate_states' => $affiliate_states,
            
            'user' => $user,
            'procedure_modalities' => $procedure_modalities,
            'requirements' => $procedure_requirements,
            'procedure_types' => $procedure_types,
            'submitted' =>  $submitted->pluck('eco_com_process_submitted_documents.procedure_requirement_id', 'procedure_requirements.number'),
            'submit_documents' => $submitted->get(),
        ];
        return view('eco_com.show',$data);
    }
    public function updateAffiliatePoliceEcoCom(Request $request)
    {
        $affiliate = Affiliate::where('id','=', $request->id)->first();
        // $this->authorize('update', $affiliate);
        $affiliate->date_entry = Util::verifyMonthYearDate($request->date_entry) ? Util::parseMonthYearDate($request->date_entry) : $request->date_entry;
        $affiliate->item = $request->item;
        $affiliate->category_id = $request->category_id;
        $service_year = $request->service_years;
        $service_month = $request->service_months;
        Log::info($service_year);
        Log::info($service_month);
        if ($service_year > 0 || $service_month > 0 ) {
            if ($service_month > 0) {
                $service_year++;
            }
            $category = Category::where('from','<=',$service_year)
            ->where('to','>=',$service_year)
            ->first();
            if ($category) {
                $affiliate->category_id = $category->id;
                $affiliate->service_years = $request->service_years;
                $affiliate->service_months = $request->service_months;
            }
        }
        Log::info($request->all());
        $affiliate->degree_id = $request->degree_id;
        $affiliate->pension_entity_id = $request->pension_entity_id;
        $affiliate->save();
        $economic_complement = EconomicComplement::find($request->eco_com_id);
        $economic_complement->degree_id = $request->degree_id;
        $economic_complement->category_id = $affiliate->category_id;
        $economic_complement->save();
        Log::info('update affiliate and eco com');
        return array('affiliate' => $affiliate);
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

    public function updateInformation(Request $request)
    {
        $economic_complement = EconomicComplement::findOrFail($request->id);

        if($request->procedure_state_id == 3){
            $economic_complement->code = $economic_complement->code.'A';
            $economic_complement->save();
            $eco_com_process = $economic_complement->eco_com_process;
            $economic_complement->delete();
            return response()->json([
                'message' => 'deleted',
            ], 204);
        }
        $economic_complement->degree_id = $request->degree_id;
        $economic_complement->category_id = $request->category_id;
        $economic_complement->city_id = $request->city_id;
        $economic_complement->procedure_state_id = $request->procedure_state_id;
        $economic_complement->reception_date = $request->reception_date;
        $economic_complement->save();
        /**
         * update affiliate info
         */
        $affiliate = $economic_complement->eco_com_process->affiliate;
        $affiliate->degree_id = $request->degree_id;
        $affiliate->category_id = $request->category_id;
        $affiliate->pension_entity_id = $request->pension_entity_id;
        $affiliate->save();
        return $economic_complement;
    }
    public function firstStep()
    {
        $cities = City::all();
        $data = [
            'cities' => $cities,
        ];
        return view('eco_com.first_step', $data);
    }

    public function getReceptionType(Request $request)
    {
        Log::info($request->all());
        $reception_type_id = 1;
        if(!$request->modality_id){
            return $reception_type_id;
        }
        if ($request->last_eco_com_id) {
            $eco_com = EconomicComplement::find($request->last_eco_com_id);
            if ($eco_com->eco_com_modality->eco_com_type_id == $request->modality_id ) {
                $reception_type_id = 2;
            }
        }
        return $reception_type_id;
    }
    public function getTypeBeneficiary(Request $request)
    {
        if(!$request->affiliate_id){
            return null;
        }
        $affiliate = Affiliate::find($request->affiliate_id);
        if($request->last_eco_com_id){
            Log::info("entre");
            $eco_com = EconomicComplement::find($request->last_eco_com_id);
            if($eco_com->eco_com_modality->eco_com_type_id == $request->modality_id){
                $eco_com_beneficiary = $eco_com->eco_com_beneficiary()->with('address')->first();
                if (!sizeOf($eco_com_beneficiary->address) > 0) {
                    $eco_com_beneficiary->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
                }
                return $eco_com_beneficiary;
            }
        }
        switch ($request->modality_id) {
            case 1:
                return $affiliate->with('address')->first();
                break;
            case 2:
                $spouse = $affiliate->spouse->first();
                $spouse->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
                if($spouse) {
                    return $spouse;
                }
                return new Spouse();
                break;
            default:
                return new EcoComBeneficiary();
                break;
        }
        return null;
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
