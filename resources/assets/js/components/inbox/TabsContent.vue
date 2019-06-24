<script>
import {mapGetters, mapMutations} from 'vuex';
import { flashErrors } from "../../helper.js";
export default {
    props:['rolId','user', 'inboxState','wfdef'],
    data(){
        return{
            workflows: [],
            area_documents: [],
            documents:[],
            activeWorkflowId:null,
            wfSequenceNextL:[],
            wfSequenceBackL:[],
            wfSequenceNext:this.wfdef,
            wfSequenceBack:null,
            wfCurrentState:null,
            showLoading:true,
            documentsReceivedTotal:null,
            documentsEditedTotal:null,
        }
    },
    mounted(){
        this.getData();
    },
    methods:{
        getData(){
            this.showLoading=true;
            let uri = `/api/documents/${this.inboxState}/${this.rolId.id}/${this.user.id}`;
            axios.get(uri).then(({data})=>{
                this.workflows =  data.workflows;
                this.activeWorkflowId = this.activeWorkflowId == null ? (data.workflows[0].id || null) : this.activeWorkflowId;
                this.area_documents =  data.documents
                console
                if(this.inboxState == 'received') {
                    this.documents = this.area_documents
                } else {
                    this.documents =  this.area_documents.filter(v => v.user_id == this.user.id)
                }
                this.wfCurrentState =  data.wf_current_state;
                this.wfSequenceNextL =  data.wf_sequences_next;
                this.wfSequenceBackL =  data.wf_sequences_back;
                this.documentsReceivedTotal = data.documents_received_total;
                this.documentsEditedTotal = data.documents_edited_total;
                this.updateCheckStatus();
                this.showLoading = false;
                this.$store.commit('inbox/setHeaders', data.headers);
            });
        },
        switchShow() {
            this.documents = this.area_documents
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
            let procedures = found.docs
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
                        this.$swal({
                            type: 'success',
                            title: 'Los Trámites fueron enviados correctamente.',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        this.getData();
                        this.classification(this.activeWorkflowId);
                        this.printCertification(procedures)
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
                        this.$swal({
                            type: 'success',
                            title: 'Los Trámites fueron enviados correctamente.',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        this.getData();
                        this.classification(this.activeWorkflowId);
                        this.printCertification()
                    }
                });
            }else{
                alert("error");
            }
        },
        async printCertification(procedures) {
            let found = this.dataInbox.workflows.find(w =>{
                return w.workflow_id == this.activeWorkflowId
            });
            try {
                let res = await axios({
                method: "POST",
                url: '/procedure/print/send',
                data: {'procedures' : procedures, from_area:this.wfCurrentState.id, to_area: this.wfSequenceNext},
                responseType: "arraybuffer",
                });
                let blob = new Blob([res.data], {
                type: "application/pdf"
                });
                printJS(window.URL.createObjectURL(blob));
            } catch (e) {
                console.log(e);
            }
        }
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
