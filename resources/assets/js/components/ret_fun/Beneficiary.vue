<template>
<<<<<<< HEAD
    <div >
            
        <div class="row" >
            <div class="col-md-12">
                <div  class="pull-left">
                    <legend >Beneficiario {{beneficiary.type=='S'?'Solicitante':''}}{{solicitante?'Solicitante':''}} </legend>
                </div>
                <div class="text-right" v-if="editable&&beneficiary.type!='S'?true:false">
                    <button class="btn btn-danger" type="button" v-on:click= "remove"> <i class="fa fa-trash" ></i> </button>
                </div>
=======
<div>
    <div v-if="readOnly">
        <div class="row"> 
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Cedula de identidad:</dt> <dd>{{ beneficiary.identity_card }} {{ !!beneficiary.city_identity_card ? beneficiary.city_identity_card.first_shortened : '' }} </dd>
                    <dt>Primer Nombre:</dt> <dd>{{ beneficiary.first_name }}</dd>
                    <dt>Segundo Nombre:</dt> <dd>{{ beneficiary.second_name }}</dd>
                    <dt>Apellido Paterno:</dt> <dd>{{ beneficiary.last_name }}</dd>
                    <dt>Apellido Materno:</dt> <dd>{{ beneficiary.mothers_last_name }}</dd>
                    <dt v-show="beneficiary.gender === 'F'">Apellido de Casada:</dt> <dd v-show="beneficiary.gender === 'F'">{{ beneficiary.surname_husband }}</dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Parentesco:</dt> <dd> {{ !!beneficiary.kinship ? beneficiary.kinship.name : '' }} </dd>
                    <dt>Generos:</dt> <dd>{{ getGender(beneficiary.gender) }}</dd>
                    <dt>Estado Civil:</dt> <dd>{{ beneficiary.civil_status }}</dd>
                    <dt>Fecha de Nacimiento:</dt> <dd>{{ beneficiary.birth_date }}</dd>
                    <dt>Edad:</dt> <dd> {{ beneficiaryAge }} </dd>
                    <dt>Telefono:</dt> <dd>{{ beneficiary.phone_number }}</dd>
                    <dt>Celular:</dt> <dd>{{ beneficiary.cell_phone_number }}</dd>
                </dl>
            </div>
        </div>
        <hr>
    </div>
    <div v-else-if="editable">
        <div class="row">
            <div class="col-sm-10"></div>
            <div class="col-sm-2">
                <button class="btn btn-danger" type="button" v-on:click= "remove"> <i class="fa fa-trash"></i> </button>
