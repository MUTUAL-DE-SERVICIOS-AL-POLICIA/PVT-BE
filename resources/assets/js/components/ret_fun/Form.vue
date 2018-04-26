<script>
export default {
    data(){
        return{
            loadingWizard: false,
            count:0,
        }
    },
    methods: {
        onFinish() {
        document.getElementById("ret-fun-form").submit();
        },
        setLoading: function(value) {
            this.loadingWizard = value;
        },
        validateFirstStep() {
            // this.$validator.validateAll().then((result) => {
            //     if (!result) {

            //     alert('Form Submitted!');
            //     return;
            //     }

            //     alert('Correct them errors!');
            // });
            return this.$validator.validate('city_end_id');
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
            phone_number: this.$refs.dos.$children[0].applicant_phone_number,
            cell_phone_number: this.$refs.dos.$children[0].applicant_cell_phone_number
        };
        this.$store.commit("setApplicant", applicant);
        return true;
        }
    }
};
</script>
