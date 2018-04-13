<?php
namespace Muserpol\Http\Controllers;
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
use Auth;
use Log;
use Validator;
use Muserpol\Models\Address;
use Muserpol\Models\Spouse;
use Muserpol\Models\RetirementFund\RetFunLegalGuardian;
use Muserpol\Models\RetirementFund\RetFunAdvisorBeneficiary;
use Muserpol\Models\RetirementFund\RetFunLegalGuardianBeneficiary;
use DateTime;
use Muserpol\User;
use Carbon\Carbon;
use Yajra\Datatables\DataTables;
use Muserpol\Models\RetirementFund\RetFunIncrement;
use Session;
use Muserpol\Helpers\Util;
use Illuminate\Auth\EloquentUserProvider;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Illuminate\Contracts\Database\ModelIdentifier;
use Muserpol\Models\DiscountType;
class RetirementFundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {                
        return view('ret_fun.index');
       
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
        
        $requirements = ProcedureRequirement::select('id')->get();        
        
        $procedure = \Muserpol\Models\RetirementFund\RetFunProcedure::where('is_enabled',true)->select('id')->first();
        
        
        $validator = Validator::make($request->all(), [
            //'applicant_first_name' => 'required|max:5',            
        ]);                
        //custom this validator
        $validator->after(function($validator){
            if(false)                
                $validator->errors()->add('Modalidad', 'el campo modalidad no puede ser tramitada este mes');            
        });        
        if($validator->fails()){
            return $validator->errors();            
        }
        
        
        $ret_fund  = RetirementFund::select('id','code')->orderby('id','desc')->first();
        if(!isset($ret_fund->id))
        $code = Util::getNextCode ("");
        else        
        $code = Util::getNextCode ($ret_fund->code);
        $retirement_fund = new RetirementFund();
        $this->authorize('create', $retirement_fund);
        $retirement_fund->user_id = Auth::user()->id;
        $retirement_fund->affiliate_id = $request->affiliate_id;
        $retirement_fund->procedure_modality_id = $request->ret_fun_modality;
        $retirement_fund->ret_fun_procedure_id = $procedure->id;
        $retirement_fund->city_start_id = Auth::user()->city_id;
        $retirement_fund->city_end_id = $request->city_end_id;
        $retirement_fund->reception_date = Carbon::now();
        $retirement_fund->code = $code;
        $retirement_fund->workflow_id = 4;
        $retirement_fund->wf_state_current_id = 1;
        //$retirement_fund->type = "Pago"; default value
        $retirement_fund->subtotal_ret_fun = 0;
        $retirement_fund->total_ret_fun = 0;
        $retirement_fund->reception_date = date('Y-m-d');
        $retirement_fund->save();
                
        //$cite = RetFunIncrement::getCite(Auth::user()->id,Session::get('rol_id'),$retirement_fund->id);
        
        foreach ($requirements  as  $requirement)
        {
            if($request->input('document'.$requirement->id) == 'checked')
            {
                $submit = new RetFunSubmittedDocument();
                $submit->retirement_fund_id = $retirement_fund->id;
                $submit->procedure_requirement_id = $requirement->id;
                $submit->reception_date = date('Y-m-d');
                $submit->comment = $request->input('comment'.$requirement->id);                
                $submit->save();
            }                
        }
        $account_type = $request->input('accountType');    
        
        
        $beneficiary = new RetFunBeneficiary();
        $beneficiary->retirement_fund_id = $retirement_fund->id;
        $beneficiary->city_identity_card_id = $request->applicant_city_identity_card;
        $beneficiary->kinship_id = $request->applicant_kinship;
        $beneficiary->identity_card = $request->applicant_identity_card;
        $beneficiary->last_name = $request->applicant_last_name;
        $beneficiary->mothers_last_name = $request->applicant_mothers_last_name;
        $beneficiary->first_name = $request->applicant_first_name;
        $beneficiary->second_name = $request->applicant_second_name;
        $beneficiary->surname_husband = $request->applicant_surname_husband;        
        $beneficiary->gender = "M";        
        $beneficiary->phone_number = trim(implode(",", $request->applicant_phone_number));
        $beneficiary->cell_phone_number = trim(implode(",", $request->applicant_phone_number));        
        $beneficiary->type = "S";
        $beneficiary->save();
        if($account_type == '2')
        {
            $advisor = new RetFunAdvisor();
            //$advisor->retirement_fund_id = $retirement_fund->id;
            $advisor->city_identity_card_id = $request->applicant_city_identity_card;
            $advisor->kinship_id = null;
            $advisor->identity_card = $request->applicant_identity_card;
            $advisor->last_name = $request->applicant_last_name;
            $advisor->mothers_last_name = $request->applicant_mothers_last_name;
            $advisor->first_name = $request->applicant_first_name;
            $advisor->second_name = $request->applicant_second_name;
            $advisor->surname_husband = $request->applicant_surname_husband;        
            $advisor->gender = "M";                    
            $advisor->phone_number = $request->applicant_phone_number;
            $advisor->cell_phone_number = $request->applicant_cell_phone_number;        
            $advisor->name_court = $request->advisor_name_court;            
            $advisor->resolution_number = $request->advisor_resolution_number;
            $advisor->resolution_date = $request->advisor_resolution_date;
            $advisor->type = "Natural";
            $advisor->save();
            
            $advisor_beneficiary = new RetFunAdvisorBeneficiary();
            $advisor_beneficiary->ret_fun_beneficiary_id = $beneficiary->id;
            $advisor_beneficiary->ret_fun_advisor_id = $advisor->id;
            $advisor_beneficiary->save();
        }
        
