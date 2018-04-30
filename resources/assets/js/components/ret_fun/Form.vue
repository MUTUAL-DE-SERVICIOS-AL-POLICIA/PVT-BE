<script>
export default {
    data(){
        return{
            pass:false,
            loadingWizard: false,
            count:0,
            name:null,
            email:null,
            phone:null,
            url:null,
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

            if (!this.$refs.uno.$children[0].city_end_id) {
                return false;
            }
            if (!this.$refs.uno.$children[0].modality) {
                return false;
            }
            var someRequirement = this.$refs.uno.$children[0].requirementList.some((val)=>{
                return val.status;
            })
            if (!someRequirement) {
                return false;
            }
            return true;
            // var deferred = $.Deferred();

            // const val = this.$validator;
            // setTimeout(function(){
            //     val.validateAll((res)=>{
            //         this.pass=res;
            //     })
            //     console.log("long func completed");
            //     deferred.resolve("hello");
            // }, 1000);
            // return deferred.promise().then((h)=>{return this.pass});

        },
        validateSecondStep() {
            if (!this.$refs.dos.$children[0].applicant_type) {
                console.log("aqui");
                
                return false;
            }
            if (!this.$refs.dos.$children[0].applicant_identity_card) {
                console.log("aqui 1");
                return false;
            }
            if (!this.$refs.dos.$children[0].applicant_first_name) {
                console.log("aqui 2");
                return false;
            }
            if (!this.$refs.dos.$children[0].applicant_kinship_id) {
                console.log("aqui 3");
                return false;
            }

            this.sendApplicant();
            return true;
            // var deferred = $.Deferred();

            // const val = this.$validator;
            // setTimeout(function(){
            //     val.validateAll((res)=>{
            //         this.pass=res;
            //     })
            //     console.log("long func completed");
            //     deferred.resolve("hello");
            // }, 1000);
            // return deferred.promise().then((h)=>{return this.pass});

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
