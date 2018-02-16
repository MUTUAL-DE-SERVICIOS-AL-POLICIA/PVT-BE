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
                    civil_status: this.affiliate.civil_status,
                    phone_number: this.affiliate.phone_number,
                    cell_phone_number: this.affiliate.cell_phone_number,
                    birth_date: this.affiliate.birth_date,
                    
                },
                first_name:{
                    value: this.affiliate.first_name,
                    edit: false,
                }
            }
        },
        computed:{
            age: function(){
                var birthday = +new Date(this.affiliate.birth_date); 
                if(this.affiliate.birth_date!=null){

                    return~~ ((Date.now() - birthday) / (31557600000));
                }else
                {
                    return '';
                }
            }
        },
        methods:{
            edit_first_name: function(){
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