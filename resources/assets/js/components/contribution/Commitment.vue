<script>
	export default{
		props:[
			'commitment',
                        'affiliate_id',
		],
        data(){
            return{
                editing: false,
                create: false,
                enable_delete: false,
                show_spinner: false,                
                iterator:-1,
            }
        },
        computed:{            
        },        
        methods:{
            toggle_editing:function () {
                this.editing = !this.editing;
                //this.ben = this.original_beneficiar   ies;
                
            },                            
            toggle_create:function(){
                this.create = !this.create;
            },
            print_commitment(){
                var affiliate_id = this.affiliate_id;
                window.open('/ret_fun/'+affiliate_id+'/print/voucher','_blank');
            },
//            disable_commitment(){
//                this.update(-1);
//            },
            create_new(){
                //this.toggle_create();
                this.toggle_editing();
                //this.update(0);
                //this.commitment.affiliate_id = this.affiliate_id;
            },/* 
            console.log(xhr.responseText);
                var resp = jQuery.parseJSON(xhr.responseText);
                $.each(resp, function(index, value)
                {                    
                    flash(value,'error',10000);
                }); */
            update (value) {
                var id = value;                
                let uri = `/commitment/`+id; 
                this.show_spinner=true;
                axios.patch(uri,this.commitment)
                    .then(response=>{                       
                        this.editing = false;
                        this.show_spinner=false;
                        console.log(response.data.state+"----");
                        
                        if(response.data.state =='ALTA'){
                            if(!response.data)
                            flash(response.data,"error",1000);
                        this.commitment.id  =   response.data.id;    
                        this.commitment.commitment_type = response.data.commitment_type;
                        this.commitment.number = response.data.number;
                        this.commitment.destination = response.data.destination;
                        this.commitment.commision_date = response.data.commision_date;
                        this.commitment.state = response.data.state;
                        this.enable_delete=true;
                        console.log("condatos");
                        
                        }
                        else{
                            console.log("eliminado");
                            this.create = true;
                            this.enable_delete=false;
                            this.commitment.id = 0;
                            this.commitment.commitment_type = "";
                            this.commitment.number = "";
                            this.commitment.destination = "";
                            this.commitment.commision_date = '';
                            this.commitment.state = '';
                        }
                        
                        console.log(response);
                        flash('Informacion actualizada');
                    }).catch((response)=>{
                        this.show_spinner=false;                                                
                        flash('fds','error');
                        flash('Error al actualizar el afiliadossss: '+response.message,'error');
                    })
            }
        }
	}
</script>