<script>
	export default{
		props:[
			'ret_fun_procedure',                        
                        'original_procedure',
		],
        data(){
            return{
                editing: false,
                show_spinner: false,
                procedure:this.original_procedure,
                iterator:-1,
            }
        },    
        methods:{            
            toggle_editing:function () {
                this.editing = !this.editing;
                this.procedure = this.original_procedure;
                
            },                        
            update () {                               
                let uri = `/ret_fun_procedure/1`;
                this.show_spinner=true;
                axios.patch(uri,this.procedure)
                    .then(response=>{                       
                        this.editing = false;
                        this.show_spinner=false;
                        this.ret_fun_procedure.annual_yield = response.data.annual_yield;   
                        this.ret_fun_procedure.administrative_expenses = response.data.administrative_expenses;   
                        this.ret_fun_procedure.contributions_number = response.data.contributions_number;   
                        flash('Informacion Actualizada');
                    }).catch((response)=>{
                        this.show_spinner=false;
                        //this.beneficiaries = this.ben;
                        flash('Error al actualizar datos '+response.message,'error');
                    })
            }
        }
	}
</script>