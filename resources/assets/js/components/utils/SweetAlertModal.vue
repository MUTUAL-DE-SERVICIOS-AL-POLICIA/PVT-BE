<script>
    export default {
      props:['docId', 'inboxState'],
      data(){
        return{
          status: this.inboxState
        }
      },
      methods: {
        showModal() {
          this.$swal({
            title: "¿Está seguro que revisó correctamente?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#59B75C",
            cancelButtonColor: "#EC4758",
            confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
            cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                let uri = `/inbox_validate_doc/${this.docId}`
                return axios.patch(uri)
                .then(response => {
                    if (!response.data) {
                        throw new Error(response.errors)
                    }
                    return response.data;
                })
                .catch((error) => {
                    this.status = false;
                    this.$swal.showValidationError(
                      `Solicitud fallida: ${error.response.data.errors}`
                    )
                })
            },
            allowOutsideClick: () => !this.$swal.isLoading()
          }).then(result => {
            if (result.value) {
             this.status = true;
             this.$swal(
                'Hecho!',
                'El tramite fue procesado correctamente.',
                'success'
                )
            }
          });
        }
      }
    };
</script>
