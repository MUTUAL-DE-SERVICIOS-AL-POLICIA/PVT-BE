<template>
    <div>
        <button class="btn btn-primary dim"
                type="button"
                data-toggle="tooltip"
                data-placement="top"
                :title="title"
                @click="modal()">
            <i class="fa fa-print"></i>
        </button>
    </div>
</template>
<script>
    export default {
      props: ["title", "urlPrint", "retFunId"],
      mounted(){
          console.log(this.title);
          console.log(this.urlPrint);
          console.log(this.retFunId);
          
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
                  }
                  return response;
                })
                .catch(error => {
                  this.$swal.showValidationError(`Request failed: ${error}`);
                });
            }
            // allowOutsideClick: () => !this.swal.isLoading()
          }).then(result => {
            if (result.value) {
              printJS(this.print());
            }
          });
        }
      }
    };
</script>

