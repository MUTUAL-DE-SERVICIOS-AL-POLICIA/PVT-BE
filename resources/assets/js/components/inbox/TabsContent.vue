<script>
export default {
    props:['rolId','user', 'inboxState'],
    data(){
        return{
            workflows: [],
            documents:[]
        }
    },
    mounted(){
        console.log(this.rolId, this.user);
        
        let uri;
        if (this.inboxState == 'received') {
            uri = `/api/documents/${this.inboxState}/${this.rolId.id}`;
        }else{
            uri = `/api/documents/${this.inboxState}/${this.rolId.id}/${this.user.id}`;
        }
        axios.get(uri).then(({data})=>{
            this.workflows =  data.workflows;
            this.documents =  data.documents;
        })
    },
    methods:{
        classification(id){
            return this.documents.filter(v => v.workflow_id == id);
        }
    },
    computed:{
        totalDocs(){
            return this.workflows.reduce((accu, curr)=>{
                return accu + this.classification(curr.id).length;
            }, 0)
        }
    }
}
</script>
