<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Address;
use Muserpol\Models\Degree;
use Muserpol\Models\Category;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\Spouse;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\City;
use Muserpol\Models\PensionEntity;
use Muserpol\Models\EconomicComplement\EcoComProcess;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Muserpol\Helpers\Util;
use Muserpol\Models\EconomicComplement\EcoComProcessSubmittedDocument;
use Muserpol\Models\ProcedureState;
use Muserpol\Helpers\ID;
use Muserpol\User;
use Auth;
use Illuminate\Support\Facades\Log;
use Muserpol\Models\EconomicComplement\EcoComProcedure;

class EcoComProcessController extends Controller
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
    public function create($affiliate_id)
    {

        $user = auth()->user();
        $affiliate = Affiliate::find($affiliate_id);
        if ($affiliate->hasEcoComProcessActive()) {
            return "no se puede crear el tramite, porque tiene tramites activos";
        }
        $degrees = Degree::all();
        $categories = Category::all();
        $pension_entities = PensionEntity::all();
        $procedure_types = ProcedureType::where('module_id', 2)->get();
        $requirements = ProcedureRequirement::select('procedure_requirements.id', 'procedure_documents.name as document', 'number', 'procedure_modality_id as modality_id')
            ->leftJoin('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
            ->orderBy('procedure_requirements.procedure_modality_id', 'ASC')
            ->orderBy('procedure_requirements.number', 'ASC')
            ->get();

        $spouse = Spouse::where('affiliate_id', $affiliate->id)->first();
        if (!isset($spouse->id)) {
            $spouse = new Spouse();
        }
        $modalities = ProcedureModality::whereIn('procedure_type_id', $procedure_types->pluck('id'))
            ->get();
        $cities = City::all();
        $data = [
            'user' => $user,
            'procedure_types' => $procedure_types,
            'modalities' => $modalities,
            'cities' => $cities,
            'degrees' => $degrees,
            'categories' => $categories,
            'pension_entities' => $pension_entities,
            'requirements' => $requirements,
            'affiliate' => $affiliate,
            'spouse' => $spouse
        ];
        return view('eco_com_process.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $affiliate = Affiliate::findOrFail($request->affiliate_id);
        if ($affiliate->hasEcoComProcessActive()) {
            return "no se puede crear el tramite, porque tiene tramites activos";
        }
        /**
         ** update affiliate info
         */
        $affiliate->pension_entity_id = $request->pension_entity_id;
        $affiliate->save();
        $eco_com_process = new EcoComProcess();
        $eco_com_process->user_id = auth()->user()->id;
        $eco_com_process->affiliate_id = $request->affiliate_id;
        $eco_com_process->procedure_modality_id = $request->procedure_modality_id;
        $eco_com_process->pension_entity_id = $request->pension_entity_id;
        $eco_com_process->procedure_state_id = 1;
        $eco_com_process->reception_date = now();
        $eco_com_process->status = true;
        $eco_com_process->save();

        $eco_com_beneficiary = new EcoComBeneficiary();
        $eco_com_beneficiary->eco_com_process_id = $eco_com_process->id;
        $eco_com_beneficiary->city_identity_card_id = $request->applicant_city_identity_card;
        $eco_com_beneficiary->identity_card = $request->applicant_identity_card;
        $eco_com_beneficiary->first_name = $request->applicant_first_name;
        $eco_com_beneficiary->second_name = $request->applicant_second_name;
        $eco_com_beneficiary->last_name = $request->applicant_last_name;
        $eco_com_beneficiary->mothers_last_name = $request->applicant_mothers_last_name;
        $eco_com_beneficiary->gender = $request->applicant_gender;
        $eco_com_beneficiary->surname_husband = $request->applicant_surname_husband ?? null;
        $eco_com_beneficiary->birth_date = Util::verifyBarDate($request->applicant_birth_date) ? Util::parseBarDate($request->applicant_birth_date) : $request->applicant_birth_date;
        $eco_com_beneficiary->phone_number = trim(implode(",", $request->applicant_phone_number));
        $eco_com_beneficiary->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number));
        $eco_com_beneficiary->civil_status = $request->applicant_civil_status;
        $eco_com_beneficiary->save();

        if ($request->beneficiary_city_address_id || $request->beneficiary_zone || $request->beneficiary_street || $request->beneficiary_number_address) {
            $address = new Address();
            $address->city_address_id = $request->beneficiary_city_address_id;
            $address->zone = $request->beneficiary_zone;
            $address->street = $request->beneficiary_street;
            $address->number_address = $request->beneficiary_number_address;
            $address->save();
            if ($request->procedure_modality_id == 24) {
                $eco_com_process->affiliate->address()->save($address);
            }
            $eco_com_beneficiary->address()->save($address);
        }
        $requirements = ProcedureRequirement::select('id')->get();
        foreach ($requirements  as  $requirement) {
            if ($request->input('document' . $requirement->id) == 'checked') {
                $submit = new EcoComProcessSubmittedDocument();
                $submit->eco_com_process_id = $eco_com_process->id;
                $submit->procedure_requirement_id = $requirement->id;
                $submit->reception_date = date('Y-m-d');
                $submit->comment = $request->input('comment' . $requirement->id);
                $submit->save();
            }
        }
        if ($request->aditional_requirements) {
            foreach ($request->aditional_requirements  as  $requirement) {
                $submit = new EcoComProcessSubmittedDocument();
                $submit->eco_com_process_id = $eco_com_process->id;
                $submit->procedure_requirement_id = $requirement;
                $submit->reception_date = date('Y-m-d');
                $submit->comment = null;
                $submit->save();
            }
        }
        return redirect()->action('EcoComProcessController@show', ['id' => $eco_com_process->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $eco_com_process = EcoComProcess::with(['pension_entity', 'procedure_modality', 'affiliate'])->find($id);
        $affiliate = $eco_com_process->affiliate;
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

        /**
         ** for beneficiary info
         */
        $eco_com_beneficiary = $eco_com_process->eco_com_beneficiary;
        $eco_com_beneficiary->phone_number = explode(',', $eco_com_beneficiary->phone_number);
        $eco_com_beneficiary->cell_phone_number = explode(',', $eco_com_beneficiary->cell_phone_number);
        if (!sizeOf($eco_com_beneficiary->address) > 0) {
            $eco_com_beneficiary->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
        }
        /**
         ** for edit requirements
         */
        // $procedures_modalities_ids = ProcedureModality::join('procedure_types','procedure_types.id','=','procedure_modalities.procedure_type_id')->where('procedure_types.module_id','=',2)->get()->pluck('id');
        // $procedures_modalities = ProcedureModality::whereIn('procedure_type_id',$procedures_modalities_ids)->get();
        // $file_modalities = ProcedureModality::get();
        // $requirements = ProcedureRequirement::where('procedure_modality_id',$eco_com_process->procedure_modality_id)->get();
        // $documents = EcoComProcessSubmittedDocument::where('eco_com_process_id',$id)->orderBy('procedure_requirement_id','ASC')->get();

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

        /**
         ** for economic complement
         */
        $eco_com_procedure_ids = Util::getEcoComCurrentProcedure();
        $has_last = !! $eco_com_process->economic_complements()->where('eco_com_procedure_id', $eco_com_procedure_ids[0])->get()->count();
        $has_before_last = !! $eco_com_process->economic_complements()->where('eco_com_procedure_id', $eco_com_procedure_ids[1])->get()->count();
        $eco_com_procedure_last = EcoComProcedure::find($eco_com_procedure_ids[0]);
        $eco_com_procedure_last->has = $has_last;
        $eco_com_procedure_before_last = EcoComProcedure::find($eco_com_procedure_ids[1]);
        $eco_com_procedure_before_last->has = $has_before_last;
        $eco_com_procedures = array($eco_com_procedure_last, $eco_com_procedure_before_last);
        $data = [
            'eco_com_process' => $eco_com_process,
            'affiliate' => $affiliate,
            'eco_com_beneficiary' => $eco_com_beneficiary,
            'states' => $states,
            'pension_entities' => $pension_entities,
            'user' => $user,

            'is_editable' => $is_editable,
            'cities' => $cities,
            'cities_pluck' => $cities_pluck,
            'birth_cities' => $birth_cities,

            'procedure_modalities' => $procedure_modalities,
            'requirements' => $procedure_requirements,
            'procedure_types' => $procedure_types,
            'submitted' =>  $submitted->pluck('eco_com_process_submitted_documents.procedure_requirement_id', 'procedure_requirements.number'),
            'submit_documents' => $submitted->get(),

            'eco_com_procedures' => $eco_com_procedures,
        ];
        return view('eco_com_process.show', $data);
    }
    public function updateInformation(Request $request)
    {
        $eco_com_process = EcoComProcess::find($request->id);
        // $this->authorize('update', $eco_com_process);
        $eco_com_process->reception_date = $request->reception_date;
        $eco_com_process->procedure_state_id = $request->procedure_state_id;
        $eco_com_process->pension_entity_id = $request->pension_entity['id'];
        if ($request->procedure_state_id == ID::state()->eliminado) {
            $eco_com_process->status = false;
            // $eco_com_process->code.="A";
        }
        $eco_com_process->save();
        $eco_com_process = EcoComProcess::with(['pension_entity', 'procedure_modality', 'affiliate'])->find($eco_com_process->id);

        $datos = array('eco_com_process' => $eco_com_process);
        return $datos;
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
    public function editRequirements(Request $request, $id)
    {
        $documents = EcoComProcessSubmittedDocument::select('procedure_requirements.number', 'eco_com_process_submitted_documents.procedure_requirement_id')
            ->leftJoin('procedure_requirements', 'eco_com_process_submitted_documents.procedure_requirement_id', '=', 'procedure_requirements.id')
            ->orderby('procedure_requirements.number', 'ASC')
            ->where('eco_com_process_submitted_documents.eco_com_process_id', $id)
            ->where('procedure_requirements.number', '>', '0')
            ->pluck('eco_com_process_submitted_documents.procedure_requirement_id', 'procedure_requirements.number');
        $num = $num2 = 0;

        foreach ($request->requirements as $requirement) {
            $from = $to = 0;
            $comment = null;
            for ($i = 0; $i < count($requirement); $i++) {
                $from = $requirement[$i]['number'];
                if ($requirement[$i]['status'] == true) {
                    $to = $requirement[$i]['id'];
                    $comment = $requirement[$i]['comment'];
                    $doc = EcoComProcessSubmittedDocument::where('eco_com_process_id', $id)->where('procedure_requirement_id', $documents[$from])->first();
                    $doc->procedure_requirement_id = $to;
                    $doc->comment = $comment;
                    $doc->save();
                }
            }
        }

        $procedure_requirements = ProcedureRequirement::select('procedure_requirements.id', 'procedure_documents.name as document', 'number', 'procedure_modality_id as modality_id')
            ->leftJoin('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
            ->where('procedure_requirements.number', '0')
            ->orderBy('procedure_requirements.procedure_modality_id', 'ASC')
            ->orderBy('procedure_requirements.number', 'ASC')
            ->get();

        $eco_com_process = EcoComProcess::select('id', 'procedure_modality_id')->find($id);
        $aditional =  $request->aditional_requirements;
        $num = "";
        foreach ($procedure_requirements as $requirement) {
            $needle = EcoComProcessSubmittedDocument::where('eco_com_process_id', $id)
                ->where('procedure_requirement_id', $requirement->id)
                ->first();
            if (isset($needle)) {
                if (!in_array($requirement->id, $aditional)) {
                    $num .= $requirement->id . ' ';
                    $needle->delete();
                    $needle->forceDelete();
                }
            } else {
                if (in_array($requirement->id, $aditional)) {
                    $submit = new EcoComProcessSubmittedDocument();
                    $submit->eco_com_process_id = $eco_com_process->id;
                    $submit->procedure_requirement_id = $requirement->id;
                    $submit->reception_date = date('Y-m-d');
                    $submit->comment = "";
                    $submit->save();
                }
            }
        }

        return $num;
    }
    public function updateBeneficiary(Request $request, $id)
    {
        $eco_com_process = EcoComProcess::find($id);
        $beneficiary = $eco_com_process->eco_com_beneficiary;
        $beneficiary->city_identity_card_id = $request->city_identity_card_id;
        $beneficiary->identity_card = mb_strtoupper(trim($request->identity_card));
        $beneficiary->last_name = mb_strtoupper(trim($request->last_name));
        $beneficiary->mothers_last_name = mb_strtoupper(trim($request->mothers_last_name));
        $beneficiary->first_name = mb_strtoupper(trim($request->first_name));
        $beneficiary->second_name = mb_strtoupper(trim($request->second_name));
        $beneficiary->surname_husband = mb_strtoupper(trim($request->surname_husband));
        $beneficiary->birth_date = Util::verifyBarDate($request->birth_date) ? Util::parseBarDate($request->birth_date) : $request->birth_date;
        $beneficiary->gender = $request->gender;
        $beneficiary->nua = $request->nua;
        $beneficiary->phone_number = implode(",", $request->phone_number);
        $beneficiary->cell_phone_number = implode(",", $request->cell_phone_number);
        $beneficiary->save();

        /**
         * Update or create address
         */
        if (sizeOf($beneficiary->address) > 0) {
            $address_id = $beneficiary->address()->first()->id;
            $address = Address::find($address_id);
            if ($request->address[0]['zone'] || $request->address[0]['street'] || $request->address[0]['number_address']) {
                $address->city_address_id = $request->address[0]['city_address_id'] ?? 1;
                $address->zone = $request->address[0]['zone'];
                $address->street = $request->address[0]['street'];
                $address->number_address = $request->address[0]['number_address'];
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
                $beneficiary->address()->detach($address->id);
                $address->delete();
            }
        } else {
            if (sizeOf($request->address) > 0) {
                $address = new Address();
                $address->city_address_id = $request->address[0]['city_address_id'] ?? 1;
                $address->zone = $request->address[0]['zone'];
                $address->street = $request->address[0]['street'];
                $address->number_address = $request->address[0]['number_address'];
                $address->save();
                $beneficiary->address()->save($address);
                if ($eco_com_process->procedure_modality_id == 24) {
                    $update_affiliate = Affiliate::find($eco_com_process->affiliate_id);
                    $update_affiliate->address()->save($address);
                }
            }
        }
    }
}
