<script>
import {scroller} from 'vue-scrollto/src/scrollTo'
export default {
    props:[
        'affiliateId'
    ],
    data(){
        return{
            pass:false,
            loadingWizard: false,
            showRequirementsError: false,
            count:0,
            name:null,
            email:null,
            phone:null,
            url:null,
            hola: 'hola'
        }
    },
    methods: {
        onFinish() {
            document.getElementById("contribution-process-form").submit();
        },
        setLoading: function(value) {
            this.loadingWizard = value;
        },
        showRequirementsErrorChanged(val){
            this.showRequirementsError = val;
        },
        validateFirstStep() {
            this.showRequirementsError = false;
            if (!this.$refs.uno.$children[0].city_id) {
                return false;
            }
            // if (!this.$refs.uno.$children[0].modality) {
            //     return false;
            // }
            let x=this.$refs.uno.$children[0].requirementList;
            var someRequirement=true;
            Object.keys(x).forEach(function(key) {
                if( !x[key].some(rq=> rq.status) ){
                    someRequirement=false;
                }
            });
            if (!someRequirement) {
                this.showRequirementsError = ! this.showRequirementsError;
                return false;
            }
            const scrollToFooterCreateBeneficiaries = scroller();
            scrollToFooterCreateBeneficiaries('#ret-fun-form-header');
            return true;

        },
        validateSecondStep() {
            // if (!this.$refs.dos.$children[0].applicant_type) {
            //     return false;
            // }
            // if (!this.$refs.dos.$children[0].applicant_identity_card) {
            //     return false;
            // }
            // if (!this.$refs.dos.$children[0].applicant_first_name) {
            //     return false;
            // }
            // if (!this.$refs.dos.$children[0].applicant_kinship_id) {
            //     return false;
            // }
            // if (!this.$refs.dos.$children[0].applicant_city_identity_card_id) {
            //     return false;
            // }
            // if (!this.$refs.dos.$children[0].applicant_gender) {
            //     return false;
            // }
            // if (!this.$refs.dos.$children[0].date_derelict) {
            //     return false;
            // }

            // if (this.$refs.dos.$children[0].applicant_type == 3) {
            //     // 3 id de Apoderado
            //     if (!this.$refs.dos.$children[0].legal_guardian_first_name) {
            //         return false;
            //     }
            //     if (!this.$refs.dos.$children[0].legal_guardian_identity_card) {
            //         return false;
            //     }
            // }
            let commitment = {
                commitment_type: this.$refs.dos.$children[0].commitment_type,
                number: this.$refs.dos.$children[0].number,
                commision_date: this.$refs.dos.$children[0].commision_date,
                destination: this.$refs.dos.$children[0].destination,
                commitment_date: this.$refs.dos.$children[0].commitment_date,
                pension_declaration: this.$refs.dos.$children[0].pension_declaration,
                pension_declaration_date: this.$refs.dos.$children[0].pension_declaration_date,
                date_commitment: this.$refs.dos.$children[0].date_commitment,
                start_contribution_date: this.$refs.dos.$children[0].start_contribution_date,
                contributorType: this.$refs.dos.$children[0].contributorType,
            }
            this.$store.commit("contributionProcessForm/updateCommitment", commitment);
            let procedure_modality_id= this.$store.state.contributionProcessForm.modality_id;
            axios.post(`/affiliate/${this.affiliateId}/contribution_process/save_commitment`,{
                commitment: commitment,
                procedure_modality_id: procedure_modality_id,
                affiliate_id: this.affiliateId
            }).then(response => {
                console.log(response.data);
            }).catch(error => {
                console.log(error);
            });



            const scrollToFooterCreateBeneficiaries = scroller();
            scrollToFooterCreateBeneficiaries('#ret-fun-form-header');
            return true;
        },
        sendApplicant() {
        let applicant = {
            type: this.$refs.dos.$children[0].applicant_type,
            first_name: this.$refs.dos.$children[0].applicant_first_name,
            second_name: this.$refs.dos.$children[0].applicant_second_name,
            last_name: this.$refs.dos.$children[0].applicant_last_name,
            mothers_last_name: this.$refs.dos.$children[0].applicant_mothers_last_name,
            surname_husband: this.$refs.dos.$children[0].applicant_surname_husband,
            identity_card: this.$refs.dos.$children[0].applicant_identity_card,
            city_identity_card_id: this.$refs.dos.$children[0].applicant_city_identity_card_id,
            kinship_id: this.$refs.dos.$children[0].applicant_kinship_id,
            birth_date: this.$refs.dos.$children[0].applicant_birth_date,
            gender: this.$refs.dos.$children[0].applicant_gender,
            phone_number: this.$refs.dos.$children[0].applicant_phone_number,
            cell_phone_number: this.$refs.dos.$children[0].applicant_cell_phone_number
        };
        this.$store.commit("retFunForm/setApplicant", applicant);
        return true;
        }
    },
    computed: {
      checkboxErrors () {
        const errors = []
        if (!this.$v.checkbox.$dirty) return errors
        !this.$v.checkbox.required && errors.push('You must agree to continue!')
        return errors
      },
      selectErrors () {
        const errors = []
        if (!this.$v.select.$dirty) return errors
        !this.$v.select.required && errors.push('Item is required')
        return errors
      },
      nameErrors () {
        const errors = []
        if (!this.$v.name.$dirty) return errors
        !this.$v.name.maxLength && errors.push('Name must be at most 10 characters long')
        !this.$v.name.required && errors.push('Name is required.')
        return errors
      },
      emailErrors () {
        const errors = []
        if (!this.$v.email.$dirty) return errors
        !this.$v.email.email && errors.push('Must be valid e-mail')
        !this.$v.email.required && errors.push('E-mail is required')
        return errors
      }
    },

};
</script>
