<template>
  <div class="col-lg-12">
    <div class="ibox">
      <div class="ibox-title">
        <h2 class="pull-left">Datos del Apoderado</h2>
        <div class="ibox-tools">
          <button v-if="!editable && can('delete_eco_com_legal_guardian')" class="btn btn-danger" @click="deleteLegalGuardian()" ><i class="fa fa-trash-o"></i></button>
          <button
            class="btn btn-primary"
            @click="edit()"
            data-toggle="tooltip"
            title="Editar"
            :disabled="!can('update_eco_com_legal_guardian')"
            v-if="can('read_eco_com_legal_guardian')"
          >
            <i class="fa fa-pencil"></i> Editar
          </button>
        </div>
      </div>
      <div class="ibox-content" v-if="can('read_eco_com_legal_guardian')">
        <div>
          <div class="row">
            <div class="col-md-12" :class="{'has-error': errors.has('eco_com_legal_guardian_type_id') }">
              <div class="col-md-4">
                <label class="control-label">Tipo de Apoderado</label>
              </div>
              <div class="col-md-8">
                <select
                  name="eco_com_legal_guardian_type_id"
                  v-model="legalGuardian.eco_com_legal_guardian_type_id"
                  class="form-control"
                  :disabled="editable"
                  v-validate="'required'"
                >
                  <option :value="null"></option>
                  <option
                    v-for="lt in ecoComLegalGuardianTypes"
                    :key="lt.id"
                    :value="lt.id"
                  >{{ lt.name }}</option>
                </select>
                <i v-show="errors.has('eco_com_legal_guardian_type_id')" class="fa fa-warning text-danger"></i>
                <span
                  v-show="errors.has('eco_com_legal_guardian_type_id')"
                  class="text-danger"
                >{{ errors.first('eco_com_legal_guardian_type_id') }}</span>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div
              class="col-md-6"
              :class="{'has-error': errors.has('legal_guardian_identity_card') }"
            >
              <div class="col-md-4">
                <label class="control-label">Cédula de Identidad</label>
              </div>
              <div class="col-md-8">
                <div class="input-group">
                  <input
                    type="text"
                    name="legal_guardian_identity_card"
                    v-model.trim="legalGuardian.identity_card"
                    class="form-control"
                    v-validate="'required'"
                    :disabled="editable"
                  >
                  <span class="input-group-btn">
                    <button
                      class="btn"
                      :class="errors.has('legal_guardian_identity_card') ? 'btn-danger' : 'btn-primary'"
                      type="button"
                      role="button"
                      :disabled="editable"
                      @click="searchLegalGuardian()"
                    >
                      <i class="fa fa-search"></i>
                    </button>
                  </span>
                </div>
                <i
                  v-show="errors.has('legal_guardian_identity_card')"
                  class="fa fa-warning text-danger"
                ></i>
                <span
                  v-show="errors.has('legal_guardian_identity_card')"
                  class="text-danger"
                >{{ errors.first('legal_guardian_identity_card') }}</span>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6" :class="{'has-error': errors.has('legal_guardian_first_name') }">
              <div class="col-md-4">
                <label class="control-label">Primer Nombre</label>
              </div>
              <div class="col-md-8">
                <input
                  type="text"
                  name="legal_guardian_first_name"
                  v-model.trim="legalGuardian.first_name"
                  class="form-control"
                  v-validate="'required|alpha_space_quote'"
                  :disabled="editable"
                >
                <div v-show="errors.has('legal_guardian_first_name')">
                  <i class="fa fa-warning text-danger"></i>
                  <span class="text-danger">{{ errors.first('legal_guardian_first_name') }}</span>
                </div>
              </div>
            </div>
            <div class="col-md-6" :class="{'has-error': errors.has('legal_guardian_second_name') }">
              <div class="col-md-4">
                <label class="control-label">Segundo Nombre</label>
              </div>
              <div class="col-md-8">
                <input
                  type="text"
                  name="legal_guardian_second_name"
                  v-model.trim="legalGuardian.second_name"
                  class="form-control"
                  v-validate="'alpha_space_quote'"
                  :disabled="editable"
                >
                <div v-show="errors.has('legal_guardian_second_name')">
                  <i class="fa fa-warning text-danger"></i>
                  <span class="text-danger">{{ errors.first('legal_guardian_second_name') }}</span>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6" :class="{'has-error': errors.has('legal_guardian_last_name') }">
              <div class="col-md-4">
                <label class="control-label">Apellido Paterno</label>
              </div>
              <div class="col-md-8">
                <input
                  type="text"
                  name="legal_guardian_last_name"
                  v-model.trim="legalGuardian.last_name"
                  class="form-control"
                  v-validate="'alpha_space_quote'"
                  :disabled="editable"
                >
                <div v-show="errors.has('legal_guardian_last_name')">
                  <i class="fa fa-warning text-danger"></i>
                  <span class="text-danger">{{ errors.first('legal_guardian_last_name') }}</span>
                </div>
              </div>
            </div>
            <div
              class="col-md-6"
              :class="{'has-error': errors.has('legal_guardian_mothers_last_name') }"
            >
              <div class="col-md-4">
                <label class="control-label">Apellido Materno</label>
              </div>
              <div class="col-md-8">
                <input
                  type="text"
                  name="legal_guardian_mothers_last_name"
                  v-model.trim="legalGuardian.mothers_last_name"
                  class="form-control"
                  v-validate="'alpha_space_quote'"
                  :disabled="editable"
                >
                <div v-show="errors.has('legal_guardian_mothers_last_name')">
                  <i class="fa fa-warning text-danger"></i>
                  <span class="text-danger">{{ errors.first('legal_guardian_mothers_last_name') }}</span>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div
              class="col-md-6"
              :class="{'has-error': errors.has('legal_guardian_surname_husband') }"
            >
              <div class="col-md-4">
                <label class="control-label">Apellido de Casada</label>
              </div>
              <div class="col-md-8">
                <input
                  type="text"
                  name="legal_guardian_surname_husband"
                  v-model.trim="legalGuardian.surname_husband"
                  class="form-control"
                  v-validate="'alpha_space_quote'"
                  :disabled="editable"
                >
                <div v-show="errors.has('legal_guardian_surname_husband')">
                  <i class="fa fa-warning text-danger"></i>
                  <span class="text-danger">{{ errors.first('legal_guardian_surname_husband') }}</span>
                </div>
              </div>
            </div>
            <div class="col-md-6" :class="{'has-error': errors.has('legal_guardian_gender') }">
              <div class="col-md-4">
                <label class="control-label">Genero</label>
              </div>
              <div class="col-md-8">
                <select
                  class="form-control m-b"
                  name="legal_guardian_gender"
                  v-model.trim="legalGuardian.gender"
                  v-validate="'required'"
                  :disabled="editable"
                >
                  <option :value="null"></option>
                  <option value="M">Masculino</option>
                  <option value="F">Femenino</option>
                </select>
                <i v-show="errors.has('legal_guardian_gender')" class="fa fa-warning text-danger"></i>
                <span
                  v-show="errors.has('legal_guardian_gender')"
                  class="text-danger"
                >{{ errors.first('legal_guardian_gender') }}</span>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div
              class="col-md-6"
              :class="{'has-error': errors.has('legal_guardian_due_date') && !legalGuardian.is_duedate_undefined }"
            >
              <div class="col-md-4">
                <label class="control-label">Fecha de Vencimiento del CI</label>
              </div>
              <div class="col-md-8">
                <input
                  type="text"
                  :disabled="legalGuardian.is_duedate_undefined || editable"
                  name="legal_guardian_due_date"
                  v-model.trim="legalGuardian.due_date"
                  class="form-control"
                  v-date
                  v-validate="'required|date_format:dd/MM/yyyy|max_due_date'"
                >
                <br>
                <input
                  class="mediumCheckbox"
                  type="checkbox"
                  name="legal_guardian_is_duedate_undefined"
                  v-model="legalGuardian.is_duedate_undefined"
                  :disabled="editable"
                  id="legal_guardian_is_duedate_undefined"
                >
                <label for="legal_guardian_is_duedate_undefined" class="pointer v-middle">
                  Indefinido
                </label>
                <div
                  v-show="errors.has('legal_guardian_due_date') && !legalGuardian.is_duedate_undefined"
                >
                  <i class="fa fa-warning text-danger"></i>
                  <span class="text-danger">{{ errors.first('legal_guardian_due_date') }}</span>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <div class="col-md-4">
                <label class="control-label">Teléfono</label>
              </div>
              <div class="col-md-8">
                <div class="row">
                  <div class="col-md-2">
                    <button
                      class="btn btn-success"
                      type="button"
                      @click="addLegalGuardianPhoneNumber()"
                      :disabled="editable"
                    >
                      <i class="fa fa-plus"></i>
                    </button>
                  </div>
                  <div class="col-md-10">
                    <div v-for="(phone,index) in legalGuardian.phone_number" :key="index">
                      <div class="input-group">
                        <input
                          type="text"
                          name="legal_guardian_phone_number[]"
                          v-model="phone.value"
                          class="form-control"
                          v-phone
                          :disabled="editable"
                        >
                        <span class="input-group-btn">
                          <button
                            class="btn btn-danger"
                            v-show="legalGuardian.phone_number.length > 1"
                            @click="deleteLegalGuardianPhoneNumber(phone)"
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
                <label class="control-label">Celular</label>
              </div>
              <div class="col-md-8">
                <div class="row">
                  <div class="col-md-2">
                    <button
                      class="btn btn-success"
                      type="button"
                      @click="addLegalGuardianCellPhoneNumber()"
                      :disabled="editable"
                    >
                      <i class="fa fa-plus"></i>
                    </button>
                  </div>
                  <div class="col-md-10">
                    <div v-for="(cell_phone,index) in legalGuardian.cell_phone_number" :key="index">
                      <div class="input-group">
                        <input
                          type="text"
                          name="legal_guardian_cell_phone_number[]"
                          v-model="cell_phone.value"
                          class="form-control"
                          v-cell-phone
                          :disabled="editable"
                        >
                        <span class="input-group-btn">
                          <button
                            v-show="legalGuardian.cell_phone_number.length > 1"
                            class="btn btn-danger"
                            @click="deleteLegalGuardianCellPhoneNumber(cell_phone)"
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
          <div class="row">
            <div
              class="col-md-6"
              :class="{'has-error': errors.has('legal_guardian_number_authority') }"
            >
              <div class="col-md-4">
                <label class="control-label">Nro de Poder</label>
              </div>
              <div class="col-md-8">
                <input
                  type="text"
                  name="legal_guardian_number_authority"
                  v-model.trim="legalGuardian.number_authority"
                  class="form-control"
                  v-validate="'required'"
                  :disabled="editable"
                >
                <i
                  v-show="errors.has('legal_guardian_number_authority')"
                  class="fa fa-warning text-danger"
                ></i>
                <span
                  v-show="errors.has('legal_guardian_number_authority')"
                  class="text-danger"
                >{{ errors.first('legal_guardian_number_authority') }}</span>
              </div>
            </div>
            <div
              class="col-md-6"
              :class="{'has-error': errors.has('legal_guardian_notary_of_public_faith') }"
            >
              <div class="col-md-4">
                <label class="control-label">Notaria de Fe Publica Nro</label>
              </div>
              <div class="col-md-8">
                <input
                  type="text"
                  name="legal_guardian_notary_of_public_faith"
                  v-model.trim="legalGuardian.notary_of_public_faith"
                  class="form-control"
                  v-validate="'required'"
                  :disabled="editable"
                >
                <i
                  v-show="errors.has('legal_guardian_notary_of_public_faith')"
                  class="fa fa-warning text-danger"
                ></i>
                <span
                  v-show="errors.has('legal_guardian_notary_of_public_faith')"
                  class="text-danger"
                >{{ errors.first('legal_guardian_notary_of_public_faith') }}</span>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6" :class="{'has-error': errors.has('legal_guardian_notary') }">
              <div class="col-md-4">
                <label class="control-label">Notario</label>
              </div>
              <div class="col-md-8">
                <input
                  type="text"
                  name="legal_guardian_notary"
                  v-model.trim="legalGuardian.notary"
                  class="form-control"
                  v-validate="'required'"
                  :disabled="editable"
                >
                <i v-show="errors.has('legal_guardian_notary')" class="fa fa-warning text-danger"></i>
                <span
                  v-show="errors.has('legal_guardian_notary')"
                  class="text-danger"
                >{{ errors.first('legal_guardian_notary') }}</span>
              </div>
            </div>
            <div
              class="col-md-6"
              :class="{'has-error': errors.has('legal_guardian_date_authority') }"
            >
              <div class="col-md-4">
                <label class="control-label">Fecha de Poder</label>
              </div>
              <div class="col-md-8">
                <input
                  type="text"
                  v-date
                  name="legal_guardian_date_authority"
                  v-model.trim="legalGuardian.date_authority"
                  class="form-control"
                  v-validate="'required|max_current_date'"
                  :disabled="editable"
                >
                <i
                  v-show="errors.has('legal_guardian_date_authority')"
                  class="fa fa-warning text-danger"
                ></i>
                <span
                  v-show="errors.has('legal_guardian_date_authority')"
                  class="text-danger"
                >{{ errors.first('legal_guardian_date_authority') }}</span>
              </div>
            </div>
          </div>
          <br>
          <div class="row" v-if="!editable">
            <div class="col-md-12">
              <div class="text-center m-sm">
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
        <div class="alert alert-warning">No tiene permisos para ver la Información del apoderado.</div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapMutations, mapState } from "vuex";
