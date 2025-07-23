<script>
import {mapGetters} from 'vuex';
import { cellPhoneInputMaskAll, phoneInputMaskAll, monthYearInputMaskAll, dateInputMask, dateInputMaskAll }  from "../../helper.js";
export default {
  props:[
    'cities',
    'kinships',
    'spouse',
    'affiliate',
    'degrees'
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
      legal_guardian_notary_of_public_faith: '',
      legal_guardian_notary: '',
      beneficiary_zone: '',
      beneficiary_street: '',
      beneficiary_number_address: '',
      applicant_type: null,
      show_advisor_form: false,
      show_apoderado_form: false,
      applicant_types:['Beneficiario', 'Tutor', 'Apoderado'],
      date_death: this.affiliate.date_death,
      beneficiary_city_address_id: null,
      error:{
        applicant_identity_card: false,
      },
      spouse_identity_card:null ,
      spouse_city_identity_card_id:null ,
      spouse_first_name: null ,
      spouse_second_name: null ,
      spouse_last_name: null ,
      spouse_mothers_last_name: null ,
      spouse_surname_husband: null ,
      spouse_birth_date: null ,
      spouse_city_birth_id: null ,
      spouse_civil_status: null ,
      spouse_date_death: null ,
      spouse_reason_death: null ,
    }
  },
  mounted(){
    //this or define initial value  => [{ value:null }]
    this.addPhoneNumber();
    this.addCellPhoneNumber();
    monthYearInputMaskAll();
    dateInputMask();
    dateInputMaskAll();
    this.addSpouse();
  },
  computed:{
    ...mapGetters('quotaAidForm', {
        quotaAid: 'getData'
    }),
    applicantIsMale(){
      return this.applicant_gender == 'M';
    },
    kinshipsFilter(){
      return this.kinships.filter((k) => {
        return !(this.quotaAid.modality_id == 4 && k.id == 1);
      })
    },
    canAddDataSpouse(){
      setTimeout(() => {
        dateInputMaskAll();
      }, 300);
      if ([14, 15].includes(this.quotaAid.modality_id)) {
        return true;
      }
      if (this.quotaAid.modality_shortened) {
        return this.quotaAid.modality_shortened.includes(" FC") ||
          this.quotaAid.modality_shortened.includes(" FV");
      }
      return false;
      // return [14,15].includes(this.quotaAid.modality_id) || this.quotaAid.modality_shortened.includes(" FC") || this.quotaAid.modality_shortened.includes(" FV");
    },
    canAddDataAffiliate(){
      return [8,9,13].includes(this.quotaAid.modality_id);
    },
    age: function(){
        if(this.spouse_birth_date!=null){
            if (this.spouse_birth_date.includes('y') || this.spouse_birth_date.includes('m') || this.spouse_birth_date.includes('d') ) {
                return ''
            }

            if(this.spouse_birth_date){
                return moment(this.spouse_birth_date, "DD/MM/YYYY").fromNow(true)
            }else
            {
                return '';
            }
        }
    },
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
      setTimeout(() => {
        phoneInputMaskAll();
      }, 500);

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
      setTimeout(() => {
        cellPhoneInputMaskAll();
      }, 500);
    },
    addSpouse(){
      if(Object.entries(this.spouse).length === 0)
      {
        this.spouse_identity_card=null
        this.spouse_city_identity_card_id=null
        this.spouse_first_name=null
        this.spouse_second_name=null
        this.spouse_last_name=null
        this.spouse_mothers_last_name=null
        this.spouse_surname_husband=null
        this.spouse_birth_date=null
        this.spouse_city_birth_id=null
        this.spouse_civil_status=null
        this.spouse_date_death=null
        this.spouse_reason_death=null
      }
      else
      {
        this.spouse_identity_card=this.spouse.identity_card
        this.spouse_city_identity_card_id=this.spouse.city_identity_card_id 
        this.spouse_first_name=this.spouse.first_name 
        this.spouse_second_name=this.spouse.second_name 
        this.spouse_last_name=this.spouse.last_name 
        this.spouse_mothers_last_name=this.spouse.mothers_last_name 
        this.spouse_surname_husband=this.spouse.surname_husband
        this.spouse_birth_date=this.spouse.birth_date
        this.spouse_city_birth_id=this.spouse.city_birth_id
        this.spouse_civil_status=this.spouse.civil_status
        this.spouse_date_death=this.spouse.date_death
        this.spouse_reason_death=this.spouse.reason_death
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
      this.applicant_phone_numbers = !! data.phone_number ? data.phone_number : [{value:null}];
      this.applicant_cell_phone_numbers = !! data.cell_phone_number ? data.cell_phone_number : [{value:null}];
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
      cellPhoneInputMaskAll();
    phoneInputMaskAll();
      let modality_id=this.quotaAid.modality_id;
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