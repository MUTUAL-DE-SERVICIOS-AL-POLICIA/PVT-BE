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
class SearcherController
{
    private $tables;
    private $select;
    public function __construct($fields = "*") {
     $this->tables = [
         new Affiliate(),
         new Spouse(),
         new RetFunBeneficiary(),
         new RetFunLegalGuardian(),
         new RetFunAdvisor(),
        ];      
     $this->select = $fields;
    }
    private function getDefaults(){
        $this->tables = [
         new Affiliate(),
         new Spouse(),
         new RetFunBeneficiary(),
         new RetFunLegalGuardian(),
         new RetFunAdvisor(),
        ];      
        $this->select = "*";
    }
    public function search($ci)
    {                
        if($ci == '')
            return new Person();
        $this->getDefaults();                
        foreach ($this->tables as $table){
            $person = $table->where('identity_card',$ci)->select($this->select)->first();
            if(isset($person->id))
               break;                
        }        

        $operson = new Person();
        $operson->parsePerson($person);      
        return $operson;        
    }
    public function searchAjax(Request $request){
        $this->getDefaults();
        return json_encode($this->search($request->ci));        
    }
    public function searchAjaxOnlyAffiliate(Request $request){
        $ci = $request->ci;
        $affiliate = Affiliate::where('identity_card',$ci)->first();
        $eco_com = null;
        if($affiliate){
            $affiliate->full_name = $affiliate->fullName();
            $affiliate->ci_with_ext = $affiliate->ciWithExt();
            $affiliate->degree_name = $affiliate->degree->name ?? '';
            $affiliate->category_percentage = $affiliate->category->name ?? '';
            $affiliate->pension_entity_name= $affiliate->pension_entity->name ?? '';
            //!! TODO getLast
            $eco_com = $affiliate->economic_complements()->with([
                'eco_com_modality:id,name',
                'eco_com_state:id,name'
            ])->orderByDesc('id')->take(2)->get();
        }
        return array('affiliate' => $affiliate , 'eco_com'=>$eco_com );
    }
}
class Person{
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
     public function parsePerson($obj){
         $this->id = $obj->id ?? '';
         $this->first_name = $obj->first_name ?? '';
         $this->second_name = $obj->second_name ?? '';
         $this->last_name = $obj->last_name ?? '';
         $this->mothers_last_name =$obj->mothers_last_name ?? '';
         $this->identity_card = $obj->identity_card ?? '';
         $this->kinship_id = $obj->kinship_id ?? null;
         $this->phone_number = $this->parsePhone($obj->phone_number) ?? '';
         $this->cell_phone_number = $this->parsePhone($obj->cell_phone_number) ?? '';
         $this->surname_husband = $obj->surname_husband ?? '';
         $this->class = get_class($obj) ?? 'desconocido';
         $this->type = $this->getClassObject();
         $this->city_identity_card_id = $obj->city_identity_card_id ?? null;
         $this->gender = $obj->gender ?? '';                 
         $this->birth_date = $obj->birth_date ?? '';
         $this->due_date = $obj->due_date ?? '';
         $this->is_duedate_undefined = $obj->is_duedate_undefined ?? false;
     }
     function __toString() {
         return $this->last_name." ".$this->first_name;
     }
     public function parsePhone($phones)
     {
         $array_phone=[];
         foreach (explode(',', $phones) as $phone) {
            $json_phone = new \stdClass;
            $json_phone->value = $phone;
            array_push($array_phone, $json_phone);
         }
         return $array_phone;
     }
     private function getClassObject()
     {
         switch ($this->class){
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
             default :
                 return  "No encontrado";                 
         }
     }
    }
?>