import { flashErrors, canOperation } from "../../helper";

export default {
  props: ["cities", "ecoCom", "ecoComLegalGuardianTypes", "permissions"],
  data() {
    return {
      copy: {},
      editable: true
    };
  },
  mounted() {
    document.querySelectorAll(".tab-eco-com-legal-guardian")[0].addEventListener(
      "click",
      () => {
        this.getLegalGuardian();
      },
      { passive: true }
    );
  },
  methods: {
    async deleteLegalGuardian(){
      await axios.delete('/eco_com_legal_guardian',{ data: this.legalGuardian })
      .then(response => {
        flash('Se elimino al apoderado');
        this.editable = true;
        this.getLegalGuardian();

      })
      .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
      })
    },
    can(operation) {
      return canOperation(operation, this.permissions);
    },
    async getLegalGuardian() {
      this.$scrollTo('#wrapper');
      await axios
        .get(`/get_eco_com_legal_guardian/${this.ecoCom.id}`)
        .then(response => {
          this.$store.commit("ecoComForm/setLegalGuardian", response.data);
        });
    },
    edit() {
      if (!this.can("update_eco_com_legal_guardian", this.permissions)) {
        return;
      }
      this.editable = !this.editable;
      this.copy = JSON.parse(JSON.stringify(this.legalGuardian));
    },
    cancel() {
      this.editable = true;
      this.$store.commit("ecoComForm/setLegalGuardian", this.copy);
    },
    async save() {
      await this.$validator.validateAll();
      if (this.$validator.errors.items.length) {
        return;
      }
      this.legalGuardian.eco_com_id = this.ecoCom.id;
      console.log(this.legalGuardian);
      await axios
        .patch(`/eco_com_legal_guardian`, this.legalGuardian)
        .then(response => {
          this.$store.commit("ecoComForm/setLegalGuardian", response.data);
          this.editable = true;
          flash("Información del apoderado actualizada");
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
        });
    },
    ...mapMutations("ecoComForm", [
      "addLegalGuardianPhoneNumber",
      "deleteLegalGuardianPhoneNumber",
      "addLegalGuardianCellPhoneNumber",
      "deleteLegalGuardianCellPhoneNumber"
    ]),
    async searchLegalGuardian() {
      await axios
        .get("/search_ajax", {
          params: {
            ci: this.legalGuardian.identity_card
          }
        })
        .then(response => {
          let data = response.data;
          this.$store.dispatch("ecoComForm/setLegalGuardian1", data);
        })
        .catch(function(error) {
          console.log(error);
        });
      await this.$validator.validateAll();
    }
  },
  computed: {
    legalGuardian() {
      return this.$store.state.ecoComForm.legalGuardian;
    }
  }
};
</script>

<style>
</style>
