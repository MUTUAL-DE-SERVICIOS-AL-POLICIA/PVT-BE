<script>
	export default{
		props:[
			'affiliate'
		],
        data(){
            return{
                editing: false,
                show_spinner: false,
                form:{
                    identity_card: this.affiliate.identity_card,
                    first_name: this.affiliate.first_name,
                    second_name: this.affiliate.second_name,
                    last_name: this.affiliate.last_name,
                    mothers_last_name: this.affiliate.mothers_last_name,
                    gender: this.affiliate.gender,
                },
                first_name:{
                    value: this.affiliate.first_name,
                    edit: false,
                }
            }
        },
        mounted(){
            console.log("hello world");
        },
        methods:{
            edit_first_name: function(){
                console.log(this.first_name.value)
            },
            toggle_editing:function () {
                this.editing = !this.editing;
            },
            update () {
                let uri = `/update_affiliate/${this.affiliate.id}`;
                this.show_spinner=true;
                axios.patch(uri,this.form)
                    .then(()=>{
                        this.editing = false;
                        this.show_spinner=false;
                        flash('Informacion del Afiliado Actualizada');
                    }).catch((response)=>{
                        this.show_spinner=false;
                        flash('Error al actualizar el afiliado: '+response.message,'error');
                    })
            }
        }
	}
</script>