<template>
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <legend>Datos del Apoderado ({{getNameLegalGuardianType}})</legend>
        <div class="row">
          <div class="col-md-6">
            <div class="col-md-4">
              <label class="control-label pointer" for="has_legal_guardian">Tiene Apoderado</label>
            </div>
            <div class="col-md-8">
              <input
                class="mediumCheckbox"
                type="checkbox"
                v-model="has_legal_guardian"
                name="has_legal_guardian"
                id="has_legal_guardian"
                @change="toggleLegalGuardian()"
              >
            </div>
          </div>
          <div class="col-md-6" v-if="has_legal_guardian">
            <div class="col-md-4">
              <label class="control-label">Tipo de Apoderado</label>
            </div>
            <div class="col-md-8">
              <select
                name="legal_guardian_type_id"
                v-model="legal_guardian_type_id"
                class="form-control"
              >
                <option :value="null"></option>
                <option
                  v-for="lt in ecoComLegalGuardianTypes"
                  :key="lt.id"
                  :value="lt.id"
                >{{ lt.name }}</option>
              </select>
            </div>
          </div>
        </div>
        <div class="hr-line-dashed"></div>
        <br>
        <div v-if="has_legal_guardian && legal_guardian_type_id">
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
                    @keypress.enter="searchLegalGuardian()"
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
                  :disabled="legalGuardian.is_duedate_undefined"
                  name="legal_guardian_due_date"
                  v-model.trim="legalGuardian.due_date"
                  class="form-control"
                  v-date
                  v-validate="'date_format:dd/MM/yyyy|max_due_date'"
                >
                <br>
                <input
                  class="mediumCheckbox"
                  type="checkbox"
                  name="legal_guardian_is_duedate_undefined"
                  v-model="legalGuardian.is_duedate_undefined"
                  id="legal_guardian_is_duedate_undefined"
                >
                <label for="legal_guardian_is_duedate_undefined" class="pointer v-middle">Indefinido</label>
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
                        >
                        <span class="input-group-btn">
                          <button
                            class="btn btn-danger"
                            v-show="legalGuardian.phone_number.length > 1"
                            @click="deleteLegalGuardianPhoneNumber(phone)"
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
                <label class="control-label">Celular</label>
              </div>
              <div class="col-md-8">
                <div class="row">
                  <div class="col-md-2">
                    <button
                      class="btn btn-success"
                      type="button"
                      @click="addLegalGuardianCellPhoneNumber()"
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
                        >
                        <span class="input-group-btn">
                          <button
                            v-show="legalGuardian.cell_phone_number.length > 1"
                            class="btn btn-danger"
                            @click="deleteLegalGuardianCellPhoneNumber(cell_phone)"
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
        </div>
      </div>
    </div>
    <div class="ibox float-e-margins" v-if="modalityId != 29">
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-12">
            <legend>Datos del Afiliado (Causahabiente)</legend>
            <div class="row">
              <div class="col-md-6">
                <div
                  class="form-group"
                  :class="{'has-error': errors.has('affiliate_identity_card') }"
                >
                  <div class="col-md-4">
                    <label class="control-label">Cédula de Identidad</label>
                  </div>
                  <div class="col-md-8">
                    <div class="input-group">
                      <input
                        type="text"
                        name="affiliate_identity_card"
                        v-model.trim="affiliate.identity_card"
                        class="form-control"
                        v-validate="'required'"
                      >
                    </div>
                    <div v-show="errors.has('affiliate_identity_card')">
                      <i class="fa fa-warning text-danger"></i>
                      <span class="text-danger">{{ errors.first('affiliate_identity_card') }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6" :class="{'has-error': errors.has('affiliate_first_name') }">
                <div class="col-md-4">
                  <label class="control-label">Primer Nombre</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="affiliate_first_name"
                    v-model.trim="affiliate.first_name"
                    class="form-control"
                    v-validate="'required|alpha_space_quote'"
                  >
                  <div v-show="errors.has('affiliate_first_name')">
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('affiliate_first_name') }}</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6" :class="{'has-error': errors.has('affiliate_second_name')}">
                <div class="col-md-4">
                  <label class="control-label">Segundo Nombre</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="affiliate_second_name"
                    v-model.trim="affiliate.second_name"
                    class="form-control"
                    v-validate="'alpha_space_quote'"
                  >
                  <div v-show="errors.has('affiliate_second_name')">
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('affiliate_second_name') }}</span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6" :class="{'has-error': errors.has('affiliate_last_name')}">
                <div class="col-md-4">
                  <label class="control-label">Apellido Paterno</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="affiliate_last_name"
                    v-model.trim="affiliate.last_name"
                    class="form-control"
                    v-validate="'alpha_space_quote'"
                  >
                  <div v-show="errors.has('affiliate_last_name')">
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('affiliate_last_name') }}</span>
                  </div>
                </div>
              </div>
              <div
                class="col-md-6"
                :class="{'has-error': errors.has('affiliate_mothers_last_name')}"
              >
                <div class="col-md-4">
                  <label class="control-label">Apellido Materno</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="affiliate_mothers_last_name"
                    v-model.trim="affiliate.mothers_last_name"
                    class="form-control"
                    v-validate="'alpha_space_quote'"
                  >
                  <div v-show="errors.has('affiliate_mothers_last_name')">
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('affiliate_mothers_last_name') }}</span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6" :class="{'has-error': errors.has('affiliate_surname_husband')}">
                <div class="col-md-4">
                  <label class="control-label">Apellido de Casada</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="affiliate_surname_husband"
                    v-model.trim="affiliate.surname_husband"
                    class="form-control"
                    v-validate="'alpha_space_quote'"
                  >
                  <div v-show="errors.has('affiliate_surname_husband')">
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('affiliate_surname_husband') }}</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6" :class="{'has-error': errors.has('affiliate_gender') }">
                <div class="col-md-4">
                  <label class="control-label">Genero</label>
                </div>
                <div class="col-md-8">
                  <select
                    class="form-control m-b"
                    name="affiliate_gender"
                    v-model.trim="affiliate.gender"
                    v-validate="'required'"
                  >
                    <option :value="null"></option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                  </select>
                  <i v-show="errors.has('affiliate_gender')" class="fa fa-warning text-danger"></i>
                  <span
                    v-show="errors.has('affiliate_gender')"
                    class="text-danger"
                  >{{ errors.first('affiliate_gender') }}</span>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6" :class="{'has-error': errors.has('affiliate_birth_date') }">
                <div class="col-md-4">
                  <label class="control-label">Fecha de Nacimiento</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    v-date
                    class="form-control"
                    v-model.trim="affiliate.birth_date"
                    name="affiliate_birth_date"
                    v-validate="'date_format:dd/MM/yyyy|max_current_date'"
                  >
                  <div>
                    <i
                      v-show="errors.has('affiliate_birth_date')"
                      class="fa fa-warning text-danger"
                    ></i>
                    <span class="text-danger">{{ errors.first('affiliate_birth_date') }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-12">
            <legend>Datos Policiales del Afiliado (Causahabiente)</legend>
            <div class="row">
              <div class="col-md-6" :class="{'has-error': errors.has('affiliate_category_id') }">
                <div class="col-md-4">
                  <label class="control-label">Categoria</label>
                </div>
                <div class="col-md-8">
                  <select
                    class="form-control"
                    name="affiliate_category_id"
                    v-model.trim="affiliate.category_id"
                    v-validate="'required'"
                    :disabled="true"
                  >
                    <option :value="null"></option>
                    <option v-for="c in categories" :value="c.id" :key="c.id">{{ c.name }}</option>
                  </select>
                  <input type="hidden" name="affiliate_category_id" :value="affiliate.category_id">
                  <div v-show="errors.has('affiliate_category_id')">
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('affiliate_category_id') }}</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6" :class="{'has-error': errors.has('affiliate_degree_id') }">
                <div class="col-md-4">
                  <label class="control-label">Grado</label>
                </div>
                <div class="col-md-8">
                  <select
                    class="form-control"
                    name="affiliate_degree_id"
                    v-model.trim="affiliate.degree_id"
                    v-validate="'required'"
                    :disabled="!validationRoles(roleId)"
                  >
                    <option :value="null"></option>
                    <option v-for="d in degrees" :value="d.id" :key="d.id">{{ d.name }}</option>
                  </select>
                  <input type="hidden" name="affiliate_degree_id" v-if="validationRoles(roleId)" :value="affiliate.degree_id">
                  <div v-show="errors.has('affiliate_degree_id')">
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('affiliate_degree_id') }}</span>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-lg-6">
                <div
                  class="col-lg-6"
                  :class="{'has-error': errors.has('affiliate_service_years') }"
                >
                  <div class="col-md-6">
                    <label class="control-label">Años de servicio</label>
                  </div>
                  <div class="col-md-6">
                    <input
                      v-validate="serviceYearsRequired"
                      type="number"
                      v-model="affiliate.service_years"
                      name="affiliate_service_years"
                      class="form-control"
                      @change="calculateCategory()"
                      max="100"
                      min="0"
                      :readonly="!validationRoles(roleId)"
                    >
                      <!-- :disabled="!((isInclusion || isReEnablement) && (roleId == 22 || roleId == 23 || roleId == 24 || roleId == 25 || roleId == 26 || roleId == 27 || roleId == 52 || roleId == 68))" -->
                    <div v-show="errors.has('affiliate_service_years')">
                      <i class="fa fa-warning text-danger"></i>
                      <span class="text-danger">{{ errors.first('affiliate_service_years') }}</span>
                    </div>
                  </div>
                </div>
                <div
                  class="col-lg-6"
                  :class="{'has-error': errors.has('affiliate_service-months') }"
                >
                  <div class="col-md-6">
                    <label class="control-label">Meses de servicio</label>
                  </div>
                  <div class="col-md-6">
                    <input
                      v-validate="serviceMonthsRequired"
                      type="number"
                      v-model="affiliate.service_months"
                      name="affiliate_service_months"
                      class="form-control"
                      @change="calculateCategory()"
                      max="11"
                      min="0"
                      :readonly="isHabitual || !((roleId == 22) || (roleId == 23) || (roleId == 24) || (roleId == 25) || (roleId == 26) || (roleId == 27) || (roleId == 52) || (roleId == 68))"
                    >
                    <div v-show="errors.has('affiliate_service_months')">
                      <i class="fa fa-warning text-danger"></i>
                      <span class="text-danger">{{ errors.first('affiliate_service_months') }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6" :class="{'has-error': errors.has('affiliate_date_derelict') }">
                <div class="col-md-4">
                  <label class="control-label">Fecha de desvinculaci&oacute;n</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="affiliate_date_derelict"
                    v-model="affiliate.date_derelict"
                    v-month-year
                    class="form-control"
                    v-validate.initial="'max_current_date_month_year'"
                    :readonly="isHabitual"
                  >
                  <div v-show="errors.has('affiliate_date_derelict')">
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('affiliate_date_derelict') }}</span>
                  </div>
                </div>
              </div>
            </div>
            <br>
          </div>
        </div>
      </div>
    </div>

    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-12">
            <legend>Datos Sigep</legend>
            <div class="row">
              <div class="col-md-6" :class="{'has-error': errors.has('affiliate_account_number') }">
                <div class="col-md-4">
                  <label class="control-label">Número de cuenta</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="affiliate_account_number"
                    v-model.trim="affiliate.account_number"
                    class="form-control"
                    :readonly="isHabitual"
                  >
                </div>
              </div>
              <div class="col-md-6" :class="{'has-error': errors.has('affiliate_financial_entity_id') }">
                <div class="col-md-4">
                  <label class="control-label">Entidad Financiera</label>
                </div>
                <div class="col-md-8">
                  <select
                    class="form-control"
                    name="affiliate_financial_entity_id"
                    v-model.trim="affiliate.financial_entity_id"
                    :disabled="isHabitual"
                  >
                    <option :value="null"></option>
                    <option v-for="c in financialEntities" :value="c.id" :key="c.id">{{ c.name }}</option>
                  </select>
                  <input type="hidden" :value="affiliate.financial_entity_id" name="affiliate_financial_entity_id" v-if="isHabitual">
                  <div v-show="errors.has('affiliate_financial_entity_id')">
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">{{ errors.first('affiliate_financial_entity_id') }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="col-md-4">
                  <label class="control-label">Estado Sigep</label>
                </div>
                <div class="col-md-8">
                  <select
                    class="form-control"
                    name="affiliate_account_number_sigep_status"
                    v-model.trim="affiliate.sigep_status"
                    :disabled="isHabitual"
                  >
                    <option :value="null"></option>
                    <option v-for="c in sigepStatus" :value="c.id" :key="c.id">{{ c.name }}</option>
                  </select>
                  <input name="affiliate_account_number_sigep_status" v-if="isHabitual" type="hidden" :value="affiliate.sigep_status">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-12">
            <legend>Datos del Beneficiario</legend>
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
                        @keypress.enter="searchApplicant()"
                      >
                      <span class="input-group-btn">
                        <button
                          class="btn"
                          :class="errors.has('eco_com_beneficiary_identity_card') ? 'btn-danger' : 'btn-primary' "
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
                    :disabled="ecoComBeneficiary.is_duedate_undefined"
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
                          >
                          <span class="input-group-btn">
                            <button
                              class="btn btn-danger"
                              v-show="ecoComBeneficiary.phone_number.length > 1"
                              @click="deleteBeneficiaryPhoneNumber(phone)"
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
                      <button
                        class="btn btn-success"
                        type="button"
                        @click="addBeneficiaryCellPhoneNumber()"
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
                          >
                          <span class="input-group-btn">
                            <button
                              v-show="ecoComBeneficiary.cell_phone_number.length > 1"
                              class="btn btn-danger"
                              @click="deleteBeneficiaryCellPhoneNumber(cell_phone)"
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
              <input
                type="hidden"
                name="eco_com_beneficiary_address_id"
                v-model="ecoComBeneficiary.address.id"
              >
              <div class="col-md-3">
                <div class="col-md-4">
                  <label class="control-label">Ciudad</label>
                </div>
                <div class="col-md-8">
                  <select
                    class="form-control"
                    v-model.trim="ecoComBeneficiary.address.city_address_id"
                    name="eco_com_beneficiary_city_address_id"
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
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="ibox float-e-margins" v-if="modalityId == 30 && receptionType.id == 2">
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-12">
            <legend>Datos SERECI</legend>
            <div class="row">
              <div class="col-md-6" :class="{'has-error': errors.has('eco_com_beneficiary_official') }">
                <div class="col-md-4">
                  <label class="control-label">Oficialia</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="eco_com_beneficiary_official"
                    v-model.trim="ecoComBeneficiary.official"
                    class="form-control"
                  >
                </div>
              </div>
              <div class="col-md-6" :class="{'has-error': errors.has('eco_com_beneficiary_book') }">
                <div class="col-md-4">
                  <label class="control-label">Libro</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="eco_com_beneficiary_book"
                    v-model.trim="ecoComBeneficiary.book"
                    class="form-control"
                  >
                </div>
              </div>
              <div class="col-md-6" :class="{'has-error': errors.has('eco_com_beneficiary_departure') }">
                <div class="col-md-4">
                  <label class="control-label">Partida</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="eco_com_beneficiary_departure"
                    v-model.trim="ecoComBeneficiary.departure"
                    class="form-control"
                  >
                </div>
              </div>
              <div class="col-md-6" :class="{'has-error': errors.has('eco_com_beneficiary_marriage_date') }">
                <div class="col-md-4">
                  <label class="control-label">Fecha de Matrimonio</label>
                </div>
                <div class="col-md-8">
                  <input
                    type="text"
                    name="eco_com_beneficiary_marriage_date"
                    v-model.trim="ecoComBeneficiary.marriage_date"
                    class="form-control"
                    v-validate="'date_format:dd/MM/yyyy'"
                    v-date
                  >
                </div>
              </div>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapMutations, mapState } from "vuex";
