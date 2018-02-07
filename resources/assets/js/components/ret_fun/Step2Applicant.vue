<script>
export default {
  props:[
    'modality',
    'cities',
    'kinships',
    'spouse',
    'affiliate',
  ],
  data(){
    return{
      applicant_type: '',
      applicant_first_name: '',
      applicant_second_name: '',
      applicant_last_name: '',
      applicant_mothers_last_name: '',
      applicant_surname_husband: '',
      applicant_identity_card: '',
      applicant_city_identity_card: '',
      applicant_kinship: '',
      applicant_phone_number: '',
      applicant_cell_phone_number: '',
      advisor_resolution_number: '',
      advisor_resolution_date: '',
      legal_guardian_first_name: '',
      legal_guardian_second_name: '',
      legal_guardian_first_name: '',
      legal_guardian_second_name: '',
      legal_guardian_last_name: '',
      legal_guardian_mothers_last_name: '',
      legal_guardian_surname_husband: '',
      legal_guardian_identity_card: '',
      legal_guardian_city_identity_card: '',
      legal_guardian_number_authority: '',
      legal_guardian_notary_of_public_faith: '',
      legal_guardian_notary: '',
      beneficiary_zone: '',
      beneficiary_street: '',
      beneficiary_number_address: '',
      applicant_type: null,
      show_advisor_form: false,
      show_apoderado_form: false,
    }
  },
  methods: {
    change_applicant: function() {
      var modality_id=document.getElementById('ret_fun_modality').value;
      if(this.applicant_type  === '2'){
        this.show_advisor_form = !this.show_advisor_form;
        this.show_apoderado_form = false;
        this.resetAffiliate();
        return;
      }
      if(this.applicant_type  === 'Apoderado'){
        this.show_apoderado_form = !this.show_advisor_form;
        this.show_advisor_form = false;
        return;
      }
      if(this.applicant_type  === '1'){
        this.show_apoderado_form = false;
        this.show_advisor_form = false;
        if(modality_id == 4){ 
          console.log('get Data Spouse');
          this.setDataSpouse();
        }else{
          console.log('get Data Affiliate');
          this.setDataAffilate();
        }
        return;
      }
    },
    resetAffiliate: function () {
      this.applicant_first_name = '';
      this.applicant_second_name = '';
      this.applicant_last_name = '';
      this.applicant_mothers_last_name = '';
      this.applicant_surname_husband = '';
      this.applicant_surname_husband = '';
      this.applicant_identity_card = '';
      this.applicant_city_identity_card = '';
    },
    setDataAffilate: function(){
        this.applicant_first_name = this.affiliate.first_name;
        this.applicant_second_name = this.affiliate.second_name;
        this.applicant_last_name = this.affiliate.last_name;
        this.applicant_mothers_last_name = this.affiliate.mothers_last_name;
        this.applicant_surname_husband = this.affiliate.surname_husband;
        this.applicant_surname_husband = this.affiliate.surname_husband,
        this.applicant_identity_card = this.affiliate.identity_card;
        this.applicant_city_identity_card = this.affiliate.city_identity_card_id;
        this.applicant_kinship = 1;
    },
    setDataSpouse: function(){
      this.applicant_first_name = this.affiliate.first_name;
        this.applicant_second_name = this.affiliate.second_name;
        this.applicant_last_name = this.affiliate.last_name;
        this.applicant_mothers_last_name = this.affiliate.mothers_last_name;
        this.applicant_surname_husband = this.affiliate.surname_husband;
        this.applicant_surname_husband = this.affiliate.surname_husband,
        this.applicant_identity_card = this.affiliate.identity_card;
        this.applicant_city_identity_card = 3;
        this.applicant_kinship = 2;
    }

  }
}
</script>