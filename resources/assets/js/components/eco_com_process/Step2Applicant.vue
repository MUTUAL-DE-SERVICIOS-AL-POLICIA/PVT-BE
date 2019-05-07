<template>
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <legend>Datos del Apoderado (@{{getNameLegalGuardianType}})</legend>
        <div class="row">
          <div class="col-md-6">
            <div class="col-md-4">
              <label class="control-label">Tiene Apoderado</label>
            </div>
            <div class="col-md-8">
              <input type="checkbox" v-model="has_legal_guardian" name="has_legal_guardian">
            </div>
          </div>
          <div class="col-md-6" v-if="has_legal_guardian">
            <div class="col-md-4">
              <label class="control-label">Tipo de Apoderado</label>
            </div>
            <div class="col-md-8">
              <select name="legal_guardian_type" v-model="legal_guardian_type" class="form-control">
                <option :value="null"></option>
                <option
                  v-for="lt in legal_guardian_types"
                  :key="lt.id"
                  :value="lt.id"
                >@{{ lt.name }}</option>
              </select>
            </div>
          </div>
        </div>
        <div class="hr-line-dashed"></div>
        <br>
        <div v-if="has_legal_guardian && legal_guardian_type">
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
                    v-model.trim="legal_guardian_identity_card"
                    class="form-control"
                    v-validate.initial="'required'"
                  >
                  <span class="input-group-btn">
                    <button
                      class="btn"
                      :class="errors.has('legal_guardian_identity_card') ? 'btn-danger' : 'btn-primary'"
                      type="button"
                      @click="searchLegalGuardian"
                      role="button"
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
                >@{{ errors.first('legal_guardian_identity_card') }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="col-md-4">
                <label class="control-label">Ciudad de Expedición</label>
              </div>
              <div class="col-md-8">
                <select
                  class="form-control"
                  name="legal_guardian_city_identity_card"
                  v-model.trim="legal_guardian_city_identity_card"
                >
                  <option :value="null"></option>
                  <option v-for="city in cities" :value="city.id">@{{ city.name }}</option>
                </select>
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
                  v-model.trim="legal_guardian_first_name"
                  class="form-control"
                  v-validate.initial="'required'"
                >
                <i
                  v-show="errors.has('legal_guardian_first_name')"
                  class="fa fa-warning text-danger"
                ></i>
                <span
                  v-show="errors.has('legal_guardian_first_name')"
                  class="text-danger"
                >@{{ errors.first('legal_guardian_first_name') }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="col-md-4">
                <label class="control-label">Segundo Nombre</label>
              </div>
              <div class="col-md-8">
                <input
                  type="text"
                  name="legal_guardian_second_name"
                  v-model.trim="legal_guardian_second_name"
                  class="form-control"
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
                  name="legal_guardian_last_name"
                  v-model.trim="legal_guardian_last_name"
                  class="form-control"
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
                  name="legal_guardian_mothers_last_name"
                  v-model.trim="legal_guardian_mothers_last_name"
                  class="form-control"
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
                  name="legal_guardian_surname_husband"
                  v-model.trim="legal_guardian_surname_husband"
                  class="form-control"
                >
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
                  v-model.trim="legal_guardian_gender"
                  v-validate.initial="'required'"
                >
                  <option :value="null"></option>
                  <option value="M">Masculino</option>
                  <option value="F">Femenino</option>
                </select>
                <i v-show="errors.has('legal_guardian_gender')" class="fa fa-warning text-danger"></i>
                <span
                  v-show="errors.has('legal_guardian_gender')"
                  class="text-danger"
                >@{{ errors.first('legal_guardian_gender') }}</span>
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
                  v-model.trim="legal_guardian_number_authority"
                  class="form-control"
                  v-validate.initial="'required'"
                >
                <i
                  v-show="errors.has('legal_guardian_number_authority')"
                  class="fa fa-warning text-danger"
                ></i>
                <span
                  v-show="errors.has('legal_guardian_number_authority')"
                  class="text-danger"
                >@{{ errors.first('legal_guardian_number_authority') }}</span>
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
                  v-model.trim="legal_guardian_notary_of_public_faith"
                  class="form-control"
                  v-validate.initial="'required'"
                >
                <i
                  v-show="errors.has('legal_guardian_notary_of_public_faith')"
                  class="fa fa-warning text-danger"
                ></i>
                <span
                  v-show="errors.has('legal_guardian_notary_of_public_faith')"
                  class="text-danger"
                >@{{ errors.first('legal_guardian_notary_of_public_faith') }}</span>
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
                  v-model.trim="legal_guardian_notary"
                  class="form-control"
                  v-validate.initial="'required'"
                >
                <i v-show="errors.has('legal_guardian_notary')" class="fa fa-warning text-danger"></i>
                <span
                  v-show="errors.has('legal_guardian_notary')"
                  class="text-danger"
                >@{{ errors.first('legal_guardian_notary') }}</span>
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
                  v-model.trim="legal_guardian_date_authority"
                  class="form-control"
                  v-validate.initial="'required|max_current_date'"
                >
                <i
                  v-show="errors.has('legal_guardian_date_authority')"
                  class="fa fa-warning text-danger"
                ></i>
                <span
                  v-show="errors.has('legal_guardian_date_authority')"
                  class="text-danger"
                >@{{ errors.first('legal_guardian_date_authority') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <legend>Datos del afiliado (Derechohabiente)</legend>
      </div>
    </div>
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <form method="get" class="form-horizontal">
          <div class="col-md-12">
            <legend>Datos del Beneficiario</legend>
            <div class="row">
              <div class="col-md-6">
                <div
                  class="form-group"
                  :class="{'has-error': errors.has('ecoComBeneficiary.identity_card') }"
                >
                  <div class="col-md-4">
                    <label class="control-label">Cédula de Identidad</label>
                  </div>
                  <div class="col-md-8">
                    <div class="input-group">
                      <input
                        type="text"
                        disabled
                        name="ecoComBeneficiary.identity_card"
                        v-model.trim="ecoComBeneficiary.identity_card"
                        class="form-control"
                        v-validate.initial="'required'"
                      >
                      <span class="input-group-btn">
                        <button
                          class="btn"
                          :class="errors.has('ecoComBeneficiary.identity_card') ? 'btn-danger' : 'btn-primary' "
                          type="button"
                          @click="searchApplicant"
                          role="button"
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
                    >@{{ errors.first('ecoComBeneficiary.identity_card') }}</span>
                  </div>
                </div>
              </div>
              <div
                class="col-md-6"
                :class="{'has-error': errors.has('ecoComBeneficiary.city_identity_card_id') }"
              >
                <div class="col-md-4">
                  <label class="control-label">Ciudad de Expedición</label>
                </div>
                <div class="col-md-8">
                  <select
                    class="form-control"
                    disabled
                    name="ecoComBeneficiary.city_identity_card_id"
                    v-model.trim="ecoComBeneficiary.city_identity_card_id"
                    v-validate.initial="'required'"
                  >
                    <option :value="null"></option>
                    <option v-for="city in cities" :value="city.id">@{{ city.name }}</option>
                  </select>
                  <i
                    v-show="errors.has('ecoComBeneficiary.city_identity_card_id')"
                    class="fa fa-warning text-danger"
                  ></i>
                  <span
                    v-show="errors.has('ecoComBeneficiary.city_identity_card_id')"
                    class="text-danger"
                  >@{{ errors.first('ecoComBeneficiary.city_identity_card_id') }}</span>
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
                    disabled
                    name="ecoComBeneficiary.first_name"
                    v-model.trim="ecoComBeneficiary.first_name"
                    class="form-control"
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
                    disabled
                    name="ecoComBeneficiary.second_name"
                    v-model.trim="ecoComBeneficiary.second_name"
                    class="form-control"
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
                    disabled
                    name="ecoComBeneficiary.last_name"
                    v-model.trim="ecoComBeneficiary.last_name"
                    class="form-control"
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
                    disabled
                    name="ecoComBeneficiary.mothers_last_name"
                    v-model.trim="ecoComBeneficiary.mothers_last_name"
                    class="form-control"
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
                    disabled
                    name="ecoComBeneficiary.surname_husband"
                    v-model.trim="ecoComBeneficiary.surname_husband"
                    class="form-control"
                  >
                </div>
              </div>
              <div class="col-md-6" :class="{'has-error': errors.has('ecoComBeneficiary.gender') }">
                <div class="col-md-4">
                  <label class="control-label">Genero</label>
                </div>
                <div class="col-md-8">
                  <select
                    class="form-control m-b"
                    disabled
                    name="ecoComBeneficiary.gender"
                    v-model.trim="ecoComBeneficiary.gender"
                    v-validate.initial="'required'"
                  >
                    <option :value="null"></option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                  </select>
                  <i
                    v-show="errors.has('ecoComBeneficiary.gender')"
                    class="fa fa-warning text-danger"
                  ></i>
                  <span
                    v-show="errors.has('ecoComBeneficiary.gender')"
                    class="text-danger"
                  >@{{ errors.first('ecoComBeneficiary.gender') }}</span>
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
                    class="form-control"
                    disabled
                    v-model.trim="ecoComBeneficiary.birth_date"
                    name="ecoComBeneficiary.birth_date"
                  >
                </div>
              </div>
              <div class="col-md-6" :class="{'has-error': errors.has('ecoComBeneficiary.nua') }">
                <div class="col-md-4">
                  <label class="control-label">CUA/NUA</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    disabled
                    name="ecoComBeneficiary.nua"
                    v-model.trim="ecoComBeneficiary.nua"
                    class="form-control"
                  >
                </div>
              </div>
            </div>
            <br>
            <!-- <div class="row">
              <div class="col-md-6">
                <div class="col-md-4">
                  <label class="control-label">Teléfono del Beneficiario</label>
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-2">
                      <button class="btn btn-success" type="button" @click="addPhoneNumber">
                        <i class="fa fa-plus"></i>
                      </button>
                    </div>
                    <div class="col-md-10">
                      <div v-for="(phone,index) in beneficiary_phone_numbers">
                        <div class="input-group">
                          <input
                            type="text"
                            name="beneficiary_phone_number[]"
                            v-model.trim="phone.value"
                            :key="index"
                            class="form-control"
                            v-phone
                          >
                          <span class="input-group-btn">
                            <button
                              class="btn btn-danger"
                              v-show="beneficiary_phone_numbers.length > 1"
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
                  <label class="control-label">Celular del Beneficiario</label>
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-2">
                      <button class="btn btn-success" type="button" @click="addCellPhoneNumber">
                        <i class="fa fa-plus"></i>
                      </button>
                    </div>
                    <div class="col-md-10">
                      <div v-for="(cell_phone,index) in beneficiary_cell_phone_numbers">
                        <div class="input-group">
                          <input
                            type="text"
                            name="beneficiary_cell_phone_number[]"
                            v-model.trim="cell_phone.value"
                            :key="index"
                            class="form-control"
                            v-cell-phone
                          >
                          <span class="input-group-btn">
                            <button
                              class="btn btn-danger"
                              v-show="beneficiary_cell_phone_numbers.length > 1"
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
            </div> -->
            <br>
          </div>
          <!-- /div principal cyk -->
          <div class="row"></div>
        </form>
      </div>
    </div>
    <!-- <div class="ibox float-e-margins">
      <div class="ibox-content">
        <legend>Direccion del Beneficiario</legend>
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Ciudad</label>
              <div class="col-md-8">
                <select
                  name="beneficiary_city_address_id"
                  v-model="ecoComBeneficiary.address[0].city_address_id"
                  class="form-control"
                >
                  <option :value="null"></option>
                  <option v-for="(city, index) in cities" :value="city.id">@{{ city.name }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Zona</label>
              <div class="col-sm-8">
                <input
                  type="text"
                  name="beneficiary_zone"
                  v-model.trim="ecoComBeneficiary.address[0].zone"
                  class="form-control"
                >
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Calle</label>
              <div class="col-sm-8">
                <input
                  type="text"
                  name="beneficiary_street"
                  v-model.trim="ecoComBeneficiary.address[0].street"
                  class="form-control"
                >
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label class="col-sm-4 control-label">Nro</label>
              <div class="col-sm-8">
                <input
                  type="text"
                  name="beneficiary_number_address"
                  v-model.trim="ecoComBeneficiary.address[0].number_address"
                  class="form-control"
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> -->
  </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  props: ["cities", "spouse", "affiliate"],
  data() {
    return {
      applicant_type: "",
      applicant_first_name: "",
      applicant_second_name: "",
      applicant_last_name: "",
      applicant_mothers_last_name: "",
      applicant_surname_husband: "",
      applicant_identity_card: "",
      applicant_city_identity_card_id: "",
      applicant_birth_date: "",
      applicant_phone_number: "",
      applicant_cell_phone_number: "",
      advisor_name_court: "",
      advisor_resolution_number: "",
      advisor_resolution_date: "",
      applicant_gender: "",
      applicant_phone_numbers: [],
      applicant_cell_phone_numbers: [],
      applicant_nua: null,
      legal_guardian_first_name: "",
      legal_guardian_second_name: "",
      legal_guardian_first_name: "",
      legal_guardian_second_name: "",
      legal_guardian_last_name: "",
      legal_guardian_mothers_last_name: "",
      legal_guardian_surname_husband: "",
      legal_guardian_identity_card: "",
      legal_guardian_city_identity_card: "",
      legal_guardian_number_authority: "",
      legal_guardian_date_authority: "",
      legal_guardian_gender: null,
      legal_guardian_notary_of_public_faith: "",
      legal_guardian_notary: "",
      beneficiary_city_id: "",
      beneficiary_zone: "",
      beneficiary_street: "",
      beneficiary_number_address: "",
      applicant_type: null,
      show_advisor_form: false,
      show_apoderado_form: false,
      applicant_types: [
        { name: "Beneficiario", id: 1 },
        { name: "Tutor", id: 2 },
        { name: "Apoderado", id: 3 }
      ],
      date_entry: this.affiliate.date_entry,
      date_derelict: this.affiliate.date_derelict,
      date_death: this.affiliate.date_death,
      reason_death: this.affiliate.reason_death,
      beneficiary_city_address_id: null,
      error: {
        applicant_identity_card: false
      }
    };
  },
  mounted() {
    //this or define initial value  => [{ value:null }]
    console.log("date_derelict");
    // console.log(this.affiliate.date_derelict);
    // this.addPhoneNumber();
    // this.addCellPhoneNumber();
  },
  computed: {
    ...mapGetters("retFunForm", {
      retFun: "getData"
    }),
    applicantIsMale() {
      return this.applicant_gender == "M";
    },
    applicant_types_filter() {
      if (this.retFun.modality_id == 4 || this.retFun.modality_id == 1) {
        return this.applicant_types;
      }
      return this.applicant_types.filter(item => {
        return item.id != 2;
      });
    },
    isDeathMode() {
      return this.retFun.modality_id == 4 || this.retFun.modality_id == 1;
    }
  },
  methods: {
    addPhoneNumber() {
      if (this.applicant_phone_numbers.length > 0) {
        let last_phone = this.applicant_phone_numbers[
          this.applicant_phone_numbers.length - 1
        ];
        if (last_phone.value && !last_phone.value.includes("_")) {
          this.applicant_phone_numbers.push({ value: null });
        }
      } else {
        this.applicant_phone_numbers.push({ value: null });
      }
    },
    deletePhoneNumber(index) {
      this.applicant_phone_numbers.splice(index, 1);
      if (this.applicant_phone_numbers.length < 1) this.addPhoneNumber();
    },
    addCellPhoneNumber() {
      if (this.applicant_cell_phone_numbers.length > 0) {
        let last_phone = this.applicant_cell_phone_numbers[
          this.applicant_cell_phone_numbers.length - 1
        ];
        if (last_phone.value && !last_phone.value.includes("_")) {
          this.applicant_cell_phone_numbers.push({ value: null });
        }
      } else {
        this.applicant_cell_phone_numbers.push({ value: null });
      }
    },
    deleteCellPhoneNumber(index) {
      this.applicant_cell_phone_numbers.splice(index, 1);
      if (this.applicant_cell_phone_numbers.length < 1)
        this.addCellPhoneNumber();
    },
    searchApplicant: function() {
      let ci = document.getElementsByName("applicant_identity_card")[0].value;
      axios
        .get("/search_ajax", {
          params: {
            ci
          }
        })
        .then(response => {
          let data = response.data;
          this.setDataApplicant(data);
          console.log(data);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    searchLegalGuardian: function() {
      let ci = document.getElementsByName("legal_guardian_identity_card")[0]
        .value;
      axios
        .get("/search_ajax", {
          params: {
            ci
          }
        })
        .then(response => {
          let data = response.data;
          this.setDataLegalGuardian(data);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    setDataApplicant(data) {
      this.applicant_first_name = data.first_name;
      this.applicant_second_name = data.second_name;
      this.applicant_last_name = data.last_name;
      this.applicant_mothers_last_name = data.mothers_last_name;
      this.applicant_surname_husband = data.surname_husband;
      (this.applicant_surname_husband = data.surname_husband),
        (this.applicant_identity_card = data.identity_card);
      this.applicant_city_identity_card_id = data.city_identity_card_id;
      this.applicant_gender = data.gender;

      this.applicant_birth_date = data.birth_date;
      this.applicant_phone_numbers = data.phone_number;
      this.applicant_cell_phone_numbers = data.cell_phone_number;
      console.log(this.applicant_birth_date + "<<<<this");
    },
    setDataLegalGuardian(data) {
      this.legal_guardian_first_name = data.first_name;
      this.legal_guardian_second_name = data.second_name;
      this.legal_guardian_last_name = data.last_name;
      this.legal_guardian_mothers_last_name = data.mothers_last_name;
      this.legal_guardian_surname_husband = data.surname_husband;
      this.legal_guardian_identity_card = data.identity_card;
      this.legal_guardian_city_identity_card = data.city_identity_card_id;
      this.legal_guardian_gender = data.gender;
    },
    change_applicant: function() {
      // let modality_id_ =
      let modality_id = this.retFun.modality_id;
      if (this.applicant_type == "2") {
        this.show_advisor_form = !this.show_advisor_form;
        this.show_apoderado_form = false;
        this.resetAffiliate();
        return;
      }
      if (this.applicant_type == "3") {
        this.show_apoderado_form = !this.show_apoderado_form;
        this.show_advisor_form = false;
        if (modality_id == 4 || modality_id == 1) {
          this.setDataSpouse();
        } else {
          this.setDataAffilate();
        }
        return;
      }
      if (this.applicant_type == "1") {
        this.show_apoderado_form = false;
        this.show_advisor_form = false;
        if (modality_id == 4 || modality_id == 1) {
          this.setDataSpouse();
        } else {
          this.setDataAffilate();
        }
        return;
      }
    },
    resetAffiliate: function() {
      this.applicant_first_name = "";
      this.applicant_second_name = "";
      this.applicant_last_name = "";
      this.applicant_mothers_last_name = "";
      this.applicant_surname_husband = "";
      this.applicant_surname_husband = "";
      this.applicant_identity_card = "";
      this.applicant_city_identity_card_id = "";
      this.applicant_gender = "";
      this.applicant_birth_date = "";
      this.applicant_cell_phone_numbers = [{ value: null }];
      this.applicant_phone_numbers = [{ value: null }];
    },
    setDataAffilate: function() {
      this.applicant_first_name = this.affiliate.first_name;
      this.applicant_second_name = this.affiliate.second_name;
      this.applicant_last_name = this.affiliate.last_name;
      this.applicant_mothers_last_name = this.affiliate.mothers_last_name;
      this.applicant_surname_husband = this.affiliate.surname_husband;
      (this.applicant_surname_husband = this.affiliate.surname_husband),
        (this.applicant_identity_card = this.affiliate.identity_card);
      this.applicant_city_identity_card_id = this.affiliate.city_identity_card_id;
      this.applicant_gender = this.affiliate.gender;
      this.applicant_birth_date = this.affiliate.birth_date;
      this.applicant_phone_numbers = !!this.affiliate.phone_number
        ? this.parsePhone(this.affiliate.phone_number.split(","))
        : [{ value: null }];
      this.applicant_cell_phone_numbers = !!this.affiliate.cell_phone_number
        ? this.parsePhone(this.affiliate.cell_phone_number.split(","))
        : [{ value: null }];
    },
    parsePhone(phones) {
      return phones.map(phone => {
        return {
          value: phone.trim()
        };
      });
    },
    setDataSpouse: function() {
      this.applicant_first_name = this.spouse.first_name;
      this.applicant_second_name = this.spouse.second_name;
      this.applicant_last_name = this.spouse.last_name;
      this.applicant_mothers_last_name = this.spouse.mothers_last_name;
      (this.applicant_surname_husband = this.spouse.surname_husband),
        (this.applicant_identity_card = this.spouse.identity_card);
      this.applicant_city_identity_card_id = this.spouse.city_identity_card_id;
      this.applicant_gender = this.setSpouseGender();
      this.applicant_birth_date = this.spouse.birth_date;
    },
    setSpouseGender() {
      return this.affiliate.gender == "M" ? "F" : "M";
    }
  }
};
</script>