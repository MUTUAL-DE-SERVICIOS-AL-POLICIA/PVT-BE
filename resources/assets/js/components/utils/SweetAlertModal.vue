<script>
import { camelCaseToSnakeCase } from "../../helper.js";
    export default {
      props:['docId', 'inboxState', 'docUserId', 'authId', 'type'],
      mounted () {
        this.$store.commit(`${this.type}Form/setIsValidated`,this.inboxState);
      },
      data(){
        return{
          status: this.inboxState,
          docUserIdLocal: this.docUserId,
        }
      },
      methods: {
        async confirmModal() {
          await this.$swal({
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
             this.docUserIdLocal = result.value.doc.user_id;
             this.$store.commit(`${this.type}Form/setCorrelative`,result.value.correlative.code);
             this.$store.commit(`${this.type}Form/setIsValidated`,result.value.doc.inbox_state);
             this.$swal({
               type: 'success',
               title: 'El Trámite fue procesado correctamente.',
               showConfirmButton: false,
               timer: 1000
             })
            }
          });
        },
        cancelModal() {
          this.$swal({
            title: "¿Está seguro de cancelar la revision del Trámite?",
            type: "error",
            showCancelButton: true,
            confirmButtonColor: "#59B75C",
            cancelButtonColor: "#EC4758",
            confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
            cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                let uri = `/inbox_invalidate_doc/${this.docId}`
                console.log(uri)
                return axios.patch(uri)
                .then(response => {
                    if (!response.data) {
                        throw new Error(response.errors)
                    }
                    return response.data;
                })
                .catch((error) => {
                    this.status = true;
                    this.$swal.showValidationError(
                      `Solicitud fallida: ${error.response.data.errors}`
                    )
                })
            },
            allowOutsideClick: () => !this.$swal.isLoading()
          }).then(result => {
            if (result.value) {
             this.status = false;
             this.$store.commit(`${this.type}Form/setIsValidated`,result.value.inbox_state);
             this.$swal({
               type: 'success',
               title: 'El Trámite fue procesado correctamente.',
               showConfirmButton: false,
               timer: 1000
             })
            }
          });
        },
      },
      computed:{
        itisMine(){
          return (this.docUserIdLocal == this.authId && this.status == true);
        }
      }
    };
</script>
