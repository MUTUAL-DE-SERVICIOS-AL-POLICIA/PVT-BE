<script>
import { mapGetters } from 'vuex';
import { cellPhoneInputMaskAll, phoneInputMaskAll, monthYearInputMaskAll, dateInputMask, dateInputMaskAll }  from "../../helper.js";
export default {
  props:[
    'cities',
    'kinships',
    'spouse',
    'affiliate',
    'degrees',
    'today'
  ],
  data(){
    return{
      contributor_first_name: '',
      contributor_second_name: '',
      contributor_last_name: '',
      contributor_mothers_last_name: '',
      contributor_surname_husband: '',
      contributor_identity_card: '',
      contributor_city_identity_card_id: '',
      contributor_kinship_id: '',
      contributor_birth_date: '',
      contributor_phone_number: '',
      contributor_cell_phone_number: '',
      advisor_name_court: '',
      advisor_resolution_number: '',
      advisor_resolution_date: '',
      contributor_gender: '',
      contributor_phone_numbers: [],
      contributor_cell_phone_numbers: [],
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
      show_advisor_form: false,
      show_apoderado_form: false,
      contributor_type_id: null,
      date_death: this.affiliate.date_death,
      error:{
        contributor_identity_card: false,
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
  },
  computed:{
    ...mapGetters('directContributionForm', {
      directContribution: 'getData',
    }),
    contributorIsMale(){
      return this.contributor_gender == 'M';
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
      return [14,15].includes(this.quotaAid.modality_id);
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
      if (this.contributor_phone_numbers.length > 0) {
        let last_phone = this.contributor_phone_numbers[this.contributor_phone_numbers.length-1];
        if (last_phone.value && !last_phone.value.includes('_')) {
          this.contributor_phone_numbers.push({value:null});
        }
      }else{
          this.contributor_phone_numbers.push({value:null});
      }
      setTimeout(() => {
        phoneInputMaskAll();
      }, 500);

    },
    deletePhoneNumber(index){
      this.contributor_phone_numbers.splice(index,1);
      if(this.contributor_phone_numbers.length < 1)
        this.addPhoneNumber()
    },
    addCellPhoneNumber(){
      if (this.contributor_cell_phone_numbers.length > 0) {
        let last_phone = this.contributor_cell_phone_numbers[this.contributor_cell_phone_numbers.length-1];
        if (last_phone.value && !last_phone.value.includes('_')) {
          this.contributor_cell_phone_numbers.push({value:null});
        }
      }else{
          this.contributor_cell_phone_numbers.push({value:null});
      }
      setTimeout(() => {
        cellPhoneInputMaskAll();
      }, 500);
    },
    deleteCellPhoneNumber(index){
      this.contributor_cell_phone_numbers.splice(index,1);
      if(this.contributor_cell_phone_numbers.length < 1)
        this.addCellPhoneNumber()
    },
    searchcontributor: function(){
      let ci= document.getElementsByName('contributor_identity_card')[0].value;
      axios.get('/search_ajax', {
        params: {
          ci
        }
      })
      .then( (response) => {
        let data = response.data;
        this.setDatacontributor(data);
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
    setDatacontributor(data){
      this.contributor_first_name = data.first_name;
      this.contributor_second_name = data.second_name;
      this.contributor_last_name = data.last_name;
      this.contributor_mothers_last_name = data.mothers_last_name;
      this.contributor_surname_husband = data.surname_husband;
      this.contributor_surname_husband = data.surname_husband,
      this.contributor_identity_card = data.identity_card;
      this.contributor_city_identity_card_id = data.city_identity_card_id;
      this.contributor_gender = data.gender;
      this.contributor_kinship_id = data.kinship_id;
      this.contributor_birth_date = data.birth_date;
      this.contributor_phone_numbers = data.phone_number;
      this.contributor_cell_phone_numbers = data.cell_phone_number;
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
    changeContributor: function() {
      // let modality_id_ = 
    //   cellPhoneInputMaskAll();
    // phoneInputMaskAll();
      // let modality_id=this.quotaAid.modality_id;
      if(this.contributor_type_id  == 1){
        this.setDataAffilate();
        return;
      }
      if(this.contributor_type_id  == 2){
        this.setDataSpouse();
        return;
      }
    },
    resetAffiliate: function () {
      this.contributor_first_name = '';
      this.contributor_second_name = '';
      this.contributor_last_name = '';
      this.contributor_mothers_last_name = '';
      this.contributor_surname_husband = '';
      this.contributor_surname_husband = '';
      this.contributor_identity_card = '';
      this.contributor_city_identity_card_id = '';
      this.contributor_gender = '';
      this.contributor_kinship_id = '';
      this.contributor_birth_date = '';
      this.contributor_cell_phone_numbers = [{value:null}]
      this.contributor_phone_numbers = [{value:null}];
    },
    setDataAffilate: function(){
        this.contributor_first_name = this.affiliate.first_name;
        this.contributor_second_name = this.affiliate.second_name;
        this.contributor_last_name = this.affiliate.last_name;
        this.contributor_mothers_last_name = this.affiliate.mothers_last_name;
        this.contributor_surname_husband = this.affiliate.surname_husband;
        this.contributor_surname_husband = this.affiliate.surname_husband,
        this.contributor_identity_card = this.affiliate.identity_card;
        this.contributor_city_identity_card_id = this.affiliate.city_identity_card_id;
        this.contributor_gender = this.affiliate.gender;
        this.contributor_birth_date = this.affiliate.birth_date;
        this.contributor_kinship_id = 1;
        this.contributor_phone_numbers = !! this.affiliate.phone_number ? this.parsePhone(this.affiliate.phone_number.split(',')) : [{value:null}];
        this.contributor_cell_phone_numbers = !! this.affiliate.cell_phone_number ? this.parsePhone(this.affiliate.cell_phone_number.split(',')) : [{value:null}];
    },
    parsePhone(phones){
      return phones.map(phone => {
        return {
          value: phone.trim()
        }
      });
    },
    setDataSpouse: function(){
      this.contributor_first_name = this.spouse.first_name;
        this.contributor_second_name = this.spouse.second_name;
        this.contributor_last_name = this.spouse.last_name;
        this.contributor_mothers_last_name = this.spouse.mothers_last_name;
        this.contributor_surname_husband = this.spouse.surname_husband,
        this.contributor_identity_card = this.spouse.identity_card;
        this.contributor_city_identity_card_id = this.spouse.city_identity_card_id;
        this.contributor_gender = this.setSpouseGender();
        this.contributor_birth_date = this.spouse.birth_date;
        this.contributor_kinship_id = 2;
    },
    setSpouseGender(){
      return this.affiliate.gender == 'M' ? 'F' : 'M';
    }

  },
}
</script>