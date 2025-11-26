<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Spouse;
use Muserpol\Models\RetirementFund\RetFunBeneficiary;
use Muserpol\Models\RetirementFund\RetFunLegalGuardian;
use Muserpol\Models\RetirementFund\RetFunAdvisor;
use Muserpol\Helpers\Util;
use Log;
use Carbon\Carbon;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Muserpol\Models\ObservationType;
use Muserpol\Models\EconomicComplement\EcoComLegalGuardian;
use DB;
use Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary;

class SearcherController
{
    private $tables;
    private $select;
    public function __construct($fields = "*")
    {
        $this->tables = [
            new Affiliate(),
            new Spouse(),
            new RetFunBeneficiary(),
            new RetFunLegalGuardian(),
            new RetFunAdvisor(),
            new EcoComBeneficiary(),
            new EcoComLegalGuardian(),
        ];
        $this->select = $fields;
    }
    private function getDefaults()
    {
        $this->tables = [
            new Affiliate(),
            new Spouse(),
            new RetFunBeneficiary(),
            new QuotaAidBeneficiary(),
            new RetFunLegalGuardian(),
            new RetFunAdvisor(),
            new EcoComBeneficiary(),
            new EcoComLegalGuardian(),
        ];
        $this->select = "*";
    }
    public function search($ci)
    {
        if (empty($ci)) {
            return new Person();
        }
        $this->getDefaults();
        $person = null;
        foreach ($this->tables as $table) {
            $query = $table->where('identity_card', $ci)->select($this->select);

            // Si el modelo tiene definida la relación address, la cargamos
            if (method_exists($table, 'address')) {
                $query->with([
                    'address' => function ($q) {
                        $q->orderBy('id', 'desc');
                    }
                ]);
            }

            $person = $query->first();

            if ($person) {
                if ($person->relationLoaded('address')) {
                    $person->setRelation('address', $person->address->first());
                }
                break;
            }
        }

        $operson = new Person();
        $operson->parsePerson($person);
        return $operson;
    }
    public function searchAjax(Request $request)
    {
        $this->getDefaults();
        return json_encode($this->search($request->ci));
    }
    public function searchAjaxOnlyAffiliate(Request $request)
    {
        if ($request->has('nup')) {
            $affiliate = Affiliate::find($request->nup);
            if ($affiliate) {
                $ci = $affiliate->identity_card;
            } else {
                $ci = null;
            }
        } else {
            $ci = $request->identity_card;
        }
        $eco_com = null;
        $affiliate = null;
        $affiliate_devolutions = [];
        $affiliate_observations = collect([]);
        $affiliate_observations_exclude = [];
        $affiliate_observations_amortizable = [];
        $affiliate_observations_exclude_rectifiable = collect([]);
        $other_observations = collect([]);
        $eco_com_beneficiary = new EcoComBeneficiary();
        $has_doble_perception = false;
        if (Util::isDoblePerceptionEcoCom($ci) && $request->one_time == true ) {
            $has_doble_perception = true;
            return response()->json(compact('has_doble_perception'), 200);
        }
        if ($request->has_doble_perception == true) {
            if ($request->type == 1) {
                $affiliate = Affiliate::where('identity_card', $ci)->with(['degree:id,name', 'category:id,name', 'pension_entity:id,name'])->select('id', 'category_id', 'degree_id', 'pension_entity_id', 'date_entry', 'identity_card', 'city_identity_card_id', 'first_name', 'second_name', 'last_name', 'mothers_last_name', 'surname_husband','gender')->first();
            } else {
                $eco_com_beneficiary = EcoComBeneficiary::leftJoin('economic_complements', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')
                    ->leftJoin('eco_com_procedures', 'economic_complements.eco_com_procedure_id', '=', 'eco_com_procedures.id')
                    ->leftJoin('eco_com_modalities', 'eco_com_modalities.id', '=', 'economic_complements.eco_com_modality_id')
                    ->leftJoin('procedure_modalities', "procedure_modalities.id", '=', 'eco_com_modalities.procedure_modality_id')
                    ->where('eco_com_applicants.identity_card', $ci)
                    ->where('procedure_modalities.id', '<>', 29)
                    ->select('eco_com_applicants.*')
                    ->orderBYDesc('eco_com_procedures.year')
                    ->orderBYDesc('eco_com_procedures.semester')
                    ->get()
                    ->first();
                $affiliate = $eco_com_beneficiary->economic_complement->affiliate()->select('id', 'category_id', 'degree_id', 'pension_entity_id', 'date_entry', 'identity_card', 'city_identity_card_id', 'first_name', 'second_name', 'last_name', 'mothers_last_name', 'surname_husband','gender')->first();
            }
        } else {
            $eco_com_beneficiary = EcoComBeneficiary::leftJoin('economic_complements', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')
                ->leftJoin('eco_com_procedures', 'economic_complements.eco_com_procedure_id', '=', 'eco_com_procedures.id')
                ->leftJoin('eco_com_modalities', 'eco_com_modalities.id', '=', 'economic_complements.eco_com_modality_id')
                ->leftJoin('procedure_modalities', "procedure_modalities.id", '=', 'eco_com_modalities.procedure_modality_id')
                ->where('eco_com_applicants.identity_card', $ci)
                ->select('eco_com_applicants.*')
                ->orderBYDesc('eco_com_procedures.year')
                ->orderBYDesc('eco_com_procedures.semester')
                ->get()
                ->first();
            if (!$eco_com_beneficiary) {
                $affiliate = Affiliate::where('identity_card', $ci)->with(['degree:id,name', 'category:id,name', 'pension_entity:id,name'])->select('id', 'category_id', 'degree_id', 'pension_entity_id', 'date_entry', 'identity_card', 'city_identity_card_id', 'first_name', 'second_name', 'last_name', 'mothers_last_name', 'surname_husband','gender')->first();
            }else{
                $affiliate = $eco_com_beneficiary->economic_complement->affiliate()->with(['degree:id,name', 'category:id,name', 'pension_entity:id,name'])->select('id', 'category_id', 'degree_id', 'pension_entity_id', 'date_entry', 'identity_card', 'city_identity_card_id', 'first_name', 'second_name', 'last_name', 'mothers_last_name', 'surname_husband','gender')->first();
            }
        }
        if (!$eco_com_beneficiary) {
            $eco_com_beneficiary = new EcoComBeneficiary();
        }
        $eco_com_beneficiary->full_name = $eco_com_beneficiary->fullName();
        $eco_com_beneficiary->ci_with_ext = $eco_com_beneficiary->ciWithExt();
        $due_date = $eco_com_beneficiary->due_date ?? Carbon::now()->subDay();
        $due_date = Util::verifyBarDate($due_date) ? Util::parseBarDate($due_date) : $due_date;
        $eco_com_beneficiary->valid_due_date = $eco_com_beneficiary->is_duedate_undefined ? true : $due_date > Carbon::now();
        if ($affiliate) {
            $affiliate->full_name = $affiliate->fullName();
            $affiliate->ci_with_ext = $affiliate->ciWithExt();
            $due_date = $affiliate->due_date ?? Carbon::now()->subDay();
            $affiliate->valid_due_date = $affiliate->is_duedate_undefined ? true : $due_date > Carbon::now();
            $affiliate->degree_name = $affiliate->degree->name ?? '';
            $affiliate->category_percentage = $affiliate->category->name ?? '';
            $affiliate->pension_entity_name = $affiliate->pension_entity->name ?? '';
            // !! TODO borrar id 33 y 35 despues de borrar las observaciones
            // Tipo A
            $affiliate_observations_exclude = $affiliate->observations()->whereNull('deleted_at')->whereIn('id', ObservationType::where('description', 'like', 'Denegado')->where('type', 'like', 'A')->get()->pluck('id'))->get();
            // Lista
            $affiliate_observations_exclude_rectifiable = $affiliate->observations()->whereNull('deleted_at')->whereIn('id', ObservationType::where('description', 'like', 'Denegado')->where('type', 'like', 'AT')->get()->pluck('id'))->get();
            $affiliate_devolutions = $affiliate->devolutions()->with('observation_type:id,name,type')->get();
            $affiliate_observations = $affiliate->observations()->whereNull('deleted_at')->whereIn('id', ObservationType::whereIn('description', ['Subsanable', 'Amortizable'])->get()->pluck('id'))->get();
            $eco_com = $affiliate->economic_complements()->select('id', 'code', 'total','eco_com_procedure_id', 'eco_com_modality_id', 'eco_com_state_id', 'aps_disability')->with([
                'eco_com_modality:id,name,shortened,procedure_modality_id',
                'eco_com_state:id,name',
                'eco_com_beneficiary',
                'eco_com_procedure:id,semester,year'
            ])->orderByDesc(DB::raw("regexp_replace(split_part(code, '/',3),'\D','','g')::integer"))
                ->orderByDesc(DB::raw("split_part(code, '/',2)"))
                ->orderByDesc(DB::raw("split_part(code, '/',1)::integer"))
                ->take(3)
                ->get();
            if ($eco_com->count() > 0) {
                if ($eco_com->first()->aps_disability > 0) {
                    $other_observations->push(['value'=>'El ultimo Trámite tuvo concurrencia.']);
                }
                $temp_ben = $eco_com->first()->eco_com_beneficiary;
                if ($temp_ben) {
                    $due_date = $temp_ben->due_date ?? Carbon::now()->subDay();
                    $due_date = Util::verifyBarDate($due_date) ? Util::parseBarDate($due_date) : $due_date;
                    $valid_due_date = $temp_ben->is_duedate_undefined ? true : $due_date > Carbon::now();
                    if (!$valid_due_date) {
                        if ($temp_ben->due_date) {
                            $other_observations->push(['value'=>'La fecha de vencimiento del CI ya fue vencida']);
                        }else{
                            $other_observations->push(['value'=>'La fecha de vencimiento del CI No esta Registrada']);
                        }
                    }
                }
                if($affiliate->stop_eco_com_consecutively()){
                    $other_observations->push(['value'=>'Beneficiario dejo de solicitar por dos semestres o mas (Debe solicitar rehabilitación).']);
                }
            }
        }

        $data = [
            'affiliate' => $affiliate,
            'affiliate_observations_exclude' =>$affiliate_observations_exclude,
            'affiliate_observations' =>array_merge($affiliate_observations->toArray(), $affiliate_observations_exclude_rectifiable->toArray()),
            'affiliate_devolutions' =>$affiliate_devolutions,
            'other_observations' =>$other_observations,
            'eco_com_beneficiary' => $eco_com_beneficiary,
            'eco_com' => $eco_com,
            'has_doble_perception' => $has_doble_perception
        ];
        return $data;
    }
}
class Person
{
    var $id;
    var $first_name;
    var $second_name;
    var $last_name;
    var $mothers_last_name;
    var $identity_card;
    var $kinship;
    var $phone_number;
    var $cell_phone_number;
    var $surname_husband;
    var $city_identity_card;
    var $class;
    var $type;
    var $gender;
    var $birth_date;
    var $due_date;
    var $is_duedate_undefined;
    var $city_birth_id;
    var $civil_status;
    var $address;
    public function parsePerson($obj)
    {
        $this->id = $obj->id ?? '';
        $this->first_name = $obj->first_name ?? '';
        $this->second_name = $obj->second_name ?? '';
        $this->last_name = $obj->last_name ?? '';
        $this->mothers_last_name = $obj->mothers_last_name ?? '';
        $this->identity_card = $obj->identity_card ?? '';
        $this->kinship_id = $obj->kinship_id ?? null;
        $this->phone_number = $obj ? $this->parsePhone($obj->phone_number) : '';
        $this->cell_phone_number = $obj ? $this->parsePhone($obj->cell_phone_number) : '';
        $this->surname_husband = $obj->surname_husband ?? '';
        $this->class = $obj ? get_class($obj) : 'desconocido';
        $this->type = $this->getClassObject();
        $this->city_identity_card_id = $obj->city_identity_card_id ?? null;
        $this->gender = $obj->gender ?? '';
        $this->birth_date = $obj->birth_date ?? '';
        $this->due_date = $obj->due_date ?? '';
        $this->is_duedate_undefined = $obj->is_duedate_undefined ?? false;
        $this->city_birth_id = $obj->city_birth_id ?? null;
        $this->civil_status = $obj->civil_status ?? null;
        $this->address = $obj->address ?? [];
    }
    function __toString()
    {
        return $this->last_name . " " . $this->first_name;
    }
    public function parsePhone($phones)
    {
        $array_phone = [];
        foreach (explode(',', $phones) as $phone) {
            $json_phone = new \stdClass;
            $json_phone->value = $phone;
            array_push($array_phone, $json_phone);
        }
        return $array_phone;
    }
    private function getClassObject()
    {
        switch ($this->class) {
            case "Muserpol\Models\Affiliate":
                return "afiliado";
            case "Muserpol\Models\RetirementFund\RetFunBeneficiary":
                return "beneficiario";
            case "Muserpol\Models\Spouse":
                return "esposa";
            case "Muserpol\Models\RetirementFund\RetFunLegalGuardian":
                return "apoderado";
            case "Muserpol\Models\RetirementFund\RetFunAdvisor":
                return "tutor";
            default:
                return  "No encontrado";
        }
    }
}
