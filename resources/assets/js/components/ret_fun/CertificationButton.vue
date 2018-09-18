<template>
  <button class="btn btn-primary dim"
          type="button"
          data-toggle="tooltip"
          data-placement="top"
          :title="title"
          :disabled="loading"
          @click="modal()"
          v-if="retFun.isValidated"
          >
      <i v-if="loading" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
      <i v-else class="fa fa-print"></i>
      &nbsp;
      {{ loading ? 'Imprimiendo...' : 'Imprimir' }}
  </button>
</template>
<script>
import { mapGetters } from 'vuex';
    export default {
      props: ["title", "urlPrint", "retFunId", "message"],
      data(){
        return {
          loading: false,
        }
      },
      methods: {
        print() {
          return {
            printable: `${this.urlPrint}`,
            type: "pdf",
            modalMessage:
              "Generando documentos de impresión, por favor espere un momento.",
            showModal: true
          };
        },
        modal() {
          this.loading = true;
          if (this.message) {
            this.$swal({
              title: "Escribe una nota:",
              input: "textarea",
              // text: "<textarea id='text'></textarea>",
              // html:true,
              inputValue: '',
              inputAttributes: {
                autocapitalize: "off"
              },
              showCancelButton: true,
              confirmButtonText: "Imprimir",
              showLoaderOnConfirm: true,
              preConfirm: note => {
                return axios
                  .post(`/ret_fun/${this.retFunId}/save_certification_note`, {
                    note: note
                  })
                  .then(response => {
                    if (!response.data) {
                      throw new Error(response.statusText);
                      this.loading = false;
                    }
                    return response;
                  })
                  .catch(error => {
                    this.$swal.showValidationError(`Request failed: ${error}`);
                    this.loading = false;
                  });
              }
              // allowOutsideClick: () => !this.swal.isLoading()
            }).then(result => {
              if (result.value) {
                printJS(this.print());
                this.loading = false;
              }else{
                this.loading = false;
              }
            });
          }else{
            printJS(this.print());
            setTimeout(() => {
              this.loading = false;
            }, 1000);
          }
        }
      },
      computed: {
        ...mapGetters('retFunForm',{
            retFun: 'getData'
        }),
      }
    };
</script>

