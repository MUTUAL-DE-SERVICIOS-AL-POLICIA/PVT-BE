<script>
export default {
  methods: {
      onFinish(){
          document.getElementById('ret-fun-form').submit();
      },
      sendApplicant(){
            let applicant={
                type : this.$refs.dos.$children[0].applicant_type,
                first_name : this.$refs.dos.$children[0].applicant_first_name,
                second_name : this.$refs.dos.$children[0].applicant_second_name,
                last_name : this.$refs.dos.$children[0].applicant_last_name,
                mothers_last_name : this.$refs.dos.$children[0].applicant_mothers_last_name,
                surname_husband : this.$refs.dos.$children[0].applicant_surname_husband,
                identity_card : this.$refs.dos.$children[0].applicant_identity_card,
                city_identity_card : this.$refs.dos.$children[0].applicant_city_identity_card,
                kinship : this.$refs.dos.$children[0].applicant_kinship,
                phone_number : this.$refs.dos.$children[0].applicant_phone_number,
                cell_phone_number : this.$refs.dos.$children[0].applicant_cell_phone_number,
            }
            this.$store.commit('setApplicant',applicant)
          return true;
        }


  }
}
</script>
