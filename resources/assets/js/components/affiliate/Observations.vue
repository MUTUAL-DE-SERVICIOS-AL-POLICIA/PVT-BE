<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h2 class="pull-left">Observaciones</h2>
          <div class="ibox-tools">
            <button
              class="btn btn-primary"
              @click="createObs()"
              data-toggle="tooltip"
              title="Adicionar Observacion"
              :disabled="!can('create_observation_type')"
              v-if="can('read_observation_type')"
            >
              <i class="fa fa-plus"></i> Adicionar
            </button>
          </div>
        </div>
        <div class="ibox-content" v-if="can('read_observation_type')" >
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr>
                <th>Nro.</th>
                <th>Fecha</th>
                <th>Tipo de Observacion</th>
                <th>Mensaje</th>
                <th>Estado</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(o, index) in observations" :key="index" v-if="o.pivot.deleted_at==null">
                <td>{{index + 1}}</td>
                <td>{{ o.pivot.date | textDate }}</td>
                <td>
                  <span class="label" :class="getBadge(o.type)">{{ o.type }}</span>
                  {{ o.name }}
                </td>
                <td>{{ o.pivot.message }}</td>
                <!-- <td>
                  <span
                    class="badge"
                    :class="{'badge-primary': o.pivot.enabled, 'badge-danger':!o.pivot.enabled }"
                  >{{ o.pivot.enabled ? "Subsanado" : "No subsanado" }}</span>
                </td> -->
                <td>
                  <div class="btn-group">
                    <button
                      data-toggle="dropdown"
                      class="btn btn-default btn-sm dropdown-toggle"
                    ><i class="fa fa-chevron-down"></i>  Opciones</button>
                    <ul class="dropdown-menu">
                      <li>
                        <a class="dropdown-item" href="#" @click.prevent="editObs(o)" :aria-disabled="!can('update_observation_type')"><i class="fa fa-pencil"></i> Editar</a>
                      </li>
                      <li>
                        <a class="dropdown-item" @click.prevent="deleteObs(o)" :aria-disabled="!can('delete_observation_type')" href="#"><i class="fa fa-times"></i> Eliminar</a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <input type="checkbox" v-model="showDeleteObservation" @change="getDeleteObservations()" id="show-delete-observation"><label for="show-delete-observation">Ver Observaciones Eliminadas</label>
          <table
            class="table table-striped table-hover table-bordered"
            v-if="showDeleteObservation"
          >
            <thead>
              <tr>
                <th>Nro.</th>
                <th>Fecha</th>
                <th>Tipo de Observacion</th>
                <th>Mensaje</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(o, index) in deleteObservations" :key="index">
                <td>{{ index+1 }}</td>
                <td>{{ o.date | textDate }}</td>
                <td>
                  <span class="label" :class="getBadge(o.type)">{{ o.type }}</span>
                  {{ o.name }}
                </td>
                <td>{{ o.message }}</td>
                <!-- <td>
                  <span
                    class="badge"
                    :class="{'badge-primary': o.enabled, 'badge-danger':!o.enabled }"
                  >{{ o.enabled ? "Subsanado" : "No subsanado" }}</span>
                </td> -->
              </tr>
            </tbody>
          </table>
        </div>
        <div class="ibox-content" v-else>
          <div class="alert alert-warning" >
              No tiene permisos para ver las observaciones.
          </div>
        </div>
      </div>
    </div>
    <modal name="observation-modal" class="p-sm" height="auto">
      <div class="ibox-title">
        <h1>{{ method == 'post' ? 'Agregar' : 'Editar'}} Observacion</h1>
      </div>
      <div class="row">
        <div class="col-md-12" :class="{'has-error': errors.has('observation_type_id')}">
          <div class="col-md-3">
            <label class="control-label">Tipo</label>
          </div>
          <div class="col-md-9">
            <select
              class="form-control m-b"
              name="observation_type_id"
              v-model="form.observationTypeId"
              v-validate.initial="'required'"
              :disabled="method == 'patch'"
              v-if="method == 'post'"
            >
              <option :value="null"></option>
              <option
                v-for="(o, index) in observationTypesFilter"
                :value="o.id"
                :key="index"
              >[{{ o.type }}] {{ o.name }}</option>
            </select>
            <select
              class="form-control m-b"
              name="observation_type_id"
              v-model="form.observationTypeId"
              v-validate.initial="'required'"
              :disabled="method == 'patch'"
              v-else
            >
              <option :value="null"></option>
              <option
                v-for="(o, index) in observationTypes"
                :value="o.id"
                :key="index"
              >[{{ o.type }}] {{ o.name }}</option>
            </select>
            <i v-show="errors.has('observation_type_id')" class="fa fa-warning text-danger"></i>
            <span
              v-show="errors.has('observation_type_id')"
              class="text-danger"
            >{{ errors.first('observation_type_id') }}</span>
          </div>
        </div>
        <div class="col-md-12" :class="{'has-error': errors.has('message') }">
          <div class="col-md-3">
            <label class="control-label">Mensaje</label>
          </div>
          <div class="col-md-9">
            <textarea
              cols="30"
              rows="10"
              name="message"
              v-model.trim="form.message"
              class="form-control"
              v-validate.initial="'required|min:10|max:250'"
            ></textarea>
            <div v-show="errors.has('message')">
              <i class="fa fa-warning text-danger"></i>
              <span class="text-danger">{{ errors.first('message') }}</span>
            </div>
          </div>
        </div>
        <!-- <div class="col-md-12">
          <div class="col-md-3">
            <label class="control-label">Subsanado</label>
          </div>
          <div class="col-md-9">
            <input type="checkbox" name="enabled" v-model="form.enabled">
          </div>
        </div> -->
        <div class="col-md-12">
          <div class="text-center m-sm">
            <button class="btn btn-danger" type="button" @click="$modal.hide('observation-modal')">
              <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
              <span class="bold">Cancelar</span>
            </button>
            <button class="btn btn-primary" type="button" @click="save()">
              <i class="fa fa-check-circle"></i>&nbsp;Guardar
            </button>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>
