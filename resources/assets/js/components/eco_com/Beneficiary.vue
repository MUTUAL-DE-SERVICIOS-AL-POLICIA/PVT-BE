<template>
  <div class="col-lg-12">
    <div class="ibox">
      <div class="ibox-title">
        <h2 class="pull-left">Datos del Beneficiario</h2>
        <div class="ibox-tools">
          <button
            class="btn btn-primary"
            @click="edit()"
            data-toggle="tooltip"
            title="Editar"
            :disabled="!can('update_eco_com_beneficiary')"
            v-if="can('read_eco_com_beneficiary')"
          >
            <i class="fa fa-pencil"></i> Editar
          </button>
        </div>
      </div>
      <div class="ibox-content" v-if="can('read_eco_com_beneficiary')">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6">
                <div
                  class="form-group"
                  :class="{'has-error': errors.has('eco_com_beneficiary_identity_card') }"
                >
                  <div class="col-md-4">
                    <label class="control-label">Cédula de Identidad</label>
                  </div>
                  <div class="col-md-8">
                    <div class="input-group">
                      <input
                        type="text"
                        name="eco_com_beneficiary_identity_card"
                        v-model.trim="ecoComBeneficiary.identity_card"
                        class="form-control"
                        v-validate="'required'"
                        :disabled="editable"
                      >
                      <span class="input-group-btn">
                        <button
                          class="btn"
                          :class="errors.has('eco_com_beneficiary_identity_card') ? 'btn-danger' : 'btn-primary' "
                          type="button"
                          @click="searchEcoComBeneficiary()"
                          role="button"
                          :disabled="editable"
                        >
                          <i class="fa fa-search"></i>
                        </button>
                      </span>
                    </div>
                    <i
                      v-show="errors.has('ecoComBeneficiary.identity_card')"
                      class="fa fa-warning text-danger"
                    ></i>
                    <span
                      v-show="errors.has('ecoComBeneficiary.identity_card')"
                      class="text-danger"
                    >{{ errors.first('ecoComBeneficiary.identity_card') }}</span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div
                class="col-md-6"
                :class="{'has-error': errors.has('eco_com_beneficiary_first_name') }"
              >
                <div class="col-md-4">
                  <label class="control-label">Primer Nombre</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="eco_com_beneficiary_first_name"
                    v-model.trim="ecoComBeneficiary.first_name"
                    class="form-control"
                    v-validate="'required|alpha_space_quote'"
                    :disabled="editable"
                  >
                  <div v-show="errors.has('eco_com_beneficiary_first_name')">
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('eco_com_beneficiary_first_name') }}</span>
                  </div>
                </div>
              </div>
              <div
                class="col-md-6"
                :class="{'has-error': errors.has('eco_com_beneficiary_second_name')}"
              >
                <div class="col-md-4">
                  <label class="control-label">Segundo Nombre</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="eco_com_beneficiary_second_name"
                    v-model.trim="ecoComBeneficiary.second_name"
                    class="form-control"
                    v-validate="'alpha_space_quote'"
                    :disabled="editable"
                  >
                  <div v-show="errors.has('eco_com_beneficiary_second_name')">
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('eco_com_beneficiary_second_name') }}</span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div
                class="col-md-6"
                :class="{'has-error': errors.has('eco_com_beneficiary_last_name')}"
              >
                <div class="col-md-4">
                  <label class="control-label">Apellido Paterno</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="eco_com_beneficiary_last_name"
                    v-model.trim="ecoComBeneficiary.last_name"
                    class="form-control"
                    v-validate="'alpha_space_quote'"
                    :disabled="editable"
                  >
                  <div v-show="errors.has('eco_com_beneficiary_last_name')">
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('eco_com_beneficiary_last_name') }}</span>
                  </div>
                </div>
              </div>
              <div
                class="col-md-6"
                :class="{'has-error': errors.has('eco_com_beneficiary_mothers_last_name')}"
              >
                <div class="col-md-4">
                  <label class="control-label">Apellido Materno</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="eco_com_beneficiary_mothers_last_name"
                    v-model.trim="ecoComBeneficiary.mothers_last_name"
                    class="form-control"
                    v-validate="'alpha_space_quote'"
                    :disabled="editable"
                  >
                  <div v-show="errors.has('eco_com_beneficiary_mothers_last_name')">
                    <i class="fa fa-warning text-danger"></i>
                    <span
                      class="text-danger"
                    >{{ errors.first('eco_com_beneficiary_mothers_last_name') }}</span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div
                class="col-md-6"
                :class="{'has-error': errors.has('eco_com_beneficiary_surname_husband')}"
              >
                <div class="col-md-4">
                  <label class="control-label">Apellido de Casada</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="eco_com_beneficiary_surname_husband"
                    v-model.trim="ecoComBeneficiary.surname_husband"
                    class="form-control"
                    v-validate="'alpha_space_quote'"
                    :disabled="editable"
                  >
                  <div v-show="errors.has('eco_com_beneficiary_surname_husband')">
                    <i class="fa fa-warning text-danger"></i>
                    <span
                      class="text-danger"
                    >{{ errors.first('eco_com_beneficiary_surname_husband') }}</span>
                  </div>
                </div>
              </div>
              <div
                class="col-md-6"
                :class="{'has-error': errors.has('eco_com_beneficiary_gender') }"
              >
                <div class="col-md-4">
                  <label class="control-label">Genero</label>
                </div>
                <div class="col-md-8">
                  <select
                    class="form-control m-b"
                    name="eco_com_beneficiary_gender"
                    v-model.trim="ecoComBeneficiary.gender"
                    v-validate="'required'"
                    :disabled="editable"
                  >
                    <option :value="null"></option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                  </select>
                  <i
                    v-show="errors.has('eco_com_beneficiary_gender')"
                    class="fa fa-warning text-danger"
                  ></i>
                  <span
                    v-show="errors.has('eco_com_beneficiary_gender')"
                    class="text-danger"
                  >{{ errors.first('eco_com_beneficiary_gender') }}</span>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div
                class="col-md-6"
                :class="{'has-error': errors.has('eco_com_beneficiary_birth_date') }"
              >
                <div class="col-md-4">
                  <label class="control-label">Fecha de Nacimiento</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    v-date
                    class="form-control"
                    v-model.trim="ecoComBeneficiary.birth_date"
                    name="eco_com_beneficiary_birth_date"
                    v-validate="'date_format:dd/MM/yyyy|max_current_date'"
                    :disabled="editable"
                  >
                  <div>
                    <i
                      v-show="errors.has('eco_com_beneficiary_birth_date')"
                      class="fa fa-warning text-danger"
                    ></i>
                    <span class="text-danger">{{ errors.first('eco_com_beneficiary_birth_date') }}</span>
                  </div>
                </div>
              </div>
              <div
                class="col-md-6"
                :class="{'has-error': errors.has('eco_com_beneficiary_city_birth_id') }"
              >
                <div class="col-md-4">
                  <label class="control-label">Lugar de Nacimiento</label>
                </div>
                <div class="col-md-8">
                  <select
                    class="form-control"
                    name="eco_com_beneficiary_city_birth_id"
                    v-model.trim="ecoComBeneficiary.city_birth_id"
                    v-validate="'required'"
                    :disabled="editable"
                  >
                    <option :value="null"></option>
                    <option v-for="city in cities" :value="city.id" :key="city.id">{{ city.name }}</option>
                  </select>
                  <div v-show="errors.has('eco_com_beneficiary_city_birth_id')">
                    <i class="fa fa-warning text-danger"></i>
                    <span
                      class="text-danger"
                    >{{ errors.first('eco_com_beneficiary_city_birth_id') }}</span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <div class="col-md-4">
                  <label class="control-label">Estado Civil</label>
                </div>
                <div class="col-md-8">
                  <select
                    class="form-control"
                    name="eco_com_beneficiary_civil_status"
                    v-model.trim="ecoComBeneficiary.civil_status"
                    :disabled="editable"
                  >
                    <option :value="null"></option>
                    <option v-for="c in civilStatus" :value="c.id" :key="c.id">{{ c.name }}</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6" :class="{'has-error': errors.has('eco_com_beneficiary_nua') }">
                <div class="col-md-4">
                  <label class="control-label">CUA/NUA</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="eco_com_beneficiary_nua"
                    v-model.trim="ecoComBeneficiary.nua"
                    class="form-control"
                    :disabled="editable"
                  >
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div
                class="col-md-6"
                :class="{'has-error': errors.has('eco_com_beneficiary_due_date') && !ecoComBeneficiary.is_duedate_undefined }"
              >
                <div class="col-md-4">
                  <label class="control-label">Fecha de Vencimiento del CI</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    :disabled="ecoComBeneficiary.is_duedate_undefined || editable"
                    name="eco_com_beneficiary_due_date"
                    v-model.trim="ecoComBeneficiary.due_date"
                    class="form-control"
                    v-date
                    v-validate="'required|date_format:dd/MM/yyyy|max_due_date'"
                  >
                  <br>
                  <input
                    class="mediumCheckbox"
                    type="checkbox"
                    name="eco_com_beneficiary_is_duedate_undefined"
                    v-model="ecoComBeneficiary.is_duedate_undefined"
                    :disabled="editable"
                    id="eco_com_beneficiary_is_duedate_undefined"
                  >
                  <label
                    for="eco_com_beneficiary_is_duedate_undefined"
                    class="pointer v-middle"
                  >Indefinido</label>
                  <div
                    v-show="errors.has('eco_com_beneficiary_due_date') && !ecoComBeneficiary.is_duedate_undefined "
                  >
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('eco_com_beneficiary_due_date') }}</span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <div class="col-md-4">
                  <label class="control-label">Teléfono del Beneficiario</label>
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-2">
                      <button
                        class="btn btn-success"
                        type="button"
                        @click="addBeneficiaryPhoneNumber()"
                        :disabled="editable"
                      >
                        <i class="fa fa-plus"></i>
                      </button>
                    </div>
                    <div class="col-md-10">
                      <div v-for="(phone,index) in ecoComBeneficiary.phone_number" :key="index">
                        <div class="input-group">
                          <input
                            type="text"
                            name="eco_com_beneficiary_phone_number[]"
                            v-model="phone.value"
                            class="form-control"
                            v-phone
                            :disabled="editable"
                          >
                          <span class="input-group-btn">
                            <button
                              class="btn btn-danger"
                              v-show="ecoComBeneficiary.phone_number.length > 1"
                              @click="deleteBeneficiaryPhoneNumber(phone)"
                              type="button"
                              :disabled="editable"
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
                  <label class="control-label">Celular del Beneficiario</label>
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-2">
                      <button
                        class="btn btn-success"
                        type="button"
                        @click="addBeneficiaryCellPhoneNumber()"
                        :disabled="editable"
                      >
                        <i class="fa fa-plus"></i>
                      </button>
                    </div>
                    <div class="col-md-10">
                      <div
                        v-for="(cell_phone,index) in ecoComBeneficiary.cell_phone_number"
                        :key="index"
                      >
                        <div class="input-group">
                          <input
                            type="text"
                            name="eco_com_beneficiary_cell_phone_number[]"
                            v-model="cell_phone.value"
                            class="form-control"
                            v-cell-phone
                            :disabled="editable"
                          >
                          <span class="input-group-btn">
                            <button
                              v-show="ecoComBeneficiary.cell_phone_number.length > 1"
                              class="btn btn-danger"
                              @click="deleteBeneficiaryCellPhoneNumber(cell_phone)"
                              type="button"
                              :disabled="editable"
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
            <br>            
            <br>
            <div class="hr-line-dashed"></div>
            <div class="row">
              <div class="col-md-3">
                <div class="col-md-4">
                  <label class="control-label">Ciudad</label>
                </div>
                <div class="col-md-8">
                  <select
                    class="form-control"
                    v-model.trim="ecoComBeneficiary.address.city_address_id"
                    name="eco_com_beneficiary_city_address_id"
                    :disabled="editable"
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
                      name="eco_com_beneficiary_zone"
                      v-model.trim="ecoComBeneficiary.address.zone"
                      class="form-control"
                      :disabled="editable"
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
                      name="eco_com_beneficiary_street"
                      v-model.trim="ecoComBeneficiary.address.street"
                      class="form-control"
                      :disabled="editable"
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
                      name="eco_com_beneficiary_number_address"
                      v-model.trim="ecoComBeneficiary.address.number_address"
                      class="form-control"
                      :disabled="editable"
                    >
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="text-center" v-if="!editable">
                <button class="btn btn-danger" type="button" @click="cancel()">
                  <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
                  <span class="bold">Cancelar</span>
                </button>
                <button class="btn btn-primary" type="button" @click="save()">
                  <i class="fa fa-check-circle"></i>&nbsp;Guardar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="ibox-content" v-else>
        <div class="alert alert-warning">No tiene permisos para ver la información del beneficiario.</div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapMutations, mapState } from "vuex";