export default {
  props: ["cities", "degrees", "categories", "ecoComLegalGuardianTypes", "financialEntities","roleId"],
  data() {
    return {
      civilStatus: [
        { id: "C", name: "Casado (a)" },
        { id: "S", name: "Soltero (a)" },
        { id: "V", name: "Viudo (a)" },
        { id: "D", name: "Divorciado (a)" }
      ],
      sigepStatus: [
        { id: "SIN REGISTRO", name: "SIN REGISTRO" },
        { id: "REGISTRO OBSERVADO", name: "REGISTRO OBSERVADO" },
        { id: "ACTIVO", name: "ACTIVO" },
        { id: "ACTIVO-PAGO-VENTANILLA", name: "ACTIVO-PAGO-VENTANILLA" }
      ],
      has_legal_guardian: false,
      legal_guardian_type_id: null
    };
  },
  computed: {
    isHabitual() {
      return this.receptionType.id == 1;
    },
    isReEnablement() {
      return this.receptionType.id == 3;
    },
    isInclusion() {
      return this.receptionType.id == 2;
    },
    serviceYearsRequired() {
      if (!this.isHabitual) {
        return "required|min_value:0|max_value:100";
      }
      return "min_value:0|max_value:100";
    },
    serviceMonthsRequired() {
      if (!this.isHabitual) {
        return "required|min_value:0|max_value:11";
      }
      return "min_value:0|max_value:11";
    },
    ecoComBeneficiary() {
      return this.$store.state.ecoComForm.beneficiary;
    },
    legalGuardian() {
      return this.$store.state.ecoComForm.legalGuardian;
    },
    affiliate() {
      return this.$store.state.ecoComForm.affiliate;
    },
    modalityId() {
      return this.$store.state.ecoComForm.modality_id;
    },
    receptionType() {
      return this.$store.state.ecoComForm.receptionType;
    },
    getNameLegalGuardianType() {
      if (this.legal_guardian_type_id) {
        return this.ecoComLegalGuardianTypes.find(
          x => x.id == this.legal_guardian_type_id
        ).name;
      }
      return null;
    }
  },
  methods: {
    calculateCategory() {
      let years = this.affiliate.service_years;
      let months = this.affiliate.service_months;
      if (years < 0 || months < 0 || years > 100 || months > 12) {
        return "error";
      }
      if (months > 0) {
        years++;
      }
      let category = this.categories.find(c => {
        return c.from <= years && c.to >= years;
      });
      if (!!category) {
        this.$store.commit("ecoComForm/setAffiliateCategoryId", category.id);
      }
    },
    toggleLegalGuardian() {
      if (this.has_legal_guardian == true) {
        this.$store.commit("ecoComForm/setLegalGuardian", {});
      }
    },
    ...mapMutations("ecoComForm", [
      "addBeneficiaryPhoneNumber",
      "deleteBeneficiaryPhoneNumber",
      "addBeneficiaryCellPhoneNumber",
      "deleteBeneficiaryCellPhoneNumber",
      "addLegalGuardianPhoneNumber",
      "deleteLegalGuardianPhoneNumber",
      "addLegalGuardianCellPhoneNumber",
      "deleteLegalGuardianCellPhoneNumber"
    ]),
    async searchApplicant() {
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
    },
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
    },
    validationRoles(role) {
      let rolesPermited = [2, 22, 23, 24, 25, 26, 27, 52, 68]
      if(this.isInclusion || this.isReEnablement) {
        return rolesPermited.indexOf(parseInt(role)) !== -1
      }
      return false
    }
  }
};
</script>