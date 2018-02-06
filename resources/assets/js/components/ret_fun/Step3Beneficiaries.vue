<script>
export default {
    props:[
        'items'
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