        if($account_type == '3')
        {
            $legal_guardian = new RetFunLegalGuardian();
            $legal_guardian->retirement_fund_id = $retirement_fund->id;
            $legal_guardian->city_identity_card_id = $request->applicant_city_identity_card;            
            $legal_guardian->identity_card = $request->applicant_identity_card  ;
            $legal_guardian->last_name = $request->applicant_last_name;
            $legal_guardian->mothers_last_name = $request->applicant_mothers_last_name;
            $legal_guardian->first_name = $request->applicant_first_name;
            $legal_guardian->second_name = $request->applicant_second_name;
            $legal_guardian->surname_husband = $request->applicant_surname_husband;        
            //$legal_guardian->gender = "M";                    
            $legal_guardian->phone_number = $request->applicant_phone_number;
            $legal_guardian->cell_phone_number = $request->applicant_cell_phone_number;        
            $legal_guardian->number_authority = $request->legal_guardian_number_authority;            
            $legal_guardian->notary_of_public_faith = $request->legal_guardian_notary_of_public_faith;
            $legal_guardian->notary = $request->legal_guardian_notary;
            $legal_guardian->save();
            
            $beneficiary_legal_guardian = new RetFunLegalGuardianBeneficiary();
            $beneficiary_legal_guardian->ret_fun_beneficiary_id = $beneficiary->id;
            $beneficiary_legal_guardian->ret_fun_legal_guardian_id = $legal_guardian->id;
            $beneficiary_legal_guardian->save();
            //$beneficiary->type = "N";            
        }
        
        
        $address = new Address();
        $address->city_address_id = 1;
        $address->zone = $request->beneficiary_zone;
        $address->street = $request->beneficiary_street;
        $address->number_address = $request->beneficiary_number_address;
        $address->save();
        
        $address_rel = new RetFunAddressBeneficiary();
        $address_rel->ret_fun_beneficiary_id = $beneficiary->id;
        $address_rel->address_id = $address->id;
        $address_rel->save();
        
        $first_name = $request->beneficiary_first_name;
        $second_name = $request->beneficiary_second_name;
        $last_name = $request->beneficiary_last_name;
        $mothers_last_name = $request->beneficiary_mothers_last_name;
        $surname_husband = $request->surname_husband;
        $identity_card = $request->beneficiary_identity_card;
        $city_id = $request-> beneficiary_city_identity_card;
        $birth_date = $request->beneficiary_birth_date;
        $kinship = $request->beneficiary_kinship;
        for($i=0;$i<sizeof($first_name);$i++){
            if($first_name[$i] != "" && $last_name[$i] != ""){
                $beneficiary = new RetFunBeneficiary();
                $beneficiary->retirement_fund_id = $retirement_fund->id;
                $beneficiary->city_identity_card_id = $city_id[$i];
                $beneficiary->kinship_id = $kinship[$i];
                $beneficiary->identity_card = $identity_card[$i];
                $beneficiary->last_name = $last_name[$i];
                $beneficiary->mothers_last_name = $mothers_last_name[$i];
                $beneficiary->first_name = $first_name[$i];
                $beneficiary->second_name = $second_name[$i];
                $beneficiary->surname_husband = $surname_husband[$i];                
                $beneficiary->birth_date = $birth_date[$i];
                $beneficiary->gender = "M";
                //$beneficiary->civil_status = $request->
                //$beneficiary->phone_number = $request->;
                //$beneficiary->cell_phone_number = $request->;               
                $beneficiary->type = "N";
                $beneficiary->save();                
            }        
        }                        
        $data = [
        ];
        
