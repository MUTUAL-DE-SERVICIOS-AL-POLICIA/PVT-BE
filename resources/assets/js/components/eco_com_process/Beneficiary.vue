<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="pull-left">
          <legend>Beneficiario</legend>
        </div>
        <div class="text-right" v-if="editable&&beneficiary.type!='S'?true:false">
          <button class="btn btn-danger" type="button" v-on:click="remove">
            <i class="fa fa-trash"></i>
          </button>
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
            <input
              type="text"
              v-model.trim="beneficiary.identity_card"
              ref="identitycard"
              name="beneficiary_identity_card[]"
              class="form-control"
              :disabled="!editable"
            >
            <span class="input-group-btn">
              <button
                class="btn btn-primary"
                type="button"
                @click="searchBeneficiary"
                role="button"
                :disabled="!editable"
              >
                <i class="fa fa-search"></i>
              </button>
            </span>
          </div>
          <!-- /input-group -->
        </div>
      </div>
      <div class="col-md-6">
        <div class="col-md-4">
          <label class="control-label">Ciudad de Expedición</label>
        </div>
        <div class="col-md-8">
          <select
            class="form-control"
            v-model.trim="beneficiary.city_identity_card_id"
            name="beneficiary_city_identity_card[]"
            :disabled="!editable"
          >
            <option :value="null"></option>
            <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
          </select>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-6">
        <div class="col-md-4">
          <label class="control-label">Primer Nombre</label>
        </div>
        <div class="col-md-8">
          <input
            type="text"
            v-model.trim="beneficiary.first_name"
            name="beneficiary_first_name[]"
            class="form-control"
            :disabled="!editable"
          >
        </div>
      </div>
      <div class="col-md-6">
        <div class="col-md-4">
          <label class="control-label">Segundo Nombre</label>
        </div>
        <div class="col-md-8">
          <input
            type="text"
            v-model.trim="beneficiary.second_name"
            name="beneficiary_second_name[]"
            class="form-control"
            :disabled="!editable"
          >
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
          <input
            type="text"
            v-model.trim="beneficiary.last_name"
            name="beneficiary_last_name[]"
            class="form-control"
            :disabled="!editable"
          >
        </div>
      </div>
      <div class="col-md-6">
        <div class="col-md-4">
          <label class="control-label">Apellido Materno</label>
        </div>
        <div class="col-md-8">
          <input
            type="text"
            v-model.trim="beneficiary.mothers_last_name"
            name="beneficiary_mothers_last_name[]"
            class="form-control"
            :disabled="!editable"
          >
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
          <input
            type="text"
            v-model.trim="beneficiary.surname_husband"
            name="beneficiary_surname_husband[]"
            class="form-control"
            :disabled="!editable"
          >
        </div>
      </div>
      <div class="col-md-6">
        <div class="col-md-4">
          <label class="control-label">Genero</label>
        </div>
        <div class="col-md-8">
          <select
            name="beneficiary_gender[]"
            id
            v-model.trim="beneficiary.gender"
            class="form-control"
            :disabled="!editable"
          >
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
          <input
            type="text"
            v-date
            v-model.trim="beneficiary.birth_date"
            name="beneficiary_birth_date[]"
            class="form-control"
            :disabled="!editable"
          >
        </div>
      </div>
      <div class="col-md-6">
        <div class="col-md-4">
          <label class="control-label">Parentesco</label>
        </div>
        <div class="col-md-8">
          <select
            class="form-control"
            v-model.trim="beneficiary.kinship_id"
            name="beneficiary_kinship[]"
            :disabled="!editable"
          >
            <option :value="null"></option>
            <option
              v-for="kinship in kinships"
              :key="beneficiary.id + ''+kinship.id "
              :value="kinship.id"
            >{{kinship.name}}</option>
          </select>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-6">
        <div class="col-md-4">
          <label class="control-label">Teléfono del Solicitante</label>
        </div>
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-2" v-if="editable">
              <button class="btn btn-success" type="button" @click="addPhoneNumber">
                <i class="fa fa-plus"></i>
              </button>
            </div>
            <div class="col-md-10">
              <div v-for="(phone,index) in beneficiary.phone_number" :key="'phone-'+index">
                <div class="input-group">
                  <input
                    type="text"
                    name="beneficiary_phone_number[]"
                    v-model.trim="beneficiary.phone_number[index]"
                    :key="index"
                    class="form-control"
                    v-phone
                    :disabled="!editable"
                  >
                  <span class="input-group-btn" v-if="editable">
                    <button
                      class="btn btn-danger"
                      v-show="beneficiary.phone_number.length > 1"
                      @click="deletePhoneNumber(index)"
                      type="button"
                    >
                      <i class="fa fa-trash"></i>
                    </button>
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
              <button class="btn btn-success" type="button" @click="addCellPhoneNumber">
                <i class="fa fa-plus"></i>
              </button>
            </div>
            <div class="col-md-10">
              <div
                v-for="(cell_phone,index) in beneficiary.cell_phone_number"
                :key="`cellphone-${index}`"
              >
                <div class="input-group">
                  <input
                    type="text"
                    name="beneficiary_cell_phone_number[]"
                    v-model.trim="beneficiary.cell_phone_number[index]"
                    :key="index"
                    class="form-control"
                    v-cell-phone
                    :disabled="!editable"
                  >
                  <span class="input-group-btn" v-if="editable">
                    <button
                      class="btn btn-danger"
                      v-show="beneficiary.cell_phone_number.length > 1"
                      @click="deleteCellPhoneNumber(index)"
                      type="button"
                    >
                      <i class="fa fa-trash"></i>
                    </button>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="hr-line-dashed"></div>
    <div class="row">
      <div class="col-md-3">
        <div class="col-md-4">
          <label class="control-label">Ciudad</label>
        </div>
        <div class="col-md-8">
          <select
            class="form-control"
            v-model.trim="beneficiary.address[0].city_address_id"
            name="beneficiary_city_address_id[]"
            :disabled="!editable"
          >
            <option :value="null"></option>
            <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="col-md-4">
          <label class="control-label">Zona</label>
        </div>
        <div class="col-md-8">
          <div class="input-group">
            <input
              type="text"
              name="beneficiary_zone[]"
              v-model.trim="beneficiary.address[0].zone"
              class="form-control"
              :disabled="!editable"
            >
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="col-md-4">
          <label class="control-label">Calle</label>
        </div>
        <div class="col-md-8">
          <div class="input-group">
            <input
              type="text"
              name="beneficiary_street[]"
              v-model.trim="beneficiary.address[0].street"
              class="form-control"
              :disabled="!editable"
            >
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="col-md-4">
          <label class="control-label">Numero</label>
        </div>
        <div class="col-md-8">
          <div class="input-group">
            <input
              type="text"
              name="beneficiary_number_address[]"
              v-model.trim="beneficiary.address[0].number_address"
              class="form-control"
              :disabled="!editable"
            >
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="hr-line-dashed"></div>
    <div class="text-center" v-show="editable">
      <button class="btn btn-danger" type="button" @click="cancel">
        <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
        <span class="bold">Cancelar</span>
      </button>
      <button class="btn btn-primary" type="button" @click="update">
        <i class="fa fa-check-circle"></i>&nbsp;Guardar
      </button>
    </div>
  </div>
