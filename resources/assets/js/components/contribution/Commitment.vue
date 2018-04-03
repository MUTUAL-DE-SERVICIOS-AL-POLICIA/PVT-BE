<script>
	export default{
		props:[
			'commitment',
                        'affiliate_id',
                        'today_date',
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
            getFormatDate: function(date_tarjet){
              var d = new Date(date_tarjet);
              return (d.getDate()+1)+"/"+(d.getMonth()+1)+"/"+d.getFullYear();
              
              //return (d.getMonth();
            },
            toggle_editing:function () {
                this.editing = !this.editing;
                //this.ben = this.original_beneficiar   ies;
                
            },                            
            toggle_create:function(){
                this.create = !this.create;
            },
            print_commitment(){
                var affiliate_id = this.affiliate_id;                
                printJS({printable:'/ret_fun/'+affiliate_id+'/print/ret_fun_commitment_letter', type:'pdf', showModal:true});
            },
//            disable_commitment(){
//                this.update(-1);
//            },
            create_new(){
                //this.toggle_create();
                //console.log(this.today_date);
                this.commitment.commitment_date=this.today_date;
                this.toggle_editing();
                //this.update(0);
                //this.commitment.affiliate_id = this.affiliate_id;
            },           
            update (value) {
                var id = value;                
                let uri = `/commitment/`+id; 
                this.show_spinner=true;
                axios.patch(uri,this.commitment)
                    .then(response=>{     
                        console.log(response.data.commitment_type);
                        this.editing = false;
                        this.show_spinner=false;
                        console.log(response.data.state+"----");
                        if(response.data.state =='ALTA'){
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
                        console.log(response.data);
                        flash('Informacion actualizada');
                       }).catch((error)=>{
                           if(error.response.data.number !== undefined)
                                flash(error.response.data.number[0],'error',10000);
                           if(error.response.data.commitment_type !== undefined)
                                flash(error.response.data.commitment_type[0],'error',10000);
                           if(error.response.data.destination !== undefined)
                                flash(error.response.data.destination[0],'error',10000);
                           if(error.response.data.commision_date !== undefined)
                                flash(error.response.data.commision_date[0],'error',10000);
                        this.show_spinner=false; 
                       // flash('Error al actualizar el afiliado: '+response.message,'error');
                    })
            }
        }
	}
</script>