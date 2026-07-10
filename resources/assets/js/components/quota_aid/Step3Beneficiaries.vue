<script>
export default {
    props:[
        'items',
        'kinship_beneficiaries',
    ],
    data(){
        return{
            list: this.items,
        }
    },
    mounted(){
    },
    methods:{
        addItem:function(){
            this.list.push()
        }
    }

}
</script>
