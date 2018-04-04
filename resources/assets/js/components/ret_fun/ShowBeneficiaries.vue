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
            }
        },
        computed:{
            // age: function(){
            //     var birthday = +new Date(this.beneficiary.birth_date); 
            //     if(this.beneficiary.birth_date!=null){

            //         return~~ ((Date.now() - birthday) / (31557600000));
            //     }else
            //     {
            //         return '';
            //     }
            // },
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
            searchPerson(iterator){
                let ci= this.ben[iterator].identity_card;
                
                axios.get('/search_ajax', {
                    params: {
                    ci
                    }
                })
                .then( (response) => {
                    let data = response.data;
                    this.setDataBeneficiary(data, iterator);
                })
                .catch(function (error) {
                    console.log(error);
                });
                },
                setDataBeneficiary(data, index) {
                    this.ben[index].first_name = data.first_name;
                    this.ben[index].second_name = data.second_name;
                    this.ben[index].last_name = data.last_name;
                    this.ben[index].mothers_last_name = data.mothers_last_name;
                    this.ben[index].surname_husband = data.surname_husband;
                    this.ben[index].identity_card = data.identity_card;
                    this.ben[index].city_identity_card_id = data.city_identity_card_id;
                    this.ben[index].birth_date = data.birth_date;
                    this.ben[index].kinship_id = data.kinship_id;
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