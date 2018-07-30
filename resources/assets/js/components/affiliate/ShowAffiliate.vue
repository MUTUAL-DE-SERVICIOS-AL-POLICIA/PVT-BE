<script>
import { dateInputMaskAll } from "../../helper.js";
	export default{
		props:[
            'affiliate',
            'cities'    
		],
        data(){
            return{
                vDate:null,
                editing: false,
                show_spinner: false,
                form:this.affiliate,
                city_birth: !!this.affiliate.city_birth?this.affiliate.city_birth:null,
                city_identity_card: !!this.affiliate.city_identity_card?this.affiliate.city_identity_card:null,
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
                    civil_status: this.affiliate.civil_status,
                    surname_husband: this.affiliate.surname_husband,
                    address: this.affiliate.address,
                    registration: this.affiliate.registration
                }
            }
        },
        created:function(){
            // if(!this.city_birth){
            //     
            // let city_id =this.affiliate.city_birth_id;
            // this.city_birth=this.cities.filter(function(city) {
            //     return city.id==city_id;
            // })[0];
            // city_id =this.affiliate.city_identity_card_id;
            // this.city_identity_card=this.cities.filter(function(city) {
            //     return city.id==city_id;
            // })[0];
            // }
            console.log(moment().subtract(100, 'years').format("DD/MM/YYYY"));
            console.log(moment().format("DD/MM/YYYY"));
            this.vDate={

                date_format:'DD/MM/YYYY',
                // date_between: `${moment().subtract(100, 'years').format("DD/MM/YYYY")},${moment().format("DD/MM/YYYY")}`
                date_between: "01/04/2000,10/10/2018"
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
                return !!this.city_birth?this.city_birth.name:'';
            },
            city_identity_card_name: function(){                
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
            },
            validAll(){
                if (this.$validator.errors.collect() == {}) {
                    return false;
                }
                return Object.keys(this.$validator.errors.collect()).length > 0;
            }
        },
        methods:{
             async validateBeforeSubmit() {
                try {
                    await this.$validator.validateAll();
                } catch (error) {
                    console.log("some error");
                }
            },
            edit_first_name: function(){
            },
            toggle_editing:function () {
                this.editing = !this.editing;
                dateInputMaskAll();
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
                    // this.form.city_identity_card_id = this.city_identity_card.id;
                    this.form.surname_husband = this.values.surname_husband;
                    this.form.address = this.values.address;
                    this.form.registration = this.values.registration;

                }else{
                    this.validateBeforeSubmit();
                }
            },
            update () {
                this.validateBeforeSubmit();
                if (this.validAll) {
                    flash("Debe completar el formulario", 'error')
                    return;
                }
                let uri = `/update_affiliate/${this.affiliate.id}`;
                this.show_spinner=true;
                axios.patch(uri,this.form)
                    .then(response=>{
                        this.editing = false;
                        this.show_spinner=false;
                        this.form = response.data.affiliate;
                        this.city_birth = response.data.city_birth;
                        this.city_identity_card = response.data.city_identity_card; 
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
                        this.values.surname_husband = response.data.affiliate.surname_husband;
                        this.values.address = response.data.affiliate.address;

                        flash('Informacion del Afiliado Actualizada');
                    }).catch((response)=>{
                        this.show_spinner=false;
                        this.toggle_editing();
                        flash('Error al actualizar el afiliado: '+response.message,'error');
                    })
            }
        },
	}
</script>