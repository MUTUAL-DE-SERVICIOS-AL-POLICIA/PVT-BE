<script>
import {mapGetters, mapMutations} from 'vuex';
export default {
    props:['rolId','user', 'inboxState'],
    data(){
        return{
            workflows: [],
            documents:[],
            activeWorkflowId:null,
            wfSequenceNextL:[],
            wfSequenceBackL:[],
            wfSequenceNext:null,
            wfSequenceBack:null,
            wfCurrentState:null,
        }
    },
    mounted(){
        this.getData();
    },
    methods:{
        getData(){
            let uri;
            if (this.inboxState == 'received') {
                uri = `/api/documents/${this.inboxState}/${this.rolId.id}`;
            }else{
                uri = `/api/documents/${this.inboxState}/${this.rolId.id}/${this.user.id}`;
            }
            axios.get(uri).then(({data})=>{
                this.workflows =  data.workflows;
                this.activeWorkflowId = this.activeWorkflowId == null ? (data.workflows[0].id || null) : this.activeWorkflowId;
                this.documents =  data.documents;
                this.wfCurrentState =  data.wf_current_state;
                this.wfSequenceNextL =  data.wf_sequences_next;
                this.wfSequenceBackL =  data.wf_sequences_back;
            });
        },
        classification(id){
            return this.documents.filter(v => v.workflow_id == id);
        },
        handleTabChange(tabIndex, newTab, oldTab){
            this.activeWorkflowId = newTab.$attrs.dataid;
        },
        sendForward(){
            let found = this.dataInbox.workflows.find(w =>{
                return w.workflow_id == this.activeWorkflowId
            });
            if (found) {
                if (!this.wfSequenceNextL.find(w=> w.wf_state_id == this.wfSequenceNext)) {
                    flash("Debe seleccionar el destino al donde enviara los tramites.", "error")
                    return;
                }
                let wfSequenceNextName = this.wfSequenceNextL.find(w=> w.wf_state_id == this.wfSequenceNext).wf_state_name;
                this.$swal({
                    title: `¿Está seguro de enviar (${found.docs.length}) tramite(s), de ${this.wfCurrentState.name} a ${wfSequenceNextName} ?`,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#59B75C",
                    cancelButtonColor: "#EC4758",
                    confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
                    cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        let uri=`/inbox_send_forward`;
                        axios.post(uri,{
                            wfSequenceNext: this.wfSequenceNext,
                            docs: found.docs
                        }).then(response =>{
                            if (!response.data) {
                                throw new Error(response.errors)
                            }
                            return response.data;
                        }).catch((error) => {
                            this.$swal.showValidationError(`Solicitud fallida: ${error.response.data.errors}`);
                            flash('Error al enviar los tramites: '+error.message,'error');
                        })
                    },
                    allowOutsideClick: () => !this.$swal.isLoading()
                }).then(result => {
                    if (result.value) {
                        flash('Tramites enviados correctamente');
                        this.getData();
                        this.classification(this.activeWorkflowId);
                        this.$store.commit("clear", this.activeWorkflowId);
                        this.$swal('Hecho!', 'Los tramites fueron enviados correctamente.','success')
                    }
                });
            }else{
                alert("error")
            }
        },
        sendBackward(){
            let found = this.dataInbox.workflows.find(w =>{
                return w.workflow_id == this.activeWorkflowId
            });
            if (found) {
                if (!this.wfSequenceBackL.find(w=> w.wf_state_id == this.wfSequenceBack)) {
                    flash("Debe seleccionar el destino al donde enviara los tramites.", "error");
                    return;
                }
                let wfSequenceBackName = this.wfSequenceBackL.find(w=> w.wf_state_id == this.wfSequenceBack).wf_state_name;
                this.$swal({
                    title: `¿Está seguro de enviar (${found.docs.length}) tramite(s), de ${this.wfCurrentState.name} a ${wfSequenceBackName} ?`,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#59B75C",
                    cancelButtonColor: "#EC4758",
                    confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
                    cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        let uri=`/inbox_send_backward`;
                        axios.post(uri,{
                            wfSequenceBack: this.wfSequenceBack,
                            docs: found.docs
                        }).then(response =>{
                            if (!response.data) {
                                throw new Error(response.errors)
                            }
                            return response.data;
                        }).catch((error) => {
                            this.$swal.showValidationError(`Solicitud fallida: ${error.response.data.errors}`);
                            flash('Error al enviar los tramites: '+error.message,'error');
                        })
                    },
                    allowOutsideClick: () => !this.$swal.isLoading()
                }).then(result => {
                    if (result.value) {
                        flash('Tramites enviados correctamente');
                        this.getData();
                        this.classification(this.activeWorkflowId);
                        this.$store.commit("clear", this.activeWorkflowId);
                        this.$swal('Hecho!', 'Los tramites fueron enviados correctamente.','success')
                    }
                });
            }else{
                alert("error");
            }
        },
    },
    computed:{
        ...mapGetters({
          dataInbox: 'getDataInbox',
        }),
        wfSequenceNextList(){
            return  this.wfSequenceNextL.filter(wfs => wfs.workflow_id == this.activeWorkflowId)
        },
        wfSequenceBackList(){
            return this.wfSequenceBackL;
            return  this.wfSequenceBackL.filter(wfs => wfs.workflow_id == this.activeWorkflowId)
        },
        rejectObject(obj, keys) {
            const vkeys = Object.keys(obj)
                .filter(k => !keys.includes(k));
            return pick(obj, vkeys);
        },
        docs(){
            let found = this.dataInbox.workflows.find(w =>{
                return w.workflow_id == this.activeWorkflowId
            });
            if (found) {
                return found.docs.length;
            }
            return 0;
        },
        totalDocs(){
            return this.workflows.reduce((accu, curr)=>{
                return accu + this.classification(curr.id).length;
            }, 0)
        },
        docss(){
            return this.dataInbox.filterCi;
        }
    }
}
</script>
 