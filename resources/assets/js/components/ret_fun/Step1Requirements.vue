
<script>
	export default{
		props:[
			'modalities',
                        'requirements'
		],
        data(){
            return{

                editing: false,
                modality: null,
                show_spinner: false,
                modality_id: 3,
                actual_target: 1,
                //requirement_list: [],
                
//                form:{
//                    identity_card: this.affiliate.identity_card,
//                    first_name: this.affiliate.first_name,
//                    second_name: this.affiliate.second_name,
//                    last_name: this.affiliate.last_name,
//                    mothers_last_name: this.affiliate.mothers_last_name,
//                    gender: this.affiliate.gender,
//                },
//                first_name:{
//                    value: this.affiliate.first_name,
//                    edit: false,
//                }
            }
        },
        methods:{
          hello:function(){

              //            requirementsList(requirements){
//         requirements.filter(function (x) { return x.modality_id > this.modalit; })[0];
//            }
          },
          actualTarget:function(data){
                //console.log(this.actual_target+' '+data);
                var tar = this.actual_target;
                this.actual_target = data;
                return tar;
            }
        },
//        mounted(){
//            //console.log(this.requirements);
//        },
        computed:{
            requirementsList(){
                // bus.$emit('modality_on_f', $this.modality);
                var list = [];
                for(var i=0;i<this.requirements.length;i++){
                    if(this.modality == this.requirements[i].modality_id)
                        list.push(this.requirements[i]);
                }
                return list;
            },
            
//            getModality(){
//                return this.modality;
//            }
        },
//        methods:{
//            edit_first_name: function(){
//                console.log(this.first_name.value)
//            },
//            toggle_editing:function () {
//                this.editing = !this.editing;
//            },
//            update () {
//                let uri = `/update_affiliate/${this.affiliate.id}`;
//                this.show_spinner=true;
//                axios.patch(uri,this.form)
//                    .then(()=>{
//                        this.editing = false;
//                        this.show_spinner=false;
//                        flash('Informacion del Afiliado Actualizada');
//                    }).catch((response)=>{
//                        this.show_spinner=false;
//                        flash('Error al actualizar el afiliado: '+response.message,'error');
//                    })
//            }
//        }
	}
</script>