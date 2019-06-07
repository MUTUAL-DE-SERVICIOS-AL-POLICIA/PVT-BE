<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h2 class="pull-left">Notas</h2>
          <div class="ibox-tools">
            <button
              class="btn btn-large btn-primary"
              @click="createNote()"
              :disabled="!can('create_note')"
              data-toggle="tooltip"
              title="Adicionar Nota"
              v-if="can('read_note')"
            >
              <i class="fa fa-plus"></i> Adicionar
            </button>
          </div>
        </div>
        <div class="ibox-content" v-if="can('read_note')">
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr>
                <th>Nro.</th>
                <th>Fecha</th>
                <th>Mensaje</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(nr, index) in noteRecords" :key="index">
                <td>{{index + 1}}</td>
                <td>{{ nr.date | textDate }}</td>
                <td>{{ nr.message }}</td>
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
                          @click.prevent="editNote(nr)"
                          :aria-disabled="!can('update_note')"
                        >
                          <i class="fa fa-pencil"></i> Editar
                        </a>
                      </li>
                      <li>
                        <a
                          class="dropdown-item"
                          @click.prevent="deleteNote(nr)"
                          :aria-disabled="!can('delete_note')"
                          href="#"
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
          <div class="alert alert-warning">No tiene permisos para ver las notas.</div>
        </div>
      </div>
    </div>
    <modal name="note-modal" class="p-sm" height="auto">
      <div class="ibox-title">
        <h1>{{ method == 'post' ? 'Agregar' : 'Editar'}} Observacion</h1>
      </div>
      <div class="row">
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
        <div class="col-md-12">
          <div class="text-center m-sm">
            <button class="btn btn-danger" type="button" @click="$modal.hide('note-modal')">
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
  props: ["ecoCom", "permissions", "noteRecords"],
  data() {
    return {
      form: {
        message: null
      },
      method: "post"
    };
  },
  methods: {
    can(operation) {
      return canOperation(operation, this.permissions);
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
    createNote() {
      if (!this.can("create_note", this.permissions)) {
        return;
      }
      this.$modal.show("note-modal");
      this.form.message = null;
      this.method = "post";
    },
    editNote(o) {
      if (!this.can("update_note", this.permissions)) {
        return;
      }
      this.form.id = o.id;
      this.form.message = o.message;
      this.method = "patch";
      this.$modal.show("note-modal");
    },
    async deleteNote(o) {
      if (!this.can("delete_note", this.permissions)) {
        return;
      }
      this.form.id = o.id;
      this.method = "delete";
      await this.$swal({
        title: "¿Está seguro de elimar la Nota?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#59B75C",
        cancelButtonColor: "#EC4758",
        confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
        cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return this.save().catch(error => {
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
    },
    async save() {
      await this.$validator.validateAll();
      if (this.$validator.errors.items.length) {
        return;
      }
      let option = this.getOption();
      this.form.ecoComId = this.ecoCom.id;
      if (this.method == "delete") {
        this.form = { data: this.form };
      }
      await axios[this.method](`/eco_com_note_${option}`, this.form)
        .then(response => {
          console.log(response);
          this.$modal.hide("note-modal");
        })
        .catch(error => {
          flashErrors(
            "Error al procesar la nota: ",
            error.response.data.errors
          );
          console.log(error);
        });
      await this.$emit('getUpdateRecords')
    }
  }
};
</script>

<style>
</style>
