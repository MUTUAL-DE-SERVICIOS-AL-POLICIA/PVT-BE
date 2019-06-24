<template>
    <transition name="fade" mode="out-in" duration="600" enter-active-class="animated bounceInDown" leave-active-class="animated fadeOutRight">
    <div class="input-group"
         v-if="!clickShow && !doc.isValidated "
         key="clickButton"
         data-toggle="tooltip"
        data-placement="top"
        title="Devolver el trámite"
         >
        <button class="btn btn-warning btn-circle btn-outline btn-lg"
                @click="clickShow = true">
            <i class="fa fa-arrow-left"></i>
        </button>
    </div>
    <div class="input-group"
         v-else-if="! doc.isValidated && show && clickShow"
         :class="{ 'has-error': errors.has('wf-sequence-back')}"
         key="selectOptions"
         >
        <span class="input-group-btn">
            <button class="btn"
                    :disabled="! wfSequenceBack"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="Devolver el trámite"
                    @click="send()">
                <i class="fa fa-arrow-left"></i> DEVOLVER</button>
        </span>
        <select name="wf-sequence-back"
                v-model="wfSequenceBack"
                class="form-control"
                v-validate="'required'">
    
            <option :value="wfs.wf_state_id"
                    v-for="(wfs, index) in wfSequenceBackList"
                    :key="index">{{wfs.wf_state_name}}</option>
        </select>
    </div>
    </transition>
</template>
<script>
    import { mapGetters } from "vuex";
    import { flashErrors } from "../../helper.js";
    export default {
      props: ["wfSequenceBackList", "docId", "wfCurrentStateName", 'type'],
      data() {
        return {
          wfSequenceBack: null,
          show: true,
          clickShow: false,
        };
      },
      computed: {
        doc(){
          return this.$store.state[`${this.type}Form`];
        }
        // ...mapGetters('quotaAidForm', {
        //   doc: "getData"
        // })
      },
      methods: {
        async validateBeforeSubmit() {
          try {
            await this.$validator.validateAll();
          } catch (error) {
            console.log("some error");
          }
        },
        validAll() {
          return Object.keys(this.$validator.errors.collect()).length > 0;
        },
        send() {
          this.validateBeforeSubmit();
          if (this.validAll()) {
            flash("Debe completar el formulario", "error");
            return;
          }
          let wfSequenceBackName = this.wfSequenceBackList.find(
            w => w.wf_state_id == this.wfSequenceBack
          ).wf_state_name;
          this.$swal({
            title: `¿Está seguro de devolver 1 trámite, de ${
              this.wfCurrentStateName
            } a ${wfSequenceBackName} ?`,
            type: "warning",
            input: "textarea",
            inputPlaceholder: "Escriba una nota:",
            showCancelButton: true,
            confirmButtonColor: "#59B75C",
            cancelButtonColor: "#EC4758",
            confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
            cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
            showLoaderOnConfirm: true,
            preConfirm: message => {
              console.log(
                "Object.create({id:this.docId}): ",
                Object.create({ id: this.docId })
              );

              let uri = `/inbox_send_backward`;
              return axios
                .post(uri, {
                  wfSequenceBack: this.wfSequenceBack,
                  docs: [{ id: this.docId }],
                  message: message
                })
                .then(response => {
                  if (!response.data) {
                    throw new Error(response.errors);
                  }
                  return response.data;
                })
                .catch(error => {
                  for (const key in error.response.data.errors) {
                    let value = error.response.data.errors[key];
                    if (error.response.data.errors.hasOwnProperty(key)) {
                      this.$swal.showValidationError(`Solicitud fallida: ${value}`);
                    }
                  }
                  flashErrors(
                    "Error al enviar los Trámites",
                    error.response.data.errors
                  );
                });
            },
            allowOutsideClick: () => !this.$swal.isLoading()
          }).then(result => {
            if (result.value) {
              this.show = false;
              flash("Trámite devuelto correctamente");
              this.$swal({
                type: "success",
                title: "El Trámite fue devuelto correctamente.",
                showConfirmButton: false,
                timer: 1000
              });
            }
          });
        }
      }
    };
</script>