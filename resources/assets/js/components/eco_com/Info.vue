<template>
  <div class="col-md-12">
    <div class="ibox">
      <div class="ibox-title">
        <h2 class="pull-left">Información del Trámite</h2>
        <div class="ibox-tools">
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
export default {
  props: [
    "ecoCom",
    "ecoComProcedure",
    "states",
    "cities",
    "permissions",
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
        category_id: this.ecoCom.category_id
      },
      editing: false,
      show_spinner: false,
      clone: {}
    };
  },
  mounted() {
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
          console.log(response);
          this.editing = false;
          this.show_spinner = false;
          this.form = response.data;
          flash("Información del Trámite Actualizada");
        })
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
    }
  }
};
</script>

<style>
</style>
