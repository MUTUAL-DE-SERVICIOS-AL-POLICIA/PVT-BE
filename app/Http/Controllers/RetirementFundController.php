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
use Muserpol\Models\RetirementFund\RetFunAdvisor;
use Auth;
use Log;
use Validator;
use Muserpol\Models\Address;
use Muserpol\Models\Spouse;
use Muserpol\Models\ObservationType;
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
use Illuminate\Support\Facades\Redirect;
use Muserpol\Models\DiscountType;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\RetirementFund\RetFunState;
use Muserpol\Models\RetirementFund\RetFunRecord;
use DB;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\Role;
use Muserpol\Models\Workflow\WorkflowRecord;
use Muserpol\Models\Contribution\ContributionType;
use Muserpol\Models\Contribution\Reimbursement;
use Muserpol\Models\RetirementFund\RetFunCorrelative;
use Muserpol\Models\InfoLoan;
use Muserpol\Helpers\ID;

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
        $first_name = $request->beneficiary_first_name;
        $second_name = $request->beneficiary_second_name;
        $last_name = $request->beneficiary_last_name;
        $mothers_last_name = $request->beneficiary_mothers_last_name;
        $surname_husband = $request->beneficiary_surname_husband;
        $identity_card = $request->beneficiary_identity_card;
        $city_id = $request->beneficiary_city_identity_card;
        $birth_date = $request->beneficiary_birth_date;
        $kinship = $request->beneficiary_kinship;
        $gender = $request->beneficiary_gender;
        $account_type = $request->input('accountType');
        //*********START VALIDATOR************//
        $rules=[];
        $biz_rules = [];

        $has_ret_fun = false;
        $ret_fun = RetirementFund::where('affiliate_id',$request->affiliate_id)->where('code','NOT LIKE','%A')->first();
        if(isset($ret_fun->id)){
            $has_ret_fun = true;
            $biz_rules = [
                'ret_fun_double'  =>  $has_ret_fun?'required':'',
            ];
            $validator = Validator::make($request->all(),$biz_rules);
            if($validator->fails()){
                return Redirect::back()->withErrors($validator);
            }
        }

        $rules = [
            'ret_fun_modality' =>  'required',
            'accountType'   =>  'required',
            'applicant_first_name'  =>  'required',
            'applicant_identity_card'   =>  'required',
        ];


        $requirements = ProcedureRequirement::where('procedure_modality_id',$request->ret_fun_modality)->select('id','number')->orderBy('number','asc')->get();
        $array_requirements = [];
        foreach($requirements as $requirement){
            $array_requirements[$requirement->number] = 0;
        }

        foreach($requirements as $requirement){
            if($request->input('document'.$requirement->id) == 'checked'){
                $array_requirements[$requirement->number]++;
            }
        }
        //return $array_requirements;
        foreach($array_requirements as $key=>$requirement){

            if($requirement == 0 && $key!=0)
            {
                $biz_rules = [
                    'no_document'.$key   =>  'required'
                ];
            }
            if($requirement > 1)
            {
                $biz_rules = [
                    'double_document'.$key  =>  'required'
                ];

            }
            $rules = array_merge($rules,$biz_rules);
        }


        $has_lastname = false;
        $legal_has_lastname = false;
        if($request->applicant_last_name == '' && $request->applicant_mothers_last_name=='')
            $has_lastname = true;
        if($account_type == ID::retFun()->legal_guardian_id )
        {
            if($request->legal_guardian_last_name == '' && $request->legal_guardian_mothers_last_name=='')
                $legal_has_lastname = true;
        }
        $biz_rules = [
            'has_lastname'  =>  $has_lastname?'required':'',
            'legal_guardian_first_name' => $account_type==ID::retFun()->legal_guardian_id ? 'required' : '',
            'legal_has_lastname' => $legal_has_lastname ? 'required' : '',
            //'legal_guardian_identity_card'  =>  $account_type==3 ? 'required' : '',
            //'legal_guardian_number_authority'   => $account_type==3 ? 'required' : '',
            //'legal_guardian_notary_of_public_faith' => $account_type==3 ? 'required' : '',
            //'legal_guardian_notary'  => $account_type==3 ? 'required' : '',
            //'advisor_name_court'    =>  $account_type==2 ? 'required' : '',
            //'advisor_resolution_number'    =>  $account_type==2 ? 'required' : '',
            //'advisor_resolution_date'   => $account_type==2 ? 'required' : '',
        ];

        $rules = array_merge($rules,$biz_rules);


        for($i=0;is_array($first_name) && $i<sizeof($first_name);$i++){
            $beneficiary_has_lastname = false;
            if($request->beneficiary_last_name[$i] == '' && $request->beneficiary_mothers_last_name[$i]=='')
                $beneficiary_has_lastname = true;

            $biz_rules = [
                'beneficiary_first_name.'.$i =>  'required',
                //'beneficiary_identity_card.'.$i  =>  'required',
                //'beneficiary_kinship.'.$i    =>  'required',
                'beneficiary_has_lastname.'.$i   =>  $beneficiary_has_lastname?'required':'',
            ];
            $rules = array_merge($rules,$biz_rules);
        }
        $rules = array_merge($rules,$biz_rules);

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            // Log::info(json_encode($validator->errors));
            return redirect(route('create_ret_fun', $request->affiliate_id))
                ->withErrors($validator)
                ->withInput();
            // return Redirect::back()->withErrors($validator)->withInput();
            //return response()->json($validator->errors(), 406);
        }

         //*********END VALIDATOR************//


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

        $nextcode = RetirementFund::where('affiliate_id',$request->affiliate_id)->where('code','LIKE','%A')->first();
        if(isset($nextcode->id))
        {
            $code = str_replace("A","",$nextcode->code);
        }else{

            //$ret_fund  = RetirementFund::select('id','code')->orderby('id','desc')->first();
            $ret_fund = RetirementFund::select('id','code')
            ->limit(10)
            ->orderBy('id','desc')
            ->get();

            $ret_fun_code = $this->getLastCode($ret_fund);
            $code = Util::getNextCode ($ret_fun_code);
        }

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
        $retirement_fund->wf_state_current_id = 19;
        //$retirement_fund->type = "Pago"; default value
        $retirement_fund->subtotal_ret_fun = 0;
        $retirement_fund->total_ret_fun = 0;
        $retirement_fund->reception_date = date('Y-m-d');
        $retirement_fund->inbox_state = true;
        $retirement_fund->ret_fun_state_id = ID::state()->en_proceso;
        $retirement_fund->save();
        $reception_code = Util::getNextAreaCode($retirement_fund->id);




        $af = Affiliate::find($request->affiliate_id);
        $af->date_derelict = Util::verifyMonthYearDate($request->date_derelict) ? Util::parseMonthYearDate($request->date_derelict) : $request->date_derelict ;
        switch ($request->ret_fun_modality) {
            case 1:
            case 4:
                $af->affiliate_state_id = ID::affiliateState()->fallecido;
                break;
            case 2:
            case 3:
            case 5:
            case 6:
            case 7:
                $af->affiliate_state_id = ID::affiliateState()->jubilado;
                break;
            default:
                $this->info("error");
                break;
        }
        $af->save();

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

        //storing aditional documents
        if($request->aditional_requirements){
            foreach ($request->aditional_requirements  as  $requirement)
            {
                $submit = new RetFunSubmittedDocument();
                $submit->retirement_fund_id = $retirement_fund->id;
                $submit->procedure_requirement_id = $requirement;
                $submit->reception_date = date('Y-m-d');
                $submit->comment = "";
                $submit->save();
            }
        }


        $beneficiary = new RetFunBeneficiary();
        $beneficiary->retirement_fund_id = $retirement_fund->id;
        $beneficiary->city_identity_card_id = mb_strtoupper(trim($request->applicant_city_identity_card));
        $beneficiary->kinship_id = $request->applicant_kinship;
        $beneficiary->identity_card = mb_strtoupper($request->applicant_identity_card);
        $beneficiary->last_name = mb_strtoupper(trim($request->applicant_last_name));
        $beneficiary->mothers_last_name = mb_strtoupper(trim($request->applicant_mothers_last_name));
        $beneficiary->first_name = mb_strtoupper(trim($request->applicant_first_name));
        $beneficiary->second_name = mb_strtoupper(trim($request->applicant_second_name));
        $beneficiary->surname_husband = mb_strtoupper(trim($request->applicant_surname_husband));
        $beneficiary->birth_date = Util::verifyBarDate($request->applicant_birth_date) ? Util::parseBarDate($request->applicant_birth_date) : $request->applicant_birth_date;
        $beneficiary->gender = $request->applicant_gender;
        $beneficiary->phone_number = trim(implode(",", $request->applicant_phone_number ?? []));
        $beneficiary->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number ?? []));
        $beneficiary->type = ID::beneficiary()->solicitante;
        $beneficiary->save();
        if($account_type == ID::retFun()->beneficiary_id && $request->ret_fun_modality != ID::retFun()->fallecimiento_id && $request->ret_fun_modality != ID::retFunGlobalPay()->fallecimiento_id )
        {
            Util::updateAffiliatePersonalInfo($retirement_fund->affiliate_id, $beneficiary);
        }
        if ($account_type == ID::retFun()->beneficiary_id && ($request->ret_fun_modality == ID::retFun()->fallecimiento_id || $request->ret_fun_modality == ID::retFunGlobalPay()->fallecimiento_id) && $beneficiary->kinship_id == ID::kinship()->conyuge) {
            Log::info("updating spouse 1");
            Util::updateCreateSpousePersonalInfo($retirement_fund->affiliate_id, $beneficiary);
        }

        if($account_type == ID::retFun()->advisor_id)
        {
            $advisor = new RetFunAdvisor();
            //$advisor->retirement_fund_id = $retirement_fund->id;
            $advisor->city_identity_card_id = $request->applicant_city_identity_card;
            $advisor->kinship_id = null;
            $advisor->identity_card = $request->applicant_identity_card;
            $advisor->last_name = strtoupper(trim($request->applicant_last_name));
            $advisor->mothers_last_name = strtoupper(trim($request->applicant_mothers_last_name));
            $advisor->first_name = strtoupper(trim($request->applicant_first_name));
            $advisor->second_name = strtoupper(trim($request->applicant_second_name));
            $advisor->surname_husband = strtoupper(trim($request->applicant_surname_husband));
            //$advisor->gender = "M";
            $advisor->phone_number = trim(implode(",", $request->applicant_phone_number ?? []));
            $advisor->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number ?? []));
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

        if($account_type == ID::retFun()->legal_guardian_id)
        {
            $legal_guardian = new RetFunLegalGuardian();
            $legal_guardian->retirement_fund_id = $retirement_fund->id;
            $legal_guardian->city_identity_card_id = $request->legal_guardian_identity_card_id;
            $legal_guardian->identity_card = strtoupper(trim($request->legal_guardian_identity_card));
            $legal_guardian->last_name = strtoupper(trim($request->legal_guardian_last_name));
            $legal_guardian->mothers_last_name = strtoupper(trim($request->legal_guardian_mothers_last_name));
            $legal_guardian->first_name = strtoupper(trim($request->legal_guardian_first_name));
            $legal_guardian->second_name = strtoupper(trim($request->legal_guardian_second_name));
            $legal_guardian->surname_husband = strtoupper(trim($request->legal_guardian_surname_husband));
            //$legal_guardian->gender = "M";
            $legal_guardian->phone_number = trim(implode(",", $request->applicant_phone_number ?? []));
            $legal_guardian->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number ?? []));
            $legal_guardian->number_authority = $request->legal_guardian_number_authority;
            $legal_guardian->notary_of_public_faith = $request->legal_guardian_notary_of_public_faith;
            $legal_guardian->notary = $request->legal_guardian_notary;
            $legal_guardian->date_authority = $request->legal_guardian_date_authority;
            $legal_guardian->gender = $request->legal_guardian_gender;
            $legal_guardian->save();
            $beneficiary_legal_guardian = new RetFunLegalGuardianBeneficiary();
            $beneficiary_legal_guardian->ret_fun_beneficiary_id = $beneficiary->id;
            $beneficiary_legal_guardian->ret_fun_legal_guardian_id = $legal_guardian->id;
            $beneficiary_legal_guardian->save();
            //$beneficiary->type = "N";
            if ($request->ret_fun_modality != ID::retFun()->fallecimiento_id && $request->ret_fun_modality != ID::retFunGlobalPay()->fallecimiento_id) {
                Util::updateAffiliatePersonalInfo($retirement_fund->affiliate_id, $beneficiary);
            }
            if (($request->ret_fun_modality == ID::retFun()->fallecimiento_id || $request->ret_fun_modality == ID::retFunGlobalPay()->fallecimiento_id) && $beneficiary->kinship_id == ID::kinship()->conyuge) {
                Log::info("updating spouse 2");
                Util::updateCreateSpousePersonalInfo($retirement_fund->affiliate_id, $beneficiary);
            }
        }
        if ($request->beneficiary_city_address_id || $request->beneficiary_zone || $request->beneficiary_street || $request->beneficiary_number_address) {
            $address = new Address();
            $address->city_address_id = $request->beneficiary_city_address_id ?? 1;
            $address->zone = $request->beneficiary_zone;
            $address->street = $request->beneficiary_street;
            $address->number_address = $request->beneficiary_number_address;
            $address->save();
            if ($request->ret_fun_modality == ID::retFun()->fallecimiento_id || $request->ret_fun_modality == ID::retFunGlobalPay()->fallecimiento_id) {
            }else{
                $retirement_fund->affiliate->address()->save($address);
            }
            $beneficiary->address()->save($address);
        }

        for($i=0;is_array($first_name) && $i < sizeof($first_name);$i++){
            if($first_name[$i] != "" && ($last_name[$i] != "" || $mothers_last_name[$i] != "") ){
                $beneficiary = new RetFunBeneficiary();
                $beneficiary->retirement_fund_id = $retirement_fund->id;
                $beneficiary->city_identity_card_id = $city_id[$i];
                $beneficiary->kinship_id = $kinship[$i]??null;
                $beneficiary->identity_card = $identity_card[$i];
                $beneficiary->last_name = strtoupper(trim($last_name[$i]));
                $beneficiary->mothers_last_name = strtoupper(trim($mothers_last_name[$i]));
                $beneficiary->first_name = strtoupper(trim($first_name[$i]));
                $beneficiary->second_name = strtoupper(trim($second_name[$i]));
                $beneficiary->surname_husband = strtoupper(trim($surname_husband[$i]));
                $beneficiary->birth_date = Util::verifyBarDate($birth_date[$i]) ? Util::parseBarDate($birth_date[$i]) : $birth_date[$i]; ;
                $beneficiary->gender = $gender[$i];
                //$beneficiary->civil_status = $request->
                //$beneficiary->phone_number = $request->;
                //$beneficiary->cell_phone_number = $request->;
                $beneficiary->type = ID::beneficiary()->normal;
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
//         $data = [

//         ];
//         return \PDF::loadView('ret_fun.print.legal_dictum', $data)
// 				->setPaper('letter')
// 				->setOption('encoding', 'utf-8')
//                 ->stream("dictamenLegal.pdf");

//         return 123;
//         $retirement_fund = RetirementFund::find($id);
//         $affiliate = Affiliate::find($retirement_fund->affiliate_id);
//         $discounts = $retirement_fund->discount_types(); //DiscountType::where('retirement_fund_id',$retirement_fund->id)->orderBy('discount_type_id','ASC')->get();
//         $loans = InfoLoan::where('affiliate_id',$affiliate->id)->get();
//         $body = "Por consiguiente, habiendo sido remitido el presente tramite al Área Legal Unidad de
//         Otorgación del Fondo de Retiro Policial Solidario, autorizado por Jefatura de la Unidad de
//         Otorgación del Fondo de Retiro Policial Solidario, conforme a los Art. 2, 3, 5, 10, 26, 27, 28,
//         32, 36, 37, 38, 41, 42, 44, 45, 48, 49, 50, 70, 71, 72, 73, 74 y la Disposición Transitoria
//         Segunda, del Reglamento de Fondo de Retiro Policial Solidario, aprobado mediante
//         Resolución de Directorio N° 31/2017 en fecha 24 de agosto de 2017 y modificado mediante
//         Resolución de Directorio N° 36/2017 en fecha 20 de septiembre de 2017. Se DICTAMINA en
//         merito a la documentación de respaldo contenida en el presente, ";

//         $flagy = 0;
//         if($discounts->count()>0)
//             $body .= "proceder a realizar el descuento de ";

//         $discount = $discounts->where('discount_type_id','1')->first();

//         if(isset($discount->id)){
//             $body.="Bs ".Util::formatMoney($discount->pivot->amount)." (".Util::convertir($discount->pivot->amount).") por concepto de anticipo de Fondo de Retiro Policial de conformidad a la nota Nro. ".$discount->pivot->note_code." de fecha ".Util::getStringDate($discount->pivot->date);
//         }

//         $discounts = $retirement_fund->discount_types();

//         if(isset($discount->id)){
//             $body .= $this->getFlagy(3,2);
//             // if($flagy == 1)
//             // $body .= " y la suma de ";
//             $body.="Bs ".Util::formatMoney($discount->pivot->amount)." (".Util::convertir($discount->pivot->amount).") por concepto de saldo de deuda con la MUSERPOL de conformidad al contrato de préstamo Nro. ".$discount->code." y nota ".$discount->note_code." de fecha ".Util::getStringDate($discount->date);
//         }
//         //
//         $discounts = $retirement_fund->discount_types();
//         $discount = $discounts->where('discount_type_id','3')->first();
//         $loans = InfoLoan::where('affiliate_id',$affiliate->id)->get();

//         $body.="Bs ".Util::formatMoney($discount->pivot->amount)." (".Util::convertir($discount->pivot->amount).") por concepto de garantía de préstamo a favor de";// los señores. ".$discount->code." y nota ".$discount->note_code." de fecha ".$discount->date;
//         $num_loans = $loans->count();
//         if($num_loans==1)
//             $body .= "l señore ";
//         else
//             $body .= " los señores ";
//         $i=0;
//         foreach($loans as $loan){
//             $i++;
//             if($i!=1)
//             {
//                 if($num_loans-$i==0)
//                     $body .= " y ";
//                 else
//                     $body .= ", ";
//             }
//             $body.= $loan->affiliate_guarantor->fullName()." con C.I. N° ".$loan->affiliate_guarantor->identity_card." en la suma de Bs ".Util::formatMoney($loan->amount)." (".Util::convertir($discount->pivot->amount);
//         }
//         $body .= " en conformidad al contrato de préstamo Nro. ".$discount->pivot->code." y la nota ".$discount->pivot->note_code." de fecha ". Util::getStringDate($retirement_fund->reception_date) ." de la Dirección de Estrategias Sociales e Inversiones. Reconocer los derechos y se otorgue el beneficio del Fondo de Retiro Policial Solidario por <b>".strtoupper($retirement_fund->procedure_modality->name)."</b> a favor de:<br><br>";
//         $body .= $affiliate->degree->shortened." ".$affiliate->fullName()." con C.I. N° ".$affiliate->identity_card." ".$affiliate->city_identity_card->first_shortened."., el monto de Bs ".Util::formatMoney($retirement_fund->total_ret_fun)." (".Util::convertir($retirement_fund->total_ret_fun).").";
//         return $body;
// return "123";

        $retirement_fund = RetirementFund::find($id);

        $this->authorize('view', $retirement_fund);

        $affiliate = Affiliate::find($retirement_fund->affiliate_id);
        if (!sizeOf($affiliate->address) > 0) {
            $affiliate->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
        }

        $beneficiaries = RetFunBeneficiary::with('address')->where('retirement_fund_id',$retirement_fund->id)->with(['kinship', 'city_identity_card'])->orderByDesc('type')->orderBy('id')->get();
        foreach ($beneficiaries as $b) {
            $b->phone_number=explode(',',$b->phone_number);
            $b->cell_phone_number=explode(',',$b->cell_phone_number);
            if(! sizeOf($b->address) > 0 && $b->type == 'S'){
                $b->address[]= array('zone' => null, 'street'=>null, 'number_address'=>null);
            }
        }
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
        //return $procedures_modalities_ids;
        $procedures_modalities = ProcedureModality::whereIn('procedure_type_id',$procedures_modalities_ids)->get();
        $file_modalities = ProcedureModality::get();
        $requirements = ProcedureRequirement::where('procedure_modality_id',$retirement_fund->procedure_modality_id)->get();
        $documents = RetFunSubmittedDocument::where('retirement_fund_id',$id)->orderBy('procedure_requirement_id','ASC')->get();
        $cities = City::get();
        $kinships = Kinship::get();

        $cities_pluck = City::all()->pluck('first_shortened', 'id');
        $birth_cities = City::all()->pluck('name', 'id');

        $states = RetFunState::get();

        $ret_fun_records=RetFunRecord::where('ret_fun_id', $id)->orderBy('id','desc')->get();
        //return $retirement_fund->ret_fun_state->name;

        ///proof
        $user = User::find(Auth::user()->id);
        $procedure_types = ProcedureType::where('module_id', 3)->get();
        $procedure_requirements = ProcedureRequirement::
                                    select('procedure_requirements.id','procedure_documents.name as document','number','procedure_modality_id as modality_id')
                                    ->leftJoin('procedure_documents','procedure_requirements.procedure_document_id','=','procedure_documents.id')
                                    ->orderBy('procedure_requirements.procedure_modality_id','ASC')
                                    ->orderBy('procedure_requirements.number','ASC')
                                    ->get();
        $modalities = ProcedureModality::where('procedure_type_id','<=', '2')->select('id','name', 'procedure_type_id')->get();

        $observation_types = ObservationType::where('module_id',3)->get();

        //selected documents
        $submitted = RetFunSubmittedDocument::
            select('ret_fun_submitted_documents.id','procedure_requirements.number','ret_fun_submitted_documents.procedure_requirement_id','ret_fun_submitted_documents.comment','ret_fun_submitted_documents.is_valid')
            ->leftJoin('procedure_requirements','ret_fun_submitted_documents.procedure_requirement_id','=','procedure_requirements.id')
            ->orderby('procedure_requirements.number','ASC')
            ->where('ret_fun_submitted_documents.retirement_fund_id',$id);       
        // return $submitted->get();
            // ->pluck('ret_fun_submitted_documents.procedure_requirement_id','procedure_requirements.number');
        /**for validate doc*/
        $rol = Util::getRol();
        $module = Role::find($rol->id)->module;
        $wf_current_state = WorkflowState::where('role_id', $rol->id)->where('module_id', '=', $module->id)->first();
        $can_validate = $wf_current_state->id == $retirement_fund->wf_state_current_id;
        $can_cancel = ($retirement_fund->user_id == $user->id && $retirement_fund->inbox_state == true);

        //workflow record
        $workflow_records = WorkflowRecord::where('ret_fun_id', $id)->orderBy('created_at', 'desc')->get();

        $first_wf_state = RetFunRecord::whereRaw("message like '%creo el Tr%'")->first();
        if ($first_wf_state) {
            $re = '/(?<= usuario )(.*)(?= cr.* )/mi';
            $str = $first_wf_state->message;
            preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
            $user_name = $matches[0][0];
            $rol = User::where('username','=', $user_name)->first()->roles->first();
            $first_wf_state = WorkflowState::where('role_id', $rol->id)->first();
        }


        // dd($first_wf_state);

        $wf_states = WorkflowState::where('module_id', '=', $module->id)->where('sequence_number','>',($first_wf_state->sequence_number ?? 1))->orderBy('sequence_number')->get();

        $correlatives = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->get();
        $steps = [];
        $data = $retirement_fund->getReceptionSummary();
        $is_editable = ID::getEditableId();
        if(isset($retirement_fund->id))
            $is_editable = ID::getNonEditableId();
        //return $data;
        //return $correlatives;
        $data = [
            'retirement_fund' => $retirement_fund,
            'affiliate' =>  $affiliate,
            'beneficiaries' =>  $beneficiaries,
            'applicant' => $applicant,
            'advisor'  =>  $advisor,
            'legal_guardian'    =>  $guardian,
            'procedure_modalities' => $procedures_modalities,
            'file_modalities'   =>  $file_modalities,
            'documents' => $documents,
            'cities'    =>  $cities,
            'kinships'   =>  $kinships,
            'cities_pluck' => $cities_pluck,
            'birth_cities' => $birth_cities,
            'states'    =>  $states,
            'ret_fun_records' => $ret_fun_records,
            'requirements'  =>  $procedure_requirements,
            'user'  =>  $user,
            'procedure_types'   =>  $procedure_types,
            'modalities'    =>  $modalities,
            'observation_types' => $observation_types,
            'observations' => $retirement_fund->ret_fun_observations,
            'submitted' =>  $submitted->pluck('ret_fun_submitted_documents.procedure_requirement_id','procedure_requirements.number'),
            'submit_documents' => $submitted->get(),
            'can_validate' =>  $can_validate,
            'can_cancel' =>  $can_cancel,
            'workflow_records' =>  $workflow_records,
            'first_wf_state' =>  $first_wf_state,
            'wf_states' =>  $wf_states,
            'is_editable'  =>  $is_editable
        ];
        // return $data;

        return view('ret_fun.show',$data);
    }
    private function getFlagy($num,$pos){
        if($num == ($pos+1))
            return ", ";
        if($num == ($pos+2))
            return " y la suma de ";
        return ;
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

        $code = $request->code ?? '';
        $last_name = strtoupper($request->last_name) ?? '';
        $mothers_last_name = strtoupper($request->mothers_last_name) ?? '';
        $surname_husband = strtoupper($request->surname_husband) ?? '';
        $first_name = strtoupper($request->first_name) ?? '';
        $second_name = strtoupper($request->second_name) ?? '';
        $modality = strtoupper($request->modality) ?? '';
        $workflow= strtoupper($request->workflow) ?? '';
        $state = strtoupper($request->state) ?? '';

        $total = RetirementFund::select('retirement_funds.id')
                                ->leftJoin('affiliates','retirement_funds.affiliate_id','=','affiliates.id')
                                ->leftJoin('procedure_modalities','retirement_funds.procedure_modality_id','=','procedure_modalities.id')
                                ->leftJoin('wf_states','retirement_funds.wf_state_current_id','=','wf_states.id')
                                ->leftJoin('ret_fun_states','retirement_funds.ret_fun_state_id','=','ret_fun_states.id')
                                ->whereRaw("coalesce(retirement_funds.code, '') LIKE '$code%'")
                                ->whereRaw("coalesce(affiliates.first_name,'' ) LIKE '$first_name%'")
                                ->whereRaw("coalesce(affiliates.second_name,'' ) LIKE '$second_name%'")
                                ->whereRaw("coalesce(affiliates.last_name,'') LIKE '$last_name%'")
                                ->whereRaw("coalesce(affiliates.mothers_last_name,'') LIKE '$mothers_last_name%'")
                                ->whereRaw("coalesce(affiliates.surname_husband,'') LIKE '$surname_husband%'")
                                ->whereRaw("coalesce(upper(wf_states.first_shortened),'') LIKE '$workflow%'")
                                ->whereRaw("coalesce(ret_fun_states.name,'') LIKE '$state%'")
                                ->whereRaw("coalesce(upper(procedure_modalities.name),'') LIKE '$modality%'")

                                ->count();

        $ret_funds = RetirementFund::select(
            'retirement_funds.id',
            'retirement_funds.code',
            'affiliates.last_name as last_name',
            'affiliates.mothers_last_name as mothers_last_name',
            'affiliates.surname_husband as surname_husband',
            'affiliates.first_name as first_name',
            'affiliates.second_name as second_name',
            'procedure_modalities.name as modality',
            'wf_states.first_shortened as workflow',
            'retirement_funds.reception_date as reception_date',
            'ret_fun_states.name as state',
            'retirement_funds.total as total'
        )
                                ->leftJoin('affiliates','retirement_funds.affiliate_id','=','affiliates.id')
                                ->leftJoin('procedure_modalities','retirement_funds.procedure_modality_id','=','procedure_modalities.id')
                                ->leftJoin('wf_states','retirement_funds.wf_state_current_id','=','wf_states.id')
                                ->leftJoin('ret_fun_states','retirement_funds.ret_fun_state_id','=','ret_fun_states.id')
                                ->whereRaw("coalesce(retirement_funds.code, '') LIKE '$code%'")
                                ->whereRaw("coalesce(affiliates.first_name,'' ) LIKE '$first_name%'")
                                ->whereRaw("coalesce(affiliates.second_name,'' ) LIKE '$second_name%'")
                                ->whereRaw("coalesce(affiliates.last_name,'') LIKE '$last_name%'")
                                ->whereRaw("coalesce(affiliates.mothers_last_name,'') LIKE '$mothers_last_name%'")
                                ->whereRaw("coalesce(affiliates.surname_husband,'') LIKE '$surname_husband%'")
                                ->whereRaw("coalesce(ret_fun_states.name,'') iLIKE '$state%'")
                                ->whereRaw("coalesce(upper(procedure_modalities.name),'') LIKE '$modality%'")
                                ->whereRaw("coalesce(upper(wf_states.first_shortened),'') LIKE '$workflow%'")
                                ->skip($offset)
                                ->take($limit)
                                ->orderBy($sort,$order)
                                ->get();


        return response()->json(['ret_funds' => $ret_funds->toArray(),'total'=>$total]);
    }

    public function generateProcedure(Affiliate $affiliate){

        $this->authorize('create',RetirementFund::class);
        $user = Auth::User();
        $affiliate = Affiliate::select('affiliates.id','identity_card', 'city_identity_card_id','registration','first_name','second_name','last_name','mothers_last_name', 'surname_husband', 'birth_date','gender', 'degrees.name as degree','civil_status','affiliate_states.name as affiliate_state','phone_number', 'cell_phone_number','date_derelict')
                                ->leftJoin('degrees','affiliates.id','=','degrees.id')
                                ->leftJoin('affiliate_states','affiliates.affiliate_state_id','=','affiliate_states.id')
                                ->find($affiliate->id);
        # 3 id of ret_fun
        $procedure_types = ProcedureType::where('module_id', 3)->get();
        $procedure_requirements = ProcedureRequirement::
                                    select('procedure_requirements.id','procedure_documents.name as document','number','procedure_modality_id as modality_id')
                                    ->leftJoin('procedure_documents','procedure_requirements.procedure_document_id','=','procedure_documents.id')
                                    ->orderBy('procedure_requirements.procedure_modality_id','ASC')
                                    ->orderBy('procedure_requirements.number','ASC')
                                    ->get();

        $spouse = Spouse::where('affiliate_id',$affiliate->id)->first();
        if(!isset($spouse->id))
            $spouse = new Spouse();
        $modalities = ProcedureModality::where('procedure_type_id','<=', '2')->select('id','name', 'procedure_type_id')->get();

        $kinships = Kinship::get();

        $cities = City::get();

        $searcher = new SearcherController();

        $data = [
            'user' => $user,
            'requirements' => $procedure_requirements,
            'procedure_types'    => $procedure_types,
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
        // return $request;
        $retirement_fund = RetirementFund::find($id);
        $this->authorize('update',new RetFunSubmittedDocument);

        foreach($request->submit_documents as $document_array){

            $document = $document_array[0];
            $submit_document = RetFunSubmittedDocument::find($document['submit_document_id']);
            $submit_document->is_valid=$document['status'];
            $submit_document->comment=$document['comment'];
            $submit_document->save();
            // Log::info($document['number']);
        }
        return $request;
        // $submited_documents = RetFunSubmittedDocument::where('retirement_fund_id',$id)->orderBy('procedure_requirement_id','ASC')->get();
        // foreach ($submited_documents as $document)
        // {
        //     $value= "comment".$document->id."";
        //     $document->comment = $request->input($value);
        //     $value= "document".$document->id."";
        //     if($request->input($value) == '1')
        //         $document->is_valid = true;
        //     else
        //         $document->is_valid = false;
        //     $document->save();
        // }

        // return redirect('ret_fun/'.$retirement_fund->id);
        //return $retirement_fund;
    }
    public function updateBeneficiaries(Request $request, $id){
        $this->authorize('update',new RetFunBeneficiary);
        $i = 0;
        $ben = 0;
        $beneficiaries_array_request = [];
        foreach (array_pluck($request->all(), 'id') as $key => $value) {
            if($value){
                array_push($beneficiaries_array_request, $value);
            }
        }
        Log::info(json_encode($request->all()[0]));
        /* delete beneficiaries */
        $beneficiaries = RetirementFund::find($id)->ret_fun_beneficiaries;
        foreach ($beneficiaries as $key => $ben) {
            $index = array_search($ben->id, $beneficiaries_array_request);
            if ($index === false) {
                $ben->delete();
            }
        }
        $retirement_fund = RetirementFund::find($id);
        /*update info beneficiaries*/
        $beneficiaries = RetirementFund::find($id)->ret_fun_beneficiaries->toArray();
        foreach ($request->all() as $key => $new_ben) {
            $found = [];
            if (isset($new_ben['id'])) {
                $found = array_filter($beneficiaries,function ($var) use($new_ben)
                {
                    return ($var['id'] == $new_ben['id']);
                });
            }
            // Log::info($new_ben);
            if($found){
                $old_ben = RetFunBeneficiary::find($new_ben['id']);
                $old_ben->city_identity_card_id = $new_ben['city_identity_card_id'];
                $old_ben->kinship_id = $new_ben['kinship_id'];
                $old_ben->identity_card = mb_strtoupper(trim($new_ben['identity_card']));
                $old_ben->last_name = mb_strtoupper(trim($new_ben['last_name']));
                $old_ben->mothers_last_name = mb_strtoupper(trim($new_ben['mothers_last_name']));
                $old_ben->first_name = mb_strtoupper(trim($new_ben['first_name']));
                $old_ben->second_name = mb_strtoupper(trim($new_ben['second_name']));
                $old_ben->surname_husband = mb_strtoupper(trim($new_ben['surname_husband']));
                $old_ben->birth_date = Util::verifyBarDate($new_ben['birth_date']) ? Util::parseBarDate($new_ben['birth_date']) : $new_ben['birth_date'];
                $old_ben->gender = $new_ben['gender'];
                $old_ben->state = $new_ben['state'] ?? false;
                if($old_ben->type == 'S' && $retirement_fund->procedure_modality_id !=  ID::retFun()->fallecimiento_id){
                    $update_affilaite = Affiliate::find($retirement_fund->affiliate_id);
                    $update_affilaite->identity_card = $old_ben->identity_card;
                    $update_affilaite->first_name = $old_ben->first_name;
                    $update_affilaite->second_name = $old_ben->second_name;
                    $update_affilaite->last_name = $old_ben->last_name;
                    $update_affilaite->mothers_last_name = $old_ben->mothers_last_name;
                    $update_affilaite->gender = $old_ben->gender;
                    $update_affilaite->birth_date = Util::verifyBarDate($old_ben->birth_date) ? Util::parseBarDate($old_ben->birth_date) : $old_ben->birth_date;
                    $update_affilaite->city_identity_card_id =$old_ben->city_identity_card_id;
                    $update_affilaite->surname_husband = $old_ben->surname_husband;
                    $update_affilaite->save();
                }
                if ($old_ben->type == 'S') {
                    $old_ben->phone_number = trim(implode(",", $new_ben['phone_number']));
                    $old_ben->cell_phone_number = trim(implode(",", $new_ben['cell_phone_number']));

                    //$old_ben->cell_phone_number = trim(implode(",", $new_ben['cell_phone_number']));
                    /*Actualizar direccion  */
                    if (sizeOf($old_ben->address) > 0) {
                        $address_id = $old_ben->address()->first()->id;
                            $address = Address::find($address_id);
                        if($new_ben['address'][0]['zone'] || $new_ben['address'][0]['street'] || $new_ben['address'][0]['number_address'] ){
                            $address->city_address_id = $new_ben['address'][0]['city_address_id'] ?? 1;
                            $address->zone = $new_ben['address'][0]['zone'];
                            $address->street = $new_ben['address'][0]['street'];
                            $address->number_address = $new_ben['address'][0]['number_address'];
                            $address->save();
                        }else{
                            if ($retirement_fund->procedure_modality_id != ID::retFun()->fallecimiento_id) {
                                $update_affilaite = Affiliate::find($retirement_fund->affiliate_id);
                                $old_ben->address()->detach($address->id);
                            }
                            $old_ben->address()->detach($address->id);
                            $address->delete();
                        }
                    }else{
                        if ($new_ben['address']) {
                            Log::info('new Address');
                            $address = new Address();
                            $address->city_address_id = $new_ben['address'][0]['city_address_id'] ?? 1;
                            $address->zone = $new_ben['address'][0]['zone'];
                            $address->street = $new_ben['address'][0]['street'];
                            $address->number_address = $new_ben['address'][0]['number_address'];
                            $address->save();
                            $old_ben->address()->save($address);
                            if ($retirement_fund->procedure_modality_id != ID::retFun()->fallecimiento_id) {
                                $update_affilaite = Affiliate::find($retirement_fund->affiliate_id);
                                $update_affilaite->address()->save($address);
                            }
                        }
                    }


                }
                $old_ben->save();
            }else{
                $beneficiary = new RetFunBeneficiary();
                $beneficiary->retirement_fund_id = $id;
                $beneficiary->city_identity_card_id = $new_ben['city_identity_card_id'];
                $beneficiary->kinship_id = $new_ben['kinship_id'];
                $beneficiary->identity_card = mb_strtoupper(trim($new_ben['identity_card']));
                $beneficiary->last_name = mb_strtoupper(trim($new_ben['last_name']));
                $beneficiary->mothers_last_name = mb_strtoupper(trim($new_ben['mothers_last_name']));
                $beneficiary->first_name = mb_strtoupper(trim($new_ben['first_name']));
                $beneficiary->second_name = mb_strtoupper(trim($new_ben['second_name']));
                $beneficiary->surname_husband = mb_strtoupper(trim($new_ben['surname_husband']));
                $beneficiary->birth_date = Util::verifyBarDate($new_ben['birth_date']) ? Util::parseBarDate($new_ben['birth_date']) : $new_ben['birth_date'];
                $beneficiary->gender = $new_ben['gender'];
                $beneficiary->state = $new_ben['state'];
                // $old_ben->state = $new_ben['state'];
                // $beneficiary->phone_number = trim(implode(",", $request->applicant_phone_number));
                // $beneficiary->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number));
                $beneficiary->type = ID::beneficiary()->normal;
                $beneficiary->save();
            }
        }
        $beneficiaries = RetirementFund::find($id)->ret_fun_beneficiaries()->with(['kinship', 'city_identity_card', 'address'])->orderByDesc('type')->orderBy('id')->get();
        foreach ($beneficiaries as $b) {
            $b->phone_number = explode(',', $b->phone_number);
            $b->cell_phone_number = explode(',', $b->cell_phone_number);
            if (!sizeOf($b->address) > 0 && $b->type == 'S') {
                $b->address[] = array('zone' => null, 'street' => null, 'number_address' => null);
            }
        }
        $data=[
            'beneficiaries' => $beneficiaries,
        ];
        return $data;

    }
    public function updateInformation(Request $request)
    {
        $retirement_fund = RetirementFund::find($request->id);
        $this->authorize('update', $retirement_fund);
        $retirement_fund->city_end_id = $request->city_end_id;
        $retirement_fund->city_start_id = $request->city_start_id;
        $retirement_fund->reception_date = $request->reception_date;
        $retirement_fund->ret_fun_state_id = $request->ret_fun_state_id;
        if($retirement_fund->ret_fun_state_id == ID::state()->eliminado){
            $retirement_fund->code.="A";
        }
        $retirement_fund->save();
        $datos = array('retirement_fund' => $retirement_fund, 'procedure_modality'=>$retirement_fund->procedure_modality,'city_start'=>$retirement_fund->city_start,'city_end'=>$retirement_fund->city_end );
        return $datos;
    }
    public function qualification($ret_fun_id)
    {
        $retirement_fund = RetirementFund::find($ret_fun_id);
        $this->authorize('qualify', $retirement_fund);
        $affiliate = $retirement_fund->affiliate;
        $current_procedure = RetFunProcedure::where('is_enabled','=', true)->first();
        if (! $current_procedure) {
            return "error: Verifique si existen procedures activos";
        }
        $dates_global = $affiliate->getDatesGlobal();
        /*  qualification*/
        // $c=ContributionType::find(1);
        $group_dates = [];
        $total_dates = Util::sumTotalContributions($affiliate->getDatesGlobal());
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
        foreach (ContributionType::orderBy('id')->get() as $c){
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
            'years' => intval($total_dates/12),
            'months' => $total_dates%12
        );

        $total_availability_aporte = null;
        $total_availability_aporte_frps = null;
        if($affiliate->hasAvailability()){
            $availability = $affiliate->getContributionsAvailability();
            $total_availability_aporte = array_sum(array_column($availability, 'total'));
            $total_availability_aporte_frps = array_sum(array_column($availability, 'retirement_fund'));
        }


        $data = [
            'retirement_fund' => $retirement_fund,
            'affiliate' => $affiliate,
            'current_procedure' => $current_procedure,
            'all_contributions' => json_encode($contributions),
            'total_availability_aporte'=>$total_availability_aporte,
            'total_availability_aporte_frps'=>$total_availability_aporte_frps,
        ];
        $data = array_merge($data, $affiliate->getTotalAverageSalaryQuotable());
        return view('ret_fun.qualification', $data);
    }

    public function getAverageQuotable(Request $request, $id)
    {
        $rules = [
            'service_years' => 'required|numeric|min:0|max:100',
            'service_months' => 'required|numeric|min:0|max:12',
        ];
        $messages = [];

        try {
            $validator = Validator::make($request->all(), $rules, $messages)->validate();
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => $exception->errors(),
            ], 403);
        }
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $affiliate->service_years = $request->service_years;
        $affiliate->service_months = $request->service_months;
        $affiliate->save();
        $total_quotes = $affiliate->getTotalQuotes();
        $total_salary_quotable = $affiliate->getTotalAverageSalaryQuotable();
        $data = [
            'total_quotes' => $total_quotes,
            'total_salary_quotable' => $total_salary_quotable,
        ];
        return $data;
    }
    public function getDataQualificationCertification(DataTables $datatables, $retirement_fund_id)
    {
        $retirement_fund = RetirementFund::find($retirement_fund_id);
        $affiliate = $retirement_fund->affiliate;
        $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;
        $contributions = $affiliate->getContributionsPlus();
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
    }
    public function getDataQualificationAvailability(DataTables $datatables, $retirement_fund_id)
    {
        $retirement_fund = RetirementFund::find($retirement_fund_id);
        $affiliate = $retirement_fund->affiliate;
        $contributions = $affiliate->getContributionsAvailability();
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
    }
    public function qualificationCertification($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $number_contributions = Util::getRetFunCurrentProcedure()->contributions_number;
        $number = Util::getNextAreaCode($retirement_fund->id);
        if($retirement_fund){
            $affiliate = $retirement_fund->affiliate;
            $data= [
                'retirement_fund' => $retirement_fund,
                'number_contributions' => $number_contributions,
            ];
            $data = array_merge($data, $affiliate->getTotalAverageSalaryQuotable());
            return view('ret_fun.qualification_certification', $data);
        }else{
            // return redirect('ret_fun');
        }
    }

    public function saveAverageQuotable(Request $request, $id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        $total_quotes = $affiliate->getTotalQuotes();
        $total_average_salary_quotable = $affiliate->getTotalAverageSalaryQuotable()['total_average_salary_quotable'];
        $retirement_fund->average_quotable = $total_average_salary_quotable;
        $retirement_fund->save();

        $sub_total_ret_fun = ($total_quotes / 12) * $total_average_salary_quotable;
        $total_ret_fun = ($total_quotes / 12) * $total_average_salary_quotable;
        $discounts = $retirement_fund->discount_types()->whereIn('discount_types.id', [1,2,3])->get();
        $guarantors = InfoLoan::where('retirement_fund_id', $retirement_fund->id)->get();
        foreach ($guarantors as $value) {
            $value->full_name = $value->affiliate_guarantor->fullName();
            $value->identity_card = $value->affiliate_guarantor->identity_card;
        }
        $data = [
            'guarantors'=> $guarantors,
            'discounts'=> $discounts,
            'sub_total_ret_fun' => $sub_total_ret_fun,
            'total_ret_fun' => $total_ret_fun,
        ];
        return $data;
    }
    public function saveTotalRetFun(Request $request, $id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;

        $total_quotes = $affiliate->getTotalQuotes();
        $total_average_salary_quotable = $affiliate->getTotalAverageSalaryQuotable()['total_average_salary_quotable'];

        $sub_total_ret_fun = ($total_quotes / 12) * $total_average_salary_quotable;

        $advance_payment = $request->advancePayment ?? 0;
        $retention_loan_payment = $request->retentionLoanPayment ?? 0;
        $retention_guarantor = $request->retentionGuarantor ?? 0;

        $total_ret_fun = $sub_total_ret_fun - $advance_payment - $retention_loan_payment - $retention_guarantor;

        $retirement_fund->subtotal_ret_fun = $sub_total_ret_fun;
        $retirement_fund->total_ret_fun = $total_ret_fun;
        if (! $affiliate->hasAvailability()) {
            $retirement_fund->total = $total_ret_fun;
        }

        //mejorar
        $discount_type = DiscountType::where('shortened','anticipo')->first();
        if ($advance_payment > 0) {
            if ($retirement_fund->discount_types->contains($discount_type->id)) {
                $retirement_fund->discount_types()->updateExistingPivot ($discount_type->id, ['amount' => $advance_payment, 'date'=> $request->advancePaymentDate, 'code'=> $request->advancePaymentCode, 'note_code'=>$request->advancePaymentNoteCode]);
            }else{
                $retirement_fund->discount_types()->save($discount_type, ['amount'=> $advance_payment, 'date'=> $request->advancePaymentDate, 'code'=> $request->advancePaymentCode, 'note_code'=>$request->advancePaymentNoteCode]);
            }
        }else{
            $retirement_fund->discount_types()->detach($discount_type->id);
        }
        $discount_type = DiscountType::where('shortened','prestamo')->first();
        if ($retention_loan_payment > 0) {
            if ($retirement_fund->discount_types->contains($discount_type->id)) {
                $retirement_fund->discount_types()->updateExistingPivot($discount_type->id, ['amount' => $retention_loan_payment, 'date'=> $request->retentionLoanPaymentDate, 'code'=> $request->retentionLoanPaymentCode, 'note_code'=>$request->retentionLoanPaymentNoteCode]);
            } else {
                $retirement_fund->discount_types()->save($discount_type, ['amount'=> $retention_loan_payment, 'date'=> $request->retentionLoanPaymentDate, 'code'=> $request->retentionLoanPaymentCode, 'note_code'=>$request->retentionLoanPaymentNoteCode]);
            }
        } else {
            $retirement_fund->discount_types()->detach($discount_type->id);
        }
        $discount_type = DiscountType::where('shortened','garantes')->first();
        Log::info($request->guarantors);
        if ($retention_guarantor > 0) {
            if ($retirement_fund->discount_types->contains($discount_type->id)) {
                $retirement_fund->discount_types()->updateExistingPivot($discount_type->id, ['amount' => $retention_guarantor, 'date'=> $request->retentionGuarantorDate, 'code'=> $request->retentionGuarantorCode, 'note_code'=>$request->retentionGuarantorNoteCode]);
            } else {
                $retirement_fund->discount_types()->save($discount_type, ['amount'=> $retention_guarantor, 'date'=> $request->retentionGuarantorDate, 'code'=> $request->retentionGuarantorCode, 'note_code'=>$request->retentionGuarantorNoteCode]);
            }
            if (sizeOf($request->guarantors)) {
                /*
                TODO
                crear eliminacion de garantes
                 */
                $loans = InfoLoan::where('affiliate_id','=', $affiliate->id)->where('retirement_fund_id','=', $retirement_fund->id)->get();
                foreach ($loans as $value) {
                    $value->delete();
                }
                foreach ($request->guarantors as $value) {
                    $loan = new InfoLoan();
                    $loan->affiliate_id = $affiliate->id;
                    $loan->retirement_fund_id = $retirement_fund->id;
                    $loan->code = 'some code';
                    $loan->date = Carbon::now();
                    $loan->affiliate_guarantor_id = $value['id'];
                    $loan->amount = Util::parseMoney($value['amount']);
                    $loan->save();
                }
            }
        } else {
            $retirement_fund->discount_types()->detach($discount_type->id);
            /*
                TODO
                crear eliminacion de garantes
             */
            $loans = InfoLoan::where('affiliate_id','=', $affiliate->id)->where('retirement_fund_id','=', $retirement_fund->id)->get();
            foreach ($loans as $value) {
                $value->delete();
            }
        }
        // fin mejorar

        $total_ret_fun = $sub_total_ret_fun - $advance_payment - $retention_loan_payment - $retention_guarantor;

        $retirement_fund->subtotal_ret_fun = $sub_total_ret_fun;
        $retirement_fund->total_ret_fun = $total_ret_fun;

        $retirement_fund->save();
        $beneficiaries = $retirement_fund->ret_fun_beneficiaries()->orderByDesc('type')->orderBy('id')->with('kinship')->get();
        //create function search spouse
        $spouse_id = ID::kinship()->conyuge;
        $spouse = $beneficiaries->filter(function ($item) use ($spouse_id)
        {
            return $item->kinship->id == $spouse_id;
        });
        if (sizeOf($spouse)>0) {
            $has_spouse = true;
            $total_spouse = $total_ret_fun / 2;
            $total_spouse_percentage = 100/2;
            $total_derechohabientes_percentage = round($total_spouse_percentage / sizeOf($beneficiaries), 2);
            $total_spouse_percentage = round($total_spouse_percentage + $total_derechohabientes_percentage, 2);
            $total_spouse = $total_ret_fun / 2;
            $total_derechohabientes = round(($total_spouse/sizeOf($beneficiaries)),2);
            $total_spouse = round(($total_spouse + $total_derechohabientes), 2);
        }else{
            $has_spouse = false;
            $total_derechohabientes = round($total_ret_fun / sizeOf($beneficiaries), 2);
            $total_derechohabientes_percentage = round(100 / sizeOf($beneficiaries), 2);
        }
        $one_spouse = 1;
        foreach ($beneficiaries as $beneficiary) {
            $beneficiary->full_name = $beneficiary->fullName();
            if ($beneficiary->kinship->id == $spouse_id ) {
                if ($one_spouse <= 1) {
                    // recalculate
                    if ($request->reload) {
                        $beneficiary->temp_percentage = $total_spouse_percentage;
                        $beneficiary->temp_amount = $total_spouse;
                    }else{
                        $beneficiary->temp_percentage = $beneficiary->percentage ? $beneficiary->percentage : $total_spouse_percentage;
                        $beneficiary->temp_amount = $beneficiary->amount_ret_fun ? $beneficiary->amount_ret_fun : $total_spouse;
                    }
                }else{
                    return response('error', 500);
                }
                $one_spouse++;
            } else {
                //recalculate
                if ($request->reload) {
                    $beneficiary->temp_percentage = $total_derechohabientes_percentage;
                    $beneficiary->temp_amount = $total_derechohabientes;
                }else{
                    $beneficiary->temp_percentage = $beneficiary->percentage ? $beneficiary->percentage : $total_derechohabientes_percentage;
                    $beneficiary->temp_amount = $beneficiary->amount_ret_fun ? $beneficiary->amount_ret_fun : $total_derechohabientes;
                }
            }
        }
        $data = [
            'total_ret_fun' => $total_ret_fun,
            'sub_total_ret_fun' => $sub_total_ret_fun,
            'has_spouse' => $has_spouse,
            'beneficiaries' => $beneficiaries,
        ];
        return $data;
    }
    public function savePercentages(Request $request, $id )
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;
        foreach ($request->beneficiaries as $beneficiary) {
            $new_beneficiary = $retirement_fund->ret_fun_beneficiaries()->where('id',$beneficiary['id'])->first();
            if (!$new_beneficiary) {
                return response("error al buscar al beneficiario", 500);
            }
            $new_beneficiary->percentage = $beneficiary['temp_percentage'];
            $new_beneficiary->amount_ret_fun = $beneficiary['temp_amount'];
            $new_beneficiary->save();
        }
        $availability = $affiliate->getContributionsWithType(10);
        $has_availability = sizeOf($availability) > 0;
        $total = $retirement_fund->total_ret_fun;
        $beneficiaries = $retirement_fund->ret_fun_beneficiaries()->orderByDesc('type')->orderBy('id')->with('kinship')->get();

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
                if (!$retirement_fund->discount_types()->find($id)) {
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


        if ($has_availability) {
            $availability = ContributionType::find(10);
            $subtotal_availability = ($retirement_fund->subtotal_availability );
            $total_annual_yield = ($subtotal_availability * Util::getRetFunCurrentProcedure()->annual_yield)/100;
            $total_availability = $subtotal_availability + $total_annual_yield;
            $total = $total + $total_availability;

            $spouse_id = ID::kinship()->conyuge;
            $spouse = $beneficiaries->filter(function ($item) use ($spouse_id) {
                return $item->kinship->id == $spouse_id;
            });
            if (sizeOf($spouse) > 0) {
                $total_spouse = $total_availability / 2;
                $total_derechohabientes = round(($total_spouse / sizeOf($beneficiaries)), 2);
                $total_spouse = round(($total_spouse + ($total_spouse / sizeOf($beneficiaries))), 2);
            } else {
                $total_derechohabientes = round($total_availability / sizeOf($beneficiaries), 2);
            }
            $one_spouse = 1;
            foreach ($beneficiaries as $beneficiary) {
                $beneficiary->full_name = $beneficiary->fullName();
                if ($beneficiary->kinship->id == $spouse_id) {
                    if ($one_spouse <= 1) {
                        if($request->reload){
                            $beneficiary->temp_amount_availability = $total_spouse;
                        }else{
                            $beneficiary->temp_amount_availability = $beneficiary->amount_availability ? $beneficiary->amount_availability : $total_spouse;
                        }
                    } else {
                        return response('error', 500);
                    }
                    $one_spouse++;
                } else {
                    if($request->reload){
                        $beneficiary->temp_amount_availability = $total_derechohabientes;
                    }else{
                        $beneficiary->temp_amount_availability = $beneficiary->amount_availability ? $beneficiary->amount_availability : $total_derechohabientes;
                    }
                }
            }

            /* added availability */
            $array_discounts_availability = [];
            foreach($array_discounts as $value) {
                array_push($array_discounts_availability, array('name' => ('Fondo de Retiro + '. $availability->display_name .' ' .($value['name'] ? ' - '.$value['name'] : '' )), 'amount' => ($retirement_fund->subtotal_ret_fun + $total_availability - $value['amount'])));
            }

        }else{
            $array_discounts_availability = [];
            foreach ($array_discounts as $value) {
                array_push($array_discounts_availability, array('name' => ('Fondo de Retiro ' . ($value['name'] ? ' - ' . $value['name'] : '')), 'amount' => ($retirement_fund->subtotal_ret_fun - $value['amount'])));
            }
        }
        Log::info("total disponibilidad: ".json_encode($retirement_fund));
        $data = [
            'has_availability' => $has_availability,
            'subtotal_availability' => $subtotal_availability ?? 0,
            'total_annual_yield' => $total_annual_yield ?? 0,
            'total_availability' => $total_availability ?? 0,
            'total' => $total ?? 0,
            'beneficiaries' => $beneficiaries,
            'array_discounts' => $array_discounts_availability,
        ];
        return $data;
    }
    public function savePercentagesAvailability(Request $request, $id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;

        /**added function calculate sub_total_availability */
        $subtotal_availability = ($retirement_fund->subtotal_availability);
        $total_annual_yield = ($subtotal_availability * Util::getRetFunCurrentProcedure()->annual_yield) / 100;
        $total_availability = $subtotal_availability + $total_annual_yield;
        $retirement_fund->total_availability =  $total_availability;
        $retirement_fund->total =  $total_availability + ($retirement_fund->total_ret_fun ?? 0);
        Log::info($retirement_fund->total);
        $retirement_fund->save();
        /**added function calculate sub_total_availability */
        foreach ($request->beneficiaries as $beneficiary) {
            $new_beneficiary = $retirement_fund->ret_fun_beneficiaries()->where('id', $beneficiary['id'])->first();
            if (!$new_beneficiary) {
                return response("error al buscar al beneficiario", 500);
            }
            $new_beneficiary->amount_availability = $beneficiary['temp_amount_availability'];
            $new_beneficiary->save();
        }
        $beneficiaries = $retirement_fund->ret_fun_beneficiaries()->orderByDesc('type')->orderBy('id')->with('kinship')->get();
        foreach ($beneficiaries as $beneficiary) {
            if($request->reload){
                $beneficiary->temp_amount_total = round(($beneficiary->amount_availability + $beneficiary->amount_ret_fun),2);
            }else{
                $beneficiary->temp_amount_total = $beneficiary->amount_total ? $beneficiary->amount_total : round(($beneficiary->amount_availability + $beneficiary->amount_ret_fun),2);
            }
            $beneficiary->full_name = $beneficiary->fullName();
        }
        $data = [
            'beneficiaries' => $beneficiaries,
        ];
        return $data;
    }
    public function saveTotalRetFunAvailability(Request $request, $id)
    {
        $retirement_fund = RetirementFund::find($id);
        $affiliate = $retirement_fund->affiliate;

        foreach ($request->beneficiaries as $beneficiary) {
            $new_beneficiary = $retirement_fund->ret_fun_beneficiaries()->where('id', $beneficiary['id'])->first();
            if (!$new_beneficiary) {
                return response("error al buscar al beneficiario", 500);
            }
            $new_beneficiary->amount_total = $beneficiary['temp_amount_total'];
            $new_beneficiary->save();
        }
        $data = [

        ];
        return $data;
    }

    public function saveMessageContributionType(Request $request,$ret_fun_id)
    {
        $retirement_fund = RetirementFund::find($ret_fun_id);
        $contribution_type = ContributionType::find($request->contributionTypeId);

        if ($request->message) {
            if ($retirement_fund->contribution_types->contains($contribution_type)) {
                $retirement_fund->contribution_types()->updateExistingPivot($contribution_type->id, ['user_id'=>Auth::user()->id, 'message' => $request->message]);
            } else {
                $retirement_fund->contribution_types()->save($contribution_type, ['user_id'=>Auth::user()->id, 'message' => $request->message]);
            }
        } else {
            $retirement_fund->contribution_types()->detach($contribution_type);
        }
        return $retirement_fund;
    }

    public function editRequirements(Request $request, $id){
        //return $request->ret_fun_modality;
        //return $request->aditional_requirements;
        $documents = RetFunSubmittedDocument::
            select('procedure_requirements.number','ret_fun_submitted_documents.procedure_requirement_id')
            ->leftJoin('procedure_requirements','ret_fun_submitted_documents.procedure_requirement_id','=','procedure_requirements.id')
            ->orderby('procedure_requirements.number','ASC')
            ->where('ret_fun_submitted_documents.retirement_fund_id',$id)
            ->where('procedure_requirements.number','>','0')
            ->pluck('ret_fun_submitted_documents.procedure_requirement_id','procedure_requirements.number');
        //return $documents;
        $num = $num2 = 0;

        foreach($request->requirements as $requirement){
                $from = $to = 0;
                $comment = null;
                for($i=0;$i<count($requirement);$i++){
                    $from = $requirement[$i]['number'];
                    if($requirement[$i]['status'] == true)
                    {
                        $to = $requirement[$i]['id'];
                        $comment = $requirement[$i]['comment'];
                        $doc = RetFunSubmittedDocument::where('retirement_fund_id',$id)->where('procedure_requirement_id',$documents[$from])->first();
                        $doc->procedure_requirement_id = $to;
                        $doc->comment = $comment;
                        $doc->save();
                    }
                }
        }

        $procedure_requirements = ProcedureRequirement::
                                    select('procedure_requirements.id','procedure_documents.name as document','number','procedure_modality_id as modality_id')
                                    ->leftJoin('procedure_documents','procedure_requirements.procedure_document_id','=','procedure_documents.id')
                                    ->where('procedure_requirements.number','0')
                                    ->orderBy('procedure_requirements.procedure_modality_id','ASC')
                                    ->orderBy('procedure_requirements.number','ASC')
                                    ->get();

        $retirement_fund = RetirementFund::select('id','procedure_modality_id')->find($id);
        $aditional =  $request->aditional_requirements;
        $num ="";
        foreach($procedure_requirements as $requirement){
            $needle = RetFunSubmittedDocument::where('retirement_fund_id',$id)
                        ->where('procedure_requirement_id',$requirement->id)
                        ->first();
            if(isset($needle)) {
                if(!in_array($requirement->id,$aditional)){
                    $num.=$requirement->id.' ';
                    $needle->delete();
                    $needle->forceDelete();
                }
            } else {
                if(in_array($requirement->id,$aditional)) {
                    $submit = new RetFunSubmittedDocument();
                    $submit->retirement_fund_id = $retirement_fund->id;
                    $submit->procedure_requirement_id = $requirement->id;
                    $submit->reception_date = date('Y-m-d');
                    $submit->comment = "";
                    $submit->save();
                }
            }

        }

        return $num;
    }
    private function getLastCode($retirement_funds){
        $num = 0;
        $year = 0;
        if(count($retirement_funds) == 0)
        return "";
        foreach($retirement_funds as $retirement_fund)
        {
            $code = str_replace('A','',$retirement_fund->code);
            if( $code != "")
            {
                $code = explode('/',$code);
                if($code[1]>$year)
                    $year = $code[1];
                if($code[0]>$num)
                    $num = $code[0];
            }
        }
        return $num."/".$year;
    }

    public function dictamenLegal($id){
        $retirement_fund = RetirementFund::find($id);
        $actual_date = date('d-m-Y');
        $cite = "D.B.E/A.B.E./GMQ/N°";
        $applicant = RetFunBeneficiary::where('retirement_fund_id',$retirement_fund->id)->where('type','S')->get();
        $beneficiaries = RetFunBeneficiary::where('retirement_fund_id',$retirement_fund->id)->where('type','N')->orderBy('id')->get();
        //return $retirement_fund->affiliate_id;
        $affiliate = Affiliate::find($retirement_fund->affiliate_id);
        $correlatives = RetFunCorrelative::where('retirement_fund_id',$retirement_fund->id)->get();

        $data = [
            'actual_date'   =>  $actual_date,   //fecha actual (hoy)
            'cite'  =>  $cite,      //codigo identificador de usuario por area
            'beneficiaries'   => $beneficiaries,    //beneficiarios
            'applicant' =>  $applicant,     //persona que hace el tramite
            'affiliate' =>  $affiliate,     //policia afiliado
            'correlarives'  =>  $correlatives,  //codigos de documentos de cada area
        ];
        return $data;
    }
}
