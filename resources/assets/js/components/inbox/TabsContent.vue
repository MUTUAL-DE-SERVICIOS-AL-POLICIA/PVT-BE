<script>
import {mapGetters, mapMutations} from 'vuex';
import { flashErrors } from "../../helper.js";
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
            showLoading:true,
            documentsReceivedTotal:null,
            documentsEditedTotal:null,
        }
    },
    mounted(){
        this.getData();

        // $('.datatable').each(function(i, obj) {
        //     console.log(obj);
            
        //     obj.removeClass('datatable table datatable--select-all');
        //     obj.addClass('table table-hover table-mail');
        // });

        
    },
    methods:{
        getData(){
            this.showLoading=true;
            let uri;
            if (this.inboxState == 'received') {
                uri = `/api/documents/${this.inboxState}/${this.rolId.id}/${this.user.id}`;
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
                this.documentsReceivedTotal = data.documents_received_total;
                this.documentsEditedTotal = data.documents_edited_total;
                this.updateCheckStatus();
                this.showLoading = false;
            });
        },
        updateCheckStatus(){
            //first method
            if (this.dataInbox.workflows.length > 0) {
                this.dataInbox.workflows.forEach(w => {
                    this.documents.forEach(docs=>{
                        if (docs.workflow_id == w.workflow_id) {
                            w.docs.forEach(d => {
                                if (d.id == docs.id) {
                                    docs.status = d.status; 
                                }
                            });
                        }
                    })
                });
            }
            //second method
            // if(this.dataInbox.workflows.length > 0){
            //     this.workflows.forEach(wf => {
            //         this.$store.commit("inbox/clear", wf.id);
            //     });
            // }
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
                    flash("Debe seleccionar el destino al donde enviará los Trámites.", "error")
                    return;
                }
                let wfSequenceNextName = this.wfSequenceNextL.find(w=> w.wf_state_id == this.wfSequenceNext).wf_state_name;
                this.$swal({
                    title: `¿Está seguro de enviar (${found.docs.length}) Trámite(s), de ${this.wfCurrentState.name} a ${wfSequenceNextName} ?`,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#59B75C",
                    cancelButtonColor: "#EC4758",
                    confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
                    cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        let uri=`/inbox_send_forward`;
                        return axios.post(uri,{
                            wfSequenceNext: this.wfSequenceNext,
                            docs: found.docs
                        }).then(response =>{
                            if (!response.data) {
                                throw new Error(response.errors)
                            }
                            return response.data;
                        }).catch((error) => {
                            this.$swal.showValidationError(`Solicitud fallida: ${error.response.data.errors}`);
                            flash('Error al enviar los Trámites: '+error.message,'error');
                        })
                    },
                    allowOutsideClick: () => !this.$swal.isLoading()
                }).then(result => {
                    if (result.value) {
                        flash('Trámites enviados correctamente');
                        this.$store.commit("inbox/clear", this.activeWorkflowId);
                        this.$swal('Hecho!', 'Los Trámites fueron enviados correctamente.','success')
                        this.getData();
                        this.classification(this.activeWorkflowId);
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
                    flash("Debe seleccionar el destino al donde enviará los Trámites.", "error");
                    return;
                }
                let wfSequenceBackName = this.wfSequenceBackL.find(w=> w.wf_state_id == this.wfSequenceBack).wf_state_name;
                this.$swal({
                    title: `¿Está seguro de enviar (${found.docs.length}) Trámite(s), de ${this.wfCurrentState.name} a ${wfSequenceBackName} ?`,
                    type: "warning",
                    input: 'textarea',
                    inputPlaceholder: 'Escriba una nota:',
                    showCancelButton: true,
                    confirmButtonColor: "#59B75C",
                    cancelButtonColor: "#EC4758",
                    confirmButtonText: "<i class='fa fa-save'></i> Confirmar",
                    cancelButtonText: "Cancelar <i class='fa fa-times'></i>",
                    showLoaderOnConfirm: true,
                    preConfirm: (message) => {
                        let uri=`/inbox_send_backward`;
                        return axios.post(uri,{
                            wfSequenceBack: this.wfSequenceBack,
                            docs: found.docs,
                            message: message
                        }).then(response =>{
                            if (!response.data) {
                                throw new Error(response.errors)
                            }
                            return response.data;
                        }).catch((error) => {
                            for (const key in error.response.data.errors) {
                                let value = error.response.data.errors[key];
                                if (error.response.data.errors.hasOwnProperty(key)) {
                                    this.$swal.showValidationError(`Solicitud fallida: ${value}`);
                                }
                            }
                            flashErrors('Error al enviar los Trámites',error.response.data.errors)
                        })
                    },
                    allowOutsideClick: () => !this.$swal.isLoading()
                }).then(result => {
                    if (result.value) {
                        flash('Trámites enviados correctamente');
                        this.$store.commit("inbox/clear", this.activeWorkflowId);
                        this.$swal('Hecho!', 'Los Trámites fueron enviados correctamente.','success')
                        this.getData();
                        this.classification(this.activeWorkflowId);
                    }
                });
            }else{
                alert("error");
            }
        },
    },
    computed:{
        ...mapGetters('inbox',{
          dataInbox: 'getDataInbox',
        }),
        wfSequenceNextList(){
            return  this.wfSequenceNextL.filter(wfs => wfs.workflow_id == this.activeWorkflowId)
        },
        wfSequenceBackList(){
            return this.wfSequenceBackL;
            return  this.wfSequenceBackL.filter(wfs => wfs.workflow_id == this.activeWorkflowId)
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
            if(this.workflows.length == 0){
                return null;
            }
            return this.workflows.reduce((accu, curr)=>{
                return accu + this.classification(curr.id).length;
            }, 0)
        },
    }
}
</script>
 