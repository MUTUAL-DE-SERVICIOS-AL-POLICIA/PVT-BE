<script>
import {mapGetters} from 'vuex';
export default {
  props:[
    'cities',
    'kinships',
    'spouse',
    'affiliate',
    'has_ret_fun'
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
      applicant_city_identity_card_id: '',
      applicant_kinship_id: '',
      applicant_birth_date: '',
      applicant_phone_number: '',
      applicant_cell_phone_number: '',
      advisor_name_court: '',
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
      legal_guardian_date_authority: '',
      legal_guardian_gender: null,
      legal_guardian_notary_of_public_faith: '',
      legal_guardian_notary: '',
      beneficiary_city_id: '',
      beneficiary_zone: '',
      beneficiary_street: '',
      beneficiary_number_address: '',
      applicant_type: null,
      show_advisor_form: false,
      show_apoderado_form: false,
      applicant_types:[{name:'Beneficiario', id:1}, {name:'Tutor', id:2}, {name:'Apoderado', id:3}],
      date_entry: this.affiliate.date_entry,
      date_derelict: this.affiliate.date_derelict,
      date_entry_reinstatement: this.affiliate.date_entry_reinstatement,
      date_derelict_reinstatement: this.affiliate.date_derelict_reinstatement,
      date_death: this.affiliate.date_death,
      reason_death: this.affiliate.reason_death,
      beneficiary_city_address_id: null,
      error:{
        applicant_identity_card: false,
      },
    }
  },
  mounted(){
    //this or define initial value  => [{ value:null }]
    console.log('date_derelict');
    console.log(this.affiliate.date_derelict);
    this.addPhoneNumber();
    this.addCellPhoneNumber();
  },
  computed:{
    ...mapGetters('retFunForm',{
        retFun: 'getData'
    }),
    applicantIsMale(){
      return this.applicant_gender == 'M';
    },
    kinshipsFilter(){
      return this.kinships.filter((k) => {
        return !(this.retFun.modality_id == 4 && k.id == 1);
      })
    },
    applicant_types_filter(){
      if (this.retFun.modality_id == 4 || this.retFun.modality_id == 1 || this.retFun.modality_id == 63 ) {
        return this.applicant_types;
      }
      return this.applicant_types.filter(item=>{
            return item.id  != 2;
      })
    },
    isDeathMode(){
      return (this.retFun.modality_id == 4 || this.retFun.modality_id == 1 || this.retFun.modality_id == 63 );
    },
    validDateDerelict(){
      return (this.retFun.modality_id == 62 ||  this.retFun.modality_id == 63 );
    }
  },
  methods: {
    addPhoneNumber(){
      if (this.applicant_phone_numbers.length > 0) {
        let last_phone = this.applicant_phone_numbers[this.applicant_phone_numbers.length-1];
        if (last_phone.value && !last_phone.value.includes('_')) {
          this.applicant_phone_numbers.push({value:null});
        }
      }else{
          this.applicant_phone_numbers.push({value:null});
      }
    },
    deletePhoneNumber(index){
      this.applicant_phone_numbers.splice(index,1);
      if(this.applicant_phone_numbers.length < 1)
        this.addPhoneNumber()
    },
    addCellPhoneNumber(){
      if (this.applicant_cell_phone_numbers.length > 0) {
        let last_phone = this.applicant_cell_phone_numbers[this.applicant_cell_phone_numbers.length-1];
        if (last_phone.value && !last_phone.value.includes('_')) {
          this.applicant_cell_phone_numbers.push({value:null});
        }
      }else{
          this.applicant_cell_phone_numbers.push({value:null});
      }
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
      this.applicant_city_identity_card_id = data.city_identity_card_id;
      this.applicant_gender = data.gender;
      this.applicant_kinship_id = data.kinship_id;
      
      this.applicant_birth_date = data.birth_date;
      this.applicant_phone_numbers = data.phone_number;
      this.applicant_cell_phone_numbers = data.cell_phone_number;
      console.log(this.applicant_birth_date+"<<<<this");
    },
    setDataLegalGuardian(data){
      this.legal_guardian_first_name = data.first_name;
      this.legal_guardian_second_name = data.second_name;
      this.legal_guardian_last_name = data.last_name;
      this.legal_guardian_mothers_last_name = data.mothers_last_name;
      this.legal_guardian_surname_husband = data.surname_husband;
      this.legal_guardian_identity_card = data.identity_card;
      this.legal_guardian_city_identity_card = data.city_identity_card_id;
      this.legal_guardian_gender = data.gender;
    },
    change_applicant: function() {
      // let modality_id_ = 
      let modality_id=this.retFun.modality_id;
      if(this.applicant_type  == '2'){
        this.show_advisor_form = !this.show_advisor_form;
        this.show_apoderado_form = false;
        this.resetAffiliate();
        return;
      }
      if(this.applicant_type  == '3'){
        this.show_apoderado_form = !this.show_apoderado_form;
        this.show_advisor_form = false;
        if(modality_id == 4 || modality_id == 1 || modality_id == 63){
          this.setDataSpouse();
        }else{
          this.setDataAffilate();
        }
        return;
      }
      if(this.applicant_type  == '1'){
        this.show_apoderado_form = false;
        this.show_advisor_form = false;
        if(modality_id == 4 || modality_id == 1 || modality_id == 63){
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
      this.applicant_city_identity_card_id = '';
      this.applicant_gender = '';
      this.applicant_kinship_id = '';
      this.applicant_birth_date = '';
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
        this.applicant_city_identity_card_id = this.affiliate.city_identity_card_id;
        this.applicant_gender = this.affiliate.gender;
        this.applicant_birth_date = this.affiliate.birth_date;
        this.applicant_kinship_id = 1;
        this.applicant_phone_numbers = !! this.affiliate.phone_number ? this.parsePhone(this.affiliate.phone_number.split(',')) : [{value:null}];
        this.applicant_cell_phone_numbers = !! this.affiliate.cell_phone_number ? this.parsePhone(this.affiliate.cell_phone_number.split(',')) : [{value:null}];
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
        this.applicant_city_identity_card_id = this.spouse.city_identity_card_id;
        this.applicant_gender = this.setSpouseGender();
        this.applicant_birth_date = this.spouse.birth_date;
        this.applicant_kinship_id = 2;
    },
    setSpouseGender(){
      return this.affiliate.gender == 'M' ? 'F' : 'M';
    }

  },
}
</script>