<script>
import { flashErrors, canOperation } from "../../helper.js";
export default {
  props: ["affiliate", "observationTypes", "permissions"],
  data() {
    return {
      form: {
        message: null,
        observationTypeId: null,
        enabled: true
      },
      method: "post",
      observations: [],
      showDeleteObservation: false,
      deleteObservations: []
    };
  },
  mounted() {
    this.getObservations();
  },
  methods: {
    can(operation){
      return canOperation(operation, this.permissions)
    },
    async getDeleteObservations() {
      if(!this.can('read_observation_type', this.permissions)){
        return;
      }
      await axios
        .get(`/affiliate_get_delete_observations/${this.affiliate.id}`)
        .then(response => {
          this.deleteObservations = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getBadge(type) {
      let obj = new Object();
      let value = null;
      switch (type) {
        case "T":
          value = "label-success";
          break;
        case "AT":
          value = "label-information";
          break;
      }
      return (obj[value] = true);
    },
    getOption() {
      let method = "";
      switch (this.method) {
        case "post":
          method = "create";
          break;
        case "patch":
          method = "update";
          break;
        case "delete":
          method = "delete";
          break;
      }
      return method;
    },
    createObs() {
      if(!this.can('create_observation_type', this.permissions)){
        return;
      }
      this.$modal.show("observation-modal");
      this.form.observationTypeId = null;
      this.form.message = null;
      this.form.enabled = false;
      this.method = "post";
    },
    editObs(o) {
      if(!this.can('update_observation_type', this.permissions)){
        return;
      }
      this.form.observationTypeId = o.id;
      this.form.editObservationTypeId = o.id;
      this.form.message = o.pivot.message;
      this.form.enabled = o.pivot.enabled;
      this.method = "patch";
      this.$modal.show("observation-modal");
    },
    async deleteObs(o) {
      if(!this.can('delete_observation_type', this.permissions)){
        return;
      }
      this.form.observationTypeId = o.id;
      this.method = "delete";
      await this.$swal({
        title: "¿Está seguro de elimar la observacion?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#59B75C",
        cancelButtonColor: "#EC4758",
        confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
        cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return this.save().catch(error => {
            this.status = false;
            this.$swal.showValidationError(
              `Solicitud fallida: ${error.response.data.errors}`
            );
          });
        },
        allowOutsideClick: () => !this.$swal.isLoading()
      });
      // .then(result => {
      //   console.log(result);
      //   if (result.value) {
      //     this.$swal({
      //       type: "success",
      //       title: "Operacion realizada con exito.",
      //       showConfirmButton: false,
      //       timer: 1000
      //     });
      //   }
      // });
      if (this.showDeleteObservation) {
        await this.getDeleteObservations();
      }
    },
    async save() {
      await this.$validator.validateAll();
      if (this.$validator.errors.items.length) {
        return;
      }
      let option = this.getOption();
      this.form.affiliateId = this.affiliate.id;
      if (this.method == "delete") {
        this.form = { data: this.form };
      }
      await axios[this.method](`/affiliate_observation_${option}`, this.form)
        .then(response => {
          console.log(response);
          this.$modal.hide("observation-modal");
        })
        .catch(error => {
          flashErrors(
            "Error al procesar la observacion: ",
            error.response.data.errors
          );
          console.log(error);
        });
      await this.getObservations();
    },
    async getObservations() {
      if(!this.can('read_observation_type', this.permissions)){
        return;
      }
      await axios
        .get(`/affiliate_get_observations/${this.affiliate.id}`)
        .then(response => {
          this.observations = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    }
  },
  computed: {
    observationTypesFilter() {
      let obs_res =[]
      return this.observationTypes.filter(o => {
        for(let i=0;i < this.observations.length; i++){
          if(this.observations[i].pivot.deleted_at == null){
            obs_res.push(this.observations[i])
          }
        }
        return !obs_res.map(oo => oo.id).includes(o.id);
      });
    }
  }
};
</script>

<style>
</style>
