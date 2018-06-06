<script>
import {mapGetters} from 'vuex';
export default {
    props:['rolId','user', 'inboxState'],
    data(){
        return{
            workflows: [],
            documents:[],
            activeWorkflowId:null,
            docIds:null,
            wfSequenceNext:[],
            options:[],
        }
    },
    mounted(){
        let uri;
        if (this.inboxState == 'received') {
            uri = `/api/documents/${this.inboxState}/${this.rolId.id}`;
        }else{
            uri = `/api/documents/${this.inboxState}/${this.rolId.id}/${this.user.id}`;
        }
        axios.get(uri).then(({data})=>{
            this.workflows =  data.workflows;
            this.activeWorkflowId = data.workflows[0].id || null;
            this.documents =  data.documents;
            this.wfSequenceNext =  data.wf_sequences_next;
        });
    },
    methods:{
        classification(id){
            return this.documents.filter(v => v.workflow_id == id);
        },
        handleTabChange(tabIndex, newTab, oldTab){
            this.activeWorkflowId = newTab.$attrs.dataid;
        },
    },
    computed:{
        ...mapGetters({
          dataInbox: 'getDataInbox',
        }),
        wfSequenceNextList(){
            return  this.wfSequenceNext.filter(wfs => wfs.workflow_id == this.activeWorkflowId)
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
                this.docIds = found.docs.map(d => d.id);
                return found.docs.length;
            }
            return 0;
        },
        totalDocs(){
            return this.workflows.reduce((accu, curr)=>{
                return accu + this.classification(curr.id).length;
            }, 0)
        }
    }
}
</script>
 