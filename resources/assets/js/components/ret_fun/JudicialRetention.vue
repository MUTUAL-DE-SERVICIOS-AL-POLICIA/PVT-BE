<template>
   <div class="col-lg-12">
      <div class="ibox">
         <div class="ibox-title">
            <h5>Retenciones judiciales</h5>
            <div class="ibox-tools">
               <button class="btn btn-primary" 
               @click="register = !register"
               :disabled="retentions != undefined && retentions.length > 0"
               >
                  <i class="fa">Registrar</i>
               </button>
            </div>
         </div>
         <div class="ibox-content">
            <div class="row">
               <div class="col-md-12" v-if="retentions != undefined && retentions.length > 0">
                  <table class="table table-striped table-hover table-bordered">
                     <thead>
                        <tr>
                           <th>Nro</th>
                           <th>Detalle</th>
                           <th>Acción</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr v-for="(o, index) in retentions" :key="index" >
                           <td>{{ index + 1 }}</td>
                           <td>{{ o.note_code }}</td>
                           <td>
                              <button class="btn btn-danger" type="button" @click="cancelJudicialRetention()">
                                 <i class="fa fa-trash"></i>
                              </button>
                              <button class="btn btn-primary" type="button" @click="openEditDialog(o)">
                                 <i class="fa fa-pencil"></i>
                              </button>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <div v-if="retentions != undefined && retentions.length <= 0">
                  <div class="col-md-6">
                     <label class="control-label">Detalle:</label>
                  </div>
                  <div class="col-md-6">
                     <textarea type="text" class="form-control" v-model="detail" placeholder="descripción..." :disabled="!register"></textarea>
                  </div>
               </div>
            </div>
         </div>
         <div class="ibox-footer" v-if="retentions != undefined && retentions.length <= 0">
            <div class="text-center" v-show="register">
               <button class="btn btn-danger" type="button" @click="cancel()">
                  <i class="fa fa-times-circle">
                     <span class="bold"> Cancelar</span>
                  </i>
               </button>
               <button class="btn btn-primary" type="button" @click="registerJudicialRetention()">
                  <i class="fa fa-check-circle"> Guardar </i>
               </button>
            </div>
         </div>
      </div>
      <!-- Modal retention -->
      <div v-if="showModal" class="modal-overlay">
         <div class="modal-content">
            <span class="close" @click="closeDialog">&times;</span>
            <h2>Editar retención judicial</h2>
            <div class="row">
               <div class="col-md-2">
                  <label class="control-label">Detalle:</label>
               </div>
               <div class="col-md-10">
                  <textarea type="text" class="form-control" v-model="detail" placeholder="descripción..." cols="30" rows="4"></textarea>
               </div>
            </div>
            <div class="text-center">
               <button @click="closeDialog" class="btn btn-danger">Cancelar</button>
               <button @click="modifiyJudicialRetention" class="btn btn-primary">Confirmar</button>
            </div>
         </div>
      </div>
   </div>
</template>
<script>
export default {
   props: ['ret_fun_id'],
   data() {
      return {
         showModal: false,
         register: false,
         detail: null,
         retentions: [],
         editRetention: null
      }
   },
   mounted() {
      this.obtainJudicialRetention();
      console.log(this.ret_fun_id)
   },
   methods: {
      async registerJudicialRetention() {
         if (!this.detail || this.detail.trim() === "") {
            flash('El campo de detalle es obligatorio', 'error');
            return; 
         }
         try {
            const response = await axios.post(`/ret_fun/${this.ret_fun_id}/save_judicial_retention`, {
               detail: this.detail
            })
            this.retentions.push(response.data.data);
            this.obtainJudicialRetention();
            flash(response.data.message);
            //window.location.reload()
         } catch( error ) {
            if(error.response) {
               if(error.response.status == 409) {
                  flash(error.response.data.error, 'error')
               } else console.log(error)
            }
            console.log(error.response)
         }
      },
      cancel() {
         this.register = false
      },
      openEditDialog(retention) {
         this.showModal = true;
         this.editRetention = retention;
         this.detail = retention.note_code;
      },
      closeDialog() {
         this.showModal = false;
      },
      async obtainJudicialRetention() {
         try {
            const response = await axios.get(`/ret_fun/${this.ret_fun_id}/obtain_judicial_retention`)
            if(response.data) {
               this.retentions = response.data.data
               console.log(this.retentions[0])
            }
         } catch( error ) {
            console.log(error)
         }
      },
      async modifiyJudicialRetention() {
         try {
            const response = await axios.patch(`/ret_fun/${this.ret_fun_id}/modify_judicial_retention`, {
               detail: this.detail
            })
            const index = this.retentions.findIndex(r => r.id === this.editRetention.id);
            if (index !== -1) {
                  this.$set(this.retentions, index, response.data.data);
            }
            flash(response.data.message)
            this.closeDialog()
            await this.obtainJudicialRetention();
         } catch( error ) {
            console.error(error)
         }
      },
      async cancelJudicialRetention() {
         await this.$swal({
            title: "¿Está seguro de anular la retención judicial?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#59B75C",
            cancelButtonColor: "#EC4758",
            confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
            cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
            showLoaderOnConfirm: true,
            preConfirm: async () => {
               await axios.delete(`/ret_fun/${this.ret_fun_id}/cancel_judicial_retention`)
               flash("Se ha eliminado la retención exitosamente");
               //this.retentions = this.retentions.filter(r => r.id !== this.editRetention.id);
               this.retentions = []
               this.detail= null
               this.register=false
               return true
            },
         });
      }
   }
}
</script>
<style>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  width: 400px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  position: relative;
}

.close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 20px;
  cursor: pointer;
}

</style>