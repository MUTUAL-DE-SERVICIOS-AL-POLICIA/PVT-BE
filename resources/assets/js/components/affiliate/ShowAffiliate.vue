<script>
import { flashErrors } from "../../helper.js";
	export default{
		props:[
            'affiliate',
            'cities'
		],
        data(){
            return{
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
                    date_death: this.affiliate.date_death,
                    reason_death: this.affiliate.reason_death,
                    phone_number: this.affiliate.phone_number,
                    cell_phone_number: this.affiliate.cell_phone_number,
                    gender: this.affiliate.gender,
                    civil_status: this.affiliate.civil_status,
                    surname_husband: this.affiliate.surname_husband,
                    address: this.affiliate.address,
                    registration: this.affiliate.registration                    
                },
                loadingButton: false,
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
                    this.form.date_death =  this.values.date_death;
                    this.form.reason_death =  this.values.reason_death;
                    this.form.phone_number =  this.values.phone_number;
                    this.form.cell_phone_number =  this.values.cell_phone_number;
                    this.form.gender = this.values.gender;
                    this.form.civil_status = this.values.civil_status;
                    this.form.city_birth_id = !!this.city_birth ? this.city_birth.id : null;
                    this.form.city_identity_card_id = !!this.city_identity_card ? this.city_identity_card.id : null;
                    this.form.surname_husband = this.values.surname_husband;
                    this.form.address = this.values.address;
                    this.form.registration = this.values.registration;
                }else{
                    this.validateBeforeSubmit();
                }
            },
            update () {
                this.loadingButton = true;
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
                        this.death_birth = response.data.death_birth;
                        this.city_identity_card = response.data.city_identity_card;
                        this.values.identity_card = response.data.affiliate.identity_card;
                        this.values.first_name =  response.data.affiliate.first_name;
                        this.values.second_name =  response.data.affiliate.second_name;
                        this.values.last_name =  response.data.affiliate.last_name;
                        this.values.mothers_last_name =  response.data.affiliate.mothers_last_name;
                        this.values.birth_date =  response.data.affiliate.birth_date;
                        this.values.date_death =  response.data.affiliate.date_death;
                        this.values.reason_death =  response.data.affiliate.reason_death;
                        this.values.phone_number =  response.data.affiliate.phone_number;
                        this.values.cell_phone_number =  response.data.affiliate.cell_phone_number;
                        this.values.gender = response.data.affiliate.gender;
                        this.values.civil_status = response.data.affiliate.civil_status;
                        this.values.surname_husband = response.data.affiliate.surname_husband;
                        this.values.address = response.data.affiliate.address;
                        flash('Informacion del Afiliado Actualizada');
                        this.loadingButton=false;
                    }).catch((error)=>{
                        this.show_spinner=false;
                        this.toggle_editing();
                        console.log(error.response.data.errors);
                        flashErrors('Error al actualizar el afiliado',error.response.data.errors)
                        this.loadingButton=false;
                        // flash(`Error al actualizar el afiliado: ${error.response.data.errors}`,'error');
                    })
                    
            },
            addPhoneNumber(){
                if (this.form.phone_number.length > 0) {
                    let last_phone = this.form.phone_number[this.form.phone_number.length-1];
                    if (last_phone && !last_phone.includes('_')) {
                        this.form.phone_number.push(null);
                    }
                }else{
                    this.form.phone_number.push(null);
                }
            },
            deletePhoneNumber(index){
                this.form.phone_number.splice(index,1);
                if(this.form.phone_number.length < 1)
                    this.addPhoneNumber()
            },
            addCellPhoneNumber(){
                if (this.form.cell_phone_number.length > 0) {
                    let last_phone = this.form.cell_phone_number[this.form.cell_phone_number.length-1];
                    if (last_phone && !last_phone.includes('_')) {
                    this.form.cell_phone_number.push(null);
                    }
                }else{
                    this.form.cell_phone_number.push(null);
                }
            },
            deleteCellPhoneNumber(index){
                this.form.cell_phone_number.splice(index,1);
                if(this.form.cell_phone_number.length < 1)
                    this.addCellPhoneNumber()
            },
        },
	}
</script>
