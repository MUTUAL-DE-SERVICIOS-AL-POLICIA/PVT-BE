<script>
	export default{
		props:[
            'spouse',
                    'cities'
        ],
        data(){
            return{
                editing: false,
                show_spinner: false,
                form:this.spouse,
                city_birth: null,
                city_identity_card: null,
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
        created:function(){
            let city_id =this.spouse.city_birth_id;
            this.city_birth=this.cities.filter(function(city) {
                return city.id==city_id;
            })[0];
            
            city_id =this.spouse.city_identity_card_id;
            this.city_identity_card=this.cities.filter(function(city) {
                return city.id==city_id;
            })[0];
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
            get_city_birth_name:function(city_birth){
                var name='';
                console.log(this.city_birth);
                let city_birth_id =city_birth;
                this.cities.forEach(function(city) {
                    console.log(city);
                    if(city.id==city_birth_id){
                        name = city.name;
                    }
                });
                return name;
            },
            get_city_first_shortened:function(){
                var name='';
                console.log(this.city_identity_card);
                let city_identity_card =this.city_identity_card;
                this.cities.forEach(function(city) {
                    console.log(city);
                    if(city.id==city_identity_card){
                        name = city.first_shortened;
                    }
                });
                return name;
            },
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
                let uri = `/update_spouse/${this.spouse.id}`;
                this.show_spinner=true;
                axios.patch(uri,this.form)
                    .then(response=>{
                        this.editing = false;
                        this.show_spinner=false;
                        this.form = response.data.spouse;
                        this.city_birth = response.data.city_birth;
                        this.city_identity_card = response.data.city_identity_card; 
                        console.log(response);
                        this.values.identity_card = response.data.spouse.identity_card;
                        this.values.resgistration = response.data.spouse.resgistration;
                        this.values.first_name =  response.data.spouse.first_name;
                        this.values.second_name =  response.data.spouse.second_name;
                        this.values.last_name =  response.data.spouse.last_name;
                        this.values.mothers_last_name =  response.data.spouse.mothers_last_name;
                        this.values.surname_husband = response.data.spouse.surname_husband;
                        this.values.birth_date =  response.data.spouse.birth_date;
                        this.values.civil_status = response.data.spouse.civil_status;

                        flash('Informacion de Esposa(o) Actualizada');
                    }).catch((response)=>{
                        this.show_spinner=false;
                        this.toggle_editing();
                        flash('Error al actualizar ala esposa(o): '+response.message,'error');
                    })
            }
        }
	}
</script>