import { flashErrors, canOperation } from "../../helper";
export default {
  props: ["cities", "beneficiary", "ecoCom", "permissions"],
  data() {
    return {
      copy: {},
      editable: true,
      civilStatus: [
        { id: "C", name: "Casado (a)" },
        { id: "S", name: "Soltero (a)" },
        { id: "V", name: "Viudo (a)" },
        { id: "D", name: "Divorciado (a)" }
      ]
    };
  },
  mounted() {
    document.querySelectorAll(".tab-eco-com-beneficiary")[0].addEventListener(
      "click",
      () => {
        this.getBeneficiary();
      },
      { passive: true }
    );
  },
  methods: {
    can(operation) {
      return canOperation(operation, this.permissions);
    },
    async getBeneficiary() {
      this.$scrollTo("#wrapper");
      await axios
        .get(`/get_eco_com_beneficiary/${this.ecoCom.id}`)
        .then(response => {
          this.$store.commit("ecoComForm/setEcoComBeneficiary", response.data);
        });
    },
    edit() {
      if (!this.can("update_eco_com_beneficiary", this.permissions)) {
        return;
      }
      this.editable = !this.editable;
      this.copy = JSON.parse(JSON.stringify(this.ecoComBeneficiary));
    },
    cancel() {
      this.editable = true;
      this.$store.commit("ecoComForm/setEcoComBeneficiary", this.copy);
    },
    async update() {
      await this.$validator.validateAll();
      if (this.$validator.errors.items.length) {
        return;
      }
      let uri = `/eco_com_beneficiary/${this.beneficiary.id}`;
      this.show_spinner = true;
      await axios
        .patch(uri, this.beneficiary)
        .then(response => {
          this.editable = false;
          this.show_spinner = false;
          flash("Información del Beneficiario Actualizada");
        })
        .catch(response => {
          this.show_spinner = false;
          flash(
            "Error al actualizar el Beneficiario: " + response.message,
            "error"
          );
        });
    },
    async save() {
      await this.$validator.validateAll();
      if (this.$validator.errors.items.length) {
        flash("Campos requeridos: ", "error");
        return;
      }
      this.ecoComBeneficiary.eco_com_id = this.ecoCom.id;
      await axios
        .patch(`/eco_com_beneficiary`, this.ecoComBeneficiary)
        .then(response => {
          this.$store.commit("ecoComForm/setEcoComBeneficiary", response.data);
          this.editable = true;
          flash("Información del Beneficiario actualizada");
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
    },
    ...mapMutations("ecoComForm", [
      "addBeneficiaryPhoneNumber",
      "deleteBeneficiaryPhoneNumber",
      "addBeneficiaryCellPhoneNumber",
      "deleteBeneficiaryCellPhoneNumber"
    ]),
    async searchEcoComBeneficiary() {
      await axios
        .get("/search_ajax", {
          params: {
            ci: this.ecoComBeneficiary.identity_card
          }
        })
        .then(response => {
          let data = response.data;
          this.$store.commit("ecoComForm/setEcoComBeneficiary", data);
        })
        .catch(function(error) {
          console.log(error);
        });
      await this.$validator.validateAll();
    }
  },
  computed: {
    ecoComBeneficiary() {
      return this.$store.state.ecoComForm.beneficiary;
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
