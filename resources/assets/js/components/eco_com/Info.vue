<template>
  <div class="col-md-12">
    <div class="ibox">
      <div class="ibox-title">
        <h2 class="pull-left">Información del Trámite <i :class="{'fa fa-home': ecoCom.eco_com_state.id == 17 || ecoCom.eco_com_state.id == 29 }"></i> </h2>
        <div class="ibox-tools">
          <span v-if="roleId === 5">
          <button
            data-animation="flip"
            data-toggle="tooltip"
            title="Cambiar a estado pagado"
            class="btn btn-primary"
            @click="estadoPagado()"
            :disabled="ecoCom.eco_com_state_id != 28 && ecoCom.eco_com_state_id != 29"
          >
            <i class="fa fa-dollar"></i> Cambiar a estado pagado
          </button>
          </span>
          <!--<span v-if="ecoCom.eco_com_state_id === 16 && ecoCom.procedure_date == null && roleId === 4 &&  ecoCom.wf_current_state_id == 3">
            <button
            data-animation="flip"
            data-toggle="tooltip"
            title="Cambiar a estado habilitado"
            class="btn btn-primary"
            @click="estadoPagadoObservado()"
          >
            <i class="fa fa-dollar"></i> Cambiar a estado habilitado
          </button>
          </span>-->
          <button
            data-animation="flip"
            data-toggle="tooltip"
            title="Form solicitud de pago"
            class="btn btn-primary"
            @click="formSolicitudPago()"
          >
            <i class="fa fa-print"></i> Imprimir solicitud de pago
          </button>
          <button
            data-animation="flip"
            data-toggle="tooltip"
            title="Certificación pago"
            class="btn btn-primary"
            @click="certificacionPago()"
            :disabled="ecoCom.eco_com_state.eco_com_state_type_id != 1"
          >
            <i class="fa fa-print"></i> Boleta de pago
          </button>
          <button
            data-animation="flip"
            data-toggle="tooltip"
            title="Certificación pago pequeño"
            class="btn btn-primary"
            @click="certificacionPagoCorto()"
            :disabled="ecoCom.eco_com_state.eco_com_state_type_id != 1"
          >
            <i class="fa fa-print"></i> Boleta de pago pequeño
          </button>
          <button
            data-toggle="tooltip"
            title="Eliminar Trámite"
            v-if="editing"
            class="btn btn-danger"
            @click="deleteEcoCom()"
            :disabled="!can('delete_economic_complement')"
          >
            <i class="fa fa-trash-o"></i>
          </button>
          <button
            data-animation="flip"
            data-toggle="tooltip"
            title="Editar"
            class="btn btn-primary"
            :class="editing ? 'active': ''"
            @click="edit()"
            :disabled="!can('update_economic_complement')"
            v-if="can('read_economic_complement')"
          >
            <i class="fa" :class="editing ?'fa-edit':'fa-pencil'"></i> Editar
          </button>
        </div>
      </div>
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-2">
            <label class="control-label">Gestion</label>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" :value="ecoComProcedure.year | year" disabled>
          </div>
          <div class="col-md-2">
            <label class="control-label">Semestre</label>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" v-model="ecoComProcedure.semester" disabled>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-2">
            <label class="control-label">Fecha de Recepcion</label>
          </div>
          <div class="col-md-4">
            <input
              type="date"
              v-model="form.reception_date"
              class="form-control"
              :disabled="!editing || (roleId != 5) "
            >
          </div>
          <div class="col-md-2">
            <label class="control-label">Regional</label>
          </div>
          <div class="col-md-4">
            <select class="form-control" v-model="form.city_id" name="city_id" :disabled="!editing" v-validate="'required'">
              <option v-for="(c, index) in cities" :value="c.id" :key="index">{{c.name}}</option>
            </select>
            <div v-show="errors.has('city_id')">
              <i class="fa fa-warning text-danger"></i>
              <span class="text-danger">{{ errors.first('city_id') }}</span>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-2">
            <label class="control-label">Tipo de Prestacion</label>
          </div>
          <div class="col-md-4">
            <input
              type="text"
              class="form-control"
              :value="ecoCom.eco_com_modality.shortened"
              disabled
            >
          </div>
          <div class="col-md-2">
            <label class="control-label">Mes Renta</label>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" :value="ecoComProcedure.rent_month" disabled>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-2">
            <label class="control-label">Tipo de Trámite</label>
          </div>
          <div class="col-md-4">
            <input
              type="text"
              :value="ecoCom.eco_com_reception_type.name"
              disabled
              class="form-control"
            >
          </div>
          <div class="col-md-2">
            <label class="control-label">Ubicacion</label>
          </div>
          <div class="col-md-4">{{ecoCom.wf_state.name}}</div>
          <!-- <div class="col-md-2">
                    <strong>Flujo:</strong>
                </div>
                <div class="col-md-4">
                    <span>
                        @{{ecoCom.workflow.name}}
                    </span>
          </div>-->
        </div>
        <br>
        <div class="row">
          <div class="col-md-2">
            <label class="control-label">Estado</label>
          </div>
          <div class="col-md-4">
            <input
              type="text"
              :value="ecoCom.eco_com_state.name"
              disabled
              class="form-control"
            >
          </div>
          <div class="col-md-2">
            <label class="control-label">Flujo</label>
          </div>
          <div class="col-md-4">{{ecoCom.workflow.name}}</div>
        </div>
        <br>
        <div>
          <!-- <div class="hr-line-dashed"></div>
          <h3>Información Policial del Titular:</h3> -->
          <div class="row">
            <div class="col-md-2" :class="{'has-error': errors.has('degree_id') }">
                <label class="control-label">Categoria</label>
            </div>
              <div class="col-md-4">
                <select class="form-control" v-model="form.category_id" name="category_id" :disabled="!editing" v-validate="'required'">
                  <option v-for="(c, index) in categories" :value="c.id" :key="index">{{c.name}}</option>
                </select>
                <div v-show="errors.has('category_id')">
                  <i class="fa fa-warning text-danger"></i>
                  <span class="text-danger">{{ errors.first('category_id') }}</span>
                </div>
            </div>
            <div class="col-md-2" :class="{'has-error': errors.has('category_id') }">
                <label class="control-label">Grado</label>
            </div>
              <div class="col-md-4">
                <select class="form-control" v-model="form.degree_id" name="degree_id" :disabled="!editing || (roleId != 5) " v-validate="'required'">
                  <option v-for="(c, index) in degrees" :value="c.id" :key="index">{{c.name}}</option>
                </select>
                <div v-show="errors.has('degree_id')">
                  <i class="fa fa-warning text-danger"></i>
                  <span class="text-danger">{{ errors.first('degree_id') }}</span>
                </div>
              </div>
            </div>
          <br>
          <div class="row">
            <div class="col-md-2"><label class="control-label">Años de servicio</label></div>
            <div class="col-md-4">
                <input type="number" v-model="form.service_years" name="service_years" class="form-control" :disabled="!editing" @change="getCalculateCategory()" v-validate="'min_value:0|max_value:100'" max="100" min="0">
                <div v-show="errors.has('service_years') && editing" >
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">@{{ errors.first('service_years') }}</span>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
           <div class="col-md-2"><label class="control-label">Meses de servicio</label></div>
            <div class="col-md-4">
                <input type="number" name="service_months" v-model="form.service_months" class="form-control" :disabled="!editing" @change="getCalculateCategory()" v-validate="'min_value:0|max_value:11'" min="0" max="11">
                <div v-show="errors.has('service_months') && editing" >
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">@{{ errors.first('service_months') }}</span>
                </div>
            </div>
             <div class="col-md-2"><label for="months_of_payment">Periodo </label></div>
             <div class="col-md-4">
             <input type="number" name="months_of_payment" id="months_of_payment" class="form-control" v-model="form.months_of_payment" :disabled="!editing || (roleId != 4)" v-validate="'min_value:0|max_value:6'" max="6" min="0" maxlength="2">
              <div v-show="errors.has('months_of_payment') && editing" >
                <i class="fa fa-warning text-danger"></i>
                <span class="text-danger">@{{ errors.first('months_of_payment') }}</span>
              </div>
            </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-4"> <label for="is_paid">PAGO POR UNICA VEZ</label> </div>
            <div class="col-md-2">
              <input class ="mediumCheckbox" type="checkbox" id="is_paid" v-model="form.is_paid" :disabled="!editing || (roleId != 4)">
            </div>
        </div>
        <br>
        <div id="once_payment_form" v-if="form.is_paid">
          <div class="row">
            <div class="col-md-2"><label for="type">Beneficiario</label></div>
              <div class="col-md-4">
                <select
                      class="form-control m-b"
                      name="type"
                      v-model="form.once_payment.type"
                      v-validate="'required'"
                      :disabled="!editing || (roleId != 4)"
                    >
                      <option value='V'>VIUDA</option>
                      <option value='H'>HUERFANO ABSOLUTO</option>
                    </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2"><label for="first_name">Primer Nombre</label></div>
              <div class="col-md-4">
                <input class="form-control" type="text" id="first_name" v-model="form.once_payment.first_name" :disabled="!editing || (roleId != 4)">
                <span v-if="!form.once_payment.first_name" class="text-danger">(*) El campo 'Primer Nombre' es obligatorio.</span>
              </div>
              <div class="col-md-2"><label for="second_name">Segundo nombre</label></div>
              <div class="col-md-4">
                <input class ="form-control" type="text" id="second_name" v-model="form.once_payment.second_name" :disabled="!editing || (roleId != 4)">
              </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2"><label for="last_name">Paterno</label></div>
              <div class="col-md-4">
                <input class ="form-control" type="text" id="last_name" v-model="form.once_payment.last_name" :disabled="!editing || (roleId != 4)">
              </div>
              <div class="col-md-2"><label for="mothers_last_name">Materno</label></div>
              <div class="col-md-4">
                <input class ="form-control" type="text" id="mothers_last_name" v-model="form.once_payment.mothers_last_name" :disabled="!editing || (roleId != 4)">
              </div>
              <span v-if="!form.once_payment.last_name && !form.once_payment.mothers_last_name" class="text-danger">(*) Al menos uno de los apellidos es obligatorio.</span>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2"><label for="surname_husband">Apellido de casad(o/a)</label></div>
              <div class="col-md-4">
                <input class ="form-control" type="text" id="surname_husband" v-model="form.once_payment.surname_husband" :disabled="!editing || (roleId != 4)">
              </div>
              <div class="col-md-2"><label for="identity_card">Carnet de Identidad</label></div>
              <div class="col-md-4">
                <input class ="form-control" type="text" id="identity_card" v-model="form.once_payment.identity_card" :disabled="!editing || (roleId != 4)">
                <span v-if="!form.once_payment.identity_card" class="text-danger">(*) El campo 'Carnet de identidad' es obligatorio.</span>
              </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2"><label for="due_date">Fecha de vencimiento</label></div>
              <div class="col-md-4">
                <input
                      type="text"
                      v-date
                      class="form-control"
                      v-model.trim="form.once_payment.due_date"
                      name="due_date"
                      :v-validate="'date_format:dd/MM/yyyy'"
                      :disabled="form.once_payment.is_duedate_undefined || !editing || (roleId != 4)"
                      >
              </div>
              <div class="col-md-2"><label for="is_duedate_undefined">Indefinido</label></div>
              <div class="col-md-4">
                <input 
                  class ="mediumCheckbox" 
                  type="checkbox" 
                  id="is_duedate_undefined" 
                  v-model="form.once_payment.is_duedate_undefined" 
                  :disabled="!editing || (roleId != 4)">
              </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2"><label for="birth_date">Fecha de Nacimiento</label></div>
              <div class="col-md-4">
                <input
                      type="text"
                      v-date
                      class="form-control"
                      v-model.trim="form.once_payment.birth_date"
                      name="birth_date"
                      :v-validate="'date_format:dd/MM/yyyy'"
                      :disabled="!editing"
                    >
              </div>
              <div class="col-md-2"><label for="city_birth_id">Ciudad de Nacimiento</label></div>
              <div class="col-md-4">
                <select
                    class="form-control"
                    name="city_birth_id"
                    v-model.trim="form.once_payment.city_birth_id"
                    v-validate="'required'"
                    :disabled="!editing"
                  >
                    <option :value="null"></option>
                    <option v-for="city in cities" :value="city.id" :key="city.id">{{ city.name }}</option>
                  </select>
                  <span v-if="!form.once_payment.city_birth_id" class="text-danger">(*) El campo 'Ciudad de Nacimiento' es obligatorio.</span>
              </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2"><label for="nua">Nua</label></div>
              <div class="col-md-4">
                <input class ="form-control" type="text" id="nua" v-model="form.once_payment.nua" :disabled="!editing || (roleId != 4)">
              </div>
              <div class="col-md-2"><label for="gender">Genero</label></div>
              <div class="col-md-4">
                <select
                      class="form-control m-b"
                      name="gender"
                      v-model.trim="form.once_payment.gender"
                      v-validate="'required'"
                      :disabled="!editing || (roleId != 4)"
                    >
                      <option value="M">Masculino</option>
                      <option value="F">Femenino</option>
                    </select>
              </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2"><label for="civil_status">Estado Civil</label></div>
              <div class="col-md-4">
                <select
                      class="form-control m-b"
                      name="civil_status"
                      v-model.trim="form.once_payment.civil_status"
                      v-validate="'required'"
                      :disabled="!editing || (roleId != 4)"
                    >
                      <option value="C">Casado</option>
                      <option value="S">Soltero</option>
                      <option value="V">Viudo</option>
                      <option value="D">Divorciado</option>
                    </select>
              </div>
              <div class="col-md-2"><label for="phone_number">Numero de Telefono</label></div>
              <div class="col-md-4">
                <input class ="form-control" type="text" id="phone_number" v-phone v-model="form.once_payment.phone_number" :disabled="!editing || (roleId != 4)">
              </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2"><label for="cell_phone_number">Numero de Celular</label></div>
              <div class="col-md-4">
                <input class ="form-control" type="text" id="cell_phone_number" v-cell-phone v-model="form.once_payment.cell_phone_number" :disabled="!editing || (roleId != 4)">
                <span v-if="!form.once_payment.cell_phone_number" class="text-danger">(*) El campo 'Número de Celular' es obligatorio.</span>
              </div>
              <div class="col-md-2"><label for="date_death">Fecha de Fallecimiento</label></div>
              <div class="col-md-4">
                <input
                      type="text"
                      v-date
                      class="form-control"
                      v-model.trim="form.once_payment.date_death"
                      name="date_death"
                      :v-validate="'date_format:dd/MM/yyyy'"
                      :disabled="!editing || (roleId != 4)"
                    >
              </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2"><label for="reason_death">Razon de Fallecimiento</label></div>
              <div class="col-md-4">
                <input class ="form-control" type="text" id="reason_death" v-model="form.once_payment.reason_death" :disabled="!editing || (roleId != 4)">
                <span v-if="!form.once_payment.reason_death" class="text-danger">(*) El campo 'Razon de Fallecimiento' es obligatorio.</span>
              </div>
              <div class="col-md-2"><label for="death_certificate_number">Numero de Certificado de defunción</label></div>
              <div class="col-md-4">
                <input class ="form-control" type="text" id="death_certificate_number" v-model="form.once_payment.death_certificate_number" :disabled="!editing || (roleId != 4)">
                <span v-if="!form.once_payment.death_certificate_number" class="text-danger">(*) El campo 'Número de certificado de defunción' es obligatorio.</span>
              </div>
          </div>
        </div>
        <br>
         <div class="row" v-show="editing">
          <div class="col-md-4" >
            <label class="control-label">ACTUALIZAR ESTADO DEL TRAMITE:</label>
          </div>
         </div>
         <br>
        <div class="row">
          <div class="col-md-4">
          <label for="eco_com_state_id">EN PROCESO</label>
          </div>
          <div class="col-md-2">
          <input class ="mediumCheckbox"
          type="radio" 
          id="eco_com_state_id" 
          v-model="form.eco_com_state_id" 
          value='16'
          :disabled="!editing || (roleId != 4)" >
          </div>
        </div>
         <br>
        <div class="row">
          <div class="col-md-4">
          <label for="eco_com_state_id">HABILITADO PARA PAGO A DOMICILIO</label>
          </div>
          <div class="col-md-2">
          <input class ="mediumCheckbox"
          type="radio" 
          id="eco_com_state_id" 
          v-model="form.eco_com_state_id" 
          value='29'
          :disabled="!editing || (roleId != 4)" >
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
          <label for="eco_com_state_id">HABILITADO PARA PAGO MEDIANTE CHEQUE</label>
          </div>
          <div class="col-md-2">
          <input class ="mediumCheckbox"
          type="radio" 
          id="eco_com_state_id" 
          v-model="form.eco_com_state_id" 
          value='28'
          :disabled="!editing || (roleId != 4)" >
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
          <label for="eco_com_state_id">NO PAGADO - REVERTIDO</label>
          </div>
          <div class="col-md-2">
          <input class ="mediumCheckbox"
          type="radio" 
          id="eco_com_state_id" 
          v-model="form.eco_com_state_id" 
          value='23'
          :disabled="!editing || (roleId != 4)" >
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
          <label for="eco_com_state_id">NO PAGADO - EXCLUIDO</label>
          </div>
          <div class="col-md-2">
          <input class ="mediumCheckbox"
          type="radio" 
          id="eco_com_state_id" 
          v-model="form.eco_com_state_id" 
          value='12'
          :disabled="!editing || (roleId != 4)" >
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
          <label for="eco_com_state_id">NO PAGADO - OBSERVACIÓN DOCUMENTAL</label>
          </div>
          <div class="col-md-2">
          <input class ="mediumCheckbox"
          type="radio" 
          id="eco_com_state_id" 
          v-model="form.eco_com_state_id" 
          value='27'
          :disabled="!editing || (roleId != 4)" >
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
          <label for="eco_com_state_id">NO PAGADO - FALLECIDO</label>
          </div>
          <div class="col-md-2">
          <input class ="mediumCheckbox"
          type="radio"
          id="eco_com_state_id"
          v-model="form.eco_com_state_id"
          value='30'
          :disabled="!editing || (roleId != 4)" >
          </div>
        </div>
        <br>
        </div>
        <div v-if="editing">
          <div class="text-center">
            <button class="btn btn-danger" type="button" @click="cancel()">
              <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
              <span class="bold">Cancelar</span>
            </button>
            <button class="btn btn-primary" type="button" @click="update()">
              <i class="fa fa-check-circle"></i>&nbsp;Guardar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { flashErrors, canOperation } from "../../helper";
