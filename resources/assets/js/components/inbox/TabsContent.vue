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
                let uri=`/inbox_send_forward`;
                axios.post(uri,{
                    wfSequenceNext: this.wfSequenceNext,
                    docs: found.docs
                }).then(response =>{
                    flash('Tramites enviados correctamente');
                    this.getData();
                    this.classification(this.activeWorkflowId);
                    this.$store.commit("clear", this.activeWorkflowId);
                }).catch(error =>{
                    flash('Error al enviar los tramites: '+error.message,'error');
                })
            }else{
                alert("error")
            }
        },
        sendBackward(){
            let found = this.dataInbox.workflows.find(w =>{
                return w.workflow_id == this.activeWorkflowId
            });
            if (found) {
                let uri=`/inbox_send_backward`;
                axios.post(uri,{
                    wfSequenceBack: this.wfSequenceBack,
                    docs: found.docs
                }).then(response =>{
                    flash('Tramites enviados correctamente');
                    this.$store.commit("clear", this.activeWorkflowId);
                    this.getData();
                    this.classification(this.activeWorkflowId);
                }).catch(error =>{
                    flash('Error al enviar los tramites: '+error.message,'error');
                })
            }else{
                alert("error")
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
 