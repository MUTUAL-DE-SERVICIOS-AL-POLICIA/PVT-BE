<template>
    <div >

        <div class="row" :id="`footerCreateBeneficiaries${index}`">
            <div class="col-md-12">
                <div  class="pull-left">
                    <legend >Beneficiario {{beneficiary.type=='S'?'Solicitante':''}}{{solicitante?'Solicitante':''}} </legend>
                </div>
                <div class="text-right" v-if="editable&&beneficiary.type!='S'?true:false">
                    <button class="btn btn-danger" type="button" v-on:click= "remove"> <i class="fa fa-trash" ></i> </button>
                </div>
            </div>
        </div>
        <br>
         <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Cédula de Identidad</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" v-model.trim="beneficiary.identity_card" ref="identitycard" name="beneficiary_identity_card[]" class="form-control" :disabled="!editable">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" @click="searchBeneficiary" role="button" :disabled="!editable"><i class="fa fa-search"></i></button>
                        </span>
                    </div><!-- /input-group -->
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
                    <input type="text" v-model.trim="beneficiary.surname_husband" name="beneficiary_surname_husband[]" class="form-control" :disabled="!editable">
                </div>
            </div>
             <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Genero</label>
                </div>
                <div class="col-md-8">
                    <select name="beneficiary_gender[]" id="" v-model.trim="beneficiary.gender" class="form-control" :disabled="!editable">
                        <option :value="null"></option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
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
                    <input type="text" v-date v-model.trim="beneficiary.birth_date" name="beneficiary_birth_date[]" class="form-control" :disabled="!editable">
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
        <br>
        <div class="row" v-if="beneficiary.type == 'S'">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Teléfono del Solicitante</label>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-2" v-if="editable">
                            <button class="btn btn-success" type="button" @click="addPhoneNumber"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="col-md-10">
                            <div v-for="(phone,index) in beneficiary.phone_number" :key="'phone-'+index">
                                <div class="input-group">
                                    <input type="text" name="beneficiary_phone_number[]" v-model.trim="beneficiary.phone_number[index]" :key="index" class="form-control" data-phone="true" :disabled="!editable">
                                    <span class="input-group-btn" v-if="editable">
                                        <button class="btn btn-danger" v-show="beneficiary.phone_number.length > 1" @click="deletePhoneNumber(index)" type="button"><i class="fa fa-trash"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Celular del Solicitante</label>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-2" v-if="editable">
                            <button class="btn btn-success" type="button" @click="addCellPhoneNumber"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="col-md-10">
                            <div v-for="(cell_phone,index) in beneficiary.cell_phone_number" :key="`cellphone-${index}`">
                                <div class="input-group">
                                    <input type="text" name="beneficiary_cell_phone_number[]" v-model.trim="beneficiary.cell_phone_number[index]" :key="index" class="form-control" data-cell-phone="true" :disabled="!editable">
                                    <span class="input-group-btn" v-if="editable">
                                        <button class="btn btn-danger" v-show="beneficiary.cell_phone_number.length > 1" @click="deleteCellPhoneNumber(index)" type="button"><i class="fa fa-trash"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>
        <div class="row" v-if="beneficiary.type == 'S'">
            <div class="col-md-4">
                <div class="col-md-4">
                    <label class="control-label">Zona</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="beneficiary_zone[]" v-model.trim="beneficiary.address[0].zone" class="form-control" :disabled="!editable">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="col-md-4">
                    <label class="control-label">Calle</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="beneficiary_street[]" v-model.trim="beneficiary.address[0].street" class="form-control" :disabled="!editable">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="col-md-4">
                    <label class="control-label">Numero</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="beneficiary_number_address[]" v-model.trim="beneficiary.address[0].number_address" class="form-control" :disabled="!editable">
                    </div>
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
import {
  getGender,
  cellPhoneInputMaskAll,
  phoneInputMaskAll
} from "../../helper.js";
export default {
  props: [
    "kinships",
    "cities",
    "beneficiary",
    "editable",
    "removable",
    "solicitante",
    "index"
  ],
  data() {
    return {
      // removable_beneficiary: true
    };
  },
  created() {
    //  Parche
  },
  mounted() {
    //this.$refs.identity_card.focus();
    phoneInputMaskAll();
    cellPhoneInputMaskAll();
  },
  methods: {
    addPhoneNumber() {
      if (this.beneficiary.phone_number.length > 0) {
        let last_phone = this.beneficiary.phone_number[
          this.beneficiary.phone_number.length - 1
        ];
        if (last_phone && !last_phone.includes("_")) {
          this.beneficiary.phone_number.push(null);
        }
      } else {
        this.beneficiary.phone_number.push(null);
      }
      setTimeout(() => {
        phoneInputMaskAll();
      }, 500);
    },
    deletePhoneNumber(index) {
      this.beneficiary.phone_number.splice(index, 1);
      if (this.beneficiary.phone_number.length < 1) this.addPhoneNumber();
    },
    addCellPhoneNumber() {
      if (this.beneficiary.cell_phone_number.length > 0) {
        let last_phone = this.beneficiary.cell_phone_number[
          this.beneficiary.cell_phone_number.length - 1
        ];
        if (last_phone && !last_phone.includes("_")) {
          this.beneficiary.cell_phone_number.push(null);
        }
      } else {
        this.beneficiary.cell_phone_number.push(null);
      }
      setTimeout(() => {
        cellPhoneInputMaskAll();
      }, 500);
    },
    deleteCellPhoneNumber(index) {
      this.beneficiary.cell_phone_number.splice(index, 1);
      if (this.beneficiary.cell_phone_number.length < 1)
        this.addCellPhoneNumber();
    },
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
      this.beneficiary.city_identity_card_id = data.city_identity_card_id;
      this.beneficiary.birth_date = data.birth_date;
      this.beneficiary.kinship_id = data.kinship_id;
      this.beneficiary.gender = data.gender;
      this.beneficiary.state = !!data.state ? data.state : false;
    },
    getGenderBeneficiary(value) {
      return getGender(value);
    }
  },
  computed: {
    beneficiaryAge() {
      if (this.beneficiary.birth_date) {
        return moment().diff(this.beneficiary.birth_date, "years");
      }
      return null;
    }
  }
};
</script>
<style>
input.mediumCheckBox {
  width: 20px;
  height: 20px;
}
</style>