</template>
<script>
import { getGender } from "../../helper.js";
export default {
  props: ["kinships", "cities", "beneficiary"],
  data() {
    return {
      // removable_beneficiary: true
      editable: true,
      legalRepresentatives: [
        { id: 1, name: "Tutor(a)" },
        { id: 2, name: "Apoderado(a)" }
      ]
    };
  },
  created() {
    //  Parche
  },
  mounted() {
    //this.$refs.identity_card.focus();
    // dateInputMaskAll();
  },
  methods: {
    cancel() {},
    update() {
      let uri = `/eco_com_process/${this.beneficiary.eco_com_process_id}/update_beneficiary`;
      this.show_spinner = true;

      axios
        .patch(uri, this.beneficiary)
        .then(response => {
          this.editing = false;
          this.show_spinner = false;
          this.beneficiaries = response.data.beneficiaries;
          flash("Informacion del Afiliado Actualizada  " + response.data);
        })
        .catch(response => {
          this.show_spinner = false;
          this.beneficiaries = this.ben;
          flash(
            "Error al actualizar el afiliado: " + response.message,
            "error"
          );
        });
    },
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
    },
    setAdvisorData(data) {
      this.beneficiary.advisor_identity_card = data.identity_card;
      this.beneficiary.advisor_city_identity_card_id =
        data.city_identity_card_id;
      this.beneficiary.advisor_first_name = data.first_name;
      this.beneficiary.advisor_second_name = data.second_name;
      this.beneficiary.advisor_last_name = data.last_name;
      this.beneficiary.advisor_mothers_last_name = data.mothers_last_name;
      this.beneficiary.advisor_surname_husband = data.surname_husband;
      this.beneficiary.advisor_birth_date = data.birth_date;
      this.beneficiary.advisor_gender = data.gender;
      // phone.value
      // cell_phone.value
      this.beneficiary.advisor_name_court = data.name_court;
      this.beneficiary.advisor_resolution_number = data.resolution_number;
      this.beneficiary.advisor_resolution_date = data.resolution_date;
    },
    setLegalGuardianData(data) {
      this.beneficiary.legal_guardian_identity_card = data.identity_card;
      this.beneficiary.legal_guardian_city_identity_card_id =
        data.city_identity_card_id;
      this.beneficiary.legal_guardian_first_name = data.first_name;
      this.beneficiary.legal_guardian_second_name = data.second_name;
      this.beneficiary.legal_guardian_last_name = data.last_name;
      this.beneficiary.legal_guardian_mothers_last_name =
        data.mothers_last_name;
      this.beneficiary.legal_guardian_surname_husband = data.surname_husband;
      this.beneficiary.legal_guardian_gender = data.gender;
      this.beneficiary.legal_guardian_number_authority = data.number_authority;
      this.beneficiary.legal_guardian_notary_of_public_faith =
        data.notary_of_public_faith;
      this.beneficiary.legal_guardian_notary = data.notary;
      this.beneficiary.legal_guardian_date_authority = data.date_authority;
    },
    searchLegalRepresentative(type) {
      // type:
      // 1 => tutor
      // 2 => apoderado
      console.log("searching legal representative");
      let ci;
      switch (type) {
        case 1:
          ci = this.beneficiary.advisor_identity_card;
          break;
        case 2:
          ci = this.beneficiary.legal_guardian_identity_card;
          break;
        default:
          alert("error al buscar legal representative");
          break;
      }
      axios
        .get("/search_ajax", {
          params: {
            ci
          }
        })
        .then(response => {
          let data = response.data;
          setTimeout(() => {
            switch (type) {
              case 1:
                this.setAdvisorData(data);
                break;
              case 2:
                this.setLegalGuardianData(data);
                break;
              default:
                alert("error al guardar datos legal representative");
                break;
            }
          }, 300);
        })
        .catch(function(error) {
          console.log("Error searching legal guardian");
          console.log(error);
        });
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
