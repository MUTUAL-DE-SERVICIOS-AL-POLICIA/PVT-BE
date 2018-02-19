<script>
	export default{
		props:[
			'affiliate'
		],
        data(){
            return{
                editing: false,
                show_spinner: false,
                form:this.affiliate,
                first_name:{
                    value: this.affiliate.first_name,
                    edit: false,
                }
            }
        },
        computed:{
            age: function(){
                var birthday = +new Date(this.form.birth_date); 
                if(this.form.birth_date!=null){

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
                    .then(response=>{
                        this.editing = false;
                        this.show_spinner=false;
                        this.form = response.data;
                       // this.form.birth_date = response.data.birth_date;
                        this.first_name = response.data.first_name;                          
                        flash('Informacion del Afiliado Actualizada');
                    }).catch((response)=>{
                        this.show_spinner=false;
                        flash('Error al actualizar el afiliado: '+response.message,'error');
                    })
            }
        }
	}
</script>