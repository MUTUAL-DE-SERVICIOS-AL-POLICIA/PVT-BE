<script>
import { dateInputMaskAll, flashErrors } from "../../helper.js";
	export default{
		props:[
            'spouse',
            'cities',
            'affiliateId'
        ],
        data(){
            return{
                editing: false,
                editingIdentityCard: false,
                show_spinner: false,
                backup: {},
                form:this.spouse,
                city_birth: !!this.spouse.city_birth?this.spouse.city_birth:null,
                city_identity_card: !!this.spouse.city_identity_card?this.spouse.city_identity_card:null,
                values:{
                    identity_card:!! this.spouse.identity_card ? this.spouse.identity_card :null ,
                    resgistration:!! this.spouse.resgistration ? this.spouse.resgistration :null ,
                    first_name: !! this.spouse.first_name ? this.spouse.first_name :null ,
                    second_name: !! this.spouse.second_name ? this.spouse.second_name :null ,
                    last_name: !! this.spouse.last_name ? this.spouse.last_name :null ,
                    mothers_last_name: !! this.spouse.mothers_last_name ? this.spouse.mothers_last_name :null ,
                    surname_husband: !! this.spouse.surname_husband ? this.spouse.surname_husband :null ,
                    civil_status: !! this.spouse.civil_status ? this.spouse.civil_status :null ,
                    birth_date: !! this.spouse.birth_date ? this.spouse.birth_date :null ,
                    date_death: !! this.spouse.date_death ? this.spouse.date_death :null ,
                    reason_death: !! this.spouse.reason_death ? this.spouse.reason_death :null ,
                    death_certificate_number: !! this.spouse.death_certificate_number ? this.spouse.death_certificate_number :null ,
                }
            }
        },
        computed:{
            age: function(){
                if(this.form.birth_date!=null){
                    if (this.form.birth_date.includes('y') || this.form.birth_date.includes('m') || this.form.birth_date.includes('d') ) {
                        return ''
                    }

                    if(this.form.birth_date){
                        return moment(this.form.birth_date, "DD/MM/YYYY").fromNow(true)
                    }else
                    {
                        return '';
                    }
                }
            },
            city_birth_name: function(){
                return !!this.city_birth?this.city_birth.name:'';
            },
            city_identity_card_name: function(){
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
            },
            validAll(){
                if (this.$validator.errors.collect() == {}) {
                    return false;
                }
                return Object.keys(this.$validator.errors.collect()).length > 0;
            },
            isLoading() {
                return this.show_spinner;
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
            get_city_birth_name:function(city_birth){
                var name='';
                let city_birth_id =city_birth;
                this.cities.forEach(function(city) {
                    if(city.id==city_birth_id){
                        name = city.name;
                    }
                });
                return name;
            },
            get_city_first_shortened:function(){
                var name='';
                let city_identity_card =this.city_identity_card;
                this.cities.forEach(function(city) {
                    if(city.id==city_identity_card){
                        name = city.first_shortened;
                    }
                });
                return name;
            },

            toggle_editing_ci() {
                this.backup = JSON.parse(JSON.stringify(this.form));
                if (this.editingIdentityCard) {
                    this.editingIdentityCard = false;
                    this.form.identity_card = this.values.identity_card;
                } else {
                    this.editingIdentityCard = true;
                    this.validateBeforeSubmit();
                }
                if (this.editing) {
                    this.cancel_editing_ci();
                }
            },

            toggle_editing:function () {
                this.editing = true;
                dateInputMaskAll();
                if(this.editing==false)
                {
                    this.form.identity_card = this.values.identity_card;
                    this.form.first_name =  this.values.first_name;
                    this.form.second_name =  this.values.second_name;
                    this.form.last_name =  this.values.last_name;
                    this.form.mothers_last_name =  this.values.mothers_last_name;
                    this.form.surname_husband = this.values.surname_husband;
                    this.form.civil_status = this.values.civil_status;
                    this.form.birth_date =  this.values.birth_date;
                    this.form.city_birth_id = !!this.city_birth ? this.city_birth.id : null;
                    this.form.city_identity_card_id = !!this.city_identity_card ? this.city_identity_card.id : null;
                    this.form.registration = this.values.registration;
                    this.form.date_death = this.values.date_death;
                    this.form.reason_death = this.values.reason_death;
                    this.form.death_certificate_number = this.values.death_certificate_number;
                }else{
                    this.validateBeforeSubmit();
                }
            },
            async getDataSpouse () {
                this.editingIdentityCard = false;
                this.toggle_editing();
                if (!this.form.identity_card) {
                    flash("Debe ingresar una cédula", "error");
                    return;
                }
                this.show_spinner = true;
                try {
                    this.backup = JSON.parse(JSON.stringify(this.form));
                    const response = await axios.get(`/person-data/${this.form.identity_card}`);
                    const data = response.data.spouses;
                    if (!data) {
                        flash("No se encontraron datos para la cédula ingresada", "warning");
                        return;
                    }
                    this.editingIdentityCard = false;

                    this.form.first_name = data.first_name;
                    this.form.second_name = data.second_name;
                    this.form.last_name = data.last_name;
                    this.form.mothers_last_name = data.mothers_last_name;
                    this.form.surname_husband = data.surname_husband;
                    this.form.birth_date = data.birth_date;
                    this.form.city_birth_id = data.city_birth_id;
                    this.form.civil_status = data.civil_status;
                    this.form.date_death = data.date_death;
                    this.form.reason_death = data.reason_death;
                    this.form.death_certificate_number = data.death_certificate_number;

                    Object.assign(this.values, data);
                    flash("Persona encontrada exitosamente", "success");
                    return response.data;
                } catch (error) {
                    flash("Error al obtener datos del cónyuge", "error");
                }finally {
                this.show_spinner = false;
                }
            },
            cancel_editing_ci() {
                this.editingIdentityCard = false;
                this.editing = false;
                if (this.backup) {
                    this.form = JSON.parse(JSON.stringify(this.backup));
                }
            },

            update() {
                this.validateBeforeSubmit();
                if (this.validAll) {
                    flash("Debe completar el formulario", 'error')
                    return;
                }
                let uri = `/update_spouse/${this.affiliateId}`;
                this.show_spinner=true;
                axios.patch(uri,this.form)
                    .then(response=>{
                        this.editing = false;
                        this.show_spinner=false;
                        this.form = response.data.spouse;
                        this.city_birth = response.data.city_birth;
                        this.city_identity_card = response.data.city_identity_card; 
                        this.values.identity_card = response.data.spouse.identity_card;
                        this.values.resgistration = response.data.spouse.resgistration;
                        this.values.first_name =  response.data.spouse.first_name;
                        this.values.second_name =  response.data.spouse.second_name;
                        this.values.last_name =  response.data.spouse.last_name;
                        this.values.mothers_last_name =  response.data.spouse.mothers_last_name;
                        this.values.surname_husband = response.data.spouse.surname_husband;
                        this.values.birth_date =  response.data.spouse.birth_date;
                        this.values.civil_status = response.data.spouse.civil_status;
                        this.values.date_death = response.data.spouse.date_death;
                        this.values.reason_death = response.data.spouse.reason_death;
                        this.values.death_certificate_number = response.data.spouse.death_certificate_number;
                        flash('Informacion de Esposa(o) Actualizada');
                    }).catch((error)=>{
                        this.show_spinner=false;
                        this.toggle_editing();
                        flashErrors('Error al actualizar el afiliado',error.response.data.errors)
                    })
            }
        }
	}
</script>