        return redirect('ret_fun/'.$retirement_fund->id);
        
    }
    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return \Illuminate\Http\Response
     */
    //public function show(RetirementFund $retirementFund)
    public function show($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $this->authorize('view', $retirement_fund);
        
        $affiliate = Affiliate::find($retirement_fund->affiliate_id);
        
        $beneficiaries = RetFunBeneficiary::where('retirement_fund_id',$retirement_fund->id)->orderBy('type','desc')->get();        
        
        $applicant = RetFunBeneficiary::where('type','S')->where('retirement_fund_id',$retirement_fund->id)->first();
        
        $beneficiary_avdisor = RetFunAdvisorBeneficiary::where('ret_fun_beneficiary_id',$applicant->id)->first();
        
        if(isset($beneficiary_avdisor->id))
            $advisor= RetFunAdvisor::find($beneficiary_avdisor->ret_fun_advisor_id);
        else
            $advisor = new RetFunAdvisor();
        
        $beneficiary_guardian = RetFunLegalGuardianBeneficiary::where('ret_fun_beneficiary_id',$applicant->id)->first();
        
        if(isset($beneficiary_guardian->id))
            $guardian = RetFunLegalGuardian::find($beneficiary_guardian->ret_fun_legal_guardian_id);
        else 
            $guardian = new RetFunLegalGuardian();                
        
        $procedures_modalities_ids = ProcedureModality::join('procedure_types','procedure_types.id','=','procedure_modalities.procedure_type_id')->where('procedure_types.module_id','=',3)->get()->pluck('id'); //3 por el module 3 de fondo de retiro
        $procedures_modalities = ProcedureModality::whereIn('id',$procedures_modalities_ids)->get();
        $documents = RetFunSubmittedDocument::where('retirement_fund_id',$id)->orderBy('procedure_requirement_id','ASC')->get();
        $cities = City::get();
        $kinships = Kinship::get();        
        
        $cities_pluck = City::all()->pluck('first_shortened', 'id');
        $birth_cities = City::all()->pluck('name', 'id');
        $data = [
            'retirement_fund' => $retirement_fund,
            'affiliate' =>  $affiliate,
            'beneficiaries' =>  $beneficiaries,
            'applicant' => $applicant,
            'advisor'  =>  $advisor,
            'legal_guardian'    =>  $guardian,
            'procedure_modalities' => $procedures_modalities,     
            'documents' => $documents,            
            'cities'    =>  $cities,
            'kinships'   =>  $kinships,
            'cities_pluck' => $cities_pluck,
            'birth_cities' => $birth_cities
        ];
        
        return view('ret_fun.show',$data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return \Illuminate\Http\Response
     */
    public function edit(RetirementFund $retirementFund)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RetirementFund $retirementFund)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\RetirementFund  $retirementFund
     * @return \Illuminate\Http\Response
     */   
    public function destroy(RetirementFund $retirementFund)
    {
        //
    }
    
    public function getAllRetFun(Request $request)
    {
        
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';          
        $last_name = strtoupper($request->last_name) ?? '';
        $first_name = strtoupper($request->first_name) ?? '';
        $code = $request->code ?? '';
        $modality = strtoupper($request->modality) ?? '';
        
        $total = RetirementFund::select('retirement_funds.id')
                                ->leftJoin('affiliates','retirement_funds.id','=','affiliates.id')
                                ->leftJoin('procedure_modalities','retirement_funds.procedure_modality_id','=','procedure_modalities.id')
                                ->leftJoin('workflows','retirement_funds.workflow_id','=','workflows.id')                               
                                ->where('retirement_funds.code','LIKE',$code.'%')
                                //->where('procedure_modalities.name','LIKE',$modality.'%')
                                ->where('affiliates.first_name','LIKE',$first_name.'%')
                                ->where('affiliates.last_name','LIKE',$last_name.'%')                                
                                ->count();
        
                                
        $ret_funds = RetirementFund::select('retirement_funds.id','affiliates.first_name as first_name','affiliates.last_name as last_name','procedure_modalities.name as modality','workflows.name as workflow','retirement_funds.code','retirement_funds.reception_date','retirement_funds.total')
                                ->leftJoin('affiliates','retirement_funds.id','=','affiliates.id')
                                ->leftJoin('procedure_modalities','retirement_funds.procedure_modality_id','=','procedure_modalities.id')
                                ->leftJoin('workflows','retirement_funds.workflow_id','=','workflows.id')                               
                                ->where('affiliates.first_name','LIKE',$first_name.'%')
                                //->where('procedure_modalities.name','LIKE',$modality.'%')
                                ->where('affiliates.last_name','LIKE',$last_name.'%')
                                ->where('retirement_funds.code','LIKE',$code.'%')
                                ->skip($offset)
                                ->take($limit)
                                ->orderBy($sort,$order)
                                ->get();
        
        
        return response()->json(['ret_funds' => $ret_funds->toArray(),'total'=>$total]);
    }
    
    public function generateProcedure(Affiliate $affiliate){  
        
        $this->authorize('create',RetirementFund::class);
        $user = Auth::User();
        $affiliate = Affiliate::select('affiliates.id','identity_card', 'city_identity_card_id','registration','first_name','second_name','last_name','mothers_last_name', 'surname_husband', 'gender', 'degrees.name as degree','civil_status','affiliate_states.name as affiliate_state','phone_number', 'cell_phone_number')
                                ->leftJoin('degrees','affiliates.id','=','degrees.id')
                                ->leftJoin('affiliate_states','affiliates.affiliate_state_id','=','affiliate_states.id')
                                ->find($affiliate->id);
                 
        $procedure_requirements = ProcedureRequirement::
                                    select('procedure_requirements.id','procedure_documents.name as document','number','procedure_modality_id as modality_id')
                                    ->leftJoin('procedure_documents','procedure_requirements.procedure_document_id','=','procedure_documents.id')
                                    ->orderBy('procedure_requirements.procedure_modality_id','ASC')
                                    ->orderBy('procedure_requirements.number','ASC')
                                    ->get();
        
        $spouse = Spouse::where('affiliate_id',$affiliate->id)->first();
        if(!isset($spouse->id))
            $spouse = new Spouse();
        $modalities = ProcedureModality::where('procedure_type_id','2')->select('id','name')->get();
        
        $kinships = Kinship::get();
        
        $cities = City::get();
        
        $searcher = new SearcherController();
        
        $data = [
            'user' => $user,
            'requirements' => $procedure_requirements,
            'modalities'    => $modalities,
            'affiliate'  => $affiliate,
            'kinships'  =>  $kinships,
            'cities'    =>  $cities,
            'ret'    =>  $cities,
            'spouse' =>  $spouse,
            'searcher'  =>  $searcher,
        ];        
        
        //return $data;
        return view('ret_fun.create',$data);        
    }
    
    public function storeLegalReview(Request $request,$id){
        //return 0;
        $retirement_fund = RetirementFund::find($id);
        $this->authorize('update',new RetFunSubmittedDocument);
        $submited_documents = RetFunSubmittedDocument::where('retirement_fund_id',$id)->orderBy('procedure_requirement_id','ASC')->get();
        foreach ($submited_documents as $document)
        {
            $value= "comment".$document->id."";
            $document->comment = $request->input($value);
            $value= "document".$document->id."";
            if($request->input($value) == '1')
                $document->is_valid = true;
            else 
                $document->is_valid = false;
            $document->save();    
        }
        
        return redirect('ret_fun/'.$retirement_fund->id);
        //return $retirement_fund;
    }
    public function updateBeneficiaries(Request $request){
        
        $this->authorize('update',new RetFunBeneficiary);
        $i = 0;
        $ben = 0;
        foreach ($request->all() as $ben){            
            $beneficiary = RetFunBeneficiary::find($ben['id']);
            $beneficiary->city_identity_card_id = $ben['city_identity_card_id'];
            $beneficiary->kinship_id = $ben['kinship_id'];
            $beneficiary->identity_card = $ben['identity_card'];
            $beneficiary->last_name = $ben['last_name'];
            $beneficiary->mothers_last_name = $ben['mothers_last_name'];
            $beneficiary->first_name = $ben['first_name'];
            $beneficiary->second_name = $ben['second_name'];
            $beneficiary->surname_husband = $ben['surname_husband'];            
            $beneficiary->gender = $ben['gender'];
            $beneficiary->phone_number = $ben['phone_number'];
            $beneficiary->cell_phone_number = $ben['cell_phone_number'];
            $beneficiary->save();
            $i++;                    
        }
        return json_encode(0);
    }
    public function updateInformation(Request $request)
    {
        $retirement_fund = RetirementFund::find($request->id);
        $this->authorize('update', $retirement_fund);
        $retirement_fund->city_end_id = $request->city_end_id;
        $retirement_fund->city_start_id = $request->city_start_id;
        $retirement_fund->reception_date = $request->reception_date;
        $retirement_fund->save();
        $datos = array('retirement_fund' => $retirement_fund, 'procedure_modality'=>$retirement_fund->procedure_modality,'city_start'=>$retirement_fund->city_start,'city_end'=>$retirement_fund->city_end );
        return $datos;
    }
    public function qualification($ret_fun_id)
    {
        $retirement_fund = RetirementFund::find($ret_fun_id);
        $beneficiaries = $retirement_fund->ret_fun_beneficiaries()->orderBy('type', 'desc')->get();
        $affiliate = $retirement_fund->affiliate;
        $dates_contributions = $affiliate->getDatesContributions();
        $dates_availability = $affiliate->getDatesAvailability();
        $dates_item_zero = $affiliate->getDatesItemZero();
        $cities_pluck = City::all()->pluck('first_shortened', 'id');
        $cities = City::get();
        $kinships = Kinship::get();
        $birth_cities = City::all()->pluck('name', 'id');
        $data = [
            'retirement_fund' => $retirement_fund,
            'affiliate' => $affiliate,
            'dates_availability' => $dates_availability,
            'dates_item_zero' => $dates_item_zero,
            'dates_contributions' => $dates_contributions,
            'cities_pluck' => $cities_pluck,
            'birth_cities' => $birth_cities,
            'beneficiaries' => $beneficiaries,
            'cities' => $cities,
            'kinships' => $kinships,
        ];
        return view('ret_fun.qualification', $data);
    }
    public function sumTotalContributions($array, $fromView = false)
    {
        $total = 0;
        foreach ($array as $key => $value) {
            if ($fromView) {
                // $value = json_encode($value);
                $diff = Carbon::parse($value['start'])->diffInMonths(Carbon::parse($value['end'])) + 1;
            }else{
                $diff = Carbon::parse($value->start)->diffInMonths(Carbon::parse($value->end)) + 1;
            }
            if ($diff < 0 ) {
                dd("error");
            }
            $total = $total + $diff;
        }
        return $total;
    }
    public function geDataQualification(Request $request, $id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;

        $total_contributions_backed = $this::sumTotalContributions($affiliate->getContributionsWithType('Servicio'));
        $total_contributions_fronted = $this::sumTotalContributions($request->datesContributions, true);

        $total_item_zero_backed = $this::sumTotalContributions($affiliate->getContributionsWithType('Item 0'));
        $total_item_zero_fronted = $this::sumTotalContributions($request->datesItemZero, true);

        $total_availability_backed = $this::sumTotalContributions($affiliate->getContributionsWithType('Disponibilidad'));
        $total_availability_fronted = $this::sumTotalContributions($request->datesAvailability, true);


        $total_quotes = ($total_contributions_backed ?? 0) + ($total_item_zero_backed ?? 0) - ($total_availability_backed ?? 0);

        if(
            $total_contributions_backed == $total_contributions_fronted &&
            $total_item_zero_backed == $total_item_zero_fronted &&
            $total_availability_backed == $total_availability_fronted
        ){

            $availability = $affiliate->getContributionsWithType('Disponibilidad');
            $contributions = $affiliate->getContributionsWithType('Servicio');

            if (sizeOf($availability) > 0) {
                $start_date_availability = Carbon::parse(end($availability)->start)->subMonth(1)->toDateString();
                $contributions = $affiliate->contributions()
                    ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
                    ->where("contribution_types.name", '=', 'Servicio')
                    ->where('contributions.month_year', '<=', $start_date_availability)
                    ->orderBy('contributions.month_year', 'desc')
                    ->take($number_contributions)
                    ->get();
                $total_base_wage = $contributions->sum('base_wage');
                $total_seniority_bonus = $contributions->sum('seniority_bonus');
                $total_retirement_fund = $contributions->sum('retirement_fund');
                $sub_total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus);
                $total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus) / $number_contributions;
            }else{
                //si no tiene periodos en disponibilidad
                $last_date_contribution = Carbon::parse(end($contributions)->end)->toDateString();
                $contributions = $affiliate->contributions()
                    ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
                    ->where("contribution_types.name", '=', 'Servicio')
                    ->where('contributions.month_year', '<=', $last_date_contribution)
                    ->orderBy('contributions.month_year', 'desc')
                    ->take($number_contributions)
                    ->get();
                $total_base_wage = $contributions->sum('base_wage');
                $total_seniority_bonus = $contributions->sum('seniority_bonus');
                $total_retirement_fund = $contributions->sum('retirement_fund');
                $sub_total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus);
                $total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus) / $number_contributions;
            }
            $data = [
                'total_base_wage' => $total_base_wage,
                'total_seniority_bonus' => $total_seniority_bonus,
                'total_retirement_fund' => $total_retirement_fund,
                'total_average_salary_quotable' => $total_average_salary_quotable,
                'total_quotes' => $total_quotes,
            ];
            return $data;
        }else{
            return response("error",500);
        }
    }
    public function getDataQualificationCertification(DataTables $datatables, $retirement_fund_id)
    {
        $retirement_fund = RetirementFund::find($retirement_fund_id);
        $affiliate = $retirement_fund->affiliate;
        $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;
        $availability = $affiliate->getContributionsWithType('Disponibilidad');
        if (sizeOf($availability) > 0) {
            $start_date_availability = Carbon::parse(end($availability)->start)->subMonth(1)->toDateString();
            $contributions = $affiliate->contributions()
                ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
                ->where("contribution_types.name", '=', 'Servicio')
                ->where('contributions.month_year', '<=', $start_date_availability)
                ->orderBy('contributions.month_year', 'desc')
                ->take($number_contributions)
                ->get();
            return $datatables->of($contributions)
                ->editColumn('month_year', function ($contribution) {
                    return Util::getDateFormat($contribution->month_year);
                })
                ->editColumn('base_wage', function ($contribution) {
                    return Util::formatMoney($contribution->base_wage);
                })
                ->editColumn('seniority_bonus', function ($contribution) {
                    return Util::formatMoney($contribution->seniority_bonus);
                })
                ->editColumn('total', function ($contribution) {
                    return Util::formatMoney($contribution->total);
                })
                ->editColumn('retirement_fund', function ($contribution) {
                    return Util::formatMoney($contribution->retirement_fund);
                })
                ->editColumn('quotable_salary', function ($contribution) {
                    $quotable_salary = $contribution->seniority_bonus + $contribution->base_wage;
                    return Util::formatMoney($quotable_salary);
                })
                ->addIndexColumn()
                ->make(true);

        } else {

        }

    }
    public function qualificationCertification($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;

        if($retirement_fund){
            $affiliate = $retirement_fund->affiliate;
            $availability = $affiliate->getContributionsWithType('Disponibilidad');
            $contributions = $affiliate->getContributionsWithType('Servicio');

            if (sizeOf($availability) > 0) {
                $start_date_availability = Carbon::parse(end($availability)->start)->subMonth(1)->toDateString();
                $contributions = $affiliate->contributions()
                    ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
                    ->where("contribution_types.name", '=', 'Servicio')
                    ->where('contributions.month_year', '<=', $start_date_availability)
                    ->orderBy('contributions.month_year', 'desc')
                    ->take($number_contributions)
                    ->get();
                $total_base_wage =$contributions->sum('base_wage');
                $total_seniority_bonus =$contributions->sum('seniority_bonus');
                $total_retirement_fund  =$contributions->sum('retirement_fund');
                $sub_total_average_salary_quotable  = ($total_base_wage + $total_seniority_bonus);
                $total_average_salary_quotable  = ($total_base_wage + $total_seniority_bonus) / $number_contributions;
                // dd($total_average_salary_quotable);
            }else{
                //si no tiene periodos en disponibilidad
                $last_date_contribution = Carbon::parse(end($contributions)->end)->toDateString();
                $contributions = $affiliate->contributions()
                    ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
                    ->where("contribution_types.name", '=', 'Servicio')
                    ->where('contributions.month_year', '<=', $last_date_contribution)
                    ->orderBy('contributions.month_year', 'desc')
                    ->take($number_contributions)
                    ->get();
                Log::info($contributions);
                $total_base_wage = $contributions->sum('base_wage');
                $total_seniority_bonus = $contributions->sum('seniority_bonus');
                $total_retirement_fund = $contributions->sum('retirement_fund');
                $sub_total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus);
                $total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus) / $number_contributions;
            }

            $data= [
                'retirement_fund' => $retirement_fund,
                'number_contributions' => $number_contributions,
                'total_base_wage' => Util::formatMoney($total_base_wage) ?? null,
                'total_seniority_bonus' => Util::formatMoney($total_seniority_bonus) ?? null,
                'total_retirement_fund' => Util::formatMoney($total_retirement_fund) ?? null,
                'sub_total_average_salary_quotable' => Util::formatMoney($sub_total_average_salary_quotable) ?? null,
                'total_average_salary_quotable' => Util::formatMoney($total_average_salary_quotable) ?? null,
            ];
            return view('ret_fun.qualification_certification', $data);
        }else{
            // return redirect('ret_fun');
        }
    }
    public function saveAverageQuotable(Request $request, $id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $availability = $affiliate->getContributionsWithType('Disponibilidad');
        $contributions = $affiliate->getContributionsWithType('Servicio');
        $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;

        $total_contributions_backed = $this::sumTotalContributions($affiliate->getContributionsWithType('Servicio'));
        $total_item_zero_backed = $this::sumTotalContributions($affiliate->getContributionsWithType('Item 0'));
        $total_availability_backed = $this::sumTotalContributions($affiliate->getContributionsWithType('Disponibilidad'));

        $total_quotes = ($total_contributions_backed ?? 0) + ($total_item_zero_backed ?? 0) - ($total_availability_backed ?? 0);

        if (sizeOf($availability) > 0) {
            $start_date_availability = Carbon::parse(end($availability)->start)->subMonth(1)->toDateString();
            $contributions = $affiliate->contributions()
                ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
                ->where("contribution_types.name", '=', 'Servicio')
                ->where('contributions.month_year', '<=', $start_date_availability)
                ->orderBy('contributions.month_year', 'desc')
                ->take($number_contributions)
                ->get();
        }else{
            //si no tiene periodos en disponibilidad
            $last_date_contribution = Carbon::parse(end($contributions)->end)->toDateString();
            $contributions = $affiliate->contributions()
                ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
                ->where("contribution_types.name", '=', 'Servicio')
                ->where('contributions.month_year', '<=', $last_date_contribution)
                ->orderBy('contributions.month_year', 'desc')
                ->take($number_contributions)
                ->get();
        }
        $total_base_wage = $contributions->sum('base_wage');
        $total_seniority_bonus = $contributions->sum('seniority_bonus');
        $total_retirement_fund = $contributions->sum('retirement_fund');
        $sub_total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus);
        $total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus) / $number_contributions;
        $retirement_fund->average_quotable = $total_average_salary_quotable;
        $retirement_fund->save();

        $sub_total_ret_fun = ($total_quotes / 12) * $total_average_salary_quotable;
        $total_ret_fun = ($total_quotes / 12) * $total_average_salary_quotable;
        $data = [
            'total_base_wage' => $total_base_wage,
            'total_seniority_bonus' => $total_seniority_bonus,
            'total_retirement_fund' => $total_retirement_fund,
            'total_average_salary_quotable' => $total_average_salary_quotable,
            'sub_total_ret_fun' => $sub_total_ret_fun,
            'total_ret_fun' => $total_ret_fun,
        ];
        return $data;
    }
    public function saveTotalRetFun(Request $request, $id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;

        $availability = $affiliate->getContributionsWithType('Disponibilidad');
        $contributions = $affiliate->getContributionsWithType('Servicio');

        $total_contributions_backed = $this::sumTotalContributions($affiliate->getContributionsWithType('Servicio'));
        $total_item_zero_backed = $this::sumTotalContributions($affiliate->getContributionsWithType('Item 0'));
        $total_availability_backed = $this::sumTotalContributions($affiliate->getContributionsWithType('Disponibilidad'));

        $total_quotes = ($total_contributions_backed ?? 0) + ($total_item_zero_backed ?? 0) - ($total_availability_backed ?? 0);

        if (sizeOf($availability) > 0) {
            $start_date_availability = Carbon::parse(end($availability)->start)->subMonth(1)->toDateString();
            $contributions = $affiliate->contributions()
                ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
                ->where("contribution_types.name", '=', 'Servicio')
                ->where('contributions.month_year', '<=', $start_date_availability)
                ->orderBy('contributions.month_year', 'desc')
                ->take($number_contributions)
                ->get();
        }else{
            //si no tiene periodos en disponibilidad
            $last_date_contribution = Carbon::parse(end($contributions)->end)->toDateString();
            $contributions = $affiliate->contributions()
                ->leftJoin("contribution_types", "contributions.contribution_type_id", '=', "contribution_types.id")
                ->where("contribution_types.name", '=', 'Servicio')
                ->where('contributions.month_year', '<=', $last_date_contribution)
                ->orderBy('contributions.month_year', 'desc')
                ->take($number_contributions)
                ->get();
        }
        
        $total_base_wage = $contributions->sum('base_wage');
        $total_seniority_bonus = $contributions->sum('seniority_bonus');
        $total_retirement_fund = $contributions->sum('retirement_fund');
        $sub_total_average_salary_quotable = ($total_base_wage + $total_seniority_bonus);
        $total_average_salary_quotable = $sub_total_average_salary_quotable / $number_contributions;
        $sub_total_ret_fun = ($total_quotes / 12) * $total_average_salary_quotable;

        $advance_payment = $request->advancePayment ?? 0;
        $retention_loan_payment = $request->retentionLoanPayment ?? 0;
        $retention_guarantor = $request->retentionGuarantor ?? 0;

        $total_ret_fun = $sub_total_ret_fun - $advance_payment - $retention_loan_payment - $retention_guarantor;

        $retirement_fund->subtotal_ret_fun = $sub_total_ret_fun;
        $retirement_fund->total_ret_fun = $total_ret_fun;

        $advance_payment = $request->advancePayment ?? 0;
        $retention_loan_payment = $request->retentionLoanPayment ?? 0;
        $retention_guarantor = $request->retentionGuarantor ?? 0;

        //mejorar
        $discount_type = DiscountType::where('shortened','anticipo')->first();
        if ($advance_payment > 0) {
            if ($retirement_fund->discount_types->contains($discount_type->id)) {
                $retirement_fund->discount_types()->updateExistingPivot ($discount_type->id, ['amount' => $advance_payment]);
            }else{
                $retirement_fund->discount_types()->save($discount_type, ['amount'=> $advance_payment]);
            }
        }else{
            $retirement_fund->discount_types()->detach($discount_type->id);
        }
        $discount_type = DiscountType::where('shortened','prestamo')->first();
        if ($retention_loan_payment > 0) {
            if ($retirement_fund->discount_types->contains($discount_type->id)) {
                $retirement_fund->discount_types()->updateExistingPivot($discount_type->id, ['amount' => $retention_loan_payment]);
            } else {
                $retirement_fund->discount_types()->save($discount_type, ['amount'=> $retention_loan_payment]);
            }
        } else {
            $retirement_fund->discount_types()->detach($discount_type->id);
        }
        $discount_type = DiscountType::where('shortened','garantes')->first();
        if ($retention_guarantor > 0) {
            if ($retirement_fund->discount_types->contains($discount_type->id)) {
                $retirement_fund->discount_types()->updateExistingPivot($discount_type->id, ['amount' => $retention_guarantor]);
            } else {
                $retirement_fund->discount_types()->save($discount_type, ['amount'=> $retention_guarantor]);
            }
        } else {
            $retirement_fund->discount_types()->detach($discount_type->id);
        }
        // fin mejorar

        $total_ret_fun = $sub_total_ret_fun - $advance_payment - $retention_loan_payment - $retention_guarantor;

        $retirement_fund->subtotal_ret_fun = $sub_total_ret_fun;
        $retirement_fund->total_ret_fun = $total_ret_fun;

        $retirement_fund->save();
        $beneficiaries = $retirement_fund->ret_fun_beneficiaries()->orderBy('type', 'desc')->with('kinship')->get();
        //create function search spouse
        $text_spouse = 'Conyugue';
        $spouse = $beneficiaries->filter(function ($item) use ($text_spouse)
        {
            return $item->kinship->name == $text_spouse;
        });
        if (sizeOf($spouse)>0) {
            $has_spouse = true;
            $total_spouse = $total_ret_fun / 2;
            $total_spouse_percentage = 100/2;
            $total_derechohabientes = $total_spouse / sizeOf($beneficiaries);
            $total_derechohabientes_percentage = round($total_spouse_percentage / sizeOf($beneficiaries), 2);
            // $total_derechohabientes_percentage = (100 * $total_derechohabientes) / $total_spouse;
            $total_spouse = $total_spouse + $total_derechohabientes;
            $total_spouse_percentage = round($total_spouse_percentage + $total_derechohabientes_percentage, 2);
            $total_derechohabientes = ($total_ret_fun * $total_derechohabientes_percentage) / 100;
            $total_spouse = ($total_ret_fun * $total_spouse_percentage) / 100;
        }else{
            $has_spouse = false;
            $total_derechohabientes = $total_ret_fun / sizeOf($beneficiaries);
            $total_derechohabientes_percentage = round(100 / sizeOf($beneficiaries), 2);
            // $total_derechohabientes_percentage = (100 * $total_derechohabientes) / $total;
            $total_derechohabientes = ($total_ret_fun * $total_derechohabientes_percentage) / 100;
        }
        $one_spouse = 1;
        foreach ($beneficiaries as $beneficiary) {
            $beneficiary->full_name = $beneficiary->fullName();
            if ($beneficiary->kinship->name == $text_spouse ) {
                if ($one_spouse <= 1) {
                    $beneficiary->temp_percentage = $total_spouse_percentage;
                    $beneficiary->temp_amount = $total_spouse;
                }else{
                    return response('error', 500);
                }
                $one_spouse++;
            } else {
                $beneficiary->temp_percentage = $total_derechohabientes_percentage;
                $beneficiary->temp_amount = $total_derechohabientes;
            }
        }
        $data = [
            'total_ret_fun' => $total_ret_fun,
            'sub_total_ret_fun'  => $sub_total_ret_fun,
            'has_spouse' => $has_spouse,
            'beneficiaries' => $beneficiaries,
            'total_spouse' => $total_spouse ?? null,
            'total_derechohabientes' => $total_derechohabientes,
        ];
        return $data;
    }
    public function savePercentages(Request $request, $id )
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $beneficiaries = $retirement_fund->ret_fun_beneficiaries;
        foreach ($request->beneficiaries as $beneficiary) {
            $new_beneficiary = $retirement_fund->ret_fun_beneficiaries()->where('id',$beneficiary['id'])->first();
            if (!$new_beneficiary) {
                return response("error al buscar al beneficiario", 500);
            }
            $new_beneficiary->percentage = $beneficiary['temp_percentage'];
            $new_beneficiary->save();
        }
        $availability = $affiliate->getContributionsWithType('Disponibilidad');
        $has_availability = sizeOf($availability) > 0;
        $data = [
            'has_availability' => $has_availability,
        ];
        return $data;
    }
}