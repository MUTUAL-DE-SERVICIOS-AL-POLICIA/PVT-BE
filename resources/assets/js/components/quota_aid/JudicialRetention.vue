<template>
   <div class="col-lg-12">
      <div class="ibox">
         <div class="ibox-title">
            <h5>Retenciones judiciales</h5>
            <div class="ibox-tools">
               <button class="btn btn-primary" @click="register = !register">
                  <i class="fa">Registrar </i>
               </button>
            </div>
         </div>
         <div class="ibox-content" v-if="register">
            <div class="row">
               <div class="col-md-5">
                  <table class="table table-striped table-hover table-bordered">
                     <thead>
                        <tr>
                           <th>Nro</th>
                           <th>Detalle</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr v-for="(o, index) in retentions" :key="index">
                           <td>{{ index + 1 }}</td>
                           <td>{{ o.note_code }}</td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <div class="col-md-1">
                  <label class="control-label">Descripción:</label>
               </div>
               <div class="col-md-6">
                  <textarea type="text" class="form-control" v-model="detail" placeholder="descripción..."></textarea>
               </div>
            </div>
         </div>
         <div class="ibox-footer">
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
   </div>
</template>
<script>
export default {
   props: ['quotaAidId'],
   data() {
      return {
         register: false,
         detail: null,
         retentions: []
      }
   },
   mounted() {
      this.obtainJudicialRetention();
   },
   methods: {
      async registerJudicialRetention() {
         try {
            const response = await axios.post(`/quota_aid/${this.quotaAidId}/save_judicial_retention`, {
               detail: this.detail
            })
            window.location.reload();
            flash(response.data.message);
            cancel();
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
         this.detail = null
      },
      async obtainJudicialRetention() {
         try {
            const response = await axios.get(`/quota_aid/${this.quotaAidId}/obtain_judicial_retention`)
            if(response.data) {
               this.retentions = response.data.data
            }
         } catch( error ) {
            console.log(error)
         }
      }
   }
}
</script>