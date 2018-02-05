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
        console.log('step 3 mounted')
    },
    methods:{
        addItem:function(){
            this.list.push()
        }
    }

}
</script>
