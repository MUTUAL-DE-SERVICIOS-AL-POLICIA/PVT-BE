<script>
import {scroller} from 'vue-scrollto/src/scrollTo'
	export default{
		props:[
            'beneficiaries2',
			'beneficiariesBackend',
			'originalBeneficiariesBackend',
            'cities',
            'kinships',
            'retFunId',
            'procedureModalityId',
            'kinship_beneficiaries'
		],
        data(){
            return{
                editing: false,
                show_spinner: false,
                iterator:-1,
                beneficiaries: this.beneficiariesBackend,
            }
        },
        computed:{
        },
        methods:{
            toggle_editing () {
			this.editing = !this.editing;
                if(this.editing==false)
                {
                    this.beneficiaries =  this.beneficiaries2;
                }
            },
            cancel(){
                this.beneficiaries =  this.originalBeneficiariesBackend;
                this.originalBeneficiariesBackend = this.beneficiaries;
                this.toggle_editing();
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
            update () {
                let uri = `/update_beneficiaries/${this.retFunId}`;
                this.show_spinner=true;
                console.log(this.beneficiaries);
                
                axios.patch(uri,this.beneficiaries)
                .then((response)=>{
                    this.editing = false;
                    this.show_spinner=false;
                    this.beneficiaries = response.data.beneficiaries;
                    flash('Información del Afiliado Actualizado');
                }).catch((response)=>{
                    this.show_spinner=false;
                    this.beneficiaries = this.ben;
                    flash('Error al actualizar el afiliado: '+response.message,'error');
                })
            },
            addBeneficiary(){
                let beneficiary = {
                        first_name: null,
                        second_name: null,
                        last_name: null,
                        mothers_last_name: null,
                        surname_husband: null,
                        identity_card: null,
                        city_identity_card_id: null,
                        birth_date: null,
                        kinship: null,
                        state: false,
                        legal_representative: null,
                        advisor_identity_card: null,
                        advisor_city_identity_card_id: null,
                        advisor_first_name: null,
                        advisor_second_name: null,
                        advisor_last_name: null,
                        advisor_mothers_last_name: null,
                        advisor_surname_husband: null,
                        advisor_birth_date: null,
                        advisor_gender: null,
                        // phone.value
                        // cell_phone.value
                        advisor_name_court: null,
                        advisor_resolution_number: null,
                        advisor_resolution_date: null,
                        kinship_beneficiary: null,

                        legal_guardian_identity_card: null,
                        legal_guardian_city_identity_card: null,
                        legal_guardian_first_name: null,
                        legal_guardian_second_name: null,
                        legal_guardian_last_name: null,
                        legal_guardian_mothers_last_name: null,
                        legal_guardian_surname_husband: null,
                        legal_guardian_gender: null,
                        legal_guardian_number_authority: null,
                        legal_guardian_notary_of_public_faith: null,
                        legal_guardian_notary: null,
                        legal_guardian_date_authority: null,
                }
                if(this.beneficiaries.length >= 0){
                    let last_beneficiary=this.beneficiaries[this.beneficiaries.length-1];
                    if (last_beneficiary.first_name) {
                        this.beneficiaries.push(beneficiary);
                    }
                }else{
                        this.beneficiaries.push(beneficiary);
                }
                setTimeout(() => {
                    if (this.$children[this.$children.length-1].$refs.identitycard) {
                        this.$children[this.$children.length-1].$refs.identitycard.focus();
                        const scrollToFooterCreateBeneficiaries = scroller();
                        scrollToFooterCreateBeneficiaries(`#footerCreateBeneficiaries${this.beneficiaries.length-1}`);
                    }
                }, 100);
            },
            canAddBeneficiary(){
                return this.procedureModalityId == 1 || this.procedureModalityId == 4 || this.procedureModalityId == 63;
            },
            removeBeneficiary(index){
                this.beneficiaries.splice(index,1);
            },
        }
	}
</script>