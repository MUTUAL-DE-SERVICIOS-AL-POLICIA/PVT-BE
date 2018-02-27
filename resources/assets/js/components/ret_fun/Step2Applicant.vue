<script>
import {mapGetters} from 'vuex';
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
      applicant_gender: '',
      applicant_phone_numbers: [],
      applicant_cell_phone_numbers: [],
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
      applicant_types:['Beneficiario', 'Tutor', 'Apoderado'],
    }
  },
  created(){
    //this or define initial value  => [{ value:null }]
    this.addPhoneNumber();
    this.addCellPhoneNumber();
  },
  computed:{
    ...mapGetters({
            retfun: 'getData'
        }),
  },
  methods: {
    addPhoneNumber(){
      this.applicant_phone_numbers.push({value:null});
    },
    deletePhoneNumber(index){
      this.applicant_phone_numbers.splice(index,1);
      if(this.applicant_phone_numbers.length < 1)
        this.addPhoneNumber()
    },
    addCellPhoneNumber(){
      this.applicant_cell_phone_numbers.push({value:null});
    },
    deleteCellPhoneNumber(index){
      this.applicant_cell_phone_numbers.splice(index,1);
      if(this.applicant_cell_phone_numbers.length < 1)
        this.addCellPhoneNumber()
    },
    searchApplicant: function(){
      let ci= document.getElementsByName('applicant_identity_card')[0].value;
      axios.get('/search_ajax', {
        params: {
          ci
        }
      })
      .then( (response) => {
        let data = response.data;
        this.setDataApplicant(data);
        console.log(data);
        
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    searchLegalGuardian: function(){
      let ci= document.getElementsByName('legal_guardian_identity_card')[0].value;
      axios.get('/search_ajax', {
        params: {
          ci
        }
      })
      .then((response) => {
        let data = response.data;
        this.setDataLegalGuardian(data);
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    setDataApplicant(data){
      this.applicant_first_name = data.first_name;
      this.applicant_second_name = data.second_name;
      this.applicant_last_name = data.last_name;
      this.applicant_mothers_last_name = data.mothers_last_name;
      this.applicant_surname_husband = data.surname_husband;
      this.applicant_surname_husband = data.surname_husband,
      this.applicant_identity_card = data.identity_card;
      this.applicant_city_identity_card = data.city_identity_card_id;
      this.applicant_gender = data.gender;
      this.applicant_kinship = data.kinship_id;
      this.applicant_phone_numbers = data.phone_number;
      this.applicant_cell_phone_numbers = data.cell_phone_number;
    },
    setDataLegalGuardian(data){
      this.legal_guardian_first_name = data.first_name;
      this.legal_guardian_second_name = data.second_name;
      this.legal_guardian_last_name = data.last_name;
      this.legal_guardian_mothers_last_name = data.mothers_last_name;
      this.legal_guardian_surname_husband = data.surname_husband;
      this.legal_guardian_identity_card = data.identity_card;
      this.legal_guardian_city_identity_card = data.city_identity_card_id;
    },
    change_applicant: function() {
      // let modality_id_ = 

      let modality_id=this.retfun.modality_id;
      if(this.applicant_type  == '2'){
        this.show_advisor_form = !this.show_advisor_form;
        this.show_apoderado_form = false;
        this.resetAffiliate();
        return;
      }
      if(this.applicant_type  == '3'){
        this.show_apoderado_form = !this.show_apoderado_form;
        this.show_advisor_form = false;
        if(modality_id == 4){
          this.setDataSpouse();
        }else{
          this.setDataAffilate();
        }
        return;
      }
      if(this.applicant_type  == '1'){
        this.show_apoderado_form = false;
        this.show_advisor_form = false;
        if(modality_id == 4){
          this.setDataSpouse();
        }else{
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
      this.applicant_gender = '';
      this.applicant_kinship = '';
      this.applicant_cell_phone_numbers = [{value:null}]
      this.applicant_phone_numbers = [{value:null}];
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
        this.applicant_gender = this.affiliate.gender;
        this.applicant_phone_numbers = !! this.affiliate.phone_number ? this.parsePhone(this.affiliate.phone_number.split(',')) : [{value:null}];
        this.applicant_cell_phone_numbers = !! this.affiliate.cell_phone_number ? this.parsePhone(this.affiliate.cell_phone_number.split(',')) : [{value:null}];
        this.applicant_kinship = 1;
    },
    parsePhone(phones){
      return phones.map(phone => {
        return {
          value: phone.trim()
        }
      });
    },
    setDataSpouse: function(){
      this.applicant_first_name = this.spouse.first_name;
        this.applicant_second_name = this.spouse.second_name;
        this.applicant_last_name = this.spouse.last_name;
        this.applicant_mothers_last_name = this.spouse.mothers_last_name;
        this.applicant_surname_husband = this.spouse.surname_husband,
        this.applicant_identity_card = this.spouse.identity_card;
        this.applicant_city_identity_card = this.spouse.city_identity_card_id;
        this.applicant_gender = this.setSpouseGender();
        this.applicant_kinship = 2;
    },
    setSpouseGender(){
      return this.affiliate.gender == 'M' ? 'F' : 'M';
    }

  }
}
</script>