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
            new RetFunLegalGuardian(),
            new RetFunAdvisor(),
            new EcoComBeneficiary(),
            new EcoComLegalGuardian(),
        ];
        $this->select = "*";
    }
    public function search($ci)
    {
        if ($ci == '')
            return new Person();
        $this->getDefaults();
        foreach ($this->tables as $table) {
            $person = $table->where('identity_card', $ci)->select($this->select)->first();
            if (isset($person->id))
                break;
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
        $ci = $request->ci;
        $eco_com = null;
        $affiliate = null;
        $affiliate_observations = [];
        $eco_com_beneficiary = new EcoComBeneficiary();
        $has_doble_perception = false;
        logger($request->all());
        if (Util::isDoblePerceptionEcoCom($ci) && $request->one_time == true ) {
            logger("entre");
            $has_doble_perception = true;
            return response()->json(compact('has_doble_perception'), 200);
        }
        if ($request->has_doble_perception == true) {
            logger("has DOble ");
            if ($request->type == 1) {
                $affiliate = Affiliate::where('identity_card', $ci)->first();
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
                logger("foun beneficiary in code");
                logger($eco_com_beneficiary->economic_complement->code);
                $affiliate = $eco_com_beneficiary->economic_complement->affiliate;
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
                $affiliate = Affiliate::where('identity_card', $ci)->first();
                logger('Found in affiliate');
            }else{
                logger('Found in eco_com_beneficiary');
                $affiliate = $eco_com_beneficiary->economic_complement->affiliate;
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
            $affiliate_observations_exclude = $affiliate->observations()->where('enabled', false)->whereIn('id', ObservationType::where('description', 'like', 'Denegado')->get()->pluck('id'))->get();
            $affiliate_observations = $affiliate->observations()->where('enabled', false)->whereIn('id', ObservationType::whereIn('description', ['Subsanable', 'Amortizable'])->get()->pluck('id'))->get();
            $eco_com = $affiliate->economic_complements()->with([
                'eco_com_modality:id,name,shortened,procedure_modality_id',
                'eco_com_state:id,name'
            ])->orderByDesc(DB::raw("regexp_replace(split_part(code, '/',3),'\D','','g')::integer"))
                ->orderByDesc(DB::raw("split_part(code, '/',2)"))
                ->orderByDesc(DB::raw("split_part(code, '/',1)::integer"))
                ->take(2)
                ->get();
        }
        $data = [
            'affiliate' => $affiliate,
            'affiliate_observations_exclude' =>$affiliate_observations_exclude,
            'affiliate_observations' =>$affiliate_observations,
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
