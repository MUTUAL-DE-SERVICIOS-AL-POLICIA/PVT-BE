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
                dropdownOpen: false,
                createButton:true,
                cancelButton: false,
                editingButton: this.spouse.id ? true : false,
                createAction:false,
                editingAction:false,
                editing: false,
                editingIdentityCard: false,
                show_spinner: false,
                backup: {},
                form:this.spouse,
                backup: {},
                form:JSON.parse(JSON.stringify(this.spouse)),
                city_birth: !!this.spouse.city_birth?this.spouse.city_birth:null,
                city_identity_card: !!this.spouse.city_identity_card?this.spouse.city_identity_card:null,
                values: JSON.parse(JSON.stringify(this.spouse)),
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
            captureState() {
            this.backup = {
                createButton: this.createButton,
                cancelButton: this.cancelButton,
                editingButton: this.editingButton,
                createAction: this.createAction,
                editing: this.editing,
                editingIdentityCard: this.editingIdentityCard,
                form: JSON.parse(JSON.stringify(this.form)),
                city_birth: this.city_birth ? JSON.parse(JSON.stringify(this.city_birth)) : null,
                city_identity_card: this.city_identity_card ? JSON.parse(JSON.stringify(this.city_identity_card)) : null,
                values: this.values,
                };
            },
            captureButtons() {
                this.backup.createButton = this.createButton;
                this.backup.cancelButton = this.cancelButton;
                this.backup.editingButton = this.editingButton;
            },

            restoreState() {
                this.createButton = this.backup.createButton;
                this.createButton = this.backup.createButton;
                this.cancelButton = this.backup.cancelButton;
                this.editingButton = this.backup.editingButton;
                this.editingIdentityCard = this.backup.editingIdentityCard;
                this.editing = this.backup.editing;
                this.form = JSON.parse(JSON.stringify(this.backup.form));
                this.values = this.backup.values;
                this.city_birth = this.backup.city_birth ? JSON.parse(JSON.stringify(this.backup.city_birth)) : null;
                this.city_identity_card = this.backup.city_identity_card ? JSON.parse(JSON.stringify(this.backup.city_identity_card)) : null;
            },
            restoreButtons() {
                this.createButton = this.backup.createButton;
                this.cancelButton = this.backup.cancelButton;
                this.editingButton = this.backup.editingButton;
                this.createAction = this.backup.createAction;
                this.editingAction = this.backup.editingAction;
            },

            create() {
                this.captureState();
                this.captureButtons();
                this.createButton = false;
                this.editingButton = false;
                this.cancelButton = true;
                this.restart_form();
                this.createAction = true;
                this.editing = false;
                this.toggle_editing_ci();
            },

            editingForm() {
                this.captureState();
                this.captureButtons();
                this.editingAction = true;
                this.createButton = false;
                this.editingButton = false;
                this.cancelButton = true;
                this.dropdownOpen = false;
                this.toggle_editing_ci();
                this.create_btn = !this.create_btn;
              },

            restart_form() {
                this.form = {
                    identity_card: '',
                    first_name: '',
                    second_name: '',
                    last_name: '',
                    mothers_last_name: '',
                    surname_husband: '',
                    civil_status: 'S',
                    birth_date: '',
                    city_birth_id: null,
                    city_identity_card_id: null,
                    registration: '',
                    date_death: null,
                    reason_death: null,
                    death_certificate_number: null
                };
                this.city_birth = null;
                this.city_identity_card = null;
            },


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
                    const response = await axios.get(`/person-data/${this.form.identity_card}`);
                    const data = response.data.spouses;
                    if (!data) {
                        flash("No se encontraron datos para la cédula ingresada", "warning");
                        return;
                    }
                    if (!this.editingAction) {
                        this.createAction = data.uuid_column===this.spouse.uuid_column? false : true;
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
                this.restoreState();
            },

            async update() {
                await this.validateBeforeSubmit();

                if (this.validAll) {
                    flash("Debe completar el formulario", 'error');
                    return;
                }
                let uri = this.createAction ? `/spouse/${this.affiliateId}` : `/update_spouse/${this.affiliateId}`;
                let requestMethod = this.createAction ? axios.post : axios.patch;
                try {
                    const response = await requestMethod(uri, this.form);
                    if (response.status === 200) {
                        flash("Cónyuge actualizado exitosamente", "success");
                    } else {
                        flash("Error al actualizar el cónyuge", "error");
                    }
                    this.editing = false;
                    this.show_spinner = false;
                } catch (error) {
                    this.show_spinner = false;
                    this.editing = true;
                    flashErrors('Error al actualizar el afiliado', error.response);
                }
                this.restoreButtons();
            }
        }
	}
</script>