>>>>>>> parent of 72d684a... added, getGender on helper.js, added more validations on first second step create ret fun, and bug fixes edit beneficiaries on show retfun
            </div>
        </div>
        <br>
         <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Carnet de Identidad</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" v-model.trim="beneficiary.identity_card" ref="identity_card" name="beneficiary_identity_card[]" class="form-control" :disabled="!editable">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" @click="searchBeneficiary" role="button" :disabled="!editable"><i class="fa fa-search"></i></button>
                        </span>
                    </div><!-- /input-group -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Ciudad de Expedición</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control" v-model.trim="beneficiary.city_identity_card_id" name="beneficiary_city_identity_card[]" :disabled="!editable">
                        <option :value="null"></option>
                        <option v-for="city in cities" :key="city.id" :value="city.id" >{{ city.name }}</option>
                    </select>
                </div>
            </div>
        </div>
        <br>
        <div class="row" >
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Primer Nombre</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.first_name" name="beneficiary_first_name[]"  class="form-control" :disabled="!editable">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Segundo Nombre</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.second_name" name="beneficiary_second_name[]" class="form-control" :disabled="!editable">
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Apellido Paterno</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.last_name" name="beneficiary_last_name[]" class="form-control" :disabled="!editable">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Apellido Materno</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.mothers_last_name" name="beneficiary_mothers_last_name[]" class="form-control" :disabled="!editable">
                </div>
            </div>
        </div>
        <br>
        <div class="row">
             <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Apellido de Casada</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.surname_husband" name="surname_husband[]" class="form-control" :disabled="!editable">
                </div>
            </div>
             <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Genero</label>
                </div>
                <div class="col-md-8">
                    <select name="gender[]" id="" v-model.trim="beneficiary.gender" class="form-control" :disabled="!editable">
                        <option :value="null"></option>
                        <option value="M">Masculino</option>
                        <option value="F">Fenemino</option>
                    </select>
                </div>
            </div>
        </div>
       <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Fecha de Nacimiento</label>
                </div>
                <div class="col-md-8">
                    <input type="date" v-model.trim="beneficiary.birth_date" name="beneficiary_birth_date[]" class="form-control" :disabled="!editable">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Parentesco</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control " v-model.trim="beneficiary.kinship_id" name="beneficiary_kinship[]" :disabled="!editable">
                        <option :value="null"></option>
                        <option v-for="kinship in kinships" :key="beneficiary.id + ''+kinship.id " :value="kinship.id">{{kinship.name}}</option>
                    </select>
                </div>
            </div>
            
        </div>
        <div class="hr-line-dashed"></div>
            <div class="row"> 
                <div class="col-md-6">
                    <div class="col-md-4">
                        <strong>Documentos Completos:</strong>
                    </div>
                    <div class="col-md-2">
                        <input type="checkbox" v-model.trim="beneficiary.state" name="beneficiary_state[]" :value="beneficiary.state" :checked="beneficiary.state" class="form-control mediumCheckBox" :disabled="!editable">     
                    </div>
                    <div class="col-md-6"></div>
                </div>
                <div class="col-md-6">
                    
                </div>
            </div>
        <div class="hr-line-dashed"></div>
    </div>

</template>
<script>
import { getGender } from '../../helper.js'
export default {
  props: ["kinships", "cities", "beneficiary", "editable", "removable","solicitante" ],
  data() {
    return {
        // removable_beneficiary: true
    };
  },
  created(){
    //  Parche
  },
  mounted() {
    //this.$refs.identity_card.focus();

  },
  methods: {
    remove() {
      this.$emit("remove");
    },
    searchBeneficiary: function() {
      let ci = this.beneficiary.identity_card;
      axios
        .get("/search_ajax", {
          params: {
            ci
          }
        })
        .then(response => {
          let data = response.data;
          console.log(data);
          this.setDataBeneficiary(data);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    setDataBeneficiary(data) {
      this.beneficiary.first_name = data.first_name;
      this.beneficiary.second_name = data.second_name;
      this.beneficiary.last_name = data.last_name;
      this.beneficiary.mothers_last_name = data.mothers_last_name;
      this.beneficiary.surname_husband = data.surname_husband;
      this.beneficiary.identity_card = data.identity_card;
    //   if(data.city_identity_card_id!=null){
    //     this.beneficiary.city_identity_card_id = data.city_identity_card_id;
    //   }
    //   else 
      this.beneficiary.city_identity_card_id = 0;
      this.beneficiary.birth_date = data.birth_date;
      this.beneficiary.kinship_id = data.kinship_id;
      this.beneficiary.gender = data.gender;
<<<<<<< HEAD
      this.beneficiary.state = data.state;
    },
    getGenderBeneficiary(value){
        return getGender(value);
=======
>>>>>>> parent of 72d684a... added, getGender on helper.js, added more validations on first second step create ret fun, and bug fixes edit beneficiaries on show retfun
    }
  },
  computed:{
      beneficiaryAge(){
          if (this.beneficiary.birth_date) {
              return moment().diff(this.beneficiary.birth_date, 'years');
          }
          return null;
      }
  }
};
</script>
<style>
input.mediumCheckBox
{
width: 20px;
height: 20px;
}

</style>

