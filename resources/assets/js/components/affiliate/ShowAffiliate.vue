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
                city_birth: this.affiliate.city_birth,
                city_identity_card: this.affiliate.city_identity_card,
                first_name:{
                    value: this.affiliate.first_name,
                    edit: false,
                },
                values:{
                    identity_card:this.affiliate.identity_card,
                    first_name: this.affiliate.first_name,
                    second_name: this.affiliate.second_name,
                    last_name: this.affiliate.last_name,
                    mothers_last_name: this.affiliate.mothers_last_name,
                    birth_date: this.affiliate.birth_date,
                    phone_number: this.affiliate.phone_number,
                    cell_phone_number: this.affiliate.cell_phone_number,
                    gender: this.affiliate.gender,
                    civil_status: this.affiliate.civil_status
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
            gender_name: function(){
                    var g = '';
                    if(this.form.gender=="F")
                    {
                        g ='Femenino';
                    }
                    if(this.form.gender=="M") 
                    {
                       g = 'Masculino';
                    }
                    return g;
                // return !!this.form.gender==='F'?'Femenino':'Masculino'; 
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
                    this.form.first_name =  this.values.first_name;
                    this.form.second_name =  this.values.second_name;
                    this.form.last_name =  this.values.last_name;
                    this.form.mothers_last_name =  this.values.mothers_last_name;
                    this.form.birth_date =  this.values.birth_date;
                    this.form.phone_number =  this.values.phone_number;
                    this.form.cell_phone_number =  this.values.cell_phone_numbe;
                    this.form.gender = this.values.gender;
                    this.form.civil_status = this.values.civil_status;
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
                        flash('Error al actualizar el afiliado: '+response.message,'error');
                    })
            }
        }
	}
</script>