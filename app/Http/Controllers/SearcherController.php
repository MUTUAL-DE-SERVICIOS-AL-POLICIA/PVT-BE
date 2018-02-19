<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Spouse;
use Muserpol\Models\RetirementFund\RetFunBeneficiary;
use Muserpol\Models\RetirementFund\RetFunLegalGuardian;
use Muserpol\Models\RetirementFund\RetFunAdvisor;
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
     public function parsePerson($obj){
         $this->id = $obj->id ?? '';
         $this->first_name = $obj->first_name ?? '';
         $this->second_name = $obj->second_name ?? '';
         $this->last_name = $obj->last_name ?? '';
         $this->mothers_last_name =$obj->mothers_last_name ?? '';
         $this->identity_card = $obj->identity_card ?? '';
         $this->kinship_id = $obj->kinship_id ?? null;
         $this->phone_number = $obj->phone_number ?? '';
         $this->cell_phone_number = $obj->cell_phone_number ?? '';
         $this->surname_husband = $obj->surname_husband ?? '';
         $this->class = get_class($obj) ?? 'desconocido';
         $this->type = $this->getClassObject();
         $this->city_identity_card_id = $obj->city_identity_card_id ?? null;
         $this->gender = $obj->gender ?? '';         
         $this->birth_date = $obj->birth_date ?? '';
     }
     function __toString() {
         return $this->last_name." ".$this->first_name;
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
