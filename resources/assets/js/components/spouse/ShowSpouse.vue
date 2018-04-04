<script>
	export default{
		props:[
            'spouse',
                    'affiliate_id',
        ],
        data(){
            return{
                editing: false,
                show_spinner: false,
                form:this.spouse,
                city_birth: this.spouse.city_birth,
                city_identity_card: this.spouse.city_identity_card,
                first_name:{
                    value: this.spouse.first_name,
                    edit: false,
                },
                values:{
                    identity_card:this.spouse.identity_card,
                    resgistration:this.spouse.resgistration,
                    first_name: this.spouse.first_name,
                    second_name: this.spouse.second_name,
                    last_name: this.spouse.last_name,
                    mothers_last_name: this.spouse.mothers_last_name,
                    surname_husband: this.spouse.surname_husband,
                    civil_status: this.spouse.civil_status,
                    birth_date: this.spouse.birth_date                    
                }
            }
        },
        computed:{
            age: function(){
                if(this.form.birth_date){
                    var birthday = +new Date(this.form.birth_date); 
                    return~~ ((Date.now() - birthday) / (31557600000));
                }else
                {
                    return '';
                }
            },
            city_birth_name: function(){
                console.log("reactividad hdp 0");
                return !!this.city_birth?this.city_birth.name:'';
            },
            city_identity_card_name: function(){
                console.log('reactividad hdp 1');
                return !!this.city_identity_card?this.city_identity_card.first_shortened:'';
            },
            civil_status_name:function(){
                var st = '';
                switch(this.form.civil_status)
                {
                    case "S":
                        st= 'Soltero(a)';
                        break;
                    
                    case "D":
                        st= 'Divorciado(a)';
                        break;
                    
                    case "V":
                        st= 'Viudo(a)';
                        break;

                    case "C":
                        st= 'Casado(a)';
                        break;

                }   
                return st;
            }

        },
        methods:{
            edit_first_name: function(){
            },
            toggle_editing:function () {
                this.editing = !this.editing;
                if(this.editing==false)
                {     
                    this.form.identity_card = this.values.identity_card;
                    this.form.resgistration = this.values.resgistration;
                    this.form.first_name =  this.values.first_name;
                    this.form.second_name =  this.values.second_name;
                    this.form.last_name =  this.values.last_name;
                    this.form.mothers_last_name =  this.values.mothers_last_name;
                    this.form.surname_husband = this.values.surname_husband;
                    this.form.civil_status = this.values.civil_status;
                    this.form.birth_date =  this.values.birth_date;
                    this.form.city_birth_id = this.city_birth.id;
                    this.form.city_identity_card_id = this.city_identity_card.id;

                }
                // console.log(this.form);
            },
            update () {
                let uri = `/update_affiliate/${this.affiliate.id}`;
                this.show_spinner=true;
                axios.patch(uri,this.form)
                    .then(response=>{
                        this.editing = false;
                        this.show_spinner=false;
                        this.form = response.data.affiliate;
                        this.city_birth = response.data.city_birth;
                        this.city_identity_card = response.data.city_identity_card; 
                        console.log(response);
                        this.values.identity_card = response.data.affiliate.identity_card;
                        this.values.first_name =  response.data.affiliate.first_name;
                        this.values.second_name =  response.data.affiliate.second_name;
                        this.values.last_name =  response.data.affiliate.last_name;
                        this.values.mothers_last_name =  response.data.affiliate.mothers_last_name;
                        this.values.birth_date =  response.data.affiliate.birth_date;
                        this.values.phone_number =  response.data.affiliate.phone_number;
                        this.values.cell_phone_number =  response.data.affiliate.cell_phone_numbe;
                        this.values.gender = response.data.affiliate.gender;
                        this.values.civil_status = response.data.affiliate.civil_status;

                        flash('Informacion del Afiliado Actualizada');
                    }).catch((response)=>{
                        this.show_spinner=false;
                        this.toggle_editing();
                        flash('Error al actualizar el afiliado: '+response.message,'error');
                    })
            }
        }
	}
</script>