<script>
	export default{
		props:[
			'beneficiaries',
                        'cities',
                        'kinships',
                        'original_beneficiaries',
		],
        data(){
            return{
                editing: false,
                show_spinner: false,
                ben:this.original_beneficiaries,
                iterator:-1,
//                first_name:{
//                    value: this.beneficiary.first_name,
//                    edit: false,
//                }
            }
        },
        computed:{
            age: function(){
                var birthday = +new Date(this.beneficiary.birth_date); 
                if(this.beneficiary.birth_date!=null){

                    return~~ ((Date.now() - birthday) / (31557600000));
                }else
                {
                    return '';
                }
            },
        },        
        methods:{            
            toggle_editing:function () {
                this.editing = !this.editing;
                //this.ben = this.original_beneficiaries;
                
            },
            getCity (id){
                var i=0;
                for(i=0;i<this.cities.length;i++)
                    if(this.cities[i].id == id)
                        return this.cities[i].first_shortened;
                return "S/N";
            },
            getKinship (id){
                var i=0;
                for(i=0;i<this.kinships.length;i++)
                    if(this.kinships[i].id == id)
                        return this.kinships[i].name;
                return "S/N";
            },
            searchApplicant(iterator){
                let ci= this.ben[iterator],identity_card;
                alert(ci);
                axios.get('/search_ajax', {
                    params: {
                    ci
                    }
                })
                .then( (response) => {
                    let data = response.data;
                    this.setDataApplicant(data);
                })
                .catch(function (error) {
                    console.log(error);
                });
                },
            update () {
                console.log(this.ben[0].identity_card+"console");        
                //console.log(this.$refs.name_ben[0].value);
                let uri = `/update_beneficiaries/322`;
                this.show_spinner=true;
                axios.patch(uri,this.ben)
                    .then(()=>{
                        
                        this.editing = false;
                        this.show_spinner=false;
                        this.beneficiaries = this.ben;
                        flash('Informacion del Afiliado Actualizada');
                    }).catch((response)=>{
                        this.show_spinner=false;
                        this.beneficiaries = this.ben;
                        flash('Error al actualizar el afiliado: '+response.message,'error');
                    })
            }
        }
	}
</script>