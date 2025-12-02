<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h2 class="pull-left">Procedimientos</h2>
          <div class="ibox-tools">
            <button
              class="btn btn-primary"
              @click="createProcedure()"
              data-toggle="tooltip"
              title="Adicionar"
              :disabled="!can('create_eco_com_procedure')"
              v-if="can('read_eco_com_procedure')"
            >
              <i class="fa fa-plus"></i> Adicionar
            </button>
          </div>
        </div>
        <div class="ibox-content" v-if="can('read_eco_com_procedure')">
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr>
                <th>Gestion</th>
                <th>Mes Renta</th>
                <th>Inicio Normal</th>
                <th>Fin Normal</th>
                <th>Inicio Rezagados</th>
                <th>Fin Rezagados</th>
                <th>Indicator</th>
                <!-- <th>Estado</th> -->
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(p, index) in procedures" :key="index">
                <td>{{ p.name }}</td>
                <td>{{ p.rent_month }}</td>
                <td>{{ p.normal_start_date }}</td>
                <td>{{ p.normal_end_date }}</td>
                <td>{{ p.lagging_start_date }}</td>
                <td>{{ p.lagging_end_date }}</td>
                <td>{{ p.indicator }}</td>
                <!-- <td>
                  <span class="label" :class="getBadge(p.id)">hola </span>
                </td>-->
                <td>
                  <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-default btn-sm dropdown-toggle">
                      <i class="fa fa-chevron-down"></i> Opciones
                    </button>
                    <ul class="dropdown-menu">
                      <li>
                        <a
                          class="dropdown-item"
                          href="#"
                          @click.prevent="editProcedure(p)"
                          :aria-disabled="!can('update_eco_com_procedure')"
                        >
                          <i class="fa fa-pencil"></i> Editar
                        </a>
                      </li>
                      <li>
                        <a
                          class="dropdown-item"
                          @click.prevent="deleteProcedure(p)"
                          href="#"
                          :aria-disabled="!can('delete_eco_com_procedure')"
                        >
                          <i class="fa fa-times"></i> Eliminar
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="ibox-content" v-else>
          <div class="alert alert-warning">No tiene permisos para ver los Procedimientos.</div>
        </div>
      </div>
    </div>
    <modal name="procedure-modal" class="p-sm" height="auto">
      <div class="ibox-title">
        <h1>{{ method == 'post' ? 'Agregar' : 'Editar'}} Procedimiento</h1>
      </div>
      <div class="row">
        <div class="col-md-12" :class="{'has-error': errors.has('procedure_year')}">
          <div class="col-md-3">
            <label class="control-label">Año</label>
          </div>
          <div class="col-md-9">
            <input
              type="text"
              class="form-control m-b"
              name="procedure_year"
              v-model="form.year"
              v-validate.initial="'required'"
            >
            <i v-show="errors.has('procedure_year')" class="fa fa-warning text-danger"></i>
            <span
              v-show="errors.has('procedure_year')"
              class="text-danger"
            >{{ errors.first('procedure_year') }}</span>
          </div>
        </div>
        <div class="col-md-12" :class="{'has-error': errors.has('procedure_semester') }">
          <div class="col-md-3">
            <label class="control-label">Semestre</label>
          </div>
          <div class="col-md-9">
            <select
              cols="30"
              rows="10"
              name="procedure_semester"
              v-model.trim="form.semester"
              class="form-control"
              v-validate.initial="'required'"
            >
              <option :value="null"></option>
              <option value="Primer">Primer</option>
              <option value="Segundo">Segundo</option>
            </select>
            <div v-show="errors.has('procedure_semester')">
              <i class="fa fa-warning text-danger"></i>
              <span class="text-danger">{{ errors.first('procedure_semester') }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-12" :class="{'has-error': errors.has('procedure_rent_month') }">
          <div class="col-md-3">
            <label class="control-label">Mes Renta</label>
          </div>
          <div class="col-md-9">
            <select
              name="procedure_rent_month"
              v-model="form.rent_month"
              class="form-control"
              v-validate.initial="'required'"
            >
              <option :value="null"></option>
              <option v-for="m in months" :key="m.id" :value="m.id">{{ m.value }}</option>
            </select>
            <div v-show="errors.has('procedure_rent_month')">
              <i class="fa fa-warning text-danger"></i>
              <span class="text-danger">{{ errors.first('procedure_rent_month') }}</span>
            </div>
          </div>
        </div>
        <div
          class="col-md-12"
          :class="{'has-error': errors.has('procedure_normal_start_date') || errors.has('procedure_normal_end_date') }"
        >
          <div class="col-md-3">
            <label class="control-label">Normal</label>
          </div>
          <div class="col-md-9">
            <div class="col-md-6">
              <input
                v-date
                name="procedure_normal_start_date"
                v-model="form.normal_start_date"
                class="form-control"
                v-validate.initial="'required'"
              >
              <div v-show="errors.has('procedure_normal_start_date')">
                <i class="fa fa-warning text-danger"></i>
                <span class="text-danger">{{ errors.first('procedure_normal_start_date') }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <input
                v-date
                name="procedure_normal_end_date"
                v-model="form.normal_end_date"
                class="form-control"
                v-validate.initial="'required'"
              >
              <div v-show="errors.has('procedure_normal_end_date')">
                <i class="fa fa-warning text-danger"></i>
                <span class="text-danger">{{ errors.first('procedure_normal_end_date') }}</span>
              </div>
            </div>
          </div>
        </div>
        <div
          class="col-md-12"
          :class="{'has-error': errors.has('procedure_lagging_start_date') || errors.has('procedure_lagging_end_date') }"
        >
          <div class="col-md-3">
            <label class="control-label">Rezagados</label>
          </div>
          <div class="col-md-9">
            <div class="col-md-6">
              <input
                v-date
                name="procedure_lagging_start_date"
                v-model="form.lagging_start_date"
                class="form-control"
                v-validate.initial="'required'"
              >
              <div v-show="errors.has('procedure_lagging_start_date')">
                <i class="fa fa-warning text-danger"></i>
                <span class="text-danger">{{ errors.first('procedure_lagging_start_date') }}</span>
              </div>
            </div>
            <div class="col-md-6">
              <input
                v-date
                name="procedure_lagging_end_date"
                v-model="form.lagging_end_date"
                class="form-control"
                v-validate.initial="'required'"
              >
              <div v-show="errors.has('procedure_lagging_end_date')">
                <i class="fa fa-warning text-danger"></i>
                <span class="text-danger">{{ errors.first('procedure_lagging_end_date') }}</span>
              </div>
            </div>
          </div>
        </div>
        <div
          class="col-md-12"
          :class="{'has-error': errors.has('procedure_additional_start_date') || errors.has('procedure_additional_end_date') }"
        >
        </div>
        <div class="col-md-12">
          <div class="text-center m-sm">
            <button class="btn btn-danger" type="button" @click="$modal.hide('procedure-modal')">
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
  props: ["permissions"],
  data() {
    return {
      form: {
        year: null,
        semester: null,
        rent_month: null,
        normal_start_date: null,
        normal_end_date: null,
        lagging_start_date: null,
        lagging_end_date: null,
        additional_start_date: null,
        additional_end_date: null,
        indicator: null
      },
      method: "post",
      procedures: [],
      months: [
        { id: "Enero", value: "Enero" },
        { id: "Febrero", value: "Febrero" },
        { id: "Marzo", value: "Marzo" },
        { id: "Abril", value: "Abril" },
        { id: "Mayo", value: "Mayo" },
        { id: "Junio", value: "Junio" },
        { id: "Julio", value: "Julio" },
        { id: "Agosto", value: "Agosto" },
        { id: "Septiembre", value: "Septiembre" },
        { id: "Octubre", value: "Octubre" },
        { id: "Noviembre", value: "Noviembre" },
        { id: "Diciembre", value: "Octubre" }
      ]
    };
  },
  mounted() {
    this.getProcedures();
  },
  methods: {
    can(operation) {
      return canOperation(operation, this.permissions);
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
    createProcedure() {
      if (!this.can("create_eco_com_procedure", this.permissions)) {
        return;
      }
      this.$modal.show("procedure-modal");
      this.form.year = moment().year();
      this.form.semester = "Primer";
      this.form.rent_month = null;
      this.form.normal_start_date = null;
      this.form.normal_end_date = null;
      this.form.lagging_start_date = null;
      this.form.lagging_end_date = null;
      this.form.additional_start_date = null;
      this.form.additional_end_date = null;
      this.form.indicator = null;
      this.method = "post";
    },
    editProcedure(p) {
      if (!this.can("update_eco_com_procedure", this.permissions)) {
        return;
      }
      this.form.id = p.id;
      this.form.year = p.year;
      this.form.semester = p.semester;
      this.form.rent_month = p.rent_month;
      this.form.normal_start_date = p.normal_start_date;
      this.form.normal_end_date = p.normal_end_date;
      this.form.lagging_start_date = p.lagging_start_date;
      this.form.lagging_end_date = p.lagging_end_date;
      this.form.additional_start_date = p.additional_start_date;
      this.form.additional_end_date = p.additional_end_date;
      this.form.indicator = p.indicator;
      this.method = "patch";
      this.$modal.show("procedure-modal");
    },
    async deleteProcedure(p) {
      if (!this.can("delete_eco_com_procedure", this.permissions)) {
        return;
      }
      this.form.id = p.id;
      this.method = "delete";
      await this.$swal({
        title: "¿Está seguro de elimar el procedimiento?",
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
      //       title: "Operacion realizada con exitp.",
      //       showConfirmButton: false,
      //       timer: 1000
      //     });
      //   }
      // });
    },
    async save() {
      await this.$validator.validateAll();
      if (this.$validator.errors.items.length) {
        return;
      }
      let option = this.getOption();
      if (this.method == "delete") {
        this.form = { data: this.form };
      }
      await axios[this.method](`/eco_com_procedure_${option}`, this.form)
        .then(response => {
          this.$modal.hide("procedure-modal");
          if (response.data.status === 'success') {
            flash(response.data.message, 'success');
          } else {
            console.log(response);
          }
        })
        .catch(error => {
          flashErrors("Error al procesar: ", error.response.data.errors);
          console.log(error);
        });
      await this.getProcedures();
    },
    async getProcedures() {
      if (!this.can("read_eco_com_procedure", this.permissions)) {
        return;
      }
      await axios
        .get(`/eco_com_get_procedures`)
        .then(response => {
          this.procedures = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style>
</style>