import EstadoPagadoVue from './EstadoPagado.vue';
export default {
  props: [
    "ecoCom",
    "affiliate",
    "ecoComProcedure",
    "states",
    "cities",
    "permissions",
    "degrees",
    "categories",
    "roleId"
  ],
  data: function() {
  var defaultType = "V";
  var defaultGender = "M";
  var defaultCivilStatus = "S";
  var currentDate = new Date();
  var formattedDate = currentDate.getDate() + '/' + (currentDate.getMonth() + 1) + '/' + currentDate.getFullYear(); // Formato "dd/MM/yyyy"
  var duedate_undefined = false;

  var formData = {
    id: this.ecoCom.id,
    reception_date: this.ecoCom.reception_date,
    procedure_state_id: this.ecoCom.procedure_state_id,
    city_id: this.ecoCom.city_id,
    degree_id: this.ecoCom.degree_id,
    category_id: this.ecoCom.category_id,
    service_years: this.affiliate.service_years,
    service_months: this.affiliate.service_months,
    is_paid: this.ecoCom.is_paid,
    months_of_payment: this.ecoCom.months_of_payment,
    eco_com_state_id: this.ecoCom.eco_com_state_id,
    once_payment: {
      type: defaultType,
      gender: defaultGender,
      civil_status: defaultCivilStatus,
      due_date: formattedDate,
      birth_date: formattedDate,
      date_death: formattedDate,
      is_duedate_undefined: duedate_undefined
    }
  };

  // Comprueba si hay datos disponibles para eco_com_once_payment
  if (this.ecoCom.eco_com_once_payment) {
    formData.once_payment = this.ecoCom.eco_com_once_payment;
  }

  return {
    read: true,
    form: formData,
    editing: false,
    show_spinner: false,
    clone: {}
  };
},
  mounted() {
    //console.log(this.ecoCom.eco_com_state_id==17 ? true: false);
    document.querySelectorAll(".tab-eco-com")[0].addEventListener(
      "click",
      () => {
        this.$scrollTo("#wrapper");
      },
      { passive: true }
    );
  },
  methods: {
    can(operation) {
      return canOperation(operation, this.permissions);
    },
    /*pagodomicilio() {
      if(this.ecoCom.eco_com_state_id==17){
      alert("El estado del tramite cambiara a CREADO EN PROCESO");
      }else{
      alert("El estado del tramite cambiara a PAGADO EN DOMICILIO");
      }
    },*/
    
    async homepayment() {
     if(this.ecoCom.eco_com_state_id==17){
      await this.$swal({   
        title: "El estado del tramite cambiara a CREADO EN PROCESO",
        confirmButtonColor: "#59B75C",
        confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
        showLoaderOnConfirm: true,
        eco_com_state_id :true
       
      });
      }else{
        await this.$swal({
        title: "El estado del tramite cambiara a PAGADO EN DOMICILIO",
        confirmButtonColor: "#59B75C",
        confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
        showLoaderOnConfirm: true,
        eco_com_state_id: true
      });
      }
    },
    getCalculateCategory(){
				let years = this.form.service_years;
				let months = this.form.service_months;
				if (years < 0 || months < 0 || years >100 || months > 12 ) {
					return "error";
				}
				if (months > 0) {
					years++;
				}
				let category = this.categories.find(c =>{
					return c.from <= years && c.to >= years
				})
				if(!!category){
					this.form.category_id = category.id
				}
			},
    edit() {
      if (!this.can("update_economic_complement", this.permissions)) {
        return;
      }
      this.editing = !this.editing;
      this.clone = JSON.parse(JSON.stringify(this.form));
    },
    cancel() {
      this.form = this.clone;
      this.editing = false;
    },
    async update() {
      if (!this.can("update_eco_com_beneficiary", this.permissions)) {
        return;
      }
      await this.$validator.validateAll();
      if (this.$validator.errors.items.length) {
        flash("Campos requeridos: ", "error");
        return;
      }
      let uri = `/economic_complement_update_information`;
      this.show_spinner = true;
      await axios
        .patch(uri, this.form)
        .then(response => {
          this.editing = false;
          this.show_spinner = false;
          this.form = response.data;
          flash("Información del Trámite Actualizada");
        })
        location.reload()
        .catch(response => {
          flashErrors("Error al procesar: ", error.response.data.errors);
          this.show_spinner = false;
        });
        
    },
    async deleteEcoCom() {
      if (!this.can("delete_economic_complement", this.permissions)) {
        flash("No tiene permisos para eliminar el Trámite", "error");
        return;
      }
      await this.$swal({
        title: "¿Está seguro de Eliminar el Trámite?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#59B75C",
        cancelButtonColor: "#EC4758",
        confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
        cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return axios
            .delete(`/eco_com/${this.ecoCom.id}`)
            .then(response => {
              if (response.status != 204) {
                throw new Error(response.errors);
              }
              return response.status;
            })
            .catch(error => {
              this.$swal.showValidationError(
                `Solicitud fallida: ${error.response.data.errors}`
              );
            });
        },
        allowOutsideClick: () => !this.$swal.isLoading()
      }).then(result => {
        if (result.value) {
          this.$swal({
            type: "success",
            title: "El Trámite fue eliminado correctamente.",
            showConfirmButton: false,
            timer: 1000
          });
          setTimeout(() => {
            window.location = "/";
          }, 1500);
        }
      });
    },
    async estadoPagado() {
      this.show_spinner = true;
      await axios
        .delete(`/eco_com_cambiar_estado_individual/${this.ecoCom.id}`)
        .then(response => {
          this.form = response.data;
          flash("Información del Trámite Actualizada");
        })
        location.reload()
         .catch(response => {
          flashErrors("Error al procesar: ", error.response.data.errors);
          this.show_spinner = false;
        });
    },
    async estadoPagadoObservado() {
      this.show_spinner = true;
      await axios
        .get(`/eco_com_cambiar_habilitado/${this.ecoCom.id}`)
        .then(response => {
          this.form = response.data;
          flash("Información del Trámite Actualizada");
        })
        location.reload()
        .catch(response => {
          flashErrors("Error al procesar: ", error.response.data.errors);
          this.show_spinner = false;
        });
    },
    async certificacionPago(){
      printJS({printable:'/eco_com/'+this.ecoCom.id+'/print/paid_cetificate', type:'pdf', showModal:true});
    },
    async certificacionPagoCorto(){
      printJS({printable:'/eco_com/'+this.ecoCom.id+'/print/paid_cetificate_short', type:'pdf', showModal:true});
    },
    async formSolicitudPago(){
      printJS({printable:'/eco_com/'+this.ecoCom.id+'/print/reception', type:'pdf', showModal:true});
    }
  }
};
</script>
<style>
</style>