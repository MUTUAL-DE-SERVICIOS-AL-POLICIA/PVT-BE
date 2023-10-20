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

            <div class="col-md-4"> <label for="is_paid">PAGO POR UNICA VEZ</label> </div>
            <div class="col-md-2">
              <input class ="mediumCheckbox" type="checkbox" id="is_paid" v-model="form.is_paid" :disabled="!editing || (roleId != 4)">
            </div>
            <!-- <div class="col-md-2"><label class="control-label">Ente gestor:</label></div>
            <div class="col-md-4">
                {!! Form::select('pension_entity_id', $pension_entities, null, ['placeholder' => 'Seleccione el ente gestor', 'class' => 'form-control','v-model'=> 'form.pension_entity_id',':disabled'=>'!editing' ]) !!}
                <div v-show="errors.has('pension_entity_id') && editing" >
                    <i class="fa fa-warning text-danger"></i>
                    <span class="text-danger">@{{ errors.first('pension_entity_id') }}</span>
                </div>
            </div> -->
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
  data() {
    return {
      read: true,
      form: {
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
      },
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
    async formSolicitudPago(){
      printJS({printable:'/eco_com/'+this.ecoCom.id+'/print/reception', type:'pdf', showModal:true});
    }
  }
};
</script>
<style>
</style>
